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
        ->where('documento_anulado','f')
        ->where('tipo','SALIDA')
        ->whereNotNull('id_requerimiento_pago_detalle')
        ->get();
        $total_pagos = 0;
        foreach ($historial_requerimientos as $key => $value) {
            $total_pagos = $total_pagos + $value->importe;
        }

        $historial_ordenes = HistorialPresupuestoInternoSaldo::where('id_partida',$partida_id)
        ->where('estado', 3)
        ->where('operacion', 'R')
        ->whereNotNull('id_orden_detalle')
        ->where('documento_anulado','f')
        ->where('tipo','SALIDA')
        ->get();
        $total_ordenes = 0;
        foreach ($historial_ordenes as $key => $value) {
            $total_ordenes = $total_ordenes + $value->importe;
        }

        $total = $total_pagos + $total_ordenes;

        return $total;
    }

    public static function totalSalidas($id){
        $salidas = HistorialPresupuestoInternoSaldo::where('id_presupuesto_interno',$id)
            ->where('tipo','SALIDA')
            ->where('estado',3)
            // ->orderBy('id','DESC')
        ->get();
        $total = 0;
        foreach ($salidas as $key => $value) {
            $total = $total + $value->importe;
        }

        return $total;
    }

}
