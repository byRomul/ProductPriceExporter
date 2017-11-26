<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands;

$application = new Application();
$application->add(new Commands\Init());
$application->add(new Commands\Learn());
$application->add(new Commands\LoadProduct());
$application->add(new Commands\LoadPrice());
$application->add(new Commands\Export());
$application->run();