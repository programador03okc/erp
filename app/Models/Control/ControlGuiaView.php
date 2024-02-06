<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlGuiaView extends Model
{
    use HasFactory;

    protected $table = 'control.control_guias_view';
    protected $primaryKey = 'id_control_almacen';
    protected $appends = ['formato_fecha'];

    public function getFormatoFechaAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha_guia));
    }
}
