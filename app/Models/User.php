<?php

namespace App\Models;

use App\Models\Administracion\Empresa;
use App\Models\Configuracion\Acceso;
use App\Models\Configuracion\Rol;
use App\Models\mgcp\Usuario\RolUsuario;
use App\Models\Rrhh\Trabajador;
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
	
	public function trabajador()
	{
		return $this->belongsTo(Trabajador::class, 'id_trabajador')->withDefault();
	}

	public function tieneAccion($id)
	{
		return Acceso::where([['id_usuario', $this->id_usuario], ['id_accion', $id], ['sis_acceso.estado', 1]])->first() != null;
	}

	public function tieneAplicacion($id)
	{
		return Acceso::join('configuracion.sis_accion', 'sis_accion.id_accion', '=', 'sis_acceso.id_accion')
			->where([['sis_acceso.id_usuario', $this->id_usuario], ['sis_acceso.estado', 1]])->where('sis_accion.id_aplicacion', $id)->first() != null;
	}

	public function tieneSubModulo($id)
	{
		return Acceso::join('configuracion.sis_accion', 'sis_accion.id_accion', '=', 'sis_acceso.id_accion')
			->join('configuracion.sis_aplicacion', 'sis_aplicacion.id_aplicacion', '=', 'sis_accion.id_aplicacion')
			->where([['sis_acceso.id_usuario', $this->id_usuario], ['sis_acceso.estado', 1]])->where('sis_aplicacion.id_sub_modulo', $id)->first() != null;
	}

	public function tieneSubModuloPadre($id)
	{
		return Acceso::join('configuracion.sis_accion', 'sis_accion.id_accion', '=', 'sis_acceso.id_accion')
			->join('configuracion.sis_aplicacion', 'sis_aplicacion.id_aplicacion', '=', 'sis_accion.id_aplicacion')
			->join('configuracion.sis_modulo', 'sis_modulo.id_modulo', '=', 'sis_aplicacion.id_sub_modulo')
			->where([['sis_acceso.id_usuario', $this->id_usuario], ['sis_acceso.estado', 1]])->where('sis_modulo.id_padre', $id)->first() != null;
	}

	public function sedesAcceso()
	{
		return DB::table('configuracion.sis_usua_sede')
			->select('sis_sede.*')
			->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'sis_usua_sede.id_sede')
			->where('sis_usua_sede.id_usuario', $this->id_usuario)->get();
	}

	public function getRolesText()
	{
		$texto = '';
		$roles = DB::table('configuracion.usuario_rol')->select('sis_rol.*')
			->join('configuracion.sis_rol', 'sis_rol.id_rol', '=', 'usuario_rol.id_rol')
			->where([['usuario_rol.id_usuario', $this->id_usuario], ['usuario_rol.estado', 1]])->get();
			
		foreach ($roles as $s) {
			if ($texto == '') {
				$texto .= $s->descripcion;
			} else {
				$texto .= ', ' . $s->descripcion;
			}
		}
		return $texto;
	}

	public function getGrupo()
	{
		return DB::table('configuracion.usuario_grupo')
			->join('configuracion.sis_grupo', 'sis_grupo.id_grupo', '=', 'usuario_grupo.id_grupo')
			->where('usuario_grupo.id_usuario', $this->id_usuario)
			->select('sis_grupo.*')->first();
	}
	public function getAllGrupo()
	{
		return DB::table('configuracion.usuario_grupo')
			->join('configuracion.sis_grupo', 'sis_grupo.id_grupo', '=', 'usuario_grupo.id_grupo')
			->where('usuario_grupo.id_usuario', $this->id_usuario)
			->select('sis_grupo.*')->distinct('id_grupo')->get();
	}

	public function getAllRol()
	{
		return DB::table('configuracion.usuario_rol')
			->select('usuario_rol.*', 'sis_rol.descripcion')
			->join('configuracion.sis_rol', 'sis_rol.id_rol', '=', 'usuario_rol.id_rol')
			->where('usuario_rol.id_usuario', $this->id_usuario)
			->where('usuario_rol.estado', 1)->get();
	}

	public function getUltimoRol()
	{
		$rol = DB::table('configuracion.usuario_rol')
			->select('usuario_rol.*', 'sis_rol.descripcion')
			->join('configuracion.sis_rol', 'sis_rol.id_rol', '=', 'usuario_rol.id_rol')
			->where('usuario_rol.id_usuario', $this->id_usuario)
			->where('usuario_rol.estado', 1)
			->orderBy('usuario_rol.id_usuario_rol', 'desc')->first();
		return ($rol) ? $rol->descripcion : 'SN';
	}

	public function getAllRolUser($id)
	{
		return DB::table('configuracion.usuario_rol')
			->select('usuario_rol.*', 'sis_rol.descripcion')
			->join('configuracion.sis_rol', 'sis_rol.id_rol', '=', 'usuario_rol.id_rol')
			->where('usuario_rol.id_usuario', $id)->get();
	}

	static public function getAllIdUsuariosPorRol($idRol)
	{
		$idUsuarioList = [];
		$accesos = DB::table('configuracion.usuario_rol')->select('usuario_rol.*')
			->where([['usuario_rol.id_rol', $idRol], ['estado', 1]])->get();

		foreach ($accesos as $value) {
			$idUsuarioList[] = $value->id_usuario;
		}
		return $idUsuarioList;
	}

	public function getLoginEmpresaAttribute()
	{
		return session('login_empresa');
	}


	public function getLoginEmpresaDataAttribute()
	{
		return Empresa::find(session('login_empresa'));
	}

	public function getLoginRolAttribute()
	{
		return session('login_rol');
	}

	public function getConceptoLoginRolAttribute()
	{
		$rol = Rol::with('rol_concepto')->findOrFail($this->login_rol);
		return $rol->rol_concepto->descripcion;
	}

	public function getCargoAttribute()
	{
		$rol = Rol::find(2);
		return $rol->cargo->descripcion;
	}

	public function roles()
	{
		return $this->trabajador->roles();
	}

	public function getPerteneceAEmpresaAttribute()
	{
		$empresas = [];
		return collect($empresas);
	}
	public function obtenerRoles()
	{
		$rolesBD = $this->trabajador->roles()->get();
		foreach ($rolesBD as $rol) {
			$roles[] = $rol->id_rol_concepto;
		}
		return $roles;
	}

	/**
	 * SECCION ROLES PERSONALIZADA
	 */
	public function authorizeRoles($roles)
	{
		if ($this->hasAnyRole($roles)) {
			return true;
		}
		abort(401, 'Esta acción no está autorizada.');
	}

	public function hasAnyRole($roles)
	{
		if (is_array($roles)) {
			foreach ($roles as $role) {
				if ($this->hasRole($role)) {
					return true;
				}
			}
		} else {
			if ($this->hasRole($roles)) {
				return true;
			}
		}
		return false;
	}

	public function hasRole($role)
	{
		return ($this->trabajador->roles()->where('rrhh.rrhh_rol.id_rol_concepto', $role)->first()) ? true : false;
	}

    public function tieneRol($rol)
    {
        return (RolUsuario::where('id_usuario', $this->id)->where('id_rol', $rol)->count() > 0) ? true : false;
    }

    public static function obtenerPorRol($rol)
    {
        return User::whereRaw('id IN (SELECT id_usuario FROM mgcp_usuarios.roles_usuario WHERE id_rol=?)', [$rol])->orderBy('name', 'asc')->get();
    }
}
