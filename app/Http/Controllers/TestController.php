<?php

namespace App\Http\Controllers;

use App\Models\Comercial\Cliente;
use App\Models\contabilidad\ContribuyenteView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{

    public function clientes() {
        $data = ContribuyenteView::all();
        return response()->json($data, 200);
    }
    
    public function actualizarClaves()
    {
        $lista = User::all();
        $data = [];

        foreach ($lista as $key) {
            $nuevaClave = $this->decode5t($key->clave);
            $actualizar = User::find($key->id_usuario);
                $actualizar->password = Hash::make($nuevaClave);
            $actualizar->save();
            $data[] = ['nombre' => $key->nombre_corto, 'clave' => $key->clave, 'decode' => $nuevaClave];
        }
        return response()->json($data, 200);
    }

    public function decode5t($str)
    {
        for ($i = 0; $i < 5; $i++) {
            $str = base64_decode(strrev($str));
        }
        return $str;
    }

    public function encriptar() {
        $clave1 = Hash::make('Inicio');
        $clave2 = bcrypt('Inicio');
        $clave3 = password_hash('Inicio', PASSWORD_DEFAULT);
        return response()->json(array('hash' => $clave1, 'bcrypt' => $clave2, 'php' => $clave3), 200);
    }
}
