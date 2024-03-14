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
            if ($usuario->fecha_renovacion > $hoy) {
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
            $usuario->fecha_renovacion = date("Y-m-d", strtotime(Carbon::now()."+ 60 days"));
            $usuario->save();

            if ($usuario) {
                $success=true;
            }
        }
        return response()->json(["success" => $success, "status" => 200]);
    }
}
