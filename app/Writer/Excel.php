<?php
declare(strict_types=1);

namespace App\Writer;

use App\DataConverter;
use App\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class Excel extends AbstractWriter
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var XlsxReader
     */
    private $reader;

    /**
     * @var XlsxWriter
     */
    private $writer;

    private const FILE_PATH = 'D:/test.xlsx';
    private const WORKSHEET_NAME = 'Курсы';
    private const FILE_TYPE = 'Xlsx';

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