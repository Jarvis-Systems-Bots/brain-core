<?php

declare(strict_types=1);

$thisAutoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($thisAutoload)) {
    require $thisAutoload;
} else {
    $otherAutoload = __DIR__ . '/../../../autoload.php';
    if (file_exists($otherAutoload)) {
        require $otherAutoload;
    } else {

        fwrite(STDERR, "You must set up the project dependencies using Composer.\n");
        exit(1);
    }
}

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Console\Application;
use BrainCore\Foundation\Application as BrainApplication;
use BrainCore\Config\ConfigManager;
use Illuminate\Support\Facades\Facade;
use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\Adapter\PutenvAdapter;

defined("DS") || define("DS", DIRECTORY_SEPARATOR);
defined("OK") || define("OK", 0);
defined("ERROR") || define("ERROR", 1);

// Load environment variables
if (file_exists(__DIR__ . DS . '..' . DS . '..' . DS . '.env')) {
    $repository = RepositoryBuilder::createWithDefaultAdapters()
        ->addAdapter(PutenvAdapter::class)
        ->immutable()
        ->make();

    $dotenv = Dotenv::create($repository, __DIR__ . DS . '..' . DS . '..');
    $dotenv->load();
}

$container = new BrainApplication();

Container::setInstance($container);
$container->instance('app', $container);

// Enable facades and configuration repository
Facade::setFacadeApplication($container);
ConfigManager::boot($container);

$events = new Dispatcher($container);

$app = new Application($container, $events, \BrainCore\Support\Brain::version());

return $app;
