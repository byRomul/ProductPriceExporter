<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\App;
use App\Site;

class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Init new instance of price analyzer');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToDatabase = App::instance()->config('pathToDatabase');
        if (file_exists($pathToDatabase)) {
            unlink($pathToDatabase);
        }
        $dalSite = new Site\DAL();
        if (!$dalSite->createTable()) {
            $output->writeln('Fail to create table ' . Site\DAL::class);
            return false;
        }

        if (!file_exists(App::instance()->config('pathToCache'))) {
            mkdir(App::instance()->config('pathToCache'));
        }
    }
}