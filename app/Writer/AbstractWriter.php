<?php
declare(strict_types=1);

namespace App\Writer;

use App\{
    DataConverter, Settings
};

abstract class AbstractWriter implements WriterInterface
{
    protected $settings;
    protected $data_converter;
    protected $is_initialized = false;

    public function __construct(Settings $settings, DataConverter $data_converter)
    {
        $this->settings = $settings;
        $this->data_converter = $data_converter;

    }

    public function init(): void
    {
        $this->doInit();
        $this->is_initialized = true;
    }

    abstract protected function doInit(): void;

    public function write(array $currencies): bool
    {
        if ($this->is_initialized !== true) {
            throw new \Exception('You must initialize writer first');
        }

        return $this->doWrite($currencies);
    }


    abstract protected function doWrite(array $currencies): bool;
}