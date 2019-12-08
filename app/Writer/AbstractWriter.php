<?php
declare(strict_types=1);

namespace App\Writer;

use App\{
    DataConverter, Settings
};

abstract class AbstractWriter implements WriterInterface
{
    protected Settings $settings;
    protected DataConverter $dataConverter;

    public function __construct($filePath, Settings $settings, DataConverter $dataConverter)
    {
        $this->init($filePath);
        $this->settings = $settings;
        $this->dataConverter = $dataConverter;
    }

    abstract protected function init(string $filePath): void;

    abstract public function write(array $currencies): void;

}