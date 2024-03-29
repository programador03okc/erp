<?php

namespace App\Http\Controllers\Proyectos\Opciones;

use App\Http\Controllers\Comercial\ClienteController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Proyectos\Catalogos\GenericoController;
use App\Http\Controllers\ProyectosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpcionesController extends Controller
{
    function view_opcion(){
        $clientes = ClienteController::mostrar_clientes_cbo();
        $unid_program = GenericoController::mostrar_unid_program_cbo();
        $tipos = GenericoController::mostrar_tipos_cbo();
        $empresas = GenericoController::mostrar_empresas_cbo();
        $modalidades = GenericoController::mostrar_modalidad_cbo();
        $tp_contribuyente = GenericoController::tp_contribuyente_cbo();
        $sis_identidad = GenericoController::sis_identidad_cbo();
        return view('proyectos.opcion.opcion', compact('clientes','unid_program',
        'tipos','empresas','modalidades','tp_contribuyente','sis_identidad'));
    }

    //OPCION COMERCIAL
    public function listar_opciones()
    {
        $data = DB::table('proyectos.proy_op_com')
            ->select('proy_op_com.*', 'proy_tp_proyecto.descripcion as des_tp_proyecto',
            'proy_unid_program.descripcion as des_program','adm_contri.razon_social',
            'adm_contri.id_contribuyente','sis_usua.nombre_corto','proy_modalidad.descripcion as des_modalidad',
            'adm_estado_doc.estado_doc')
            ->join('proyectos.proy_tp_proyecto','proy_tp_proyecto.id_tp_proyecto','=','proy_op_com.tp_proyecto')
            ->leftjoin('proyectos.proy_unid_program','proy_unid_program.id_unid_program','=','proy_op_com.unid_program')
            ->leftjoin('proyectos.proy_modalidad','proy_modalidad.id_modalidad','=','proy_op_com.modalidad')
            ->join('comercial.com_cliente','com_cliente.id_cliente','=','proy_op_com.cliente')
            ->join('contabilidad.adm_contri','adm_contri.id_contribuyente','=','com_cliente.id_contribuyente')
            ->join('configuracion.sis_usua','sis_usua.id_usuario','=','proy_op_com.elaborado_por')
            ->join('administracion.adm_estado_doc','adm_estado_doc.id_estado_doc','=','proy_op_com.estado')
                ->where([['proy_op_com.estado', '!=', 7]])
                ->orderBy('proy_op_com.codigo','desc')
                ->get();
        $output['data'] = $data;
        return response()->json($output);
    }

    public function nextOpcion($id_emp,$fecha)
    {
        // $mes = date('m',strtotime($fecha));
        $yyyy = date('Y',strtotime($fecha));
        $anio = date('y',strtotime($fecha));
        $code_emp = '';
        $result = '';

        $emp = DB::table('administracion.adm_empresa')
        ->select('codigo')
        ->where('id_empresa', '=', $id_emp)
        ->get();
        foreach ($emp as $rowEmp) {
            $code_emp = $rowEmp->codigo;
        }
        $data = DB::table('proyectos.proy_op_com')
                ->where('id_empresa', '=', $id_emp)
                // ->whereMonth('fecha_emision', '=', $mes)
                ->whereYear('fecha_emision', '=', $yyyy)
                ->count();

        $number = GenericoController::leftZero(3,$data+1);
        $result = "OP-".$code_emp."-".$anio."".$number;

        return $result;
    }

    public function guardar_opcion(Request $request)
    {
        $id_usuario = Auth::user()->id_usuario;
        $codigo = $this->nextOpcion($request->id_empresa, $request->fecha_emision);
        $id_op_com = DB::table('proyectos.proy_op_com')->insertGetId(
            [
                'tp_proyecto' => $request->tp_proyecto,
                'id_empresa' => $request->id_empresa,
                'descripcion' => strtoupper(trim($request->descripcion)),
                'cliente' => $request->cliente,
                'unid_program' => ($request->unid_program > 0 ? $request->unid_program : null),
                'cantidad' => $request->cantidad,
                'modalidad' => ($request->modalidad > 0 ? $request->modalidad : null),
                'fecha_emision' => $request->fecha_emision,
                'codigo' => $codigo,
                'elaborado_por' => $id_usuario,
                'estado' => 1,
                'fecha_registro' => date('Y-m-d H:i:s')
            ],
                'id_op_com'
            );

        return response()->json($id_op_com);
    }

    public function update_opcion(Request $request)
    {
        $data = DB::table('proyectos.proy_op_com')->where('id_op_com', $request->id_op_com)
            ->update([
                'tp_proyecto' => $request->tp_proyecto,
                'id_empresa' => $request->id_empresa,
                'descripcion' => strtoupper(trim($request->descripcion)),
                'cliente' => $request->cliente,
                'unid_program' => ($request->unid_program > 0 ? $request->unid_program : null),
                'cantidad' => $request->cantidad,
                'modalidad' => ($request->modalidad > 0 ? $request->modalidad : null),
                'fecha_emision' => $request->fecha_emision,
            ]);

        return response()->json($data);
    }

    public function anular_opcion(Request $request, $id)
    {
        $data = DB::table('proyectos.proy_op_com')
                ->where('id_op_com',$id)
                ->update([ 'estado' => 7 ]);
        return response()->json($data);
    }


    public function listar_opciones_sin_presint()
    {
        $opciones = DB::table('proyectos.proy_op_com')
            ->select('proy_op_com.*', 'proy_tp_proyecto.descripcion as des_tp_proyecto',
            'proy_unid_program.descripcion as des_program','adm_contri.razon_social',
            'adm_contri.id_contribuyente','sis_usua.nombre_corto','proy_modalidad.descripcion as des_modalidad')
            ->join('proyectos.proy_tp_proyecto','proy_tp_proyecto.id_tp_proyecto','=','proy_op_com.tp_proyecto')
            ->leftjoin('proyectos.proy_unid_program','proy_unid_program.id_unid_program','=','proy_op_com.unid_program')
            ->leftjoin('proyectos.proy_modalidad','proy_modalidad.id_modalidad','=','proy_op_com.modalidad')
            ->join('comercial.com_cliente','com_cliente.id_cliente','=','proy_op_com.cliente')
            ->join('contabilidad.adm_contri','adm_contri.id_contribuyente','=','com_cliente.id_contribuyente')
            ->join('configuracion.sis_usua','sis_usua.id_usuario','=','proy_op_com.elaborado_por')
                ->where([['proy_op_com.estado', '!=', 7]])
                ->orderBy('proy_op_com.codigo','desc')
                ->get();
        $output['data'] = $opciones;
        return response()->json($output);
    }

}
