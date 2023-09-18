<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PresupuestoInternoSaldoExport implements FromView, WithColumnFormatting
{
    public $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }
    public function view(): View
    {
        foreach ($this->data as $key => $value) {
            // dd($value);exit;
        }

        return view('finanzas.export.presupuesto_interno_saldo',['data' => $this->data,]);
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,

            // 'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }
}
