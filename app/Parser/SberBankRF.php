<?php
declare(strict_types=1);

namespace App\Parser;

use App\CUrl;

class SberBankRF extends AbstractParser
{
    use HtmlParserTrait;

    private const PARSER_PARAMS = [
        'url' => 'https://www.sravni.ru/bank/sberbank-rossii/valjuty/',
        'pattern' => '/([0-9]{2},[0-9]{2}) руб\.\s*([0-9]{2},[0-9]{2})/',
        'containing_tag' => 'tr',
        'depth' => 0,
        'attributes' => [
            'class' => 'table-light__row'
        ]
    ];
    private const PATTERNS = [
        'RUB/DOL' => '/([0-9]{2},[0-9]{2}) руб\.\s*[0-9]{2},[0-9]{2}/',
        'DOL/RUB' => '/[0-9]{2},[0-9]{2} руб\.\s*([0-9]{2},[0-9]{2})/',
    ];

    public function __construct(CUrl $curl)
    {
        parent::__construct($curl);
        $this->initHtmlParserTrait();
    }

    public function doGet(string $code): ?float
    {
        switch ($code) {
            case 'RUB/DOL':
                return $this->getData($code);
            case 'DOL/RUB':
                return 1/$this->getData($code);
        };

        return null;
    }

    private function getData($code): float
    {
        return $this->find(self::PARSER_PARAMS + ['pattern' => self::PATTERNS[$code]])[0];
    }

}