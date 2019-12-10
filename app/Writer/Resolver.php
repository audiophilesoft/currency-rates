<?php

declare(strict_types=1);

namespace App\Writer;

use DI\FactoryInterface;

class Resolver
{
    private array $map;
    private FactoryInterface $serviceContainer;

    public function __construct(
        array $map,
        FactoryInterface $serviceContainer
    ) {
        $this->map = $map;
        $this->serviceContainer = $serviceContainer;
    }

    public function get(string $format, array $arguments): WriterInterface
    {
        if (!array_key_exists($format, $this->map)) {
            throw new \Exception(
                'This format is not supported, choose one of these: ' . implode(', ', array_keys($this->map))
            );
        }
        $class = $this->map[$format];

        return $this->serviceContainer->make($class, $arguments);
    }

}