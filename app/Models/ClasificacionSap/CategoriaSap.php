<?php

namespace App\Models\ClasificacionSap;

use Illuminate\Database\Eloquent\Model;

class CategoriaSap extends Model
{
    protected $table = 'clasificacion_sap.categoria';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
