<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'configuracion.sis_usua';
    protected $primaryKey = 'id_usuario';
    protected $fillable = ['id_trabajador', 'usuario', 'clave', 'password', 'estado', 'email', 'nombre_corto', 'renovar'];

	public $timestamps = false;
	
    public function getAllRol()
	{
		return DB::table('configuracion.usuario_rol')
			->select('usuario_rol.*', 'sis_rol.descripcion')
			->join('configuracion.sis_rol', 'sis_rol.id_rol', '=', 'usuario_rol.id_rol')
			->where('usuario_rol.id_usuario', $this->id_usuario)
			->where('usuario_rol.estado', 1)
			->orderBy('usuario_rol.id_usuario_rol', 'desc')->first();
	}
}
