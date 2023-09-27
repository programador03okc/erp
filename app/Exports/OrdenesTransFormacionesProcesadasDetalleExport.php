<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrdenesTransFormacionesProcesadasDetalleExport implements FromView
{
    public $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    public function view(): View
    {
        // dd($this->data);
        return view('almacen.export.ordenes_transformaciones_procesadas_detalle',["data"=>$this->data]);
    }
}
