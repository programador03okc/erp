<?php

namespace App\Http\Controllers\ClasificacionSap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClasificacionSap\SubCategoriaSap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 

class SubcategoriaSapController extends Controller
{
    public static function mostrar()
    {
        $data = DB::table('clasificacion_sap.subcategoria')
            ->select('subcategoria.*')
            ->orderBy('descripcion')
            ->get();
        return $data;
    }

    public function mostrarSubCategoriasSapPorCategoria($id_categoria_sap){
        $data = SubCategoriaSap::where('categoria_id', '=', $id_categoria_sap)
            ->orderBy('descripcion')
            ->get();
        return response()->json($data);
    }
}