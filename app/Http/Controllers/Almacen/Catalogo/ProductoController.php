<?php

namespace App\Http\Controllers\Almacen\Catalogo;

use App\Http\Controllers\AlmacenController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Configuracion\AccesosUsuarios;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// use Debugbar;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosExport;
use App\Http\Controllers\ClasificacionSap\CategoriaSapController;
use App\Http\Controllers\ClasificacionSap\GrupoSapController;
use App\Http\Controllers\ClasificacionSap\SubcategoriaSapController;
use App\Models\Almacen\Producto;
use App\Models\Almacen\ProductoSap;
use App\Models\Configuracion\LogActividad;
use Carbon\Carbon;

class ProductoController extends Controller
{
    function view_producto()
    {
        $tipos = AlmacenController::mostrar_tipos_cbo();
        // * antigua clasificacion de softlink1
        // $clasificaciones = AlmacenController::mostrar_clasificaciones_cbo();
        // $subcategorias = AlmacenController::mostrar_subcategorias_cbo();
        // $categorias = AlmacenController::mostrar_categorias_cbo();

        // * clasificacion sap
        $grupos = GrupoSapController::mostrar();
        $categorias = CategoriaSapController::mostrar();
        $subcategorias = SubcategoriaSapController::mostrar();


        $unidades = AlmacenController::mostrar_unidades_cbo();
        // $posiciones = $this->mostrar_posiciones_cbo();
        // $ubicaciones = $this->mostrar_ubicaciones_cbo();
        $monedas = AlmacenController::mostrar_moneda_cbo();

        $array_accesos_botonera = array();
        $accesos_botonera = AccesosUsuarios::where('accesos_usuarios.estado', '=', 1)
            ->select('accesos.*')
            ->join('configuracion.accesos', 'accesos.id_acceso', '=', 'accesos_usuarios.id_acceso')
            ->where('accesos_usuarios.id_usuario', Auth::user()->id_usuario)
            ->where('accesos_usuarios.id_modulo', 65)
            ->where('accesos_usuarios.id_padre', 4)
            ->get();
        foreach ($accesos_botonera as $key => $value) {
            $value->accesos;
            array_push($array_accesos_botonera, $value->accesos->accesos_grupo);
        }
        // * antigua clasificacion softlink1
        // return view('almacen.producto.producto', compact('tipos', 'categorias', 'clasificaciones', 'subcategorias', 'unidades', 'monedas', 'array_accesos_botonera'));
        // * clasificacion sap
        return view('almacen.producto.producto', compact('tipos', 'grupos','categorias', 'subcategorias', 'unidades', 'monedas', 'array_accesos_botonera'));
    }

    function view_prod_catalogo()
    {
        $array_accesos = [];
        $accesos_usuario = AccesosUsuarios::where('estado', 1)->where('id_usuario', Auth::user()->id_usuario)->get();
        foreach ($accesos_usuario as $key => $value) {
            array_push($array_accesos, $value->id_acceso);
        }
        return view('almacen.producto.prod_catalogo', compact('array_accesos'));
    }

    public function mostrar_prods()
    {
        $prod = DB::table('almacen.alm_prod')
            ->select(
                'alm_prod.id_producto',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.descripcion',
                'alm_prod.part_number',
                'alm_prod.id_unidad_medida',
                'alm_prod.id_moneda',
                'sis_moneda.descripcion as descripcion_moneda',
                'alm_prod.series',
                'alm_prod.estado',
                'adm_estado_doc.estado_doc',
                'adm_estado_doc.bootstrap_color',
                'alm_subcat.descripcion as marca',
                'alm_und_medida.abreviatura'
            )
            ->leftjoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
            ->leftjoin('administracion.adm_estado_doc', 'adm_estado_doc.id_estado_doc', '=', 'alm_prod.estado')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'alm_prod.id_moneda')
            ->where('alm_prod.estado', 1);
        return datatables($prod)->toJson();
    }

    ////sugeridos

    public function listarProductosSugeridos(Request $request)
    {

        $data = DB::table('almacen.alm_prod')
            ->select(
                'alm_prod.id_producto',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.descripcion',
                'alm_prod.part_number',
                'alm_prod.id_unidad_medida',
                'alm_prod.id_moneda',
                'sis_moneda.descripcion as descripcion_moneda',
                'alm_prod.series',
                'alm_und_medida.abreviatura',
                'alm_subcat.descripcion as marca'
            )
            ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
            ->leftjoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->leftJoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'alm_prod.id_moneda')
            ->where('alm_prod.estado', 1);

        if ($request->part_number != null) {
            // $data = $data->where('alm_prod.part_number', trim(session()->get('productFilter_partnumber')))->get();
            $data = $data->where('alm_prod.part_number', trim($request->part_number))->get();
        } else if ($request->descripcion != null) {
            // $data = $data->where('alm_prod.descripcion', trim(session()->get('productFilter_descripcion')))->get();
            $data = $data->where('alm_prod.descripcion', trim($request->descripcion))->get();
        }
        // $output['data'] = $data;
        // Debugbar::info($data);

        return response()->json($data);
    }

    public function mostrar_prods_sugeridos($part_number, $descripcion)
    {
        if ($part_number !== '') {
            $prod = DB::table('almacen.alm_prod')
                ->select(
                    'alm_prod.id_producto',
                    'alm_prod.codigo',
                    'alm_prod.descripcion',
                    'alm_prod.part_number',
                    'alm_subcat.descripcion as marca'
                )
                ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
                ->where([['alm_prod.part_number', trim($part_number)],['alm_prod.estado','=',1]])
                ->get();
        } else if ($descripcion !== '') {
            $prod = DB::table('almacen.alm_prod')
                ->select(
                    'alm_prod.id_producto',
                    'alm_prod.codigo',
                    'alm_prod.descripcion',
                    'alm_prod.part_number',
                    'alm_subcat.descripcion as marca'
                )
                ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
                ->where([['alm_prod.descripcion', trim($descripcion)],['alm_prod.estado','=',1]])
                ->get();
        }
        $output['data'] = $prod;
        return response()->json($output);
    }

    public function mostrar_prods_almacen($id_almacen)
    {
        $prod = DB::table('almacen.alm_prod_ubi')
            ->select(
                'alm_prod_ubi.*',
                'alm_prod.codigo',
                'alm_prod.descripcion',
                'alm_prod.part_number',
                'alm_prod.id_unidad_medida',
                'alm_prod_antiguo.cod_antiguo',
                'alm_prod_ubi.stock',
                'alm_ubi_posicion.codigo as cod_posicion'
            )
            ->join('almacen.alm_prod', 'alm_prod.id_producto', '=', 'alm_prod_ubi.id_producto')
            ->leftjoin('almacen.alm_prod_antiguo', 'alm_prod_antiguo.id_producto', '=', 'alm_prod_ubi.id_producto')
            ->join('almacen.alm_ubi_posicion', 'alm_ubi_posicion.id_posicion', '=', 'alm_prod_ubi.id_posicion')
            ->join('almacen.alm_ubi_nivel', 'alm_ubi_nivel.id_nivel', '=', 'alm_ubi_posicion.id_nivel')
            ->join('almacen.alm_ubi_estante', 'alm_ubi_estante.id_estante', '=', 'alm_ubi_nivel.id_estante')
            ->join('almacen.alm_almacen', 'alm_almacen.id_almacen', '=', 'alm_ubi_estante.id_almacen')
            ->where([
                ['alm_almacen.id_almacen', '=', $id_almacen],
                ['alm_prod_ubi.stock', '>', 0]
            ])
            ->get();
        $size = $prod->count();
        $output['data'] = $prod;
        return response()->json($output);
    }

    public function mostrar_producto($id)
    {
        $producto = DB::table('almacen.alm_prod')
            ->select(
                'alm_prod.*',
                'alm_subcat.descripcion as subcat_descripcion',
                'alm_cat_prod.descripcion as cat_descripcion',
                'alm_tp_prod.descripcion as tipo_descripcion',
                'alm_tp_prod.id_tipo_producto',
                'alm_tp_prod.id_clasificacion',
                'sis_usua.nombre_corto',
                'adm_estado_doc.estado_doc',
                'adm_estado_doc.bootstrap_color',
                'grupo.id as id_grupo_sap',
                'grupo.descripcion as descripcion_grupo_sap',
                'categoria.id as id_categoria_sap',
                'categoria.descripcion as descripcion_categoria_sap',
                'subcategoria.id as id_subcategoria_sap',
                'subcategoria.descripcion as descripcion_subcategoria_sap'
            )
            ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
            ->leftjoin('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
            ->leftjoin('almacen.alm_tp_prod', 'alm_tp_prod.id_tipo_producto', '=', 'alm_cat_prod.id_tipo_producto')
            ->leftjoin('almacen.alm_clasif', 'alm_clasif.id_clasificacion', '=', 'alm_tp_prod.id_clasificacion')
            ->leftjoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'alm_prod.id_usuario')
            ->leftjoin('administracion.adm_estado_doc', 'adm_estado_doc.id_estado_doc', '=', 'alm_prod.estado')
            ->leftJoin('almacen.producto_sap', 'producto_sap.codigo_agile', '=', 'alm_prod.codigo')
            ->leftJoin('clasificacion_sap.subcategoria', 'subcategoria.id', '=', 'producto_sap.subcategoria_id')
            ->leftJoin('clasificacion_sap.categoria', 'categoria.id', '=', 'subcategoria.categoria_id')
            ->leftJoin('clasificacion_sap.grupo', 'grupo.id', '=', 'categoria.grupo_id')

            ->where([['alm_prod.id_producto', '=', $id]])
            ->get();

        $antiguos = DB::table('almacen.alm_prod_antiguo')
            ->where([['alm_prod_antiguo.id_producto', '=', $id]])
            ->orderBy('cod_antiguo')->get();

        $data = ["producto" => $producto, "antiguos" => $antiguos];
        return response()->json($data);
    }

    public function guardar_producto(Request $request)
    {
        $fecha = date('Y-m-d H:i:s');
        $id_usuario = Auth::user()->id_usuario;
        $msj = '';
        $des = strtoupper(trim($request->descripcion));
        $pn = trim($request->part_number);

        if ($pn !== null && $pn !== '') {
            $count = DB::table('almacen.alm_prod')
                ->where([['part_number', '=', $pn], ['estado', '=', 1]])
                ->count();
        } else if ($des !== null && $des !== '') {
            $count = DB::table('almacen.alm_prod')
                ->where([['descripcion', '=', $des], ['estado', '=', 1]])
                ->count();
        }

        if ($count == 0) {

            $producto = new Producto();
            $producto->part_number = $pn;
            // * INICIO: antigua clasificacion softlink1
            // $producto->id_clasif = $request->id_clasif;
            // $producto->id_categoria = $request->id_categoria;
            // $producto->id_subcategoria = $request->id_subcategoria;
            // * FIN: antigua clasificacion softlink1
            // * INICIO: nueva clasificacion sap
            $producto->id_grupo_sap = $request->id_grupo_sap;
            $producto->id_categoria_sap = $request->id_categoria_sap;
            $producto->id_subcategoria_sap = $request->id_subcategoria_sap;
            // *FIN: nueva clasificacion sap
            $producto->descripcion = $des;
            $producto->id_unidad_medida = ($request->id_unidad_medida !== 0 ? $request->id_unidad_medida : null);
            $producto->id_unid_equi = (($request->id_unid_equi !== 0 && $request->id_unid_equi !== null) ? $request->id_unid_equi : null);
            $producto->cant_pres = ($request->cant_pres !== null ? $request->cant_pres : null);
            $producto->series = ($request->series == null || $request->series !== '1') ? false : true;
            $producto->afecto_igv = ($request->afecto_igv == null || $request->afecto_igv !== '1') ? false : true;
            $producto->id_moneda = $request->id_moneda;
            $producto->notas = ($request->notas !== null ? $request->notas : '');
            $producto->id_usuario = $id_usuario;
            $producto->sunat_unsps = $request->sunat_unsps;
            $producto->codigo_compuesto = $request->codigo_compuesto;
            $producto->peso = $request->peso;
            $producto->largo = $request->largo;
            $producto->ancho =  $request->ancho;
            $producto->alto =  $request->alto;
            $producto->estado = 1;
            $producto->fecha_registro = $fecha;
            $producto->save();

   


            $codigo = AlmacenController::leftZero(7, $producto->id_producto);

            DB::table('almacen.alm_prod')
                ->where('id_producto', $producto->id_producto)
                ->update(['codigo' => $codigo]);


            // * registrar producto con clasificacion sap
            $productoSap = new ProductoSap();
            $productoSap->codigo_agile =$codigo;
            $productoSap->subcategoria_id =$request->id_subcategoria_sap;
            $productoSap->created_at =new Carbon();
            $productoSap->save();

            // $id_item = DB::table('almacen.alm_item')->insertGetId(
            //     [   'id_producto' => $id_producto,
            //         'codigo' => $codigo,
            //         'fecha_registro' => $fecha
            //     ],  'id_item');

            $comentario = 'Producto creado. Código: '.$codigo.', Partnumber: '.$producto->part_number.', Descripción: '.$producto->descripcion.' , Agregado por: ' . Auth::user()->nombre_corto;
            LogActividad::registrar(Auth::user(), 'Producto', 2, $producto->getTable(), null, $producto, $comentario, 'Almacén');

            // * antigua clasificacion softlink1
            // $productoCreado = DB::table('almacen.alm_prod')
            //     ->select('alm_prod.*', 'alm_und_medida.abreviatura', 'alm_cat_prod.descripcion as categoria', 'alm_subcat.descripcion as subcategoria')
            //     ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            //     ->join('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
            //     ->join('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
            //     ->where('id_producto', $producto->id_producto)->first();
            // * clasificacion antigua y si el producto cuenta con clasificacion sap se debe mostrar
            $productoCreado = DB::table('almacen.alm_prod')
                ->select('alm_prod.*', 'alm_und_medida.abreviatura', 'alm_cat_prod.descripcion as categoria', 'alm_subcat.descripcion as subcategoria')
                ->leftJoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
                ->leftJoin('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
                ->leftJoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
                ->leftJoin('almacen.producto_sap', 'producto_sap.codigo_agile', '=', 'alm_prod.codigo')
                ->leftJoin('clasificacion_sap.subcategoria', 'subcategoria.id', '=', 'producto_sap.subcategoria_id')
                ->leftJoin('clasificacion_sap.categoria', 'categoria.id', '=', 'subcategoria.categoria_id')
                ->leftJoin('clasificacion_sap.grupo', 'grupo.id', '=', 'categoria.grupo_id')
                ->where('alm_prod.id_producto', $producto->id_producto)->first();

            return response()->json(['msj' => $msj, 'id_item' => 0, 'id_producto' => $producto->id_producto, 'producto' => $productoCreado]);
        } else {
            $prod = DB::table('almacen.alm_prod')
                ->select('codigo')
                ->where([['part_number', '=', $pn], ['estado', '=', 1]])
                ->first();

            if ($prod == null) {
                $prod = DB::table('almacen.alm_prod')
                    ->select('codigo')
                    ->where([['descripcion', '=', $des], ['estado', '=', 1]])
                    ->first();
            }
            $msj = 'No es posible guardar. Ya existe un producto con dicha descripción y/o Part Number. ' . ($prod !== null ? $prod->codigo : '');

            return response()->json(['msj' => $msj]);
        }
    }

    public function update_producto(Request $request)
    {
        $msj = '';
        $des = strtoupper(trim($request->descripcion));
        $pn = trim($request->part_number);

        $actual = DB::table('almacen.alm_prod')
            ->where('id_producto', $request->id_producto)
            ->first();

        if ($pn !== null && $pn !== '') {
            $count = DB::table('almacen.alm_prod')
                ->where([['part_number', '=', $pn], ['estado', '=', 1], ['id_producto', '!=', $actual->id_producto]])
                ->count();
        } else if ($des !== null && $des !== '') {
            $count = DB::table('almacen.alm_prod')
                ->where([['descripcion', '=', $des], ['estado', '=', 1], ['id_producto', '!=', $actual->id_producto]])
                ->count();
        }

        $id_item = 0;
        $id_producto = $request->id_producto;

        $producto = Producto::find($id_producto);

        if ($count == 0) {
            DB::table('almacen.alm_prod')
                ->where('id_producto', $id_producto)
                ->update([
                    'part_number' => $request->part_number,
                    // * clasificacion softlink1
                    // 'id_subcategoria' => $request->id_subcategoria,
                    // 'id_categoria' => $request->id_categoria,
                    // 'id_clasif' => $request->id_clasif,
                    'descripcion' => $des,
                    'id_unidad_medida' => $request->id_unidad_medida,
                    'id_unid_equi' => $request->id_unid_equi,
                    'cant_pres' => $request->cant_pres,
                    'series' => ($request->series == '1' ? true : false),
                    'afecto_igv' => ($request->afecto_igv == '1' ? true : false),
                    'id_moneda' => $request->id_moneda,
                    'notas' => $request->notas,
                    'sunat_unsps' => $request->sunat_unsps,
                    'codigo_compuesto' => $request->codigo_compuesto,
                    'peso' => $request->peso,
                    'largo' => $request->largo,
                    'ancho' => $request->ancho,
                    'alto' => $request->alto,
                ]);

                $productoSap = ProductoSap::firstOrNew(['codigo_agile' => $producto->codigo]);
                $productoSap->codigo_agile = $producto->codigo;
                $productoSap->subcategoria_id = $request->id_subcategoria_sap;
                $productoSap->updated_at = new Carbon();
                $productoSap->save();


            $id_item = DB::table('almacen.alm_item')
                ->select('alm_item.id_item')
                ->where('id_producto', $id_producto)
                ->first();
        } else {
            $msj = 'No es posible actualizar. Ya existe un producto con la misma descripción y/o Part Number.';
        }
        return response()->json(['msj' => $msj, 'id_item' => $id_item, 'id_producto' => $id_producto]);
    }

    public function anular_producto($id)
    {
        try {
            DB::beginTransaction();

            $relacionados = DB::table('almacen.alm_prod')
                ->leftjoin('almacen.alm_det_req', 'alm_det_req.id_producto', '=', 'alm_prod.id_producto')
                ->leftjoin('almacen.mov_alm_det', 'mov_alm_det.id_producto', '=', 'alm_prod.id_producto')
                ->leftjoin('almacen.transfor_sobrante', 'transfor_sobrante.id_producto', '=', 'alm_prod.id_producto')
                ->where([
                    ['alm_prod.id_producto', '=', $id],
                    ['alm_det_req.estado', '!=', 7]
                ])
                ->count();

            if ($relacionados > 0) {
                $arrayRspta = array(
                    'tipo' => 'warning',
                    'mensaje' => 'El producto ya fue relacionado con otros documentos! No es posible dar de baja',
                );
            } else {
                $producto = Producto::find($id);
                $producto->estado= 7;
                $producto->save();

                // DB::table('almacen.alm_prod')
                //     ->where('id_producto', $id)
                //     ->update(['estado' => 7]);

                $arrayRspta = array(
                    'tipo' => 'success',
                    'mensaje' => 'El producto fue dado de baja.',
                );
            }

            //registrar en log actividad
            $comentario = 'Producto : ' . $producto->codigo . ', status: Anulado'. ', Realizado Por: '. Auth::user()->nombre_corto;
            LogActividad::registrar(Auth::user(), 'Producto', 4, $producto->getTable(), null, $producto, $comentario, 'Almacén');

            
            DB::commit();
            return response()->json($arrayRspta, 200);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('tipo' => 'error', 'mensaje' => 'Hubo un problema al anular. Por favor intente de nuevo', 'error' => $e->getMessage()));
        }
    }


    public function guardar_imagen(Request $request)
    {
        $update = false;
        $namefile = "";
        if ($request->codigo !== "" && $request->codigo !== null) {
            $nfile = $request->file('imagen');

            if (isset($nfile)) {

                $extension = pathinfo($nfile->getClientOriginalName(), PATHINFO_EXTENSION);
                $namefile = $request->codigo . '.' . $extension;

                File::delete(public_path('almacen/productos/' . $namefile));
                Storage::disk('archivos')->put('almacen/productos/' . $namefile, File::get($nfile));

                $update = DB::table('almacen.alm_prod')
                    ->where('id_producto', $request->id_producto)
                    ->update(['imagen' => $namefile]);
            }
        }

        if ($update) {
            $status = 1;
        } else {
            $status = 0;
        }
        $array = array("status" => $status, "imagen" => $namefile);
        return response()->json($array);
    }

    //Promociones

    public function listar_promociones($id_producto)
    {
        $data = DB::table('almacen.alm_prod_prom')
            ->select(
                'alm_prod_prom.*',
                'sis_usua.nombre_corto',
                DB::raw("(cat_prod.descripcion) || ' ' || (subcat_prod.descripcion) || ' ' || (prod.descripcion) AS descripcion_producto"),
                DB::raw("(cat_prod_prom.descripcion) || ' ' || (subcat_prod_prom.descripcion) || ' ' || (prod_prom.descripcion) AS descripcion_producto_promocion")
            )
            ->join('almacen.alm_prod as prod', 'prod.id_producto', '=', 'alm_prod_prom.id_producto')
            ->join('almacen.alm_cat_prod as cat_prod', 'cat_prod.id_categoria', '=', 'prod.id_categoria')
            ->join('almacen.alm_subcat as subcat_prod', 'subcat_prod.id_subcategoria', '=', 'prod.id_subcategoria')
            ->join('almacen.alm_prod as prod_prom', 'prod_prom.id_producto', '=', 'alm_prod_prom.id_producto_promocion')
            ->join('almacen.alm_cat_prod as cat_prod_prom', 'cat_prod_prom.id_categoria', '=', 'prod_prom.id_categoria')
            ->join('almacen.alm_subcat as subcat_prod_prom', 'subcat_prod_prom.id_subcategoria', '=', 'prod_prom.id_subcategoria')
            ->join('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'alm_prod_prom.usuario_registro')
            ->where([
                ['alm_prod_prom.id_producto', '=', $id_producto],
                ['alm_prod_prom.estado', '!=', 7]
            ])
            ->get();
        $output['data'] = $data;
        return response()->json($output);
    }

    public function crear_promocion(Request $request)
    {
        $id_usuario = Auth::user()->id_usuario;
        $id = DB::table('almacen.alm_prod_prom')
            ->insertGetId(
                [
                    'id_producto' => $request->id_producto,
                    'id_producto_promocion' => $request->id_producto_promocion,
                    'estado' => 1,
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_registro' => $id_usuario,
                ],
                'id_promocion'
            );
        return response()->json($id);
    }

    public function anular_promocion($id)
    {
        $update = DB::table('almacen.alm_prod_prom')
            ->where('id_promocion', $id)
            ->update(['estado' => 7]);
        return response()->json($update);
    }

    /** Producto Ubicacion */
    public function listar_ubicaciones_producto($id)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->select(
                'alm_prod_ubi.*', //'alm_almacen.codigo',
                'alm_almacen.descripcion as alm_descripcion',
                'alm_ubi_posicion.codigo as cod_posicion'
            )
            ->leftjoin('almacen.alm_ubi_posicion', 'alm_ubi_posicion.id_posicion', '=', 'alm_prod_ubi.id_posicion')
            ->join('almacen.alm_almacen', 'alm_almacen.id_almacen', '=', 'alm_prod_ubi.id_almacen')
            ->where([['alm_prod_ubi.id_producto', '=', $id], ['alm_prod_ubi.estado', '!=', 7]])
            ->orderBy('id_almacen')
            ->get();
        $output['data'] = $data;
        return response()->json($output);
    }

    public function mostrar_ubicacion($id)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->select('alm_prod_ubi.*', 'alm_almacen.descripcion as alm_descripcion')
            ->join('almacen.alm_ubi_posicion', 'alm_ubi_posicion.id_posicion', '=', 'alm_prod_ubi.id_posicion')
            ->join('almacen.alm_ubi_nivel', 'alm_ubi_nivel.id_nivel', '=', 'alm_ubi_posicion.id_nivel')
            ->join('almacen.alm_ubi_estante', 'alm_ubi_estante.id_estante', '=', 'alm_ubi_nivel.id_estante')
            ->join('almacen.alm_almacen', 'alm_almacen.id_almacen', '=', 'alm_ubi_estante.id_almacen')
            ->where([['alm_prod_ubi.id_prod_ubi', '=', $id]])
            ->get();
        return response()->json($data);
    }

    public function guardar_ubicacion(Request $request)
    {
        $fecha = date('Y-m-d H:i:s');
        $id_almacen = DB::table('almacen.alm_prod_ubi')->insertGetId(
            [
                'id_producto' => $request->id_producto,
                'id_posicion' => $request->id_posicion,
                'stock' => $request->stock,
                'estado' => 1,
                'fecha_registro' => $fecha
            ],
            'id_prod_ubi'
        );
        return response()->json($id_almacen);
    }

    public function update_ubicacion(Request $request)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->where('id_prod_ubi', $request->id_prod_ubi)
            ->update([
                'id_posicion' => $request->id_posicion,
                'stock' => $request->stock
            ]);
        return response()->json($data);
    }

    public function anular_ubicacion(Request $request, $id)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->where([['alm_prod_ubi.id_prod_ubi', '=', $id]])
            ->update(['estado' => 7]);
        return response()->json($data);
    }

    /**ProductoUbicacion Series */
    public function listar_series_producto($id)
    {
        $data = DB::table('almacen.alm_prod_serie')
            ->select(
                'alm_prod_serie.*',
                'alm_almacen.descripcion as alm_descripcion',
                DB::raw("('GR') || '-' || (guia_com.serie) || '-' || (guia_com.numero) as guia_com"),
                DB::raw("('GR') || '-' || (guia_ven.serie) || '-' || (guia_ven.numero) as guia_ven")
            )
            ->join('almacen.alm_almacen', 'alm_almacen.id_almacen', '=', 'alm_prod_serie.id_almacen')
            ->leftjoin('almacen.guia_com_det', 'guia_com_det.id_guia_com_det', '=', 'alm_prod_serie.id_guia_com_det')
            ->leftjoin('almacen.guia_com', 'guia_com.id_guia', '=', 'guia_com_det.id_guia_com')
            ->leftjoin('almacen.guia_ven_det', 'guia_ven_det.id_guia_ven_det', '=', 'alm_prod_serie.id_guia_ven_det')
            ->leftjoin('almacen.guia_ven', 'guia_ven.id_guia_ven', '=', 'guia_ven_det.id_guia_ven')
            ->where([['alm_prod_serie.id_prod', '=', $id]])
            ->get();
        $output['data'] = $data;
        return response()->json($output);
    }

    public function mostrar_serie($id)
    {
        $data = DB::table('almacen.alm_prod_serie')
            ->select('alm_prod_serie.*')
            ->where([['alm_prod_serie.id_prod_serie', '=', $id]])
            ->get();
        return response()->json($data);
    }

    public function guardar_serie(Request $request)
    {
        $fecha = date('Y-m-d H:i:s');
        $id_almacen = DB::table('almacen.alm_prod_serie')->insertGetId(
            [
                'id_prod' => $request->id_prod,
                'id_almacen' => $request->id_almacen,
                'serie' => $request->serie,
                'estado' => 1,
                'fecha_registro' => $fecha
            ],
            'id_prod_serie'
        );
        return response()->json($id_almacen);
    }

    public function update_serie(Request $request)
    {
        $data = DB::table('almacen.alm_prod_serie')
            ->where('id_prod_serie', $request->id_prod_serie)
            ->update([
                'id_prod' => $request->id_prod,
                'serie' => $request->serie
            ]);
        return response()->json($data);
    }

    public function anular_serie(Request $request, $id)
    {
        $data = DB::table('almacen.alm_prod_serie')
            ->where([['alm_prod_serie.id_prod_serie', '=', $id]])
            ->update(['estado' => 7]);
        return response()->json($data);
    }

    //Catalogo Producto
    public function mostrar_productos()
    {
        $data = DB::table('almacen.view_catalogo_productos')->get();

        // $data = DB::table('almacen.alm_prod')
        //     ->select(
        //         'alm_prod.id_producto',
        //         'alm_prod.part_number',
        //         'alm_prod.codigo',
        //         'alm_prod.cod_softlink',
        //         'alm_prod.descripcion',
        //         'sis_moneda.simbolo',
        //         DB::raw("CASE WHEN series=true THEN 'SI'
        //                 ELSE 'NO' END  AS series"),
        //         'alm_und_medida.abreviatura',
        //         'alm_subcat.cod_softlink as cod_sub_cat',
        //         'alm_subcat.descripcion as subcat_descripcion',
        //         'alm_cat_prod.cod_softlink as cod_cat',
        //         'alm_cat_prod.descripcion as cat_descripcion',
        //         'alm_tp_prod.id_tipo_producto',
        //         'alm_tp_prod.descripcion as tipo_descripcion',
        //         'alm_clasif.id_clasificacion',
        //         'alm_clasif.descripcion as clasif_descripcion'
        //     )
        //     ->join('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
        //     ->join('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
        //     ->join('almacen.alm_tp_prod', 'alm_tp_prod.id_tipo_producto', '=', 'alm_cat_prod.id_tipo_producto')
        //     ->join('almacen.alm_clasif', 'alm_clasif.id_clasificacion', '=', 'alm_tp_prod.id_clasificacion')
        //     ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
        //     ->leftjoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'alm_prod.id_moneda')
        //     ->get();
        $output['data'] = $data;
        return response()->json($output);
    }

    public function productosExcel()
    {
        $productos = DB::table('almacen.alm_prod')
            ->select(
                'alm_prod.id_producto',
                'alm_prod.part_number',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.descripcion',
                'alm_prod.notas',
                'alm_prod.series',
                'alm_prod.fecha_registro',
                'sis_moneda.simbolo',
                'alm_und_medida.abreviatura',
                'alm_subcat.descripcion as subcat_descripcion',
                'alm_cat_prod.descripcion as cat_descripcion',
                'alm_tp_prod.descripcion as tipo_descripcion',
                'alm_clasif.descripcion as clasif_descripcion',
                'sis_usua.nombre_corto'
            )
            ->join('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
            ->join('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
            ->join('almacen.alm_tp_prod', 'alm_tp_prod.id_tipo_producto', '=', 'alm_cat_prod.id_tipo_producto')
            ->join('almacen.alm_clasif', 'alm_clasif.id_clasificacion', '=', 'alm_tp_prod.id_clasificacion')
            ->leftjoin('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'alm_prod.id_usuario')
            ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->leftjoin('configuracion.sis_moneda', 'sis_moneda.id_moneda', '=', 'alm_prod.id_moneda')
            ->where('alm_prod.estado', 1)
            ->get();

        // return response()->json($productos);
        return Excel::download(new ProductosExport(
            $productos,
        ), 'Catalogo de Productos.xlsx');
    }
}
