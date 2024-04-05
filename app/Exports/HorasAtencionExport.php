<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HorasAtencionExport implements FromView, WithStyles, WithColumnWidths
{
    public $data;
    public $fecha_menor;
    public $fecha_mayor;
    public $dias;

    public function __construct($data, $fecha_menor, $fecha_mayor, $dias)
    {
        $this->data = json_decode($data);
        $this->fecha_menor = $fecha_menor;
        $this->fecha_mayor = $fecha_mayor;
        $this->dias = $dias;


    }

    public function view(): View
    {
        return view('cas.export.horas_acumuladas_incidencia',
            [
                'data' => $this->data,
                'fecha_menor' => $this->fecha_menor,
                'fecha_mayor' => $this->fecha_mayor,
                'dias' => $this->dias
            ]
        );
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
            'C' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'D' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            'A1:E1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],
            ],
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 25,
        ];
    }
}
