<?php

namespace App\Models\Distribucion;

use Illuminate\Database\Eloquent\Model;

class OrdenDespachoDetalle extends Model
{
    protected $table = 'almacen.orden_despacho_det';
    protected $primaryKey = 'id_od_detalle';
    public $timestamps = false;

}
