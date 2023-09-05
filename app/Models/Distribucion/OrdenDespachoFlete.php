<?php

namespace App\Models\Distribucion;

use Illuminate\Database\Eloquent\Model;

class OrdenDespachoFlete extends Model
{
    protected $table = 'almacen.orden_despacho_flete';
    protected $primaryKey = 'id_od_flete';
    public $timestamps = false;
}
