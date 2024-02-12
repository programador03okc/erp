<?php

namespace App\Http\Controllers\Finanzas\Presupuesto;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoInternoController;
use App\Models\Almacen\DetalleRequerimiento;
use App\Models\Almacen\Requerimiento;
use App\Models\Finanzas\HistorialPresupuestoInternoSaldo;
use App\Models\Finanzas\PresupuestoInternoDetalle;
use App\Models\Logistica\Orden;
use App\Models\Logistica\OrdenCompraDetalle;
use App\Models\Tesoreria\RequerimientoPago;
use App\Models\Tesoreria\RequerimientoPagoDetalle;
use App\Models\Tesoreria\TipoCambio;
use Illuminate\Http\Request;

class ValidarPresupuestoInternoController extends Controller
{
    public function getTipoCambioVenta($fecha)
    {
        $tc = TipoCambio::where([['moneda', '=', 2], ['fecha', '<=', $fecha]])
            ->orderBy('fecha', 'DESC')->first();

        return ($tc !== null ? floatval($tc->venta) : 1);
    }


    function validarPresupuestoParaPago(Request $request)
    {

        $tipo = $request->tipo;
        $id = $request->id;
        $fechaPago = $request->fecha_pago;
        $montoPago = $request->monto_pago;
        $fase = $request->fase; //* opciones: (FASE_APROBACION, FASE_AUTORIZACION, FASE_EJECUCION) 


        $fechaPago = $fechaPago;
        $totalPago = round($montoPago, 2);
        $data = [];
        $mensaje = '';
        $estado = '';
        $mensajeValidacion = [];

        $numeroMes = strlen($fechaPago) == 2 ? intval($fechaPago) : intval(date('m', strtotime($fechaPago)));

        if ($tipo == 'requerimiento pago') { // id requerimiento de pago
            $idRequerimientoPago = $id;
            $validacionDePartidaConPresupuestoInternoList =  $this->tienePresupuestoLasPartidasDelRequerimientoPago($idRequerimientoPago, $numeroMes, $totalPago, $fechaPago,$fase);
            $montoAcumuladoDePartida = $this->obtenerMontoAcumuladoPartidas($validacionDePartidaConPresupuestoInternoList);
            // $no_exceder_pago = (floatval($montoAcumuladoDePartida) >= floatval($totalPago))?true:false;
            $tienePresupuestoEnPartidas = true;

            foreach ($validacionDePartidaConPresupuestoInternoList as $key => $value) {
                $tienePresupuestoEnPartidas = (boolval($tienePresupuestoEnPartidas) * boolval($value['tiene_presupuesto']));
            }

            // if(boolval($no_exceder_pago ==false)){
            //     $mensajeValidacion[]='El monto ingresado es mayor al monto total del documento';
            // }

            if (boolval($tienePresupuestoEnPartidas) == false) {
                $mensajeValidacion[] = 'Hay partida(s) de presupuesto interno sin saldo suficiente';
            }

            $data = [
                // 'tiene_presupuesto'=> boolval($no_exceder_pago * $tienePresupuestoEnPartidas),
                'tiene_presupuesto' => boolval($tienePresupuestoEnPartidas),
                'mensaje' => $mensajeValidacion,
                'monto_acumulado_partidas_presupuesto_interno' => $montoAcumuladoDePartida,
                'monto_sin_vinculo_con_presupuesto_interno' => 0,
                'monto_pago_ingresado' => floatval($totalPago),
                'validacion_partidas_con_presupuesto_interno' => $validacionDePartidaConPresupuestoInternoList
            ];
        } elseif ($tipo == 'orden') { // id orden de compra o orden de servicio
            $idOrden = $id;
            $validacionDePartidaConPresupuestoInternoList =  $this->tienePresupuestoLasPartidasDeLaOrden($idOrden, $numeroMes, $totalPago, $fechaPago,$fase);
            $montoAcumuladoDePartida = $this->obtenerMontoAcumuladoPartidas($validacionDePartidaConPresupuestoInternoList);
            $montoSinVinculoConPresupuestoInterno = $this->obtenerMontoSinVinculoConPresupuestoInterno($idOrden);
            // $no_exceder_pago = (floatval($montoAcumuladoDePartida) + floatval($montoSinVinculoConPresupuestoInterno)) >= floatval($totalPago)?true:false;
            $mensajeValidacion = [];

            // if(boolval($no_exceder_pago ==false)){
            //     $mensajeValidacion[]='El monto ingresado es mayor al monto total del documento';
            // }

            $tienePresupuestoEnPartidas = true;
            foreach ($validacionDePartidaConPresupuestoInternoList as $key => $value) {
                $tienePresupuestoEnPartidas = (boolval($tienePresupuestoEnPartidas) * boolval($value['tiene_presupuesto']));
            }

            if (boolval($tienePresupuestoEnPartidas) == false) {
                $mensajeValidacion[] = 'Hay partida(s) de presupuesto interno sin saldo suficiente';
            }


            $data = [
                // 'tiene_presupuesto'=> boolval($no_exceder_pago * $tienePresupuestoEnPartidas),
                'tiene_presupuesto' => boolval($tienePresupuestoEnPartidas),
                'mensaje' => $mensajeValidacion,
                'monto_acumulado_partidas_presupuesto_interno' => floatval($montoAcumuladoDePartida),
                'monto_sin_vinculo_con_presupuesto_interno' => floatval($montoSinVinculoConPresupuestoInterno),
                'monto_pago_ingresado' => floatval($totalPago),
                'validacion_partidas_con_presupuesto_interno' => $validacionDePartidaConPresupuestoInternoList
            ];
        } elseif ($tipo == 'requerimiento logistico') { // id de requerimiento logistico
            $idRequerimientoLogistio = $id;
            $mensajeValidacion = [];
            $validacionDePartidaConPresupuestoInternoList =  $this->tienePresupuestoLasPartidasDelRequerimientoLogistico($idRequerimientoLogistio, $numeroMes, $totalPago, $fechaPago, $fase);

            $tienePresupuestoEnPartidas = true;
            foreach ($validacionDePartidaConPresupuestoInternoList as $key => $value) {
                $tienePresupuestoEnPartidas = (boolval($tienePresupuestoEnPartidas) * boolval($value['tiene_presupuesto']));
            }

            if (boolval($tienePresupuestoEnPartidas) == false) {
                $mensajeValidacion[] = 'Hay partida(s) de presupuesto interno sin saldo suficiente';
            }

            $data = [
                'tiene_presupuesto' => boolval($tienePresupuestoEnPartidas),
                'mensaje' => $mensajeValidacion,
                'monto_acumulado_partidas_presupuesto_interno' => 0,
                'monto_sin_vinculo_con_presupuesto_interno' => 0,
                'monto_pago_ingresado' => floatval($totalPago),
                'validacion_partidas_con_presupuesto_interno' => $validacionDePartidaConPresupuestoInternoList
            ];
        } else {
            $estado = 'warning';
            $mensaje .= 'El id de documento no corresponde a una orden o requerimiento de pago. ';
        }

        if (count($data) > 0) {
            $estado = 'success';
            $mensaje .= 'Se obtuvo data de la partidas.';
        } else {
            $estado = 'warning';
            $mensaje .= 'No se encontró presupuesto interno o no al que hace referencia no esta aprobado.';
        }

        return ['tipo' => $estado, 'mensaje' => $mensaje, 'data' => $data];
    }


    public function tienePresupuestoLasPartidasDeLaOrden($idOrden, $numeroMes, $totalPago, $fechaPago, $fase)
    {

        $mesLista = ['1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril', '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto', '9' => 'setiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
        $nombreMes = $mesLista[$numeroMes];
        $nombreMesAux = $nombreMes . '_aux';
        $montoPorUtilizarPorPartida = [];
        $data = [];

        if ($idOrden > 0) {
            // $orden = Orden::find($idOrden);
            $detalleOrden = OrdenCompraDetalle::where('id_orden_compra', $idOrden)->get();
            $orden = Orden::find($idOrden);
            foreach ($detalleOrden as $item) {
                if ($item->estado != 7 && $item->id_detalle_requerimiento > 0) {
                    $detalleRequerimiento = DetalleRequerimiento::find($item->id_detalle_requerimiento);
                    if ($detalleRequerimiento->id_partida_pi > 0) {

                        if ($orden->id_moneda == 1) {
                            if ($orden->incluye_igv == true) {
                                $montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] = floatval($montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] ?? 0) + floatval($item->subtotal * 1.18);
                            } else {
                                $montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] = floatval($montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] ?? 0) + floatval($item->subtotal);
                            }
                        } elseif ($orden->id_moneda == 2) {
                            if ($orden->incluye_igv == true) {
                                $montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] = ((floatval($montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] ?? 0) + floatval($item->subtotal * 1.18)) * $this->getTipoCambioVenta($fechaPago));
                            } else {
                                $montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] = ((floatval($montoPorUtilizarPorPartida[$detalleRequerimiento->id_partida_pi] ?? 0) + floatval($item->subtotal)) * $this->getTipoCambioVenta($fechaPago));
                            }
                        }
                    }
                }
            }
            foreach ($montoPorUtilizarPorPartida as $partidaId => $monto) {
                $tieneSaldoPartida = $this->TieneSaldoLaPartida($partidaId, $nombreMesAux, number_format($monto, 2, '.', ''), $totalPago,$fase);
                if ($tieneSaldoPartida != []) {
                    $data[] = $tieneSaldoPartida;
                }
            }
        }

        return $data;
    }


    public function tienePresupuestoLasPartidasDelRequerimientoPago($idRequerimientoPago, $numeroMes, $totalPago, $fechaPago, $fase)
    {

        $mesLista = ['1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril', '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto', '9' => 'setiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
        $nombreMes = $mesLista[$numeroMes];
        $nombreMesAux = $nombreMes . '_aux';
        $montoPorUtilizarPorPartida = [];
        $data = [];

        if ($idRequerimientoPago > 0) {
            $detalleRequerimientoPago = RequerimientoPagoDetalle::where('id_requerimiento_pago', $idRequerimientoPago)->get();
            $requerimientoPago = RequerimientoPago::find($idRequerimientoPago);
            foreach ($detalleRequerimientoPago as $item) {
                if ($item->id_estado != 7 && $item->id_requerimiento_pago_detalle > 0) {
                    if ($item->id_partida_pi > 0) {
                        // lista de item con monto y partida
                        if ($requerimientoPago->id_moneda == 1) { // soles
                            $montoPorUtilizarPorPartida[$item->id_partida_pi] = floatval($montoPorUtilizarPorPartida[$item->id_partida_pi] ?? 0) + floatval($item->subtotal);
                        } else if ($requerimientoPago->id_moneda == 2) {
                            $montoPorUtilizarPorPartida[$item->id_partida_pi] = ((floatval($montoPorUtilizarPorPartida[$item->id_partida_pi] ?? 0) + floatval($item->subtotal)) *  $this->getTipoCambioVenta($fechaPago));
                        }
                    }
                }
            }

            foreach ($montoPorUtilizarPorPartida as $partidaId => $monto) {
                $tieneSaldoPartida = $this->TieneSaldoLaPartida($partidaId, $nombreMesAux, number_format($monto, 2, '.', ''), number_format($totalPago, 2, '.', ''),$fase);
                if ($tieneSaldoPartida != []) {
                    $data[] = $tieneSaldoPartida;
                }
            }
        }

        return $data;
    }

    public function tienePresupuestoLasPartidasDelRequerimientoLogistico($idRequerimientoLogistico, $numeroMes, $totalPago, $fechaPago, $fase)
    {

        $mesLista = ['1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril', '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto', '9' => 'setiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
        $nombreMes = $mesLista[$numeroMes];
        $nombreMesAux = $nombreMes . '_aux';
        $montoPorUtilizarPorPartida = [];
        $data = [];

        if ($idRequerimientoLogistico > 0) {
            $detalleRequerimientoLogistico = DetalleRequerimiento::where('id_requerimiento', $idRequerimientoLogistico)->get();
            $requerimiento = Requerimiento::find($idRequerimientoLogistico);
            foreach ($detalleRequerimientoLogistico as $item) {
                if ($item->estado != 7 && $item->id_detalle_requerimiento > 0) {
                    if ($item->id_partida_pi > 0) {
                        // lista de item con monto y partida
                        if ($requerimiento->id_moneda == 1) {
                            $montoPorUtilizarPorPartida[$item->id_partida_pi] = floatval($montoPorUtilizarPorPartida[$item->id_partida_pi] ?? 0) + floatval($item->subtotal);
                        } elseif ($requerimiento->id_moneda == 2) {
                            $montoPorUtilizarPorPartida[$item->id_partida_pi] = ((floatval($montoPorUtilizarPorPartida[$item->id_partida_pi] ?? 0) + floatval($item->subtotal)) * $this->getTipoCambioVenta($fechaPago));
                        }
                    }
                }
            }

            foreach ($montoPorUtilizarPorPartida as $partidaId => $monto) {
                $tieneSaldoPartida = $this->TieneSaldoLaPartida($partidaId, $nombreMesAux, $monto, $totalPago,$fase);
                if ($tieneSaldoPartida != []) {
                    $data[] = $tieneSaldoPartida;
                }
            }
        }

        return $data;
    }


    function obtenerMontoAcumuladoPartidas($data){
        $montoAcumuladoDePartidas=0;
        foreach ($data as $key => $value) {
            $montoAcumuladoDePartidas= floatval($montoAcumuladoDePartidas)+ floatval($value['monto_soles_documento_actual']); 
        }
        return number_format($montoAcumuladoDePartidas,2,'.','');
    }

    function obtenerMontoSinVinculoConPresupuestoInterno($idOrden){

        $montoSinVinculoAPresupustoInterno=0;
        if($idOrden>0){
            $detalleOrden = OrdenCompraDetalle::where('id_orden_compra',$idOrden)->get();
            $orden = Orden::find($idOrden);

            foreach ($detalleOrden as $item) {
                if($item->estado !=7 && $item->id_detalle_requerimiento >0){
                    $detalleRequerimiento = DetalleRequerimiento::find($item->id_detalle_requerimiento);
                    if($detalleRequerimiento->id_partida_pi ==null || $detalleRequerimiento->id_partida_pi==''){
                        if($orden->incluye_igv ==true ){
                            $montoSinVinculoAPresupustoInterno= floatval($montoSinVinculoAPresupustoInterno??0) + floatval($item->subtotal * 1.18);
                        }else{
                            $montoSinVinculoAPresupustoInterno= floatval($montoSinVinculoAPresupustoInterno??0) + floatval($item->subtotal);

                        }
                    }

                }

            }
        }

        return number_format($montoSinVinculoAPresupustoInterno,2,'.','');
    }

    public function TieneSaldoLaPartida($idPartidaDePresupuestoInterno, $nombreMesAux, $monto, $totalPago,$fase)
    {

        $data = [];
        $mensaje="";
        $detallePresupuestoInterno = PresupuestoInternoDetalle::find($idPartidaDePresupuestoInterno);
        $presupuesto = (new PresupuestoInternoController)->obtenerDetallePresupuestoInterno($detallePresupuestoInterno->id_presupuesto_interno);
        // $obtenerMontoUtilizadoPorRequerimientosLogisticosYPagoConPartida = $this->obtenerMontoUtilizadoPorRequerimientosLogisticosYPagoConPartida($idPartidaDePresupuestoInterno);
        $montoComprometido = $this->obtenerMontoTotalComprometido($idPartidaDePresupuestoInterno,$nombreMesAux,$fase);
        if (isset($presupuesto[0]->detalle)) {

            foreach (($presupuesto[0]->detalle) as $detalle) {
                if ($detalle->id_presupuesto_interno_detalle == $idPartidaDePresupuestoInterno) {
                    if ($detalle->$nombreMesAux >= (floatval($monto) + floatval($montoComprometido)) ) { // valida el ppto disponible en el mes con el monto total de la partida de item y tambien compara que el monto envia a pago(que se envia ingresando un monto en interfaz) no sea mayor
                        $mensaje="Tiene suficiente saldo en partida";
                        $data = [
                            'tiene_presupuesto' => true,
                            'id_presupuesto_interno' => $detallePresupuestoInterno->id_presupuesto_interno,
                            'id_partida' => $detalle->id_presupuesto_interno_detalle ,
                            'partida' => $detalle->partida,
                            'descripcion' => $detalle->descripcion,
                            'monto_aux' => $detalle->$nombreMesAux,
                            'monto_soles_documento_actual' => $monto,
                            'monto_soles_comprometido_otros_documentos' => $montoComprometido,
                            'mensaje'=>$mensaje
                            // 'monto_utilizado_por_otros_documentos' => $obtenerMontoUtilizadoPorRequerimientosLogisticosYPagoConPartida[0]['monto_total_partida_ulitizado'] ?? 0,
                        ];
                    } else {
                        if($detalle->$nombreMesAux < floatval($montoComprometido)){
                            $mensaje.="Saldo insuficiente, otros documentos estan con saldo comprometido.";
                        }   

                        $mensaje.="No tiene Suficiente presupuesto en la partida ".$detalle->partida." - ".$detalle->descripcion.", en el ppto del mes ".substr($nombreMesAux, 0,-4);
                        $data = [

                            'tiene_presupuesto' => false,
                            'id_presupuesto_interno' => $detallePresupuestoInterno->id_presupuesto_interno,
                            'id_partida' => $detalle->id_presupuesto_interno_detalle ,
                            'partida' => $detalle->partida,
                            'descripcion' => $detalle->descripcion,
                            'monto_aux' => $detalle->$nombreMesAux,
                            'monto_soles_documento_actual' => $monto,
                            'monto_soles_comprometido_otros_documentos' => $montoComprometido,
                            'mensaje'=>$mensaje
                            // 'monto_utilizado_por_otros_documentos' => $obtenerMontoUtilizadoPorRequerimientosLogisticosYPagoConPartida[0]['monto_total_partida_ulitizado'] ?? 0,
                        ];
                    }
                }
            }
        }
        return $data;
    }

    function obtenerMontoTotalComprometido($idPartida,$nombreMesAux,$fase){

        $montoTotal=0;
        $mesLista = ['1' => 'enero_aux', '2' => 'febrero_aux', '3' => 'marzo_aux', '4' => 'abril_aux', '5' => 'mayo_aux', '6' => 'junio_aux', '7' => 'julio_aux', '8' => 'agosto_aux', '9' => 'setiembre_aux', '10' => 'octubre_aux', '11' => 'noviembre_aux', '12' => 'diciembre_aux'];


        
        if($fase=='FASE_APROBACION' || $fase=="FASE_AUTORIZACION"){
            foreach($mesLista as $keyMes => $mesAux) {
                if($mesAux ==$nombreMesAux ){
                    $numeroMes = str_pad($keyMes,2,"0",STR_PAD_LEFT); 
    
                    if($fase=='FASE_APROBACION'){
                        $historialPresupuestoInternoComprometido = HistorialPresupuestoInternoSaldo::where([['id_partida',$idPartida],['mes',$numeroMes],['tipo','SALIDA'],['estado','=',2]])->get();
                    }elseif($fase =='FASE_AUTORIZACION'){
                        $historialPresupuestoInternoComprometido = HistorialPresupuestoInternoSaldo::where([['id_partida',$idPartida],['mes',$numeroMes],['tipo','SALIDA'],['estado','=',2]])->get();
                    }
                
    
                    if(isset($historialPresupuestoInternoComprometido)){
                        foreach ($historialPresupuestoInternoComprometido as $valueHistorial) {
                            $montoTotal = $montoTotal + floatval($valueHistorial->importe);
                        }
                    }
                }
            }
    
        }

        return number_format($montoTotal,2,'.','');

    }
}
