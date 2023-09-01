<?php

namespace App\Http\Controllers\Logistica\Distribucion;

use App\Http\Controllers\Controller;
use App\Models\Logistica\ProgramacionDespacho;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramacionDespachosController extends Controller
{
    //
    public function lista()
    {
        return view('almacen.distribucion.programacio_despachos.lista', get_defined_vars());
    }
    public function listarODI(Request $request) {
        // en lista la odi
        if($request->ajax()){
            $data = ProgramacionDespacho::orderBy('fecha_registro','DESC')->where('aplica_cambios','t')->paginate(2);
            $array_fechas = array();
            foreach ($data as $key => $value) {
                if (!in_array($value->fecha_registro, $array_fechas)) {
                    array_push($array_fechas,$value->fecha_registro);
                }
            }
            return response()->json(["data"=>$data,"fechas"=>$array_fechas],200);
        }
        return response()->json(["success"=>false],404);
    }
    public function listarODE(Request $request) {
        // en lista la odi
        if($request->ajax()){
            $data = ProgramacionDespacho::orderBy('fecha_registro','DESC')->where('aplica_cambios','f')->paginate(2);
            $array_fechas = array();
            foreach ($data as $key => $value) {
                if (!in_array($value->fecha_registro, $array_fechas)) {
                    array_push($array_fechas,$value->fecha_registro);
                }
            }
            return response()->json(["data"=>$data,"fechas"=>$array_fechas],200);
        }
        return response()->json(["success"=>false],404);
    }
    public function guardar(Request $request) {

        try {
            $data = ProgramacionDespacho::firstOrNew(['id' => $request->id]);
                $data->titulo           = $request->titulo;
                $data->descripcion      = $request->descripcion;
                $data->fecha_registro   = date('Y-m-d');
                $data->fecha_programacion   = $request->fecha_programacion;
                $data->estado               = 1;
                $data->aplica_cambios       = $request->aplica_cambios;
                $data->created_id           = Auth()->user()->id_usuario;
            $data->save();
            return response()->json(["titulo"=>"Éxito", "mensaje"=>"Se guardo con éxito.", "tipo"=>'success', "data"=>$data,"success"=>true],200);
        } catch (Exception $e) {
            return response()->json(["titulo"=>"Alerta", "mensaje"=>"Comuníquese con su area de TI.", "tipo"=>'warning',"success"=>false],200);
        }

    }
    public function editar($id){
        $data = ProgramacionDespacho::find($id);
        return response()->json(["success"=>true, "data"=>$data],200);
    }
    public function eliminar($id){
        $data = ProgramacionDespacho::find($id);
        $data->deleted_at = date('Y-m-d H:i:s');
        $data->updated_id = Auth()->user()->id_usuario;
        $data->deleted_id = Auth()->user()->id_usuario;
        $data->deleted_at = date('Y-m-d H:i:s');
        $data->save();
        return response()->json(["titulo"=>"Éxito", "mensaje"=>"Se guardo con éxito.", "tipo"=>'success', "success"=>true],200);
    }
}
