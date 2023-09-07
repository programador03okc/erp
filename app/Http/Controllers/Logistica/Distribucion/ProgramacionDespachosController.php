<?php

namespace App\Http\Controllers\Logistica\Distribucion;

use App\Http\Controllers\Controller;
use App\models\Configuracion\AccesosUsuarios;
use App\Models\Logistica\ProgramacionDespacho;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramacionDespachosController extends Controller
{
    //
    public function lista()
    {
        $data = ProgramacionDespacho::orderBy('fecha_registro','DESC')->where('aplica_cambios','t')->paginate(4);
        $array_fechas = array();
        foreach ($data as $key => $value) {
            if (!in_array($value->fecha_registro, $array_fechas)) {
                array_push($array_fechas,$value->fecha_registro);
            }
        }

        $array_accesos=[];
        $accesos_usuario = AccesosUsuarios::where('estado',1)->where('id_usuario',Auth::user()->id_usuario)->get();
        foreach ($accesos_usuario as $key => $value) {
            array_push($array_accesos,$value->id_acceso);
        }

        return view('almacen.distribucion.programacio_despachos.lista', get_defined_vars());
    }
    public function listarODI(Request $request) {
        // en lista la odi
        // if($request->ajax()){
            $data = ProgramacionDespacho::orderBy('fecha_registro','DESC')->where('aplica_cambios','t')->paginate(4);
            $array_fechas = array();
            foreach ($data as $key => $value) {
                if (!in_array($value->fecha_registro, $array_fechas)) {
                    array_push($array_fechas,$value->fecha_registro);
                }
            }
            return response()->json(["data"=>$data,"fechas"=>$array_fechas],200);
        // }
        return response()->json(["success"=>false],404);
    }
    public function listarODE(Request $request) {
        // en lista la odi
        // if($request->ajax()){
            $data = ProgramacionDespacho::orderBy('fecha_registro','DESC')->where('aplica_cambios','f')->paginate(4);
            $array_fechas = array();
            foreach ($data as $key => $value) {
                if (!in_array($value->fecha_registro, $array_fechas)) {
                    array_push($array_fechas,$value->fecha_registro);
                }
            }
            return response()->json(["data"=>$data,"fechas"=>$array_fechas],200);
        // }
        return response()->json(["success"=>false],404);
    }
    public function guardar(Request $request) {

        try {
            $data = ProgramacionDespacho::firstOrNew(['id' => $request->id]);
                $data->titulo           = $request->titulo;
                $data->descripcion      = $request->descripcion;

                $data->fecha_programacion   = $request->fecha_programacion;

                $data->aplica_cambios       = $request->aplica_cambios;

                if ((int) $request->id == 0) {
                    $data->estado               = 1;
                    $data->fecha_registro   = date('Y-m-d');
                    $data->created_id           = Auth()->user()->id_usuario;

                }else{
                    $data->updated_id           = Auth()->user()->id_usuario;
                }
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
    public function reprogramar(){
        $data = ProgramacionDespacho::where('reprogramado','f')->get();
        foreach ($data as $key => $value) {
            if (date('Y-m-d') > $value->fecha_programacion) {

                $item = new ProgramacionDespacho();
                    $item->titulo           = $value->titulo;
                    $item->descripcion      = $value->descripcion;
                    $item->fecha_programacion   = date('Y-m-d');
                    $item->aplica_cambios       = $value->aplica_cambios;
                    $item->estado               = 1;
                    $item->fecha_registro       = date('Y-m-d');
                    $item->reprogramacion_id    = $value->id;
                $item->save();

                $programacion = ProgramacionDespacho::find($value->id);
                $programacion->reprogramado = true;
                $programacion->save();

            }

        }
        return response()->json(["data"=>$data,"success"=>true],200);
    }
}
