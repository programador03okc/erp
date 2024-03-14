<?php

namespace App\Http\Controllers\Kardex;

use App\Http\Controllers\Controller;
use App\Imports\ProductosKardexImport;
use App\Models\kardex\Producto;
use App\Models\kardex\ProductoDetalle;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductosController extends Controller
{
    //
    public function lista(){
        return view('kardex.productos.productos', get_defined_vars());
    }
    public function listar(){

        $data = Producto::all();
        return DataTables::of($data)
        ->addColumn('accion', function ($data) {
            return '<div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a class="text-primary ver-series" href="javascript:void(0)" data-id="'.$data->id.'">Ver series</a></li>
                </ul>
            </div>';
        })->rawColumns(['accion'])->make(true);;
    }

    public function cargaInicial(Request $request){
        try {
            $array = Excel::toArray(new ProductosKardexImport, request()->file('carga_inicial'));

            foreach ($array[0] as $key => $value) {
                if($key !== 0){

                    $producto = Producto::where('codigo_agil', (int)$value[3])->first();
                    if(!$producto){
                        $producto = new Producto();
                        $producto->codigo_agil      = (int)$value[3];
                        $producto->codigo_softlink  = $value[4];
                        $producto->descripcion      = $value[5];
                        $producto->part_number      = $value[6];
                        $producto->almacen          = $value[7];
                        $producto->empresa          = $value[8];
                        $producto->clasificacion    = $value[9];
                        $producto->estado_kardex    = $value[10];
                        $producto->ubicacion        = $value[11];
                        $producto->responsable      = $value[12];
                        $producto->fecha_registro   = date('Y-m-d H:i:s');
                        $producto->anual            = $value[16];
                        $producto->estado           = 1;
                        $producto->save();
                    }

                    $serie = ProductoDetalle::where('serie',$value[1])->first();


                    if (!$serie) {
                        $monto = strrpos ($value[18], "$");
                        $tipo_moneda = ($monto?1:2); // 1 es dolar y el 2 soles
                        $precio = str_replace("$", "0", $value[18]);

                        $serie = new ProductoDetalle();
                        $serie->serie           = $value[1];
                        $serie->precio          = (float) $value[13];
                        $serie->tipo_moneda     = $tipo_moneda;
                        $serie->precio_unitario = (float) $precio;
                        $serie->producto_id     = $producto->id;
                        $serie->fecha           = $this->formatoFechaExcel($value[14]);
                        $serie->estado          = 1;
                        $serie->save();
                    }

                }
            }

            return response()->json([
                "titulo"=>"Éxito",
                "mensjae"=>"se importo con éxito",
                "tipo"=>"success"
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "titulo"=>"Error",
                "mensjae"=>"Comuniquese con el su área de TI",
                "tipo"=>"error"
            ],200);
        }

    }
    public function formatoFechaExcel($numero){
        return gmdate("Y-m-d", (((int)$numero - 25569) * 86400));
    }

    public function listarSeries(Request $request){

        $data = ProductoDetalle::where('producto_id',$request->id)->get();
        return DataTables::of($data)
        ->addColumn('accion', function ($data) {
            return '<div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a class="text-primary ver-series" href="javascript:void(0)" data-id="'.$data->id.'">Ver series</a></li>
                </ul>
            </div>';
        })->rawColumns(['accion'])->make(true);;
    }
    public function actualizarProductos(){

        return response()->json([
            "tipo"=>true
        ],200);
    }
}
