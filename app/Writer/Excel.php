<?php
declare(strict_types=1);

namespace App\Writer;

use App\{
    DataConverter, Settings
};
use PhpOffice\PhpSpreadsheet\{
    Reader\Xlsx as XlsxReader, Spreadsheet, Worksheet\Worksheet, Writer\Xlsx as XlsxWriter
};

class Excel extends AbstractWriter
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;
    private $reader;
    private $writer;

    private const FILE_PATH = 'D:/test.xlsx';
    private const WORKSHEET_NAME = 'Курсы';

    public function __construct(Settings $settings, DataConverter $data_converter, XlsxReader $reader, XlsxWriter $writer)
    {
        parent::__construct($settings, $data_converter);
        $this->reader = $reader;
        $this->writer = $writer;
    }


    public function doInit(): void
    {
        $this->spreadsheet =  $this->reader->load(self::FILE_PATH);
        $this->writer->setSpreadsheet($this->spreadsheet);
    }

    private function getWorksheet(): Worksheet
    {
        return $this->spreadsheet->getSheetByName(self::WORKSHEET_NAME);
    }

    private function saveFile()
    {
        $this->writer->save(self::FILE_PATH);
    }


    public function doWrite(array $currencies): void
    {
        $worksheet = $this->getWorksheet();
        $last_row = $worksheet->getHighestRow();
        $date = (new \DateTime)->format('d.m.Y');
        $worksheet->setCellValueByColumnAndRow(1, $last_row+1, $date);
        $this->saveFile();
    }

}