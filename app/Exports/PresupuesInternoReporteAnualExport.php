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
        // $sheet->getStyle($index)->getQuotePrefix(true);
        $sheet->getDefaultColumnDimension()->setWidth(12);

        // $sheet
        // ->getStyle("A1")
        // ->getNumberFormat()
        // ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        return [
            'A1' => [
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
            'A2:A'.$this->cantidad => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B1' => [
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
            'B2:B'.$this->cantidad => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C1' => [
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

            'C2:E'.$this->cantidad =>
            [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'C2:E'.$this->cantidad => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
