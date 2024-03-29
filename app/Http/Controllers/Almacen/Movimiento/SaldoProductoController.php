<?php

namespace App\Http\Controllers\Almacen\Movimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaldoProductoController extends Controller
{
    public function listarProductosAlmacen(Request $request)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->select(
                'alm_prod_ubi.id_producto',
                'alm_prod_ubi.id_almacen',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.part_number',
                'alm_prod.descripcion',
                'alm_prod.series',
                'alm_und_medida.abreviatura',
                // 'sis_moneda.simbolo',
                'alm_prod.id_moneda',
                'alm_prod.id_unidad_medida',

                DB::raw("(SELECT SUM(alm_reserva.stock_comprometido) FROM almacen.alm_reserva 
                WHERE alm_reserva.id_producto = alm_prod_ubi.id_producto
                AND alm_reserva.id_almacen_reserva = alm_prod_ubi.id_almacen
                AND (alm_reserva.estado != 7 AND alm_reserva.estado != 5) ) as stock_comprometido"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm_det.estado = 1
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND mov_alm.id_tp_mov != 2) as suma_ingresos"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm_det.estado = 1
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND mov_alm.id_tp_mov = 2) as suma_salidas")
            )
            ->join('almacen.alm_prod', 'alm_prod.id_producto', '=', 'alm_prod_ubi.id_producto')
            ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->where([
                ['alm_prod_ubi.estado', '=', 1],
                ['alm_prod_ubi.id_almacen', '=', $request->id_almacen_origen_nueva]
            ]);

        return datatables($data)->toJson();
    }

    public function listarProductosCostoAlmacen(Request $request)
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->select(
                'alm_prod_ubi.id_producto',
                'alm_prod_ubi.id_almacen',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.part_number',
                'alm_prod.descripcion',
                'alm_und_medida.abreviatura',
                // 'sis_moneda.simbolo',
                'alm_prod.id_moneda',
                'alm_prod.id_unidad_medida',

                DB::raw("(SELECT SUM(alm_reserva.stock_comprometido) FROM almacen.alm_reserva 
                WHERE alm_reserva.id_producto = alm_prod_ubi.id_producto
                AND alm_reserva.id_almacen_reserva = alm_prod_ubi.id_almacen
                AND (alm_reserva.estado != 7 AND alm_reserva.estado != 5) ) as stock_comprometido"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm_det.estado = 1
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND mov_alm.id_tp_mov != 2) as suma_ingresos"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm_det.estado = 1
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND mov_alm.id_tp_mov = 2) as suma_salidas")
            )
            ->join('almacen.alm_prod', 'alm_prod.id_producto', '=', 'alm_prod_ubi.id_producto')
            ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->where([
                ['alm_prod_ubi.estado', '=', 1],
                ['alm_prod_ubi.id_almacen', '=', $request->id_almacen_origen_nueva]
            ])
            ->get();

        $lista = [];

        foreach ($data as $det) {
            $date = Carbon::now();
            $date = $date->format('Y');
            $costo_promedio = (new SalidaPdfController)->obtenerCostoPromedioSalida($det->id_producto, $det->id_almacen, $date . '-01-01', new Carbon());

            array_push(
                $lista,
                [
                    'id_producto' => $det->id_producto,
                    'codigo' => $det->codigo,
                    'cod_softlink' => $det->cod_softlink,
                    'part_number' => $det->part_number,
                    'descripcion' => $det->descripcion,
                    'suma_ingresos' => $det->suma_ingresos,
                    'suma_salidas' => $det->suma_salidas,
                    'costo_promedio' => $costo_promedio,
                    'stock_comprometido' => $det->stock_comprometido,
                    'id_almacen' => $det->id_almacen,
                    'abreviatura' => $det->abreviatura,
                ]
            );
        }
        return response()->json($data);
    }

    public function pruebaSaldos()
    {
        $data = DB::table('almacen.alm_prod_ubi')
            ->select(
                'alm_prod_ubi.id_producto',
                'alm_prod_ubi.id_almacen',
                'alm_prod.codigo',
                'alm_prod.cod_softlink',
                'alm_prod.part_number',
                'alm_prod.descripcion',
                'alm_und_medida.abreviatura',
                // 'sis_moneda.simbolo',
                'alm_prod.id_moneda',
                'alm_prod.id_unidad_medida',

                DB::raw("(SELECT SUM(alm_reserva.stock_comprometido) FROM almacen.alm_reserva 
                WHERE alm_reserva.id_producto = alm_prod_ubi.id_producto
                AND alm_reserva.id_almacen_reserva = alm_prod_ubi.id_almacen
                AND (alm_reserva.estado != 7 AND alm_reserva.estado != 5) ) as stock_comprometido"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND (mov_alm.id_tp_mov != 2)) as suma_ingresos"),

                DB::raw("(SELECT SUM(mov_alm_det.cantidad) FROM almacen.mov_alm_det
                INNER JOIN almacen.mov_alm ON(
                    mov_alm_det.id_mov_alm = mov_alm.id_mov_alm
                )
                WHERE mov_alm_det.id_producto = alm_prod_ubi.id_producto
                AND mov_alm.id_almacen = alm_prod_ubi.id_almacen
                AND mov_alm.id_tp_mov = 2) as suma_salidas")
            )
            ->join('almacen.alm_prod', 'alm_prod.id_producto', '=', 'alm_prod_ubi.id_producto')
            ->join('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
            ->where([
                ['alm_prod_ubi.estado', '=', 1],
                ['alm_prod_ubi.id_almacen', '=', 1]
            ])->get();

        return response()->json($data);
    }
}
