<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\App;
use App\Site;
use App\Site\Pattern;
use App\Product;
use App\Product\Price;

class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Init new instance of price analyzer');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToDatabase = App::instance()->config('pathToDatabase');
        if (file_exists($pathToDatabase)) {
            unlink($pathToDatabase);
        }
        $dals = [
            new Site\DAL(),
            new Pattern\DAL(),
            new Product\DAL(),
            new Price\DAL(),
        ];
        /** @var \App\DAL $dal */
        foreach ($dals as $dal) {
            if (!$dal->createTable()) {
                $output->writeln('Fail to create table ' . $dal->tableName());
                return 1;
            }
        }
        return 0;
    }
}