<?php
declare(strict_types=1);

namespace App\Parser;

use App\CUrl;

abstract class AbstractParser implements ParserInterface
{
    protected $curl;

    public function __construct(CUrl $curl)
    {
        $this->curl = $curl;
    }

    public function get(string $code): float
    {
        if(null === $currency = $this->doGet($code)) {
            throw new \Exception('No such currency');
        }

        return $currency;
    }

    abstract public function doGet(string $code): ?float;
}