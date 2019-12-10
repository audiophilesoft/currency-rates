<?php

declare(strict_types=1);

namespace App\Writer;

use App\{
    FloatToStringConverter, Settings
};
use PhpOffice\PhpSpreadsheet\{Cell\DataType,
    Reader\Xlsx as XlsxReader,
    Spreadsheet,
    Worksheet\Worksheet,
    Writer\Xlsx as XlsxWriter
};

class Excel extends AbstractWriter
{
    private Spreadsheet $spreadsheet;
    private XlsxReader $reader;
    private XlsxWriter $writer;
    private string $filePath;

    public function __construct(
        $filePath,
        Settings $settings,
        FloatToStringConverter $converter,
        XlsxReader $reader,
        XlsxWriter $writer
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->filePath = $filePath;
        parent::__construct($filePath, $settings, $converter);
    }

    protected function init(string $filePath): void
    {
        $this->spreadsheet = $this->reader->load($filePath);
        $this->writer->setSpreadsheet($this->spreadsheet);
    }

    private function getWorksheet(): Worksheet
    {
        return $this->spreadsheet->getSheet(0);
    }

    private function saveFile(): void
    {
        $this->writer->save($this->filePath);
    }

    public function write(array $currencies): void
    {
        $strings = $this->converter->convert($currencies);
        $worksheet = $this->getWorksheet();

        $row = $worksheet->getHighestRow() + 1;

        $dateColumn = $this->settings->get('date_column');
        $date = (new \DateTime)->format('d.m.Y');
        $worksheet->setCellValueByColumnAndRow($dateColumn, $row, $date);

        $columnsMap = $this->settings->get('columns_map');

        for ($column = $dateColumn + 1; $column <= $this->settings->get('last_column'); $column++) {
            if ($currency = array_search($column, $columnsMap, true)) {
                $worksheet->setCellValueByColumnAndRow($column, $row, $strings[$currency]);
            } else {
                $worksheet->setCellValueByColumnAndRow(
                    $column,
                    $row,
                    $worksheet->getCellByColumnAndRow($column, $row - 1)
                );
            }
        }

        $this->saveFile();
    }

}