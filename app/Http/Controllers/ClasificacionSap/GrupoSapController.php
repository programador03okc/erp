<?php

namespace App\Http\Controllers\ClasificacionSap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 

class GrupoSapController extends Controller
{

    public static function mostrar()
    {
        $data = DB::table('clasificacion_sap.grupo')
            ->select('grupo.*')
            ->orderBy('descripcion')
            ->get();
        return $data;
    }

}