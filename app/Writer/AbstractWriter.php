<?php

declare(strict_types=1);

namespace App\Writer;

use App\{
    FloatToStringConverter, Settings
};

abstract class AbstractWriter implements WriterInterface
{
    protected Settings $settings;
    protected FloatToStringConverter $converter;

    public function __construct($filePath, Settings $settings, FloatToStringConverter $converter)
    {
        $this->init($filePath);
        $this->settings = $settings;
        $this->converter = $converter;
    }

    abstract protected function init(string $filePath): void;

    abstract public function write(array $currencies): void;

}