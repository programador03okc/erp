<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CentroCosto extends Model
{
    protected $table = 'finanzas.centro_costo';
    protected $primaryKey = 'id_centro_costo';
    public $timestamps = false;
}
