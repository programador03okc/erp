<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReporteSalosMensualesExport implements FromView
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
}
