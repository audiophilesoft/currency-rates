<?php
declare(strict_types=1);

namespace App\Parser;

use Curl\Curl;

class ObmenkaUa extends AbstractParser
{
    use HtmlParserTrait;

    private const ATTRIBUTES = [
        'HRN/WMR' => [
            'data-system-with-currency-from' => 'Webmoney WMR',
            'data-system-with-currency-to' => 'Приват24 (авто) UAH'
        ],
        'HRN/WMZ' => [
            'data-system-with-currency-from' => 'Webmoney WMZ',
            'data-system-with-currency-to' => 'Приват24 (авто) UAH'
        ],
        'HRN/WMB' => [
            'data-system-with-currency-from' => 'Webmoney WMB',
            'data-system-with-currency-to' => 'Приват24 (авто) UAH'
        ]
    ];

    private const PARSER_PARAMS = [
        'url' => 'https://obmenka.ua/exchange-rates',
        'pattern' => '/([0-9]{1,2}\.[0-9]{1,4})/',
        'containing_tag' => 'tr',
        'depth' => 0
    ];


    public function __construct(CUrl $curl)
    {
        parent::__construct($curl);
        $this->initHtmlParserTrait();
    }


    public function doGet(string $code): ?float
    {

        switch ($code) {
            case 'HRN/WMZ':
                return $this->getData($code);
            case 'HRN/WMR':
                return 1/$this->getData($code);
            case 'HRN/WMB':
                return $this->getData($code);
        };

        return null;
    }

    private function getData($code): float
    {
        return $this->find(self::PARSER_PARAMS + ['attributes' => self::ATTRIBUTES[$code]])[0];
    }

}
