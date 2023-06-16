<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Models\Configuracion\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function validarClave()
    {
        $usuario = Usuario::where('id_usuario', Auth::user()->id_usuario)->first();
        $success = true;
        $hoy = date("Y-m-d");

        if ($usuario->renovar == true) {
            if (date("Y-m-d", strtotime($usuario->fecha_registro."+ 45 days")) > $hoy) {
                $success = false;
            }
        } else {
            $success = false;
        }
        return response()->json(["success"=> $success, "status"=> 200]);
    }
    
    public function modificarClave(Request $request)
    {
        $success = false;
        if ($request->clave === $request->repita_clave) {

            $usuario = Usuario::find(Auth::user()->id_usuario);
            $usuario->clave = StringHelper::encode5t($request->clave);
            $usuario->password = Hash::make($request->clave);
            $usuario->fecha_registro = Carbon::now();
            $usuario->save();

            if ($usuario) {
                $success=true;
            }
        }
        return response()->json(["success" => $success, "status" => 200]);
    }
}
