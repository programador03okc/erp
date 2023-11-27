<?php


namespace App\Models\Rrhh;

use Illuminate\Database\Eloquent\Model;

class PersonaView extends Model
{
    protected $table = 'rrhh.personas_view';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;
    protected $fillable = ['id_tipo_documento_idendidad','descripcion_tipo_documento_idendidad','nro_documento',
    'nombre_completo', 'estado', 'fecha_registro'];

}