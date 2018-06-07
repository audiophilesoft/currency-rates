<?php
declare(strict_types=1);

namespace App\Writer;


interface WriterInterface
{
    public function write(array $currencies): void;

    public function init(): void;

}