<?php


namespace App\Models\Configuracion;

use App\Models\Administracion\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsuarioDivision extends Model {

    protected $table = 'configuracion.usuario_division';
    protected $fillable = ['id_usuario_division','id_usuario','id_estado','id_division','es_jefe','es_gerente','comentario'];
    protected $primaryKey = 'id_usuario_division';
    public $timestamps = false;


    public static function mostrarDivisionUsuario()
    {
        return UsuarioDivision::where([["id_usuario",Auth::user()->id_usuario],["id_estado",1]])->get();
    }

    public static function mostrarDivisionUsuarioAcceso()
    {
        $usuarioDivision= UsuarioDivision::where([["id_usuario",Auth::user()->id_usuario],["id_estado",1]])->get();
        foreach ($usuarioDivision as $key => $ud) {
            if($ud->es_gerente == true){
                $division = Division::where("id_division",$ud->id_division)->first();
                $usuarioDivision = Division::where("grupo_id",$division->grupo_id)->get();
            }
        }

        return $usuarioDivision;
    }

    public function division()
    {
        return $this->hasOne(Division::class, 'id_division', 'id_division')->where('estado',1);
    }

 
}
