<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class OrdenesTransformacionesPendientesExport implements FromView
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // dd($this->data);
        return view('almacen.export.ordenes_transformaciones_pendientes',["data"=>$this->data]);
    }

}
