<?php

namespace App\Models\almacen;

use App\Models\Distribucion\OrdenDespachoDetalle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materia extends Model
{
    protected $table = 'almacen.transfor_materia';
    protected $primaryKey = 'id_materia';
    public $timestamps = false;

    public function ordenDespachoDetalle(): BelongsTo
    {
        return $this->belongsTo(OrdenDespachoDetalle::class, 'id_od_detalle', 'id_od_detalle');
    }
}
