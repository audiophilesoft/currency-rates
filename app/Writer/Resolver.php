<?php

declare(strict_types=1);

namespace App\Writer;

use DI\FactoryInterface;

class Resolver
{
    private array $map;
    private FactoryInterface $serviceContainer;

    public function __construct(array $map,
        FactoryInterface $serviceContainer)
    {
        $this->map = $map;
        $this->serviceContainer = $serviceContainer;
    }

    public function get(string $format, array $arguments): WriterInterface
    {
        $class = $this->map[$format];
        return $this->serviceContainer->make($class, $arguments);
    }

}