<?php

namespace App\Models\ClasificacionSap;

use Illuminate\Database\Eloquent\Model;

class GrupoSap extends Model
{
    protected $table = 'clasificacion_sap.grupo';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
