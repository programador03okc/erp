<?php

namespace App\Http\Controllers\Migraciones;

use App\Exports\CorrelativosSoftlinkExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuracion\LogActividad;
use App\Models\Logistica\Orden;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\StringHelper;
use App\Models\Almacen\Producto;
use App\Models\Almacen\ProductoSap;

class MigrateOrdenSoftLinkController extends Controller
{

    public function getCorrelativoDocumentoSoftlink()
    {


        $empresas_soft_orden_compra = [
            ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OC', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 2, 'nombre' => 'PYC', 'cod_docu' => 'O3', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 3, 'nombre' => 'SVS', 'cod_docu' => 'O2', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 4, 'nombre' => 'RBDB', 'cod_docu' => 'O4', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 5, 'nombre' => 'JEDR', 'cod_docu' => 'O5', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 6, 'nombre' => 'PTEC', 'cod_docu' => 'O6', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => '']
        ];

        $empresas_soft_orden_servicio = [
            ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OS', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 2, 'nombre' => 'PYC', 'cod_docu' => 'OP', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 3, 'nombre' => 'SVS', 'cod_docu' => 'OV', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 4, 'nombre' => 'RBDB', 'cod_docu' => 'OR', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 5, 'nombre' => 'JEDR', 'cod_docu' => 'OJ', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
            ['id' => 6, 'nombre' => 'PTEC', 'cod_docu' => 'OA', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => '']
        ];

        $empresas_soft_orden_importacion = [
            ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OI', 'next_num_docu_soft1' => '', 'ultimo_correlativo_soft1' => ''],
        ];

        $hoy = date('Y-m-d'); //Carbon::now()
        $yy = $this->leftZero(4, intval(date('y', strtotime($hoy))));


        foreach ($empresas_soft_orden_compra as $key => $value) {

            $ult_mov = DB::connection('soft1')->table('movimien')
                ->where([
                    ['num_docu', '>', $yy . '0000000'],
                    ['num_docu', '<', $yy . '9999999'],
                    ['cod_suc', '=', $value['id']],
                    ['tipo', '=', 1], //ingreso
                    ['cod_docu', '=', $value['cod_docu']]
                ])
                ->orderBy('num_docu', 'desc')->first();

            //obtiene el correlativo
            $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
            //crea el correlativo del documento
            $nro_mov = $this->leftZero(7, (intval($num_ult_mov) + 1));
            //anida el anio con el numero de documento
            $num_docu = $yy . $nro_mov;
            $empresas_soft_orden_compra[$key]['next_num_docu_soft1'] = $num_docu;
            $empresas_soft_orden_compra[$key]['ultimo_correlativo_soft1'] = intval($num_ult_mov);
        }

        foreach ($empresas_soft_orden_servicio as $key => $value) {

            $ult_mov = DB::connection('soft1')->table('movimien')
                ->where([
                    ['num_docu', '>', $yy . '0000000'],
                    ['num_docu', '<', $yy . '9999999'],
                    ['cod_suc', '=', $value['id']],
                    ['tipo', '=', 1], //ingreso
                    ['cod_docu', '=', $value['cod_docu']]
                ])
                ->orderBy('num_docu', 'desc')->first();

            //obtiene el correlativo
            $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
            //crea el correlativo del documento
            $nro_mov = $this->leftZero(7, (intval($num_ult_mov) + 1));
            //anida el anio con el numero de documento
            $num_docu = $yy . $nro_mov;
            $empresas_soft_orden_servicio[$key]['next_num_docu_soft1'] = $num_docu;
            $empresas_soft_orden_servicio[$key]['ultimo_correlativo_soft1'] = intval($num_ult_mov);
        }

        foreach ($empresas_soft_orden_importacion as $key => $value) {

            $ult_mov = DB::connection('soft1')->table('movimien')
                ->where([
                    ['num_docu', '>', $yy . '0000000'],
                    ['num_docu', '<', $yy . '9999999'],
                    ['cod_suc', '=', $value['id']],
                    ['tipo', '=', 1], //ingreso
                    ['cod_docu', '=', $value['cod_docu']]
                ])
                ->orderBy('num_docu', 'desc')->first();

            //obtiene el correlativo
            $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
            //crea el correlativo del documento
            $nro_mov = $this->leftZero(7, (intval($num_ult_mov) + 1));
            //anida el anio con el numero de documento
            $num_docu = $yy . $nro_mov;
            $empresas_soft_orden_importacion[$key]['next_num_docu_soft1'] = $num_docu;
            $empresas_soft_orden_importacion[$key]['ultimo_correlativo_soft1'] = intval($num_ult_mov);
        }


        return ['orden_compra'=>$empresas_soft_orden_compra,'orden_servicio'=>$empresas_soft_orden_servicio,'orden_importacion'=>$empresas_soft_orden_importacion];

    }

    public function getCorrelativoDocumentoSoftlinkStatic(){
        $json = '{
    "orden_compra": [
        {
            "id": 1,
            "nombre": "OKC",
            "cod_docu": "OC",
            "next_num_docu_soft1": "00240000395",
            "ultimo_correlativo_soft1": 394
        },
        {
            "id": 2,
            "nombre": "PYC",
            "cod_docu": "O3",
            "next_num_docu_soft1": "00240000113",
            "ultimo_correlativo_soft1": 112
        },
        {
            "id": 3,
            "nombre": "SVS",
            "cod_docu": "O2",
            "next_num_docu_soft1": "00240000006",
            "ultimo_correlativo_soft1": 5
        },
        {
            "id": 4,
            "nombre": "RBDB",
            "cod_docu": "O4",
            "next_num_docu_soft1": "00240000001",
            "ultimo_correlativo_soft1": 0
        },
        {
            "id": 5,
            "nombre": "JEDR",
            "cod_docu": "O5",
            "next_num_docu_soft1": "00240000001",
            "ultimo_correlativo_soft1": 0
        },
        {
            "id": 6,
            "nombre": "PTEC",
            "cod_docu": "O6",
            "next_num_docu_soft1": "00240000009",
            "ultimo_correlativo_soft1": 8
        }
    ],
    "orden_servicio": [
        {
            "id": 1,
            "nombre": "OKC",
            "cod_docu": "OS",
            "next_num_docu_soft1": "00240000243",
            "ultimo_correlativo_soft1": 242
        },
        {
            "id": 2,
            "nombre": "PYC",
            "cod_docu": "OP",
            "next_num_docu_soft1": "00240000036",
            "ultimo_correlativo_soft1": 35
        },
        {
            "id": 3,
            "nombre": "SVS",
            "cod_docu": "OV",
            "next_num_docu_soft1": "00240000006",
            "ultimo_correlativo_soft1": 5
        },
        {
            "id": 4,
            "nombre": "RBDB",
            "cod_docu": "OR",
            "next_num_docu_soft1": "00240000002",
            "ultimo_correlativo_soft1": 1
        },
        {
            "id": 5,
            "nombre": "JEDR",
            "cod_docu": "OJ",
            "next_num_docu_soft1": "00240000001",
            "ultimo_correlativo_soft1": 0
        },
        {
            "id": 6,
            "nombre": "PTEC",
            "cod_docu": "OA",
            "next_num_docu_soft1": "00240000007",
            "ultimo_correlativo_soft1": 6
        }
    ],
    "orden_importacion": [
        {
            "id": 1,
            "nombre": "OKC",
            "cod_docu": "OI",
            "next_num_docu_soft1": "00240000013",
            "ultimo_correlativo_soft1": 12
        }
    ]
}';
            return (json_decode($json, true));
    }

    public function descargarExcelCorrelativoDocumentoSoftlink()
    {

        return Excel::download(new CorrelativosSoftlinkExport(), 'correlativo_softlink1.xlsx');
    }



    //Valida el estado de la orden en softlink
    public function validarOrdenSoftlink($id_orden_compra)
    {
        try {
            DB::beginTransaction();

            $oc = DB::table('logistica.log_ord_compra')
                ->where('id_orden_compra', $id_orden_compra)
                ->first();

            $arrayRspta = [];
            //si existe un id_softlink
            if ($oc->id_softlink !== null) {
                //obtiene oc softlink
                $oc_softlink = DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $oc->id_softlink)->first();

                if ($oc_softlink !== null) {
                    //pregunta si fue referenciado
                    $guia_referen = DB::connection(app('conexion_softlink'))->table('movimien')
                        ->where([
                            ['cod_pedi', '=', $oc_softlink->cod_docu],
                            ['num_pedi', '=', $oc_softlink->num_docu],
                            ['flg_anulado', '=', 0]
                        ])
                        ->first();

                    if ($guia_referen !== null) {
                        $arrayRspta = array(
                            'tipo' => 'warning',
                            'id_softlink' => 0,
                            'mensaje' => 'Ésta orden ya fue referenciada en Softlink.',
                            'ocSoftlink' => array('cabecera' => $oc_softlink),
                            'ocAgile' => array('cabecera' => $oc),
                        );
                    }
                    //pregunta si fue anulada en softlink
                    else if ($oc_softlink->flg_anulado > 0) {
                        $arrayRspta = array(
                            'tipo' => 'error',
                            'id_softlink' => null,
                            'mensaje' => 'Ésta orden ya fue anulada en Softlink.',
                            'ocSoftlink' => array('cabecera' => $oc_softlink),
                            'ocAgile' => array('cabecera' => $oc),
                        );
                    } else {
                        $arrayRspta = array(
                            'tipo' => 'success',
                            'id_softlink' => $oc_softlink->mov_id,
                            'mensaje' => 'Se actualizó ésta OC en softlink. Con Nro. ' . $oc_softlink->num_docu . ' con id ' . $oc_softlink->mov_id,
                            'ocSoftlink' => array('cabecera' => $oc_softlink),
                            'ocAgile' => array('cabecera' => $oc),
                        );
                    }
                } else {
                    $arrayRspta = array(
                        'tipo' => 'error',
                        'id_softlink' => null,
                        'mensaje' => 'No existe dicho id en Softlink. Id: ' . $oc->id_softlink,
                        'ocSoftlink' => array('cabecera' => $oc_softlink),
                        'ocAgile' => array('cabecera' => $oc),
                    );
                }
            } else {
                $arrayRspta = array(
                    'tipo' => 'error',
                    'id_softlink' => null,
                    'mensaje' => 'No existe un id_softlink en la OC seleccionada. Id: ' . $id_orden_compra
                );
            }
            DB::commit();
            return $arrayRspta;
        } catch (\PDOException $e) {
            DB::rollBack();
            return array('tipo' => 'error', 'mensaje' => 'Hubo un problema al enviar la orden. Por favor intente de nuevo', 'error' => $e->getMessage());
        }
    }

    //Envio de la orden a softlink
    public function migrarOrdenCompra($id_orden_compra)
    {
        try {
            DB::beginTransaction();



            $oc = DB::table('logistica.log_ord_compra')
                ->select(
                    'log_ord_compra.id_orden_compra',
                    'log_ord_compra.codigo',
                    'log_ord_compra.id_tp_documento',
                    'log_ord_compra.id_softlink',
                    'log_ord_compra.fecha',
                    'log_ord_compra.fecha_registro',
                    'log_ord_compra.id_moneda',
                    'log_ord_compra.id_sede',
                    'log_ord_compra.id_condicion_softlink',
                    'log_ord_compra.observacion',
                    'log_ord_compra.plazo_entrega',
                    'log_ord_compra.direccion_destino',
                    'log_ord_compra.incluye_igv',
                    DB::raw("(ubi_dis.descripcion) || ' - ' || (ubi_prov.descripcion) || ' - ' || (ubi_dpto.descripcion) AS ubigeo_destino"),
                    'alm_almacen.codigo as codigo_almacen',
                    'adm_contri.nro_documento as ruc',
                    'adm_contri.razon_social',
                    'adm_contri.id_tipo_contribuyente',
                    'sis_identi.cod_softlink as cod_di',
                    'adm_empresa.codigo as codigo_emp',
                    'sis_usua.codvend_softlink',
                    DB::raw("(SELECT SUM(log_det_ord_compra.precio * log_det_ord_compra.cantidad) FROM logistica.log_det_ord_compra
                      WHERE log_det_ord_compra.estado <> 7
                      AND log_det_ord_compra.id_orden_compra = log_ord_compra.id_orden_compra)
                      as total_precio")
                )
                ->join('logistica.log_prove', 'log_prove.id_proveedor', '=', 'log_ord_compra.id_proveedor')
                ->join('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
                ->leftJoin('configuracion.ubi_dis', 'ubi_dis.id_dis', '=', 'log_ord_compra.ubigeo_destino')
                ->leftJoin('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
                ->leftJoin('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')
                ->leftJoin('almacen.alm_almacen', function ($join) {
                    $join->on('alm_almacen.id_sede', '=', 'log_ord_compra.id_sede');
                    $join->where('alm_almacen.id_tipo_almacen', '=', 1);
                    $join->where('alm_almacen.estado', '!=', 7);
                    $join->orderBy('alm_almacen.codigo');
                    $join->limit(1);
                })
                ->leftjoin('contabilidad.sis_identi', 'sis_identi.id_doc_identidad', '=', 'adm_contri.id_doc_identidad')
                ->join('administracion.sis_sede', 'sis_sede.id_sede', '=', 'log_ord_compra.id_sede')
                ->join('administracion.adm_empresa', 'adm_empresa.id_empresa', '=', 'sis_sede.id_empresa')
                ->join('configuracion.sis_usua', 'sis_usua.id_usuario', '=', 'log_ord_compra.id_usuario')
                ->where('id_orden_compra', $id_orden_compra)
                ->first();

            $detalles = DB::table('logistica.log_det_ord_compra')
                ->select(
                    'log_det_ord_compra.*',
                    'alm_prod.codigo as codigo_agile',
                    'alm_prod.part_number',
                    'alm_prod.descripcion',
                    'alm_und_medida.abreviatura',
                    // * softlink1
                    // 'alm_tp_prod.id_tipo_producto as id_categoria',
                    // 'alm_tp_prod.descripcion as categoria',
                    // 'alm_subcat.id_subcategoria',
                    // 'alm_subcat.descripcion as subcategoria',
                    // 'alm_clasif.descripcion as clasificacion',
                    // * softlink2
                    'grupo.id as id_grupo',
                    'grupo.descripcion as grupo',
                    'categoria.id as id_categoria',
                    'categoria.descripcion as categoria',
                    'subcategoria.id as id_subcategoria',
                    'subcategoria.descripcion as subcategoria',


                    'log_ord_compra.id_moneda',
                    'alm_prod.series',
                    'alm_prod.notas',
                    'oportunidades.codigo_oportunidad'
                )
                ->join('logistica.log_ord_compra', 'log_ord_compra.id_orden_compra', '=', 'log_det_ord_compra.id_orden_compra')
                ->leftjoin('almacen.alm_prod', 'alm_prod.id_producto', '=', 'log_det_ord_compra.id_producto')
                // * softlink1
                // ->leftjoin('almacen.alm_clasif', 'alm_clasif.id_clasificacion', '=', 'alm_prod.id_clasif')
                // ->leftjoin('almacen.alm_tp_prod', 'alm_tp_prod.id_tipo_producto', '=', 'alm_prod.id_categoria')
                // ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')


                // * softlink2
                ->leftJoin('almacen.producto_sap', 'producto_sap.codigo_agile', '=', 'alm_prod.codigo')
                ->leftJoin('clasificacion_sap.subcategoria', 'subcategoria.id', '=', 'producto_sap.subcategoria_id')
                ->leftJoin('clasificacion_sap.categoria', 'categoria.id', '=', 'subcategoria.categoria_id')
                ->leftJoin('clasificacion_sap.grupo', 'grupo.id', '=', 'categoria.grupo_id')

                ->leftjoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
                ->leftjoin('almacen.alm_det_req', 'alm_det_req.id_detalle_requerimiento', '=', 'log_det_ord_compra.id_detalle_requerimiento')
                ->leftjoin('almacen.alm_req', 'alm_req.id_requerimiento', '=', 'alm_det_req.id_requerimiento')
                ->leftjoin('mgcp_cuadro_costos.cc', 'cc.id', '=', 'alm_req.id_cc')
                ->leftjoin('mgcp_oportunidades.oportunidades', 'oportunidades.id', '=', 'cc.id_oportunidad')
                ->where([
                    ['log_det_ord_compra.id_orden_compra', '=', $id_orden_compra],
                    ['log_det_ord_compra.estado', '!=', 7]
                ])
                ->get();

            $cuadros = [];
            $itemSinClasificacion=[];
            foreach ($detalles as $det) {
                if ($det->codigo_oportunidad !== null) {
                    if (!in_array($det->codigo_oportunidad, $cuadros)) {
                        array_push($cuadros, $det->codigo_oportunidad);
                    }
                }


                if(($det->id_subcategoria ==null) && $det->tipo_item_id ==1 ){

                    $itemSinClasificacion[] =$det->descripcion;
                    }

                if(count($itemSinClasificacion)>0){
                    return response()->json(array('tipo' => 'warning', 'mensaje' => 'Revise los items de esta orden sin definir clasificación: <br>'.implode("<br>", $itemSinClasificacion) ));
                }
            }

            $anho = date("y", strtotime($oc->fecha));
            $hoy = date('Y-m-d'); //Carbon::now()
            //igv por defecto
            $igv = 18.00;
            $fecha = date("Y-m-d", strtotime($oc->fecha));

            //obtiene el tipo de cambio
            $tp_cambio = DB::connection(app('conexion_softlink'))->table('tcambio')
                ->where([['dfecha', '<=', new Carbon($oc->fecha)]])
                ->orderBy('dfecha', 'desc')
                ->first();

            if ($oc->codvend_softlink == '000055' || $oc->codvend_softlink == '000022') { //si es deza o dorado
                $yy = 'P0' . $anho;
            } else {
                //obtiene el año a 2 digitos y le aumenta 2 ceros adelante
                $yy = $this->leftZero(4, intval(date('y', strtotime($hoy))));
            }


            $arrayRspta = [];

            if ($oc !== null && count($detalles) > 0) {

                if ($oc->id_tp_documento == 2) { //Compra

                    $empresas_soft = [
                        ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OC'],
                        ['id' => 2, 'nombre' => 'PYC', 'cod_docu' => 'O3'],
                        ['id' => 3, 'nombre' => 'SVS', 'cod_docu' => 'O2'],
                        ['id' => 4, 'nombre' => 'RBDB', 'cod_docu' => 'O4'],
                        ['id' => 5, 'nombre' => 'JEDR', 'cod_docu' => 'O5'],
                        ['id' => 6, 'nombre' => 'PTEC', 'cod_docu' => 'O6']
                    ];
                } else if ($oc->id_tp_documento == 3) { //Servicio

                    $empresas_soft = [
                        ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OS'],
                        ['id' => 2, 'nombre' => 'PYC', 'cod_docu' => 'OP'],
                        ['id' => 3, 'nombre' => 'SVS', 'cod_docu' => 'OV'],
                        ['id' => 4, 'nombre' => 'RBDB', 'cod_docu' => 'OR'],
                        ['id' => 5, 'nombre' => 'JEDR', 'cod_docu' => 'OJ'],
                        ['id' => 6, 'nombre' => 'PTEC', 'cod_docu' => 'OA']
                    ];
                } else if ($oc->id_tp_documento == 12) { //importación
                    $empresas_soft = [
                        ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OI']
                    ];
                }

                $cod_suc = '';
                $cod_docu = '';

                if ($oc->id_tp_documento == 12 && $oc->codigo_emp != 'OKC') { //Importación
                    return response()->json(array('tipo' => 'warning', 'mensaje' => 'Solo la empresa con código OKC puede crear una orden de importación'));
                } else {

                    foreach ($empresas_soft as $emp) {
                        if ($emp['nombre'] == $oc->codigo_emp) {
                            $cod_suc = $emp['id'];
                            $cod_docu = $emp['cod_docu'];
                        }
                    }
                }

                // veriicar si existe un id_softlink para decidir si actualizar una orden en softlink o crear una nueva
                if ($oc->id_softlink !== null) {
                    //obtiene oc softlink
                    $oc_softlink = DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $oc->id_softlink)->first();


                    if ($oc_softlink !== null) {

                        //pregunta si fue referenciado
                        $guia_referen = DB::connection(app('conexion_softlink'))->table('movimien')
                            ->where([
                                ['cod_pedi', '=', $oc_softlink->cod_docu],
                                ['num_pedi', '=', $oc_softlink->num_docu],
                                ['cod_suc', '=', $oc_softlink->cod_suc],
                                ['flg_anulado', '=', 0]
                            ])
                            ->first();
                        if ($guia_referen !== null) {
                            $arrayRspta = array(
                                'tipo' => 'warning',
                                'mensaje' => 'Ésta orden ya fue referenciada en Softlink.',
                                'ocSoftlink' => array('cabecera' => $oc_softlink),
                                'ocAgile' => array('cabecera' => $oc),
                            );
                        }
                        //pregunta si fue anulada en softlink
                        else if ($oc_softlink->flg_anulado == 1) {
                            $arrayRspta = array(
                                'tipo' => 'warning',
                                'mensaje' => 'Ésta orden ya fue anulada en Softlink.',
                                'ocSoftlink' => array('cabecera' => $oc_softlink),
                                'ocAgile' => array('cabecera' => $oc),
                            );
                        } else {
                            //* actualiza orden
                            $arrayRspta = $this->actualizarOrdenEnSoftlink($oc_softlink, $id_orden_compra, $oc, $detalles, $cod_suc, $cod_docu, $cuadros, $yy, $fecha, $igv, $tp_cambio);
                        }
                    } else {
                        $arrayRspta = array(
                            'tipo' => 'warning',
                            'mensaje' => 'No existe dicho id en Softlink. Id: ' . $oc->id_softlink,
                            'ocSoftlink' => array('cabecera' => $oc_softlink),
                            'ocAgile' => array('cabecera' => $oc),
                        );
                    }
                } else {
                    //* crear orden
                    $ordenEnSoftlink =  $this->crearNuevaOrdenEnSoftlink($id_orden_compra, $oc, $detalles, $cod_suc, $cod_docu, $cuadros, $yy, $fecha, $igv, $tp_cambio);

                    $arrayRspta = array(
                        'tipo' => $ordenEnSoftlink['tipo'],
                        'mensaje' => $ordenEnSoftlink['mensaje'],
                        'orden_softlink' => $ordenEnSoftlink['orden_softlink'],
                        'ocSoftlink' => $ordenEnSoftlink['ocSoftlink'],
                        'ocAgile' => $ordenEnSoftlink['ocAgile']
                    );
                }
            } else {
                $arrayRspta = array(
                    'tipo' => 'warning',
                    'mensaje' => 'No existe la OC seleccionada. Id: ' . $id_orden_compra
                );
            }

            DB::commit();

            // si existe uno o mas cod_docu con num_doc iguales entonces debe volver a contar para aumentar el contador y actualizar las tablas movimien y detmov
            if ($oc->id_softlink == null) {
                $cantidadCodigosExistentes = DB::connection(app('conexion_softlink'))->table('movimien')->where([['num_docu', $ordenEnSoftlink['orden_softlink']], ['cod_docu', $cod_docu]])->count();
                if ($cantidadCodigosExistentes > 1) {
                    //obtiene el ultimo registro
                    $ult_mov = DB::connection(app('conexion_softlink'))->table('movimien')
                        ->where([
                            ['num_docu', '>', $yy . '0000000'],
                            ['num_docu', '<', $yy . '9999999'],
                            ['cod_suc', '=', $cod_suc],
                            ['tipo', '=', 1], //ingreso
                            ['cod_docu', '=', $cod_docu]
                        ])
                        ->orderBy('num_docu', 'desc')->first();
                    //obtiene el correlativo
                    $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
                    //crea el correlativo del documento
                    $nro_mov = $this->leftZero(7, (intval($num_ult_mov) + 1));
                    //anida el anio con el numero de documento
                    $num_docu = $yy . $nro_mov;

                    DB::connection(app('conexion_softlink'))->table('movimien')
                        ->where('mov_id', $ordenEnSoftlink['mov_id'])
                        ->update(['num_docu' => $num_docu]);

                    DB::connection(app('conexion_softlink'))->table('detmov')
                        ->where('mov_id', $ordenEnSoftlink['mov_id'])
                        ->update(['num_docu' => $num_docu]);

                    DB::table('logistica.log_ord_compra')
                        ->where('id_orden_compra', $id_orden_compra)
                        ->update([
                            'codigo_softlink' => $num_docu,
                            'id_softlink' => $ordenEnSoftlink['mov_id']
                        ]);

                    $arrayRspta = array(
                        'tipo' => 'success',
                        'mensaje' => 'Se migró correctamente la OC Nro. ' . $num_docu . ' con id ' . $ordenEnSoftlink['mov_id'],
                        'orden_softlink' => $num_docu, //($yy . '-' . $nro_mov),
                        'ocSoftlink' => array('cabecera' => $ordenEnSoftlink['soc'], 'detalle' => $ordenEnSoftlink['sdet']),
                        'ocAgile' => array('cabecera' => $oc, 'detalle' => $detalles),
                    );
                }
            }
            // return response()->json($msj);
            return response()->json($arrayRspta, 200);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('tipo' => 'error', 'mensaje' => 'Hubo un problema al enviar la orden. Por favor intente de nuevo', 'error' => $e->getMessage()));
        }
    }

    public function crearNuevaOrdenEnSoftlink($id_orden_compra, $oc, $detalles, $cod_suc, $cod_docu, $cuadros, $yy, $fecha, $igv, $tp_cambio)
    {


        //persona juridica x defecto
        $doc_tipo = ($oc->id_tipo_contribuyente !== null
            ? ($oc->id_tipo_contribuyente <= 2 ? 2 : 1)
            : 1);
        //por defecto ruc
        $cod = ($oc->cod_di !== null ? $oc->cod_di : '06');
        //obtiene o crea el proveedor
        $cod_auxi = $this->obtenerProveedor($oc->ruc, $oc->razon_social, $doc_tipo, $cod);
        //Calcular IGV
        if ($oc->incluye_igv) {
            $mon_impto = (floatval($oc->total_precio) * ($igv / 100));
        } else {
            $mon_impto = 0;
        }


        $mov_id = $this->obtenerMovId();

        // obtener ultimo correlativo de softlink1
        $ultimosCorrelativosSoftlink1 = $this->getCorrelativoDocumentoSoftlinkStatic();
        $ult_correlativo_softlink1 = 0;
        $arrayTipoOrden='';
        if ($oc->id_tp_documento == 2) { // compra
            $arrayTipoOrden = 'orden_compra';
        }
        if ($oc->id_tp_documento == 3) { // servicio
            $arrayTipoOrden = 'orden_servicio';
        }
        if ($oc->id_tp_documento == 12) { // importacion
            $arrayTipoOrden = 'orden_importacion';
        }

        foreach ($ultimosCorrelativosSoftlink1[$arrayTipoOrden] as $key => $value) {
            if ($value['nombre'] == $oc->codigo_emp) {
                $ult_correlativo_softlink1 = $value['ultimo_correlativo_soft1'];
            }
        }
        //


        //obtiene el ultimo registro
        $ult_mov = DB::connection(app('conexion_softlink'))->table('movimien')
            ->where([
                ['num_docu', '>', $yy . '0000000'],
                ['num_docu', '<', $yy . '9999999'],
                ['cod_suc', '=', $cod_suc],
                ['tipo', '=', 1], //ingreso
                ['cod_docu', '=', $cod_docu]
            ])
            ->orderBy('num_docu', 'desc')->first();
        //obtiene el correlativo
        $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
        //crea el correlativo del documento
        $nro_mov = $this->leftZero(7, (intval($num_ult_mov)  + (intval($num_ult_mov)>0?0:$ult_correlativo_softlink1)+ 1));
        //anida el anio con el numero de documento

        $num_docu = $yy . $nro_mov;

        $mov_id = $this->agregarOrden($mov_id, $cod_suc, $oc, $cod_docu, $num_docu, $fecha, $cod_auxi, $igv, $mon_impto, $tp_cambio, $id_orden_compra, $cuadros);

        $i = 0;
        foreach ($detalles as $det) {
            $cod_prod = null;
            //Obtiene y/o crea el producto
            if ($det->id_producto !== null) {
                $cod_prod = $this->obtenerProducto($det);
            } else {
                $cod_prod = '005675'; //OTROS SERVICIOS - DEFAULT
            }
            $this->agregarDetalleOrden($det, $mov_id, $cod_prod, $cod_docu, $num_docu, $fecha, $igv, $i);
            $this->actualizaStockEnTransito($oc, $cod_prod, $det, $cod_suc);
        }
        $this->agregarAudita($oc, $yy, $nro_mov, 'NUEVO');

        $soc = DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $mov_id)->first();
        $sdet = DB::connection(app('conexion_softlink'))->table('detmov')->where('mov_id', $mov_id)->get();

        $arrayRspta = array(
            'tipo' => 'success',
            'mov_id' => $mov_id,
            'mensaje' => 'Se migró correctamente la OC Nro. ' . $num_docu . ' con id ' . $mov_id,
            'orden_softlink' => $num_docu, //($yy . '-' . $nro_mov),
            'ocSoftlink' => array('cabecera' => $soc, 'detalle' => $sdet),
            'ocAgile' => array('cabecera' => $oc, 'detalle' => $detalles),
            'soc' => $soc,
            'sdet' => $sdet
        );

        return $arrayRspta;
    }

    public function obtenerMovId()
    {
        $count = DB::connection(app('conexion_softlink'))->table('movimien')->count();
        //codificar segun criterio x documento
        $mov_id = $this->leftZero(10, (intval($count) + 1));

        return $mov_id;
    }

    public function actualizarOrdenEnSoftlink($oc_softlink, $id_orden_compra, $oc, $detalles, $cod_suc, $cod_docu, $cuadros, $yy, $fecha, $igv, $tp_cambio)
    {

        if ($oc_softlink->cod_docu != $cod_docu) { // si la empresa de agil es distinta a la empresa de softlink

            $migrarOrdenSoftlink = $this->anularOrdenSoftlink($id_orden_compra)->original;

            $respuestaAnularOrdenSoftlink =  array(
                'tipo' => $migrarOrdenSoftlink['tipo'],
                'mensaje' => $migrarOrdenSoftlink['mensaje'],
                'ocSoftlink' => $migrarOrdenSoftlink['ocSoftlink'],
                'ocAgile' => $migrarOrdenSoftlink['ocAgile'],
            );

            //persona juridica x defecto
            $doc_tipo = ($oc->id_tipo_contribuyente !== null
                ? ($oc->id_tipo_contribuyente <= 2 ? 2 : 1)
                : 1);
            //por defecto ruc
            $cod = ($oc->cod_di !== null ? $oc->cod_di : '06');
            //obtiene o crea el proveedor
            $cod_auxi = $this->obtenerProveedor($oc->ruc, $oc->razon_social, $doc_tipo, $cod);
            //Calcular IGV
            if ($oc->incluye_igv) {
                $mon_impto = (floatval($oc->total_precio) * ($igv / 100));
            } else {
                $mon_impto = 0;
            }

            $mov_id = $this->obtenerMovId();


        // obtener ultimo correlativo de softlink1
        $ultimosCorrelativosSoftlink1 = $this->getCorrelativoDocumentoSoftlinkStatic();
        $ult_correlativo_softlink1 = 0;
        $arrayTipoOrden='';
        if ($oc->id_tp_documento == 2) { // compra
            $arrayTipoOrden = 'orden_compra';
        }
        if ($oc->id_tp_documento == 3) { // servicio
            $arrayTipoOrden = 'orden_servicio';
        }
        if ($oc->id_tp_documento == 12) { // importacion
            $arrayTipoOrden = 'orden_importacion';
        }

        foreach ($ultimosCorrelativosSoftlink1[$arrayTipoOrden] as $key => $value) {
            if ($value['nombre'] == $oc->codigo_emp) {
                $ult_correlativo_softlink1 = $value['ultimo_correlativo_soft1'];
            }
        }
        //


            //obtiene el ultimo registro
            $ult_mov = DB::connection(app('conexion_softlink'))->table('movimien')
                ->where([
                    ['num_docu', '>', $yy . '0000000'],
                    ['num_docu', '<', $yy . '9999999'],
                    ['cod_suc', '=', $cod_suc],
                    ['tipo', '=', 1], //ingreso
                    ['cod_docu', '=', $cod_docu]
                ])
                ->orderBy('num_docu', 'desc')->first();
            //obtiene el correlativo
            $num_ult_mov = substr(($ult_mov !== null ? $ult_mov->num_docu : 0), 4);
            //crea el correlativo del documento
            $nro_mov = $this->leftZero(7, (intval($num_ult_mov) +  (intval($num_ult_mov)>0?0:intval($ult_correlativo_softlink1))  + 1));
            //anida el anio con el numero de documento
            $num_docu = $yy . $nro_mov;


            $mov_id = $this->agregarOrden($mov_id, $cod_suc, $oc, $cod_docu, $num_docu, $fecha, $cod_auxi, $igv, $mon_impto, $tp_cambio, $id_orden_compra, $cuadros);

            $i = 0;
            foreach ($detalles as $det) {
                $cod_prod = null;
                //Obtiene y/o crea el producto
                if ($det->id_producto !== null) {
                    $cod_prod = $this->obtenerProducto($det);
                } else {
                    $cod_prod = '005675'; //OTROS SERVICIOS - DEFAULT
                }
                $this->agregarDetalleOrden($det, $mov_id, $cod_prod, $cod_docu, $num_docu, $fecha, $igv, $i);
                $this->actualizaStockEnTransito($oc, $cod_prod, $det, $cod_suc);
            }
            $this->agregarAudita($oc, $yy, $nro_mov, 'NUEVO');

            $soc = DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $mov_id)->first();
            $sdet = DB::connection(app('conexion_softlink'))->table('detmov')->where('mov_id', $mov_id)->get();

            $arrayRspta = array(
                'tipo' => 'success',
                'mov_id' => $mov_id,
                'mensaje' => $respuestaAnularOrdenSoftlink['mensaje'] . '. Se migró correctamente la OC Nro. ' . $num_docu . ' con id ' . $mov_id,
                'orden_softlink' => $num_docu, //($yy . '-' . $nro_mov),
                'ocSoftlink' => array('cabecera' => $soc, 'detalle' => $sdet),
                'ocAgile' => array('cabecera' => $oc, 'detalle' => $detalles),
                'soc' => $soc,
                'sdet' => $sdet
            );
        } else {

            //persona juridica x defecto
            $doc_tipo = ($oc->id_tipo_contribuyente !== null
                ? ($oc->id_tipo_contribuyente <= 2 ? 2 : 1)
                : 1);
            //por defecto ruc
            $cod = ($oc->cod_di !== null ? $oc->cod_di : '06');
            $cod_auxi = $this->obtenerProveedor($oc->ruc, $oc->razon_social, $doc_tipo, $cod);
            //Calcular IGV
            if ($oc->incluye_igv) {
                $mon_impto = (floatval($oc->total_precio) * ($igv / 100));
            } else {
                $mon_impto = 0;
            }

            DB::connection(app('conexion_softlink'))->table('movimien')
                ->where('mov_id', $oc_softlink->mov_id)
                ->update(
                    [
                        'cod_suc' => $cod_suc,
                        'cod_alma' => $oc->codigo_almacen,
                        'fec_docu' => $fecha,
                        'fec_entre' => $fecha,
                        'fec_vcto' => $fecha,
                        'cod_auxi' => $cod_auxi,
                        'cod_vend' => $oc->codvend_softlink,
                        'tip_mone' => $oc->id_moneda,
                        'tip_codicion' => $oc->id_condicion_softlink,
                        'impto1' => $igv,
                        'mon_bruto' => $oc->total_precio,
                        'mon_impto1' => $mon_impto,
                        'mon_total' => ($oc->total_precio + $mon_impto),
                        'txt_observa' => ($oc->codigo) . ' / ' . ($oc->observacion !== null ? $oc->observacion : ''),
                        'cod_user' => $oc->codvend_softlink,
                        'tip_cambio' => $tp_cambio->cambio3, //tipo cambio venta
                        'ndocu1' => ($oc->plazo_entrega !== null ? $oc->plazo_entrega . ' DIAS' : ''),
                        'ndocu2' => ($oc->direccion_destino !== null ? $oc->direccion_destino . ' ' . ($oc->ubigeo_destino !== null ? $oc->ubigeo_destino : '') : ''),
                        'ndocu3' => ($oc->codigo) . '/' . implode(', ', $cuadros)
                    ]
                );

            $i = 0;
            foreach ($detalles as $det) {
                $i++;
                //Obtiene y/o crea el producto
                if ($det->id_producto !== null) {
                    $cod_prod = $this->obtenerProducto($det);
                } else {
                    $cod_prod = '005675'; //OTROS SERVICIOS - DEFAULT
                }

                if ($det->id_oc_det_softlink !== null) {
                    //actualiza el detalle
                    DB::connection(app('conexion_softlink'))->table('detmov')
                        ->where('unico', $det->id_oc_det_softlink)
                        ->update([
                            'fec_pedi' => $fecha,
                            'cod_auxi' => trim($det->abreviatura),
                            'cod_prod' => $cod_prod,
                            'nom_prod' => ($cod_prod == '005675' ? 'OTROS SERVICIOS - ' . $det->descripcion_adicional : $det->descripcion),
                            'can_pedi' => $det->cantidad,
                            'sal_pedi' => $det->cantidad,
                            'can_devo' => $i, //numeracion del item
                            'pre_prod' => ($det->precio !== null ? $det->precio : 0),
                            'pre_neto' => ($det->precio !== null ? ($det->precio * $det->cantidad) : 0),
                            'impto1' => $igv,
                            'imp_item' => ($det->precio !== null ? ($det->precio * $det->cantidad) : 0),
                            'flg_serie' => ($cod_prod == '005675' ? 0 : ($det->series ? 1 : 0)),
                            // 'ok_serie' => ($det->series ? '1' : '0'),
                        ]);
                } else {
                    $this->agregarDetalleOrden($det, $oc->id_softlink, $cod_prod, $oc_softlink->cod_docu, $oc_softlink->num_docu, $fecha, $igv, $i);
                }
            }
            $arrayRspta = array(
                'tipo' => 'success',
                'mensaje' => 'Se actualizó ésta OC en softlink. Con Nro. ' . $oc_softlink->num_docu . ' con id ' . $oc_softlink->mov_id,
                'orden_softlink' => $oc_softlink->num_docu,
                'ocSoftlink' => array('cabecera' => $oc_softlink),
                'ocAgile' => array('cabecera' => $oc),
            );

            //Actualiza la oc softlink eb agile
            $ordenActualizada = Orden::find($id_orden_compra);
            $ordenActualizada->codigo_softlink = $oc_softlink->num_docu;
            $ordenActualizada->id_softlink = $oc_softlink->mov_id;
            $ordenActualizada->save();

            $this->agregarAudita($oc, $yy, $oc_softlink->num_docu, 'MODIFICO');

            $this->agregarLogActividad('MODIFICO', $oc_softlink->mov_id, $oc_softlink->num_docu, $ordenActualizada);
        }
        return $arrayRspta;
    }

    public function agregarOrden($mov_id, $cod_suc, $oc, $cod_docu, $num_docu, $fecha, $cod_auxi, $igv, $mon_impto, $tp_cambio, $id_orden_compra, $cuadros)
    {

        // actualizar el mov_id por si existe ya otro registro agregdo anteriormente con el mismo mov_id
        $mov_id = $this->obtenerMovId();


        DB::connection(app('conexion_softlink'))->table('movimien')->insert(
            [
                'mov_id' => $mov_id,
                'tipo' => '1', //Compra
                'cod_suc' => $cod_suc,
                'cod_alma' => $oc->codigo_almacen,
                'cod_docu' => $cod_docu,
                'num_docu' => $num_docu,
                'fec_docu' => $fecha,
                'fec_entre' => $fecha,
                'fec_vcto' => $fecha,
                'flg_sitpedido' => 0, //
                'cod_pedi' => '',
                'num_pedi' => '',
                'cod_auxi' => $cod_auxi,
                'cod_trans' => '00000',
                'cod_vend' => $oc->codvend_softlink,
                'tip_mone' => $oc->id_moneda,
                'impto1' => $igv,
                'impto2' => '0.00',
                'mon_bruto' => $oc->total_precio,
                'mon_impto1' => $mon_impto,
                'mon_impto2' => '0.00',
                'mon_gravado' => '0.00',
                'mon_inafec' => '0.00',
                'mon_exonera' => '0.00',
                'mon_gratis' => '0.00',
                'mon_total' => ($oc->total_precio + $mon_impto),
                'sal_docu' => '0.00',
                'tot_cargo' => '0.00',
                'tot_percep' => '0.00',
                'tip_codicion' => $oc->id_condicion_softlink,
                'txt_observa' => ($oc->codigo) . ' / ' . ($oc->observacion !== null ? $oc->observacion : ''),
                'flg_kardex' => 0,
                'flg_anulado' => 0,
                'flg_referen' => 0,
                'flg_percep' => 0,
                'cod_user' => $oc->codvend_softlink,
                'programa' => '',
                'txt_nota' => '',
                'tip_cambio' => $tp_cambio->cambio3, //tipo cambio venta
                'tdflags' => 'NSSNNSSNSS',
                'numlet' => '',
                'impdcto' => '0.0000',
                'impanticipos' => '0.0000',
                'registro' => new Carbon(), //date('Y-m-d H:i:s'),
                'tipo_canje' => '0',
                'numcanje' => '',
                'cobrobco' => 0,
                'ctabco' => '',
                'flg_qcont' => 0,
                'fec_anul' => '0000-00-00',
                'audit' => '2',
                'origen' => '',
                'tip_cont' => '',
                'tip_fact' => '',
                'contrato' => '',
                'idcontrato' => '',
                'canje_fact' => 0,
                'aceptado' => 0,
                'reg_conta' => 0,
                'mov_pago' => '',
                'ndocu1' => ($oc->plazo_entrega !== null ? $oc->plazo_entrega . ' DIAS' : ''),
                'ndocu2' => ($oc->direccion_destino !== null ? $oc->direccion_destino . ' ' . ($oc->ubigeo_destino !== null ? $oc->ubigeo_destino : '') : ''),
                'ndocu3' => ($oc->codigo) . '/' . implode(', ', $cuadros),
                'flg_logis' => 1,
                'cod_recep' => '',
                'flg_aprueba' => 0,
                'fec_aprueba' => '0000-00-00 00:00:00.000000',
                'flg_limite' => 0,
                'fecpago' => '0000-00-00',
                'imp_comi' => '0.00',
                'ptosbonus' => '0',
                'canjepedtran' => 0,
                'cod_clasi' => 1, //mercaderias
                'doc_elec' => '',
                'cod_nota' => '',
                'hashcpe' => '',
                'flg_sunat_acep' => 0,
                'flg_sunat_anul' => 0,
                'flg_sunat_mail' => 0,
                'flg_sunat_webs' => 0,
                'mov_id_baja' => '',
                'mov_id_resu_bv' => '',
                'mov_id_resu_ci' => '',
                'flg_guia_traslado' => 0,
                'flg_anticipo_doc' => 0,
                'flg_anticipo_reg' => 0,
                'doc_anticipo_id' => '',
                'flg_emi_itinerante' => 0,
                'placa' => ''
            ]
        );
        //Actualiza la oc softlink eb agile

        $ordenActualizada = Orden::find($id_orden_compra);
        $ordenActualizada->codigo_softlink = $num_docu;
        $ordenActualizada->id_softlink = $mov_id;
        $ordenActualizada->save();

        $this->agregarLogActividad('NUEVO', $mov_id, $num_docu, $ordenActualizada);

        return $mov_id;
    }

    public function agregarDetalleOrden($det, $mov_id, $cod_prod, $cod_docu, $num_docu, $fecha, $igv, $i)
    {
        //cuenta los registros
        $count_det = DB::connection(app('conexion_softlink'))->table('detmov')->count();
        //aumenta uno y completa los 10 digitos
        $mov_det_id = $this->leftZero(10, (intval($count_det) + 1));
        // $suma_unica = 0;
        // do {
        //     $suma_unica = $suma_unica + 1;
        //     $mov_det_id = $this->leftZero(10, (intval($count_det) + $suma_unica));

        //     $buscar = DB::connection(app('conexion_softlink'))->table('detmov')
        //     ->where([
        //         ['unico', '=', $count_det]
        //     ])->first();
        // } while ($buscar);
        //Obtiene y/o crea el producto
        // $cod_prod = $this->obtenerProducto($det);

        DB::connection(app('conexion_softlink'))->table('detmov')->insert(
            [
                'unico' => $mov_det_id,
                'mov_id' => $mov_id,
                'tipo' => '1', //Compra
                'cod_docu' => $cod_docu,
                'num_docu' => $num_docu,
                'fec_pedi' => $fecha,
                'cod_auxi' => trim($det->abreviatura),
                'cod_prod' => $cod_prod,
                // 'nom_prod' => $det->descripcion,
                'nom_prod' => ($cod_prod == '005675' ? 'OTROS SERVICIOS - ' . $det->descripcion_adicional : $det->descripcion),
                'can_pedi' => $det->cantidad,
                'sal_pedi' => $det->cantidad,
                'can_devo' => $i, //numeracion del item
                'pre_prod' => ($det->precio !== null ? $det->precio : 0),
                'dscto_condi' => '0.000',
                'dscto_categ' => '0.000',
                'pre_neto' => ($det->precio !== null ? ($det->precio * $det->cantidad) : 0),
                'igv_inclu' => 0,
                'cod_igv' => '',
                'impto1' => $igv,
                'impto2' => '0.00',
                'imp_item' => ($det->precio !== null ? ($det->precio * $det->cantidad) : 0),
                'pre_gratis' => '0.0000',
                'descargo' => '*',
                'trecord' => '',
                'cod_model' => '',
                'flg_serie' => ($cod_prod == '005675' ? 0 : ($det->series ? 1 : 0)),
                'series' => '',
                'entrega' => 0,
                'notas' => '',
                'flg_percep' => 0,
                'por_percep' => 0,
                'mon_percep' => 0,
                'ok_stk' => 1,
                'ok_serie' => 1,
                'lStock' => 0,
                'no_calc' => 0,
                'promo' => 0,
                'seriesprod' => '',
                'pre_anexa' => 0,
                'dsctocompra' => 0,
                'cod_prov' => '',
                'costo_unit' => 0,
                // 'margen' => 0,
                'peso' => 0,
                'gasto1' => 0,
                'gasto2' => 0,
                'flg_detrac' => 0,
                'por_detrac' => 0,
                'cod_detrac' => '',
                'mon_detrac' => 0,
                'tipoprecio' => '6'
            ]
        );
        DB::table('logistica.log_det_ord_compra')
            ->where('id_detalle_orden', $det->id_detalle_orden)
            ->update(['id_oc_det_softlink' => $mov_det_id]);
    }

    public function actualizaStockEnTransito($oc, $cod_prod, $det, $cod_suc)
    {
        //OBTIENE STOCK EN TRANSITO
        $stock = DB::connection(app('conexion_softlink'))->table('stocks')
            ->where([
                ['cod_alma', '=', $oc->codigo_almacen],
                ['cod_prod', '=', $cod_prod]
            ])->first();

        if ($stock !== null) {
            //ACTUALIZA STOCK EN TRANSITO
            DB::connection(app('conexion_softlink'))->table('stocks')
                ->update(['stock_ing' => (floatval($stock->stock_ing) + floatval($det->cantidad))]);
        } else {
            //CREA
            DB::connection(app('conexion_softlink'))->table('stocks')
                ->insert([
                    'cod_suc' => $cod_suc,
                    'cod_alma' => $oc->codigo_almacen,
                    'cod_prod' => $cod_prod,
                    'stock_act' => 0,
                    'stock_ing' => $det->cantidad,
                    'stock_ped' => 0,
                    'stock_min' => 0,
                    'stock_max' => 0,
                    'cod_ubic' => '',
                ]);
        }
    }

    public function agregarAudita($oc, $yy, $nro_mov, $accion)
    {
        $vendedor = DB::connection(app('conexion_softlink'))->table('vendedor')
            ->select('usuario')
            ->where('codvend', $oc->codvend_softlink)
            ->first();

        $count = DB::connection(app('conexion_softlink'))->table('audita')->count();

        //Agrega registro de auditoria
        if ($accion == 'NUEVO') {
            DB::connection(app('conexion_softlink'))->table('audita')
                ->insert([
                    'unico' => sprintf('%010d', $count + 1),
                    'usuario' => $oc->codvend_softlink,
                    'terminal' => $vendedor->usuario,
                    'fecha_hora' => new Carbon(),
                    'accion' => $accion . ': OC ' . $yy . '-' . $nro_mov
                ]);
        } elseif ($accion == 'MODIFICO') {
            DB::connection(app('conexion_softlink'))->table('audita')
                ->insert([
                    'unico' => sprintf('%010d', $count + 1),
                    'usuario' => $oc->codvend_softlink,
                    'terminal' => $vendedor->usuario,
                    'fecha_hora' => new Carbon(),
                    'accion' => $accion . ': NUMERO DOC. ' . $nro_mov
                ]);
        }
    }

    public function agregarLogActividad($accion, $mov_id, $num_docu, $tabla)
    {
        //Agrega registro de auditoria AGILE
        switch ($accion) {
            case 'NUEVO':
                $comentario = 'Se creo y migro la orden a softlink: ' . $num_docu . ', ID: ' . $mov_id . ' Actualizado por: ' . Auth::user()->nombre_corto;
                $tipoLog = 2;
                break;

            case 'MODIFICO':
                $comentario = 'Se actualizó y migro la orden a softlink: ' . $num_docu . ', ID: ' . $mov_id . ' Actualizado por: ' . Auth::user()->nombre_corto;
                $tipoLog = 3;
                break;

            default:
                # code...
                break;
        }
        if ($accion == 'NUEVO' || $accion == 'MODIFICO') {
            LogActividad::registrar(Auth::user(), 'Orden de compra / servicio', $tipoLog, $tabla->getTable(), null, $tabla, $comentario, 'Logistica');
        }
    }


    public function obtenerProducto($det)
    {
        //Verifica si esxiste el producto
        $prod = null;
        if (!empty($det->part_number)) { //if ($det->part_number !== null && $det->part_number !== '') {
            $prod = DB::connection(app('conexion_softlink'))->table('sopprod')
                ->select('cod_prod')
                ->join('sopsub2', 'sopsub2.cod_sub2', '=', 'sopprod.cod_subc')
                ->where([
                    ['sopprod.cod_espe', '=', trim($det->part_number)],
                    ['sopsub2.nom_sub2', '=', $det->subcategoria]
                ])
                ->first();
        } else if ($det->descripcion !== null && $det->descripcion !== '') {
            $prod = DB::connection(app('conexion_softlink'))->table('sopprod')
                ->select('cod_prod')
                ->join('sopsub2', 'sopsub2.cod_sub2', '=', 'sopprod.cod_subc')
                ->where([
                    ['nom_prod', '=', trim($det->descripcion)],
                    ['sopsub2.nom_sub2', '=', $det->subcategoria]
                ])
                ->first();
        }
        $cod_prod = null;
        //Si existe copia el cod_prod
        if ($prod !== null) {
            $cod_prod = $prod->cod_prod;
        } //Si no existe, genera el producto
        else {
            //obtiene el sgte codigo
            $ultimo = DB::connection(app('conexion_softlink'))->table('sopprod')
                ->select('cod_prod')
                ->where([['cod_prod', '!=', 'TEXTO']])
                ->orderBy('cod_prod', 'desc')
                ->first();

            $cod_prod = $this->leftZero(6, (intval($ultimo->cod_prod) + 1));

            $cod_clasi = $this->obtenerClasificacion($det->grupo);

            $cod_cate = $this->obtenerCategoria($det->categoria, $det->id_categoria);

            $cod_subc = $this->obtenerSubCategoria($det->subcategoria, $det->id_subcategoria);

            $cod_unid = $this->obtenerUnidadMedida($det->abreviatura);

            DB::connection(app('conexion_softlink'))->table('sopprod')->insert(
                [
                    'cod_prod' => $cod_prod,
                    'cod_clasi' => $cod_clasi,
                    'cod_cate' => $cod_cate,
                    'cod_subc' => $cod_subc,
                    'cod_prov' => '',
                    'cod_espe' => trim($det->part_number),
                    'cod_sunat' => '',
                    'nom_prod' => trim($det->descripcion),
                    'cod_unid' => $cod_unid,
                    'nom_unid' => trim($det->abreviatura),
                    'fac_unid' => 1,
                    'kardoc_costo' => '0.000',
                    'kardoc_stock' => '0.000',
                    'kardoc_ultingfec' => '0000-00-00',
                    'kardoc_ultingcan' => '0.000',
                    'kardoc_unico' => '',
                    'fec_ingre' => date('Y-m-d'),
                    'flg_descargo' => 1,
                    'tip_moneda' => $det->id_moneda,
                    'flg_serie' => ($det->series ? 1 : 0), //Revisar
                    'txt_observa' => ($det->notas !== null ? $det->notas : ''),
                    'flg_afecto' => 1,
                    'flg_suspen' => 0,
                    'apl_lista' => 3,
                    'foto' => '',
                    'aweb' => '',
                    'bi_c' => '',
                    'impto1_c' => '',
                    'impto2_c' => '',
                    'impto3_c' => '',
                    'dscto_c' => '',
                    'bi_v' => '',
                    'impto1_v' => '',
                    'impto2_v' => '',
                    'impto3_v' => '',
                    'dscto_v' => '',
                    'cta_s_caja' => 0,
                    'cta_d_caja' => '',
                    'cod_ubic' => '',
                    'peso' => 0,
                    'flg_percep' => 0,
                    'por_percep' => 0,
                    'gasto' => 0,
                    'dsctocompra' => 0,
                    'dsctocompra2' => 0,
                    'cod_promo' => '',
                    'can_promo' => 0,
                    'ult_edicion' => date('Y-m-d H:i:s'),
                    'ptosbonus' => 0,
                    'bonus_moneda' => 0,
                    'bonus_importe' => 0,
                    'flg_detrac' => 0,
                    'por_detrac' => 0,
                    'cod_detrac' => '',
                    'mon_detrac' => 0,
                    'largo' => 0,
                    'ancho' => 0,
                    'area' => 0,
                    'aweb' => 0,
                    'id_product' => 0,
                    'width' => 0,
                    'height' => 0,
                    'depth' => 0,
                    'weight' => 0,
                    'costo_adicional' => 0
                ]
            );

            $sucursales = DB::connection(app('conexion_softlink'))->table('sucursal')->get();

            foreach ($sucursales as $suc) {
                $prod = DB::connection(app('conexion_softlink'))->table('precios')
                    ->where([['cod_prod', '=', $cod_prod], ['cod_suc', '=', $suc->cod_suc]])
                    ->first();

                if ($prod == null) {
                    DB::connection(app('conexion_softlink'))->table('precios')->insert(
                        [
                            'cod_prod' => $cod_prod,
                            'cod_suc' => $suc->cod_suc,
                            'en_lista' => 1,
                            'lsupendido' => 0,
                            'fecha_susp' => '0000-00-00',
                            'precio_venta' => 0,
                            'precio_mayor' => 0,
                            'precio_tres' => 0,
                            'precio_cuatro' => 0,
                            'precio_cinco' => 0,
                            'precio_seis' => 0,
                            'precio_costo' => 0,
                            'precio_inver' => 0,
                            'precio_refer' => 0,
                            'porct_1' => 0,
                            'porct_2' => 0,
                            'porct_3' => 0,
                            'porct_4' => 0,
                            'porct_5' => 0,
                            'porct_6' => 0,
                            'costo_ultimo' => 0
                        ]
                    );
                }
            }

            $almacenes = DB::connection(app('conexion_softlink'))->table('almacen')->get();

            foreach ($almacenes as $alm) {
                $stock = DB::connection(app('conexion_softlink'))->table('stocks')
                    ->where([['cod_suc', '=', $alm->cod_suc], ['cod_alma', '=', $alm->cod_alma], ['cod_prod', '=', $cod_prod]])
                    ->first();

                if ($stock == null) {
                    DB::connection(app('conexion_softlink'))->table('stocks')->insert(
                        [
                            'cod_suc' => $alm->cod_suc,
                            'cod_alma' => $alm->cod_alma,
                            'cod_prod' => $cod_prod,
                            'stock_act' => 0,
                            'stock_ing' => 0,
                            'stock_ped' => 0,
                            'stock_min' => 0,
                            'stock_max' => 0,
                            'cod_ubic' => '',
                        ]
                    );
                }
            }
        }
        DB::table('almacen.alm_prod')
            ->where('id_producto', $det->id_producto)
            ->update(['cod_softlink' => $cod_prod]);

        DB::table('almacen.producto_sap')
            ->where('codigo_agile', $det->codigo_agile)
            ->update(['cod_softlink' => $cod_prod]);

        return $cod_prod;
    }

    public function obtenerClasificacion($clasificacion)
    {
        //verifica si tiene clasificacion
        $clasif = DB::connection(app('conexion_softlink'))->table('soplinea')
            ->select('cod_line')
            ->where('nom_line', trim($clasificacion))
            ->first();

        $cod_clasi = null;

        if ($clasif !== null) {
            $cod_clasi = $clasif->cod_line;
        } else {
            $ultimo_line = DB::connection(app('conexion_softlink'))->table('soplinea')
                ->select('cod_line')->orderBy('cod_line', 'desc')->first();

            $cod_clasi = $this->leftZero(2, (intval($ultimo_line->cod_line) + 1));

            DB::connection(app('conexion_softlink'))->table('soplinea')->insert(
                [
                    'cod_line' => $cod_clasi,
                    'nom_line' => trim($clasificacion),
                    'cod_sunat' => '',
                    'cod_osce' => ''
                ]
            );
        }
        return $cod_clasi;
    }

    public function obtenerCategoria($categoria, $id_categoria)
    {
        //verifica si existe categoria
        $cate = DB::connection(app('conexion_softlink'))->table('sopsub1')
            ->select('cod_sub1')
            ->where('nom_sub1', trim($categoria))
            ->first();

        $cod_cate = null;

        if ($cate !== null) {
            $cod_cate = $cate->cod_sub1;
        } else {
            $ultima_cate = DB::connection(app('conexion_softlink'))->table('sopsub1')
                ->select('cod_sub1')->orderBy('cod_sub1', 'desc')->first();

            $cod_cate = $this->leftZero(3, (intval($ultima_cate->cod_sub1) + 1));

            DB::connection(app('conexion_softlink'))->table('sopsub1')->insert(
                [
                    'cod_sub1' => $cod_cate,
                    'nom_sub1' => trim($categoria),
                    'por_dcto' => 0,
                    'num_corr' => 0
                ]
            );

            DB::table('almacen.alm_tp_prod')
                ->where('id_tipo_producto', $id_categoria)
                ->update(['cod_softlink' => $cod_cate]);
        }
        return $cod_cate;
    }

    public function obtenerSubCategoria($subcategoria, $id_subcategoria)
    {
        //verifica si existe subcategoria
        $subcate = DB::connection(app('conexion_softlink'))->table('sopsub2')
            ->select('cod_sub2')
            ->where('nom_sub2', trim($subcategoria))
            ->first();

        $cod_subc = null;

        if ($subcate !== null) {
            $cod_subc = $subcate->cod_sub2;
        } else {
            $ultima_subc = DB::connection(app('conexion_softlink'))->table('sopsub2')
                ->select('cod_sub2')->orderBy('cod_sub2', 'desc')->first();

            $cod_subc = $this->leftZero(3, (intval($ultima_subc->cod_sub2) + 1));

            DB::connection(app('conexion_softlink'))->table('sopsub2')->insert(
                [
                    'cod_sub2' => $cod_subc,
                    'nom_sub2' => trim($subcategoria),
                    'por_adic' => '0.00',
                    'cod_sub1' => '',
                    'id_manufacturer' => 0
                ]
            );

            DB::table('almacen.alm_subcat')
                ->where('id_subcategoria', $id_subcategoria)
                ->update(['cod_softlink' => $cod_subc]);
        }
        return $cod_subc;
    }

    public function obtenerUnidadMedida($abreviatura)
    {
        //verifica si existe unidad medida
        $unidad = DB::connection(app('conexion_softlink'))->table('unidades')
            ->select('cod_unid')
            ->where('nom_unid', trim($abreviatura))
            ->first();

        $cod_unid = null;

        if ($unidad !== null) {
            $cod_unid = $unidad->cod_unid;
        } else {
            $count_unid = DB::connection(app('conexion_softlink'))->table('unidades')->count();

            $cod_unid = $this->leftZero(3, (intval($count_unid) + 1));

            DB::connection(app('conexion_softlink'))->table('unidades')->insert(
                [
                    'cod_unid' => $cod_unid,
                    'nom_unid' => trim($abreviatura),
                    'fac_unid' => '1'
                ]
            );
        }

        DB::table('almacen.alm_und_medida')
            ->where('abreviatura', trim($abreviatura))
            ->update(['cod_softlink' => $cod_unid]);

        return $cod_unid;
    }

    public function obtenerProveedor($nro_documento, $razon_social, $doc_tipo, $cod_di)
    {
        if ($nro_documento !== null && $nro_documento !== '') {
            $proveedor = DB::connection(app('conexion_softlink'))->table('auxiliar')
                ->select('cod_auxi')
                ->where([
                    ['ruc_auxi', '=', $nro_documento],
                    ['tip_auxi', '=', 'P']
                ])
                ->first();
        } else {
            $proveedor = DB::connection(app('conexion_softlink'))->table('auxiliar')
                ->select('cod_auxi')
                ->where([
                    ['nom_auxi', '=', $razon_social],
                    ['tip_auxi', '=', 'P']
                ])
                ->first();
        }

        $cod_auxi = null;

        if ($proveedor == null) {
            //obtiene el codigo mayor
            $mayor = DB::connection(app('conexion_softlink'))->table('auxiliar')
                ->select('cod_auxi')
                ->where([
                    ['cod_auxi', '!=', 'TRANSF'],
                    // ['tip_auxi', '=', 'P']
                ])
                ->orderBy('cod_auxi', 'desc')
                ->first();
            //le aumenta 1 al codigo mayor
            $cod_auxi = $this->leftZero(6, (intval($mayor->cod_auxi) + 1));


            DB::connection(app('conexion_softlink'))->table('auxiliar')->insert(
                [
                    'tip_auxi' => 'P',
                    'cod_auxi' => $cod_auxi,
                    'nom_auxi' => $razon_social,
                    'nom_contac' => '',
                    'car_contac' => '',
                    'dir_auxi' => '', //($req->direccion_entrega !== null ? $req->direccion_entrega : ''),
                    'dir_entre' => '',
                    'tel_auxi' => '', //($req->telefono !== null ? $req->telefono : ''),
                    'fax_auxi' => '',
                    'doc_tipo' => $doc_tipo,
                    'ruc_auxi' => $nro_documento,
                    'doc_auxi' => '',
                    'est_auxi' => '',
                    'hijos_auxi' => '0',
                    'sexo_auxi' => '',
                    'fnac_auxi' => '0000-00-00',
                    'cod_di' => $cod_di,
                    'cre_moneda' => '0',
                    'max_credi' => '0.0000',
                    'util_credi' => '0.0000',
                    'fec_credi' => '0000-00-00',
                    'nom_aval' => '',
                    'ruc_aval' => '',
                    'dir_aval' => '',
                    'tel_aval' => '',
                    'fax_aval' => '',
                    'doc_aval' => '',
                    'cod_zona' => '000',
                    'tip_clasi' => '00',
                    'cta1' => '',
                    'cta2' => '',
                    'codvend' => '',
                    'condicion' => '',
                    'aux_qcont' => '',
                    'website' => '',
                    'email' => '', //($req->email !== null ? $req->email : ''),
                    'visita' => '',
                    'notas' => '',
                    'notas2' => '',
                    'v_tipo' => '',
                    'v_nombre' => '',
                    'v_numero' => '',
                    'v_interior' => '',
                    'v_zona' => '',
                    'v_distrito' => '',
                    'v_provincia' => '',
                    'v_depart' => '',
                    'cta3' => '',
                    'cta4' => '',
                    'fec_llama' => '0000-00-00',
                    'asunto' => '0',
                    'flg_percep' => 0,
                    'flg_reten' => 0,
                    'por_reten' => '0',
                    'flg_baja' => 0,
                    'fec_baja' => '0000-00-00',
                    'dias_cred' => 0,
                    'tipo_auxi' => 0,
                    'ult_edicion' => date('Y-m-d H:i:s'),
                    'ptosbonus' => '0',
                    'canje_bonus' => '0000-00-00',
                    'id_pais' => 'PE',
                    'cta_detrac' => ''
                ]
            );
        } else {
            $cod_auxi = $proveedor->cod_auxi;
        }
        return $cod_auxi;
    }

    public function leftZero($lenght, $number)
    {
        $nLen = strlen($number);
        $zeros = '';
        for ($i = 0; $i < ($lenght - $nLen); $i++) {
            $zeros = $zeros . '0';
        }
        return $zeros . $number;
    }

    public function anularOrdenSoftlink($id_orden_compra)
    {
        try {
            DB::beginTransaction();

            $oc = DB::table('logistica.log_ord_compra')
                ->where('id_orden_compra', $id_orden_compra)
                ->first();
            //si existe un id_softlink
            if (!empty($oc->id_softlink)) {
                //pregunta si ya se ha migrado antes
                $oc_softlink = DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $oc->id_softlink)->first();

                //verifica si ya fue referenciado
                $guia_referen = DB::connection(app('conexion_softlink'))->table('movimien')
                    ->where([
                        ['cod_pedi', '=', $oc_softlink->cod_docu],
                        ['num_pedi', '=', $oc_softlink->num_docu],
                        ['flg_anulado', '=', 0]
                    ])
                    ->first();
                if ($guia_referen !== null) {
                    //Ya tiene ingreso a almacen
                    $arrayRspta = array(
                        'tipo' => 'warning',
                        'mensaje' => 'Ésta orden ya fue referenciada en Softlink. Nro. OC ' . $oc_softlink->num_docu . ' id ' . $oc_softlink->mov_id,
                        'ocSoftlink' => array('cabecera' => $oc_softlink),
                        'ocAgile' => array('cabecera' => $oc),
                    );
                }
                //verifica si fue anulado
                else if ($oc_softlink->flg_anulado == 1) {
                    //Ya fue anulada en softlink
                    $arrayRspta = array(
                        'tipo' => 'success',
                        'mensaje' => 'Ésta orden ya fue anulada en Softlink. Nro. OC ' . $oc_softlink->num_docu . ' id ' . $oc_softlink->mov_id,
                        'ocSoftlink' => array('cabecera' => $oc_softlink),
                        'ocAgile' => array('cabecera' => $oc),
                    );
                } else {
                    //Anula orden en softlink
                    DB::connection(app('conexion_softlink'))->table('movimien')->where('mov_id', $oc->id_softlink)
                        ->update([
                            'flg_anulado' => 1,
                            'fec_anul' => new Carbon(),
                        ]);

                    $arrayRspta = array(
                        'tipo' => 'success',
                        'mensaje' => 'Ésta orden fue anulada con éxito en Softlink. Nro. OC ' . $oc_softlink->num_docu . ' id ' . $oc_softlink->mov_id,
                        'ocSoftlink' => array('cabecera' => $oc_softlink),
                        'ocAgile' => array('cabecera' => $oc),
                    );
                }
            }

            DB::commit();
            return response()->json($arrayRspta, 200);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('tipo' => 'error', 'mensaje' => 'Hubo un problema al anular la orden. Por favor intente de nuevo', 'error' => $e->getMessage()));
        }
    }

    public function listarOrdenesSoftlinkNoVinculadas($cod_empresa, $fechaInicio, $fechaFin)
    {
        try {
            DB::beginTransaction();
            $empresas = [
                ['id' => 1, 'nombre' => 'OKC', 'cod_docu' => 'OC'],
                ['id' => 2, 'nombre' => 'PYC', 'cod_docu' => 'O3'],
                ['id' => 3, 'nombre' => 'SVS', 'cod_docu' => 'O2'],
                ['id' => 4, 'nombre' => 'JEDR', 'cod_docu' => 'O5'],
                ['id' => 5, 'nombre' => 'RBDB', 'cod_docu' => 'O4'],
                ['id' => 6, 'nombre' => 'PTEC', 'cod_docu' => 'O6']
            ];
            $cod_suc = '';
            $cod_docu = '';

            foreach ($empresas as $emp) {
                if ($emp['nombre'] == $cod_empresa) {
                    $cod_suc = $emp['id'];
                    $cod_docu = $emp['cod_docu'];
                }
            }
            // $fechaDesde = (new Carbon($fecha))->subMonth(3);

            $lista = DB::connection(app('conexion_softlink'))->table('movimien')
                ->select('mov_id', 'num_docu', 'cod_docu', 'auxiliar.nom_auxi')
                ->join('auxiliar', 'auxiliar.cod_auxi', '=', 'movimien.cod_auxi')
                ->where([
                    ['cod_suc', '=', $cod_suc],
                    ['cod_docu', '=', $cod_docu],
                    ['flg_anulado', '=', 0],
                    ['mov_id', 'not like', '000%']
                ])
                ->whereDate('fec_docu', '>=', (new Carbon($fechaInicio))->format('Y-m-d'))
                ->whereDate('fec_docu', '<=', (new Carbon($fechaFin))->format('Y-m-d'))
                ->get();

            DB::commit();
            return response()->json(array('tipo' => 'success', 'data' => $lista), 200);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(array('tipo' => 'error', 'mensaje' => 'Hubo un problema en la conexión. Por favor intente de nuevo', 'error' => $e->getMessage()));
        }
    }

    public function listarOrdenesPendientesMigrar()
    {
        $ordenes = DB::table('logistica.log_ord_compra')
            ->select('id_orden_compra', 'codigo')
            ->where([['id_usuario', '=', 14], ['estado', '!=', 7]])
            ->whereNull('id_softlink')
            ->orderBy('id_orden_compra', 'asc')
            ->get();
        return response()->json($ordenes);
    }

    public function ordenesPendientesMigrar()
    {
        $ordenes = DB::table('logistica.log_ord_compra')
            ->select('id_orden_compra')
            ->where([['id_usuario', '=', 14], ['estado', '!=', 7]])
            ->whereNull('id_softlink')
            ->orderBy('id_orden_compra', 'asc')
            ->get();

        $respuestas = [];

        foreach ($ordenes as $oc) {
            array_push($respuestas, $this->migrarOrdenCompra($oc->id_orden_compra));
        }
        return response()->json($respuestas);
    }
}
