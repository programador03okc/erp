<?php

namespace App\Http\Controllers\Tesoreria;

use App\Exports\OrdenCompraServicioExport;
use App\Exports\OrdenCompraServicioNivelItemExport;
use App\Exports\OrdenesCompraServicioExport;
use App\Exports\RegistroPagosExport;
use App\Exports\RequerimientoPagosExport;
use App\Exports\RequerimientoPagosNivelItemExport;
use App\Helpers\Finanzas\PresupuestoInternoHistorialHelper;
use App\Http\Controllers\AlmacenController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoInternoController;
use App\Http\Controllers\Proyectos\Opciones\PresupuestoInternoController as OpcionesPresupuestoInternoController;
use App\Models\Administracion\Empresa;
use App\Models\Administracion\Prioridad;
use App\Models\Almacen\AdjuntoDetalleRequerimiento;
use App\Models\Almacen\AdjuntoRequerimiento;
use App\Models\Almacen\DetalleRequerimiento;
use App\Models\Almacen\Requerimiento;
use App\Models\Configuracion\LogActividad;
use App\Models\Configuracion\Moneda;
use App\Models\Finanzas\HistorialPresupuestoInternoSaldo;
use App\Models\Finanzas\PresupuestoInternoDetalle;
use App\Models\Logistica\Orden;
use App\Models\Logistica\OrdenCompraDetalle;
use App\Models\Logistica\PagoCuota;
use App\Models\Logistica\PagoCuotaDetalle;
use App\Models\Rrhh\Persona;
use App\Models\Tesoreria\OtrosAdjuntosTesoreria;
use App\Models\Tesoreria\RegistroPago;
use App\Models\Tesoreria\RegistroPagoAdjuntos;
use App\Models\Tesoreria\RequerimientoPago;
use App\Models\Tesoreria\RequerimientoPagoAdjunto;
use App\Models\Tesoreria\RequerimientoPagoAdjuntoDetalle;
use App\Models\Tesoreria\RequerimientoPagoCategoriaAdjunto;
use App\Models\Tesoreria\RequerimientoPagoDetalle;
use App\Models\Tesoreria\RequerimientoPagoEstados;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
// use Debugbar;
use Exception;

class RegistroPagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view_main_tesoreria()
    {
        $pagos_pendientes = DB::table('almacen.alm_req')
            ->where('estado', 8)->count();

        $confirmaciones_pendientes = DB::table('almacen.alm_req')
            ->where([['estado', '=', 19], ['confirmacion_pago', '=', false]])->count();

        $tipo_cambio = DB::table('contabilidad.cont_tp_cambio')->orderBy('fecha', 'desc')->first();

        return view('tesoreria.main', get_defined_vars());
    }

    function view_pendientes_pago()
    {
        $prioridad = Prioridad::all();
        $empresas = AlmacenController::select_empresa();
        $estados = RequerimientoPagoEstados::all();
        $moneda = Moneda::all();
        // return view('tesoreria/pagos/pendientesPago', compact('empresas'));
        return view('tesoreria.Pagos.pendientesPago', get_defined_vars());
    }

    public function listarRequerimientosPago(Request $request)
    {
        // return $request->all();exit;

        $data = DB::table('tesoreria.requerimiento_pago')
            ->select(
                'requerimiento_pago.*',
                'adm_prioridad.descripcion as prioridad',
                'adm_contri.nro_documento',
                'adm_contri.razon_social',
                'empresa.razon_social as razon_social_empresa',
                'adm_empresa.codigo as codigo_empresa',
                'sis_moneda.simbolo',
                'sis_grupo.descripcion as grupo_descripcion',
                'requerimiento_pago_estado.descripcion as estado_doc',
                'requerimiento_pago_estado.bootstrap_color',
                'sis_sede.descripcion as sede_descripcion',
                'adm_cta_contri.nro_cuenta',
                'adm_cta_contri.nro_cuenta_interbancaria',
                'adm_tp_cta.descripcion as tipo_cuenta',
                'banco_contribuyente.razon_social as banco_contribuyente',
                'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
                'rrhh_cta_banc.nro_cci as nro_cci_persona',
                'tp_cta_persona.descripcion as tipo_cuenta_persona',
                'banco_persona.razon_social as banco_persona',
                'sis_usua.nombre_corto',
                'autorizado.nombre_corto as nombre_autorizado',
                'rrhh_perso.nro_documento as dni_persona',
                DB::raw("concat(rrhh_perso.nombres, ' ' ,rrhh_perso.apellido_paterno, ' ' ,rrhh_perso.apellido_materno) AS persona"),
                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_adjunto
                        WHERE requerimiento_pago_adjunto.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and requerimiento_pago_adjunto.id_estado != 7) AS count_adjunto_cabecera"),

                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_detalle_adjunto
                        INNER JOIN tesoreria.requerimiento_pago_detalle as detalle on(
                            detalle.id_requerimiento_pago_detalle = requerimiento_pago_detalle_adjunto.id_requerimiento_pago_detalle
                        )
                        WHERE detalle.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and requerimiento_pago_detalle_adjunto.id_estado != 7) AS count_adjunto_detalle"),

                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                        WHERE registro_pago.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and registro_pago.estado != 7) AS suma_pagado")
            )
            // ->join('logistica.log_prove', 'log_prove.id_proveedor', '=', 'requerimiento_pago.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'requerimiento_pago.id_contribuyente')
            ->leftjoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'requerimiento_pago.id_persona')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
            ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'requerimiento_pago.id_estado')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'requerimiento_pago.id_prioridad')
            ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'requerimiento_pago.id_sede')
            ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'requerimiento_pago.id_empresa')
            ->join('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'requerimiento_pago.id_cuenta_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'requerimiento_pago.id_cuenta_persona')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->join('configuracion.sis_grupo', 'sis_grupo.id_grupo', '=', 'requerimiento_pago.id_grupo')
            ->join('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'requerimiento_pago.id_usuario')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'requerimiento_pago.usuario_autorizacion');



        $data = $data->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9]);
        // ->where([['requerimiento_pago.id_estado', '!=', 7], ['requerimiento_pago.id_estado', '!=', 1]]);
        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '<=', $request->fecha_final);
        }
        // return $request->prioridad;exit;
        return datatables($data)->filterColumn('persona', function ($query, $keyword) {
            $keywords = trim(strtoupper($keyword));
            $query->whereRaw("UPPER(CONCAT(rrhh_perso.nombres,' ',rrhh_perso.apellido_paterno,' ',rrhh_perso.apellido_materno)) LIKE ?", ["%{$keywords}%"]);
        })->toJson();
    }

    public function listarOrdenesCompra(Request $request)
    {
        $data = Orden::select(
            'log_ord_compra.*',
            'adm_contri.nro_documento',
            'adm_contri.razon_social',
            'adm_empresa.id_empresa',
            'empresa.razon_social as razon_social_empresa',
            'adm_empresa.codigo as codigo_empresa',
            'requerimiento_pago_estado.descripcion as estado_doc',
            'requerimiento_pago_estado.bootstrap_color',
            'sis_moneda.simbolo',
            'log_cdn_pago.descripcion AS condicion_pago',
            'sis_sede.descripcion as sede_descripcion',
            'adm_cta_contri.nro_cuenta',
            'adm_cta_contri.nro_cuenta_interbancaria',
            'adm_tp_cta.descripcion as tipo_cuenta',
            'banco_contribuyente.razon_social as banco_contribuyente',
            'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
            'rrhh_cta_banc.nro_cci as nro_cci_persona',
            'tp_cta_persona.descripcion as tipo_cuenta_persona',
            'banco_persona.razon_social as banco_persona',
            DB::raw("CONCAT(rrhh_perso.nombres,' ',rrhh_perso.apellido_paterno,' ',rrhh_perso.apellido_materno) as nombre_completo_persona"),
            'rrhh_perso.nro_documento as nro_documento_persona',
            'adm_prioridad.descripcion as prioridad',
            'autorizado.nombre_corto as nombre_autorizado',
            DB::raw("(SELECT sum(subtotal) FROM logistica.log_det_ord_compra
                        WHERE log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra
                        and log_det_ord_compra.estado != 7) AS suma_total"),
            DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                        WHERE registro_pago.id_oc = log_ord_compra.id_orden_compra
                        and registro_pago.estado != 7) AS suma_pagado"),
            DB::raw("(SELECT COUNT(adjuntos_logisticos.id_adjunto)
                    FROM logistica.adjuntos_logisticos
                    WHERE  adjuntos_logisticos.id_orden = log_ord_compra.id_orden_compra AND
                    adjuntos_logisticos.estado != 7) AS cantidad_adjuntos_logisticos"),
            DB::raw("(SELECT monto_cuota FROM logistica.pago_cuota_detalle
            inner join logistica.pago_cuota on pago_cuota.id_pago_cuota = pago_cuota_detalle.id_pago_cuota
            WHERE  pago_cuota.id_orden = log_ord_compra.id_orden_compra and pago_cuota_detalle.id_estado != 7 order by pago_cuota_detalle.fecha_registro desc limit 1 ) AS ultima_monto_cuota"),
            DB::raw("(SELECT sum(monto_cuota) FROM logistica.pago_cuota_detalle
            inner join logistica.pago_cuota on pago_cuota.id_orden = log_ord_compra.id_orden_compra
            WHERE pago_cuota.id_pago_cuota = pago_cuota_detalle.id_pago_cuota
            and pago_cuota_detalle.id_estado =5) AS suma_cuotas_con_autorizacion"),

        )
            ->leftjoin('logistica.log_prove', 'log_prove.id_proveedor', '=', 'log_ord_compra.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->leftjoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'log_ord_compra.estado_pago')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'log_ord_compra.id_moneda')
            ->leftJoin('logistica.log_cdn_pago', 'log_cdn_pago.id_condicion_pago', '=', 'log_ord_compra.id_condicion')
            ->leftjoin('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')
            ->leftjoin('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'log_ord_compra.id_cta_principal')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'log_ord_compra.id_persona_pago')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'log_ord_compra.id_cuenta_persona_pago')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'log_ord_compra.usuario_autorizacion')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'log_ord_compra.id_prioridad_pago')
            ->whereIn('log_ord_compra.estado_pago', [8, 5, 6, 9, 10])
            ->where('log_ord_compra.estado', '!=', 7);

        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '<=', $request->fecha_final);
        }
        return DataTables::eloquent($data)
            ->filterColumn('requerimientos', function ($query, $keyword) {
                $sql_oc = "id_orden_compra IN (
                SELECT log_det_ord_compra.id_orden_compra FROM logistica.log_det_ord_compra
                INNER JOIN almacen.alm_det_req ON
                 log_det_ord_compra.id_detalle_requerimiento = alm_det_req.id_detalle_requerimiento
                INNER JOIN almacen.alm_req ON
                 alm_req.id_requerimiento = alm_det_req.id_requerimiento
                 WHERE   UPPER(alm_req.codigo) LIKE ? )
                    ";
                $query->whereRaw($sql_oc, ['%' . strtoupper($keyword) . '%']);
            })->toJson();
    }

    public function listarComprobantesPagos()
    {
        $data = DB::table('almacen.doc_com')
            ->select(
                'doc_com.id_doc_com',
                'doc_com.serie',
                'doc_com.numero',
                'adm_contri.razon_social',
                'doc_com.fecha_emision',
                'doc_com.fecha_vcmto',
                'doc_com.serie',
                'doc_com.total_a_pagar',
                'doc_com.estado',
                'doc_com.credito_dias',
                'adm_estado_doc.estado_doc',
                'adm_estado_doc.bootstrap_color',
                'sis_moneda.simbolo',
                'log_cdn_pago.descripcion AS condicion_pago',
                'cont_tp_doc.descripcion as tipo_documento',
                'adm_cta_contri.nro_cuenta',
                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                      WHERE registro_pago.id_doc_com = doc_com.id_doc_com
                        and registro_pago.estado != 7) AS suma_pagado")
            )
            ->join('logistica.log_prove', 'log_prove.id_proveedor', '=', 'doc_com.id_proveedor')
            ->join('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->join('administracion.adm_estado_doc', 'adm_estado_doc.id_estado_doc', '=', 'doc_com.estado')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'doc_com.moneda')
            ->leftJoin('logistica.log_cdn_pago', 'log_cdn_pago.id_condicion_pago', '=', 'doc_com.id_condicion')
            ->leftJoin('contabilidad.cont_tp_doc', 'cont_tp_doc.id_tp_doc', '=', 'doc_com.id_tp_doc')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'doc_com.id_cta_bancaria')
            ->where('doc_com.id_condicion', 2)
            ->whereIn('doc_com.estado', [1, 9]);

        // return datatables($data)->toJson();
        return DataTables::of($data)
            ->editColumn('fecha_emision', function ($data) {
                return ($data->fecha_emision !== null ? date('d-m-Y', strtotime($data->fecha_emision)) : '');
            })
            ->editColumn('condicion_pago', function ($data) {
                return ($data->condicion_pago !== null ? ($data->condicion_pago . ' ' . $data->credito_dias . ' días') : '');
            })
            ->editColumn('fecha_vcmto', function ($data) {
                return ($data->fecha_vcmto !== null ? date('d-m-Y', strtotime($data->fecha_vcmto)) : '');
            })
            ->addColumn('total_a_pagar_format', function ($data) {
                return ($data->total_a_pagar !== null ? number_format($data->total_a_pagar, 2) : '0.00');
            })
            ->addColumn('span_estado', function ($data) {
                $estado = ($data->estado == 9 ? 'Pagada' : $data->estado_doc);
                return '<span class="label label-' . $data->bootstrap_color . '">' . $estado . '</span>';
            })
            ->rawColumns(['span_estado', 'total_a_pagar_format'])

            ->make(true);
    }

    public function listarPagos($tipo, $id)
    {
        $detalles = DB::table('tesoreria.registro_pago')
            ->select(
                'registro_pago.*',
                'sis_usua.nombre_corto',
                'sis_moneda.simbolo',
                'adm_contri.razon_social as razon_social_empresa',
                'adm_cta_contri.nro_cuenta',
                DB::raw("(SELECT count(adjunto) FROM tesoreria.registro_pago_adjuntos
                      WHERE registro_pago_adjuntos.id_pago = registro_pago.id_pago
                        and registro_pago_adjuntos.estado != 7) AS count_adjuntos")
            )
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'registro_pago.registrado_por')
            ->leftJoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'registro_pago.id_empresa')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'registro_pago.id_cuenta_origen');

        if ($tipo == "orden") {
            $query = $detalles->leftJoin('logistica.log_ord_compra', 'log_ord_compra.id_orden_compra', '=', 'registro_pago.id_oc')
                ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'log_ord_compra.id_moneda')
                ->where([['registro_pago.id_oc', '=', $id], ['registro_pago.estado', '!=', 7]])
                ->get();
        } else if ($tipo == "requerimiento pago") {
            $query = $detalles->join('tesoreria.requerimiento_pago', 'requerimiento_pago.id_requerimiento_pago', '=', 'registro_pago.id_requerimiento_pago')
                ->join('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
                ->where([['registro_pago.id_requerimiento_pago', '=', $id], ['registro_pago.estado', '!=', 7]])
                ->get();
        } else if ($tipo == "comprobante") {
            $query = $detalles->leftJoin('almacen.doc_com', 'doc_com.id_doc_com', '=', 'registro_pago.id_doc_com')
                ->join('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'doc_com.moneda')
                ->where([['registro_pago.id_doc_com', '=', $id], ['registro_pago.estado', '!=', 7]])
                ->get();
        }
        return response()->json($query);
    }
    public function listarPagosEnCuotas($tipo, $id)
    {
        $query = [];
        if ($tipo == "orden") {
            $query = PagoCuota::with(['orden', 'detalle' => function ($query) {
                $query->orderBy('fecha_registro', 'asc');
            }, 'detalle.creadoPor', 'detalle.adjuntos', 'detalle.estado'])->where('id_orden', $id)->first();
        }

        return response()->json($query);
    }

    public function verAdjuntosPago($id)
    {
        $adjuntos = DB::table('tesoreria.registro_pago_adjuntos')
            ->where('id_pago', $id)
            ->get();
        return response()->json($adjuntos);
    }

    function anularAdjuntoTesoreria(Request $request)
    {
        DB::beginTransaction();
        try {

            $estado_accion = '';
            $adjunto = RequerimientoPagoAdjunto::find($request->id_adjunto);
            if (isset($adjunto)) {
                $adjunto->id_estado = 7;
                $adjunto->save();
                $estado_accion = 'success';
                $mensaje = 'Adjuntos anulado';
            } else {
                $estado_accion = 'warning';
                $mensaje = 'Hubo un problema y no se pudo anular el adjuntos';
            }
            DB::commit();

            return response()->json(['status' => $estado_accion, 'mensaje' => $mensaje]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'mensaje' => 'Hubo un problema al anular el adjuntos. Por favor intentelo de nuevo. Mensaje de error: ' . $e->getMessage()]);
        }
    }
    /*
    public function pagosRequerimientos($id_requerimiento_pago)
    {
        $detalles = DB::table('tesoreria.registro_pago')
            ->select(
                'registro_pago.*',
                'sis_usua.nombre_corto',
                'sis_moneda.simbolo',
                'adm_contri.razon_social as razon_social_empresa',
                'adm_cta_contri.nro_cuenta'
            )
            ->leftJoin('tesoreria.requerimiento_pago', 'requerimiento_pago.id_requerimiento_pago', '=', 'registro_pago.id_requerimiento_pago')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'registro_pago.registrado_por')
            ->leftJoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'registro_pago.id_empresa')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'registro_pago.id_cuenta_origen')
            ->where([
                ['registro_pago.id_requerimiento_pago', '=', $id_requerimiento_pago],
                ['registro_pago.estado', '!=', 7]
            ])
            ->get();

        return response()->json($detalles);
    }

    public function pagosComprobante($id_doc_com)
    {
        $detalles = DB::table('tesoreria.registro_pago')
            ->select(
                'registro_pago.*',
                'sis_usua.nombre_corto',
                'sis_moneda.simbolo',
                'adm_contri.razon_social as razon_social_empresa',
                'adm_cta_contri.nro_cuenta'
            )
            ->leftJoin('almacen.doc_com', 'doc_com.id_doc_com', '=', 'registro_pago.id_doc_com')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'doc_com.moneda')
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'registro_pago.registrado_por')
            ->leftJoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'registro_pago.id_empresa')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'registro_pago.id_cuenta_origen')
            ->where([
                ['registro_pago.id_doc_com', '=', $id_doc_com],
                ['registro_pago.estado', '!=', 7]
            ])
            ->get();

        return response()->json($detalles);
    }*/

    public function detalleComprobante($id_doc_com)
    {
        $detalles = DB::table('almacen.doc_com_det')
            ->select(
                'doc_com_det.*',
                'adm_estado_doc.estado_doc',
                'adm_estado_doc.bootstrap_color',
                'alm_prod.descripcion as producto_descripcion',
                'alm_prod.codigo as producto_codigo',
                'alm_und_medida.abreviatura',
                'alm_prod.part_number'
            )
            ->leftJoin('almacen.alm_prod', 'alm_prod.id_producto', '=', 'doc_com_det.id_item')
            ->leftJoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'doc_com_det.id_unid_med')
            ->join('administracion.adm_estado_doc', 'adm_estado_doc.id_estado_doc', '=', 'doc_com_det.estado')
            ->where([
                ['doc_com_det.id_doc', '=', $id_doc_com],
                ['doc_com_det.estado', '!=', 7]
            ])
            ->get();

        return response()->json($detalles);
    }

    function cuentasOrigen($id_empresa)
    {
        $cuentas = DB::table('contabilidad.adm_cta_contri')
            ->select('adm_cta_contri.id_cuenta_contribuyente', 'adm_cta_contri.nro_cuenta')
            // ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_cta_contri.id_contribuyente')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_contribuyente', '=', 'adm_cta_contri.id_contribuyente')
            ->where([['adm_empresa.id_empresa', $id_empresa],['adm_cta_contri.estado','!=',7]])
            ->get();
        return response()->json($cuentas);
    }


    function procesarPago(Request $request)
    {
        try {
            DB::beginTransaction();

            $id_usuario = Auth::user()->id_usuario;

            $registroPago= new RegistroPago();
            $registroPago->id_oc = $request->id_oc;
            $registroPago->id_requerimiento_pago = $request->id_requerimiento_pago;
            $registroPago->id_doc_com = $request->id_doc_com;
            $registroPago->fecha_pago = $request->fecha_pago;
            $registroPago->observacion = $request->observacion;
            $registroPago->total_pago = round($request->total_pago, 2);
            $registroPago->id_empresa = $request->id_empresa;
            $registroPago->id_cuenta_origen = $request->id_cuenta_origen;
            $registroPago->registrado_por = $id_usuario;
            $registroPago->estado = 1;
            $registroPago->fecha_registro = date('Y-m-d H:i:s');
            $registroPago->save();

            if (isset($request->vincularCuotaARegistroDePago) && count($request->vincularCuotaARegistroDePago) > 0) {
                foreach ($request->vincularCuotaARegistroDePago as $key => $value) {
                    $pagoCuotaDetalle = PagoCuotaDetalle::where('id_pago_cuota_detalle', $value)->first();
                    $pagoCuotaDetalle->id_pago = $registroPago->id_pago;
                    $pagoCuotaDetalle->id_estado = 6; // pagado
                    $pagoCuotaDetalle->save();
                }
            }

            //Guardar archivos subidos
            if ($request->hasFile('archivos')) {
                $archivos = $request->file('archivos');

                foreach ($archivos as $archivo) {
                    $id_adjunto = DB::table('tesoreria.registro_pago_adjuntos')
                        ->insertGetId([
                            'id_pago' => $registroPago->id_pago,
                            // 'adjunto' => $nombre,
                            'estado' => 1,
                        ], 'id_adjunto');

                    //obtenemos el nombre del archivo
                    $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
                    $nombre = $id_adjunto . '.' . $request->codigo . '.' . $extension;

                    //indicamos que queremos guardar un nuevo archivo en el disco local
                    File::delete(public_path('tesoreria/pagos/' . $nombre));
                    Storage::disk('archivos')->put('tesoreria/pagos/' . $nombre, File::get($archivo));

                    DB::table('tesoreria.registro_pago_adjuntos')
                        ->where('id_adjunto', $id_adjunto)
                        ->update(['adjunto' => $nombre]);
                }
            }

            // Debugbar::info('total pago: '.$request->total_pago.' total:'.$request->total);
            if (floatval($request->total_pago) >= floatval(round($request->total, 2))) {

                if ($request->id_oc !== null) {
                    $ord = Orden::find($request->id_oc);
                    $ord->estado_pago= 6;
                    $ord->save(); //pagada
                

                    // $detalleArray = PresupuestoInternoHistorialHelper::obtenerDetalleRequerimientoLogisticoDeOrdenParaAfectarPresupuestoInterno($request->id_oc, floatval($request->total_pago));
                    // // Debugbar::info('completo orden');

                    // PresupuestoInternoHistorialHelper::registrarEstadoGastoAfectadoDeRequerimientoLogistico($request->id_oc, $registroPago->id_pago, $detalleArray, 'R', $request->fecha_pago , "Registrar afectación regular");

                    // $comentario='Registro de pago total - orden '.($ord->codigo??'');
                    // LogActividad::registrar(Auth::user(), 'Nuevo registro de pago', 4, $registroPago->getTable(), null, $registroPago, $comentario,'Tesorería');

                } else if ($request->id_requerimiento_pago !== null) {
                    DB::table('tesoreria.requerimiento_pago')
                        ->where('id_requerimiento_pago', $request->id_requerimiento_pago)
                        ->update(['id_estado' => 6]); //pagada

                }
            } else {
                if ($request->id_oc !== null) {
                    if (isset($request->vincularCuotaARegistroDePago) && count($request->vincularCuotaARegistroDePago) > 0) {
                        $nuevoEstado = 10;
                    } else {
                        $nuevoEstado = 9;
                    }
                    $or= Orden::find($request->id_oc);
                    $or->estado_pago = $nuevoEstado;//con saldo
                    $or->save(); 
                    
 
                    // $comentario='Registro de pago parcial - orden '.($or->codigo??'');
                    // LogActividad::registrar(Auth::user(), 'Nuevo registro de pago', 4, $registroPago->getTable(), null, $registroPago, $comentario,'Tesorería');

                } else if ($request->id_requerimiento_pago !== null) {
                    DB::table('tesoreria.requerimiento_pago')
                        ->where('id_requerimiento_pago', $request->id_requerimiento_pago)
                        ->update(['id_estado' => 9]); //con saldo


                }
            }

            //* buscar en tabla de historial finanzas.historial_presupuesto_interno_saldo y actualizar el campo id_pago (si es en cuotas solo se registra una vez el id_pago, ya que se afecta por el total en el primer envio a pago)
            if($request->id_requerimiento_pago >0){
               $historialPresupuestoInternoSaldo= HistorialPresupuestoInternoSaldo::where([['id_requerimiento_pago',$request->id_requerimiento_pago],['tipo','SALIDA'],['estado',3]])->get();
               foreach ($historialPresupuestoInternoSaldo as  $value) {
                    if($value->id_pago == null){
                        $actualiarHistorial = HistorialPresupuestoInternoSaldo::find($value->id);
                        $actualiarHistorial->id_pago = $registroPago->id_pago;
                        $actualiarHistorial->save();
                    }
               }
            }elseif($request->id_oc > 0){
                $historialPresupuestoInternoSaldo= HistorialPresupuestoInternoSaldo::where([['id_orden',$request->id_oc],['tipo','SALIDA'],['estado',3]])->get();
                foreach ($historialPresupuestoInternoSaldo as  $value) {
                    if($value->id_pago == null){
                        $actualiarHistorial = HistorialPresupuestoInternoSaldo::find($value->id);
                        $actualiarHistorial->id_pago = $registroPago->id_pago;
                        $actualiarHistorial->save();
                    }
                }
            }
            // 

            DB::commit();

            // determinar el estado de la estadopago en la orden si todo los pagoDetalle con estad 6 (pagado) es igual al monto e la orden, el estado podria ser "pagado" o "pagado con saldo"
            if (isset($request->vincularCuotaARegistroDePago) && count($request->vincularCuotaARegistroDePago) > 0) {
                if ($request->id_oc !== null) {
                    $ord = Orden::where('id_orden_compra', $request->id_oc)->first();
                    $lastPagoCuota = PagoCuota::where([['id_orden', $request->id_oc]])->first();
                    $lastPagoCuotaDetallePagadas = PagoCuotaDetalle::where([['id_pago_cuota', $lastPagoCuota->id_pago_cuota], ['id_estado', '=', 6]])->get();
                    $sumaPagos = 0;
                    foreach ($lastPagoCuotaDetallePagadas as $key => $detCuota) {
                        $sumaPagos += $detCuota->monto_cuota;
                    }

                    // Debugbar::info($ord->monto_total);
                    // Debugbar::info($sumaPagos);

                    if (floatval($ord->monto_total) > floatval($sumaPagos)) {
                        DB::table('logistica.log_ord_compra')
                            ->where('id_orden_compra', $ord->id_orden_compra)
                            ->update(['estado_pago' => 10]); //pagada con saldo
                    } else {
                        $pagoc = PagoCuota::where('id_orden', $ord->id_orden_compra)->first();
                        $pagoc->id_estado = 6; // pagado
                        $pagoc->save();
                    }
                }
            }


            return response()->json($registroPago->id_pago);
        } catch (\PDOException $e) {
            DB::rollBack();
        }
    }




    function actualizarEstadoPago()
    {

        $nro_actualizados_req = 0;
        $nro_actualizados_oc = 0;

        $requerimientos = DB::table('tesoreria.requerimiento_pago')
            ->select(
                'requerimiento_pago.id_requerimiento_pago',
                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                    WHERE registro_pago.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                    and registro_pago.estado != 7) AS suma_pagado")
            )
            ->where('id_estado', 5)
            ->get();

        foreach ($requerimientos as $req) {

            if ($req->suma_pagado > 0) {
                DB::table('tesoreria.requerimiento_pago')
                    ->where('id_requerimiento_pago', $req->id_requerimiento_pago)
                    ->update(['id_estado' => 9]);
                $nro_actualizados_req++;
            }
        }

        $ordenes = DB::table('logistica.log_ord_compra')
            ->select(
                'log_ord_compra.id_orden_compra',
                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                            WHERE registro_pago.id_oc = log_ord_compra.id_orden_compra
                            and registro_pago.estado != 7) AS suma_pagado")
            )
            ->where('estado_pago', 5)
            ->get();

        foreach ($ordenes as $req) {

            if ($req->suma_pagado > 0) {
                DB::table('logistica.log_ord_compra')
                    ->where('id_orden_compra', $req->id_orden_compra)
                    ->update(['estado_pago' => 9]);
                $nro_actualizados_oc++;
            }
        }
        return response()->json("Se actualizaron " . $nro_actualizados_req . " requerimientos y " . $nro_actualizados_oc . " ordenes.");
    }

    function enviarAPago(Request $request)
    {
        try {
            DB::beginTransaction();
            $msj = '';
            $tipo = '';
            $id_usuario = Auth::user()->id_usuario;

            if ($request->tipo == "requerimiento pago") {
                $req = DB::table('tesoreria.requerimiento_pago')
                    ->where('id_requerimiento_pago', $request->id)->first();
                //ya fue pagado?
                if ($req->id_estado !== 6) {
                    //fue anulado?
                    if ($req->id_estado !== 7) {

                        $requerimientoPago = RequerimientoPago::find($request->id);
                        $requerimientoPago->id_estado= 5; //enviado a pago
                        $requerimientoPago->fecha_autorizacion= new Carbon();
                        $requerimientoPago->usuario_autorizacion= $id_usuario;
                        $requerimientoPago->save();

                        $msj = 'Se autorizó el pago del requerimiento exitosamente';
                        $tipo = 'success';

                        // * al enviar a pago se aplicar la afectación de presupuesto de requerimiento de pago
                        // if ($requerimientoPago->id_presupuesto_interno > 0) {
                        //     $detalleArray = PresupuestoInternoHistorialHelper::obtenerDetalleRequerimientoPagoParaPresupuestoInterno($requerimientoPago->id_requerimiento_pago, floatval($requerimientoPago->monto_total), null);

                        //     PresupuestoInternoHistorialHelper::registrarEstadoGastoAfectadoDeRequerimientoPago($requerimientoPago->id_requerimiento_pago, null, $detalleArray, 'R', $requerimientoPago->fecha_autorizacion, 'Registrar afectación regular');

                        //     $comentario='Autorización de pago - requerimiento de pago '.($requerimientoPago->codigo??'');
                        //     LogActividad::registrar(Auth::user(), 'Nueva autorización de pago', 4, $requerimientoPago->getTable(), null, $requerimientoPago, $comentario,'Contabilidad');
                        // }

                    } else {
                        $msj = 'El requerimiento fue anulado';
                        $tipo = 'warning';
                    }
                } else {
                    $msj = 'El requerimiento ya fue pagado';
                    $tipo = 'warning';
                }
            } else if ($request->tipo == "orden") {
                $oc = DB::table('logistica.log_ord_compra')
                    ->where('id_orden_compra', $request->id)->first();
                $tieneUnPagoEfectuado=false;
                //fue pagada?
                if ($oc->estado_pago !== 6) {
                    //fue anulado?
                    if ($oc->estado !== 7) {
                        $orden = Orden::find($request->id);
                        $orden->estado_pago=5; //enviado a pago
                        $orden->fecha_autorizacion=new Carbon();
                        $orden->usuario_autorizacion=$id_usuario;
                        $orden->save();

                        $msj = 'Se autorizó el pago de la orden exitosamente';

                        $montoAfectar=floatval($orden->monto_total);

                        // evaluar si tiene pago en cuotas
                        if($orden->tiene_pago_en_cuotas ==true){ // la orden tiene pago en cuotas
                            $pagoCuota = PagoCuota::where('id_orden',$orden->id_orden_compra)->first();
                            $pagoCuotaDetalle =PagoCuotaDetalle::where('id_pago_cuota',$pagoCuota->id_pago_cuota)->get();
                            foreach ($pagoCuotaDetalle as $value) {
                                if($value->id_estado ==6 && $request->idPagoCuotaDetalle==$value->id_pago_cuota_detalle){
                                    $tieneUnPagoEfectuado=true;
                                }else{
                                    $tieneUnPagoEfectuado=false;

                                }
                            }
                        }
                         
                        if($tieneUnPagoEfectuado ==false){
                            // no tiene ningun pago, debe pasar por registrar el gasto afectando al ppto interno

                            // * al enviar a pago se aplicar la afectación de presupuesto de requerimiento de logistico (Orden)
                            // $detalleArray = PresupuestoInternoHistorialHelper::obtenerDetalleRequerimientoLogisticoDeOrdenParaAfectarPresupuestoInterno($orden->id_orden_compra, $montoAfectar);
                            // PresupuestoInternoHistorialHelper::registrarEstadoGastoAfectadoDeRequerimientoLogistico($orden->id_orden_compra, null, $detalleArray, 'R',  $orden->fecha_autorizacion,  'Registrar afectación regular');
                            $comentario='Autorizacion de pago - orden '.($orden->codigo??'');
                            LogActividad::registrar(Auth::user(), 'Nueva autorizacion de pago', 4, $orden->getTable(), null, $orden, $comentario,'Tesorería');    

                            if (isset($request->idPagoCuotaDetalle) && ($request->idPagoCuotaDetalle > 0)) {
                                $pagoCuotaDetalle = PagoCuotaDetalle::where('id_pago_cuota_detalle', $request->idPagoCuotaDetalle)->first();
                                $pagoCuotaDetalle->fecha_autorizacion = new Carbon();
                                $pagoCuotaDetalle->id_estado = 5;
                                $pagoCuotaDetalle->save();

                                $msj = 'Se autorizó el pago de la cuota exitosamente';
                            }
                        }
                        $tipo = 'success';
                    } else {
                        $msj = 'La orden fue anulada';
                        $tipo = 'warning';
                    }
                } else {
                    $msj = 'La orden ya fue pagada';
                    $tipo = 'warning';
                }
            }
            // else if ($tipo !== "comprobante") {
            //     DB::table('almacen.doc_com')
            //         ->where('id_doc_com', $id)
            //         ->update(['estado' => 5]); //
            // }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $msj]);
        } catch (\PDOException $e) {
            DB::rollBack();
        }
    }

    function revertirEnvio(Request $request)
    {
        try {
            DB::beginTransaction();
            $msj = '';
            $tipo = '';

            $mesRetorno= str_pad((Carbon::now('America/Lima')->month), 2, "0", STR_PAD_LEFT);


            if ($request->tipo == "requerimiento pago") {
                $req = DB::table('tesoreria.requerimiento_pago')
                    ->where('id_requerimiento_pago', $request->id)->first();

                if ($req->id_estado !== 6) {
                    if ($req->id_estado !== 7) {
                        $requerimientoPago = RequerimientoPago::find($request->id);
                        $requerimientoPago->id_estado=2;//aprobado
                        $requerimientoPago->save();
                
                        $msj = 'La autorización de pago fue revertida exitosamente';
                        $tipo = 'success';

                        //* Retorno de presupuesto - requerimiento de logistico(orden)
                        // $cantidadDeRetornoDePresupuesto= PresupuestoInternoHistorialHelper::registrarRetornoDePresupuestoPorRequerimientoPago($requerimientoPago->id_requerimiento_pago,$mesRetorno);
                        // if($cantidadDeRetornoDePresupuesto >0){
                        //     $msj.='. Se retorno de presupuesto';
                        // }

                    } else {
                        $msj = 'El requerimiento fue anulado';
                        $tipo = 'warning';
                    }
                } else {
                    $msj = 'El requerimiento ya fue pagado';
                    $tipo = 'warning';
                }
            } else if ($request->tipo == "orden") {
                $oc = DB::table('logistica.log_ord_compra')
                    ->where('id_orden_compra', $request->id)->first();

                if ($oc->estado_pago !== 6) {
                    if ($oc->estado !== 7) {
                        $orden = Orden::find($request->id);
                        if ($orden->tiene_pago_en_cuotas == true) {
                            $pagoCuota = PagoCuota::where('id_orden', $request->id)->first();
                            if (isset($pagoCuota->id_pago_cuota)) {
                                $pagoCuotaDetalle = PagoCuotaDetalle::where([['id_pago_cuota', $pagoCuota->id_pago_cuota]])->whereIn('id_estado', [1,5])->get();
                                foreach ($pagoCuotaDetalle as $keyPcd => $pcd) {
                                    $updatePagoCuotaDetalle = PagoCuotaDetalle::find($pcd->id_pago_cuota_detalle);
                                    $updatePagoCuotaDetalle->id_estado = 7;
                                    $updatePagoCuotaDetalle->fecha_autorizacion = null;
                                    $updatePagoCuotaDetalle->save();
                                }
                            }
                        }
         
                            $orden = Orden::find($request->id);
                            $orden->estado_pago=1; //elaborado
                            $orden->fecha_autorizacion=new Carbon();
                            $orden->save();

                        $msj = 'La autorización y solicitud fueron revertidas';
                        $tipo = 'success';

                        //* Retorno de presupuesto - requerimiento de logistico(orden)
                        // $cantidadDeRetornoDePresupuesto= PresupuestoInternoHistorialHelper::registrarRetornoDePresupuestoPorOrden($orden->id_orden_compra, $mesRetorno);
                        // if($cantidadDeRetornoDePresupuesto >0){
                        //     $msj.='. Se retorno de presupuesto';
                        // }

                    } else {
                        $msj = 'La orden fue anulada';
                        $tipo = 'warning';
                    }
                } else {
                    $msj = 'La orden ya fue pagada';
                    $tipo = 'warning';
                }
            }
            // else if ($tipo !== "comprobante") {
            //     DB::table('almacen.doc_com')
            //         ->where('id_doc_com', $id)
            //         ->update(['estado' => 5]); //
            // }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $msj]);
        } catch (\PDOException $e) {
            DB::rollBack();
        }
    }

    function anularPago($id_pago)
    {
        try {
            DB::beginTransaction();

            $mensaje="";
            $pago = DB::table('tesoreria.registro_pago')
                ->select(
                    'registro_pago.id_requerimiento_pago',
                    'registro_pago.id_oc',
                    'registro_pago.id_doc_com',
                    'registro_pago.total_pago'
                )
                ->where('registro_pago.id_pago', $id_pago)
                ->first();

            if ($pago->id_requerimiento_pago !== null) {
                DB::table('tesoreria.requerimiento_pago')
                    ->where('id_requerimiento_pago', $pago->id_requerimiento_pago)
                    ->update(['id_estado' => 5]); //enviado a pago

                $requerimientoPago = RequerimientoPago::find($pago->id_requerimiento_pago);
                $detalleArray = [];

                if ($requerimientoPago->id_presupuesto_interno > 0) {
                    $todoDetalleRequerimientoPago = RequerimientoPagoDetalle::where([["id_requerimiento_pago", $pago->id_requerimiento_pago], ['id_estado', '!=', 7]])->get();
                    if (count($todoDetalleRequerimientoPago) == 1) {
                        foreach ($todoDetalleRequerimientoPago as $detalleRequerimientoPago) {
                            $detalle = RequerimientoPagoDetalle::where("id_requerimiento_pago_detalle", $detalleRequerimientoPago->id_requerimiento_pago_detalle)->first();
                            $detalle->importe_item_para_presupuesto = $pago->total_pago;
                            $detalleArray[] = $detalle;
                        }
                    } elseif (count($todoDetalleRequerimientoPago) > 1) {
                        $prorrateo = $pago->total_pago / count($todoDetalleRequerimientoPago);

                        foreach ($todoDetalleRequerimientoPago as $detalleRequerimientoPago) {
                            $detalle = RequerimientoPagoDetalle::where("id_requerimiento_pago_detalle", $detalleRequerimientoPago->id_requerimiento_pago_detalle)->first();
                            $detalle->importe_item_para_presupuesto = $prorrateo;
                            $detalleArray[] = $detalle;
                        }
                    }

                }
            } else if ($pago->id_oc !== null) {
                DB::table('logistica.log_ord_compra')
                    ->where('id_orden_compra', $pago->id_oc)
                    ->update(['estado_pago' => 5]); //enviado a pago
            } //falta agregar comprobante

            $registroPago = RegistroPago::find($id_pago);
            $registroPago->estado=7;
            $registroPago->save();

            $pagoCuotaDetalle = PagoCuotaDetalle::where('id_pago',$id_pago)->get();
            foreach ($pagoCuotaDetalle as $value) {
                $pagoCuotaDetalleUpdate= PagoCuotaDetalle::find($value->id_pago_cuota_detalle);
                $pagoCuotaDetalleUpdate->estado=7;
                $pagoCuotaDetalleUpdate->save();
            }
           

            if($registroPago){
                $mensaje = 'Se anulo correctamente';
            }else{
                $mensaje = 'No se pudo anular, Hubo un problama';

            }

            DB::commit();
            return response()->json($mensaje);
        } catch (\PDOException $e) {
            DB::rollBack();
        }
    }

    function verAdjuntos($id_requerimiento_pago)
    {
        $usuarioPropietarioDeRequerimiento = RequerimientoPago::find($id_requerimiento_pago)->id_usuario;
        $adjuntoPadre = RequerimientoPagoAdjunto::where([['id_requerimiento_pago', $id_requerimiento_pago], ['id_estado', '!=', 7]])->with('categoriaAdjunto')->get();
        $adjuntoDetalle = RequerimientoPagoAdjuntoDetalle::join('tesoreria.requerimiento_pago_detalle', 'requerimiento_pago_detalle.id_requerimiento_pago_detalle', '=', 'requerimiento_pago_detalle_adjunto.id_requerimiento_pago_detalle')
            ->where([['requerimiento_pago_detalle.id_requerimiento_pago', $id_requerimiento_pago], ['requerimiento_pago_detalle_adjunto.id_estado', '!=', 7]])->get();

        $adjuntos_pagos = RegistroPago::select('registro_pago_adjuntos.adjunto', 'registro_pago_adjuntos.id_adjunto')
            ->where('id_requerimiento_pago', $id_requerimiento_pago)
            ->join('tesoreria.registro_pago_adjuntos', 'registro_pago_adjuntos.id_pago', '=', 'registro_pago.id_pago')
            ->get();
        $adjuntos_pagos_complementarios = OtrosAdjuntosTesoreria::where('id_requerimiento_pago', $id_requerimiento_pago)
            ->where('id_estado', '!=', 7)
            ->get();
        return response()->json(['adjuntoPadre' => $adjuntoPadre, 'adjuntoDetalle' => $adjuntoDetalle, 'adjuntos_pago' => $adjuntos_pagos, 'adjuntos_pagos_complementarios' => $adjuntos_pagos_complementarios]);
    }

    function verAdjuntosRegistroPagoOrden($id_orden)
    {

        $registro_pago = RegistroPago::where([['id_oc', $id_orden], ['estado', '!=', 7]])->first();
        $adjuntos_pagos = [];
        if ($registro_pago != null && $registro_pago->id_pago > 0) {
            $adjuntos_pagos = RegistroPagoAdjuntos::where([['id_pago', $registro_pago->id_pago], ['estado', '!=', 7]])->get();
        }

        $adjuntos_pagos_complementarios = OtrosAdjuntosTesoreria::where('id_orden', $id_orden)
            ->where('id_estado', '!=', 7)
            ->get();

        return response()->json(['adjuntos_pago' => $adjuntos_pagos, 'adjuntos_pagos_complementarios' => $adjuntos_pagos_complementarios]);
    }
    function verAdjuntosRequerimientoDeOrden($id_orden)
    {

        $orden = Orden::with(['detalle' => function ($q) {
            $q->where('log_det_ord_compra.estado', '!=', 7);
        }])->find($id_orden);

        $idRequerimientoList = [];
        $idDetalleRequerimientoList = [];
        $adjuntoPadre = [];
        $adjuntoDetalle = [];
        if ($orden) {
            if (isset($orden->requerimientos)) {
                foreach (($orden->requerimientos) as $key => $value) {
                    $idRequerimientoList[] = $value->id_requerimiento;
                }
            }
            if (isset($orden->detalle)) {
                foreach (($orden->detalle) as $key => $value) {
                    $idDetalleRequerimientoList[] = $value->id_detalle_requerimiento;
                }
            }
        }
        if (count($idRequerimientoList) > 0) {
            $adjuntoPadre = AdjuntoRequerimiento::with('categoriaAdjunto')->whereIn('id_requerimiento', $idRequerimientoList)->where('alm_req_adjuntos.estado', '!=', 7)->get();
        }
        if (count($idDetalleRequerimientoList) > 0) {
            $adjuntoDetalle = AdjuntoDetalleRequerimiento::whereIn('id_detalle_requerimiento', $idDetalleRequerimientoList)->where('alm_det_req_adjuntos.estado', '!=', 7)->get();
        }

        return response()->json(['adjuntoPadre' => $adjuntoPadre, 'adjuntoDetalle' => $adjuntoDetalle]);
    }

    function listarAdjuntosPago($id_requerimiento_pago)
    {
        $adjuntos = DB::table('tesoreria.registro_pago_adjuntos')
            ->select('registro_pago_adjuntos.*', 'registro_pago.fecha_pago', 'registro_pago.observacion')
            ->join('tesoreria.registro_pago', 'registro_pago.id_pago', '=', 'registro_pago_adjuntos.id_pago')
            ->where('registro_pago.id_requerimiento_pago', $id_requerimiento_pago)
            ->get();

        return response()->json($adjuntos);
    }
    public function registroPagosExportarExcel()
    {
        return Excel::download(new RegistroPagosExport, 'listado_ventas_Externas_exportar_excel.xlsx');
    }
    public function obtenerRegistroPagos()
    {
        return DB::table('tesoreria.requerimiento_pago')
            ->select(
                'requerimiento_pago.*',
                'adm_prioridad.descripcion as prioridad',
                'adm_contri.nro_documento',
                'adm_contri.razon_social',
                'empresa.razon_social as razon_social_empresa',
                'adm_empresa.codigo as codigo_empresa',
                'sis_moneda.simbolo',
                'sis_grupo.descripcion as grupo_descripcion',
                'requerimiento_pago_estado.descripcion as estado_doc',
                'requerimiento_pago_estado.bootstrap_color',
                'sis_sede.descripcion as sede_descripcion',
                'adm_cta_contri.nro_cuenta',
                'adm_cta_contri.nro_cuenta_interbancaria',
                'adm_tp_cta.descripcion as tipo_cuenta',
                'banco_contribuyente.razon_social as banco_contribuyente',
                'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
                'rrhh_cta_banc.nro_cci as nro_cci_persona',
                'tp_cta_persona.descripcion as tipo_cuenta_persona',
                'banco_persona.razon_social as banco_persona',
                'sis_usua.nombre_corto',
                'autorizado.nombre_corto as nombre_autorizado',
                'rrhh_perso.nro_documento as dni_persona',
                DB::raw("concat(rrhh_perso.nombres, ' ' ,rrhh_perso.apellido_paterno, ' ' ,rrhh_perso.apellido_materno) AS persona"),
                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_adjunto
                    WHERE requerimiento_pago_adjunto.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                    and requerimiento_pago_adjunto.id_estado != 7) AS count_adjunto_cabecera"),

                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_detalle_adjunto
                    INNER JOIN tesoreria.requerimiento_pago_detalle as detalle on(
                        detalle.id_requerimiento_pago_detalle = requerimiento_pago_detalle_adjunto.id_requerimiento_pago_detalle
                    )
                    WHERE detalle.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                    and requerimiento_pago_detalle_adjunto.id_estado != 7) AS count_adjunto_detalle"),

                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                    WHERE registro_pago.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                    and registro_pago.estado != 7) AS suma_pagado")
            )
            // ->join('logistica.log_prove', 'log_prove.id_proveedor', '=', 'requerimiento_pago.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'requerimiento_pago.id_contribuyente')
            ->leftjoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'requerimiento_pago.id_persona')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
            ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'requerimiento_pago.id_estado')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'requerimiento_pago.id_prioridad')
            ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'requerimiento_pago.id_sede')
            ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'requerimiento_pago.id_empresa')
            ->join('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'requerimiento_pago.id_cuenta_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'requerimiento_pago.id_cuenta_persona')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->join('configuracion.sis_grupo', 'sis_grupo.id_grupo', '=', 'requerimiento_pago.id_grupo')
            ->join('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'requerimiento_pago.id_usuario')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'requerimiento_pago.usuario_autorizacion')
            ->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9]);
    }
    public function ordenesCompraServicioExportarExcel()
    {
        return Excel::download(new OrdenesCompraServicioExport, 'ordenes_compras_servicio_exportar_excel.xlsx');
    }
    public function obtenerOrdenesCompraServicio()
    {
        return Orden::select(
            'log_ord_compra.*',
            'adm_contri.nro_documento',
            'adm_contri.razon_social',
            'adm_empresa.id_empresa',
            'empresa.razon_social as razon_social_empresa',
            'adm_empresa.codigo as codigo_empresa',
            'requerimiento_pago_estado.descripcion as estado_doc',
            'requerimiento_pago_estado.bootstrap_color',
            'sis_moneda.simbolo',
            'log_cdn_pago.descripcion AS condicion_pago',
            'sis_sede.descripcion as sede_descripcion',
            'adm_cta_contri.nro_cuenta',
            'adm_cta_contri.nro_cuenta_interbancaria',
            'adm_tp_cta.descripcion as tipo_cuenta',
            'banco_contribuyente.razon_social as banco_contribuyente',
            'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
            'rrhh_cta_banc.nro_cci as nro_cci_persona',
            'tp_cta_persona.descripcion as tipo_cuenta_persona',
            'banco_persona.razon_social as banco_persona',
            DB::raw("CONCAT(rrhh_perso.nombres,' ',rrhh_perso.apellido_paterno,' ',rrhh_perso.apellido_materno) as nombre_completo_persona"),
            'rrhh_perso.nro_documento as nro_documento_persona',
            'adm_prioridad.descripcion as prioridad',
            'autorizado.nombre_corto as nombre_autorizado',
            DB::raw("(SELECT sum(subtotal) FROM logistica.log_det_ord_compra
                        WHERE log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra
                        and log_det_ord_compra.estado != 7) AS suma_total"),
            DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                        WHERE registro_pago.id_oc = log_ord_compra.id_orden_compra
                        and registro_pago.estado != 7) AS suma_pagado")
        )
            ->leftjoin('logistica.log_prove', 'log_prove.id_proveedor', '=', 'log_ord_compra.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->leftjoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'log_ord_compra.estado_pago')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'log_ord_compra.id_moneda')
            ->leftJoin('logistica.log_cdn_pago', 'log_cdn_pago.id_condicion_pago', '=', 'log_ord_compra.id_condicion')
            ->leftjoin('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')
            ->leftjoin('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'log_ord_compra.id_cta_principal')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'log_ord_compra.id_persona_pago')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'log_ord_compra.id_cuenta_persona_pago')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'log_ord_compra.usuario_autorizacion')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'log_ord_compra.id_prioridad_pago')
            ->whereIn('log_ord_compra.estado_pago', [8, 5, 6, 9]);
    }
    public function obtenerRegistroPagosDetalle($id)
    {
        $detalles = DB::table('tesoreria.registro_pago')
            ->select(
                'registro_pago.*',
                'sis_usua.nombre_corto',
                'sis_moneda.simbolo',
                'adm_contri.razon_social as razon_social_empresa',
                'adm_cta_contri.nro_cuenta',
                DB::raw("(SELECT count(adjunto) FROM tesoreria.registro_pago_adjuntos
                      WHERE registro_pago_adjuntos.id_pago = registro_pago.id_pago
                        and registro_pago_adjuntos.estado != 7) AS count_adjuntos")
            )
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'registro_pago.registrado_por')
            ->leftJoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'registro_pago.id_empresa')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'registro_pago.id_cuenta_origen');

        $query = $detalles->join('tesoreria.requerimiento_pago', 'requerimiento_pago.id_requerimiento_pago', '=', 'registro_pago.id_requerimiento_pago')
            ->join('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
            ->where([['registro_pago.id_requerimiento_pago', '=', $id], ['registro_pago.estado', '!=', 7]])
            ->get();

        return $query;
    }
    public function obtenerOrdenesCompraServicioDetalle($id_orden_compra)
    {
        $detalles = DB::table('tesoreria.registro_pago')
            ->select(
                'registro_pago.*',
                'sis_usua.nombre_corto',
                'sis_moneda.simbolo',
                'adm_contri.razon_social as razon_social_empresa',
                'adm_cta_contri.nro_cuenta',
                'log_ord_compra.id_orden_compra',
                DB::raw("(SELECT count(adjunto) FROM tesoreria.registro_pago_adjuntos
                    WHERE registro_pago_adjuntos.id_pago = registro_pago.id_pago
                        and registro_pago_adjuntos.estado != 7) AS count_adjuntos")
            )
            ->leftJoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'registro_pago.registrado_por')
            ->leftJoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'registro_pago.id_empresa')
            ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'registro_pago.id_cuenta_origen');

        $query = $detalles->leftJoin('logistica.log_ord_compra', 'log_ord_compra.id_orden_compra', '=', 'registro_pago.id_oc')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'log_ord_compra.id_moneda')
            ->where([['registro_pago.id_oc', '=', $id_orden_compra], ['registro_pago.estado', '!=', 7]])
            ->get();
        return $query;
    }
    public function guardarAdjuntosTesoreria(Request $request)
    {
        $mensaje = '';
        $status = 'warning';
        if (count($request->adjuntos) > 0) {
            foreach ($request->adjuntos as $key => $archivo) {

                $fechaHoy = new Carbon();
                $sufijo = $fechaHoy->format('YmdHis');
                $file = $archivo->getClientOriginalName();
                // $codigo = $codigoRequerimiento;
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                // $newNameFile = $codigo . '_' . $key . $idCategoria . $sufijo . '.' . $extension;
                $newNameFile = $request->codigo_requerimiento . $key  . $sufijo . '.' . $extension;
                // Storage::disk('archivos')->put("tesoreria/pagos/" . $newNameFile, File::get($archivo));
                Storage::disk('archivos')->put("tesoreria/otros_adjuntos/" . $newNameFile, File::get($archivo));

                // $adjunto = new RequerimientoPagoAdjunto();
                // $adjunto->id_requerimiento_pago = $request->id_requerimiento_pago;
                // $adjunto->archivo  = $newNameFile;
                // $adjunto->id_estado  = 1;
                // $adjunto->fecha_registro  = $fechaHoy;
                // $adjunto->id_categoria_adjunto = 5;
                // // $adjunto->id_usuario = Auth::user()->id_usuario;
                // $adjunto->save();

                $adjunto = new OtrosAdjuntosTesoreria();
                if ($request->id_requerimiento_pago > 0 && ($request->id_orden == null || $request->id_orden == '')) {
                    $adjunto->id_requerimiento_pago = $request->id_requerimiento_pago;
                } else if ($request->id_orden > 0 && ($request->id_requerimiento_pago == null || $request->id_requerimiento_pago == '')) {

                    $adjunto->id_orden = $request->id_orden;
                }
                $adjunto->archivo  = $newNameFile;
                $adjunto->id_estado  = 1;
                $adjunto->fecha_registro  = $fechaHoy;
                // $adjunto->id_categoria_adjunto = 5;
                $adjunto->id_tp_doc = 1;
                $adjunto->id_usuario = Auth::user()->id_usuario;
                $adjunto->save();
                $mensaje = 'Se guardo el adjunto';
                $status = 'success';
            }
        } else {
            $mensaje = 'Hubo un problema al intentar guardo el adjunto.';
            $status = 'error';
        }


        return response()->json([
            "mensaje" => $mensaje,
            "status" => $status,
            "data" => $request
        ]);
    }
    public function exportarRequerimientosPagos(Request $request)
    {
        // return $request->$request;exit;
        $data = DB::table('tesoreria.requerimiento_pago')
            ->select(
                'requerimiento_pago.*',
                'adm_prioridad.descripcion as prioridad',
                'adm_contri.nro_documento',
                'adm_contri.razon_social',
                'empresa.razon_social as razon_social_empresa',
                'adm_empresa.codigo as codigo_empresa',
                'sis_moneda.simbolo',
                'sis_grupo.descripcion as grupo_descripcion',
                'requerimiento_pago_estado.descripcion as estado_doc',
                'requerimiento_pago_estado.bootstrap_color',
                'sis_sede.descripcion as sede_descripcion',
                'adm_cta_contri.nro_cuenta',
                'adm_cta_contri.nro_cuenta_interbancaria',
                'adm_cta_contri.fecha_registro as fecha_registro_cuenta_contribuyente',
                'adm_tp_cta.descripcion as tipo_cuenta',
                'banco_contribuyente.razon_social as banco_contribuyente',
                'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
                'rrhh_cta_banc.nro_cci as nro_cci_persona',
                'rrhh_cta_banc.fecha_registro as fecha_registro_cuenta_persona',
                'tp_cta_persona.descripcion as tipo_cuenta_persona',
                'banco_persona.razon_social as banco_persona',
                'sis_usua.nombre_corto',
                'autorizado.nombre_corto as nombre_autorizado',
                'rrhh_perso.nro_documento as dni_persona',
                DB::raw("concat(rrhh_perso.nombres, ' ' ,rrhh_perso.apellido_paterno, ' ' ,rrhh_perso.apellido_materno) AS persona"),
                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_adjunto
                        WHERE requerimiento_pago_adjunto.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and requerimiento_pago_adjunto.id_estado != 7) AS count_adjunto_cabecera"),

                DB::raw("(SELECT count(archivo) FROM tesoreria.requerimiento_pago_detalle_adjunto
                        INNER JOIN tesoreria.requerimiento_pago_detalle as detalle on(
                            detalle.id_requerimiento_pago_detalle = requerimiento_pago_detalle_adjunto.id_requerimiento_pago_detalle
                        )
                        WHERE detalle.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and requerimiento_pago_detalle_adjunto.id_estado != 7) AS count_adjunto_detalle"),

                DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                        WHERE registro_pago.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago
                        and registro_pago.estado != 7) AS suma_pagado")
            )
            // ->join('logistica.log_prove', 'log_prove.id_proveedor', '=', 'requerimiento_pago.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'requerimiento_pago.id_contribuyente')
            ->leftjoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'requerimiento_pago.id_persona')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'requerimiento_pago.id_moneda')
            ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'requerimiento_pago.id_estado')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'requerimiento_pago.id_prioridad')
            ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'requerimiento_pago.id_sede')
            ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'requerimiento_pago.id_empresa')
            ->join('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'requerimiento_pago.id_cuenta_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'requerimiento_pago.id_cuenta_persona')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->join('configuracion.sis_grupo', 'sis_grupo.id_grupo', '=', 'requerimiento_pago.id_grupo')
            ->join('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'requerimiento_pago.id_usuario')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'requerimiento_pago.usuario_autorizacion')
            ->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9]);
        // ->where([['requerimiento_pago.id_estado', '!=', 7], ['requerimiento_pago.id_estado', '!=', 1]]);
        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '<=', $request->fecha_final);
        }

        $data = $data->orderBy('id_requerimiento_pago', 'DESC')->get();
        // return $data;exit;
        $json_excel = array();
        foreach ($data as $key => $value) {
            $pagado = floatval($value->suma_pagado !== null ? $value->suma_pagado : 0);
            $total = floatval($value->monto_total);
            $por_pagar = ($total - $pagado);

            array_push($json_excel,array(
                "prioridad"=>$value->prioridad,
                "codigo_empresa"=>$value->codigo_empresa,
                "codigo"=>$value->codigo,
                "concepto"=>$value->concepto,
                "nombre_corto"=>$value->nombre_corto,
                // "persona"=>$value->persona,
                "fecha_registro"=>$value->fecha_registro,
                "simbolo"=>$value->simbolo,
                "monto_total"=>$value->monto_total,
                "saldo"=>$por_pagar,
                "estado_doc"=>$value->estado_doc,
                "nombre_autorizado"=>($value->nombre_autorizado !==''?$value->nombre_autorizado.' el '.$value->fecha_autorizacion:''),

                "id_tipo_destinatario"=>(!empty($value->id_tipo_destinatario)?$value->id_tipo_destinatario:'-'),
                //contribuyente
                "nro_documento"=>(!empty($value->nro_documento)?$value->nro_documento:'-'),
                "razon_social"=>(!empty($value->razon_social)?$value->razon_social:'-'),
                "tipo_cuenta"=>(!empty($value->tipo_cuenta)?$value->tipo_cuenta:'-'),
                "banco_contribuyente"=>(!empty($value->banco_contribuyente)?$value->banco_contribuyente:'-'),
                "nro_cuenta"=>(!empty($value->nro_cuenta)?$value->nro_cuenta:'-'),
                "nro_cuenta_interbancaria"=>(!empty($value->nro_cuenta_interbancaria)?$value->nro_cuenta_interbancaria:'-'),
                "fecha_registro_cuenta_contribuyente"=>(!empty($value->fecha_registro_cuenta_contribuyente)?$value->fecha_registro_cuenta_contribuyente:'-'),

                //persona
                "nro_documento_persona"=>(!empty($value->nro_documento_persona)?$value->nro_documento_persona:'-'),
                "nombre_completo_persona"=>(!empty($value->persona)?$value->persona:'-'),
                "tipo_cuenta_persona"=>(!empty($value->tipo_cuenta_persona)?$value->tipo_cuenta_persona:'-'),
                "banco_persona"=>(!empty($value->banco_persona)?$value->banco_persona:'-'),
                "nro_cuenta_persona"=>(!empty($value->nro_cuenta_persona)?$value->nro_cuenta_persona:'-'),
                "nro_cci_persona"=>(!empty($value->nro_cci_persona)?$value->nro_cci_persona:'-'),
                "fecha_registro_cuenta_persona"=>(!empty($value->fecha_registro_cuenta_persona)?$value->fecha_registro_cuenta_persona:'-'),
                
            ));
        }
        // $json_excel = json_encode($json_excel);
        return Excel::download(new RequerimientoPagosExport(json_encode($json_excel)), 'requerimiento_de_pago_pagados.xlsx');
        // return response()->json($json_excel,200);
    }

    public function exportarRequerimientosPagosItems(Request $request)
    {
        $data = DB::table('tesoreria.requerimiento_pago_detalle')
        ->select(
            'requerimiento_pago_detalle.id_requerimiento_pago_detalle',
            'requerimiento_pago_detalle.descripcion',
            'requerimiento_pago_detalle.motivo',
            'requerimiento_pago_detalle.cantidad',
            'requerimiento_pago_detalle.precio_unitario',
            'requerimiento_pago_detalle.subtotal',
            'requerimiento_pago_detalle.fecha_registro',
            'adm_prioridad.descripcion as prioridad',
            'requerimiento_pago_tipo.descripcion AS tipo_requerimiento',
            'requerimiento_pago.codigo',
            'oportunidades.codigo_oportunidad',
            'requerimiento_pago.concepto',
            'requerimiento_pago.comentario',
            'sis_moneda.simbolo as simbolo_moneda',
            'sis_sede.codigo as sede',
            'sis_sede.descripcion as descripcion_empresa_sede',
            'adm_contri.razon_social as empresa_razon_social',
            'sis_identi.descripcion as empresa_tipo_documento',
            'proy_proyecto.descripcion AS descripcion_proyecto',
            'sis_grupo.descripcion as grupo',
            'division.descripcion as division',
            'requerimiento_pago.monto_total',
            'presup_par.codigo as partida',
            'presup_par.descripcion as descripcion_partida',
            'presup_par.id_partida',
            'padre_centro_costo.codigo as padre_centro_costo',
            'padre_centro_costo.descripcion as padre_descripcion_centro_costo',
            'centro_costo.codigo as centro_costo',
            'centro_costo.descripcion as descripcion_centro_costo',
            'centro_costo.id_centro_costo',
            'requerimiento_pago_estado.descripcion as estado_requerimiento',
            'presup.codigo as codigo_presupuesto_old',
            'presup.descripcion as descripcion_presupuesto_old',
            'presupuesto_interno.codigo as codigo_presupuesto_interno',
            'presupuesto_interno.descripcion as descripcion_presupuesto_interno',
            
            DB::raw("(SELECT 
            (CAST (replace(presupuesto_interno_detalle.enero, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.febrero, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.marzo, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.abril, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.mayo, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.junio, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.julio, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.agosto, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.setiembre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.octubre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.noviembre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.diciembre, ',', '') AS NUMERIC(10,2)))
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno = requerimiento_pago.id_presupuesto_interno and requerimiento_pago_detalle.id_partida_pi=presupuesto_interno_detalle.id_presupuesto_interno_detalle limit 1) AS presupuesto_interno_total_partida"),
            
            DB::raw("( SELECT
                CASE WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =1 THEN presupuesto_interno_detalle.enero
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =2 THEN presupuesto_interno_detalle.febrero
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =3 THEN presupuesto_interno_detalle.marzo
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =4 THEN presupuesto_interno_detalle.abril
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =5 THEN presupuesto_interno_detalle.mayo
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =6 THEN presupuesto_interno_detalle.junio
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =7 THEN presupuesto_interno_detalle.julio
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =8 THEN presupuesto_interno_detalle.agosto
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =9 THEN presupuesto_interno_detalle.setiembre
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =10 THEN presupuesto_interno_detalle.octubre
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =11 THEN presupuesto_interno_detalle.noviembre
                    WHEN (SELECT date_part('month', requerimiento_pago.fecha_registro)) =12 THEN presupuesto_interno_detalle.diciembre
                    ELSE ''
                    END
                FROM finanzas.presupuesto_interno_detalle
                WHERE presupuesto_interno_detalle.id_presupuesto_interno = requerimiento_pago.id_presupuesto_interno 
                and requerimiento_pago_detalle.id_partida_pi=presupuesto_interno_detalle.id_presupuesto_interno_detalle 
                limit 1 ) AS presupuesto_interno_mes_partida "),
            
            DB::raw("(SELECT presup_titu.descripcion
            FROM finanzas.presup_titu
            WHERE presup_titu.codigo = presup_par.cod_padre and presup_titu.id_presup=presup_par.id_presup limit 1) AS descripcion_partida_padre"),
            DB::raw("(SELECT presupuesto_interno_detalle.partida
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = requerimiento_pago_detalle.id_partida_pi and requerimiento_pago.id_presupuesto_interno > 0 limit 1) AS codigo_sub_partida_presupuesto_interno"),
            DB::raw("(SELECT presupuesto_interno_detalle.descripcion
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = requerimiento_pago_detalle.id_partida_pi and requerimiento_pago.id_presupuesto_interno > 0 limit 1) AS descripcion_sub_partida_presupuesto_interno"),
            DB::raw("(SELECT presupuesto_interno_modelo.descripcion
            FROM finanzas.presupuesto_interno_detalle
            inner join finanzas.presupuesto_interno_modelo on presupuesto_interno_modelo.id_modelo_presupuesto_interno = presupuesto_interno_detalle.id_padre
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = requerimiento_pago_detalle.id_partida_pi and requerimiento_pago.id_presupuesto_interno > 0 limit 1) AS descripcion_partida_presupuesto_interno")

        )
        ->leftJoin('tesoreria.requerimiento_pago', 'requerimiento_pago.id_requerimiento_pago', '=', 'requerimiento_pago_detalle.id_requerimiento_pago')
        ->leftJoin('finanzas.presupuesto_interno', 'presupuesto_interno.id_presupuesto_interno', '=', 'requerimiento_pago.id_presupuesto_interno')
        ->leftJoin('configuracion.sis_moneda', 'requerimiento_pago.id_moneda', '=', 'sis_moneda.id_moneda')
        ->leftJoin('administracion.adm_prioridad', 'requerimiento_pago.id_prioridad', '=', 'adm_prioridad.id_prioridad')
        ->leftJoin('configuracion.sis_grupo', 'requerimiento_pago.id_grupo', '=', 'sis_grupo.id_grupo')
        ->leftJoin('administracion.sis_sede', 'sis_sede.id_sede', '=', 'requerimiento_pago.id_sede')
        ->leftJoin('administracion.division', 'division.id_division', '=', 'requerimiento_pago.id_division')
        ->leftJoin('proyectos.proy_proyecto', 'proy_proyecto.id_proyecto', '=', 'requerimiento_pago.id_proyecto')
        ->leftJoin('administracion.adm_empresa', 'requerimiento_pago.id_empresa', '=', 'adm_empresa.id_empresa')
        ->leftJoin('contabilidad.adm_contri', 'adm_empresa.id_contribuyente', '=', 'adm_contri.id_contribuyente')
        ->leftJoin('contabilidad.sis_identi', 'sis_identi.id_doc_identidad', '=', 'adm_contri.id_doc_identidad')
        ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'requerimiento_pago.id_cc')
        ->leftJoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
        ->leftJoin('tesoreria.requerimiento_pago_tipo', 'requerimiento_pago_tipo.id_requerimiento_pago_tipo', '=', 'requerimiento_pago.id_requerimiento_pago_tipo')
        ->leftJoin('finanzas.presup_par', 'presup_par.id_partida', '=', 'requerimiento_pago_detalle.id_partida')
        ->leftJoin('finanzas.presup', 'presup.id_presup', '=', 'presup_par.id_presup')
        ->leftJoin('finanzas.centro_costo', 'centro_costo.id_centro_costo', '=', 'requerimiento_pago_detalle.id_centro_costo')
        ->leftJoin('finanzas.centro_costo as padre_centro_costo', 'padre_centro_costo.id_centro_costo', '=', 'centro_costo.id_padre')
        ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago.id_estado', '=', 'requerimiento_pago_estado.id_requerimiento_pago_estado')
        ->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9]);
        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('requerimiento_pago.fecha_registro', '<=', $request->fecha_final);
        }

        $data = $data->orderBy('requerimiento_pago.id_requerimiento_pago', 'DESC')->get();

        return Excel::download(new RequerimientoPagosNivelItemExport(json_encode($data)), 'requerimiento_pagados_nivel_item.xlsx');
    }

    public function exportarOrdenesComprasServicios(Request $request)
    {
        $data = Orden::select(
            'log_ord_compra.*',
            'adm_contri.nro_documento',
            'adm_contri.razon_social',
            'adm_empresa.id_empresa',
            'empresa.razon_social as razon_social_empresa',
            'adm_empresa.codigo as codigo_empresa',
            'requerimiento_pago_estado.descripcion as estado_doc',
            'requerimiento_pago_estado.bootstrap_color',
            'sis_moneda.simbolo',
            'log_cdn_pago.descripcion AS condicion_pago',
            'sis_sede.descripcion as sede_descripcion',
            'adm_cta_contri.nro_cuenta',
            'adm_cta_contri.nro_cuenta_interbancaria',
            'adm_cta_contri.fecha_registro as fecha_registro_cuenta_contribuyente',
            'adm_tp_cta.descripcion as tipo_cuenta',
            'banco_contribuyente.razon_social as banco_contribuyente',
            'rrhh_cta_banc.nro_cuenta as nro_cuenta_persona',
            'rrhh_cta_banc.nro_cci as nro_cci_persona',
            'rrhh_cta_banc.fecha_registro as fecha_registro_cuenta_persona',
            'tp_cta_persona.descripcion as tipo_cuenta_persona',
            'banco_persona.razon_social as banco_persona',
            DB::raw("CONCAT(rrhh_perso.nombres,' ',rrhh_perso.apellido_paterno,' ',rrhh_perso.apellido_materno) as nombre_completo_persona"),
            'rrhh_perso.nro_documento as nro_documento_persona',
            'adm_prioridad.descripcion as prioridad',
            'autorizado.nombre_corto as nombre_autorizado',
            DB::raw("(SELECT sum(subtotal) FROM logistica.log_det_ord_compra
                        WHERE log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra
                        and log_det_ord_compra.estado != 7) AS suma_total"),
            DB::raw("(SELECT sum(total_pago) FROM tesoreria.registro_pago
                        WHERE registro_pago.id_oc = log_ord_compra.id_orden_compra
                        and registro_pago.estado != 7) AS suma_pagado"),
            DB::raw("(SELECT COUNT(adjuntos_logisticos.id_adjunto)
                    FROM logistica.adjuntos_logisticos
                    WHERE  adjuntos_logisticos.id_orden = log_ord_compra.id_orden_compra AND
                    adjuntos_logisticos.estado != 7) AS cantidad_adjuntos_logisticos"),
            DB::raw("(SELECT monto_cuota FROM logistica.pago_cuota_detalle
            inner join logistica.pago_cuota on pago_cuota.id_pago_cuota = pago_cuota_detalle.id_pago_cuota
            WHERE  pago_cuota.id_orden = log_ord_compra.id_orden_compra and pago_cuota_detalle.id_estado != 7 order by pago_cuota_detalle.fecha_registro desc limit 1 ) AS ultima_monto_cuota"),
            DB::raw("(SELECT sum(monto_cuota) FROM logistica.pago_cuota_detalle
            inner join logistica.pago_cuota on pago_cuota.id_orden = log_ord_compra.id_orden_compra
            WHERE pago_cuota.id_pago_cuota = pago_cuota_detalle.id_pago_cuota
            and pago_cuota_detalle.id_estado =5) AS suma_cuotas_con_autorizacion"),

        )
            ->leftjoin('logistica.log_prove', 'log_prove.id_proveedor', '=', 'log_ord_compra.id_proveedor')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->leftjoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'log_ord_compra.estado_pago')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'log_ord_compra.id_moneda')
            ->leftJoin('logistica.log_cdn_pago', 'log_cdn_pago.id_condicion_pago', '=', 'log_ord_compra.id_condicion')
            ->leftjoin('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')
            ->leftjoin('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftJoin('contabilidad.adm_cta_contri', 'adm_cta_contri.id_cuenta_contribuyente', '=', 'log_ord_compra.id_cta_principal')
            ->leftJoin('contabilidad.adm_tp_cta', 'adm_tp_cta.id_tipo_cuenta', '=', 'adm_cta_contri.id_tipo_cuenta')
            ->leftJoin('contabilidad.cont_banco as bco_contribuyente', 'bco_contribuyente.id_banco', '=', 'adm_cta_contri.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_contribuyente', 'banco_contribuyente.id_contribuyente', '=', 'bco_contribuyente.id_contribuyente')
            ->leftJoin('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'log_ord_compra.id_persona_pago')
            ->leftJoin('rrhh.rrhh_cta_banc', 'rrhh_cta_banc.id_cuenta_bancaria', '=', 'log_ord_compra.id_cuenta_persona_pago')
            ->leftJoin('contabilidad.cont_banco as bco_persona', 'bco_persona.id_banco', '=', 'rrhh_cta_banc.id_banco')
            ->leftJoin('contabilidad.adm_contri as banco_persona', 'banco_persona.id_contribuyente', '=', 'bco_persona.id_contribuyente')
            ->leftJoin('contabilidad.adm_tp_cta as tp_cta_persona', 'tp_cta_persona.id_tipo_cuenta', '=', 'rrhh_cta_banc.id_tipo_cuenta')
            ->leftJoin('configuracion.sis_usua as autorizado', 'autorizado.id_usuario', '=', 'log_ord_compra.usuario_autorizacion')
            ->join('administracion.adm_prioridad', 'adm_prioridad.id_prioridad', '=', 'log_ord_compra.id_prioridad_pago')
            ->whereIn('log_ord_compra.estado_pago', [8, 5, 6, 9, 10])
            ->where('log_ord_compra.estado', '!=', 7);

        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '<=', $request->fecha_final);
        }

        $data = $data->orderBy('id_orden_compra', 'DESC')->get();

        $json_excel = array();
        // return $data;exit;
        foreach ($data as $key => $value) {
            $pagado = floatval($value->suma_pagado !== null ? $value->suma_pagado : 0);
            $total = floatval($value->monto_total);
            $por_pagar = ($total - $pagado);

            $tiene_pago_en_cuotas = '';
            if ($value->tiene_pago_en_cuotas === true) {
                $tiene_pago_en_cuotas = ((floatval($value->ultima_monto_cuota) > 0 ? (floatval($value->ultima_monto_cuota)) : ($value->monto_total !== null ? floatval($value->monto_total) : '0.00')));
            } else {
                $tiene_pago_en_cuotas = '(No aplica)';
            }

            array_push($json_excel,array(
                "prioridad"=>$value->prioridad,
                "requerimientos"=>$value->requerimientos,
                "codigo_empresa"=>$value->codigo_empresa,
                "codigo"=>$value->codigo,
                "condicion_pago"=>$value->condicion_pago,
                "plazo_dias"=>$value->plazo_dias,
                "razon_social"=>$value->razon_social,
                "fecha_solicitud_pago"=>($value->fecha_solicitud_pago !== null ? $value->fecha_solicitud_pago: ''),
                "simbolo"=>$value->simbolo,
                "monto_total"=>($value->monto_total!== null ? round($value->monto_total,2) : '0.00'),
                "saldo"=>$por_pagar,
                "tiene_pago_en_cuotas"=>$tiene_pago_en_cuotas,
                "estado_doc"=>$value->estado_doc,
                "nombre_autorizado"=>($value->nombre_autorizado?$value->nombre_autorizado.' el '.$value->fecha_autorizacion:''),

                
                "id_tipo_destinatario_pago"=>(!empty($value->id_tipo_destinatario_pago)?$value->id_tipo_destinatario_pago:'-'),
                //contribuyente
                "nro_documento"=>(!empty($value->nro_documento)?$value->nro_documento:'-'),
                "razon_social"=>(!empty($value->razon_social)?$value->razon_social:'-'),
                "tipo_cuenta"=>(!empty($value->tipo_cuenta)?$value->tipo_cuenta:'-'),
                "banco_contribuyente"=>(!empty($value->banco_contribuyente)?$value->banco_contribuyente:'-'),
                "nro_cuenta"=>(!empty($value->nro_cuenta)?$value->nro_cuenta:'-'),
                "nro_cuenta_interbancaria"=>(!empty($value->nro_cuenta_interbancaria)?$value->nro_cuenta_interbancaria:'-'),
                "fecha_registro_cuenta_contribuyente"=>(!empty($value->fecha_registro_cuenta_contribuyente)?$value->fecha_registro_cuenta_contribuyente:'-'),

                //persona
                "nro_documento_persona"=>(!empty($value->nro_documento_persona)?$value->nro_documento_persona:'-'),
                "nombre_completo_persona"=>(!empty($value->nombre_completo_persona)?$value->nombre_completo_persona:'-'),
                "tipo_cuenta_persona"=>(!empty($value->tipo_cuenta_persona)?$value->tipo_cuenta_persona:'-'),
                "banco_persona"=>(!empty($value->banco_persona)?$value->banco_persona:'-'),
                "nro_cuenta_persona"=>(!empty($value->nro_cuenta_persona)?$value->nro_cuenta_persona:'-'),
                "nro_cci_persona"=>(!empty($value->nro_cci_persona)?$value->nro_cci_persona:'-'),
                "fecha_registro_cuenta_persona"=>(!empty($value->fecha_registro_cuenta_persona)?$value->fecha_registro_cuenta_persona:'-'),


            ));
        }

        return Excel::download(new OrdenCompraServicioExport(json_encode($json_excel)), 'requerimiento_logisticos_pagados.xlsx');
    }
    public function exportarOrdenesComprasServiciosItems(Request $request)
    {
        $data = DB::table('almacen.alm_det_req')
        ->select(
            'alm_prod.codigo as codigo_producto',
            'alm_prod.descripcion as descripcion_producto',
            'alm_det_req.descripcion as descripcion_detalle_requerimiento',
            'alm_det_req.motivo',
            'alm_det_req.cantidad',
            'alm_det_req.precio_unitario',
            'alm_det_req.subtotal',
            'alm_det_req.fecha_registro',
            'adm_prioridad.descripcion as prioridad',
            'alm_tp_req.descripcion AS tipo_requerimiento',
            'alm_req.codigo',
            'oportunidades.codigo_oportunidad',
            'alm_req.concepto',
            'alm_req.codigo',
            'alm_req.observacion',
            'sis_moneda.simbolo as simbolo_moneda',
            'sis_sede.codigo as sede',
            'sis_sede.descripcion as descripcion_empresa_sede',
            'adm_contri.razon_social as empresa_razon_social',
            'sis_identi.descripcion as empresa_tipo_documento',
            'proy_proyecto.descripcion AS descripcion_proyecto',
            'sis_grupo.descripcion as grupo',
            'division.descripcion as division',
            'alm_req.monto_total',
            'presup_par.descripcion as descripcion_partida',
            'presup_par.codigo as partida',
            'presup_par.id_partida',
            'padre_centro_costo.codigo as padre_centro_costo',
            'padre_centro_costo.descripcion as padre_descripcion_centro_costo',
            'centro_costo.descripcion as descripcion_centro_costo',
            'centro_costo.codigo as centro_costo',
            'centro_costo.id_centro_costo',
            'adm_estado_doc.estado_doc as estado_requerimiento',
            'presup.codigo as codigo_presupuesto_old',
            'presup.descripcion as descripcion_presupuesto_old',
            'presupuesto_interno.codigo as codigo_presupuesto_interno',
            'presupuesto_interno.descripcion as descripcion_presupuesto_interno',

            DB::raw("(SELECT 
            (CAST (replace(presupuesto_interno_detalle.enero, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.febrero, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.marzo, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.abril, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.mayo, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.junio, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.julio, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.agosto, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.setiembre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.octubre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.noviembre, ',', '') AS NUMERIC(10,2))
            + CAST (replace(presupuesto_interno_detalle.diciembre, ',', '') AS NUMERIC(10,2)))
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno = alm_req.id_presupuesto_interno and alm_det_req.id_partida_pi=presupuesto_interno_detalle.id_presupuesto_interno_detalle limit 1) AS presupuesto_interno_total_partida"),
            
            DB::raw("( SELECT
                CASE WHEN (SELECT date_part('month', alm_req.fecha_registro)) =1 THEN presupuesto_interno_detalle.enero
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =2 THEN presupuesto_interno_detalle.febrero
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =3 THEN presupuesto_interno_detalle.marzo
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =4 THEN presupuesto_interno_detalle.abril
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =5 THEN presupuesto_interno_detalle.mayo
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =6 THEN presupuesto_interno_detalle.junio
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =7 THEN presupuesto_interno_detalle.julio
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =8 THEN presupuesto_interno_detalle.agosto
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =9 THEN presupuesto_interno_detalle.setiembre
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =10 THEN presupuesto_interno_detalle.octubre
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =11 THEN presupuesto_interno_detalle.noviembre
                    WHEN (SELECT date_part('month', alm_req.fecha_registro)) =12 THEN presupuesto_interno_detalle.diciembre
                    ELSE ''
                    END
                FROM finanzas.presupuesto_interno_detalle
                WHERE presupuesto_interno_detalle.id_presupuesto_interno = alm_req.id_presupuesto_interno 
                and alm_det_req.id_partida_pi=presupuesto_interno_detalle.id_presupuesto_interno_detalle 
                limit 1 ) AS presupuesto_interno_mes_partida "),
            
            DB::raw("(SELECT presup_titu.descripcion
            FROM finanzas.presup_titu
            WHERE presup_titu.codigo = presup_par.cod_padre and presup_titu.id_presup=presup_par.id_presup limit 1) AS descripcion_partida_padre"),
            DB::raw("(SELECT presupuesto_interno_detalle.partida
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = alm_det_req.id_partida_pi and alm_req.id_presupuesto_interno > 0 limit 1) AS codigo_sub_partida_presupuesto_interno"),
            DB::raw("(SELECT presupuesto_interno_detalle.descripcion
            FROM finanzas.presupuesto_interno_detalle
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = alm_det_req.id_partida_pi and alm_req.id_presupuesto_interno > 0 limit 1) AS descripcion_sub_partida_presupuesto_interno"),
            DB::raw("(SELECT presupuesto_interno_modelo.descripcion
            FROM finanzas.presupuesto_interno_detalle
            inner join finanzas.presupuesto_interno_modelo on presupuesto_interno_modelo.id_modelo_presupuesto_interno = presupuesto_interno_detalle.id_padre
            WHERE presupuesto_interno_detalle.id_presupuesto_interno_detalle = alm_det_req.id_partida_pi and alm_req.id_presupuesto_interno > 0 limit 1) AS descripcion_partida_presupuesto_interno"),
            
            'log_ord_compra.codigo as nro_orden',
            'estados_compra.descripcion as estado_orden',
            'requerimiento_pago_estado.descripcion as estado_pago',
            'moneda_orden.simbolo as simbolo_moneda_orden',
            'log_det_ord_compra.cantidad as cantidad_orden',
            'log_det_ord_compra.precio as precio_orden',
            DB::raw("(SELECT log_det_ord_compra.subtotal  
            FROM logistica.log_ord_compra
            WHERE log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra 
            and log_det_ord_compra.id_detalle_requerimiento = alm_det_req.id_detalle_requerimiento limit 1) AS subtotal_orden"),
            DB::raw("(SELECT CASE WHEN 
            log_ord_compra.incluye_igv =true THEN (log_det_ord_compra.subtotal * 1.18 ) ELSE log_det_ord_compra.subtotal END  
            FROM logistica.log_ord_compra
            WHERE log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra 
            and log_det_ord_compra.id_detalle_requerimiento = alm_det_req.id_detalle_requerimiento limit 1) AS subtotal_orden_considera_igv"),

            'alm_req.fecha_requerimiento',
            DB::raw("(SELECT cont_tp_cambio.venta  
            FROM contabilidad.cont_tp_cambio
            WHERE TO_DATE(to_char(cont_tp_cambio.fecha,'YYYY-MM-DD'),'YYYY-MM-DD') = TO_DATE(to_char(alm_req.fecha_requerimiento,'YYYY-MM-DD'),'YYYY-MM-DD') limit 1) AS tipo_cambio"),

            'estado_despacho.estado_doc as estado_despacho',
        )

        ->leftJoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
        ->leftJoin('finanzas.presupuesto_interno', 'presupuesto_interno.id_presupuesto_interno', '=', 'alm_req.id_presupuesto_interno')
        ->leftJoin('almacen.alm_prod', 'alm_prod.id_producto', '=', 'alm_det_req.id_producto')
        ->leftJoin('configuracion.sis_moneda', 'alm_req.id_moneda', '=', 'sis_moneda.id_moneda')
        ->leftJoin('administracion.adm_prioridad', 'alm_req.id_prioridad', '=', 'adm_prioridad.id_prioridad')
        ->leftJoin('configuracion.sis_grupo', 'alm_req.id_grupo', '=', 'sis_grupo.id_grupo')
        ->leftJoin('administracion.sis_sede', 'sis_sede.id_sede', '=', 'alm_req.id_sede')
        ->leftJoin('administracion.division', 'division.id_division', '=', 'alm_req.division_id')
        ->leftJoin('proyectos.proy_proyecto', 'proy_proyecto.id_proyecto', '=', 'alm_req.id_proyecto')
        ->leftJoin('administracion.adm_empresa', 'alm_req.id_empresa', '=', 'adm_empresa.id_empresa')
        ->leftJoin('contabilidad.adm_contri', 'adm_empresa.id_contribuyente', '=', 'adm_contri.id_contribuyente')
        ->leftJoin('contabilidad.sis_identi', 'sis_identi.id_doc_identidad', '=', 'adm_contri.id_doc_identidad')
        ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
        ->leftJoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
        ->leftJoin('almacen.alm_tp_req', 'alm_tp_req.id_tipo_requerimiento', '=', 'alm_req.id_tipo_requerimiento')
        ->leftJoin('finanzas.presup_par', 'presup_par.id_partida', '=', 'alm_det_req.partida')
        ->leftJoin('finanzas.presup', 'presup.id_presup', '=', 'presup_par.id_presup')

        ->leftJoin('finanzas.centro_costo', 'centro_costo.id_centro_costo', '=', 'alm_det_req.centro_costo_id')
        ->leftJoin('finanzas.centro_costo as padre_centro_costo', 'padre_centro_costo.id_centro_costo', '=', 'centro_costo.id_padre')
        ->leftJoin('administracion.adm_estado_doc', 'alm_req.estado', '=', 'adm_estado_doc.id_estado_doc')
        ->leftJoin('logistica.log_det_ord_compra','log_det_ord_compra.id_detalle_requerimiento','=','alm_det_req.id_detalle_requerimiento')
        ->leftJoin('logistica.log_ord_compra','log_ord_compra.id_orden_compra','=','log_det_ord_compra.id_orden_compra')
        ->leftJoin('configuracion.sis_moneda as moneda_orden', 'moneda_orden.id_moneda', '=', 'log_ord_compra.id_moneda')
        ->leftJoin('logistica.estados_compra', 'estados_compra.id_estado', '=', 'log_ord_compra.estado')
        ->leftJoin('tesoreria.requerimiento_pago_estado', 'requerimiento_pago_estado.id_requerimiento_pago_estado', '=', 'log_ord_compra.estado_pago')
        ->leftJoin('almacen.orden_despacho', 'orden_despacho.id_requerimiento', '=', 'alm_req.id_requerimiento')
        ->leftJoin('administracion.adm_estado_doc as estado_despacho', 'estado_despacho.id_estado_doc', '=', 'orden_despacho.estado') 
        ->whereIn('log_ord_compra.estado_pago', [8, 5, 6, 9, 10])
        ->where('log_ord_compra.estado', '!=', 7);

        if (!empty($request->prioridad)) {
            $data = $data->where('adm_prioridad.id_prioridad', $request->prioridad);
        }
        if (!empty($request->empresa)) {
            $data = $data->where('adm_empresa.id_empresa', $request->empresa);
        }
        if (!empty($request->estado)) {
            $data = $data->where('requerimiento_pago_estado.id_requerimiento_pago_estado', $request->estado);
        }
        if (!empty($request->fecha_inicio)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '>=', $request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->whereDate('log_ord_compra.fecha_solicitud_pago', '<=', $request->fecha_final);
        }

        $data = $data->orderBy('log_ord_compra.id_orden_compra', 'DESC')->get();

        return Excel::download(new OrdenCompraServicioNivelItemExport(json_encode($data)), 'requerimiento_logisticos_pagados_nivel_item.xlsx');
    }
    public function cuadroComparativoPagos()
    {

        $empresa = Empresa::all();
        $estados = RequerimientoPagoEstados::whereIn('id_requerimiento_pago_estado', [6, 2, 5, 8, 9])->get();
        $monedas = Moneda::all();

        $grupo_estado = array();
        foreach ($estados as $key => $value) {
            $grupo_estado = array();
            foreach ($empresa as $key_empresa => $value_empresa) {
                $cantidad = RequerimientoPago::where('id_estado', $value->id_requerimiento_pago_estado)
                    ->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9])
                    ->where('id_empresa', $value_empresa->id_empresa)
                    ->count();
                // return $cantidad;exit;
                array_push($grupo_estado, array(
                    "empresa_id" => $value_empresa->id_empresa,
                    "estado_id" => $value->id_requerimiento_pago_estado,
                    "cantidad" => $cantidad,
                ));
            }
            $value->grupo = $grupo_estado;
        }
        $grupo_moneda = array();
        foreach ($monedas as $key => $value) {
            $grupo_moneda = array();
            foreach ($empresa as $key_empresa => $value_empresa) {
                $cantidad = RequerimientoPago::where('id_moneda', $value->id_moneda)
                    ->where('id_empresa', $value_empresa->id_empresa)
                    ->whereIn('requerimiento_pago.id_estado', [6, 2, 5, 8, 9])
                    ->whereNotIn('id_estado', [7, 6])
                    ->get();
                $total = 0;

                foreach ($cantidad as $key_cantidad => $value_cantidad) {
                    $monto_total = $value_cantidad->monto_total;

                    $registro_pagados = RegistroPago::where('id_requerimiento_pago',$value_cantidad->id_requerimiento_pago)->where('estado','!=',7)->get();

                    $total_registro_pagado=0;
                    if (sizeof($registro_pagados)>0) {
                        foreach ($registro_pagados as $flight) {
                            $total_registro_pagado=$total_registro_pagado + $flight->total_pago;
                        }
                    }

                    $total= $total + ($monto_total - $total_registro_pagado);
                }
                // $total= $monto_total - $total_registro_pagado;
                // return $cantidad;exit;
                array_push($grupo_moneda,array(
                    "empresa_id"=>$value_empresa->id_empresa,
                    "moneda_id"=>$value->id_moneda,
                    "total"=> number_format($total, 2, '.', ','),
                ));
            }
            $value->grupo = $grupo_moneda;
        }
        return response()->json(["empresas" => $empresa, "estados" => $estados, "monedas" => $monedas], 200);
    }
    public function cuadroComparativoOrdenes()
    {
        $empresa = Empresa::all();
        $estados = RequerimientoPagoEstados::whereIn('id_requerimiento_pago_estado', [8, 5, 6, 9, 10])->get();
        $monedas = Moneda::all();

        $grupo_estado = array();
        foreach ($estados as $key => $value) {
            $grupo_estado = array();
            foreach ($empresa as $key_empresa => $value_empresa) {
                $cantidad = Orden::select('log_ord_compra.*')
                    ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
                    ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')
                    ->where('log_ord_compra.estado_pago', $value->id_requerimiento_pago_estado)
                    ->where('adm_empresa.id_empresa', $value_empresa->id_empresa)
                    ->whereIn('log_ord_compra.estado_pago', [8, 5, 6, 9, 10])
                    ->where('log_ord_compra.estado', '!=', 7)
                    ->count();
                // return $cantidad;exit;
                array_push($grupo_estado, array(
                    "empresa_id" => $value_empresa->id_empresa,
                    "estado_id" => $value->id_requerimiento_pago_estado,
                    "cantidad" => $cantidad,
                ));
            }
            $value->grupo = $grupo_estado;
        }

        $grupo_moneda = array();
        foreach ($monedas as $key => $value) {
            $grupo_moneda = array();
            foreach ($empresa as $key_empresa => $value_empresa) {
                $cantidad = Orden::select('log_ord_compra.*')
                    ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
                    ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')

                    ->where('adm_empresa.id_empresa', $value_empresa->id_empresa)
                    ->where('log_ord_compra.id_moneda', $value->id_moneda)
                    ->whereIn('log_ord_compra.estado_pago', [8, 5, 9, 10])
                    ->where('log_ord_compra.estado', '!=', 7)
                    ->whereNotIn('log_ord_compra.estado_pago', [7, 6])
                    ->get();
                $total = 0;

                foreach ($cantidad as $key_cantidad => $value_cantidad) {
                    $total = $value_cantidad->monto_total + $total;
                }
                // return $cantidad;exit;
                array_push($grupo_moneda, array(
                    "empresa_id" => $value_empresa->id_empresa,
                    "moneda_id" => $value->id_moneda,
                    "total" => round($total, 2),
                ));
            }
            $value->grupo = $grupo_moneda;
        }
        return response()->json(["empresas" => $empresa, "estados" => $estados, "monedas" => $monedas], 200);
    }


    public function consultarPagoEfectuadosDeOrden($idOrden){
        $cantidadPagoEfectuados=0;
        if($idOrden >0){
            $cantidadPagoEfectuados= RegistroPago::where([['id_oc',$idOrden],['estado','!=',7]])->count();
        }
        return $cantidadPagoEfectuados;
    }
}
