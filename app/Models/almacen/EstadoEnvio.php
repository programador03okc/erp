<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoEnvio extends Model
{
    protected $table = 'almacen.estado_envio';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;


    public static function listaEstadosDespacho(){

        return EstadoEnvio::whereIn('id_estado', [3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15])->orderBy('descripcion', 'asc')->get();
    }

    public static function listaEstadosDespachoParaSeleccionEnRequerimientoLogistico(){

        return EstadoEnvio::whereIn('id_estado', [3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16])->orderBy('id_estado', 'asc')->get();
    }
    
}
