<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RequerimientoPagosNivelItemExport implements FromView, WithColumnFormatting, WithStyles
{
    public $data;
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function view(): View{
        $json = json_decode($this->data);
        // va
        // dd(json_decode($this->data));exit;

        return view('tesoreria.Pagos.export.requerimiento_pagados_nivel_item', [
            "data"=>$json
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D2:D'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:S')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        return [
            1    => ['font' => ['bold' => true] ],
            'A:S'  => ['font' => ['size' => 10]]
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'P' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT
        ];
    }

}
