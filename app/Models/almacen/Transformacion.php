<?php

namespace App\Models\almacen;

use App\Models\Distribucion\OrdenDespachoDetalle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transformacion extends Model
{
    protected $table = 'almacen.transformacion';
    protected $primaryKey = 'id_transformacion';
    public $timestamps = false;


}
