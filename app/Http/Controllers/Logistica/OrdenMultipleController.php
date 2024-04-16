<?php

namespace App\Http\Controllers\Logistica;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Periodo;
use App\Models\Almacen\DetalleRequerimiento;
use App\Models\Almacen\Requerimiento;
use App\Models\Almacen\Reserva;
use App\Models\Almacen\UnidadMedida;
use App\models\Configuracion\AccesosUsuarios;
use App\Models\Configuracion\Moneda;
use App\Models\Contabilidad\Banco;
use App\Models\Contabilidad\Contribuyente;
use App\Models\Contabilidad\CuentaContribuyente;
use App\Models\Contabilidad\TipoCuenta;
use App\Models\Logistica\CondicionSoftlink;
use App\Models\Logistica\Orden;
use App\Models\Logistica\OrdenCompraDetalle;
use App\Models\Logistica\Proveedor;
use Carbon\Carbon;
use Exception;
date_default_timezone_set('America/Lima');

class OrdenMultipleController extends Controller
{
 
    
    function view_orden_multiple()
    {
        $tp_documento = $this->select_documento();
        $tp_moneda = $this->select_moneda();
        $periodos = Periodo::mostrar();
        $sedes = $this->select_empresa_sede();
        $proveedores = $this->listar_proveedores();
        $rubros = $this->select_mostrar_rubos();

        $empresas = $this->select_mostrar_empresas();
        $bancos = Banco::mostrar();
        $tipo_cuenta = TipoCuenta::mostrar();
        $sis_identidad = $this->select_sis_identidad();
        $ubigeos = $this->select_ubigeos();
        $trabajadores = $this->select_trabajadores();



        $condiciones = $this->select_condiciones();
        $condiciones_softlink = CondicionSoftlink::mostrar();
 
        $tp_doc = $this->select_tp_doc();
        $clasificaciones = (new AlmacenController)->mostrar_clasificaciones_cbo();
        $subcategorias = (new AlmacenController)->mostrar_subcategorias_cbo();
        $categorias = (new AlmacenController)->mostrar_categorias_cbo();
        $unidades = (new AlmacenController)->mostrar_unidades_cbo();
        $unidades_medida = UnidadMedida::mostrar();
        $monedas = Moneda::mostrar();
        // $array_accesos_botonera = array();
        // $accesos_botonera = AccesosUsuarios::where('accesos_usuarios.estado', '=', 1)
        //     ->select('accesos.*')
        //     ->join('configuracion.accesos', 'accesos.id_acceso', '=', 'accesos_usuarios.id_acceso')
        //     ->where('accesos_usuarios.id_usuario', Auth::user()->id_usuario)
        //     ->where('accesos_usuarios.id_modulo', 91)
        //     ->where('accesos_usuarios.id_padre', 89)
        //     ->get();
        // foreach ($accesos_botonera as $key => $value) {
        //     $value->accesos;
        //     array_push($array_accesos_botonera, $value->accesos->accesos_grupo);
        // }
        $modulo = 'logistica';
        // $array_accesos = [];
        // $accesos_usuario = AccesosUsuarios::where('estado', 1)->where('id_usuario', Auth::user()->id_usuario)->get();
        // foreach ($accesos_usuario as $key => $value) {
        //     array_push($array_accesos, $value->id_acceso);
        // }

        return view('logistica.gestion_logistica.compras.ordenes.elaborar.orden_multiple', compact('empresas', 'rubros', 'bancos', 'tipo_cuenta', 'sedes','proveedores', 'sis_identidad', 'tp_documento', 'tp_moneda', 'tp_doc', 'condiciones', 'condiciones_softlink','ubigeos','trabajadores', 'clasificaciones', 'subcategorias', 'categorias', 'unidades', 'unidades_medida', 'monedas', 'modulo',  'periodos'));
    }


    public function obtenerDataProveedor($idProveedor)
    {
        $proveedor = Proveedor::with(['contribuyente.tipoDocumentoIdentidad', 'estadoProveedor','contactoContribuyente' ,'cuentaContribuyente' => function ($q) {
                $q->where('estado', '=', 1);
            }])
                ->whereHas('contribuyente', function ($q) {
                    $q->where('estado', '=', 1);
                })->where([['id_proveedor','=',$idProveedor],['log_prove.estado', '=', 1]])->get()->first();

        return $proveedor;
    }

    
    public function listar_proveedores(){
        $proveedores = Proveedor::with(['contribuyente.tipoDocumentoIdentidad', 'estadoProveedor', 'cuentaContribuyente' => function ($q) {
            $q->where('estado', '=', 1);
        }])->whereHas('contribuyente', function ($q) {
                $q->where('estado', '=', 1);
        })->where('log_prove.estado', '=', 1)->get();
        
        return $proveedores;
    }

    function lista_contactos_proveedor($id_proveedor)
    {

        $data = DB::table('logistica.log_prove')
            ->select(
                'adm_ctb_contac.id_datos_contacto as id_contacto',
                'adm_ctb_contac.nombre as nombre_contacto',
                'adm_ctb_contac.cargo as cargo_contacto',
                'adm_ctb_contac.email as email_contacto',
                'adm_ctb_contac.telefono as telefono_contacto',
                'adm_ctb_contac.direccion as direccion_contacto',
                'adm_ctb_contac.ubigeo as ubigeo_contacto'
            )
            // ->leftJoin('contabilidad.adm_contri', 'adm_contri.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->join('contabilidad.adm_ctb_contac', 'adm_ctb_contac.id_contribuyente', '=', 'log_prove.id_contribuyente')
            ->where('log_prove.id_proveedor', $id_proveedor)
            ->orderby('adm_ctb_contac.nombre', 'asc')
            ->get();


        return response()->json(['data' => $data]);
    }

    public function select_documento()
    {
        $data = DB::table('administracion.adm_tp_docum')
            ->select('adm_tp_docum.id_tp_documento', 'adm_tp_docum.descripcion', 'adm_tp_docum.abreviatura')
            ->where([
                ['adm_tp_docum.estado', '=', 1],
                ['adm_tp_docum.uso_logistica', true]
            ])
            ->orderBy('adm_tp_docum.id_tp_documento', 'asc')
            ->get();
        return $data;
    }

    public function select_moneda()
    {
        $data = DB::table('configuracion.sis_moneda')
            ->select('sis_moneda.id_moneda', 'sis_moneda.descripcion', 'sis_moneda.simbolo')
            ->where([
                ['sis_moneda.estado', '=', 1]
            ])
            ->orderBy('sis_moneda.id_moneda', 'asc')
            ->get();
        return $data;
    }

    public function select_sedes()
    {
        $data = DB::table('administracion.sis_sede')
            ->select(
                'sis_sede.*'
            )
            ->orderBy('sis_sede.id_empresa', 'asc')
            ->get();
        return $data;
    }

    public function select_empresa_sede()
    {
        $data = DB::table('administracion.sis_sede')
            ->select(
                'sis_sede.*',
                'ubi_dis.descripcion as ubigeo_descripcion'
            )
            ->leftJoin('configuracion.ubi_dis', 'ubi_dis.id_dis', '=', 'sis_sede.id_ubigeo')
            ->where('sis_sede.estado', '=', '1')
            ->orderBy('sis_sede.id_empresa', 'asc')
            ->get();
        return $data;
    }

    public function select_tp_doc()
    {
        $data = DB::table('contabilidad.cont_tp_doc')
            ->select('cont_tp_doc.id_tp_doc', 'cont_tp_doc.cod_sunat', 'cont_tp_doc.descripcion')
            ->where([['cont_tp_doc.estado', '=', 1]])
            ->orderBy('cont_tp_doc.id_tp_doc')
            ->get();
        return $data;
    }

    public function select_condiciones()
    {
        $data = DB::table('logistica.log_cdn_pago')
            ->select('log_cdn_pago.id_condicion_pago', 'log_cdn_pago.descripcion')
            ->where('log_cdn_pago.estado', 1)
            ->orderBy('log_cdn_pago.descripcion')
            ->get();
        return $data;
    }

    public function select_sis_identidad()
    {
        $data = DB::table('contabilidad.sis_identi')
            ->select('sis_identi.id_doc_identidad', 'sis_identi.descripcion')
            ->where('sis_identi.estado', '=', 1)
            ->orderBy('sis_identi.descripcion', 'asc')->get();
        return $data;
    }

    
    public function select_ubigeos()
    {
        $data = DB::table('configuracion.ubi_dis')
            ->select('ubi_dis.*', 'ubi_prov.descripcion as provincia', 'ubi_dpto.descripcion as departamento')
            ->join('configuracion.ubi_prov', 'ubi_prov.id_prov', '=', 'ubi_dis.id_prov')
            ->join('configuracion.ubi_dpto', 'ubi_dpto.id_dpto', '=', 'ubi_prov.id_dpto')
            ->get();
      
        return $data;
    }

    public function select_trabajadores()
    {
        $data = DB::table('rrhh.rrhh_trab')
                ->select('rrhh_trab.*', 'rrhh_perso.nro_documento',
                DB::raw("concat(rrhh_perso.nombres, ' ' ,rrhh_perso.apellido_paterno, ' ' ,rrhh_perso.apellido_materno) AS nombre_trabajador"))
                ->join('rrhh.rrhh_postu', 'rrhh_postu.id_postulante', '=', 'rrhh_trab.id_postulante')
                ->join('rrhh.rrhh_perso', 'rrhh_perso.id_persona', '=', 'rrhh_postu.id_persona')
                ->where([['rrhh_trab.estado', '=', 1]])
                ->orderBy('nombre_trabajador')
                ->get();
         
        return $data;
    }

    public function select_mostrar_empresas()
    {
        $data = DB::table('administracion.adm_empresa')
            ->select('adm_empresa.id_empresa', 'adm_empresa.codigo', 'adm_empresa.logo_empresa', 'adm_contri.nro_documento', 'adm_contri.razon_social')
            ->join('contabilidad.adm_contri', 'adm_empresa.id_contribuyente', '=', 'adm_contri.id_contribuyente')
            ->where('adm_empresa.estado', '=', 1)
            ->orderBy('adm_contri.razon_social', 'asc')
            ->get();
        return $data;
    }
    public function select_mostrar_rubos()

    {
        $data = DB::table('contabilidad.adm_rubro')
            ->where('adm_rubro.estado', '=', 1)
            ->orderBy('adm_rubro.descripcion', 'asc')
            ->get();
        return $data;
    }


    public function ObtenerAtencionItemRequerimiento($idRequerimiento){
        $isSuccess=true;
        $listaAtendidoOrden=[];
        $estadoItems=[];

        $requerimiento = Requerimiento::with('periodo','moneda','empresa.contribuyente','sede')->find($idRequerimiento);
        if(in_array($requerimiento->estado,[2,15,27])){
            $detalleRequerimiento = DetalleRequerimiento::with('producto')->where([['id_requerimiento',$idRequerimiento],['estado','!=',7]])->get();
            foreach ($detalleRequerimiento as $detReq) {

                    $estadoItems[]=[
                        'id_requerimiento'=>intval($detReq->id_requerimiento),
                        'id_detalle_requerimiento'=>intval($detReq->id_detalle_requerimiento),
                        'id_tipo_item'=>intval($detReq->id_tipo_item),
                        'cantidad_solicitada'=>intval($detReq->cantidad),
                        'cantidad_atendida_orden'=>0,
                        'cantidad_atendida_almacen'=>0,
                        'tiene_atencion_total'=>false
                    ];

                $detalleOrden = OrdenCompraDetalle::where([['id_detalle_requerimiento',$detReq->id_detalle_requerimiento],['estado','!=',7]])->get();
                foreach ($detalleOrden as $keyDetOrd => $detOrd) {
                    foreach ($estadoItems as $keyEstItem => $estItem) {
                        if($estItem['id_detalle_requerimiento'] == $detOrd->id_detalle_requerimiento){
                            $estadoItems[$keyEstItem]['cantidad_atendida_orden'] += intval($detOrd->cantidad);
                            if($estadoItems[$keyEstItem]['cantidad_atendida_orden']==$estItem['cantidad_solicitada']){
                                $estadoItems[$keyEstItem]['tiene_atencion_total'] =true ;

                            }
                         
                        }
                        
                    }
                }

                $reservas = Reserva::where([['id_detalle_requerimiento',$detReq->id_detalle_requerimiento],['estado','!=',7],['id_guia_com_det',null],['id_guia_ven_det',null]])->get();
                foreach ($reservas as $keyRes => $res) {
                    foreach ($estadoItems as $keyEstItem => $estItem) {
                        if($estItem['id_detalle_requerimiento'] == $res->id_detalle_requerimiento){
                            $estadoItems[$keyEstItem]['cantidad_atendida_almacen'] += intval($res->stock_comprometido);
                            if($estadoItems[$keyEstItem]['cantidad_atendida_almacen']==$estItem['cantidad_solicitada']){
                                $estadoItems[$keyEstItem]['tiene_atencion_total'] =true ;

                            }
                         
                        }
                        
                    }
                }
            }
        }

        $detalleRequerimientoFiltrado=[];
        foreach ($estadoItems as $keyEstItem => $EstItemValue) {
            foreach ($detalleRequerimiento as $keyDetReq => $DetReqValue) {

                if($EstItemValue['id_detalle_requerimiento'] == $DetReqValue->id_detalle_requerimiento){
                    if($EstItemValue['tiene_atencion_total']==false){
                        $proveedorAgile= ($this->getIdProveedorAgile($DetReqValue->proveedor_seleccionado));
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_id_proveedor']=$proveedorAgile['id_proveedor'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_id_contribuyente']=$proveedorAgile['id_contribuyente'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_razon_social']=$proveedorAgile['razon_social'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_tipo_documento']=$proveedorAgile['tipo_documento'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_numero_documento']=$proveedorAgile['numero_documento'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_direccion_fiscal']=$proveedorAgile['direccion_fiscal'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_id_cuenta_bancaria']=$proveedorAgile['id_cuenta_bancaria'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_numero_cuenta_bancaria']=$proveedorAgile['numero_cuenta_bancaria'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_numero_cuenta_interbancaria']=$proveedorAgile['numero_cuenta_interbancaria'];
                        $detalleRequerimiento[$keyDetReq]['proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria']=$proveedorAgile['simbolo_moneda_cuenta_bancaria'];
                         $detalleRequerimientoFiltrado[]= $DetReqValue;
                    }
                }
            }
        }


       

        return response()->json([
            "success" => $isSuccess,
            "estado_item_list" => $estadoItems,
            "requerimiento" => $requerimiento,
            "detalle_requerimiento_list" => $detalleRequerimientoFiltrado,
            "mensaje" => '',
        ], 200);
    }
    
    
    public function  getIdProveedorAgile($razonSocialProveedor){
        $idProveedor='';
        $idContribuyente='';
        $razonSocial='';
        $tipoDocumento='';
        $numeroDocumento='';
        $DireccionFiscal='';
        $idCuentaBancaria='';
        $numeroCuentaBancaria='';
        $numeroCuentaInterbancariaBancaria='';
        $simboloMonedaCuentaBancaria='';

        if($razonSocialProveedor !=''){
            $contri=  Contribuyente::with("tipoDocumentoIdentidad")->where('razon_social','LIKE','%'.trim($razonSocialProveedor).'%')->get();
            $cuentasContri= CuentaContribuyente::with('moneda')->where([['id_contribuyente',$contri->first()->id_contribuyente],['estado',1]])->orderBy('fecha_registro','desc')->first();
          
            if($contri){
                $idContribuyente=$contri->first()->id_contribuyente;
                $razonSocial=$contri->first()->razon_social;
                $tipoDocumento=$contri->first()->tipoDocumentoIdentidad->descripcion;
                $numeroDocumento=$contri->first()->nro_documento;
                $DireccionFiscal=$contri->first()->direccion_fiscal;
                $idCuentaBancaria= $cuentasContri !=null ? $cuentasContri->id_cuenta_contribuyente:'';
                $numeroCuentaBancaria= $cuentasContri !=null ? $cuentasContri->nro_cuenta:'';
                $numeroCuentaInterbancariaBancaria= $cuentasContri !=null ? $cuentasContri->nro_cuenta_interbancaria:'';
                $simboloMonedaCuentaBancaria= $cuentasContri !=null ? $cuentasContri->moneda->simbolo:'';
                $prove = Proveedor::where('id_contribuyente',$contri->first()->id_contribuyente)->get();
                if($prove){
                    $idProveedor = $prove->first()->id_proveedor;
                }

            } 
        }

        return [
            'id_proveedor'=>$idProveedor,
            'id_contribuyente'=>$idContribuyente,
            'razon_social'=>$razonSocial,
            'tipo_documento'=>$tipoDocumento,
            'numero_documento'=>$numeroDocumento,
            'direccion_fiscal'=>$DireccionFiscal,
            'id_cuenta_bancaria'=>$idCuentaBancaria,
            'numero_cuenta_bancaria'=>$numeroCuentaBancaria,
            'numero_cuenta_interbancaria'=>$numeroCuentaInterbancariaBancaria,
            'simbolo_moneda_cuenta_bancaria'=>$simboloMonedaCuentaBancaria
        ];
    }
}