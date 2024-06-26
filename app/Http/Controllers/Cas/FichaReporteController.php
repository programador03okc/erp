<?php

namespace App\Http\Controllers\Cas;

use App\Exports\HorasAtencionExport;
use App\Exports\IncidenciasConHistorialExport;
use App\Exports\IncidenciasExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Periodo;
use App\Models\Cas\Incidencia;
use App\Models\Cas\IncidenciaProducto;
use App\Models\Cas\IncidenciaReporte;
use App\Models\Cas\IncidenciaReporteAdjunto;
use App\Models\Cas\MedioReporte;
use App\Models\Configuracion\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

ini_set('max_execution_time', 0);

class FichaReporteController extends Controller
{
    function view_ficha_reporte()
    {
        $periodos = Periodo::mostrar();

        $usuarios = Usuario::join('configuracion.usuario_rol', 'usuario_rol.id_usuario', '=', 'sis_usua.id_usuario')
            ->where([['sis_usua.estado', '=', 1], ['usuario_rol.id_rol', '=', 20], ['usuario_rol.estado', '=', 1]])->get(); //20 CAS

        return view('cas.fichasReporte.fichaReporte', compact('usuarios','periodos'));
    }

    function incidencias()
    {
        $lista = DB::table('cas.incidencia')
            ->select(
                'incidencia.*',
                'incidencia.serie as inci_serie',
                'guia_ven.serie',
                'guia_ven.numero',
                'guia_ven.id_od',
                'adm_contri.razon_social',
                'adm_contri.id_contribuyente',
                'adm_empresa.id_empresa',
                'empresa.razon_social as empresa_razon_social',
                'alm_req.codigo as codigo_requerimiento',
                'alm_req.id_requerimiento',
                'alm_req.concepto',
                'adm_ctb_contac.nombre',
                'adm_ctb_contac.telefono',
                'adm_ctb_contac.cargo',
                'adm_ctb_contac.direccion',
                'adm_ctb_contac.horario',
                'adm_ctb_contac.email',
                'sis_usua.nombre_corto',
                'incidencia_estado.descripcion as estado_doc',
                'incidencia_estado.bootstrap_color',
            )
            ->leftjoin('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'incidencia.id_salida')
            ->leftjoin('almacen.guia_ven', 'guia_ven.id_guia_ven', '=', 'mov_alm.id_guia_ven')
            ->leftjoin('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'guia_ven.id_od')
            ->leftjoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'orden_despacho.id_requerimiento')
            ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'incidencia.id_empresa')
            ->leftjoin('contabilidad.adm_contri as empresa', 'empresa.id_contribuyente', '=', 'adm_empresa.id_contribuyente')
            ->leftjoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'incidencia.id_responsable')
            ->leftjoin('cas.incidencia_estado', 'incidencia_estado.id_estado', '=', 'incidencia.estado')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'incidencia.id_contribuyente')
            ->leftjoin('contabilidad.adm_ctb_contac', 'adm_ctb_contac.id_datos_contacto', '=', 'incidencia.id_contacto')
            ->where([['incidencia.estado', '!=', 7]]);

        return $lista;
    }
    public function listarIncidencias(Request $request)
    {   

        $query = $this->incidencias()->orderBy('fecha_reporte','desc')->orderBy('id_incidencia','desc');
        if (session()->has('clPeriodoIncidencia')) {
            $anio = session()->get('clPeriodoIncidencia');
            if($anio!=''){
                $fechaInicio = Carbon::create($anio, 1, 1)->format('d-m-Y');
                $fechaFin = Carbon::create($anio, 12, 31)->format('d-m-Y');
                $query = $query->whereBetween('incidencia.fecha_registro', [$fechaInicio,$fechaFin]);
            }
        }else{
            $anioActual = Periodo::obtenerDescripciónPeriodoActual();

            $fechaInicio = Carbon::create($anioActual, 1, 1)->format('d-m-Y');
            $fechaFin = Carbon::create($anioActual, 12, 31)->format('d-m-Y');
            $query = $query->whereBetween('incidencia.fecha_registro', [$fechaInicio,$fechaFin]);
        }
        return datatables($query)->toJson();
    }

    public function incidenciasExcel(Request $request)
    {
        set_time_limit(0);
        $data = $this->incidencias()->orderBy('fecha_reporte','desc')->orderBy('id_incidencia','desc');
        if (!empty($request->fecha_inicio)) {
            $data = $data->where('incidencia.fecha_reporte','>=',$request->fecha_inicio);
        }
        if (!empty($request->fecha_final)) {
            $data = $data->where('incidencia.fecha_reporte','<=',$request->fecha_final);
        }
        // return $data->get();exit;
        $fecha = new Carbon();
        return Excel::download(new IncidenciasExport(
            $data,
        ), 'Reporte de incidencias al ' . $fecha . '.xlsx');
    }

    public function incidenciasExcelConHistorial(Request $request)
    {
        set_time_limit(0);
        $data = $this->incidencias()->orderBy('fecha_reporte','desc')->orderBy('id_incidencia','desc');
        $fecha = new Carbon();
        return Excel::download(new IncidenciasConHistorialExport(
            $data,
        ), 'Reporte de incidencias con historial al ' . $fecha . '.xlsx');
    }

    function listarFichasReporte($id_incidencia)
    {
        $lista = IncidenciaReporte::with('usuario', 'adjuntos')->where([
            ['id_incidencia', '=', $id_incidencia], ['estado', '!=', 7]
        ])->get();
        return response()->json($lista);
    }

    public function verAdjuntosFicha($id)
    {
        $adjuntos = IncidenciaReporteAdjunto::where('id_incidencia_reporte', $id)
            ->get();
        return response()->json($adjuntos);
    }

    function guardarFichaReporte(Request $request)
    {
        try {
            DB::beginTransaction();
            $mensaje = '';
            $tipo = '';

            $reporte = new IncidenciaReporte();
            $reporte->codigo = IncidenciaReporte::nuevoCodigoFicha($request->padre_id_incidencia);
            $reporte->id_incidencia = $request->padre_id_incidencia;
            $reporte->fecha_reporte = $request->fecha_reporte;
            $reporte->id_usuario = $request->id_usuario;
            $reporte->acciones_realizadas = $request->acciones_realizadas;
            $reporte->estado = 1;
            $reporte->fecha_registro = new Carbon();
            $reporte->save();

            $incidencia = Incidencia::find($request->padre_id_incidencia);
            $incidencia->estado = 2;
            $incidencia->save();

            //Guardar archivos subidos
            if ($request->hasFile('archivos')) {
                $archivos = $request->file('archivos');

                foreach ($archivos as $archivo) {
                    $id_adjunto = DB::table('cas.incidencia_reporte_adjuntos')
                        ->insertGetId([
                            'id_incidencia_reporte' => $reporte->id_incidencia_reporte,
                            'estado' => 1,
                        ], 'id_adjunto');

                    //obtenemos el nombre del archivo
                    $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
                    $nombre = $id_adjunto . '.' . $reporte->codigo . '.' . $extension;

                    //indicamos que queremos guardar un nuevo archivo en el disco local
                    File::delete(public_path('cas/incidencias/fichas/' . $nombre));
                    Storage::disk('archivos')->put('cas/incidencias/fichas/' . $nombre, File::get($archivo));

                    DB::table('cas.incidencia_reporte_adjuntos')
                        ->where('id_adjunto', $id_adjunto)
                        ->update(['adjunto' => $nombre]);
                }
            }

            $mensaje = 'Se guardó la ficha reporte correctamente';
            $tipo = 'success';

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $mensaje]);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['tipo' => 'error', 'mensaje' => 'Hubo un problema al guardar. Por favor intente de nuevo', 'error' => $e->getMessage()], 200);
        }
    }

    function actualizarFichaReporte(Request $request)
    {
        try {
            DB::beginTransaction();
            $mensaje = '';
            $tipo = '';

            $reporte = IncidenciaReporte::find($request->id_incidencia_reporte);

            if ($reporte !== null) {
                $reporte->fecha_reporte = $request->fecha_reporte;
                $reporte->id_usuario = $request->id_usuario;
                $reporte->acciones_realizadas = $request->acciones_realizadas;
                $reporte->save();

                $mensaje = 'Se actualizó la ficha reporte correctamente';
                $tipo = 'success';
            } else {
                $mensaje = 'No existe la ficha reporte seleccionada';
                $tipo = 'warning';
            }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $mensaje]);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['tipo' => 'error', 'mensaje' => 'Hubo un problema al actualizar. Por favor intente de nuevo', 'error' => $e->getMessage()], 200);
        }
    }

    function anularFichaReporte($id_reporte)
    {
        try {
            DB::beginTransaction();
            $mensaje = '';
            $tipo = '';

            $reporte = IncidenciaReporte::find($id_reporte);

            if ($reporte !== null) {
                $reporte->estado = 7;
                $reporte->save();

                $mensaje = 'Se anuló la ficha reporte correctamente.';
                $tipo = 'success';
            } else {
                $mensaje = 'No existe la ficha reporte.';
                $tipo = 'warning';
            }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $mensaje]);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['tipo' => 'error', 'mensaje' => 'Hubo un problema al guardar. Por favor intente de nuevo', 'error' => $e->getMessage()], 200);
        }
    }

    function cancelarIncidencia(Request $request)
    {
        try {
            DB::beginTransaction();
            $mensaje = '';
            $tipo = '';

            $incidencia = Incidencia::find($request->id_incidencia_cancelacion);

            if ($incidencia !== null) {
                $incidencia->fecha_cancelacion = $request->fecha_cancelacion;
                $incidencia->comentarios_cancelacion = $request->comentarios_cancelacion;
                $incidencia->estado = 4;
                $incidencia->save();

                $mensaje = 'Se canceló la incidencia.';
                $tipo = 'success';
            } else {
                $mensaje = 'No existe la incidencia.';
                $tipo = 'warning';
            }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $mensaje]);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['tipo' => 'error', 'mensaje' => 'Hubo un problema al guardar. Por favor intente de nuevo', 'error' => $e->getMessage()], 200);
        }
    }

    function cerrarIncidencia(Request $request)
    {
        try {
            DB::beginTransaction();
            $mensaje = '';
            $tipo = '';

            $incidencia = Incidencia::find($request->id_incidencia_cierre);

            if ($incidencia !== null) {
                $incidencia->fecha_cierre = $request->fecha_cierre;
                $incidencia->importe_gastado = $request->importe_gastado;
                $incidencia->comentarios_cierre = $request->comentarios_cierre;
                $incidencia->parte_reemplazada = $request->parte_reemplazada;
                $incidencia->estado = 3;
                $incidencia->save();

                $mensaje = 'Se cerró satisfactoriamente la incidencia.';
                $tipo = 'success';
            } else {
                $mensaje = 'No existe la incidencia.';
                $tipo = 'warning';
            }

            DB::commit();
            return response()->json(['tipo' => $tipo, 'mensaje' => $mensaje]);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['tipo' => 'error', 'mensaje' => 'Hubo un problema al guardar. Por favor intente de nuevo', 'error' => $e->getMessage()], 200);
        }
    }

    function imprimirFichaReporte($id)
    {
        $reporte = IncidenciaReporte::with('usuario')->where('id_incidencia_reporte', $id)->first();
        // $resultado = (new IncidenciaController)->mostrarIncidencia($reporte->id_incidencia);
        $incidencia = DB::table('cas.incidencia')
            ->select(
                'incidencia.*',
                'adm_contri.razon_social',
                'adm_contri.id_contribuyente',
                'adm_empresa.id_empresa',
                'adm_empresa.logo_empresa',
                'alm_req.codigo as codigo_requerimiento',
                'alm_req.concepto',
                'incidencia_tipo_falla.descripcion as tipo_falla_descripcion',
                'incidencia_tipo_servicio.descripcion as tipo_servicio_descripcion',
                'incidencia_tipo_garantia.descripcion as tipo_garantia_descripcion',
                'incidencia_modo.descripcion as modo_descripcion',
                'incidencia_medio.descripcion as medio_descripcion',
                'incidencia_atiende.descripcion as atiende_descripcion',
                'incidencia_estado.descripcion as estado_descripcion',
                'oportunidades.codigo_oportunidad',
                'incidencia_producto_tipo.descripcion as tipo_descripcion',
                DB::raw("(ubi_dpto.descripcion)||' '||(ubi_prov.descripcion)||' '||(ubi_dis.descripcion) as ubigeo_descripcion")
            )
            // ->leftjoin('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'incidencia.id_salida')
            // ->leftjoin('almacen.guia_ven', 'guia_ven.id_guia_ven', '=', 'mov_alm.id_guia_ven')
            // ->leftjoin('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'guia_ven.id_od')
            ->leftjoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'incidencia.id_requerimiento')
            ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
            ->leftjoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'incidencia.id_empresa')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'incidencia.id_contribuyente')
            ->leftjoin('contabilidad.adm_ctb_contac', 'adm_ctb_contac.id_datos_contacto', '=', 'incidencia.id_contacto')
            ->leftjoin('cas.incidencia_tipo_falla', 'incidencia_tipo_falla.id_tipo_falla', '=', 'incidencia.id_tipo_falla')
            ->leftjoin('cas.incidencia_tipo_servicio', 'incidencia_tipo_servicio.id_tipo_servicio', '=', 'incidencia.id_tipo_servicio')
            ->leftjoin('cas.incidencia_tipo_garantia', 'incidencia_tipo_garantia.id_tipo_garantia', '=', 'incidencia.id_tipo_garantia')
            ->leftjoin('cas.incidencia_modo', 'incidencia_modo.id_modo', '=', 'incidencia.id_modo')
            ->leftjoin('cas.incidencia_medio', 'incidencia_medio.id_medio', '=', 'incidencia.id_medio')
            ->leftjoin('cas.incidencia_atiende', 'incidencia_atiende.id_atiende', '=', 'incidencia.id_atiende')
            ->leftjoin('cas.incidencia_estado', 'incidencia_estado.id_estado', '=', 'incidencia.estado')
            ->leftjoin('configuracion.ubi_dis', 'ubi_dis.id_dis', '=', 'incidencia.id_ubigeo_contacto')
            ->leftjoin('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
            ->leftjoin('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')
            ->leftjoin('cas.incidencia_producto_tipo', 'incidencia_producto_tipo.id_tipo', '=', 'incidencia.id_tipo')
            ->where('incidencia.id_incidencia', $reporte->id_incidencia)
            ->first();

        $productos = IncidenciaProducto::where([['id_incidencia', '=', $reporte->id_incidencia], ['estado', '!=', 7]])
            ->get();

        $logo_empresa = ".$incidencia->logo_empresa";
        $fecha_registro =  (new Carbon($incidencia->fecha_registro))->format('d-m-Y');
        $hora_registro = (new Carbon($incidencia->fecha_registro))->format('H:i:s');

        $vista = View::make(
            'cas/fichasReporte/fichaReportePdf',
            compact(
                'incidencia',
                'logo_empresa',
                'productos',
                'reporte',
                'fecha_registro',
                'hora_registro'
            )
        )->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($vista);

        return $pdf->stream();
        return $pdf->download($incidencia->codigo . '.pdf');

        // return response()->json(['incidencia' => $incidencia, 'productos' => $productos, 'reporte' => $reporte]);
    }
    public function obtenerListadoGestionincidenciasDetalleExport($id_incidencia)
    {
        $lista = IncidenciaReporte::with('usuario', 'adjuntos')->where([
            ['id_incidencia', '=', $id_incidencia], ['estado', '!=', 7]
        ])->get();

        return $lista;
    }
    public function obtenerListadoIncidencias($id_incidencia)
    {
        $incidencia = DB::table('cas.incidencia')
            ->select(
                'incidencia_producto_tipo.descripcion as tipo',

                'incidencia_tipo_falla.descripcion as tipo_falla',
                'incidencia_modo.descripcion as modo',
                'incidencia_tipo_garantia.descripcion as tipo_garantia',
                'incidencia_tipo_servicio.descripcion as tipo_servicio',
                'incidencia_medio.descripcion as medio',
                'incidencia_atiende.descripcion as atiende',

                'incidencia.*',
                'adm_contri.razon_social',
                'adm_contri.id_contribuyente',
                'adm_empresa.id_empresa',
                'alm_req.codigo as codigo_requerimiento',
                'alm_req.concepto',
                // 'adm_ctb_contac.nombre',
                // 'adm_ctb_contac.telefono',
                // 'adm_ctb_contac.cargo',
                // 'adm_ctb_contac.direccion',
                'adm_ctb_contac.horario',
                'adm_ctb_contac.email',
                'oportunidades.codigo_oportunidad',
                'oc_propias_view.id_entidad',
                'ubi_dpto.descripcion as departamento_text',
                'ubi_prov.descripcion as provincia_text',
                'ubi_dis.descripcion as distrito_text',
                DB::raw("(ubi_dpto.descripcion)||' '||(ubi_prov.descripcion)||' '||(ubi_dis.descripcion) as ubigeo_descripcion")
            )
            // ->leftjoin('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'incidencia.id_salida')
            // ->leftjoin('almacen.guia_ven', 'guia_ven.id_guia_ven', '=', 'mov_alm.id_guia_ven')
            // ->leftjoin('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'guia_ven.id_od')
            ->leftjoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'incidencia.id_requerimiento')
            ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
            ->leftjoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
            ->leftJoin('mgcp_ordenes_compra.oc_propias_view', 'oc_propias_view.id_oportunidad', '=', 'cc.id_oportunidad')
            ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'incidencia.id_empresa')
            ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'incidencia.id_contribuyente')
            ->leftjoin('contabilidad.adm_ctb_contac', 'adm_ctb_contac.id_datos_contacto', '=', 'incidencia.id_contacto')
            ->leftjoin('configuracion.ubi_dis', 'ubi_dis.id_dis', '=', 'incidencia.id_ubigeo_contacto')
            ->leftjoin('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
            ->leftjoin('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')

            ->leftjoin('cas.incidencia_producto_tipo', 'incidencia_producto_tipo.id_tipo', '=', 'incidencia.id_tipo')

            ->leftjoin('cas.incidencia_tipo_falla', 'incidencia_tipo_falla.id_tipo_falla', '=', 'incidencia.id_tipo_falla')
            ->leftjoin('cas.incidencia_modo', 'incidencia_modo.id_modo', '=', 'incidencia.id_modo')
            ->leftjoin('cas.incidencia_tipo_garantia', 'incidencia_tipo_garantia.id_tipo_garantia', '=', 'incidencia.id_tipo_garantia')
            ->leftjoin('cas.incidencia_tipo_servicio', 'incidencia_tipo_servicio.id_tipo_servicio', '=', 'incidencia.id_tipo_servicio')
            ->leftjoin('cas.incidencia_medio', 'incidencia_medio.id_medio', '=', 'incidencia.id_medio')
            ->leftjoin('cas.incidencia_atiende', 'incidencia_atiende.id_atiende', '=', 'incidencia.id_atiende')

            ->where('incidencia.id_incidencia', $id_incidencia)
            ->first();
        return $incidencia;
    }
    public function clonarIncidencia(Request $request)
    {
        $incidencia = DB::table('cas.incidencia')
        ->select(
            'incidencia.*',
            'adm_contri.razon_social',
            'adm_contri.id_contribuyente',
            'adm_empresa.id_empresa',
            'alm_req.codigo as codigo_requerimiento',
            'alm_req.concepto',
            // 'adm_ctb_contac.nombre',
            // 'adm_ctb_contac.telefono',
            // 'adm_ctb_contac.cargo',
            // 'adm_ctb_contac.direccion',
            'adm_ctb_contac.horario',
            'adm_ctb_contac.email',
            'oportunidades.codigo_oportunidad',
            'oc_propias_view.id_entidad',
            DB::raw("(ubi_dpto.descripcion)||' '||(ubi_prov.descripcion)||' '||(ubi_dis.descripcion) as ubigeo_descripcion")
        )
        // ->leftjoin('almacen.mov_alm', 'mov_alm.id_mov_alm', '=', 'incidencia.id_salida')
        // ->leftjoin('almacen.guia_ven', 'guia_ven.id_guia_ven', '=', 'mov_alm.id_guia_ven')
        // ->leftjoin('almacen.orden_despacho', 'orden_despacho.id_od', '=', 'guia_ven.id_od')
        ->leftjoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'incidencia.id_requerimiento')
        ->leftJoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
        ->leftjoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
        ->leftJoin('mgcp_ordenes_compra.oc_propias_view', 'oc_propias_view.id_oportunidad', '=', 'cc.id_oportunidad')
        ->leftjoin('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'incidencia.id_empresa')
        ->leftjoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'incidencia.id_contribuyente')
        ->leftjoin('contabilidad.adm_ctb_contac', 'adm_ctb_contac.id_datos_contacto', '=', 'incidencia.id_contacto')
        ->leftjoin('configuracion.ubi_dis', 'ubi_dis.id_dis', '=', 'incidencia.id_ubigeo_contacto')
        ->leftjoin('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
        ->leftjoin('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')
        ->where('incidencia.id_incidencia', $request->id)
        ->first();


        // $mensaje = '';
        // $tipo = '';
        // return [$incidencia];exit;
        $yyyy = date('Y', strtotime("now"));
        $codigo = Incidencia::nuevoCodigoIncidencia($incidencia->id_empresa, $yyyy);

        $incidencia_new = new Incidencia();
        $incidencia_new->codigo = $codigo;
        $incidencia_new->fecha_reporte = $incidencia->fecha_reporte;
        $incidencia_new->id_requerimiento = $incidencia->id_requerimiento;
        $incidencia_new->id_responsable = $incidencia->id_responsable;
        $incidencia_new->id_salida = $incidencia->id_salida;
        $incidencia_new->id_empresa = $incidencia->id_empresa;
        $incidencia_new->sede_cliente = $incidencia->sede_cliente;
        $incidencia_new->factura = $incidencia->factura;
        $incidencia_new->id_contribuyente = $incidencia->id_contribuyente;
        $incidencia_new->id_contacto = $incidencia->id_contacto;
        $incidencia_new->usuario_final = $incidencia->usuario_final;
        $incidencia_new->id_tipo_falla = $incidencia->id_tipo_falla;
        $incidencia_new->id_tipo_servicio = $incidencia->id_tipo_servicio;
        $incidencia_new->id_medio = $incidencia->id_medio;
        $incidencia_new->conformidad = $incidencia->conformidad;
        $incidencia_new->equipo_operativo = ((isset($incidencia->equipo_operativo) && $incidencia->equipo_operativo == 'on') ? true : false);
        $incidencia_new->falla_reportada = $incidencia->falla_reportada;
        $incidencia_new->id_modo = $incidencia->id_modo;
        $incidencia_new->id_tipo_garantia = $incidencia->id_tipo_garantia;
        $incidencia_new->id_atiende = $incidencia->id_atiende;
        $incidencia_new->numero_caso = $incidencia->numero_caso;
        $incidencia_new->importe_gastado = $incidencia->importe_gastado;
        $incidencia_new->comentarios_cierre = $incidencia->comentarios_cierre;
        $incidencia_new->parte_reemplazada = $incidencia->parte_reemplazada;
        $incidencia_new->cliente = $incidencia->cliente;
        $incidencia_new->nro_orden = $incidencia->nro_orden;
        $incidencia_new->nombre_contacto = $incidencia->nombre_contacto;
        $incidencia_new->cargo_contacto = $incidencia->cargo_contacto;
        $incidencia_new->id_ubigeo_contacto = $incidencia->id_ubigeo_contacto;
        $incidencia_new->telefono_contacto = $incidencia->telefono_contacto;
        $incidencia_new->direccion_contacto = $incidencia->direccion_contacto;
        $incidencia_new->anio = $yyyy;
        $incidencia_new->estado = 1;
        $incidencia_new->fecha_registro = new Carbon();

        $incidencia_new->serie = $incidencia->serie;
        $incidencia_new->producto = $incidencia->producto;
        $incidencia_new->marca = $incidencia->marca;
        $incidencia_new->modelo = $incidencia->modelo;
        $incidencia_new->id_tipo = $incidencia->id_tipo;

        $incidencia_new->horario_contacto = $incidencia->horario_contacto;
        $incidencia_new->email_contacto = $incidencia->email_contacto;

        $incidencia_new->cdp = $incidencia->codigo_oportunidad;
        $incidencia_new->fecha_documento = $incidencia->fecha_documento;
        $incidencia_new->save();

        return response()->json([
            "success"=>true,
            "status"=>200,
            "data"=>$incidencia
        ]);
    }

    public function reporteAtencion(){
        $incidencia = Incidencia::select('incidencia.*', 'incidencia_estado.descripcion as estado_incidencia')
        // ->where('incidencia.estado','!=',7)
        ->where('incidencia.estado',3)
        ->join('cas.incidencia_estado', 'incidencia_estado.id_estado', '=', 'incidencia.estado')
        ->get();

        $data = array();
        foreach ($incidencia as $key => $value) {

            if(array_search($value->nro_orden, array_column($data, 'nro_orden')) === false){

                array_push($data,array(
                    "nro_orden"=>$value->nro_orden,
                    "fecha_inicio"=>'-',
                    "fecha_final"=>'-',
                    "diferencia_dias"=>0,
                    "incidencias"=>array(),
                ));

            }
            $index = array_search($value->nro_orden, array_column($data, 'nro_orden'));
            array_push($data[$index]['incidencias'], $value);

        }
        $json = array();
        $fecha_menor = '';
        $fecha_mayor = '';
        $dias = '';

        foreach ($data as $key => $value) {
            // --------- fechas -------------------
            $fecha_inicial = '';
            $fecha_final = '';
            foreach ($value['incidencias'] as $key_inci => $incidente) {

                if($key_inci==0){
                    $fecha_inicial = $incidente->fecha_reporte;
                    $fecha_final = $incidente->fecha_cierre;
                }else{

                    if($fecha_inicial > $incidente->fecha_reporte){
                        $fecha_inicial = $incidente->fecha_reporte;
                    }

                    if( $fecha_final < $incidente->fecha_cierre ){
                        $fecha_final = $incidente->fecha_cierre;
                    }
                }



            }

            $diferencia = Carbon::parse($fecha_inicial)->diffInDays(Carbon::parse($fecha_final));

            $data[$key]['fecha_inicio'] = $fecha_inicial;
            $data[$key]['fecha_final'] = $fecha_final;
            $data[$key]['diferencia_dias'] = $diferencia;

            if( $key == 0 ){

                $fecha_menor = $fecha_inicial;
                $fecha_mayor = $fecha_final;

            }else{

                if($fecha_menor > $fecha_inicial){
                    $fecha_menor = $fecha_inicial;
                }

                if($fecha_mayor < $fecha_final){
                    $fecha_mayor = $fecha_final;
                }
                $dias_general = Carbon::parse($fecha_menor)->diffInDays(Carbon::parse($fecha_mayor));
                $dias = $dias_general;
            }

        }
        // return $data;
        return Excel::download(new HorasAtencionExport(json_encode($data), $fecha_menor, $fecha_mayor, $dias), 'Reporte-horas-incidencia.xlsx');
    }


    public function generarFiltrosIncidencias(Request $request)
    {
        $año =$request->anio;
        if($año!=null){
            $request->session()->put('clPeriodoIncidencia', $año);
        }

        return response()->json('filtros', 200);
    }
}
