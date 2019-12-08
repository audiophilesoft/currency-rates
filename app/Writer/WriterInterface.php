<?php
declare(strict_types=1);

namespace App\Writer;

use App\DataConverter;
use App\Settings;

interface WriterInterface
{
    public function __construct(string $filePath, Settings $settings, DataConverter $dataConverter);

    public function write(array $currencies): void;
}