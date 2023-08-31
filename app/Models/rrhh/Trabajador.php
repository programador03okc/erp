<?php


namespace App\Models\Rrhh;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Trabajador extends Model {
    protected $table = 'rrhh.rrhh_trab';
    protected $primaryKey = 'id_trabajador';
    public $timestamps = false;


    public static function mostrar()
    {
        $data = Trabajador::select('rrhh_trab.*', 'rrhh_perso.nro_documento',
                DB::raw("concat(rrhh_perso.nombres, ' ' ,rrhh_perso.apellido_paterno, ' ' ,rrhh_perso.apellido_materno) AS nombre_trabajador"))
                ->join('rrhh.rrhh_postu', 'rrhh_postu.id_postulante', '=', 'rrhh_trab.id_postulante')
                ->join('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'rrhh_postu.id_persona')
                ->where([['rrhh_trab.estado', '=', 1]])
                ->orderBy('nombre_trabajador')
                ->get();
        return $data;
    }


    public function postulante()
    {
        return $this->belongsTo('App\Models\Rrhh\Postulante','id_postulante')->withDefault();
    }
}
