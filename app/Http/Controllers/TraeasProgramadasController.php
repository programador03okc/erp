<?php

namespace App\Http\Controllers;

use App\Models\Configuracion\LogActividad;
use App\Models\Contabilidad\CuentaContribuyente;
use App\Models\Logistica\Orden;
use App\Models\Logistica\OrdenesView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraeasProgramadasController extends Controller
{
    //
    public function enviarPagoAutomatico(){
        $data = OrdenesView::where([['id_estado', '>=', 1], ['id_tp_documento', '!=', 13]])->whereNotNull('enviar_pago');

        $fecha = new Carbon();
        $inicio = $fecha::now()->startOfYear();
        $fin = $fecha::now()->endOfMonth();

        $data = $data->whereBetween('fecha_emision', [$inicio, $fin]);
        $data = $data->orderBy('id', 'desc')->limit(5)->get();

        $data_enviada = array();
        $data_espera = array();

        $usuario = (object) array('id_usuario'=>202);
        // return $usuario->id_usuario;
        foreach ($data as $key => $value) {
            if($value->enviar_pago || $value->enviar_pago!==null || $value->enviar_pago!==''){
                if(date('Y-m-d') === date("Y-m-d", strtotime($value->enviar_pago))){
                    $contribuyente = CuentaContribuyente::with("banco.contribuyente", "moneda", "tipoCuenta")->where([["id_contribuyente", $value->id_contribuyente], ["estado", "!=", 7]])->first();

                    // return $contribuyente;

                    if($contribuyente){
                        $orden = Orden::where('codigo',$value->codigo)->whereNotIn('estado_pago',[5,6])->where('enviado','f')->first();
                        if($orden){

                            $valor_anterior = $orden = Orden::where('codigo',$value->codigo)->whereNotIn('estado_pago',[5,6])->where('enviado','f')->first();
                            // return $orden;
                            $orden->estado_pago = 8; //enviado a pago
                            $orden->id_tipo_destinatario_pago = 2;
                            $orden->id_prioridad_pago = 3; // prioridad

                            // if ($value->id_tipo_destinatario_pago == 2) {
                                $orden->id_cta_principal = $contribuyente->id_cuenta_contribuyente;
                            // } elseif ($value->id_tipo_destinatario_pago == 1) {
                            //     $orden->id_cuenta_persona_pago = $request->id_cuenta;
                            // }
                            // $orden->id_persona_pago = $request->id_persona;
                            $orden->comentario_pago = 'Enviado desde pago automática.';
                            $orden->tiene_pago_en_cuotas = false;
                            $orden->fecha_solicitud_pago = Carbon::now();
                            $orden->tipo_impuesto = null;
                            $orden->enviado = true;
                            $orden->save();

                            // $usuario = array('id_usuario'=>202);
                            LogActividad::registrar($usuario, 'Gestion de ordenes', 3, $orden->getTable(), $valor_anterior, $orden, 'Se realizó el envío de modo automatico ', 'Logística');
                            array_push($data_enviada, $orden);

                        }
                    }else{
                        array_push($data_espera, $value);
                    }
                }
            }else{
                array_push($data_espera, $value);
            }
        }
        return response()->json(["no_enviado"=>$data_espera,"enviado"=>$data_enviada],200);
    }
}
