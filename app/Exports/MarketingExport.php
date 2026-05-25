<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
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

class MarketingExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting, WithTitle, WithEvents
{
    protected $activities;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($activities, $dateFrom, $dateTo)
    {
        $this->activities = $activities;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return $this->activities->map(function ($activity) {
            $rootId = $activity->parent_id ?? $activity->id;
            $suffix = match ($activity->status_type) {
                'CLOSING' => 'CLS',
                'FAILED' => 'FLD',
                default => $activity->sequence ?? 1,
            };
            $ref = 'ACT#' . $rootId . '-' . $suffix;
            return [
                $activity->created_at->format('d M Y'),
                $ref,
                $activity->shipper->shipper_name ?? '—',
                $activity->shipper->shipper_concept ?? '—',
                $activity->shipper->shipper_type ?? '—',
                $activity->shipper->commodity ?? '—',
                $activity->activity_type,
                $activity->visit_date ? $activity->visit_date->format('d M Y') : '—',
                $activity->status_type ?? '—',
                $activity->volume_20 ?? '—',
                $activity->volume_40 ?? '—',
                $activity->other_volume ?? '—',
                $activity->profit ?? '—',
                $activity->remarks ?? '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'DATE',
            'REF',
            'SHIPPER',
            'CONCEPT',
            'TYPE',
            'COMMODITY',
            'ACTIVITY',
            'VISIT',
            'STATUS',
            '20 FT',
            '40 FT',
            'OTHER',
            'PROFIT',
            'REMARKS',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 15,
            'C' => 30,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 12,
            'H' => 12,
            'I' => 15,
            'J' => 8,
            'K' => 8,
            'L' => 20,
            'M' => 15,
            'N' => 25,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'M' => '#,##0',
        ];
    }

    public function title(): string
    {
        return 'Activities Report';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A1D20'],
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
            $sheet->getStyle('A2:' . $highestCol . $highestRow)->applyFromArray([
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
                    $sheet->getStyle('A' . $i . ':' . $highestCol . $i)->applyFromArray([
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
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
                $sheet->setAutoFilter('A1:' . $highestCol . '1');
                $footerRow = $highestRow + 2;
                if ($this->dateFrom && $this->dateTo) {
                    $dateRange = 'Period: ' . Carbon::parse($this->dateFrom)->format('d M Y')
                        . ' - ' . Carbon::parse($this->dateTo)->format('d M Y');
                    $sheet->setCellValue('A' . $footerRow, $dateRange);
                    $sheet->mergeCells('A' . $footerRow . ':F' . $footerRow);
                }
                $exportInfo = 'Exported by: ' . Auth::user()->name . ' on ' . now()->format('d M Y H:i');
                $sheet->setCellValue('G' . $footerRow, $exportInfo);
                $sheet->mergeCells('G' . $footerRow . ':' . $highestCol . $footerRow);
                $sheet->getStyle('A' . $footerRow . ':' . $highestCol . $footerRow)->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->freezePane('A2');
                $prevShipper = null;
                $groupToggle = false;
                for ($i = 2; $i <= $highestRow; $i++) {
                    $currentShipper = $sheet->getCell('C' . $i)->getValue();
                    if ($currentShipper !== $prevShipper) {
                        $groupToggle = !$groupToggle;
                        $prevShipper = $currentShipper;
                    }
                    $bgColor = $groupToggle ? 'EAF4FF' : 'FFFFFF';
                    $sheet->getStyle('A' . $i . ':' . $highestCol . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $bgColor],
                        ],
                    ]);
                }
            },
        ];
    }
}