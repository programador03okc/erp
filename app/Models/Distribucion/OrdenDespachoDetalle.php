<?php

namespace App\Models\Distribucion;

use App\Models\Almacen\DetalleRequerimiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenDespachoDetalle extends Model
{
    protected $table = 'almacen.orden_despacho_det';
    protected $primaryKey = 'id_od_detalle';
    public $timestamps = false;

    public function detalleRequerimiento(): BelongsTo
    {
        return $this->belongsTo(DetalleRequerimiento::class, 'id_detalle_requerimiento', 'id_detalle_requerimiento');
    }

}
