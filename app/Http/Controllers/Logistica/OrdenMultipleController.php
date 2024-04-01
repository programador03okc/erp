<?php

namespace App\Http\Controllers\Logistica;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Periodo;
use App\Models\Almacen\UnidadMedida;
use App\models\Configuracion\AccesosUsuarios;
use App\Models\Configuracion\Moneda;
use App\Models\Contabilidad\Banco;
use App\Models\Contabilidad\TipoCuenta;
use App\Models\Logistica\CondicionSoftlink;
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

}
