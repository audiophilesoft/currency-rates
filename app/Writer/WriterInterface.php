<?php

namespace App\Writer;


interface WriterInterface
{
    public function write(array $currencies): void;
}