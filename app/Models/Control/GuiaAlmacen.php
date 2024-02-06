<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuiaAlmacen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'control.control_almacen';
    protected $fillable = [
        'codigo', 'documento', 'sede', 'tipo_movimiento_id', 'destino', 'ocam', 'oc_virtual', 'codigo_oportunidad', 'codigo_requerimiento',
        'procesado_agile', 'procesado_softlink', 'id_responsable', 'fecha_ingreso', 'marca', 'descripcion', 'id_usuario', 'fecha_guia', 'empresa',
        'entidad', 'estado', 'estado_gr', 'recepcion_gci', 'empresa_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
