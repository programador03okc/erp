<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PresupuestoInternoSaldoExport implements FromView
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
}
