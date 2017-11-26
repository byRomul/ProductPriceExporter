<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Product;

class Export extends Command
{
    protected function configure()
    {
        $this
            ->setName('export')
            ->setDescription('Export product')
            ->addArgument('rev', InputArgument::REQUIRED, 'Revision');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $offset = 0;
        $limit = 100;
        $revId = (int)$input->getArgument('rev');
        $dalProduct = new Product\DAL;
        while (true) {
            $products = $dalProduct->getAllWithPrice($revId, $offset, $limit);
            if ($products === false || count($products) === 0) {
                break;
            }
            /** @var Product\Product $product */
            foreach ($products as $product) {
                $output->writeln(implode(';', [$product->getUrl(), $product->getTitle(), $product->getPrice()]));
            }
            $offset += $limit;
        }
    }
}