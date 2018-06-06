<?php
declare(strict_types=1);

namespace App\Writer;


class Text extends AbstractWriter
{

    /**
     * @var \SplFileObject
     */
    private $file;


    public function init(): void
    {
        $this->file = new \SplFileObject($this->settings->get('txt_path'), 'a+');
    }


    public function write(array $currencies): void
    {
        $strings = $this->data_converter->floatsToStrings($currencies);
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

}