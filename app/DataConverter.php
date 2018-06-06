<?php

namespace App;

class DataConverter
{
    public function floatsToStrings(array $floats)
    {
        return array_map(function (float $float) {
            return str_replace('.', ',', (string)round($float, (int)(3 - floor(log10($float)))));
        }, $floats);
    }

}