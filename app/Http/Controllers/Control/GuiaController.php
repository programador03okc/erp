<?php

namespace App\Http\Controllers\Control;

use App\Exports\GuiasRemisionExport;
use App\Helpers\ConfiguracionHelper;
use App\Http\Controllers\Controller;
use App\Models\Configuracion\Usuario;
use App\Models\Contabilidad\Transportista;
use App\Models\Control\Archivador;
use App\Models\Control\ControlGuiaView;
use App\Models\Control\GuiaAlmacen;
use App\Models\Control\GuiaDespacho;
use App\Models\Control\Historial;
use App\Models\Control\Observaciones;
use App\Models\Control\OrdenCompraView;
use App\Models\Control\TipoMovimiento;
use App\Models\Distribucion\OrdenDespacho;
use App\Models\Tesoreria\Contribuyente;
use App\Models\Tesoreria\Empresa;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GuiaController extends Controller
{
    //
    public function index()
    {
        $tipoMovimientos = TipoMovimiento::orderBy('descripcion', 'asc')->get();
        $responsables = Usuario::select('id_usuario', 'nombre_corto')->where('nombre_corto', 'NOT LIKE', '%Suplente%')->orderBy('nombre_corto', 'asc')->get();
        $empresas = Empresa::all();
        // return $empresas[0]->contribuyente;exit;
        return view('control.guias.index', get_defined_vars());
    }

    public function listar(Request $request)
    {

        $data = ControlGuiaView::whereIn('estado_registro',[0,1]);
        // ->get();
        if(!empty($request->empresa_id)){
            $data = $data->where('empresa_id','=',$request->empresa_id);
        }
        if(!empty($request->estado)){
            $data = $data->where('estado',$request->estado);
        }
        if(!empty($request->fecha_inicio)){
            $data = $data->whereDate('fecha_guia','>=',$request->fecha_inicio);
        }
        if(!empty($request->fecha_final)){
            $data = $data->whereDate('fecha_guia','<=',$request->fecha_final);
        }
        $data = $data->get();
        return DataTables::of($data)
        ->editColumn('fecha_guia', function ($data) { return date('d/m/Y', strtotime($data->fecha_guia)); })
        ->addColumn('orden', function ($data) {
            if ($data->ocam != null) {
                $oc = ($data->oc_virtual != null) ? '<br>'.$data->oc_virtual : '';
                return $data->ocam.$oc;
            } else {
                return ($data->oc_virtual != null) ? $data->oc_virtual : '';
            }
        })
        ->addColumn('documentos_agile', function ($data) {
            if ($data->codigo_oportunidad != null) {
                $requerimiento = ($data->codigo_requerimiento != null) ? '<br>'.$data->codigo_requerimiento : '';
                return $data->codigo_oportunidad.$requerimiento;
            } else {
                return ($data->codigo_requerimiento != null) ? $data->codigo_requerimiento : '';
            }
        })
        ->addColumn('documentos_transportista', function ($data) {
            if ($data->guia_transportista != null) {
                $factura = ($data->factura_transportista != null) ? '<br>'.$data->factura_transportista : '';
                return $data->guia_transportista.$factura;
            } else {
                return ($data->factura_transportista != null) ? $data->factura_transportista : '';
            }
        })
        ->addColumn('adj_guia', function ($data) {
            $ruta = url('js/control/documentos/guias_remision/gr_adjuntos');
            return ($data->adjunto_guia != null) ? '<a href="'.$ruta.'/'.$data->adjunto_guia.'" target="_blank">Descargar GR</a>' : '';
        })
        ->addColumn('adj_guia_sellada', function ($data) {
            $ruta = url('js/control/documentos/guias_remision/gr_selladas');
            return ($data->adjunto_guia_sellada != null) ? '<a href="'.$ruta.'/'.$data->adjunto_guia_sellada.'" target="_blank">Descargar GR sellada</a>' : '';
        })
        ->addColumn('estado_gci', function ($data) {
            // $control_guia = GuiaAlmacen::where('id',$data->id_control_logistica)->first();
            $tipo = 'danger';
            if($data->recepcion_gci){
                $tipo = 'success';
            }
            return '<span class="badge bg-'.$tipo.'-transparent rounded-pill text-'.$tipo.' p-2 px-3">'.($data->recepcion_gci?'Recepcionado':'Sin documento').'</span>';
        })
        ->addColumn('accion', function ($data) {
            $log_salida = ($data->id_control_logistica != null) ? $data->id_control_logistica : 0;
            $opcion = '<li><a class="text-primary" href="javascript:void(0)" onclick="verHistorial('.$data->id_control_almacen.')">Ver historial</a></li>';

            if ($data->estado_registro > 0) {
                $opcion .= '<li><a class="text-primary" href="javascript:void(0)" onclick="agregarObservacion('.$data->id_control_almacen.', '.$log_salida.')">Agregar observaciones</a></li>';
                switch ($data->estado) {
                    case 'ALMACEN':
                        if ($data->estado_gr != 'NORMAL') {
                            $opcion .= '<li><a class="text-primary" href="javascript:void(0)" onclick="archivarGuia('.$data->id_control_almacen.', '.$log_salida.')">Archivar GR</a></li>';
                        } else {
                            $opcion .= '<li><a class="text-primary" href="javascript:void(0)" onclick="cargarTransportista('.$data->id_control_almacen.')">Cargar datos transp.</a></li>';
                        }
                    break;
                    case 'LOG. DE SALIDA':
                        if ($data->check_guia_envio == 'SI' && $data->check_cargo_guia_envio == 'SI') {
                            $opcion .= '<li><a class="text-primary" href="javascript:void(0)" onclick="archivarGuia('.$data->id_control_almacen.', '.$log_salida.')">Archivar GR</a></li>';
                        } else {
                            if ($data) {
                                $opcion .= '<li><a class="text-primary" href="javascript:void(0)" onclick="actualizarDatosLogistica('.$data->id_control_logistica.')">Actualizar GR</a></li>';
                            }
                        }
                    break;
                }
            }

            // if (Auth::user()->id_usuario == 36 || Auth::user()->id_usuario == 16 || Auth::user()->id_usuario == 144 || Auth::user()->id_usuario == 1) {
            if (in_array(Auth::user()->id_usuario, [36,16,1,144,135])) {
                $opcion .= '
                <li><a class="text-primary" href="javascript:void(0)" onclick="editar('.$data->id_control_almacen.')">Editar</a></li>';
            }

            if (Auth::user()->id_usuario == 36 || Auth::user()->id_usuario == 16 || Auth::user()->id_usuario == 144 || Auth::user()->id_usuario == 1) {
                $opcion .= '
                <li><a class="text-primary" href="javascript:void(0)" onclick="anular('.$data->id_control_almacen.')">Anular</a></li>
                <li><a class="text-primary" href="javascript:void(0)" onclick="eliminar('.$data->id_control_almacen.')">Eliminar</a></li>';
            }

            if (in_array(Auth::user()->id_usuario, [16,1]) && !$data->recepcion_gci) {
                $opcion .= '
                <li><a class="text-primary" href="javascript:void(0)" onclick="eviarGRControl('.$data->id_control_almacen.')">Recepción GCI</a></li>';
            }

            return $boton= '<div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    '.$opcion.'
                </ul>
            </div>';
            // return '<div class="btn-group mt-2 mb-2">
            //     <button type="button" class="btn btn-primary btn-sm btn-pill dropdown-toggle" data-bs-toggle="dropdown">
            //             <i class="fe fe-settings"></i> <span class="caret"></span>
            //         </button>
            //     <ul class="dropdown-menu" role="menu" style="">
            //         '.$opcion.'
            //     </ul>
            // </div>';
        })
        ->addColumn('empresa_codigo', function ($data) {
            $empresa = Empresa::find($data->empresa_id);
            return ($empresa?$empresa->codigo:'-');
        })
        ->rawColumns(['estado_gci','orden', 'documentos_agile', 'documentos_transportista', 'adj_guia', 'adj_guia_sellada', 'accion'])->make(true);
    }

    public function guardarAlmacen(Request $request) {
        DB::beginTransaction();
        try {
            // $consulta = GuiaAlmacen::where('codigo', $request->codigo)->whereNull('deleted_at')->count();

            // if ($consulta > 0) {
            //     $respuesta = 'duplicado';
            //     $alerta = 'info';
            //     $mensaje = 'Duplicado, la GR ingresada ya está registrada';
            //     $error = '';
            // } else {
                $data = GuiaAlmacen::firstOrNew(['id' => $request->id]);
                    $data->codigo = $request->codigo;
                    $data->documento = $request->documento;
                    $data->sede = Str::upper($request->sede);
                    $data->tipo_movimiento_id = $request->tipo_movimiento_id;
                    $data->destino = Str::upper($request->destino);
                    $data->ocam = Str::upper($request->orden);
                    $data->oc_virtual = Str::upper($request->orden_virtual);
                    $data->codigo_oportunidad = Str::upper($request->codigo_cdp);
                    $data->codigo_requerimiento = Str::upper($request->codigo_requerimiento);
                    $data->procesado_agile = ($request->procesado_agile) ? $request->procesado_agile : false;
                    $data->procesado_softlink = ($request->procesado_softlink) ? $request->procesado_softlink : false;
                    $data->id_responsable = $request->id_responsable;
                    $data->fecha_ingreso = $request->fecha_ingreso;
                    $data->marca = Str::upper($request->marca);
                    $data->descripcion = Str::upper($request->descripcion);
                    $data->marca = Str::upper($request->marca);
                    $data->empresa = Str::upper($request->empresa);
                    $data->entidad = Str::upper($request->entidad);
                    $data->fecha_guia = $request->fecha;
                    $data->estado = 1;
                    $data->estado_gr = $request->estado_gr;
                    $data->id_usuario = Auth::user()->id_usuario;
                    $data->empresa_id = $request->empresa_id;
                $data->save();

                $historial = new Historial();
                    $historial->id_control = $data->id;
                    $historial->descripcion = ( (int) $request->id > 0 ?'EDICIÓN DE CARGA INICIAL DE LA GR - ALMACEN':'CARGA INICIAL DE LA GR - ALMACEN');
                    $historial->id_usuario = Auth::user()->id_usuario;
                $historial->save();

                if ($request->observacion) {
                    $observacion = new Observaciones();
                        $observacion->control_almacen_id = $data->id;
                        $observacion->observacion = $request->observacion;
                        $observacion->id_usuario = Auth::user()->id_usuario;
                    $observacion->save();
                }

                DB::commit();
                $respuesta = 'ok';
                $alerta = 'success';
                $mensaje = ($request->id > 0) ? 'Se ha editado la guía por Almacén' : 'Se ha registrado la guía por Almacén';
                $error = '';
            // }
        } catch (Exception $ex) {
            DB::rollBack();
            $respuesta = 'error';
            $alerta = 'error';
            $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function guardarDespacho(Request $request) {

        // $despachos_externos = $this->despachosExternos();
        // $despachos_externos = $despachos_externos->where('alm_req.id_requerimiento',$request->requerimiento_id);
        // $despachos_externos = $despachos_externos->first();

        // return $despachos_externos;exit;

        DB::beginTransaction();
        // try {



            $transportista = Contribuyente::find($request->contribuyente_id);
            $data = GuiaDespacho::firstOrNew(['control_almacen_id' => $request->id_control]);
                $data->control_almacen_id = $request->id_control;
                $data->transportista = $transportista->razon_social;
                // $data->guia_transportista = $request->guia_transportista;
                $data->guia_transportista = $request->guia_transportista_serie.'-'.$request->guia_transportista_numero;
                $data->factura_transportista = $request->factura_transportista;
                // $data->fecha_guia_transportista = $request->fecha_guia_transportista;
                $data->fecha_guia_transportista = $request->fecha_emision_guia;
                $data->flete = $request->importe_flete;
                $data->cargo_guia = ($request->cargo_guia) ? $request->cargo_guia : false;
                $data->fecha_retorno_guia = ($request->cargo_guia)? new Carbon() : null;
                $data->envio_adjunto_guia = ($request->envio_adjunto_guia) ? $request->envio_adjunto_guia : false;
                $data->envio_adjunto_guia_sellada = ($request->envio_adjunto_guia_sellada) ? $request->envio_adjunto_guia_sellada : false;
                $data->id_usuario = Auth::user()->id_usuario;

                if ($request->hasFile('adjunto_guia')) {
                    $file = $request->file('adjunto_guia');
                    $file_name = uniqid().'-'.time().'.'.$file->getClientOriginalExtension();
                    $file_path = public_path().'/js/control/documentos/guias_remision/gr_adjuntos/'.$file_name;
                    $file->move(public_path().'/js/control/documentos/guias_remision/gr_adjuntos/', $file_name);
                    $data->adjunto_guia = $file_name;
                    $data->link_adjunto_guia = $file_path;
                }

                if ($request->hasFile('adjunto_guia_sellada')) {
                    $file = $request->file('adjunto_guia_sellada');
                    $file_name = uniqid().'-'.time().'.'.$file->getClientOriginalExtension();
                    $file_path = public_path().'/js/control/documentos/guias_remision/gr_selladas/'.$file_name;
                    $file->move(public_path().'/js/control/documentos/guias_remision/gr_selladas/', $file_name);
                    $data->adjunto_guia_sellada = $file_name;
                    $data->link_adjunto_guia_sellada = $file_path;
                }

                $data->contribuyente_id             = $request->contribuyente_id;
                $data->guia_transportista_serie     = $request->guia_transportista_serie;
                $data->guia_transportista_numero    = $request->guia_transportista_numero;
                $data->fecha_emision_guia           = $request->fecha_emision_guia;
                $data->importe_flete                = $request->importe_flete;
                $data->importe_flete_sin_igv        = $request->importe_flete_sin_igv;
                $data->aplica_igv                   = ((isset($request->aplica_igv) && $request->aplica_igv == 'on') ? true : false);
                $data->codigo_envio                 = $request->codigo_envio;
                $data->credito                      = ($request->credito=='true'?$request->credito:false);
                $data->guia_venta_serie             = $request->guia_venta_serie;
                $data->guia_venta_numero            = $request->guia_venta_numero;
                $data->fecha_despacho_real          = $request->fecha_despacho_real;

                $data->orden_despacho            = $request->orden_despacho;
                $data->requerimiento_id          = $request->requerimiento_id;


            $data->save();

            $historial = new Historial();
                $historial->id_control = $request->id_control;
                $historial->descripcion = 'REGISTRO DE DATOS DEL TRANSPORTISTA - LOGISTICA DE SALIDA';
                $historial->id_usuario = Auth::user()->id_usuario;
            $historial->save();

            if ($request->observacion) {
                $observacion = new Observaciones();
                    $observacion->control_almacen_id = $request->id_control;
                    $observacion->control_logistica_salida_id = $data->id;
                    $observacion->observacion = Str::upper($request->observacion);
                    $observacion->id_usuario = Auth::user()->id_usuario;
                $observacion->save();
            }

            // migracion al agil
            $despachos_externos=array();
            if(!empty($request->requerimiento_id)){
                $despachos_externos = $this->despachosExternos();
                $despachos_externos = $despachos_externos->where('alm_req.id_requerimiento',$request->requerimiento_id);
                $despachos_externos = $despachos_externos->first();
            }
            if ($despachos_externos) {
                $id_usuario = Auth::user()->id_usuario;
                $fecha_registro = date('Y-m-d H:i:s');
                $id_estado_envio = 2; //transportandose (ag transp. lima)

                $fechaRegistroFlete = null;
                $actualOD = OrdenDespacho::find($despachos_externos->id_od);
                if (($actualOD->importe_flete == null && isset($request->importe_flete)) || ($actualOD->importe_flete != null && $request->fecha_registro_flete == null)) {
                    $fechaRegistroFlete = new Carbon();
                }

                $data = DB::table('almacen.orden_despacho')
                ->where('id_od', $despachos_externos->id_od)
                ->update([
                    'id_transportista' => $request->contribuyente_id,
                    'serie' => $request->guia_transportista_serie,
                    'numero' => $request->guia_transportista_numero,
                    'fecha_transportista' => $request->fecha_emision_guia,
                    'fecha_despacho_real' => $request->fecha_despacho_real,
                    'codigo_envio' => $request->codigo_envio,
                    'importe_flete' => $request->importe_flete,
                    'importe_flete_sin_igv' => $request->importe_flete_sin_igv,
                    'aplica_igv' => ((isset($request->aplica_igv) && $request->aplica_igv == 'on') ? true : false),
                    'serie_guia_venta' => $request->guia_venta_serie,
                    'numero_guia_venta' => $request->guia_venta_numero,
                    'id_estado_envio' => $id_estado_envio,
                    'fecha_actualizacion_od' => new Carbon(),
                    'fecha_registro_flete' => $fechaRegistroFlete ?? null,
                    // 'propia'=>((isset($request->transporte_propio)&&$request->transporte_propio=='on')?true:false),
                    'credito' => ((isset($request->credito) && $request->credito == 'on') ? true : false),
                ]);

                // parte numero 2
                if ($request->fecha_despacho_real !== null || $request->contribuyente_id !== null) {
                    $oc = DB::table('almacen.alm_req')
                        ->select(
                            'oc_propias_view.id',
                            'oc_propias_view.tipo',
                            'oc_directas.id_despacho as id_despacho_directa',
                            'oc_propias.id_despacho as id_despacho_propia',
                            DB::raw("(SELECT SUM(orden_despacho_obs.gasto_extra) FROM almacen.orden_despacho_obs
                            inner join almacen.orden_despacho on
                            (orden_despacho_obs.id_od = orden_despacho.id_od)
                            where   orden_despacho.id_requerimiento = alm_req.id_requerimiento
                                    and orden_despacho.aplica_cambios = false
                                    and orden_despacho.estado != 7) AS gasto_extra")
                        )
                        // ->join('almacen.orden_despacho', 'orden_despacho.id_requerimiento', '=', 'alm_req.id_requerimiento')
                        ->join('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
                        ->join('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
                        ->join('mgcp_ordenes_compra.oc_propias_view', 'oc_propias_view.id_oportunidad', '=', 'cc.id_oportunidad')
                        ->leftJoin('mgcp_ordenes_compra.oc_directas', 'oc_directas.id', '=', 'oc_propias_view.id')
                        ->leftJoin('mgcp_acuerdo_marco.oc_propias', 'oc_propias.id', '=', 'oc_propias_view.id')
                        ->where('alm_req.id_requerimiento', $request->requerimiento_id)
                        ->first();
                    $id_despacho = null;
                    //si existe una oc
                    if ($oc !== null) {
                        //tiene un despacho
                        $id_despacho = $oc->id_despacho_directa !== null ? $oc->id_despacho_directa
                            : ($oc->id_despacho_propia !== null ? $oc->id_despacho_propia : null);

                        //si ya existe un despacho
                        if ($id_despacho !== null) {

                            DB::table('mgcp_ordenes_compra.despachos')
                                ->where('id', $id_despacho)
                                ->update([
                                    'id_transportista' => $request->contribuyente_id,
                                    'flete_real' => (($request->importe_flete !== null ? $request->importe_flete : 0) + ($oc->gasto_extra !== null ? $oc->gasto_extra : 0)),
                                    'fecha_salida' => $request->fecha_despacho_real,
                                ]);
                        } else {
                            $id_despacho = DB::table('mgcp_ordenes_compra.despachos')
                                ->insertGetId([
                                    'id_transportista' => $request->contribuyente_id,
                                    'flete_real' => (($request->importe_flete !== null ? $request->importe_flete : 0) + ($oc->gasto_extra !== null ? $oc->gasto_extra : 0)),
                                    'fecha_salida' => $request->fecha_despacho_real,
                                    'id_usuario' => $id_usuario,
                                    'fecha_registro' => new Carbon(),
                                ], 'id');
                        }

                        if ($oc->tipo == 'am') {
                            DB::table('mgcp_acuerdo_marco.oc_propias')
                                ->where('oc_propias.id', $oc->id)
                                ->update([
                                    'despachada' => true,
                                    'id_despacho' => $id_despacho
                                ]);
                        } else {
                            DB::table('mgcp_ordenes_compra.oc_directas')
                                ->where('oc_directas.id', $oc->id)
                                ->update([
                                    'despachada' => true,
                                    'id_despacho' => $id_despacho
                                ]);
                        }
                    }
                }

                // parte 3
                if (!empty($request->guia_transportista_serie) && !empty($request->guia_transportista_numero)) {
                    //si se ingreso serie y numero de la guia se agrega el nuevo estado envio
                    $obs = DB::table('almacen.orden_despacho_obs')
                        ->where([
                            ['id_od', '=', $despachos_externos->id_od],
                            ['accion', '=', $id_estado_envio]
                        ])
                        ->first();

                    if ($obs !== null) {
                        //si ya existe este estado lo actualiza
                        DB::table('almacen.orden_despacho_obs')
                            ->where('id_obs', $obs->id_obs)
                            ->update([
                                'observacion' => 'Guía N° ' . $request->guia_transportista_serie . '-' . $request->guia_transportista_numero,
                                'fecha_estado' => $request->fecha_emision_guia,
                                'registrado_por' => $id_usuario,
                                'fecha_registro' => $fecha_registro
                            ]);
                    } else {
                        //si no existe este estado lo crea
                        DB::table('almacen.orden_despacho_obs')
                            ->insert([
                                'id_od' => $despachos_externos->id_od,
                                'accion' => $id_estado_envio,
                                'fecha_estado' => $request->fecha_emision_guia,
                                'observacion' => 'Guía N° ' . $request->guia_transportista_serie . '-' . $request->guia_transportista_numero,
                                'registrado_por' => $id_usuario,
                                'fecha_registro' => $fecha_registro
                            ]);
                    }

                    //Agrega accion en requerimiento
                    if ($request->requerimiento_id !== null) {
                        DB::table('almacen.alm_req_obs')
                            ->insert([
                                'id_requerimiento' => $request->requerimiento_id,
                                'accion' => 'TRANSPORTANDOSE',
                                'descripcion' => 'Se agrego los Datos del transportista. ' . $request->guia_transportista_serie . '-' . $request->guia_transportista_numero,
                                'id_usuario' => $id_usuario,
                                'fecha_registro' => $fecha_registro
                            ]);
                    }
                }
            }

            // ---------

            DB::commit();
            $respuesta = 'ok';
            $alerta = 'success';
            $mensaje = ($request->id_despacho > 0) ? 'Se ha editado los datos del transportista por Log. de salida' : 'Se ha registrado los datos del transportista por Log. de salida';
            $error = '';
        // } catch (Exception $ex) {
        //     DB::rollBack();
        //     $respuesta = 'error';
        //     $alerta = 'error';
        //     $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
        //     $error = $ex;
        // }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function actualizarDespacho(Request $request) {
        DB::beginTransaction();
        try {
            $data = GuiaDespacho::find($request->id_despacho_act);
                $data->cargo_guia = ($request->cargo_guia_act) ? $request->cargo_guia_act : false;
                $data->fecha_retorno_guia = ($request->cargo_guia_act)? new Carbon() : null;
                $data->envio_adjunto_guia = ($request->envio_adjunto_guia_act) ? $request->envio_adjunto_guia_act : false;
                $data->envio_adjunto_guia_sellada = ($request->envio_adjunto_guia_sellada_act) ? $request->envio_adjunto_guia_sellada_act : false;
                $data->id_usuario = Auth::user()->id_usuario;


                if ($request->hasFile('adjunto_guia_sellada_act')) {
                    $file = $request->file('adjunto_guia_sellada_act');
                    $file_name = uniqid().'-'.time().'.'.$file->getClientOriginalExtension();
                    $file_path = public_path().'/js/control/documentos/guias_remision/gr_selladas/'.$file_name;
                    $file->move(public_path().'/js/control/documentos/guias_remision/gr_selladas/', $file_name);
                    $data->adjunto_guia_sellada = $file_name;
                    $data->link_adjunto_guia_sellada = $file_path;
                }
            $data->save();

            $historial = new Historial();
                $historial->id_control = $data->control_almacen_id;
                $historial->descripcion = 'ACTUALIZACION DE DATOS DE LA GR - LOGISTICA DE SALIDA';
                $historial->id_usuario = Auth::user()->id_usuario;
            $historial->save();

            if ($request->observacion) {
                $observacion = new Observaciones();
                    $observacion->control_almacen_id = $data->control_almacen_id;
                    $observacion->control_logistica_salida_id = $data->id;
                    $observacion->observacion = Str::upper($request->observacion);
                    $observacion->id_usuario = Auth::user()->id_usuario;
                $observacion->save();
            }

            DB::commit();
            $respuesta = 'ok';
            $alerta = 'success';
            $mensaje = 'Se ha actualizaron datos de la GR - Log. de salida';
            $error = '';
        } catch (Exception $ex) {
            DB::rollBack();
            $respuesta = 'error';
            $alerta = 'error';
            $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function guardarArchivador(Request $request) {
        DB::beginTransaction();
        try {
            $data = Archivador::firstOrNew(['id' => $request->id_arch]);
                $data->control_almacen_id           = $request->id_control_arch;
                $data->control_logistica_salida_id  = $request->id_despacho_arch;
                $data->estado                       = $request->estado_gr;
                $data->libro_archivado              = $request->libro;
                $data->cargo_guia                   = $request->enviado_cargo_guia;
                $data->remitente_guia               = $request->enviado_guia_sellada;
                $data->sunat_guia                   = $request->enviado_guia_sunat;
                $data->destinatario_guia            = $request->enviado_guia_destinatario;
                $data->id_usuario                   = Auth::user()->id_usuario;

            $data->save();

            $historial = new Historial();
                $historial->id_control = $request->id_control_arch;
                $historial->descripcion = 'GR ARCHIVADA - DOCUMENTOS';
                $historial->id_usuario = Auth::user()->id_usuario;
            $historial->save();

            if ($request->observaciones_arch) {
                $observacion = new Observaciones();
                    $observacion->control_almacen_id = $request->id_control_arch;
                    $observacion->control_logistica_salida_id = $request->id_despacho_arch;
                    $observacion->observacion = Str::upper($request->observaciones_arch);
                    $observacion->id_usuario = Auth::user()->id_usuario;
                $observacion->save();
            }

            DB::commit();
            $respuesta = 'ok';
            $alerta = 'success';
            $mensaje = 'Se archivó con exíto la GR';
            $error = '';
        } catch (Exception $ex) {
            DB::rollBack();
            $respuesta = 'error';
            $alerta = 'error';
            $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function guardarObservacion(Request $request) {
        DB::beginTransaction();
        try {
            $historial = new Historial();
                $historial->id_control = $request->id_control_obs;
                $historial->descripcion = 'REGISTRO DE OBSERVACIONES';
                $historial->id_usuario = Auth::user()->id_usuario;
            $historial->save();

            $observacion = new Observaciones();
                $observacion->control_almacen_id = $request->id_control_obs;
                if ($request->id_control_logistica > 0) {
                    $observacion->control_logistica_salida_id = $request->id_control_logistica;
                }
                $observacion->observacion = Str::upper($request->comentario);
                $observacion->id_usuario = Auth::user()->id_usuario;
            $observacion->save();

            DB::commit();
            $respuesta = 'ok';
            $alerta = 'success';
            $mensaje = 'Se ha registrado la observación';
            $error = '';
        } catch (Exception $ex) {
            DB::rollBack();
            $respuesta = 'error';
            $alerta = 'error';
            $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function historial($id) {
        $guia = ControlGuiaView::find($id);
        // $historial = Historial::with('usuario')->where('id_control', $id)->get();
        $historial = Historial::with('usuario')->where('id_control', $id)->get();
        // $observaciones = Observaciones::with('usuario')->where('control_almacen_id', $id)->get();
        $observaciones = Observaciones::with('usuario')->where('control_almacen_id', $id)->get();
        return response()->json(array('guia' => $guia, 'historial' => $historial, 'observaciones' => $observaciones), 200);
    }

    public function informacionDespacho($id) {
        $despacho = GuiaDespacho::find($id);
        return response()->json($despacho, 200);
    }

    public function buscarCuadro(Request $request) {
        $orden = OrdenCompraView::select('id_empresa','nombre_empresa', 'nombre_entidad', 'nro_orden')->where('codigo_oportunidad', trim($request->valor))->first();
        $respuesta = ($orden) ? 'ok' : 'null';
        return response()->json(array('orden' => $orden, 'respuesta' => $respuesta), 200);
    }

    public function anular($id)
    {
        try {
            $data = GuiaAlmacen::find($id);
                $data->estado = 0;
            $data->save();

            $alerta = 'info';
            $mensaje = 'Se anuló el registro de la GR';
            $error = '';
        } catch (Exception $ex) {
            $alerta = 'error';
            $mensaje ='Hubo un problema al eliminar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('mensaje' => $mensaje, 'alerta' => $alerta, 'error' => $error), 200);
    }

    public function eliminar($id)
    {
        try {
            $data = GuiaAlmacen::find($id)->delete();
            $alerta = 'info';
            $mensaje = 'Se ha eliminado el registro de la GR';
            $error = '';
        } catch (Exception $ex) {
            $alerta = 'error';
            $mensaje ='Hubo un problema al eliminar. Por favor intente de nuevo';
            $error = $ex;
        }
        return response()->json(array('mensaje' => $mensaje, 'alerta' => $alerta, 'error' => $error), 200);
    }

    public function agenciaTransportista(Request $request) {
        $string = $request->search;
        // return $string;exit;
        $data_json = array();
        $transportista = Transportista::select('adm_contri.id_contribuyente', 'adm_contri.nro_documento', 'adm_contri.razon_social')
        ->join('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'transportistas.id_contribuyente')

        ->where('adm_contri.razon_social','LIKE','%'.$string.'%')
        ->orWhere(function (Builder $query) use($string) {
            $query->orWhere('adm_contri.razon_social','LIKE','%'.strtolower($string).'%')
                ->orWhere('adm_contri.razon_social','LIKE','%'.strtoupper($string).'%');
        })
        ->get();

        if (sizeof($transportista)>0) {
            foreach ($transportista as $key => $value) {
                array_push($data_json, array(
                    "id"=>$value->id_contribuyente,
                    "text"=>$value->razon_social,
                ));
            }
        }

        return response()->json($data_json,200);
    }
    public function guardarAlmacenMasivo(Request $request) {
        // $array = array();
        // for ($i=0; $i <((int) $request->hasta) ; $i++) {
        //     $numero = ConfiguracionHelper::leftZero(4, ((int) $request->serie_gr + $i));
        //     $codigo = $request->codigo_gr.'-'.$numero;
        //     array_push($array,$codigo);
        // }
        // return response()->json($array,200);exit;
        // DB::beginTransaction();
        // try {

            $codigo_duplicado = array();
            for ($i = 0; $i < ((int) $request->hasta) ; $i++) {
                $numero = ConfiguracionHelper::leftZero(8, ((int) $request->serie_gr + $i));
                $codigo = $request->codigo_gr.'-'.$numero;

                $consulta = GuiaAlmacen::where('codigo', $codigo)->whereNull('deleted_at')->count();

                if ($consulta > 0) {
                    // $respuesta = 'duplicado';
                    // $alerta = 'info';
                    // $mensaje = 'Duplicado, la GR ingresada ya está registrada';
                    // $error = '';
                    array_push($codigo_duplicado,$codigo);
                } else {
                    $data = new GuiaAlmacen();
                        $data->codigo               = $codigo;
                        $data->sede                 = Str::upper("LIMA");
                        $data->tipo_movimiento_id   = 2;
                        $data->id_responsable       = Auth::user()->id_usuario;
                        $data->fecha_guia           = date('Y-m-d');
                        $data->estado               = 1;
                        $data->estado_gr            = "NORMAL";
                        $data->id_usuario           = Auth::user()->id_usuario;
                    $data->save();

                    $historial = new Historial();
                        $historial->id_control = $data->id;
                        $historial->descripcion = 'CARGA AUTOMÁTICA DE SERIES DE GR';
                        $historial->id_usuario = Auth::user()->id_usuario;
                    $historial->save();

                    // DB::commit();

                }
            }

            if (sizeof($codigo_duplicado) === (int)$request->hasta) {
                $respuesta = 'duplicado';
                $alerta = 'info';
                $mensaje = 'Duplicados, las GR AUTOMÁTICA ingresada ya estan registradas';
                $error = '';
            }else{
                $respuesta = 'ok';
                $alerta = 'success';
                $mensaje = 'Se ha registrado la guía por Almacén. '.(sizeof($codigo_duplicado)>0?'Codigos duplicados '.implode(",", $codigo_duplicado):' ');
                $error = '';
            }



        // } catch (Exception $ex) {
        //     DB::rollBack();
        //     $respuesta = 'error';
        //     $alerta = 'error';
        //     $mensaje = 'Hubo un problema al registrar. Por favor intente de nuevo';
        //     $error = $ex;
        // }
        return response()->json(array('respuesta' => $respuesta, 'alerta' => $alerta, 'mensaje' => $mensaje, 'error' => $error), 200);
    }

    public function editar($id) {

        $data = GuiaAlmacen::find($id);
        // $response = array("success"=>true,"data"=>$data);
        return response()->json($data,200);
    }

    public function buscarCodigo(Request $request){
        $data = array();
        if (!$request->empresa) {
            $response['titulo'] = "Alerta";
            $response['mensaje'] = "Seleccione una empresa";
            $response['tipo'] = "warning";
            $response['status'] = 200;
            return response()->json($response,200);
        }
        $data = GuiaAlmacen::where('codigo', $request->codigo)->where('empresa_id', $request->empresa)->where('estado', 1)->first();
        if(!$data){

            $codigo_busqueda = ConfiguracionHelper::leftZero(8,(int) explode('-',$request->codigo)[1]);
            $data = GuiaAlmacen::where('codigo', (explode('-', $request->codigo)[0].'-'.$codigo_busqueda))
            ->where('estado',1)->where('empresa_id', $request->empresa)->first();

        }
        $response = array();
        if ($data && ($data->id !== (int)$request->id)) {
            $response['titulo'] = "Alerta";
            $response['mensaje'] = "Duplicado, la GR ingresada ya está registrada";
            $response['tipo'] = "warning";
            $response['status'] = 200;
        }else{
            $response['status'] = 400;
        }
        return response()->json($response,200);
    }
    public function buscarCodigoOrdenDespacho(Request $request) {

        $data_json = array();

        $data = $this->despachosExternos();
        $data = $data->where('oportunidades.codigo_oportunidad',$request->codigo);
        $data = $data->orWhere('oc_propias_view.nro_orden',$request->codigo);
        $data = $data->get();

        if(sizeof($data)>0) {
            $data_json['titulo'] = "Éxito";
            $data_json['mensaje'] = "Se encontro con exito el resultado";
            $data_json['tipo'] = "success";
            $data_json['status'] = 200;
            $data_json['data'] = $data;
        }else{
            $data_json['titulo'] = "Alerta";
            $data_json['mensaje'] = "No se encontro ningun resultado";
            $data_json['tipo'] = "warning";
            $data_json['status'] = 400;
        }

        return response()->json($data_json,200);
    }
    public function buscarOD(Request $request) {
        $data_json = array();
        $data = OrdenDespacho::where('id_requerimiento',$request->requerimiento_id)
        ->first();
        if($data) {
            $despachos_externos = $this->despachosExternos();
            $despachos_externos = $despachos_externos->where('alm_req.id_requerimiento',$request->requerimiento_id);
            $despachos_externos = $despachos_externos->first();

            $data_json['titulo'] = "Éxito";
            $data_json['mensaje'] = "Se encontro con exito el resultado";
            $data_json['tipo'] = "success";
            $data_json['status'] = 200;
            $data_json['data'] = $despachos_externos;
        }else{
            $data_json['titulo'] = "Alerta";
            $data_json['mensaje'] = "No cuenta con ODE/ODI";
            $data_json['tipo'] = "warning";
            $data_json['status'] = 400;
        }
        return response()->json($data_json,200);
    }
    public function despachosExternos() {
        $data = DB::table('almacen.alm_req')
            ->select(
                'alm_req.id_requerimiento',
                'alm_req.id_tipo_requerimiento',
                'alm_req.codigo',
                'alm_req.concepto',
                'oc_propias_view.fecha_entrega',
                'alm_req.tiene_transformacion',
                'alm_req.direccion_entrega',
                'alm_req.id_ubigeo_entrega',
                'alm_req.id_almacen',
                'alm_req.id_sede as sede_requerimiento',
                'alm_req.telefono',
                'alm_req.email',
                'alm_req.id_cliente',
                'alm_req.id_prioridad',
                'alm_req.id_contacto',
                'alm_req.enviar_contacto',
                'alm_req.estado',
                'alm_req.estado_despacho',
                'alm_tp_req.descripcion as tipo_requerimiento_descripcion',
                'sis_usua.nombre_corto as responsable',
                'adm_estado_doc.estado_doc',
                'adm_estado_doc.bootstrap_color',
                'alm_almacen.descripcion as almacen_descripcion',
                'sede_req.descripcion as sede_descripcion_req',
                'adm_contri.id_contribuyente',
                'adm_contri.nro_documento as cliente_ruc',
                'adm_contri.razon_social as cliente_razon_social',
                'orden_despacho.id_od',
                'orden_despacho.fecha_despacho',
                'orden_despacho.nro_orden as numero_orden',
                'orden_despacho.persona_contacto',
                'orden_despacho.direccion_destino',
                'orden_despacho.correo_cliente',
                'orden_despacho.telefono as telefono_od',
                'orden_despacho.ubigeo_destino',
                'orden_despacho.codigo as codigo_od',
                'orden_despacho.estado as estado_od',
                'orden_despacho.serie as serie_tra',
                'orden_despacho.numero as numero_tra',
                'orden_despacho.serie_guia_venta as serie_guia',
                'orden_despacho.numero_guia_venta as numero_guia',
                'orden_despacho.fecha_transportista',
                'orden_despacho.codigo_envio',
                'orden_despacho.credito',
                'orden_despacho.importe_flete',
                'orden_despacho.importe_flete_sin_igv',
                'orden_despacho.aplica_igv',
                'orden_despacho.id_transportista',
                'orden_despacho.plazo_excedido',
                'orden_despacho.fecha_entregada',
                'orden_despacho.fecha_despacho_real',
                'orden_despacho.fecha_registro_flete',
                'orden_despacho.fecha_actualizacion_od',
                'despachoInterno.id_od as id_despacho_interno',
                'despachoInterno.codigo as codigo_despacho_interno',
                'despachoInterno.estado as estado_di',
                'estado_envio.descripcion as estado_envio',
                'transportista.razon_social as transportista_razon_social',
                // 'guia_ven.serie',
                // 'guia_ven.numero',
                DB::raw("(SELECT COUNT(*) FROM almacen.orden_despacho_obs where
                            orden_despacho_obs.id_od = orden_despacho.id_od
                            and orden_despacho.estado != 7) AS count_estados_envios"),
                DB::raw("(SELECT SUM(orden_despacho_obs.gasto_extra) FROM almacen.orden_despacho_obs where
                            orden_despacho_obs.id_od = orden_despacho.id_od
                            and orden_despacho.estado != 7) AS gasto_extra"),
                DB::raw("(SELECT orden_despacho_obs.adjunto FROM almacen.orden_despacho_obs where
                            orden_despacho_obs.id_od = orden_despacho.id_od
                            and (orden_despacho_obs.accion = 8 or orden_despacho_obs.accion = 7 or orden_despacho_obs.accion = 6)
                            and orden_despacho.estado != 7
                            order by id_obs desc limit 1) AS adjunto"),
                'oc_propias_view.nro_orden',
                'oportunidades.codigo_oportunidad',
                'oc_propias_view.id as id_oc_propia',
                'oc_propias_view.tipo',
                'oc_propias_view.id_oportunidad',
                'oc_propias_view.id_entidad',
                'oc_propias_view.estado_oc',
                'oc_propias_view.moneda_oc',
                'oc_propias_view.monto_total',
                'oc_propias_view.fecha_publicacion',
                'oc_propias_view.estado_aprobacion_cuadro',
                'oc_propias_view.siaf',
                'oc_propias_view.orden_compra',
                'oc_propias_view.occ',
                'oc_propias_view.tiene_comentarios',
                'oc_propias_view.nombre_entidad',
                'oc_propias_view.nombre_largo_responsable',
                // 'trazabilidad.adjunto',
                DB::raw("(SELECT COUNT(*) FROM almacen.alm_det_req where
                            alm_det_req.id_requerimiento = alm_req.id_requerimiento
                            and alm_det_req.estado != 7
                            and alm_det_req.id_tipo_item = 1
                            and alm_det_req.id_producto is null) AS productos_no_mapeados"),
                // DB::raw('count(*) as user_count, status')
                DB::raw("(SELECT COUNT(*) FROM almacen.alm_det_req where
                            alm_det_req.id_requerimiento = alm_req.id_requerimiento
                            and alm_det_req.estado != 7
                            and alm_det_req.id_tipo_item = 1
                            and alm_det_req.id_tipo_item = 1) AS count_productos"),
            )
            ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
            ->leftjoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
            ->leftJoin('mgcp_ordenes_compra.oc_propias_view', 'oc_propias_view.id_oportunidad', '=', 'oportunidades.id')
            ->leftJoin('almacen.alm_tp_req', 'alm_tp_req.id_tipo_requerimiento', '=', 'alm_req.id_tipo_requerimiento')
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'alm_req.id_usuario')
            ->leftJoin('administracion.adm_estado_doc', 'adm_estado_doc.id_estado_doc', '=', 'alm_req.estado_despacho')
            ->leftJoin('administracion.sis_sede as sede_req', 'sede_req.id_sede', '=', 'alm_req.id_sede')
            ->leftJoin('almacen.alm_almacen', 'alm_almacen.id_almacen', '=', 'alm_req.id_almacen')
            ->leftJoin('comercial.com_cliente', 'com_cliente.id_cliente', '=', 'alm_req.id_cliente')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'com_cliente.id_contribuyente')
            ->leftJoin('almacen.orden_despacho', function ($join) {
                $join->on('orden_despacho.id_requerimiento', '=', 'alm_req.id_requerimiento');
                $join->where('orden_despacho.aplica_cambios', '=', false);
                $join->where('orden_despacho.estado', '!=', 7);
            })
            ->leftJoin('almacen.orden_despacho as despachoInterno', function ($join) {
                $join->on('despachoInterno.id_requerimiento', '=', 'alm_req.id_requerimiento');
                $join->where('despachoInterno.aplica_cambios', '=', true);
                $join->where('despachoInterno.estado', '!=', 7);
            })
            ->leftJoin('contabilidad.adm_contri as transportista', 'transportista.id_contribuyente', '=', 'orden_despacho.id_transportista')
            ->leftJoin('administracion.adm_estado_doc as est_od', 'est_od.id_estado_doc', '=', 'orden_despacho.estado')
            ->leftJoin('almacen.estado_envio', 'estado_envio.id_estado', '=', 'orden_despacho.id_estado_envio')
            ->where([
                ['alm_req.estado', '!=', 7]
                // ['alm_req.observacion', '!=', 'Creado de forma automática por venta interna'],
                // ['nro_productos', '>', 0]
            ])
            ->whereIn('alm_req.id_tipo_detalle', [1, 3]);
        return $data;
    }
    public function enviarGRControl(Request $request) {
        $data = GuiaAlmacen::firstOrNew(['id' => $request->id]);
            $data->recepcion_gci    = true;
        $data->save();
        return response()->json($request,200);
    }
    public function reporteFiltros(Request $request) {
        // return $request;exit;

        $data = ControlGuiaView::whereIn('estado_registro',[0,1]);

        if(!empty($request->empresa_id) || $request->empresa_id!==null){
            $data = $data->where('empresa_id','=',$request->empresa_id);
        }

        if(!empty($request->estado) || $request->estado!==null){
            $data = $data->where('estado',$request->estado);
        }

        if(!empty($request->fecha_inicio)  || $request->fecha_inicio!==null){
            $data = $data->whereDate('fecha_guia','>=',$request->fecha_inicio);
        }

        if(!empty($request->fecha_final)  || $request->fecha_final!==null){
            $data = $data->whereDate('fecha_guia','<=',$request->fecha_final);
        }

        $data = $data->orderBy('codigo', 'asc')->get();

        // return ControlGuiaView::whereIn('estado_registro',[0,1])->count();exit;
        $array = array();

        foreach ( $data as $key => $value) {

            array_push($array,(object) array(
                "ocam"                  =>$value->ocam,
                "oc_virtual"            =>$value->oc_virtual,
                "codigo_oportunidad"    =>$value->codigo_oportunidad,
                "codigo_requerimiento"  =>$value->codigo_requerimiento,
                "guia_transportista"    =>$value->guia_transportista,
                "factura_transportista" =>$value->factura_transportista,
                "empresa_id"            =>$value->empresa_id,
                "fecha_guia"            =>$value->fecha_guia,
                "recepcion_gci"         =>($value->recepcion_gci=='t'?'Recepcionado':'Sin documento'),
                "codigo"                =>$value->codigo,
                "destino"               =>$value->destino,
                "descripcion_guia"      =>$value->descripcion_guia,
                "transportista"         =>$value->transportista,
                "responsable"           =>$value->responsable,
                "estado"                =>$value->estado,

                "adjunto_guia"          =>($value->adjunto_guia != null ? 'si' :'no'),
                "adjunto_guia_sellada"  =>($value->adjunto_guia_sellada != null? 'si' : 'no'),
                // ($data->adjunto_guia != null) ? '<a href="'.$ruta.'/'.$data->adjunto_guia.'" target="_blank">Descargar GR</a>' : '',
            ));
        }

        // return $array;exit;
        foreach ($array as $key => $item) {

            if($item->empresa_id!==null){
                $empresa = Empresa::find($item->empresa_id);
                $item->empresa_razon = $empresa->contribuyente->razon_social;
            }else{
                $item->empresa_razon = '-';
            }


            if ($item->ocam != null) {
                $oc = ($item->oc_virtual != null) ? ' / '.$item->oc_virtual : '';
                $item->orden= $item->ocam.$oc;
            } else {
                $item->orden= ($item->oc_virtual != null) ? $item->oc_virtual : '';
            }

            if ($item->codigo_oportunidad != null) {
                $requerimiento = ($item->codigo_requerimiento != null) ? ' / '.$item->codigo_requerimiento : '';
                $item->documentos_agile = $item->codigo_oportunidad.$requerimiento;
            } else {
                $item->documentos_agile =  ($item->codigo_requerimiento != null) ? $item->codigo_requerimiento : '';
            }

            if ($item->guia_transportista != null) {
                $factura = ($item->factura_transportista != null) ? ' / '.$item->factura_transportista : '';
                $item->documentos_transportista = $item->guia_transportista.$factura;
            } else {
                $item->documentos_transportista = ($item->factura_transportista != null) ? $item->factura_transportista : '';
            }
        }

        // return response()->json($array,200);exit;
        return Excel::download(new GuiasRemisionExport(json_encode($array)), 'guias_remision_reporte-'.date('d-m-Y H:m:s').'.xlsx');
        // return response()->json($data,200);
    }
}
