<?php

namespace App\Writer;


use App\DataConverter;
use App\Settings;

interface WriterInterface
{

    public function write(array $currencies): void;

    public function init(): void;

}