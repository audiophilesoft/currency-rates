<?php
declare(strict_types=1);

namespace App;

class DataConverter
{
    private int $precision;

    public function __construct(int $precision = 4)
    {
        $this->precision = $precision;
    }

    public function setPrecision(int $precision)
    {
        $this->precision = $precision;
    }


    public function floatsToStrings(array $floats)
    {
        return array_map(function (float $float) {
            return str_replace('.', ',', (string)round($float, (int)($this->precision - 1 - floor(log10($float)))));
        }, $floats);
    }

}