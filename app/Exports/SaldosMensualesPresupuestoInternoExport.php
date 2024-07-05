<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

class SaldosMensualesPresupuestoInternoExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $data;
    public $cantidad;
    public $presupuesto;

    public function __construct($data, $presupuesto)
    {
        $this->data = $data;
        $this->cantidad = sizeof($this->data) + 1;
        $this->presupuesto = json_decode($presupuesto);
    }
    public function view(): View
    {
        // dd($this->presupuesto);exit;
        foreach ($this->data as $key => $value) {
            // dd($value);exit;
        }

        return view('finanzas.export.presupuesto_interno_saldo_mensual',['data' => $this->data,]);
    }
}
