<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Periodo extends Model
{
    protected $table = 'administracion.adm_periodo';
    protected $primaryKey = 'id_periodo';
    public $timestamps = false;

    public static function mostrar()
    {
        $data = Periodo::select(
                'adm_periodo.*'
            )
            ->where([
                ['adm_periodo.estado', '=', 1],
                ['adm_periodo.activo', '=', true]
            ])
            ->orderBy('adm_periodo.descripcion', 'desc')
            ->get();
        return $data;
    }
    public static function obtenerIdPeriodoActual()
    {
        $data = Periodo::select(
                'adm_periodo.*'
            )
            ->where([
                ['adm_periodo.estado', '=', 1],
                ['adm_periodo.activo', '=', true]
            ])
            ->orderBy('adm_periodo.descripcion', 'desc')
            ->first();
        return $data->id_periodo;
    }
    public static function obtenerDescripciónPeriodoActual()
    {
        $data = Periodo::select(
                'adm_periodo.*'
            )
            ->where([
                ['adm_periodo.estado', '=', 1],
                ['adm_periodo.activo', '=', true]
            ])
            ->orderBy('adm_periodo.descripcion', 'desc')
            ->first();
        return $data->descripcion;
    }
}
