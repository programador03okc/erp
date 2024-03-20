<?php

namespace App\Http\Controllers\Kardex;

use App\Http\Controllers\Controller;
use App\Imports\ProductosKardexImport;
use App\Models\kardex\Producto;
use App\Models\kardex\ProductoDetalle;
use App\Models\softlink\Movimiento;
use App\Models\softlink\MovimientoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        ->addColumn('cantidad', function ($data) {
            return ProductoDetalle::where('producto_id', $data->id)->where('disponible','t')->count();
        })
        ->addColumn('habilitado_estado', function ($data) {
            $status_color = ($data->habilitado=='t'?'success':'danger');
            $status_text = ($data->habilitado=='t'?'Habilitado':'Deshabilitado ');
            return '<span class="label label-'.$status_color.'">'.$status_text.'</span>';
        })
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
        })->rawColumns(['accion','habilitado_estado'])->make(true);;
    }

    public function cargaInicial(Request $request){
        // try {
            $array = Excel::toArray(new ProductosKardexImport, request()->file('carga_inicial'));

            foreach ($array[0] as $key => $value) {
                if($key !== 0){
                    // return $value;
                    $producto = Producto::firstOrNew([ 'codigo_softlink'=> $value[4] ]);
                    // return $value;
                    // if($producto){
                    //     $producto = new Producto();
                        $producto->codigo_agil      = (int)$value[3];
                        $producto->codigo_softlink  = $value[4];
                        $producto->descripcion      = $value[6];
                        $producto->part_number      = $value[5];
                        $producto->almacen          = $value[7];
                        $producto->empresa          = $value[8];
                        $producto->clasificacion    = $value[9];
                        $producto->estado_kardex    = $value[10];
                        $producto->ubicacion        = $value[11];
                        $producto->responsable      = $value[12];
                        $producto->habilitado       = true;
                        if(!Producto::where('codigo_softlink',$value[4])->first()){
                            $producto->fecha_registro   = date('Y-m-d H:i:s');
                        }

                        $producto->anual            = $value[16];
                        $producto->estado           = 1;
                        $producto->save();
                    // }
                    // return $value;
                    // $serie = ProductoDetalle::where('serie',$value[1])->first();
                    $serie = ProductoDetalle::firstOrNew(['serie'=>$value[1], 'producto_id'=>$producto->id]);

                    // if (!$serie) {
                        $monto = strrpos ($value[18], "$");
                        $tipo_moneda = ($monto?1:2); // 1 es dolar y el 2 soles
                        $precio = str_replace("$", "0", $value[18]);

                        // $serie = new ProductoDetalle();
                        $serie->serie           = $value[1];
                        $serie->precio          = (float) $value[13];
                        $serie->tipo_moneda     = $tipo_moneda;
                        $serie->precio_unitario = (float) $precio;
                        $serie->producto_id     = $producto->id;
                        $serie->fecha           = $this->formatoFechaExcel($value[14]);
                        $serie->estado          = 1;
                        $serie->disponible      = ($value[1] == $value[19] ? 't' :'f');
                        $serie->save();
                    // }

                }
            }

            return response()->json([
                "titulo"=>"Éxito",
                "mensjae"=>"se importo con éxito",
                "tipo"=>"success"
            ],200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         "titulo"=>"Error",
        //         "mensjae"=>"Comuniquese con el su área de TI",
        //         "tipo"=>"error"
        //     ],200);
        // }

    }
    public function formatoFechaExcel($numero){
        return gmdate("Y-m-d", (((int)$numero - 25569) * 86400));
    }

    public function listarSeries(Request $request){

        $data = ProductoDetalle::where('producto_id',$request->id)->get();
        return DataTables::of($data)
        ->addColumn('disponible_estado', function ($data) {
            $status_color = ($data->disponible=='t'?'success':'danger');
            $status_text = ($data->disponible=='t'?'Habilitado':'Deshabilitado ');
            return '<span class="label label-'.$status_color.'">'.$status_text.'</span>';
        })
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
        })->rawColumns(['accion', 'disponible_estado'])->make(true);;
    }
    public function actualizarProductos(){


        // $array_detalle = array();
        // foreach ($movimietos as $key => $value) {
        //     $value->detalle = MovimientoDetalle::where('mov_id',$value->mov_id)->get();
        //     array_push($array_detalle, $value->detalle);
        // }
        // $data  = DB::connection('soft')->table('movimien')->whereIN('cod_docu',['GR','G1','G2','G4','G5','G6'])->orderBy('fec_docu','asc')->get();
        // foreach ($data as $key => $value) {
        //     return $value;
        // }
        return $this->obtenerMovimientos();
        return response()->json([
            "tipo"=>true,
            // "movimientos"=>$movimietos
            // "detalle"=>$array_detalle
        ],200);
    }
    public function obtenerMovimientos(){

        $array=array();
        // set_time_limit(0);
        $contador = 0;
        $count = 0;
        do {

            $count = Movimiento::whereIn('cod_docu',['GR','G1','G2','G4','G5','G6'])
            ->count();
            $inicio = 0;
            $limit = 2000;

            $pages = (($count % $limit) == 0 ? (int) ($count / $limit) : (int) ($count / $limit) + 1 );

            $movimietos = Movimiento::whereIn('cod_docu',['GR','G1','G2','G4','G5','G6'])
            ->offset($inicio) // Starting position of records
            ->limit($limit) // Number of records to retrieve
            ->get();

            array_push($array, $movimietos);

            $contador ++;

            $inicio = $inicio + $limit;

        } while ($contador < 3);

        return [$contador, $count, $inicio, $array];

    }
    public function paginarMovimiento(){
        return $movimietos = Movimiento::whereIn('cod_docu',['GR','G1','G2','G4','G5','G6'])->paginate(90);
    }
}
