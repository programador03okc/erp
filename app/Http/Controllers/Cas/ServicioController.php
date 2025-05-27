<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Models\Cas\Servicio;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Administracion\Empresa;
use App\Models\Cas\AtiendeIncidencia;
use App\Models\Cas\CasMarca;
use App\Models\Cas\CasModelo;
use App\Models\Cas\CasProducto;
use App\Models\Cas\IncidenciaEstado;
use App\Models\Cas\IncidenciaProductoTipo;
use App\Models\Cas\MedioReporte;
use App\Models\Cas\ModoIncidencia;
use App\Models\Cas\TipoFalla;
use App\Models\Cas\TipoGarantia;
use App\Models\Cas\TipoServicio;
use App\Models\Configuracion\Usuario;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

class ServicioController extends Controller
{
    //
    function lista() {
        return view('cas.servicios.lista', get_defined_vars());
    }
    function listar() {
        $data     = Servicio::where('estado',1)->get();
        //$tipo_cambio = TipoCambio::orderBy('name', 'desc')->first();
        return DataTables::of($data)
        ->addColumn('estado_doc', function ($data) {
            $estado = IncidenciaEstado::find($data->estado);
            return '<span class="badge badge-'.$estado->bootstrap_color.'">'.$estado->descripcion.'</span>';

        })->addColumn('responsable', function ($data) {
            return Usuario::find($data->id_responsable)->nombre_corto;
        })->addColumn('empresa', function ($data) {
            return Empresa::find($data->id_empresa)->contribuyente->razon_social;
        })->addColumn('accion', function ($data) {
            return
            '<div class="btn-group" role="group">

                <a class="cerrar btn btn-primary boton" data-toggle="tooltip"
                data-placement="bottom" data-id="'.$data->id.'" title="Editar Servicio" href="'.route('cas.servicios.editar', $data->id).'">
                <i class="fa fa-edit"></i></a>
                '.
                    (!$data->fecha_cierre ? '<button type="button" class="cerrar btn btn-warning boton" data-toggle="tooltip" data-placement="bottom" data-id="'. $data->id.'" title="Fecha de cierre" > <i class="fa fa-calendar"></i></button>' : '' )

                .'


                <button type="button" class="cancelar btn btn-danger boton" data-toggle="tooltip"
                data-placement="bottom" data-id="'.$data->id.'" title="Cancelar Servicio" >
                <i class="fa fa-trash"></i></button>

                <a href="'.route('cas.servicios.pdf',["id"=>$data->id]).'" class="pdf btn btn-default boton" data-toggle="tooltip"
                data-placement="bottom" data-id="'.$data->id.'" title="Reporte PDF" target="_blank">
                <i class="fa fa-file-pdf"></i></a>
            </div>';
        })->rawColumns(['accion','estado_doc'])->make(true);
    }
    public function crear($id=0) {
        $empresas = Empresa::mostrar();
        $usuarios = Usuario::join('configuracion.usuario_rol', 'usuario_rol.id_usuario', '=', 'sis_usua.id_usuario')
            ->where([['sis_usua.estado', '=', 1], ['usuario_rol.id_rol', '=', 20], ['usuario_rol.estado', '=', 1]])->get();

        $cas_marca = CasMarca::where('estado',1)->orderBy('descripcion','ASC')->get();
        $cas_modelo = CasModelo::where('estado',1)->orderBy('descripcion','ASC')->get();
        $cas_producto = CasProducto::where('estado',1)->orderBy('descripcion','ASC')->get();

        $tiposProducto = IncidenciaProductoTipo::where('estado', 1)->get();
        $tipoFallas = TipoFalla::where('estado', 1)->get();
        $modos = ModoIncidencia::where('estado', 1)->get();
        $tiposGarantia = TipoGarantia::where('estado', 1)->get();
        $tipoServicios = TipoServicio::where('estado', 1)->get();
        $medios = MedioReporte::where('estado', 1)->get();
        $atiende = AtiendeIncidencia::where('estado', 1)->get();

        // $dias_atencion = ();
        $servicio = array();
        $ubigeo = "";
        if($id>0){
            $servicio = Servicio::find($id);
            $ubigeo_first = DB::table('configuracion.ubi_dis')
            ->select('ubi_dis.*', 'ubi_prov.descripcion as provincia', 'ubi_dpto.descripcion as departamento')
            ->join('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
            ->join('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')
            ->where('ubi_dis.id_dis', $servicio->id_ubigeo_contacto)
            ->first();
            $ubigeo = $ubigeo_first->descripcion.' - '.$ubigeo_first->provincia.' - '.$ubigeo_first->departamento;
        }
        return view('cas.servicios.formulario', get_defined_vars());
    }
    public function guardar(Request $request) {
        $mensaje = '';
        $tipo = '';
        $yyyy = date('Y',  strtotime($request->fecha_documento));
        // $servicio = new Servicio();
        // return Servicio::nuevoCorrelativo($request->id_empresa, $request->fecha_documento);
        $servicio = Servicio::firstOrNew(
            ['id' => $request->id_servicio],
        );
            if($request->id_servicio ==0){
                $servicio->codigo = Servicio::nuevoCodigo($request->id_empresa, $request->fecha_documento);
                $servicio->correlativo = Servicio::nuevoCorrelativo($request->id_empresa, $request->fecha_documento);
                $servicio->fecha_registro = new Carbon();
            }


            $servicio->fecha_reporte = $request->fecha_reporte;
            $servicio->id_requerimiento = $request->id_requerimiento;
            $servicio->id_responsable = $request->id_responsable;
            $servicio->id_salida = $request->id_mov_alm;
            $servicio->id_empresa = $request->id_empresa;
            $servicio->sede_cliente = $request->sede_cliente;
            $servicio->factura = $request->factura;
            $servicio->fecha_aceptacion = $request->fecha_aceptacion;
            $servicio->id_contribuyente = $request->id_contribuyente;
            $servicio->id_contacto = $request->id_contacto;
            $servicio->usuario_final = $request->usuario_final;
            $servicio->id_tipo_falla = $request->id_tipo_falla;
            $servicio->id_tipo_servicio = $request->id_tipo_servicio;
            $servicio->id_medio = $request->id_medio;
            $servicio->conformidad = $request->conformidad;
            $servicio->equipo_operativo = ((isset($request->equipo_operativo) && $request->equipo_operativo == 'on') ? true : false);
            $servicio->falla_reportada = $request->falla_reportada;
            $servicio->id_modo = $request->id_modo;
            $servicio->id_tipo_garantia = $request->id_tipo_garantia;
            $servicio->id_atiende = $request->id_atiende;
            $servicio->numero_caso = $request->numero_caso;
            $servicio->importe_gastado = $request->importe_gastado;
            $servicio->comentarios_cierre = $request->comentarios_cierre;
            $servicio->parte_reemplazada = $request->parte_reemplazada;
            $servicio->cliente = $request->cliente_razon_social;
            $servicio->nro_orden = $request->nro_orden;
            $servicio->nombre_contacto = $request->nombre_contacto;
            $servicio->cargo_contacto = $request->cargo_contacto;
            $servicio->id_ubigeo_contacto = $request->id_ubigeo_contacto;
            $servicio->telefono_contacto = $request->telefono_contacto;
            $servicio->direccion_contacto = $request->direccion_contacto;
            $servicio->anio = $yyyy;
            $servicio->estado = 1;


            $servicio->serie = $request->serie;
            $servicio->producto = $request->producto;
            $servicio->marca = $request->marca;
            $servicio->modelo = $request->modelo;
            $servicio->id_tipo = $request->id_tipo;

            $servicio->horario_contacto = $request->horario_contacto;
            $servicio->email_contacto = $request->email_contacto;
            $servicio->cdp = $request->cdp;

            // $servicio->region = $request->region;
            $servicio->descripcion_accion = $request->descripcion_accion;
            // $servicio->dias_atencion = $request->dias_atencion;

            $servicio->hora_fin = $request->hora_fin;
            $servicio->hora_inicio = $request->hora_inicio;
            $servicio->hora_llegada = $request->hora_llegada;
            $servicio->boletines = $request->boletines;
            $servicio->manipulacion_danos = $request->manipulacion_danos;
            $servicio->equipo_limpo = $request->equipo_limpo;
            $servicio->golpes = $request->golpes;
            $servicio->desgaste = $request->desgaste;
            $servicio->ensamblado = $request->ensamblado;
            $servicio->accesorios_completos = $request->accesorios_completos;
            $servicio->fisico_detectado = $request->fisico_detectado;
            $servicio->estado_servicio = $request->estado_servicio;
            $servicio->bios_actualizada = $request->bios_actualizada;
            $servicio->bios_actual = $request->bios_actual;
            $servicio->part_number = $request->part_number;
            $servicio->nro_orden_trabajo = $request->nro_orden_trabajo;
        $servicio->save();

        return response()->json([
            'status' => true,
            "title"=> "Éxito",
            "text"=> "Se guardo correctamente el servicio",
            "icon"=> "success",
        ]);
    }
    public function guardarFechaCierre(Request $request) {

        $fechaFinal = new DateTime($request->fecha_cierre);
        $servicio = Servicio::find($request->id_servicio);
        $fechaInicio = new DateTime($servicio->fecha_aceptacion);

        $dias_atencion = $fechaInicio->diff($fechaFinal);

        $servicio->fecha_cierre = $request->fecha_cierre;
        $servicio->dias_atencion = $dias_atencion->days;
        $servicio->estado = 3;
        $servicio->save();

        return response()->json([
            'status' => true,
            "title"=> "Éxito",
            "text"=> "Se guardo correctamente la fecha de cierre",
            "icon"=> "success",
        ]);
    }
    public function cancelar($id_servicio) {
        $servicio = Servicio::find($id_servicio);
        $servicio->estado = 4;
        $servicio->save();

        return response()->json([
            'status' => true,
            "title"=> "Éxito",
            "text"=> "Se canceló correctamente el servicio",
            "icon"=> "success",
        ]);

    }
    public function pdf($id){
        $servicio = Servicio::find($id);
        $usuarios = Usuario::find($servicio->id_responsable);
        // return response()->json([
        //     'data' => $servicio,
        //     "title"=> "Éxito",
        // ]);
        $logo = Empresa::where('id_empresa',$servicio->id_empresa)->first();
        $logo_empresa = ".$logo->logo_empresa";
        $caritas = "images/caras.png";
        $lenovo = "images/lenobo.png";
        // return $caritas;
        $vista = View::make('cas/fichasReporte/servicio', get_defined_vars())->render();
        // return $logo;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($vista);

        return $pdf->stream();
        return $pdf->download('prueba1.pdf');
    }

}
