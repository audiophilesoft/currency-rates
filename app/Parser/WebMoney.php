<?php
declare(strict_types=1);

namespace App\Parser;


use Curl\Curl;

class WebMoney extends AbstractParser
{
    protected const URL = 'https://wm.exchanger.ru/asp/XMLWMList.asp?exchtype=';
    protected const WMU_WMZ = 8;
    protected const WMU_WMR = 10;
    protected const WMU_WMB = 18;
    private const CURL_OPTIONS = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSLVERSION => 4
    ];

    public function __construct(CUrl $curl)
    {
        parent::__construct($curl);
        $this->reconfigureCurl();
    }

    private function reconfigureCurl()
    {
        $class = get_class($this->curl);
        $this->curl = new $class();
        foreach(self::CURL_OPTIONS as $option => $value) {
            $this->curl->setOpt($option, $value);
        }
    }


    private function getCourse(int $type, string $attr = 'inoutrate'): float
    {
        $xml = new \SimpleXMLElement($this->curl->get(self::URL . $type));
        return (float)str_replace(',', '.', (string)$xml->WMExchnagerQuerys->query[1][$attr]);
    }

    public function doGet(string $code): ?float
    {
        switch ($code) {
            case 'WMU/WMZ':
                return $this->getCourse(self::WMU_WMZ);
            case 'WMU/WMR':
                return $this->getCourse(self::WMU_WMR);
            case 'WMU/WMB':
                return $this->getCourse(self::WMU_WMB);
        }

        return null;
    }


}