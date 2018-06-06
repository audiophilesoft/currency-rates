<?php
declare(strict_types=1);

namespace App\Writer;

use App\DataConverter;
use App\Settings;

abstract class AbstractWriter implements WriterInterface
{
    protected $settings;
    protected $data_converter;

    public function __construct(Settings $settings, DataConverter $data_converter)
    {
        $this->settings = $settings;
        $this->data_converter = $data_converter;

    }
}