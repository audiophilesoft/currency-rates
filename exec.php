<?php
declare(strict_types = 1);

require_once 'vendor/autoload.php';

try {

    $di_config = require('config/di.php');
    $builder = new \DI\ContainerBuilder;
    $builder->addDefinitions($di_config);

    $container = $builder->build();
    $kernel = $container->get(\App\Kernel::class);
    $kernel->run();

} catch (\Throwable $e) {
    echo $e;
}

