<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Site;
use App\Product;
use App\Content\SiteMap;
use App\Content\ProductPage;
use App\Content\Cached\Loader;

class LoadProduct extends Command
{
    protected function configure()
    {
        $this
            ->setName('load-product')
            ->setDescription('Load products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dalSite = new Site\DAL;
        $sites = $dalSite->getAll();

        /** @var Site\Site $site */
        foreach ($sites as $site) {
            $output->writeln($site->getHost() . ' in progress');
            $this->parseSiteMap($output, $site, $site->getSiteMap());
        }
    }

    /**
     * @param OutputInterface $output
     * @param Site\Site $site
     * @param string $url
     */
    private function parseSiteMap(OutputInterface $output, Site\Site $site, string $url)
    {
        $loader = new Loader($url);
        $source = $loader->getSource();
        $parser = new SiteMap\Parser($source);
        if ($parser->parse()) {
            $output->writeln($url . ' has parsed');
            if ($parser->hasSiteMaps()) {
                foreach ($parser->getItems() as $siteMap) {
                    $this->parseSiteMap($output, $site, $siteMap);
                }
            } elseif ($parser->hasProducts()) {
                foreach ($parser->getItems() as $product) {
                    $output->writeln($product);
                    $this->saveProduct($output, $site, $product);
                }
            }
        }
    }

    /**
     * @param OutputInterface $output
     * @param Site\Site $site
     * @param string $url
     * @return string
     */
    private function saveProduct(OutputInterface $output, Site\Site $site, $url)
    {
        $parser = new ProductPage\Parser($site, new Loader($url));
        $product = $parser->parse();
        if ($product !== null) {
            try {
                (new Product\DAL())->create($product);
                $output->writeln($product->getId() . ' has saved');
            } catch (\Exception $exception) {
                if (strpos($exception->getMessage(), 'UNIQUE constraint failed')) {
                    $output->writeln('exists');
                }
            }
        }
    }
}