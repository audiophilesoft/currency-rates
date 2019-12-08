<?php

declare(strict_types=1);

namespace App\Writer;

use SplFileObject;

class Text extends AbstractWriter
{
    private SplFileObject $file;

    protected function init(string $filePath): void
    {
        $this->file = new SplFileObject($filePath, 'a+');
    }

    public function write(array $currencies): void
    {
        $strings = $this->dataConverter->floatsToStrings($currencies);
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

        $this->file->fwrite($formatted); // todo: handle errors
    }
}