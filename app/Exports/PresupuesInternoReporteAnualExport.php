<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PresupuesInternoReporteAnualExport implements FromView, WithStyles, WithColumnFormatting
{
    public $data;
    public $cantidad;

    public function __construct($data)
    {
        $this->data = $data;
        $this->cantidad = sizeof($this->data) + 1;
    }
    public function view(): View
    {

        // dd($this->cantidad);exit;
        return view('finanzas.export.reporte_general_presupuestos_internos',['data' => $this->data,]);
    }

    public function styles(Worksheet $sheet)
    {

        // $sheet->getDefaultColumnDimension()->setWidth('1',20);
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

            // -------------------------
            'B' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B1' => [
                'alignment' => [
                    'horizontal' => Alignment::VERTICAL_CENTER,

                ],
            ],

            'D1' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E1' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'D' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'D1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'E' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'F' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'G' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'G1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
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
        ];
    }
}
