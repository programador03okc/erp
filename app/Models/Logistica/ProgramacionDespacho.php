<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramacionDespacho extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'logistica.programacion_despachos';
    protected $fillable = ['titulo', 'descripcion','fecha_registro', 'fecha_programacion', 'estado', 'reprogramacion_id', 'requerimiento_id','orden_despacho_id', 'aplica_cambios', 'created_id', 'updated_id', 'deleted_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
