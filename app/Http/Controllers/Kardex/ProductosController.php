<?php

namespace App\Http\Controllers\Kardex;

use App\Http\Controllers\Controller;
use App\Imports\ProductosKardexImport;
use App\Models\kardex\Producto;
use App\Models\kardex\ProductoDetalle;
use App\Models\softlink\Movimiento;
use App\Models\softlink\MovimientoDetalle;
use App\Models\softlink\TipoCambio as SoftlinkTipoCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

use Carbon\Carbon;

class ProductosController extends Controller
{
    //
    public function lista(){
        // $almacen = Producto::where('estado',1)->dis
        $almacenes = Producto::select('almacen')->where('estado',1)->whereNotNull('almacen')->distinct()->orderBy('almacen', 'asc')->get();
        $empresas = Producto::select('empresa')->where('estado',1)->whereNotNull('empresa')->distinct()->orderBy('empresa', 'asc')->get();
        $estados_kardex = Producto::select('estado_kardex')->where('estado',1)->whereNotNull('estado_kardex')->distinct()->orderBy('estado_kardex', 'asc')->get();
        return view('kardex.productos.productos', get_defined_vars());
    }
    public function listar(Request $request){

        $data = Producto::where('estado',1);

        if($request->almacen!=='null'){
            $data = $data->where('almacen',$request->almacen);
        }

        if($request->empresa!=='null'){
            $data = $data->where('empresa',$request->empresa);
        }

        if($request->estado_kardex!=='null'){
            $data = $data->where('estado_kardex',$request->estado_kardex);
        }
        // $data = $data->get();
        $data = $data->limit(20);
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
            if ($request->hasFile('carga_inicial')) {
                $array = Excel::toArray(new ProductosKardexImport, $request->file('carga_inicial'));

                $array_series = array();
                // return sizeof($array[0]); exit;
                foreach ($array[0] as $key => $value) {
                    $productos_activos = ( (int) $request->productos_activos == 1 ? 1 : $value[22]);
                    // return [$productos_activos, $request->productos_activos];exit;
                    if($key !== 0){

                        if($value[22] == $productos_activos){

                            if(isset($value[14])){

                                $fecha03 = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[14]));
                                $fecha = $fecha03->format('Y-m-d');
                                // if($value[5] == 'ASU650SS-512GT-R'){
                                //     return $value;
                                // }
                                // return $value;
                                // $producto = Producto::firstOrNew([ 'codigo_softlink'=> $value[4] ]);
                                $producto = Producto::where('codigo_softlink', $value[4])->where('almacen', $value[7])->first();
                                // return $producto;
                                if(!$producto){
                                    $producto = new Producto();
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
                                }

                                $serieInicial = (string) $value[1];
                                $serie_codigo = ProductoDetalle::verificarSerie($serieInicial, $request->generar_serie);
                                if ($serie_codigo['serie'] != null) {
                                    $serie = ProductoDetalle::firstOrNew(['serie'=> $serie_codigo['serie'], 'producto_id'=> $producto->id]);

                                        $monto = strrpos ($value[18], "$");
                                        $tipo_moneda = ($monto ? 1 : 2); // 1 es dolar y el 2 soles
                                        $precio = str_replace("$", "0", $value[18]);

                                        $tipoCambio= $this->getTipoCambio($fecha);
                                        // return [$value, $fecha, $tipoCambio];exit;
                                        $serie->serie           = $serie_codigo['serie'];
                                        $serie->precio          = (float) $value[13];
                                        $serie->tipo_moneda     = $tipo_moneda;
                                        $serie->precio_unitario = (float) $precio;
                                        $serie->producto_id     = $producto->id;
                                        $serie->fecha           = $fecha;
                                        $serie->estado          = 1;
                                        $serie->disponible      = ($value[1] == $value[19] ? 'f' :'t');
                                        $serie->autogenerado    = ($serie_codigo['autogenerado'] == true ? 't' :'f');
                                        $serie->tipo_cambio     = $tipoCambio;
                                        $serie->total           = (int) $value[22];
                                    $serie->save();

                                    $index = array_search($producto->id, array_column($array_series, 'producto'));
                                    // return [$index];
                                    if($serie_codigo['autogenerado'] == true){
                                        if($index!==false){
                                            // return [$index,'ingreso'];
                                        //     // return [$array_series[$index]['series'], $array_series];
                                            array_push($array_series[$index]['series'],$serie->id);
                                        }else{
                                            // return [$index,'ingreso'];
                                            array_push($array_series, array(
                                                "producto"=>$producto->id,
                                                "series"=>[$serie->id],
                                            ));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // return $array_series;exit;
                foreach ($array_series as $key => $value) {
                    $data = ProductoDetalle::where('producto_id',$value['producto'])
                    ->whereNotIn('id',$value['series'])->where('autogenerado','t')
                    ->delete();
                    // return $data;
                }

                return response()->json(["titulo"=>"Éxito", "mensaje"=>"Se importo con éxito", "tipo"=>"success"], 200);
            } else {
                return response()->json(["titulo"=>"Error", "mensaje"=>"No ha importado ningún archivo", "tipo"=>"error"],200);
            }
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         "titulo"=>"Error",
        //         "mensjae"=>"Comuniquese con el su área de TI",
        //         "tipo"=>"error"
        //     ],200);
        // }

    }

    public function formatoFechaExcel($numero){


        // $fecha03 = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_atencion']));
        // $fecha = $fecha01->format('Y-m-d');

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
        // $data  = DB::connection('soft1')->table('movimien')->whereIN('cod_docu',['GR','G1','G2','G4','G5','G6'])->orderBy('fec_docu','asc')->get();
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


    public function getTipoCambio($fecha)
    {
        $tc = SoftlinkTipoCambio::where([['dfecha', '!=', null], ['dfecha', '<=', $fecha]])
            ->orderBy('dfecha', 'DESC')->first();

        return ($tc !== null ? $tc->cambio3 : 0);
    }


}
