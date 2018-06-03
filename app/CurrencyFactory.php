<?php
declare(strict_types=1);

namespace App;

class CurrencyFactory
{
    public function create(string $code): Currency
    {
        return new Currency($code);
    }
}