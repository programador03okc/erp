<?php

namespace App\models\Configuracion;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    //
    protected $table = 'configuracion.usuario_rol';
	protected $primaryKey = 'id_usuario_rol';
    public $timestamps = false;
    public function sisUSua()
    {
        return $this->belongsTo(SisUsua::class, 'id_usuario', 'id_usuario');
    }
}
