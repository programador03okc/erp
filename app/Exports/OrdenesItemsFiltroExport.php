<?php

namespace App\Exports;

use App\Http\Controllers\OrdenController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class OrdenesItemsFiltroExport implements FromView
{

    public $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    public function view(): View
    {
        // $data =(new OrdenController)->reporteListaItemsOrdenesFiltros($this->filtros);
        // var_dump($data);exit;
        return view('logistica.gestion_logistica.compras.ordenes.export.ordenes_items', [ 'data' => $this->data ]);
    }
}
