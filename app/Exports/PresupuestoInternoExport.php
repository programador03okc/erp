<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PresupuestoInternoExport implements FromView, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $presupuesto_interno;
    public $presupuesto_interno_detalle;

    public function __construct($presupuesto_interno, $presupuesto_interno_detalle)
    {
        $this->presupuesto_interno = $presupuesto_interno;
        $this->presupuesto_interno_detalle = $presupuesto_interno_detalle;
    }
    public function view(): View
    {
        // dd($this->presupuesto_interno);exit;
        return view(
            'finanzas.export.presupuesto_interno',
            [
                'presupuesto_interno' => $this->presupuesto_interno,
                'ingresos'  => $this->presupuesto_interno_detalle['ingresos'],
                'costos'    => $this->presupuesto_interno_detalle['costos'],
                'gastos'    => $this->presupuesto_interno_detalle['gastos'],
            ]
        );
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,

            // 'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }
}
