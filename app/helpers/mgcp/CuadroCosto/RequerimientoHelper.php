<?php
namespace App\Helpers\mgcp\CuadroCosto;

use App\Mail\mgcp\CuadroCosto\AprobacionCuadro;
use App\Mail\mgcp\CuadroCosto\ErrorReplicarRequerimiento;
use App\Mail\mgcp\CuadroCosto\RespuestaSolicitud;
use App\Mail\mgcp\CuadroCosto\SolicitudAprobacion;
use App\Models\Administracion\Aprobacion;
use App\Models\Administracion\Documento;
use App\Models\Administracion\Periodo;
use App\Models\Almacen\CdpRequerimiento;
use App\Models\Almacen\DetalleOrdenCompra;
use App\Models\Almacen\DetalleRequerimiento;
use App\Models\Almacen\OrdenCompra;
use App\Models\Almacen\Producto;
use App\Models\Almacen\Requerimiento;
use App\Models\Almacen\RequerimientoObservacion;
use App\Models\Almacen\Reserva;
use App\Models\Almacen\Subcategoria;
use App\Models\Comercial\Cliente;
use App\Models\Configuracion\Usuario;
use App\Models\Configuracion\UsuarioSede;
use App\Models\Contabilidad\Contribuyente;
use App\Models\mgcp\AcuerdoMarco\Entidad\Entidad;
use App\Models\mgcp\CuadroCosto\AprobadorUno;
use App\Models\mgcp\CuadroCosto\AprobadorDos;
use App\Models\mgcp\CuadroCosto\AprobadorTres;
use App\Models\mgcp\CuadroCosto\CcAmFila;
use App\Models\mgcp\CuadroCosto\CcFilaMovimientoTransformacion;
use App\Models\mgcp\CuadroCosto\CcSolicitud;
use App\Models\mgcp\CuadroCosto\CuadroCosto;
use App\Models\mgcp\CuadroCosto\Proveedor;
use App\Models\mgcp\Usuario\Notificacion;
use Illuminate\Support\Facades\Auth;
use App\Models\mgcp\Oportunidad\Oportunidad;
use App\Models\mgcp\OrdenCompra\Propia\OrdenCompraPropiaView;
use App\Models\mgcp\Usuario\LogActividad;
use App\Models\RRHH\Persona;
use App\Models\RRHH\Postulante;
use App\Models\RRHH\Trabajador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;
use Debugbar;


class RequerimientoHelper
{
    const ID_USUARIO_MGCP = 79;
    const ID_ESTADO_REGULARIZAR = 38;
    const ID_ESTADO_ANULADO = 7;
    const ID_DIVISION_UCORP = 1;

    /**
     * Devuelve el requerimiento creado, el reemplazado y el estado (sin_cambios, nuevo, reemplazo)
     */

    public function replicarPorCuadroCosto($idOportunidad)
    {
        $cuadro = CuadroCosto::where('id_oportunidad', $idOportunidad)->first();

        try {
            $respuesta = new stdClass();
            $oportunidad = $cuadro->oportunidad;
            $ordenCompra = OrdenCompraPropiaView::where('id_oportunidad', $idOportunidad)->first();
            $requerimiento = Requerimiento::where([['id_cc', $cuadro->id], ['id_tipo_requerimiento', 1]])->orderBy('id_requerimiento', 'DESC')->first();
            //$crearRequerimiento = false;

            DB::beginTransaction();
            if ($requerimiento == null) { //Si requerimiento no existe
                $requerimiento = $this->crearRequerimiento($oportunidad, $ordenCompra, $cuadro);
                $this->crearDetalles($requerimiento, $cuadro);
                $respuesta->estado = 'nuevo';
                LogActividad::registrar(Auth::user(), 'Replica por CDP - Creación', 16, $ordenCompra->getTable(), $ordenCompra);
            } else {
                $respuesta->estado = $this->actualizarRequerimiento($requerimiento, $cuadro);
                LogActividad::registrar(Auth::user(), 'Replica por CDP - Actualización', 16, $ordenCompra->getTable(), $ordenCompra);
            }
            $this->crearHistorialAprobacion($requerimiento, $cuadro, 1); // referente a la tabla administracion.adm_vobo  1=Aprobado
            DB::commit();
            $respuesta->requerimiento = $requerimiento;

            /*if ($crearRequerimiento) { }*/

            return $respuesta;
        } catch (Exception $ex) {
            DB::rollBack();
            //Envía por correo el error generado para poder corregirlo
            // Mail::to(config('global.adminEmail'))->send(new ErrorReplicarRequerimiento($cuadro, $ex->getMessage()));
            return null;
        }
    }

    public function retirarAprobacionCuadroCosto($cuadro)
    {
        try {
            $requerimiento = Requerimiento::where('id_cc', $cuadro->id)->where('id_tipo_requerimiento', 1)->orderBy('id_requerimiento', 'desc')->first();
            if ($requerimiento != null) {
                $requerimiento->estado_anterior=$requerimiento->estado;
                $requerimiento->estado = 39;//En pausa
                $requerimiento->save();
            }

            $this->crearHistorialAprobacion($requerimiento, $cuadro, 6, 'Retiro de aprobación por actualización de CDP'); // referente a la tabla administracion.adm_vobo  6=Pausado
            return $requerimiento;
        } catch (Exception $e) {
            return null;
        }
    }

    private function actualizarRequerimiento($requerimiento, $cuadro)
    {
        // Actualiza la cabecera del requerimiento
        $actualizarCabecera = Requerimiento::find($requerimiento->id_requerimiento);
            $actualizarCabecera->tiene_transformacion = $cuadro->tiene_transformacion;
        $actualizarCabecera->save();

        $estado = 'sin_cambios';
        $marcarRequerimientoPorRegularizar = false;
        $filasCuadro = CcAmFila::where('id_cc_am', $cuadro->id)->orderBy('id', 'asc')->get();

        foreach ($filasCuadro as $filaCuadro) {
            //Obtiene las filas base del cuadro
            $detReqBase = DetalleRequerimiento::where('id_cc_am_filas', $filaCuadro->id)->where('estado', '!=', 7)->where('tipo_cc_am_filas', 1)->first();

            if ($detReqBase == null) {
                $this->crearFilaRequerimiento($requerimiento, $cuadro, $filaCuadro);
                $estado = 'actualizado';
            } else {
                if ($filaCuadro->tieneTransformacion()) {
                    /*
                     * Evalua 2 opciones:
                     * a) Si CDP tiene transf y el REQ no tiene transf
                     * b) Si CDP tiene transf y el REQ también tiene transf
                     */
                    $detReqTransf = DetalleRequerimiento::where('id_cc_am_filas', $filaCuadro->id)->where('estado', '!=', 7)->where('tipo_cc_am_filas', 2)->first();
                    if ($detReqTransf == null) {
                        $this->crearDetallePorTransformacion($filaCuadro, $requerimiento);
                    } else {
                        if ($detReqBase->cantidad != $filaCuadro->cantidad || $detReqTransf->descripcion != $filaCuadro->descripcion_producto_transformado || $detReqTransf->part_number != $filaCuadro->part_no_producto_transformado || $detReqBase->precio_unitario != $filaCuadro->pvu_oc) {
                            if ($detReqBase->id_producto != null && $this->ItemConOrdenOReserva($detReqBase->id_detalle_requerimiento) == true) {
                                $detReqBase->estado = self::ID_ESTADO_REGULARIZAR;
                                $marcarRequerimientoPorRegularizar = true;
                            }

                                $detReqTransf->cantidad = $filaCuadro->cantidad;
                                $detReqTransf->descripcion = $filaCuadro->descripcion_producto_transformado;
                                $detReqTransf->part_number = $filaCuadro->part_no_producto_transformado;
                                $detReqTransf->precio_unitario = $filaCuadro->pvu_oc;
                            $detReqTransf->save();
                        }
                    }

                    if ($detReqBase->cantidad != $filaCuadro->cantidad || $detReqBase->descripcion != $filaCuadro->descripcion || $detReqBase->part_number != $filaCuadro->part_no || $detReqBase->precio_unitario != $filaCuadro->pvu_oc) {
                        if ($detReqBase->id_producto != null && $this->ItemConOrdenOReserva($detReqBase->id_detalle_requerimiento) == true) {
                            $detReqBase->estado = self::ID_ESTADO_REGULARIZAR;
                            $marcarRequerimientoPorRegularizar = true;
                        }
                            $detReqBase->cantidad = $filaCuadro->cantidad;
                            $detReqBase->descripcion = $filaCuadro->descripcion;
                            $detReqBase->part_number = $filaCuadro->part_no;
                            $detReqBase->precio_unitario = $filaCuadro->pvu_oc;
                        $detReqBase->save();
                    }
                    $estado = 'actualizado';
                } else {
                    /*
                     * Evalua 1 opción:
                     * a) Si CDP no tiene transf y el REQ también tiene transf
                     */
                    $detReqTransf = DetalleRequerimiento::where('id_cc_am_filas', $filaCuadro->id)->where('estado', '!=', 7)->where('tipo_cc_am_filas', 2)->first();
                    if ($detReqTransf) {
                            $detReqTransf->estado = 7;
                        $detReqTransf->save();
                    }

                    if ($detReqBase->cantidad != $filaCuadro->cantidad || $detReqBase->descripcion != $filaCuadro->descripcion || $detReqBase->part_number != $filaCuadro->part_no || $detReqBase->precio_unitario != $filaCuadro->pvu_oc) {
                        if ($detReqBase->id_producto != null && $this->ItemConOrdenOReserva($detReqBase->id_detalle_requerimiento) == true) {
                            $detReqBase->estado = self::ID_ESTADO_REGULARIZAR;
                            $marcarRequerimientoPorRegularizar = true;
                        }
                            $detReqBase->cantidad = $filaCuadro->cantidad;
                            $detReqBase->descripcion = $filaCuadro->descripcion;
                            $detReqBase->part_number = $filaCuadro->part_no;
                            $detReqBase->precio_unitario = $filaCuadro->pvu_oc;
                        $detReqBase->save();
                    }
                }
            }
        }

        if ($marcarRequerimientoPorRegularizar) {
            $requerimiento->estado = self::ID_ESTADO_REGULARIZAR;
            $estado = 'por_regularizar';
        } else {
            $requerimiento->estado = $requerimiento->estado_anterior ?? $requerimiento->estado;
        }

            $requerimiento->id_tipo_detalle = $this->obtenerTipoDetalle($cuadro->id); 
        $requerimiento->save();
        return $estado;
    }

    private function RequerimientoConOrdenOReserva($idRequerimiento)
    {
        $tieneOrdenesReservasActivas=false;
        $detalleRequerimiento= DetalleRequerimiento::where('id_requerimiento',$idRequerimiento)->get();

        foreach ($detalleRequerimiento as $dr) {
            $itemTieneOrdenOReserva= $this->ItemConOrdenOReserva($dr->id_detalle_requerimiento);
            if($itemTieneOrdenOReserva ==true){
                $tieneOrdenesReservasActivas=true;
            }
        }

        return $tieneOrdenesReservasActivas;
    }

    private function ItemConOrdenOReserva($idDetalleRequerimiento)
    {
        $tieneOrdenesReservasActivas=false;
        $cantidadDetalleOrdenes= DetalleOrdenCompra::where([['id_detalle_requerimiento',$idDetalleRequerimiento],['estado','!=',7]])->count();
        $cantidadReservas = Reserva::where([['id_detalle_requerimiento',$idDetalleRequerimiento],['estado','!=',7]])->count();

        if($cantidadDetalleOrdenes > 0 || $cantidadReservas > 0){
            $tieneOrdenesReservasActivas=true;
        } 

        return $tieneOrdenesReservasActivas;
    }

    
    private function crearRequerimiento($oportunidad, $ordenCompra, $cuadro)
    {
        $idUsuario = $this->obtenerIdUsuario($oportunidad->id_responsable);
        $requerimiento = new Requerimiento();
        $requerimiento->id_tipo_requerimiento = 1;
        $requerimiento->id_usuario = $idUsuario;
        //$requerimiento->trabajador_id = $idUsuario;
        $requerimiento->fecha_requerimiento = new Carbon();
        $concepto = ($ordenCompra == null ? '' : 'O/C: ' . $ordenCompra->nro_orden . ' / ');
        $requerimiento->concepto = trim($concepto . ' CDP: ' . $oportunidad->codigo_oportunidad . ' / CLIENTE: ' . $oportunidad->entidad->nombre);

        $requerimiento->id_grupo = 2; //2 es Comercial
        $requerimiento->estado = 2;
        $requerimiento->occ_softlink = ($ordenCompra == null ? null : $ordenCompra->occ);
        $requerimiento->fecha_registro = new Carbon();
        $requerimiento->id_prioridad = 1;
        $requerimiento->observacion = "CREADO DE FORMA AUTOMÁTICA DESDE EL MGC";
        $requerimiento->id_moneda = 2; //2 es dólares

        $requerimiento->id_empresa = $ordenCompra == null ? 1 : $ordenCompra->id_empresa;
        $requerimiento->id_periodo = $this->obtenerPeriodo($requerimiento->fecha_requerimiento->year)->id_periodo;
        $requerimiento->id_sede = $ordenCompra == null ? 4 : $this->obtenerIdSede($ordenCompra->id_empresa); //sede de la empresa de donde viene el requerimiento

        $requerimiento->id_cliente = $this->obtenerCliente($oportunidad->id_entidad)->id_cliente;
        $requerimiento->id_tipo_detalle = $this->obtenerTipoDetalle($cuadro->id);
        //die("OKA");

        $requerimiento->tipo_cliente = 2; //Cliente persona jurídica
        $requerimiento->direccion_entrega = $ordenCompra == null ? 'CONSULTAR CON EL CORPORATIVO' : $ordenCompra->lugar_entrega;


        //id del almacen que va a atender
            //$requerimiento->id_almacen = $ordenCompra == null ? 2 : $this->obtenerIdAlmacen($ordenCompra->id_empresa);
        //id del almacen que va a atender con id_sede
        $requerimiento->id_almacen = $ordenCompra == null ? 78 : $this->obtenerIdAlmacen($requerimiento->id_sede);
        $requerimiento->confirmacion_pago = true;
        $requerimiento->fecha_entrega = ($ordenCompra == null ? (new Carbon()) : $ordenCompra->fecha_entrega);
        $requerimiento->id_cc = $cuadro->id;
        $requerimiento->tiene_transformacion = $cuadro->tiene_transformacion; //Rocío lo usa por conveniencia, para no revisar las filas
        $requerimiento->division_id = self::ID_DIVISION_UCORP;
        //$requerimiento->save();
        //$requerimiento->codigo = Requerimiento::crearCodigo(1, 0);

        $requerimiento->save();
        $requerimiento->codigo = Requerimiento::crearCodigo($requerimiento->id_tipo_requerimiento, $requerimiento->id_grupo, $requerimiento->id_requerimiento);

        $requerimiento->save();
        $this->crearDocumentoReferencia($requerimiento);

        $cdpRequerimiento = new CdpRequerimiento();
        $cdpRequerimiento->id_cc = $cuadro->id;
        $cdpRequerimiento->codigo_oportunidad = $oportunidad->codigo_oportunidad;
        $cdpRequerimiento->id_requerimiento_logistico = $requerimiento->id_requerimiento;
        $cdpRequerimiento->estado = 1;
        $cdpRequerimiento->save();
        
        return $requerimiento;
    }

    private function crearDocumentoReferencia($requerimiento)
    {
        if($requerimiento){
            $documento = new Documento();
                $documento->id_tp_documento = 1;
                $documento->codigo_doc = $requerimiento->codigo??'';
                $documento->id_doc = $requerimiento->id_requerimiento;
            $documento->save();
        }
    }

    private function crearHistorialAprobacion($requerimiento, $cuadro, $tipoAprobacion, $comentario = null)
    {
        $ultimaSolicitudCc = CcSolicitud::where('id_cc', $cuadro->id)->orderBy('id', 'DESC')->first();
        $comentarioDesc = ($comentario != null) ? $comentario : $ultimaSolicitudCc->comentario_aprobador;
        $documento = Documento::where("id_doc",$requerimiento->id_requerimiento)->first();

        if($documento && $documento->id_doc_aprob >0) {
            // Debugbar::info($this->obtenerIdUsuario($idUsuario));
            $aprobacion = new Aprobacion();
            $aprobacion->id_flujo = null;
            $aprobacion->id_doc_aprob = $documento->id_doc_aprob;
            $aprobacion->id_vobo = $tipoAprobacion; //posibles valores: 1=Aprobado, 2=Demegado, 3=Observado, 5=Revisado, 6=Pausado
            $aprobacion->id_usuario = $this->obtenerIdUsuario($ultimaSolicitudCc->enviada_a);
            $aprobacion->id_usuario = null;
            $aprobacion->fecha_vobo = new Carbon();
            $aprobacion->detalle_observacion = $comentarioDesc;
            $aprobacion->id_rol = null;
            $aprobacion->tiene_sustento = null;
            $aprobacion->save();
        }
    }

    private function obtenerIdUsuario($idUsuario): int
    {
        //Usuario por defecto en el sistema Agile en caso que no exista el usuario buscado
        $usuarioMgcp = User::withTrashed()->find($idUsuario);
        $usuarioAgil = Usuario::where('email', $usuarioMgcp->email)->first();
        if ($usuarioAgil == null) {
            return self::ID_USUARIO_MGCP; //Usuario MGCP
        } else {
            return $usuarioAgil->id_usuario;
        }
    }

    /**
     * Obtiene la ID de la sede en Lima de la empresa especificada
     */

    private function obtenerIdSede($idEmpresa)
    {
        $id = null;
        switch ($idEmpresa) {
            case 1:
                $id = 4;
            break;

            case 2:
                $id = 10;
            break;

            case 3:
                $id = 11;
            break;

            case 4:
                $id = 12;
            break;

            case 5:
                $id = 13;
            break;

            case 6:
                $id = 14;
            break;
        }
        return $id;
    }

    /**
     * Obtiene el ID del almacén por Sede
     */

    private function obtenerIdAlmacen($idSede)
    {
        $almacenSeleccionado= DB::table('almacen.alm_almacen')
            ->select('alm_almacen.id_almacen')
            ->where([
                ['alm_almacen.estado', '!=', 7],
                ['alm_almacen.id_tipo_almacen', '=', 1],
                ['alm_almacen.id_sede', '=', $idSede]
            ])->orderBy('id_almacen', 'desc')
            ->first();
        return $almacenSeleccionado->id_almacen;
    }

    private function crearDetalles($cabecera, $cuadro)
    {
        $filasCuadro = CcAmFila::where('id_cc_am', $cabecera->id_cc)->orderBy('id', 'asc')->get();

        foreach ($filasCuadro as $fila) {
            $this->crearFilaRequerimiento($cabecera, $cuadro, $fila);
        }
    }

    private function crearFilaRequerimiento($cabecera, $cuadro, $filaCuadro)
    {
        $proveedorFila = $filaCuadro->amProveedor;

        $detalle = new DetalleRequerimiento();
            $detalle->id_requerimiento = $cabecera->id_requerimiento;
            $detalle->cantidad = $filaCuadro->cantidad ?? 0;
            $detalle->estado = 1; //1 es elaborado
            $detalle->fecha_registro = new Carbon();
            $detalle->id_tipo_item = 1; //1 es producto
            $detalle->id_unidad_medida = 1; //Unidad (UND)
            $detalle->id_cc_am_filas = $filaCuadro->id;
            $detalle->id_moneda = 2; //siempre en dólares 
            $detalle->tiene_transformacion = false; //False son los productos base
            $detalle->centro_costo_id = $cuadro->id_centro_costo;
            
            $objProducto = $this->obtenerProducto($filaCuadro->marca, $filaCuadro->part_no);
            
            $detalle->id_producto = $objProducto == null ? null : $objProducto->id_producto; //Mapeo de producto si es que existe en el catálogo de Agil. El MGC no lo crea de forma automática
            $detalle->proveedor_id = $proveedorFila == null ? null : $this->obtenerProveedor($proveedorFila->id_proveedor)->id_proveedor;
            $detalle->precio_unitario = $proveedorFila == null ? 0 : ($proveedorFila->precio / ($proveedorFila->moneda == 'd' ? 1 :  $cuadro->tipo_cambio));
            $detalle->part_number = $filaCuadro->part_no;
            $detalle->descripcion = $filaCuadro->descripcion;
            $detalle->entrega_cliente = ($filaCuadro->tieneTransformacion() == false && (CcFilaMovimientoTransformacion::where('id_fila_ingresa', $filaCuadro->id)->first() == null));
            
            if ($filaCuadro->tieneTransformacion()) {
                $this->crearDetallePorTransformacion($filaCuadro, $cabecera);
            }
        $detalle->save();
        return $detalle;
    }

    private function crearDetallePorTransformacion($fila, $cabecera)
    {
        $detalle = new DetalleRequerimiento();
            $detalle->id_requerimiento = $cabecera->id_requerimiento;
            $detalle->cantidad = $fila->cantidad ?? 0;
            $detalle->estado = 1; //1 es elaborado
            $detalle->fecha_registro = new Carbon();
            $detalle->id_tipo_item = 1; //1 es producto
            $detalle->id_unidad_medida = 1; //Unidad (UND)
            $detalle->id_cc_am_filas = $fila->id;
            $detalle->id_moneda = 2; //siempre en dólares 
            $detalle->tiene_transformacion = true; //$fila->tieneTransformacion();
            $detalle->part_number = $fila->part_no_producto_transformado;
            $detalle->descripcion = $fila->descripcion_producto_transformado;
            $detalle->proveedor_id = null;
            $detalle->precio_unitario = 0;
            $detalle->entrega_cliente = true;
            $detalle->tipo_cc_am_filas = 2;
        $detalle->save();
    }


    private function obtenerProveedor($idProveedor)
    {
        $proveedorMgcp = \App\Models\mgcp\CuadroCosto\Proveedor::find($idProveedor);
        $contribuyente = Contribuyente::where('nro_documento', $proveedorMgcp->ruc)->first();
        if ($contribuyente == null) {
            $contribuyente = new Contribuyente();
            $contribuyente->nro_documento = $proveedorMgcp->ruc;
            $contribuyente->razon_social = $proveedorMgcp->razon_social;
            $contribuyente->fecha_registro = new Carbon();
            $contribuyente->transportista = false;
            $contribuyente->save();
        }

        $proveedorAgile = \App\Models\Logistica\Proveedor::where('id_contribuyente', $contribuyente->id_contribuyente)->first();

        if ($proveedorAgile == null) {
            $proveedorAgile = new \App\Models\Logistica\Proveedor();
            $proveedorAgile->id_contribuyente = $contribuyente->id_contribuyente;
            $proveedorAgile->estado = 1;
            $proveedorAgile->fecha_registro = new Carbon();
            $proveedorAgile->save();
        }
        return $proveedorAgile;
    }

    /**
     * Devuelve un objeto del tipo Producto si se encuentra o NULL si no se encuentra en la BD
     * @param string $marca Marca (subcategoría) del producto
     * @param string $nroParte Número de parte del producto
     */

    private function obtenerProducto($marca, $nroParte)
    {
        //No busca productos si no tienen Nro. de parte
        if (empty($nroParte)) {
            return null;
        }

        $objMarca = $this->obtenerMarca(mb_strtoupper($marca));
        //Si la marca no está registrada en la BD, se asume que el producto no está registrado

        if ($objMarca == null) {
            return null;
        } else {
            return \App\Models\Almacen\Producto::where('estado', 1)->where('part_number', mb_strtoupper($nroParte))->where('id_subcategoria', $objMarca->id_subcategoria)->first();;
        }
    }

    /**
     * Devuelve un objeto del tipo Subcategoría si se encuentra la marca o NULL si no se encuentra en la BD
     * @param string $nombre Nombre de la marca a buscar
     */

    private function obtenerMarca($nombre)
    {
        return Subcategoria::where('descripcion', (empty($nombre) ? 'SIN MARCA' : $nombre))->first();
    }

    private function obtenerCliente($idEntidad)
    {
        $entidad = Entidad::find($idEntidad);
        if ($entidad->ruc != null) {
            $contribuyente = Contribuyente::where('nro_documento', $entidad->ruc)->first();
        } else {
            $nombreEntidad = Str::upper($entidad->nombre);
            $contribuyente = Contribuyente::whereRaw('UPPER(razon_social) = (?)', [$nombreEntidad])->first();
        }

        if ($contribuyente == null) {
            $contribuyente = new Contribuyente();
            $contribuyente->nro_documento = $entidad->ruc;
            $contribuyente->razon_social = Str::upper($entidad->nombre);
            $contribuyente->telefono = $entidad->telefono;
            $contribuyente->direccion_fiscal = $entidad->direccion;
            $contribuyente->ubigeo = null; //Ubigeo es string en MGCP, id (int) en Agile
            $contribuyente->fecha_registro = new Carbon();
            $contribuyente->email = $entidad->correo;
            $contribuyente->transportista = false;
            $contribuyente->save();
        }

        $cliente = Cliente::where('id_contribuyente', $contribuyente->id_contribuyente)->first();

        if ($cliente == null) {
            $cliente = new Cliente();
            $cliente->id_contribuyente = $contribuyente->id_contribuyente;
            $cliente->save();
        }

        return $cliente;
    }

    private function obtenerPeriodo($anio)
    {
        $periodo = Periodo::where('descripcion', $anio)->first();
        
        if ($periodo == null) {
            $periodo->descripcion = $anio;
            $periodo->estado = 1;
            $periodo->save();
        }

        return $periodo;
    }

    private function obtenerTipoDetalle($idCuadro) 
    {
        $idTipoDetalle = null;
        $cantidad_productos = 0;
        $cantidad_licencias = 0;
        $cantidad_servicios = 0;
        $cantidad_fondos_microsft = 0;

        if($idCuadro > 0){
            $filaCuadro = CcAmFila::where('id_cc_am',$idCuadro)->get();
            foreach ($filaCuadro as $key => $fila) {
                switch ($fila->id_tipo_fila) {
                    case 1:
                        $cantidad_productos++;
                        break;
                    case 2:
                        $cantidad_licencias++;
                        break;
                    case 3:
                        $cantidad_servicios++;
                        break;
                    case 4:
                        $cantidad_fondos_microsft++;
                        break;  
                    default:
                        break;
                }
            }
            //obtener tipo detalle para Agil
            if($cantidad_productos > 0 && $cantidad_licencias == 0 && $cantidad_servicios == 0 && $cantidad_fondos_microsft == 0 ){
                $idTipoDetalle=1;
            } else if($cantidad_productos  == 0 &&  $cantidad_servicios > 0 &&  $cantidad_licencias  == 0 && $cantidad_fondos_microsft == 0 ){
                $idTipoDetalle=2;
            }else{
                $idTipoDetalle=3;
            }
        } 
        return $idTipoDetalle;
    }
}

