<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
// use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ReporteSalosMensualesExport implements FromView, WithStyles, WithColumnFormatting, WithColumnWidths
{
    public $data;

    public function __construct(string $data)
    {
        $this->data = json_decode($data);
    }
    public function view(): View{
        // dd($this->data);
        return view('finanzas.export.reporte_saldos_mensual', ['saldos' => $this->data]);
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            'A1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],
            ],
            'B' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            'B1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],
            ],
            // ---------------------
            'C:N' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            'C1:N1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],
            ],

        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
        ];
    }
}
