<?php

declare(strict_types=1);

namespace App;

use TKovrijenko\FloatFormatter\FormatterInterface;

class FloatToStringConverter
{
    private FormatterInterface $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function convert(array $floats, int $precision = 4)
    {
        return array_map(
            fn(float $float) => '=' . $this->formatter->format($float, $precision),
            $floats
        );
    }

}