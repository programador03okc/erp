<?php

namespace App\Exports;

use App\Http\Controllers\OrdenController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class OrdenesItemsFiltroExport implements FromView
{

    public $filtros;

    public function __construct($filtros)
    {
        $this->filtros = json_decode($filtros);
    }

    public function view(): View
    {
        $data =(new OrdenController)->reporteListaItemsOrdenesFiltros($this->filtros);
        // dd($data);exit;
        return view('logistica.gestion_logistica.compras.ordenes.export.ordenes_items', [
            'data' => $data
        ]);
    }
}
