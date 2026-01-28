<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\Auth;

class SummariesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $performanceData;
    protected $selectedMonth;

    public function __construct($performanceData, $selectedMonth)
    {
        $this->performanceData = $performanceData;
        $this->selectedMonth = $selectedMonth;
    }

    public function collection()
    {
        $rows = [];
        foreach ($this->performanceData as $data) {
            $rows[] = [
                $data['name'],
                $data['activities']['performance']['actual'],
                $data['activities']['performance']['remaining'],
                min($data['activities']['performance']['percentage'], 100) . '%',
                $data['volume']['performance']['actual'],
                $data['volume']['performance']['remaining'],
                min($data['volume']['performance']['percentage'], 100) . '%',
                $data['profit']['performance']['actual'],
                $data['profit']['performance']['remaining'],
                min($data['profit']['performance']['percentage'], 100) . '%',
            ];
        }
        return collect($rows);
    }

    public function headings(): array
    {
        return [
            ['NAME', 'ACTIVITY', 'ACTIVITY', 'ACTIVITY', 'VOLUME', 'VOLUME', 'VOLUME', 'PROFIT', 'PROFIT', 'PROFIT'],
            ['', 'ACT', 'REM', '%', 'ACT', 'REM', '%', 'ACT', 'REM', '%'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 12, 'C' => 12, 'D' => 12,
            'E' => 12, 'F' => 12, 'G' => 12,
            'H' => 15, 'I' => 15, 'J' => 12,
        ];
    }

    public function title(): string
    {
        return 'Summary Activities';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $lastColumn = 'J';
        $sheet->mergeCells('A1:A2');
        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11, 'name' => 'Calibri'],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        foreach (['B1:D1', 'E1:G1', 'H1:J1'] as $range) {
            $sheet->mergeCells($range);
            $sheet->getStyle($range)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11, 'name' => 'Calibri'],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            ]);
        }
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getStyle('B2:J2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10, 'name' => 'Calibri'],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '555555']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);
        if ($highestRow > 2) {
            $sheet->getStyle('A3:' . $lastColumn . $highestRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            ]);
            $sheet->getStyle('A3:A' . $highestRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            foreach(['B','C', 'E','F', 'H','I'] as $col) {
                $sheet->getStyle($col . '3:' . $col . $highestRow)
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');
            }
            $sheet->getStyle('H3:I' . $highestRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);
        }
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $numericCols = ['B', 'C', 'E', 'F', 'H', 'I'];
                for ($row = 3; $row <= $highestRow; $row++) {
                    foreach ($numericCols as $col) {
                        $cell = $sheet->getCell($col . $row);
                        $val = $cell->getValue();
                        if ($val === null || $val === '' || $val === 0 || $val === '0') {
                            $sheet->setCellValueExplicit(
                                $col . $row, 
                                0, 
                                DataType::TYPE_NUMERIC
                            );
                        }
                    }
                }
                $sheet->freezePane('A3');
                $footerRow = $highestRow + 2;
                $periodInfo = 'Period: ' . $this->selectedMonth;
                $sheet->setCellValue('A' . $footerRow, $periodInfo);
                $sheet->mergeCells('A' . $footerRow . ':E' . $footerRow);
                $userName = Auth::check() ? Auth::user()->name : 'System';
                $exportInfo = 'Exported by: ' . $userName . ' on ' . now()->format('d M Y H:i');
                $sheet->setCellValue('F' . $footerRow, $exportInfo);
                $sheet->mergeCells('F' . $footerRow . ':J' . $footerRow);
                $sheet->getStyle('A' . $footerRow . ':J' . $footerRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
