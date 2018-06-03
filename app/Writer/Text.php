<?php
declare(strict_types=1);

namespace App\Writer;


use App\Settings;

class Text implements WriterInterface
{
    private $settings;

    /**
     * @var \SplFileObject
     */
    private $file;


    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->init();
    }


    private function init()
    {
        $this->file = new \SplFileObject($this->settings->get('txt_path'), 'a+');
    }


    public function write(array $currencies): void
    {
        $strings = self::floatsToStrings($currencies);
        $format = (new \DateTime())->format('d-m-Y') . "\r\n=%s\t=%s\t=%s\t=%s\r\n=%s\t=%s\t=%s\t=%s\t=%s\r\n=%s\t=%s\r\n\r\n";

        $formatted = sprintf($format,
            $strings['HRN/DOL'],
            $strings['HRN/RUB'],
            $strings['HRN/USD'],
            $strings['HRN/RUR'],
            $strings['HRN/WMZ'],
            $strings['HRN/WMR'],
            $strings['HRN/WMB'],
            $strings['DOL/HRN'],
            $strings['DOL/RUB'],
            $strings['RUB/HRN'],
            $strings['RUB/DOL']
        );

        $this->file->fwrite($formatted);
    }


    private static function floatsToStrings(array $floats)
    {
        return array_map(function (float $float) {
            return str_replace('.', ',', (string)round($float, (int)(3 - floor(log10($float)))));
        }, $floats);

    }

}