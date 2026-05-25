<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RatesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting, WithTitle, WithEvents
{
    protected $rates;

    public function __construct($rates)
    {
        $this->rates = $rates;
    }

    public function collection()
    {
        return $this->rates;
    }

    public function map($rate): array
    {
        return [
            $rate->pol ?? '—',
            $rate->pod ?? '—',
            $rate->liner ?? '—',
            $rate->container_type ?? '—',
            $rate->container_20,
            $rate->container_40,
            $rate->free_time ?? '—',
            $rate->valid_date ? Carbon::parse($rate->valid_date)->format('d M Y') : '—',
            $rate->notes ?? '—',
        ];
    }

    public function headings(): array
    {
        return [
            'POL',
            'POD',
            'LINER',
            'TYPE',
            '20 FT',
            '40 FT',
            'FREE TIME',
            'VALIDITY',
            'NOTES',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 15,
            'D' => 10,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 40,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',
            'F' => '#,##0',
        ];
    }

    public function title(): string
    {
        return 'Rates Data';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $lastColumn = 'I';
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11, 'name' => 'Calibri'],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        if ($highestRow > 1) {
            $sheet->getStyle('A2:' . $lastColumn . $highestRow)->applyFromArray([
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            ]);
            $sheet->getStyle('A2:H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'I';
                $sheet->setAutoFilter('A1:' . $lastColumn . $highestRow);
                $sheet->freezePane('A2');
                $footerRow = $highestRow + 2;
                $totalData = $this->rates->count();
                $sheet->setCellValue('A' . $footerRow, 'Total Data: ' . $totalData . ' Rates');
                $sheet->mergeCells('A' . $footerRow . ':D' . $footerRow);
                $userName = Auth::check() ? Auth::user()->name : 'System';
                $exportInfo = 'Exported by: ' . $userName . ' on ' . now()->format('d M Y H:i');
                $sheet->setCellValue('E' . $footerRow, $exportInfo);
                $sheet->mergeCells('E' . $footerRow . ':' . $lastColumn . $footerRow);
                $sheet->getStyle('A' . $footerRow . ':' . $lastColumn . $footerRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
