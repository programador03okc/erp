<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Model;

class HistorialPresupuestoInternoSaldo extends Model
{
    //
    protected $table = 'finanzas.historial_presupuesto_interno_saldo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function totalEjecutadoPartida($partida_id){
        $historial_requerimientos = HistorialPresupuestoInternoSaldo::where('id_partida',$partida_id)
        ->where('estado', 3)
        ->where('operacion', 'R')
        ->whereNotNull('id_requerimiento_pago_detalle')
        ->get();
        $total = 0;
        foreach ($historial_requerimientos as $key => $value) {
            $total = $total + (float) $value->importe;
        }

        $historial_ordenes = HistorialPresupuestoInternoSaldo::where('id_partida',$partida_id)
        ->where('estado', 3)
        ->where('operacion', 'R')
        ->whereNotNull('id_orden_detalle')
        ->get();
        foreach ($historial_ordenes as $key => $value) {
            $total = $total + (float) $value->importe;
        }

        return $total;
    }

}
