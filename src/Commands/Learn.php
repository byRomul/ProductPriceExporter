<?php

namespace App\Commands;

use App\Content\Analyzer;
use App\Example;
use App\Site;
use App\Content\Analyzer\DataSet;
use App\Site\Pattern;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Learn extends Command
{
    protected function configure()
    {
        $this
            ->setName('learn')
            ->setDescription('Learn data set of product pages')
            ->addArgument('data-set', InputArgument::REQUIRED, 'File with data set in csv format');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToDataSet = $input->getArgument('data-set');

        if (!file_exists($pathToDataSet)) {
            $output->writeln('Not found ' . $pathToDataSet . ' file');
            return 1;
        }

        $csv = fopen($pathToDataSet, 'r');
        if ($csv === false) {
            $output->writeln('Wrong file ' . $pathToDataSet);
            return 2;
        }

        $dalSite = new Site\DAL();

        $sites = [];
        /** @var DataSet[] $dataSets */
        $dataSets = [];
        while (($data = fgetcsv($csv, 0, ';')) !== false) {
            $example = Example::build($data);
            $partOfUrl = parse_url($example->getUrl());
            if (isset($partOfUrl['host'])) {
                if (isset($sites[$partOfUrl['host']])) {
                    $site = $sites[$partOfUrl['host']];
                } else {
                    $site = $dalSite->getSiteByHost($partOfUrl['host']);
                    if ($site === false) {
                        $site = new Site\Site();
                        $site->setScheme($partOfUrl['scheme']);
                        $site->setHost($partOfUrl['host']);
                        $site->setSiteMap(Site\Utils::findSiteMap($site));
                        $site->setCharset(Site\Utils::findCharset($site));
                        $output->writeln('Find new site ' . $site->getHost());
                        $validator = new Site\Validator($site);
                        if ($validator->isValid() !== true) {
                            $output->writeln($site->getHost() . ' : ' . $validator->getLastError());
                            return 3;
                        }
                        $dalSite->create($site);
                    }
                    $sites[$partOfUrl['host']] = $site;
                }
                if (!isset($dataSets[$site->getHost()])) {
                    $dataSets[$site->getHost()] = new DataSet($site);
                }
                $dataSets[$site->getHost()]->add($example);
            }
        }
        $output->writeln('Found ' . count($dataSets) . ' data sets');

        if (count($dataSets)) {
            $dalPattern = new Pattern\DAL();
            foreach ($dataSets as $dataSet) {
                $output->writeln('Site ' . $dataSet->getSite()->getHost() . ' in progress [' . count($dataSet) . ' examples]');
                $dalPattern->deleteBySiteId($dataSet->getSite()->getId());
                $output->writeln('Clean patterns for ' . $dataSet->getSite()->getHost());
                $analyzer = new Analyzer($dataSet);
                $patterns = $analyzer->research();
                $output->writeln('Patterns were found for ' . $dataSet->getSite()->getHost());
                foreach ($patterns as $data) {
                    $pattern = new Pattern\Pattern();
                    $pattern->setSiteId($dataSet->getSite()->getId());
                    $pattern->setName($data['name']);
                    $pattern->setLeft($data['left']);
                    $pattern->setRight($data['right']);
                    $dalPattern->create($pattern);
                    $output->writeln('Pattern "' . $pattern->getName() . '" is saved');
                }
            }
        }
        return 0;
    }
}