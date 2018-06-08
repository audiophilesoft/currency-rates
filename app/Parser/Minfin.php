<?php
declare(strict_types=1);

namespace App\Parser;

use App\CUrl;

class Minfin extends AbstractParser
{
    use HtmlParserTrait;

    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.75 Safari/537.36 Vivaldi/1.0.219.3';

    private const PARSER_PARAMS_RUB = [
        'url' => 'http://minfin.com.ua/currency/auction/rub/buy/all/',
        'pattern' => '/([0-9]{1}\,[0-9]{3})/',
        'containing_tag' => 'small',
        'depth' => 3,
        'attributes' => [
            'class' => 'au-mid-buysell--title'
        ]
    ];

    private const PARSER_PARAMS_DOL = [
        'url' => 'http://minfin.com.ua/currency/auction/usd/buy/all/',
        'pattern' => '/([0-9]{2}\,[0-9]{2})/',
        'containing_tag' => 'small',
        'depth' => 3,
        'attributes' => [
            'class' => 'au-mid-buysell--title'
        ]
    ];

    private $dol_data;
    private $rub_data;


    public function __construct(CUrl $curl)
    {
        parent::__construct($curl);
        $this->configure();
    }


    private function configure()
    {
        ini_set("user_agent", self::USER_AGENT);
        $this->initHtmlParserTrait();
    }


    public function doGet(string $code): ?float
    {
        switch ($code) {
            case 'HRN/RUB':
                return $this->getRUBData()[0];
            case 'RUB/HRN':
                return 1 / $this->getRUBData()[1];
            case 'HRN/DOL':
                return $this->getDOLData()[0];
            case 'DOL/HRN':
                return 1 / $this->getDOLData()[1];
        }

        return null;
    }


    private function getDOLData(): array
    {
        return $this->dol_data ?? $this->dol_data = $this->find(self::PARSER_PARAMS_DOL);
    }


    private function getRUBData(): array
    {
        return $this->rub_data ?? $this->rub_data = $this->find(self::PARSER_PARAMS_RUB);
    }

}