<?php
declare(strict_types=1);

namespace App\Writer;

use App\FloatToStringConverter;
use App\Settings;

interface WriterInterface
{
    public function write(array $currencies): void;
}