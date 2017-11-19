<?php

namespace App\Commands;

use App\Content\Analyzer;
use App\Example;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Site;

class Learn extends Command
{
    protected function configure()
    {
        $this
            ->setName('learn')
            ->setDescription('Learn data set of product pages')
            ->addArgument('data-set',  InputArgument::REQUIRED, 'File with data set in csv format');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToDataSet = $input->getArgument('data-set');

        if (!file_exists($pathToDataSet)) {
            $output->writeln('Not found file ' . $pathToDataSet);
            return false;
        }

        $csv = fopen($pathToDataSet, 'r');
        if ($csv === false) {
            $output->writeln('Wrong file ' . $pathToDataSet);
            return false;
        }

        $dalSite = new Site\DAL();

        $sites = [];
        while (($data = fgetcsv($csv, 0, ';')) !== false) {
            $example = Example::build($data);
            $partOfUrl = parse_url($example->getUrl());var_dump($partOfUrl);
            if (isset($partOfUrl['host'])) {
                if (isset($sites[$partOfUrl['host']])) {
                    $site = $sites[$partOfUrl['host']];
                } else {
                    $site = $dalSite->getSiteByHost($partOfUrl['host']);
                    if ($site === false) {
                        $site = new Site\Site();
                        $site->setScheme($partOfUrl['scheme']);
                        $site->setHost($partOfUrl['host']);
                        $dalSite->create($site);
                    }
                    $validator = new Site\Validator($site);
                    if ($validator->isValid() !== true) {
                        $output->writeln('Wrong site ' . $site->getHost());
                        return false;
                    }
                    $sites[$partOfUrl['host']] = $site;
                }
                $analyzer = new Analyzer($site, $example);
                $analyzer->research();
            }
            break;
        }
    }
}