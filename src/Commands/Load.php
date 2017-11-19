<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Config;

class Load extends Command
{
    protected function configure()
    {
        $this
            ->setName('load')
            ->setDescription('Load prices');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToDataSet = $input->getArgument('data-set');
        $output->writeln($pathToDataSet);
    }
}