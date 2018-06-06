<?php
declare(strict_types=1);

namespace App\Writer;

use App\DataConverter;
use App\Settings;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel extends AbstractWriter
{
    private $worksheet;

    private const FILE_PATH = 'D:/test.xlsx';
    private const WORKSHEET_NAME = 'Курсы';

    public function init(): void
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile(self::FILE_PATH);
        $reader->setLoadSheetsOnly(self::WORKSHEET_NAME);
        $this->worksheet = $reader->load(self::FILE_PATH)->getActiveSheet();
    }


    public function write(array $currencies): void
    {
        $x =1;
    }

    private static function floatsToStrings(array $floats)
    {
        return array_map(function (float $float) {
            return str_replace('.', ',', (string)round($float, (int)(3 - floor(log10($float)))));
        }, $floats);

    }

}