<?php

namespace App\Http\Controllers\ClasificacionSap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClasificacionSap\CategoriaSap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 

class CategoriaSapController extends Controller
{
    public static function mostrar()
    {
        $data = DB::table('clasificacion_sap.categoria')
            ->select('categoria.*')
            ->orderBy('descripcion')
            ->get();
        return $data;
    }

    public function mostrarCategoriasSapPorGrupo($id_grupo_sap){
        $data = CategoriaSap::where([['grupo_id', '=', $id_grupo_sap]])
        ->orderBy('descripcion')
        ->get();
    return response()->json($data);
    }
}