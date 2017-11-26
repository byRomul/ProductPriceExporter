<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Site;
use App\Product;
use App\Product\Price;
use App\Content\ProductPage;
use App\Content\Cached\Loader;

class LoadPrice extends Command
{
    protected function configure()
    {
        $this
            ->setName('load-price')
            ->setDescription('Load prices')
            ->addArgument('rev', InputArgument::REQUIRED, 'Revision');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dalSite = new Site\DAL;
        $rows = $dalSite->getAll();
        $sites = [];
        foreach ($rows as $row) {
            $sites[$row->getId()] = $row;
        }

        $offset = 0;
        $limit = 100;
        $revId = (int)$input->getArgument('rev');
        $dalProduct = new Product\DAL;
        while (true) {
            $products = $dalProduct->getAll($offset, $limit);
            if ($products === false || count($products) === 0) {
                break;
            }
            /** @var Product\Product $product */
            foreach ($products as $product) {
                $output->writeln($product->getUrl() . ' in progress');
                if (isset($sites[$product->getSiteId()])) {
                    $this->parsePrice($output, $sites[$product->getSiteId()], $product, $revId);
                } else {
                    $output->writeln('Unknown site ' . $product->getSiteId());
                }
            }
            $offset += $limit;
        }
    }

    /**
     * @param OutputInterface $output
     * @param Site\Site $site
     * @param Product\Product $product
     * @param int $revId
     */
    private function parsePrice(OutputInterface $output, Site\Site $site, $product, int $revId)
    {
        $parser = new ProductPage\Parser($site, new Loader($product->getUrl()));
        $parsedProduct = $parser->parse();
        if ($parsedProduct !== null) {
            $price = new Price\Price();
            $price->setProductId($product->getId());
            $price->setRevId($revId);
            $price->setPrice($parsedProduct->getPrice());
            try {
                (new Price\DAL())->create($price);
                $output->writeln($product->getId() . ' has saved');
            } catch (\Exception $exception) {
                if (strpos($exception->getMessage(), 'UNIQUE constraint failed')) {
                    $output->writeln('exists');
                }
            }

        }
    }
}