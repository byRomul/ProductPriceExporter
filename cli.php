<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands;

$application = new Application();
$application->add(new Commands\Init());
$application->add(new Commands\Learn());
$application->add(new Commands\Load());
$application->run();