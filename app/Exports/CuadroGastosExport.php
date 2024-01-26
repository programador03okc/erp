<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class CuadroGastosExport implements FromView
{
    public $req_compras;
    public $req_pagos;
    public $devoluciones;

    public function __construct($req_compras, $req_pagos, $devoluciones)
    {
        $this->req_compras = $req_compras;
        $this->req_pagos = $req_pagos;
        $this->devoluciones = $devoluciones;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view(
            'finanzas/presupuestos/export/cuadroGastosExcel',
            [
                'req_compras' => $this->req_compras,
                'req_pagos' => $this->req_pagos,
                'devoluciones'=> $this->devoluciones
            ]
        );
    }
}
