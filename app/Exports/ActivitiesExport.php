<?php

namespace App\Exports;

use App\Models\Activity;
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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivitiesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    private $dateFrom;
    private $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $activities = Activity::with('shipper', 'user')
            ->whereBetween('report_date', [$this->dateFrom, $this->dateTo])
            ->orderBy('report_date', 'desc')
            ->get();

        return $activities->map(function ($activity) {
            return [
                Carbon::parse($activity->report_date)->format('d M Y'),
                $activity->concept_type,
                $activity->shipper->shipper_name ?? '-',
                $activity->shipper->shipper_type ?? '-',
                $activity->shipper->commodity ?? '-',
                $activity->activity_type,
                $activity->visit_date ? Carbon::parse($activity->visit_date)->format('d M Y') : '-',
                $activity->status ?? '-',
                $activity->status_detail ?? '-',
                $activity->prospect ?? '-',
                $activity->user->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'DATE',
            'CONCEPT',
            'SHIPPER',
            'TYPE',
            'COMMODITY',
            'ACTIVITY',
            'VISIT',
            'STATUS',
            'DETAIL',
            'PROSPECT',
            'CREATED BY',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 12,
            'G' => 15,
            'H' => 12,
            'I' => 25,
            'J' => 35,
            'K' => 18,
        ];
    }

    public function title(): string
    {
        return 'Activities Report';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E7D32'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);

        if ($highestRow > 1) {
            $sheet->getStyle('A2:K' . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            for ($i = 2; $i <= $highestRow; $i++) {
                if ($i % 2 == 0) {
                    $sheet->getStyle('A' . $i . ':K' . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F5F5F5'],
                        ],
                    ]);
                }
            }
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->setAutoFilter('A1:K1');

                $footerRow = $highestRow + 2;

                if ($this->dateFrom && $this->dateTo) {
                    $dateRange = 'Period: ' . Carbon::parse($this->dateFrom)->format('d M Y') 
                               . ' - ' . Carbon::parse($this->dateTo)->format('d M Y');
                    $sheet->setCellValue('A' . $footerRow, $dateRange);
                    $sheet->mergeCells('A' . $footerRow . ':D' . $footerRow);
                }

                $exportInfo = 'Exported by: ' . Auth::user()->name . ' on ' . now()->format('d M Y H:i');
                $sheet->setCellValue('G' . $footerRow, $exportInfo);
                $sheet->mergeCells('G' . $footerRow . ':K' . $footerRow);

                $sheet->getStyle('A' . $footerRow . ':K' . $footerRow)->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->freezePane('A2');
            },
        ];
    }
}