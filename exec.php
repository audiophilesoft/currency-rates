<?php
declare(strict_types = 1);

require_once 'vendor\autoload.php';

try {
    $container = new \DI\Container;
    $kernel = $container->get(\App\Kernel::class);
    $kernel->run();
} catch (\Throwable $e) {
    echo $e;
}

