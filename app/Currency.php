<?php
declare(strict_types=1);

namespace App;

class Currency
{
    private $code;
    private $purchase;
    private $sale;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getPurchase(): float
    {
        return $this->purchase;
    }

    public function setPurchase(float $purchase): void
    {
        $this->purchase = $purchase;
    }

    public function getSale(): float
    {
        return $this->sale;
    }

    public function setSale(float $sale): void
    {
        $this->sale = $sale;
    }
}