<?php
declare(strict_types = 1);

namespace App\Parser;

class PrivatBank extends AbstractParser
{
    const URL = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
    const RUR_NUM = 2;
    const USD_NUM = 0;

    private function getCourse(int $arr_num): array
    {
        $arr = json_decode(file_get_contents(self::URL), true);
        return [(float)$arr[$arr_num]['buy'], (float)$arr[$arr_num]['sale']];
    }


    public function doGet(string $code): ?float
    {
        switch ($code) {
            case 'HRN/USD':
                return $this->getCourse(self::USD_NUM)[0];
            case 'HRN/RUR':
                return $this->getCourse(self::RUR_NUM)[0];
        }

        return null;
    }
}