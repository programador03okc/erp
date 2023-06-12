<?php

namespace App\Models;

use App\Models\Configuracion\Acceso;
use App\Models\mgcp\Usuario\RolUsuario;
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
    public function tieneRol($rol)
    {
        if (RolUsuario::where('id_usuario', $this->id)->where('id_rol', $rol)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function obtenerPorRol($rol)
    {
        return User::whereRaw('id IN (SELECT id_usuario FROM mgcp_usuarios.roles_usuario WHERE id_rol=?)', [$rol])->orderBy('name', 'asc')->get();
    }
    public function tieneAccion($id)
	{

		return Acceso::where([['id_usuario', $this->id_usuario],['id_accion', $id],['sis_acceso.estado', 1]])->first() != null;

		// return Acceso::join('configuracion.sis_rol', 'sis_acceso.id_rol', '=', 'sis_rol.id_rol')
		// 	->join('configuracion.sis_accion_rol', 'sis_accion_rol.id_rol', '=', 'sis_rol.id_rol')
		// 	->where('id_usuario', $this->id_usuario)->where('sis_accion_rol.id_accion', $id)->first() != null;
	}
}
