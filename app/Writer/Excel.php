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
    private Spreadsheet $spreadsheet;
    private XlsxReader $reader;
    private XlsxWriter $writer;

    protected const FILE_EXTENSION = 'xlsx';
    private const WORKSHEET_NAME = 'Курсы';

    public function __construct(
        Settings $settings,
        DataConverter $dataConverter,
        XlsxReader $reader,
        XlsxWriter $writer
    ) {
        parent::__construct($settings, $dataConverter);
        $this->reader = $reader;
        $this->writer = $writer;
    }


    public function doInit(): void
    {
        $this->spreadsheet = $this->reader->load(self::FILE_PATH);
        $this->writer->setSpreadsheet($this->spreadsheet);
    }


    private function getWorksheet(): Worksheet
    {
        return $this->spreadsheet->getSheetByName(self::WORKSHEET_NAME);
    }


    private function saveFile(): void
    {
        $this->writer->save(self::FILE_PATH);
    }


    public function write(array $currencies): void
    {
        $worksheet = $this->getWorksheet();
        $last_row = $worksheet->getHighestRow();
        $date = (new \DateTime)->format('d.m.Y');
        $worksheet->setCellValueByColumnAndRow(1, $last_row + 1, $date);

        $this->saveFile();
    }

}