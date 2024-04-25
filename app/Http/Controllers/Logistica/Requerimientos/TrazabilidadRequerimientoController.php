<?php

namespace App\Http\Controllers\Logistica\Requerimientos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Configuracion\AccesosUsuarios;
use App\Models\Tesoreria\RegistroPago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrazabilidadRequerimientoController extends Controller
{


    public function viewTrazabilidad()
    {

        $roles = Auth::user()->getAllRol(); //Usuario::getAllRol(Auth::user()->id_usuario);
        $gruposUsuario = Auth::user()->getAllGrupo();

        $array_accesos = [];
        $accesos_usuario = AccesosUsuarios::where('estado', 1)->where('id_usuario', Auth::user()->id_usuario)->get();
        foreach ($accesos_usuario as $key => $value) {
            array_push($array_accesos, $value->id_acceso);
        }
        $array_accesos_botonera = array();
        $accesos_botonera = AccesosUsuarios::where('accesos_usuarios.estado', '=', 1)
            ->select('accesos.*')
            ->join('configuracion.accesos', 'accesos.id_acceso', '=', 'accesos_usuarios.id_acceso')
            ->where('accesos_usuarios.id_usuario', Auth::user()->id_usuario)
            ->where('accesos_usuarios.id_modulo', 57)
            ->where('accesos_usuarios.id_padre', 54)
            ->get();
        foreach ($accesos_botonera as $key => $value) {
            $value->accesos;
            array_push($array_accesos_botonera, $value->accesos->accesos_grupo);
        }
        return view('logistica.requerimientos.trazabilidad.grafica_trazabilidad', compact(
            'roles',
            'gruposUsuario',
            'array_accesos',
            'array_accesos_botonera'
        ));

    }


    public function mostrarDocumentosByRequerimiento($id_requerimiento)
    {

        // $nodos=[
        //     'nodo1'=>['id_nodo'=>1,'data'=>$requerimiento,'input'=>1,'ouput'=>2],
        //     'nodo2'=>['id_nodo'=>2,'data'=>$flujo_aprobacion,'input'=>1,'ouput'=>3]
        // ];
        $requerimiento = DB::table('almacen.alm_req')
            ->select('alm_req.id_requerimiento', 'alm_req.codigo', 'alm_req.fecha_requerimiento', 'alm_req.concepto', 'alm_req.estado', 'adm_estado_doc.estado_doc AS estado_descripcion', 'alm_req.fecha_registro')
            ->leftJoin('administracion.adm_estado_doc', 'alm_req.estado', '=', 'adm_estado_doc.id_estado_doc')
            ->where('id_requerimiento', $id_requerimiento)
            ->first();
        //   $requerimiento = collect($requerimiento);
        // $requerimiento->put('id_nodo',1);


         $flujo_aprobacion = DB::table('administracion.adm_documentos_aprob')
        ->select('adm_aprobacion.id_aprobacion','adm_vobo.descripcion as descripcion_vobo','adm_aprobacion.detalle_observacion','adm_aprobacion.fecha_vobo','sis_usua.nombre_corto as nombre_usuario')
        ->leftJoin('almacen.alm_req', function ($join) {
            $join->on('adm_documentos_aprob.id_doc', '=', 'alm_req.id_requerimiento');
            $join->where('adm_documentos_aprob.id_tp_documento', '=', 1);
        })
        ->leftJoin('administracion.adm_aprobacion', 'adm_aprobacion.id_doc_aprob', '=', 'adm_documentos_aprob.id_doc_aprob')
        ->leftJoin('administracion.adm_vobo', 'adm_vobo.id_vobo', '=', 'adm_aprobacion.id_vobo')
        ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'adm_aprobacion.id_usuario')
        ->where('alm_req.id_requerimiento', $id_requerimiento)
        ->orderBy('adm_aprobacion.fecha_vobo','asc')
        ->get();



        $ordenes = DB::table('logistica.log_det_ord_compra')
            ->select(
            'log_ord_compra.id_orden_compra',
            'log_ord_compra.codigo',
            'log_ord_compra.fecha_registro',
            'log_ord_compra.fecha_autorizacion',
            'log_ord_compra.fecha_solicitud_pago',
            'log_ord_compra.estado',
            'log_ord_compra.estado_pago',
            'estados_compra.descripcion AS estado_descripcion',
            'usu_autoriza_pago.nombre_corto as usuario_autoriza_pago',
            'requerimiento_pago_estado.descripcion as descripcion_estado_pago')
            ->join('logistica.log_ord_compra', 'log_ord_compra.id_orden_compra', '=', 'log_det_ord_compra.id_orden_compra')
            ->join('almacen.alm_det_req', 'alm_det_req.id_detalle_requerimiento', '=', 'log_det_ord_compra.id_detalle_requerimiento')
            ->join('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
            ->leftJoin('logistica.estados_compra', 'log_ord_compra.estado', '=', 'estados_compra.id_estado')
            ->leftJoin('configuracion.sis_usua as usu_autoriza_pago', 'usu_autoriza_pago.id_usuario', '=', 'log_ord_compra.usuario_autorizacion')
            ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'log_ord_compra.estado_pago')

            ->where([
                ['alm_req.id_requerimiento', '=', $id_requerimiento],
                // ['log_ord_compra.estado', '!=', 7]
            ])
            ->distinct()
            ->get();

            $registoPago=[];
            $flujoDeEnvioApago=[];
            foreach ($ordenes as $key => $orden) {
                if($orden->estado !=7){
                    $registoPago = RegistroPago::with('adjunto')->where([['id_oc',$orden->id_orden_compra],['estado',1]])->get();
                }
                
                if($orden->fecha_autorizacion){
                    
                    $flujoDeEnvioApago[]=[
                        'id_orden'=>$orden->id_orden_compra,
                        'fecha_solicitud_pago'=>$orden->fecha_solicitud_pago,
                        'fecha_autorizacion'=>$orden->fecha_autorizacion,
                        'usuario_autoriza_pago'=>$orden->usuario_autoriza_pago,
                        'descripcion_estado_pago'=>$orden->descripcion_estado_pago,
                    ];
                }

            }



        $reservas = DB::table('almacen.alm_reserva')
            ->select('alm_reserva.*','alm_req.id_requerimiento')
            ->join('almacen.alm_det_req', 'alm_det_req.id_detalle_requerimiento', '=', 'alm_reserva.id_detalle_requerimiento')
            ->join('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
            ->where([
                ['alm_req.id_requerimiento', '=', $id_requerimiento],
                ['alm_reserva.estado', '=', 1]
            ])
            // ->whereNull('id_guia_com_det')
            ->distinct()
            ->get();

        $guias = DB::table('almacen.alm_det_req')
            ->select(
                'mov_alm.id_mov_alm as id_ingreso',
                'mov_alm.codigo as codigo_ingreso',
                'guia_com.id_guia',
                'guia_com.estado as estado_guia',
                'guia_com.serie as serie_guia',
                'guia_com.numero as numero_guia',
                'log_ord_compra.id_orden_compra',
                'guia_com.fecha_emision',
                'guia_com.fecha_registro'
            )
            ->join('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
            ->join('logistica.log_det_ord_compra', 'log_det_ord_compra.id_detalle_requerimiento', '=', 'alm_det_req.id_detalle_requerimiento')
            ->join('logistica.log_ord_compra', 'log_ord_compra.id_orden_compra', '=', 'log_det_ord_compra.id_orden_compra')
            ->join('almacen.guia_com_det', 'guia_com_det.id_oc_det', '=', 'log_det_ord_compra.id_detalle_orden')
            ->join('almacen.guia_com', 'guia_com.id_guia', '=', 'guia_com_det.id_guia_com')
            ->join('almacen.mov_alm_det', 'mov_alm_det.id_guia_com_det', '=', 'guia_com_det.id_guia_com_det')
            ->join('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'mov_alm_det.id_mov_alm')
            ->where([
                ['alm_req.id_requerimiento', '=', $id_requerimiento],
                ['log_det_ord_compra.estado', '!=', 7],
                ['guia_com.estado', '!=', 7],
                ['mov_alm.estado', '!=', 7],
            ])
            ->distinct()
            ->get();

        $docs = DB::table('almacen.alm_det_req')
            ->select(
                'doc_com.id_doc_com',
                'doc_com.serie as serie_doc',
                'doc_com.numero as numero_doc',
                'doc_com.estado as estado_doc',
                'mov_alm.id_mov_alm as id_ingreso',
            )
            ->join('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
            ->join('logistica.log_det_ord_compra', 'log_det_ord_compra.id_detalle_requerimiento', '=', 'alm_det_req.id_detalle_requerimiento')
            ->join('almacen.guia_com_det', 'guia_com_det.id_oc_det', '=', 'log_det_ord_compra.id_detalle_orden')
            ->join('almacen.guia_com', 'guia_com.id_guia', '=', 'guia_com_det.id_guia_com')
            ->join('almacen.mov_alm_det', 'mov_alm_det.id_guia_com_det', '=', 'guia_com_det.id_guia_com_det')
            ->join('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'mov_alm_det.id_mov_alm')
            ->join('almacen.doc_com_det', 'doc_com_det.id_guia_com_det', '=', 'guia_com_det.id_guia_com_det')
            ->join('almacen.doc_com', function ($join) {
                $join->on('doc_com.id_doc_com', '=', 'doc_com_det.id_doc');
                $join->where('doc_com.estado', '!=', 7);
            })
            ->where([
                ['alm_req.id_requerimiento', '=', $id_requerimiento],
                ['log_det_ord_compra.estado', '!=', 7],
                ['guia_com.estado', '!=', 7],
                ['mov_alm.estado', '!=', 7]
            ])
            ->distinct()
            ->get();

        // $transferencias = DB::table('almacen.trans')
        //     ->select(
        //         'trans.id_transferencia',
        //         'trans.codigo',
        //         'ingreso.id_mov_alm as id_ingreso',
        //         'salida.id_mov_alm as id_salida',
        //         'guia_com.serie as serie_guia_com',
        //         'guia_ven.serie as serie_guia_ven',
        //         'guia_com.numero as numero_guia_com',
        //         'guia_ven.numero as numero_guia_ven',
        //     )
        //     ->leftJoin('almacen.guia_com', function ($join) {
        //         $join->on('guia_com.id_guia', '=', 'trans.id_guia_com');
        //         $join->where('guia_com.estado', '!=', 7);
        //     })
        //     ->leftJoin('almacen.mov_alm as ingreso', function ($join) {
        //         $join->on('ingreso.id_guia_com', '=', 'guia_com.id_guia');
        //         $join->where('ingreso.estado', '!=', 7);
        //     })
        //     ->leftJoin('almacen.guia_ven', function ($join) {
        //         $join->on('guia_ven.id_guia_ven', '=', 'trans.id_guia_ven');
        //         $join->where('guia_ven.estado', '!=', 7);
        //     })
        //     ->leftJoin('almacen.mov_alm as salida', function ($join) {
        //         $join->on('salida.id_guia_ven', '=', 'guia_ven.id_guia_ven');
        //         $join->where('salida.estado', '!=', 7);
        //     })
        //     ->where([
        //         ['trans.id_requerimiento', '=', $id_requerimiento],
        //         ['trans.estado', '!=', 7]
        //     ])
        //     ->get();

        // $transformaciones = DB::table('almacen.transformacion')
        //     ->select(
        //         'transformacion.id_transformacion',
        //         'transformacion.codigo',
        //         'guia_ven.serie',
        //         'guia_ven.numero'
        //     )
        //     ->join('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'transformacion.id_od')
        //     ->leftJoin('almacen.guia_ven', function ($join) {
        //         $join->on('guia_ven.id_od', '=', 'orden_despacho.id_od');
        //         $join->where('orden_despacho.estado', '!=', 7);
        //     })
        //     ->where([
        //         ['orden_despacho.id_requerimiento', '=', $id_requerimiento],
        //         ['orden_despacho.estado', '!=', 7],
        //         ['transformacion.estado', '!=', 7],
        //     ])
        //     ->get();

        $despacho = DB::table('almacen.orden_despacho')
            ->select(
                'mov_alm.id_mov_alm as id_salida',
                'orden_despacho.codigo',
                'orden_despacho.fecha_despacho',
                'guia_ven.serie',
                'guia_ven.numero'
            )
            ->leftJoin('almacen.guia_ven', function ($join) {
                $join->on('guia_ven.id_od', '=', 'orden_despacho.id_od');
                $join->where('guia_ven.estado', '!=', 7);
            })
            ->leftJoin('almacen.mov_alm', function ($join) {
                $join->on('mov_alm.id_guia_ven', '=', 'guia_ven.id_guia_ven');
                $join->where('mov_alm.estado', '!=', 7);
            })
            ->where([
                ['orden_despacho.id_requerimiento', '=', $id_requerimiento],
                ['orden_despacho.aplica_cambios', '=', false],
                ['orden_despacho.estado', '!=', 7]
            ])
            ->get();

        // $guia_transportista = DB::table('almacen.orden_despacho')
        //     ->select(
        //         'orden_despacho.serie',
        //         'orden_despacho.numero',
        //         'orden_despacho.fecha_transportista',
        //         'orden_despacho.codigo_envio',
        //         'orden_despacho.importe_flete'
        //     )
        //     ->where([
        //         ['orden_despacho.id_requerimiento', '=', $id_requerimiento],
        //         ['orden_despacho.aplica_cambios', '=', false],
        //         ['orden_despacho.estado', '!=', 7]
        //     ])
        //     ->first();

        // $estados_envio = DB::table('almacen.orden_despacho_obs')
        //     ->select('orden_despacho_obs.*', 'estado_envio.descripcion as accion_descripcion')
        //     ->join('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'orden_despacho_obs.id_od')
        //     ->join('almacen.estado_envio', 'estado_envio.id_estado', '=', 'orden_despacho_obs.accion')
        //     ->where([
        //         ['orden_despacho.id_requerimiento', '=', $id_requerimiento],
        //         ['orden_despacho.aplica_cambios', '=', false],
        //         ['orden_despacho.estado', '!=', 7]
        //     ])
        //     ->get();

       


        $numeroDeNodo=1;
        // $nodos=[];

            $nodos[]=[
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'requerimiento',
                'grupo'   => 'A',
                'data'   => $requerimiento,
                'output'=> [2]
            ];

            $nodos[]=[
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'flujo_aprobacion',
                'grupo'   => 'B',
                'data'   => $flujo_aprobacion
            ];

            foreach ($reservas as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'reserva',
                'grupo'   => 'C',
                'data'   => $value,
                ];
            }
            foreach ($ordenes as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'orden',
                'grupo'   => 'C',
                'data'   => $value
                ];
            }


            foreach ($flujoDeEnvioApago as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'flujo_pago',
                'grupo'   => 'D',
                'data'   => $value
                ];
            }
  

            foreach ($registoPago as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'registro_pago',
                'grupo'   => 'F',
                'data'   => $value
                ];
            }

            foreach ($guias as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'ingreso',
                'grupo'   => 'G',
                'data'   => $value
                ];
            }

            foreach ($despacho as $key => $value) {
                $nodos[]= [
                'id_nodo' => $numeroDeNodo++,
                'plantilla'   => 'despacho',
                'grupo'   => 'H',
                'data'   => $value
                ];
            }



            //conexión de nodos 
            foreach ($nodos as $key1 => $value1) {
                if($value1['plantilla'] =='flujo_aprobacion'){
                    foreach ($nodos as $key2 => $value2) {
                        if($value2['plantilla']=='reserva'){
                            $nodos[$key1]['output'][]=$value2['id_nodo'];
                        }
                    }
                }
            }
            foreach ($nodos as $key1 => $value1) {
                if($value1['plantilla'] =='flujo_aprobacion'){
                    foreach ($nodos as $key2 => $value2) {
                        if($value2['plantilla']=='orden'){
                            $nodos[$key1]['output'][]=$value2['id_nodo'];
                        }
                    }
                }
            }

            foreach ($nodos as $key1 => $value1) {
                if($value1['plantilla'] =='orden'){
                    foreach ($nodos as $key2 => $value2) {
                        if($value2['plantilla']=='flujo_pago'){
                           if($value2['data']['id_orden'] == $value1['data']->id_orden_compra){
                            $nodos[$key1]['output'][]=$value2['id_nodo'];
                           }
                        }
                    }
                }
            }


            foreach ($nodos as $key1 => $value1) {
                if($value1['plantilla'] =='orden'){
                    foreach ($nodos as $key2 => $value2) {
                        if($value2['plantilla']=='ingreso'){
                           if($value2['data']->id_orden_compra == $value1['data']->id_orden_compra){
                            $nodos[$key1]['output'][]=$value2['id_nodo'];
                           }
                        }
                    }
                }
            }

            foreach ($nodos as $key1 => $value1) {
                if($value1['plantilla'] =='flujo_pago'){
                    foreach ($nodos as $key2 => $value2) {
                        if($value2['plantilla']=='registro_pago'){
                           if($value2['data']['id_oc'] == $value1['data']['id_orden']){
                            $nodos[$key1]['output'][]=$value2['id_nodo'];
                           }
                        }
                    }
                }
            }



      

            
            return response()->json([
                $nodos
                // 'requerimiento' => $requerimiento,
                // 'flujo_aprobacion' => $flujo_aprobacion,
                // 'ordenes' => $ordenes,
                // 'flujo_envio_pago' => $flujoDeEnvioApago,
                // 'pagos' => $registoPago,
                // 'reservado' => (count($reservas) > 0 ? true : false),
                // 'reservas' => $reservas,
                // 'ingresos' => $guias,
                // 'docs' => $docs,
                // 'transferencias' => $transferencias,
                // 'transformaciones' => $transformaciones,
                // 'despacho' => $despacho,
                // 'estados_envio' => $estados_envio,
                // 'guia_transportista' => $guia_transportista,
            ]);
    }
}
