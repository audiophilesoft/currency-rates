<?php
declare(strict_types=1);

namespace App;

use TKovrijenko\FloatFormatter\FormatterInterface;

class DataConverter
{
    private int $precision;
    private FormatterInterface $formatter;

    public function __construct(FormatterInterface $formatter, int $precision = 4)
    {
        $this->precision = $precision;
        $this->formatter = $formatter;
    }

    public function setPrecision(int $precision)
    {
        $this->precision = $precision;
    }

    public function floatsToStrings(array $floats)
    {
        return array_map(function (float $float) {
            return str_replace('.', ',', $this->formatter->format($float, $this->precision));
        }, $floats);
    }

}