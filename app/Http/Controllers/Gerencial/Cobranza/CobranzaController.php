<?php

namespace App\Http\Controllers\Gerencial\Cobranza;

use App\Exports\CobranzaExport;
use App\Helpers\ConfiguracionHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Empresa;
use App\Models\Administracion\Periodo;
use App\Models\almacen\DocumentoVenta;
use App\Models\Comercial\Cliente;
use App\models\Configuracion\AccesosUsuarios;
use App\Models\Configuracion\Departamento;
use App\Models\Configuracion\LogActividad;
use App\Models\Configuracion\Pais;
use App\Models\Configuracion\SisUsua;
use App\Models\Contabilidad\Contribuyente;
use App\Models\contabilidad\ContribuyenteView;
use App\Models\Gerencial\AreaContacto;
use App\models\Gerencial\Cobranza;
use App\Models\Gerencial\CobranzaAdjuntoObservacion;
use App\models\Gerencial\CobranzaFase;
use App\Models\Gerencial\CobranzaView;
use App\models\Gerencial\EstadoDocumento;
use App\Models\Gerencial\Fase;
use App\Models\Gerencial\Observaciones;
use App\Models\Gerencial\Penalidad;
use App\Models\Gerencial\PenalidadCobro;
use App\Models\Gerencial\ProgramacionPago;
use App\Models\Gerencial\RegistroCobranza;
use App\Models\Gerencial\RegistroCobranzaFase;
use App\models\Gerencial\Sector;
use App\models\Gerencial\TipoTramite;
use App\Models\Gerencial\Vendedor;
use App\Models\Logistica\Proveedor;
use App\Models\mgcp\AcuerdoMarco\AcuerdoMarco;
use App\Models\mgcp\AcuerdoMarco\OrdenCompraPropias;
use App\Models\mgcp\OrdenCompra\Propia\Directa\OrdenCompraDirecta;
use App\Models\mgcp\OrdenCompra\Propia\OrdenCompraPropiaView;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

ini_set('max_execution_time', '0');
class CobranzaController extends Controller
{
    public function index()
    {
        $sector = Sector::where('estado', 1)->get();
        $tipo_ramite = TipoTramite::where('estado', 1)->get();
        $empresas = Empresa::with('contribuyente')->where('estado', 1)->get();
        $periodo = Periodo::where('estado', 1)->orderBy('descripcion', 'desc')->get();
        $estado_documento = EstadoDocumento::where('estado', 1)->orderBy('nombre','asc')->get();
        $vendedores = Vendedor::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $fases = Fase::orderBy('descripcion', 'asc')->get();
        $area_contacto= AreaContacto::where('estado',1)->orderBy('nombre','asc')->get();
        if (!session()->has('cobranzaPeriodo')) {
            $periodoActual = Periodo::where('descripcion', date('Y'))->first();
            session()->put('cobranzaPeriodo', $periodoActual->descripcion);
        }

        #array de accesos de los modulos copiar en caso tenga accesos -----
        $array_accesos = [];
        $accesos_usuario = AccesosUsuarios::where('estado', 1)->where('id_usuario', Auth::user()->id_usuario)->get();
        foreach ($accesos_usuario as $key => $value) {
            array_push($array_accesos, $value->id_acceso);
        }
        #-------------------------------
        // return $array_accesos;exit;
        return view('gerencial.cobranza.registro', get_defined_vars());
    }

    public function filtros(Request $request)
    {
        if ($request->checkSemaforo == 'on') {
            $request->session()->put('cobranzaSemaforo', $request->filterSemaforo);
        } else {
            $request->session()->forget('cobranzaSemaforo');
        }
        if ($request->checkEmpresa == 'on') {
            $request->session()->put('cobranzaEmpresa', $request->filterEmpresa);
        } else {
            $request->session()->forget('cobranzaEmpresa');
        }

        if ($request->checkFase == 'on') {
            $request->session()->put('cobranzaFase', $request->filterFase);
        } else {
            $request->session()->forget('cobranzaFase');
        }

        if ($request->checkEstadoDoc == 'on') {
            $request->session()->put('cobranzaEstadoDoc', $request->filterEstadoDoc);
        } else {
            $request->session()->forget('cobranzaEstadoDoc');
        }

        if ($request->checkEmi == 'on') {
            $request->session()->put('cobranzaEmisionDesde', $request->filterEmisionDesde);
            $request->session()->put('cobranzaEmisionHasta', $request->filterEmisionHasta);
        } else {
            $request->session()->forget('cobranzaEmisionDesde');
            $request->session()->forget('cobranzaEmisionHasta');
        }

        if ($request->checkPenalidad == 'on') {
            $request->session()->put('cobranzaPenalidad', true);
        } else {
            $request->session()->forget('cobranzaPenalidad');
        }

        $request->session()->put('cobranzaPeriodo', $request->filterPeriodo);
        return response()->json('filtros', 200);
    }

    public function listar(Request $request)
    {
        $data = CobranzaView::select(['*']);

        if ($request->session()->has('cobranzaPenalidad')) {
            $data = $data->where('tiene_penalidad', session()->get('cobranzaPenalidad'));
        }

        if ($request->session()->has('cobranzaSemaforo')) {

            $optionSemaforo =session()->get('cobranzaSemaforo');

            if($optionSemaforo ==0){
                $data = $data->whereRaw("coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) >= 0 and coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) < 5");

            }elseif($optionSemaforo==1){
                $data = $data->whereRaw("coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) <= 5 and coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) < 10");

            }elseif($optionSemaforo==2){
                $data = $data->whereRaw("coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) >= 10  and coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp),0) < 15");

            }elseif($optionSemaforo==3){
                $data = $data->whereRaw("coalesce(EXTRACT(DAY FROM  CURRENT_DATE::timestamp - fecha_entrega_real::timestamp ),0) >= 15");

            }
        }
        if ($request->session()->has('cobranzaEmpresa')) {
            $data = $data->where('empresa', session()->get('cobranzaEmpresa'));
        }

        if ($request->session()->has('cobranzaFase')) {
            $data = $data->where('fase', session()->get('cobranzaFase'));
        }

        if ($request->session()->has('cobranzaEstadoDoc')) {
            $data = $data->where('estado_cobranza', session()->get('cobranzaEstadoDoc'));
        }

        if ($request->session()->has('cobranzaPeriodo')) {
            $data = $data->where('periodo', session()->get('cobranzaPeriodo'));
        }

        if ($request->session()->has('cobranzaEmisionDesde')) {
            $data = $data->whereBetween('fecha_emision', [session()->get('cobranzaEmisionDesde'), session()->get('cobranzaEmisionHasta')]);
        }

        $data = $data->orderBy('fecha_emision', 'desc');

        return DataTables::of($data)
        ->addColumn('atraso', function ($data){
            return ($this->restar_fechas($data->fecha_recepcion, date('Y-m-d')) > 0) ? $this->restar_fechas($data->fecha_recepcion, date('Y-m-d')) : '0';
        })
        ->addColumn('accion', function ($data) {
            $array_accesos = [];
            $accesos_usuario = AccesosUsuarios::where('estado', 1)->where('id_usuario', Auth::user()->id_usuario)->get();
            foreach ($accesos_usuario as $key => $value) {
                array_push($array_accesos, $value->id_acceso);
            }
            $btn_editar = (in_array(307,$array_accesos)?'':'');
            $btn ='
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right">

                    '.(in_array(308,$array_accesos)?'<li><a href="javascript: void(0);" class="editar" data-id="'. $data->id .'" data-toggle="tooltip" title="Editar" data-original-title="Editar">Editar</a></li>':'').'

                    '.(in_array(312,$array_accesos)?'<li><a href="javascript: void(0);" class="fases" data-id="'. $data->id .'" title="Fases">Fases</a></li>':'').'';

                if ($data->estado_cobranza == 'PAGADO') {
                    $btn.= '
                    <li><a href="javascript: void(0);" class="acciones" data-accion="penalidad" data-id="'. $data->id .'"data-toggle="tooltip" title="Penalidad">Penalidades</a></li>
                    <li><a href="javascript: void(0);" class="acciones" data-accion="retencion" data-id="'. $data->id .'"data-toggle="tooltip" title="Retencion">Retenciones</a></li>
                    <li><a href="javascript: void(0);" class="acciones" data-accion="detraccion" data-id="'. $data->id .'"data-toggle="tooltip" title="Detraccion">Detracciones</a></li>';
                }

                $btn .= '<li><a href="javascript: void(0);" class="observaciones" data-id="'. $data->id .'" title="OBSERVACIONES">Observaciones</a></li>
                    '.(in_array(310,$array_accesos)?'<li><a href="javascript: void(0);" class="eliminar" data-id="'. $data->id .'" title="Eliminar">Eliminar</a></li>':'').'
                </ul>
            </div>';
            return $btn;
        })
        ->addColumn('semaforo', function ($data){
            $diferenciaEnDias=0;
            $fechaActual = Carbon::now();

            if($data->fecha_entrega_real!=null){
                $fechaEntregaReal  = Carbon::createFromFormat('Y-m-d',$data->fecha_entrega_real );
                // ->addDay(5)->toDateTimeString();
                $diferenciaEnDias = $fechaEntregaReal->diffInDays($fechaActual);

            }

            if($diferenciaEnDias >= 0 && $diferenciaEnDias < 5){
                return '<div class="text-center"> <i class="fas fa-thermometer-empty gris"  data-toggle="tooltip" data-placement="right"></i></div>';
            }elseif($diferenciaEnDias >= 5 && $diferenciaEnDias < 10){
                return '<div class="text-center"> <i class="fas fa-thermometer-quarter green"  data-toggle="tooltip" data-placement="right"></i></div>';
            }elseif($diferenciaEnDias >= 10 && $diferenciaEnDias < 15){
                return '<div class="text-center"> <i class="fas fa-thermometer-half orange"  data-toggle="tooltip" data-placement="right"></i></div>2';
            }elseif($diferenciaEnDias >=15){
                return '<div class="text-center"> <i class="fas fa-thermometer-full red"  data-toggle="tooltip" data-placement="right" title="Rojo"></i></div>';

            }

        })
        ->editColumn('importe', function ($data) { return number_format($data->importe, 2); })
        ->editColumn('fase', function ($data) {
            return ($data->fase != null) ? '<label class="label label-primary label-badge">'.$data->fase.'</label>' : '<label class="label label-danger label-badge">-</label>';
         })
        ->rawColumns(['fase', 'accion','semaforo'])

        ->make(true);
    }

    public function guardarRegistro(Request $request)
    {
        // return [$request->ip()];exit;
        DB::beginTransaction();
        try {
            $empresa = Empresa::find($request->empresa);
            $programacion_pago = [];

            /**
             * Registro de cobranza
             */
            $cobranza_old = RegistroCobranza::find($request->id);
            $cobranza = RegistroCobranza::firstOrNew(['id_registro_cobranza' => $request->id]);
                if ($request->id===0 || $request->id==='0') {
                    $cobranza->fecha_registro = new Carbon();

                    $cobranza->created_at   = new Carbon(); #obtiene la fecha de creacion del registro
                    $cobranza->created_id      = Auth::user()->id_usuario;

                }
                $cobranza->updated_at   = new Carbon(); #obtiene la fecha de actualizacion del registro
                $cobranza->updated_id   = Auth::user()->id_usuario; #obtiene la fecha de actualizacion del registro
                $cobranza->user_ip   = $request->ip(); #obtiene la fecha de actualizacion del registro

                $cobranza->id_empresa = $request->empresa;
                $cobranza->id_sector = $request->sector;
                $cobranza->id_cliente = $request->id_cliente;
                $cobranza->factura = $request->fact;
                $cobranza->uu_ee = $request->ue;
                $cobranza->fuente_financ = $request->ff;
                $cobranza->ocam = $request->oc; // OCAM es igul que la oc
                $cobranza->siaf = $request->siaf;
                // $cobranza->fecha_emision = $request->fecha_emi;
                $cobranza->fecha_final = $request->fecha_final;
                $cobranza->fecha_recepcion = $request->fecha_rec;
                $cobranza->moneda = $request->moneda;
                $cobranza->importe = $request->importe;
                $cobranza->id_estado_doc = $request->estado_doc;
                $cobranza->id_tipo_tramite = $request->tramite;
                $cobranza->vendedor = ($request->vendedor) ? $request->vendedor : null;
                $cobranza->estado = 1;

                $cobranza->id_area = $request->area;
                $cobranza->id_periodo = $request->periodo;
                $cobranza->codigo_empresa = $empresa->codigo;
                $cobranza->categoria = $request->categ;
                $cobranza->cdp = $request->cdp;
                $cobranza->plazo_credito = $request->plazo_credito;
                $cobranza->id_doc_ven = $request->id_doc_ven;
                $cobranza->oc_fisica = $request->orden_compra;
                $cobranza->inicio_entrega = $request->fecha_inicio;
                $cobranza->fecha_entrega = $request->fecha_entrega;
                $cobranza->id_oc = $request->id_oc;
                $cobranza->area_usario = $request->area_usario;
                $cobranza->penalidad = $request->penalidad;
            $cobranza->save();

            if((int) $request->id > 0){
                $comentarioCabecera = 'EDITAR COBRANZA';
                LogActividad::registrar(Auth::user(), 'Crear / editar registro de Cobranza', 3, $cobranza->getTable(), $cobranza_old, $cobranza, $comentarioCabecera,'GERENCIAL');
            }else{
                $comentarioCabecera = 'NUEVA COBRANZA';
                LogActividad::registrar(Auth::user(), 'Crear / editar registro de Cobranza', 2, $cobranza->getTable(), null, $cobranza, $comentarioCabecera,'GERENCIAL');
            }



            if ($request->id == 0) {
                /**
                 * Registro de Fase automática
                 */
                $nuevo = new RegistroCobranzaFase();
                    $nuevo->id_registro_cobranza = $cobranza->id_registro_cobranza;
                    $nuevo->fase = 'COMPROMISO';
                    $nuevo->fecha = $cobranza->fecha_registro;
                $nuevo->save();
            }

            /**
             * Programacion de pagos
             */
            $programacion_pago = new ProgramacionPago();
                $programacion_pago->id_registro_cobranza = $cobranza->id_registro_cobranza;
                $programacion_pago->fecha = $request->fecha_ppago;
                $programacion_pago->estado = 1;
                $programacion_pago->fecha_registro = new Carbon();
            $programacion_pago->save();

            /**
             * Penalidad automática
             */
            // if ($request->importe) {
            //     if (intval($request->dias_atraso) > 0) {
            //         $formula_penalidad = (0.10 * floatval($request->importe))/(0.4*intval($request->dias_atraso));
            //         $penalidad = new Penalidad();
            //             $penalidad->tipo                    = 'PENALIDAD';
            //             $penalidad->monto                   = $formula_penalidad;
            //             $penalidad->documento               = '--';
            //             $penalidad->fecha                   = date('Y-m-d');
            //             $penalidad->observacion             = 'PENALIDAD CALCULADA';
            //             $penalidad->estado                  = 1;
            //             $penalidad->fecha_registro          = date('Y-m-d H:i:s');
            //             $penalidad->id_registro_cobranza    = $cobranza->id_registro_cobranza;
            //         $penalidad->save();
            //     }
            // }

            /**
             * Consulta de OCAM y CDP
             */
            $ordenVista = 0;
            if ($cobranza->id_oc != null) {
                $ordenVista = OrdenCompraPropiaView::where('nro_orden', $request->oc)->orWhere('codigo_oportunidad', $request->cdp)->count();

                if ($ordenVista > 0) {
                    if (strpos($cobranza->ocam, 'DIRECTA') == 0) {
                        OrdenCompraDirecta::where('nro_orden', rtrim($cobranza->ocam))
                        ->update([
                            'factura'        => (($cobranza->factura !== '') && ($cobranza->factura != null)) ? $cobranza->factura : '',
                            'siaf'           => (($cobranza->siaf !== '') && ($cobranza->siaf != null)) ? $cobranza->siaf : '',
                            'orden_compra'   => (($cobranza->oc_fisica !== '') && ($cobranza->oc_fisica != null )) ? $cobranza->oc_fisica : '',
                        ]);
                    }

                    if (strpos($cobranza->ocam, 'OCAM') == 0) {
                        OrdenCompraPropias::where('orden_am', rtrim($cobranza->ocam))
                        ->update([
                            'factura'        => (($cobranza->factura !== '') && ($cobranza->factura != null)) ? $cobranza->factura : '',
                            'siaf'           => (($cobranza->siaf !== '') && ($cobranza->siaf != null)) ? $cobranza->siaf : '',
                            'orden_compra'   => (($cobranza->oc_fisica !== '') && ($cobranza->oc_fisica != null )) ? $cobranza->oc_fisica : '',
                        ]);
                    }
                }

                $penalidad = Penalidad::where('id_registro_cobranza', $cobranza->id_registro_cobranza)->where('estado', 1)->get();
                if ($penalidad) {
                    foreach ($penalidad as $key) {
                        $actualizarPenalidad = Penalidad::find($key->id_penalidad);
                            $actualizarPenalidad->id_oc = $cobranza->id_oc;
                        $actualizarPenalidad->save();
                    }
                }
            }
            DB::commit();
            return response()->json(["success" => true, "status" => 200, "data" => $cobranza, "pago" => $programacion_pago, "view" => $ordenVista]);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json(["success" => false, "status" => 500, "error" => $error]);
        }
    }

    public function editarRegistro(Request $request)
    {
        $registro_cobranza = RegistroCobranza::where('id_registro_cobranza', $request->id)->first();
        $contribuyente = array();
        $comercial_cliente = Cliente::find($registro_cobranza->id_cliente);
        if ($comercial_cliente) {
            $contribuyente = Contribuyente::where('id_contribuyente', $comercial_cliente->id_contribuyente)->first();
        }

        // $programacion_pago = ProgramacionPago::where('id_registro_cobranza',$registro_cobranza->id_registro_cobranza)
        //     ->where('estado', 1)->orWhere('id_cobranza', $registro_cobranza->id_cobranza_old)
        //     ->orderBy('id_programacion_pago', 'desc')->first();
        $programacion_pago = ProgramacionPago::where('id_registro_cobranza',$registro_cobranza->id_registro_cobranza)
        ->orderBy('id_programacion_pago', 'desc')
        ->first();
        return response()->json(["status" => 200, "success" => true, "data" => $registro_cobranza, "programacion_pago" => $programacion_pago, "cliente" => $contribuyente]);
    }

    public function eliminarRegistro(Request $request)
    {
        $registro_cobranza = RegistroCobranza::find($request->id);
            $registro_cobranza->estado = 0;
            $registro_cobranza->deleted_at   = new Carbon(); #obtiene la fecha de la eliminacion del registro
            $registro_cobranza->deleted_id   = Auth::user()->id_usuario; #obtiene la fecha de la eliminacion del registro
            $registro_cobranza->user_ip   = $request->ip();
        $registro_cobranza->save();

        $comentarioCabecera = 'SE PROCEDIO A ELIMINAR EL REGISTRO';
        LogActividad::registrar(Auth::user(), 'Eliminar registro de Cobranza', 4, $registro_cobranza->getTable(), null, $registro_cobranza, $comentarioCabecera,'GERENCIAL');
        return response()->json(["success" => true, "status" => 200]);
    }

    public function listarClientes()
    {
        // $data = Cliente::has('contribuyente')->get();
        $data = ContribuyenteView::select(['*'])->where('tipo', 'CLIENTE');
        return DataTables::of($data)->make(true);
    }

    public function buscarRegistro(Request $request)
    {
        // $data = DB::table('almacen.requerimiento_logistico_view');
        // if ($request->tipo == 'oc') {
        //     $data->where('requerimiento_logistico_view.nro_orden', $request->valor);
        // }
        // if ($request->tipo == 'cdp') {
        //     $data->where('requerimiento_logistico_view.codigo_oportunidad', $request->valor);
        // }
        // $data = $data->select(
        //     'requerimiento_logistico_view.id_requerimiento_logistico',
        //     'requerimiento_logistico_view.codigo_oportunidad',
        //     'requerimiento_logistico_view.nro_orden',
        //     'doc_ven.serie',
        //     'doc_ven.numero',
        //     'doc_ven.fecha_emision'
        // )
        // ->join('almacen.doc_vent_req', 'doc_vent_req.id_requerimiento', '=', 'requerimiento_logistico_view.id_requerimiento_logistico')
        // ->join('almacen.doc_ven', 'doc_ven.id_doc_ven', '=', 'doc_vent_req.id_doc_venta')->distinct();

        // $data = DB::table('mgcp_ordenes_compra.oc_propias_view');


        if ($request->tipo == 'oc') {
            // $data->where('requerimiento_logistico_view.nro_orden', $request->valor);
            $data = OrdenCompraPropiaView::where('nro_orden',$request->valor)->distinct();
        }
        if ($request->tipo == 'cdp') {
            // $data->where('requerimiento_logistico_view.codigo_oportunidad', $request->valor);
            $data = OrdenCompraPropiaView::where('codigo_oportunidad',$request->valor)->distinct();
        }

        return DataTables::of($data)->addColumn('documento', function ($data) { return $data->serie.'-'.$data->numero; })->make(true);
    }

    public function buscarContacto(Request $request)
    {

        $data = Observaciones::select('nombre_contacto','telefono_contacto','area_contacto_id')->with('areaContacto')->where([['cobranza_id', $request->cobranza_id],['estado','!=',7]])->distinct();


        return DataTables::of($data)->make(true);
    }


    public function cargarDatosRequerimiento($id_requerimiento)
    {
        // $cliente_gerencial = DB::table('almacen.requerimiento_logistico_view')
        // ->where('requerimiento_logistico_view.id_requerimiento_logistico', $id_requerimiento)
        // ->select(
        //     'requerimiento_logistico_view.id_requerimiento_logistico',
        //     'requerimiento_logistico_view.codigo_oportunidad',
        //     'requerimiento_logistico_view.nro_orden',
        //     'requerimiento_logistico_view.id_contribuyente_cliente',
        //     'requerimiento_logistico_view.id_contribuyente_empresa',
        //     'doc_vent_req.id_documento_venta_requerimiento',
        //     'doc_ven.id_doc_ven',
        //     'doc_ven.serie',
        //     'doc_ven.numero',
        //     'doc_ven.fecha_emision',
        //     'doc_ven.credito_dias',
        //     'doc_ven.total_a_pagar',
        //     'adm_contri.nro_documento',
        //     'adm_contri.razon_social',
        //     'com_cliente.id_cliente'
        // )
        // ->join('almacen.doc_vent_req', 'doc_vent_req.id_requerimiento', '=', 'requerimiento_logistico_view.id_requerimiento_logistico')
        // ->join('almacen.doc_ven', 'doc_ven.id_doc_ven', '=', 'doc_vent_req.id_doc_venta')

        // ->join('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'requerimiento_logistico_view.id_requerimiento_logistico')
        // ->join('comercial.com_cliente', 'com_cliente.id_cliente', '=', 'alm_req.id_cliente')
        // ->join('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'com_cliente.id_contribuyente')->first();

        $doc_ven = [];
        // $oc_propias_view = DB::table('mgcp_ordenes_compra.oc_propias_view')->where('nro_orden',$cliente_gerencial->nro_orden)->first();


        // if ($cliente_gerencial) {
        //     $doc_ven = DocumentoVenta::where('id_doc_ven', $cliente_gerencial->id_doc_ven)->first();
        //     return response()->json(["status"=>200, "success"=>true, "data"=>$cliente_gerencial, "factura"=>$doc_ven, "oc"=>$oc_propias_view ? $oc_propias_view:[]]);
        // }else{
        //     return response()->json(["status"=>400, "success"=>false, "data"=>$cliente_gerencial, "factura"=>$doc_ven, "oc"=> $oc_propias_view ? $oc_propias_view : []]);
        // }
        $oc_propias_view = OrdenCompraPropiaView::find($id_requerimiento);
        if ($oc_propias_view) {
            return response()->json(["status"=>200,"data"=>$oc_propias_view],200);
        }else{
            return response()->json(["status"=>401],401);
        }
    }

    public function obtenerFase($id)
    {
        $fases = RegistroCobranzaFase::where('id_registro_cobranza', $id)->get();
        return response()->json(["success" => true, "status" => 200, "fases"=> $fases]);
    }

    public function guardarFase(Request $request)
    {
        $nuevo = new RegistroCobranzaFase();
            $nuevo->id_registro_cobranza = $request->id_registro_cobranza;
            $nuevo->fase = $request->fase;
            $nuevo->fecha = $request->fecha_fase;
        $nuevo->save();

        $comentarioCabecera = 'SE PROCEDIO A CREAR UNA FASE - cobranza_id'.$request->id_registro_cobranza;
        LogActividad::registrar(Auth::user(), 'Crear FASE', 2, $nuevo->getTable(), null, $nuevo, $comentarioCabecera,'GERENCIAL - FASES');

        return response()->json(["success" => true, "status" => 200, "data" => $nuevo]);
    }

    public function eliminarFase(Request $request)
    {
        $cobranza = RegistroCobranzaFase::find($request->id);
        $comentarioCabecera = 'SE PROCEDIO A ELIMINAR UNA FASE - cobranza_id'.$cobranza->cobranza_id;
        LogActividad::registrar(Auth::user(), 'ELIMINAR FASE', 4, $cobranza->getTable(), null, $cobranza, $comentarioCabecera,'GERENCIAL - FASES');

        RegistroCobranzaFase::find($request->id)->delete();



        return response()->json(["success" => true, "status" => 200]);
    }

    public function obtenerObservaciones($id)
    {
        $cobranza= RegistroCobranza::find($id);
        $guia=[];
        $guiaDespacho=OrdenCompraPropias::leftJoin('mgcp_oportunidades.oportunidades','oportunidades.id','=','oc_propias.id_oportunidad')
        ->leftJoin('mgcp_cuadro_costos.cc','cc.id_oportunidad','=','oportunidades.id')
        ->leftJoin('almacen.alm_req','alm_req.id_cc','=','cc.id')
        ->leftJoin('almacen.orden_despacho','orden_despacho.id_requerimiento','=','alm_req.id_requerimiento')
        ->leftJoin('almacen.orden_despacho_obs','orden_despacho_obs.id_od','=','orden_despacho.id_od')
        ->where([['alm_req.estado','!=',7],['orden_despacho.estado','!=',7],['oc_propias.id',$cobranza->id_oc],['orden_despacho.aplica_cambios',false],['orden_despacho_obs.accion',8]])->orderBy('orden_despacho_obs.fecha_registro','desc')->limit(1)->get();

        if(count($guiaDespacho)>0){
            $guia=[
                'fecha_entrega_real'=>$guiaDespacho[0]['fecha_estado'],
                'fecha_registro'=>$guiaDespacho[0]['fecha_registro'],
                'estado_entrega'=>$guiaDespacho[0]['estado_entrega'],
                'adjunto'=>$guiaDespacho[0]['adjunto']
            ];

        }


        $observaciones = Observaciones::with(['estadoDocumento','usuario','areaContacto','adjunto' => function ($q) {
            $q->where('cobranza_adjunto_observacion.estado','!=', 7);
        }

        ])->where([['cobranza_id', $id],['estado','!=',7]])->orderBy('created_at', 'desc')->get();
        return response()->json(["success" => true, "status" => 200, "observaciones"=> $observaciones,"guia"=>$guia]);
    }

    public function guardarObservaciones(Request $request)
    {
        $registro_cobranza = RegistroCobranza::find($request->cobranza_id);
        $registro_cobranza->id_estado_doc = $request->estado;
        $registro_cobranza->updated_at = new Carbon();
        $registro_cobranza->save();

            $observacion = new Observaciones();
            $observacion->descripcion = $request->descripcion_observacion;
            $observacion->cobranza_id = $request->cobranza_id;
            $observacion->usuario_id = Auth::user()->id_usuario;
            $observacion->oc_id = ($registro_cobranza) ? $registro_cobranza->id_oc : null;
            $observacion->estado = $request->estado;
            $observacion->fecha_observacion = $request->fecha_observacion;
            $observacion->fecha_documento = $request->fecha_documento;
            $observacion->telefono_contacto = $request->telefono_contacto;
            $observacion->nombre_contacto = $request->nombre_contacto;
            $observacion->area_contacto_id = $request->area_contacto;
            $observacion->created_at = date('Y-m-d H:i:s');
            $observacion->updated_at = date('Y-m-d H:i:s');
            $observacion->save();



            if($observacion->id >0 && (isset($request->archivo_adjunto) || $request->archivo_adjunto !=null)){
                // $cantidadDeAdjunto =  count($request->adjunto);
                foreach (($request->archivo_adjunto) as $key => $archivo) {
                if ($archivo != null) {
                    $fechaHoy = new Carbon();
                    $sufijo = $fechaHoy->format('YmdHis');
                    $file = $archivo->getClientOriginalName();
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $newNameFile = $observacion->cobranza_id . $key  . $sufijo . '.' . $extension;
                    Storage::disk('archivos')->put("cobranzas/" . $newNameFile, File::get($archivo));

                    $idAdjunto = DB::table('cobranza.cobranza_adjunto_observacion')->insertGetId(
                        [
                            'observacion_id'            => $observacion->id,
                            'cobranza_id'               => $observacion->cobranza_id,
                            'archivo'                   => $newNameFile,
                            'estado'                    => 1,
                            'usuario_id'                => Auth::user()->id_usuario,
                            'created_at'                => $fechaHoy
                        ],
                        'id'
                    );

                    $idAdjuntoList[] = $idAdjunto;
                }
            }

        }

        $comentarioCabecera = 'SE PROCEDIO A CREAR UNA OBSERVACIÓN - cobranza_id'.$request->cobranza_id;
        LogActividad::registrar(Auth::user(), 'Crear observaciones', 2, $observacion->getTable(), null, $observacion, $comentarioCabecera,'GERENCIAL - OBSERVACIONES');

        return response()->json(["success" => true, "status" => 200, "data" => $observacion]);
    }

    public function eliminarObservaciones(Request $request)
    {
        $observacion = Observaciones::find($request->id);
        $observacion->estado = 7;
        $observacion->save();

        $adjuntoObservacion = CobranzaAdjuntoObservacion::where('observacion_id',$request->id)->get();
        foreach ($adjuntoObservacion as $adjunto) {
            $adjunto = CobranzaAdjuntoObservacion::find($adjunto->id);
            $adjunto->estado =7;
            $adjunto->save();
        }

        $comentarioCabecera = 'SE PROCEDIO A ELIMINAR UNA OBSERVACIÓN - cobranza_id'.$observacion->cobranza_id;
        LogActividad::registrar(Auth::user(), 'ELIMINAR observaciones', 4, $observacion->getTable(), null, $observacion, $comentarioCabecera,'GERENCIAL - OBSERVACIONES');

        return response()->json(["success" => true, "status" => 200]);
    }

    public function obtenerPenalidades(Request $request)
    {
        $tipo = Str::upper($request->tipo);
        $penalidades = Penalidad::where('id_registro_cobranza', $request->id)->where('tipo', $tipo)->where('estado', '!=', 7)->orderBy('fecha_registro', 'desc')->get();
        return response()->json(["success" => true, "status" => 200, "penalidades" => $penalidades]);
    }

    public function guardarPenalidad(Request $request)
    {
        $registro_cobranza = RegistroCobranza::find($request->id_cobranza);

        if ($registro_cobranza) {
            $estado = ($request->tipo_registro == 'penalidad') ? 'APLICADA' :'ELABORADO';

            $penalidad = Penalidad::firstOrNew(['id_penalidad' => $request->id_penalidad]);
                $penalidad->tipo = Str::upper($request->tipo_registro);
                $penalidad->monto = $request->importe_penal;
                $penalidad->documento = $request->doc_penal;
                $penalidad->fecha = $request->fecha_penal;
                $penalidad->observacion = $request->obs_penal;
                $penalidad->estado = 1;
                $penalidad->fecha_registro = new Carbon;
                $penalidad->id_registro_cobranza  = $request->id_cobranza;
                $penalidad->id_oc  = $registro_cobranza->id_oc;
                if ($request->id_penalidad == 0) {
                    $penalidad->estado_penalidad = $estado;
                }
                // $penalidad->id_usuario = Auth::user()->id_usuario;
            $penalidad->save();
        }
        return response()->json(["status" => 200, "success" => true, "data" => $penalidad]);
    }

    public function cambioEstadoPenalidad(Request $request)
    {
        $penalidad = Penalidad::find($request->id);
            $penalidad->estado_penalidad = $request->estado_penalidad;
            $penalidad->motivo = ($request->estado_penalidad == 'DEVOLUCION') ? $request->estado_penalidad.' DE LA PENALIDAD' : 'PENALIDAD '.$request->estado_penalidad;
        $penalidad->save();

        if ($request->estado_penalidad == 'DEVOLUCION') {
            $control = new PenalidadCobro();
                $control->id_penalidad = $penalidad->id_penalidad;
                $control->id_registro_cobranza = $penalidad->id_registro_cobranza;
                $control->importe = $penalidad->monto;
                $control->estado = 'PENDIENTE';
                $control->gestion = $request->gestion;
            $control->save();
        }
        return response()->json($penalidad,200);
    }

    public function exportarExcel()
    {
        return Excel::download(new CobranzaExport(), 'cobranza.xlsx');
    }

    /**
     * Script Migracion
     */

    public function scriptRegistroFase()
    {
        $data = RegistroCobranza::where('estado', 1)->get();
        $cont = 0;
        foreach ($data as $key) {
            $nuevo = new RegistroCobranzaFase();
                $nuevo->id_registro_cobranza = $key->id_registro_cobranza;
                $nuevo->fase = 'COMPROMISO';
                $nuevo->fecha = $key->fecha_registro;
            $nuevo->save();
            $cont++;
        }
        return response()->json($cont, 200);
    }

    public function scriptFasesActual()
    {
        $cobranzas_fases = DB::table('gerencial.cobranza_fase')->where('estado', 1)->get();
        $init = 0;

        foreach ($cobranzas_fases as $key => $value) {
            $cobranza = RegistroCobranza::where('id_cobranza_old', $value->id_cobranza)->first();
            $cobranza_fase = new CobranzaFase();
            $cobranza_fase->id_registro_cobranza = $cobranza->id_registro_cobranza;
                $cobranza_fase->fase = $value->fase;
                $cobranza_fase->fecha = $value->fecha;
                $cobranza_fase->estado = $value->estado;
                $cobranza_fase->fecha_registro = $value->fecha_registro;
                $cobranza_fase->id_cobranza = $value->id_cobranza;
            $cobranza_fase->save();
            $init++;
        }

        $dataActiva = CobranzaFase::all();
        $cont = 0;
        foreach ($dataActiva as $key) {
            $consulta = RegistroCobranzaFase::where('id_registro_cobranza', $key->id_registro_cobranza)->where('fase', $key->fase)->count();
            if ($consulta == 0) {
                $nuevo = new RegistroCobranzaFase();
                    $nuevo->id_registro_cobranza = $key->id_registro_cobranza;
                    $nuevo->fase = $key->fase;
                    $nuevo->fecha = $key->fecha;
                $nuevo->save();
                $cont++;
            }
        }

        $dataInactiva = CobranzaFase::where('estado', 0)->get();
        $dele = 0;

        foreach ($dataInactiva as $row) {
            $eliminar = RegistroCobranzaFase::where('id_registro_cobranza', $row->id_registro_cobranza);
            if ($eliminar) {
                $eliminar->delete();
            }
            $dele++;
        }
        return response()->json(array("inicializado" => $init, "cargados" => $cont, "eliminados" => $dele), 200);
    }

    public function scriptPeriodosActual()
    {
        $cont = 0;
        // $cobranza = Cobranza::all();

        // foreach ($cobranza as $key) {
        //     $periodos = DB::table('gerencial.periodo')->select('descripcion')->where('id_periodo', $key->id_periodo)->first();
        //     RegistroCobranza::where('id_cobranza_old', $key->id_cobranza)->update(['periodo' => $periodos->descripcion]);
        //     $cont++;
        // }
        $cobranza = RegistroCobranza::all();

        foreach ($cobranza as $key) {
            $periodos = Periodo::where('descripcion', $key->periodo)->first();
            RegistroCobranza::where('id_registro_cobranza', $key->id_registro_cobranza)->update(['id_periodo' => $periodos->id_periodo]);
            $cont++;
        }
        return response()->json($cont, 200);
    }

    public function scriptFases()
    {
        $cobranzas_fases = DB::table('gerencial.cobranza_fase')->where('estado', 1)->get();
        $init = 0;

        foreach ($cobranzas_fases as $key => $value) {
            $cobranza = RegistroCobranza::where('id_cobranza_old', $value->id_cobranza)->first();
            $cobranza_fase = new CobranzaFase();
            $cobranza_fase->id_registro_cobranza = $cobranza->id_registro_cobranza;
                $cobranza_fase->fase = $value->fase;
                $cobranza_fase->fecha = $value->fecha;
                $cobranza_fase->estado = $value->estado;
                $cobranza_fase->fecha_registro = $value->fecha_registro;
                $cobranza_fase->id_cobranza = $value->id_cobranza;
            $cobranza_fase->save();
            $init++;
        }

        $dataActiva = CobranzaFase::all();
        $cont = 0;
        foreach ($dataActiva as $key) {
            $consulta = RegistroCobranzaFase::where('id_registro_cobranza', $key->id_registro_cobranza)->where('fase', $key->fase)->count();
            if ($consulta == 0) {
                $nuevo = new RegistroCobranzaFase();
                    $nuevo->id_registro_cobranza = $key->id_registro_cobranza;
                    $nuevo->fase = $key->fase;
                    $nuevo->fecha = $key->fecha;
                $nuevo->save();
                $cont++;
            }
        }

        $dataInactiva = CobranzaFase::where('estado', 0)->get();
        $dele = 0;

        foreach ($dataInactiva as $row) {
            $eliminar = RegistroCobranzaFase::where('id_registro_cobranza', $row->id_registro_cobranza);
            if ($eliminar) {
                $eliminar->delete();
            }
            $dele++;
        }
        return response()->json(array("inicializado" => $init, "cargados" => $cont, "eliminados" => $dele), 200);
    }

    public function restar_fechas($fi, $ff){
		$ini = strtotime($fi);
		$fin = strtotime($ff);
		$dif = $fin - $ini;
		$diasFalt = ((($dif / 60) / 60) / 24);
		return ceil($diasFalt);
	}
    public function scriptContribuyenteCliente()
    {
        $contribuyente = Contribuyente::all();
        $array_clientes_nuevos=array();
        foreach ($contribuyente as $key => $value) {
            $com_cliente = Cliente::where('id_contribuyente',$value->id_contribuyente)->first();
            if (!$com_cliente) {
                $com_cliente = new Cliente();
                $com_cliente->id_contribuyente = $value->id_contribuyente;
                // $com_cliente->observacion = $request->observacion;
                $com_cliente->estado = 1;
                // $com_cliente->fecha_registro = new Carbon();
                $com_cliente->save();
                array_push($array_clientes_nuevos,$com_cliente);
            }
        }
        return response()->json(["data"=>$array_clientes_nuevos],200);
    }
    public function scriptGenerarCodigoCliente()
    {
        $sincodigo = Cliente::where('codigo','!=',null)->count();

        $com_cliente = Cliente::orderBy('id_cliente','ASC')->where('codigo',null)->get();
        foreach ($com_cliente as $key => $value) {
            $codigo = ConfiguracionHelper::generarCodigo('C','-',3,'clienteCodigo');
            $cliente = Cliente::find($value->id_cliente);
            $cliente->codigo = $codigo;
            $cliente->save();
        }
        $con_codigo = Cliente::where('codigo','!=',null)->count();

        return response()->json(["mensaje"=>"Los clientes cuenta con su codigo correspondiente","cantidad_null"=>$sincodigo,"cantidad_not_null"=>$con_codigo],200);
    }
    public function scriptGenerarCodigoProveedores()
    {
        $sincodigo = Proveedor::where('codigo','!=',null)->count();

        $log_proveedor = Proveedor::orderBy('id_proveedor','ASC')->where('codigo',null)->get();
        foreach ($log_proveedor as $key => $value) {
            $codigo = ConfiguracionHelper::generarCodigo('P','-',3,'proveedoresCodigo');
            $proveedor = Proveedor::find($value->id_proveedor);
            $proveedor->codigo = $codigo;
            $proveedor->save();
        }
        $con_codigo = Proveedor::where('codigo','!=',null)->count();

        return response()->json(["mensaje"=>"Los proveedores cuenta con su codigo correspondiente","cantidad_null"=>$sincodigo,"cantidad_not_null"=>$con_codigo],200);
    }

    public function cargaManual() {
        $data = RegistroCobranza::where('fecha_registro', '2023-07-14')->get();
        $count = 0;

        foreach ($data as $key) {
            $nuevo = new RegistroCobranzaFase();
                $nuevo->id_registro_cobranza = $key->id_registro_cobranza;
                $nuevo->fase = 'COMPROMISO';
                $nuevo->fecha = $key->fecha_registro;
            $nuevo->save();
            $count++;
        }
        return response()->json($count, 200);
    }
    public function guardarVededor(Request $request) {
        $data = new Vendedor();
        $data->nombre = $request->nombre;
        $data->estado = 1;
        $data->save();
        $vendedores = Vendedor::where('estado',1)->get();
        return response()->json($data,200);
    }
}
