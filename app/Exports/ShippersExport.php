<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Auth;

class ShippersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $shippers;

    public function __construct($shippers)
    {
        $this->shippers = $shippers;
    }

    public function collection()
    {
        return $this->shippers;
    }

    public function map($shipper): array
    {
        return [
            $shipper->shipper_name ?? '—',
            $shipper->shipper_concept ?? '—',
            $shipper->shipper_type ?? '—',
            $shipper->shipper_city ?? '—',
            $shipper->shipper_address ?? '—',
            $shipper->contact_person ?? '—',
            $shipper->phone_number ?? '—',
            $shipper->email_address ?? '—',
            $shipper->commodity ?? '—',
            $shipper->export ?? '—',
            $shipper->import ?? '—',
            $shipper->domestic ?? '—',
            $shipper->notes ?? '—',
        ];
    }

    public function headings(): array
    {
        return [
            'SHIPPER NAME',
            'CONCEPT',
            'TYPE',
            'CITY',
            'ADDRESS',
            'PIC',
            'PHONE',
            'EMAIL',
            'COMMODITY',
            'EXPORT',
            'IMPORT',
            'DOMESTIC',
            'NOTES',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 35,
            'F' => 20,
            'G' => 15,
            'H' => 25,
            'I' => 20,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 30,
        ];
    }

    public function title(): string
    {
        return 'Shippers Data';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $lastColumn = 'M';
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
            $sheet->getStyle('B2:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I2:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('E2:H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('M2:M' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'M';
                $sheet->setAutoFilter('A1:' . $lastColumn . $highestRow);
                $sheet->freezePane('A2');
                $footerRow = $highestRow + 2;
                $totalData = $this->shippers->count();
                $sheet->setCellValue('A' . $footerRow, 'Total Data: ' . $totalData . ' Shippers');
                $sheet->mergeCells('A' . $footerRow . ':F' . $footerRow);
                $userName = Auth::check() ? Auth::user()->name : 'System';
                $exportInfo = 'Exported by: ' . $userName . ' on ' . now()->format('d M Y H:i');
                $sheet->setCellValue('G' . $footerRow, $exportInfo);
                $sheet->mergeCells('G' . $footerRow . ':' . $lastColumn . $footerRow);
                $sheet->getStyle('A' . $footerRow . ':' . $lastColumn . $footerRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
