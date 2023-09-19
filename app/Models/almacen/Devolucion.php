<?php

namespace App\Models\almacen;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'cas.devolucion';
    protected $primaryKey = 'id_devolucion';
    public $timestamps = false;

}
