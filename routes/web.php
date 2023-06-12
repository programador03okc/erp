<?php

use App\Http\Controllers\Almacen\Catalogo\ProductoController;
use App\Http\Controllers\Almacen\Catalogo\SubCategoriaController;
use App\Http\Controllers\Almacen\Movimiento\IngresoPdfController;
use App\Http\Controllers\Almacen\Movimiento\SalidasPendientesController;
use App\Http\Controllers\Almacen\Movimiento\TransferenciaController;
use App\Http\Controllers\Almacen\Movimiento\TransformacionController;
use App\Http\Controllers\Almacen\Reporte\SaldosController;
use App\Http\Controllers\Almacen\Ubicacion\AlmacenController as UbicacionAlmacenController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cas\IncidenciaController;
use App\Http\Controllers\Comercial\ClienteController;
use App\Http\Controllers\ComprasPendientesController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\Finanzas\CentroCosto\CentroCostoController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoInternoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Logistica\RequerimientoController;
use App\Http\Controllers\Logistica\Requerimientos\MapeoProductosController;
use App\Http\Controllers\Logistica\Requerimientos\TrazabilidadRequerimientoController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\Migraciones\MigrateRequerimientoSoftLinkController;
use App\Http\Controllers\NecesidadesController;
use App\Http\Controllers\Notificaciones\NotificacionController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\RecursosHumanosController;
use App\Http\Controllers\RevisarAprobarController;
use App\Http\Controllers\Tesoreria\RegistroPagoController;
use App\Http\Controllers\Tesoreria\RequerimientoPagoController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('artisan', function () {
    Artisan::call('clear-compiled');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});


Auth::routes();
Route::view('/', 'auth.login');

Route::get('modulos', [ConfiguracionController::class, 'getModulos'])->name('modulos');

Route::get('test-claves', [TestController::class, 'actualizarClaves'])->name('test-claves');

Route::middleware(['auth'])->group(function () {
    Route::get('cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');
    Route::get('inicio', [HomeController::class, 'index'])->name('inicio');


    Route::name('configuracion.')->prefix('configuracion')->group(function () { // TODO : falta agregar rutas

        Route::get('index', [ConfiguracionController::class, 'view_main_configuracion'])->name('index');
    });
    
    Route::name('notificaciones.')->prefix('notificaciones')->group(function () {
        Route::get('index', [NotificacionController::class, 'index'])->name('index');
        Route::get('ver/{id}', [NotificacionController::class, 'ver'])->name('ver');
        Route::post('eliminar', [NotificacionController::class, 'eliminar'])->name('eliminar');
        Route::post('lista-pendientes', [NotificacionController::class, 'listaPendientes'])->name('lista-pendientes');
        Route::post('cantidad-no-leidas', [NotificacionController::class, 'cantidadNoLeidas'])->name('cantidad-no-leidas');
    });
    
    Route::name('necesidades.')->prefix('necesidades')->group(function () {
        Route::get('index', [NecesidadesController::class, 'view_main_necesidades'])->name('index');
        Route::name('requerimiento.')->prefix('requerimiento')->group(function () {
    
            Route::name('elaboracion.')->prefix('elaboracion')->group(function () {
                Route::get('index', [RequerimientoController::class, 'index'])->name('index');
                Route::get('mostrar/{idRequerimiento?}', [RequerimientoController::class, 'mostrar'])->name('mostrar');
                Route::get('tipo-cambio-compra/{fecha?}', [SaldosController::class, 'tipo_cambio_compra'])->name('tipo-cambio-compra');
                Route::get('lista-divisiones', [RequerimientoController::class, 'listaDivisiones'])->name('lista-divisiones');
                Route::get('mostrar-partidas/{idGrupo?}/{idProyecto?}', [PresupuestoController::class, 'mostrarPresupuestos'])->name('mostrar-partidas');
                Route::get('mostrar-centro-costos', [CentroCostoController::class, 'mostrarCentroCostosSegunGrupoUsuario'])->name('mostrar-centro-costos');
                Route::post('guardar-requerimiento', [RequerimientoController::class, 'guardarRequerimiento'])->name('guardar-requerimiento');
                Route::post('actualizar-requerimiento', [RequerimientoController::class, 'actualizarRequerimiento'])->name('actualizar-requerimiento');
                Route::get('listar-sedes-por-empresa', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
                Route::get('mostrar-requerimiento/{id?}/{codigo?}', [RequerimientoController::class, 'mostrarRequerimiento'])->name('mostrar-requerimiento');
                Route::post('elaborados', [RequerimientoController::class, 'listarRequerimientosElaborados'])->name('elaborados');
                Route::post('imprimir-requerimiento-pdf/{id}/{codigo}', [RequerimientoController::class, 'generar_requerimiento_pdf'])->name('imprimir-requerimiento-pdf');
                Route::put('anular-requerimiento/{id_requerimiento?}', [RequerimientoController::class, 'anularRequerimiento'])->name('anular-requerimiento');
                Route::get('mostrar-categoria-adjunto', [RequerimientoController::class, 'mostrarCategoriaAdjunto'])->name('mostrar-categoria-adjunto');
                Route::get('listar-adjuntos-requerimiento-cabecera/{idRequerimento}', [RequerimientoController::class, 'listaAdjuntosRequerimientoCabecera'])->name('listar-adjuntos-requerimiento-cabecera');
                Route::get('listar-adjuntos-requerimiento-detalle/{idRequerimentoDetalle}', [RequerimientoController::class, 'listaAdjuntosRequerimientoDetalle'])->name('listar-adjuntos-requerimiento-detalle');
                Route::get('trazabilidad-detalle-requerimiento/{id}', [RequerimientoController::class, 'mostrarTrazabilidadDetalleRequerimiento'])->name('trazabilidad-detalle-requerimiento');
                Route::post('guardar', [LogisticaController::class, 'guardar_requerimiento'])->name('guardar');
                Route::put('actualizar/{id?}', [LogisticaController::class, 'actualizar_requerimiento'])->name('actualizar');
                Route::post('copiar-requerimiento/{id?}', [LogisticaController::class, 'copiar_requerimiento'])->name('copiar-requerimiento');
                Route::get('telefonos-cliente/{id_persona?}/{id_cliente?}', [LogisticaController::class, 'telefonos_cliente'])->name('telefonos-cliente');
                Route::get('direcciones-cliente/{id_persona?}/{id_cliente?}', [LogisticaController::class, 'direcciones_cliente'])->name('direcciones-cliente');
                Route::get('cuentas-cliente/{id_persona?}/{id_cliente?}', [LogisticaController::class, 'cuentas_cliente'])->name('cuentas-cliente');
                Route::post('guardar-cuentas-cliente', [LogisticaController::class, 'guardar_cuentas_cliente'])->name('guardar-cuentas-cliente');
                Route::get('emails-cliente/{id_persona?}/{id_cliente?}', [LogisticaController::class, 'emails_cliente'])->name('emails-cliente');
                Route::get('listar_ubigeos', [AlmacenController::class, 'listar_ubigeos'])->name('lista-ubigeos');
                Route::get('listar_personas', [RecursosHumanosController::class, 'mostrar_persona_table'])->name('listar-personas');
                Route::get('mostrar_clientes', [ClienteController::class, 'mostrar_clientes'])->name('mostrar-clientes');
                Route::get('cargar_almacenes/{id_sede}', [UbicacionAlmacenController::class, 'cargar_almacenes'])->name('cargar-almacenes');
                Route::post('guardar-archivos-adjuntos-detalle-requerimiento', [LogisticaController::class, 'guardar_archivos_adjuntos_detalle_requerimiento'])->name('guardar-archivos-adjuntos-detalle-requerimiento');
                Route::put('eliminar-archivo-adjunto-detalle-requerimiento/{id_archivo}',[LogisticaController::class],'eliminar_archivo_adjunto_detalle_requerimiento')->name('eliminar-archivo-adjunto-detalle-requerimiento');
                Route::post('guardar-archivos-adjuntos-requerimiento',[LogisticaController::class],'guardar_archivos_adjuntos_requerimiento')->name('guardar-archivos-adjuntos-requerimiento');
                Route::put('eliminar-archivo-adjunto-requerimiento/{id_archivo}',[LogisticaController::class,'eliminar_archivo_adjunto_requerimiento'])->name('eliminar-archivo-adjunto-requerimiento');
                Route::get('mostrar-archivos-adjuntos-requerimiento/{id_requerimiento?}/{categoria?}',[RequerimientoController::class],'mostrarArchivosAdjuntosRequerimiento')->name('mostrar-archivos-adjuntos-requerimiento');
                Route::get('listar_almacenes',[AlmacenController::class],'mostrar_almacenes')->name('listar-almacenes');
                Route::get('mostrar-sede',[ConfiguracionController::class],'mostrarSede')->name('mostrar-sede');
                Route::get('mostrar_proveedores',[LogisticaController::class],'mostrar_proveedores')->name('mostrar-proveedores');
                Route::post('guardar_proveedor',[LogisticaController::class],'guardar_proveedor')->name('guardar-proveedor');
                Route::get('getCodigoRequerimiento/{id}',[LogisticaController::class],'getCodigoRequerimiento')->name('getCodigoRequerimiento');
                Route::get('mostrar-archivos-adjuntos/{id_detalle_requerimiento}',[RequerimientoController::class],'mostrarArchivosAdjuntos')->name('mostrar-archivos-adjuntos');
                Route::post('save_cliente',[LogisticaController::class],'save_cliente')->name('save-cliente');
                Route::get('listar_saldos',[AlmacenController::class],'listar_saldos')->name('listar-saldos');
                Route::get('listar_opciones',[ProyectosController::class],'listar_opciones')->name('listar-opciones');
                Route::get('listar-saldos-por-almacen',[AlmacenController::class],'listar_saldos_por_almacen')->name('listar-saldos-por-almacen');
                Route::get('listar-saldos-por-almacen/{id_producto}',[AlmacenController::class],'listar_saldos_por_almacen_producto')->name('listar-saldos-por-almacen');
                Route::get('obtener-promociones/{id_producto}/{id_almacen}',[LogisticaController::class],'obtener_promociones')->name('obtener-promociones');
                Route::get('migrar_venta_directa/{id}',[MigrateRequerimientoSoftLinkController::class],'migrar_venta_directa')->name('migrar-venta-directa');
                Route::post('guardar-producto',[AlmacenController::class],'guardar_producto')->name('guardar-producto');
                Route::get('cuadro-costos/{id_cc?}',[RequerimientoController::class],'cuadro_costos')->name('cuadro-costos');
                Route::get('detalle-cuadro-costos/{id_cc?}',[RequerimientoController::class],'detalle_cuadro_costos')->name('detalle-cuadro-costos');
                Route::post('obtener-construir-cliente',[RequerimientoController::class],'obtenerConstruirCliente')->name('obtener-construir-cliente');
                Route::get('proyectos-activos',[ProyectosController::class],'listar_proyectos_activos')->name('proyectos-activos');
                Route::get('grupo-select-item-para-compra',[ComprasPendientesController::class],'getGrupoSelectItemParaCompra')->name('grupo-select-item-para-compra');
                Route::get('mostrar-fuente',[LogisticaController::class],'mostrarFuente')->name('mostrar-fuente');
                Route::post('guardar-fuente',[LogisticaController::class],'guardarFuente')->name('guardar-fuente');
                Route::post('anular-fuente',[LogisticaController::class],'anularFuente')->name('anular-fuente');
                Route::post('actualizar-fuente',[LogisticaController::class],'actualizarFuente')->name('actualizar-fuente');
                Route::post('guardar-detalle-fuente',[LogisticaController::class],'guardarDetalleFuente')->name('guardar-detalle-fuente');
                Route::get('mostrar-fuente-detalle/{fuente_id?}',[LogisticaController::class],'mostrarFuenteDetalle')->name('mostrar-fuente-detalle');
                Route::post('anular-detalle-fuente',[LogisticaController::class],'anularDetalleFuente')->name('anular-detalle-fuente');
                Route::post('actualizar-detalle-fuente',[LogisticaController::class],'actualizarDetalleFuente')->name('actualizar-detalle-fuente');
                Route::get('buscar-stock-almacenes/{id_item?}',[RequerimientoController::class],'buscarStockEnAlmacenes')->name('buscar-stock-almacenes');
                Route::get('listar_trabajadores',[ProyectosController::class],'listar_trabajadores')->name('listar-trabajadores');
                Route::post('lista-cuadro-presupuesto',[RequerimientoPagoController::class],'listaCuadroPresupuesto')->name('lista-cuadro-presupuesto');
                Route::post('listarIncidencias',[IncidenciaController::class],'listarIncidencias')->name('listar-incidencias');
                Route::get('combo-presupuesto-interno/{idGrupo?}/{idArea?}',[PresupuestoInternoController::class],'comboPresupuestoInterno')->name('combo-presupuesto-interno');
                Route::get('obtener-detalle-presupuesto-interno/{idPresupuesto?}',[PresupuestoInternoController::class],'PresupuestoInternoController')->name('obtener-detalle-presupuesto-interno');
                Route::get('obtener-lista-proyectos/{idGrupo?}',[RequerimientoController::class],'obtenerListaProyectos')->name('obtener-lista-proyectos');
    
            });
            Route::name('listado.')->prefix('listado')->group(function () {
                Route::get('index', [RequerimientoController::class, 'viewLista'])->name('index');
                Route::post('elaborados', [RequerimientoController::class, 'listarRequerimientosElaborados'])->name('elaborados');
                Route::post('ver-flujos/{req?}/{doc?}', [RequerimientoController::class, 'flujoAprobacion'])->name('ver-flujos');
                Route::get('mostrar-divisiones/{idGrupo?}', [RequerimientoController::class, 'mostrarDivisionesDeGrupo'])->name('mostrar-divisiones');
                Route::get('requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'mostrarCabeceraRequerimiento'])->name('mostrar-cabecera-requerimiento');
                Route::get('detalle-requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'detalleRequerimiento'])->name('detalle-requerimientos');
                Route::get('historial-aprobacion/{idRequerimiento?}', [RequerimientoController::class, 'mostrarHistorialAprobacion'])->name('mostrar-historial-aprobacion');
                Route::get('trazabilidad-detalle-requerimiento/{id}', [RequerimientoController::class, 'mostrarTrazabilidadDetalleRequerimiento'])->name('trazabilidad-detalle-requerimiento');
                Route::get('mostrar-requerimiento/{id?}/{codigo?}', [RequerimientoController::class, 'mostrarRequerimiento'])->name('mostrar-requerimiento');
                Route::put('anular-requerimiento/{id_requerimiento?}', [RequerimientoController::class, 'anularRequerimiento'])->name('anular-requerimiento');
                Route::get('imprimir-requerimiento-pdf/{id}/{codigo}', [RequerimientoController::class, 'generar_requerimiento_pdf'])->name('imprimir-requerimiento-pdf');
                Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
                Route::get('mostrarDocumentosByRequerimiento/{id}', [TrazabilidadRequerimientoController::class, 'mostrarDocumentosByRequerimiento'])->name('mostrar-documentos-por-requerimiento');
                Route::get('imprimir_transferencia/{id}', [TransferenciaController::class, 'imprimir_transferencia'])->name('imprimir-transferencia');
                Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
                Route::get('imprimir_salida/{id}', [SalidasPendientesController::class, 'imprimir_salida'])->name('imprimir-salida');
                Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
                Route::get('reporte-requerimientos-bienes-servicios-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoController::class, 'reporteRequerimientosBienesServiciosExcel'])->name('reporte-requerimientos-bienes-servicios-excel');
                Route::get('reporte-items-requerimientos-bienes-servicios-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoController::class, 'reporteItemsRequerimientosBienesServiciosExcel'])->name('reporte-items-requerimientos-bienes-servicios-excel');
                Route::get('listar-todo-archivos-adjuntos-requerimiento-logistico/{id}', [RequerimientoController::class, 'listarTodoArchivoAdjuntoRequerimientoLogistico'])->name('listar-todo-archivos-adjuntos-requerimiento-logistico');
                Route::post('anular-adjunto-requerimiento-logístico-cabecera', [RequerimientoController::class, 'anularArchivoAdjuntoRequerimientoLogisticoCabecera'])->name('anular-adjunto-requerimiento-logístico-cabecera');
                Route::post('anular-adjunto-requerimiento-logístico-detalle', [RequerimientoController::class, 'anularArchivoAdjuntoRequerimientoLogisticoDetalle'])->name('anular-adjunto-requerimiento-logístico-detalle');
                Route::get('lista-adjuntos-pago/{idRequerimientoPago}', [RegistroPagoController::class, 'listarAdjuntosPago'])->name('lista-adjuntos-pago');
                Route::get('listar-archivos-adjuntos-pago/{id}', [RequerimientoController::class, 'listarArchivoAdjuntoPago'])->name('listar-archivos-adjuntos-pago');
                Route::get('listar-otros-adjuntos-tesoreria-orden-requerimiento/{id}', [RequerimientoController::class, 'listarOtrsAdjuntosTesoreriaOrdenRequerimiento'])->name('listar-otros-adjuntos-tesoreria-orden-requerimiento');
                Route::get('listar-adjuntos-logisticos/{id}', [RequerimientoController::class, 'listarAdjuntosLogisticos'])->name('listar-adjuntos-logisticos');
                Route::get('listar-categoria-adjunto', [RequerimientoController::class, 'mostrarCategoriaAdjunto'])->name('listar-categoria-adjunto');
                Route::post('guardar-adjuntos-adicionales-requerimiento-compra', [RequerimientoController::class, 'guardarAdjuntosAdicionales'])->name('guardar-adjuntos-adicionales-requerimiento-compra');
                Route::get('listar-flujo/{idDocumento}', [RevisarAprobarController::class, 'mostrarTodoFlujoAprobacionDeDocumento'])->name('listar-flujo');
    
            });
    
            Route::name('mapeo.')->prefix('mapeo')->group(function () {
    
                Route::get('index', [MapeoProductosController::class, 'view_mapeo_productos'])->name('index');
                Route::post('listarRequerimientos', [MapeoProductosController::class, 'listarRequerimientos'])->name('listar-requerimiento');
                Route::get('itemsRequerimiento/{id}', [MapeoProductosController::class, 'itemsRequerimiento'])->name('items-requerimiento');
                Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-productos');
                Route::post('listarProductosSugeridos', [ProductoController::class, 'listarProductosSugeridos'])->name('listar-productos-sugeridos');
                Route::get('mostrar_prods_sugeridos/{part}/{desc}', [ProductoController::class, 'mostrar_prods_sugeridos'])->name('mostrar-productos-sugeridos');
                Route::post('guardar_mapeo_productos', [MapeoProductosController::class, 'guardar_mapeo_productos'])->name('guardar-mapeo-productos');
                Route::get('mostrar_categorias_tipo/{id}', [SubCategoriaController::class, 'mostrarSubCategoriasPorCategoria'])->name('mostrar-categorias-tipo');
            });
        });
    
        Route::name('pago.')->prefix('pago')->group(function () {
            Route::name('listado.')->prefix('listado')->group(function () {
                Route::get('index', [RequerimientoPagoController::class, 'viewListaRequerimientoPago'])->name('index');
                Route::get('listado-requerimientos-pagos-export-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoPagoController::class, 'viewlistadoRequerimientoPagoExportExcelListaRequerimientoPago'])->name('listado-requerimientos-pagos-export-excel');
                Route::get('listado-items-requerimientos-pagos-export-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoPagoController::class, 'listadoItemsRequerimientoPagoExportExcel'])->name('listado-items-requerimientos-pagos-export-excel');
                Route::post('lista-requerimiento-pago', [RequerimientoPagoController::class, 'listarRequerimientoPago'])->name('lista-requerimiento-pago');
                Route::get('lista-adjuntos-pago/{idRequerimientoPago}', [RegistroPagoController::class, 'listarAdjuntosPago'])->name('lista-adjuntos-pago');
                Route::get('obtener-otros-adjuntos-tesoreria/{id_requerimiento_pago}', [RequerimientoPagoController::class, 'obtenerOtrosAdjuntosTesoreria'])->name('obtener-otros-adjuntos-tesoreria');
                Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
                Route::get('listar-division-por-grupo/{id?}', [RequerimientoController::class, 'listarDivisionPorGrupo'])->name('listar-division-por-grupo');
                Route::get('mostrar-partidas/{idGrupo?}/{idProyecto?}', [PresupuestoController::class, 'mostrarPresupuestos'])->name('mostrar-partidas');
                Route::get('mostrar-centro-costos', [CentroCostoController::class, 'mostrarCentroCostosSegunGrupoUsuario'])->name('mostrar-centro-costos');
                Route::post('guardar-requerimiento-pago', [RequerimientoPagoController::class, 'guardarRequerimientoPago'])->name('guardar-requerimiento-pago');
                Route::post('lista-cuadro-presupuesto', [RequerimientoPagoController::class, 'listaCuadroPresupuesto'])->name('lista-cuadro-presupuesto');
                Route::get('mostrar-requerimiento-pago/{idRequerimientoPago}', [RequerimientoPagoController::class, 'mostrarRequerimientoPago'])->name('mostrar-requerimiento-pago');
                Route::post('actualizar-requerimiento-pago', [RequerimientoPagoController::class, 'actualizarRequerimientoPago'])->name('actualizar-requerimiento-pago');
                Route::post('anular-requerimiento-pago', [RequerimientoPagoController::class, 'anularRequerimientoPago'])->name('anular-requerimiento-pago');
                Route::get('listar-adjuntos-requerimiento-pago-cabecera/{idRequerimentoPago}', [RequerimientoPagoController::class, 'listaAdjuntosRequerimientoPagoCabecera'])->name('listar-adjuntos-requerimiento-pago-cabecera');
                Route::get('listar-adjuntos-requerimiento-pago-detalle/{idRequerimentoPagoDetalle}', [RequerimientoPagoController::class, 'listaAdjuntosRequerimientoPagoDetalle'])->name('listar-adjuntos-requerimiento-pago-detalle');
                Route::get('listar-categoria-adjunto', [ContabilidadController::class, 'listaTipoDocumentos'])->name('listar-categoria-adjunto');
                Route::post('mostrar-proveedores', [OrdenController::class, 'mostrarProveedores'])->name('mostrar-proveedores');
                Route::get('listar-cuentas-bancarias-proveedor/{idProveedor?}', [OrdenController::class, 'listarCuentasBancariasProveedor'])->name('listar-cuentas-bancarias-proveedor');
                Route::post('guardar-cuenta-bancaria-proveedor', [OrdenController::class, 'guardarCuentaBancariaProveedor'])->name('guardar-cuenta-bancaria-proveedor');
                Route::get('imprimir-requerimiento-pago-pdf/{id}', [RequerimientoPagoController::class, 'imprimirRequerimientoPagoPdf'])->name('imprimir-requerimiento-pago-pdf');
                Route::post('obtener-destinatario-por-nro-documento', [RequerimientoPagoController::class, 'obtenerDestinatarioPorNumeroDeDocumento'])->name('obtener-destinatario-por-nro-documento');
                Route::post('obtener-destinatario-por-nombre', [RequerimientoPagoController::class, 'obtenerDestinatarioPorNombre'])->name('obtener-destinatario-por-nombre');
                Route::post('guardar-contribuyente', [RequerimientoPagoController::class, 'guardarContribuyente'])->name('guardar-contribuyentee');
                Route::post('guardar-persona', [RequerimientoPagoController::class, 'guardarPersona'])->name('guardar-personae');
                Route::post('guardar-cuenta-destinatario', [RequerimientoPagoController::class, 'guardarCuentaDestinatario'])->name('guardar-cuenta-destinatario');
                Route::get('obtener-cuenta-persona/{idPersona}', [RequerimientoPagoController::class, 'obtenerCuentaPersona'])->name('obtener-cuenta-persona');
                Route::get('obtener-cuenta-contribuyente/{idContribuyente}', [RequerimientoPagoController::class, 'obtenerCuentaContribuyente'])->name('obtener-cuenta-contribuyente');
                Route::get('listar-todo-archivos-adjuntos-requerimiento-pago/{id}', [RequerimientoPagoController::class, 'listarTodoArchivoAdjuntoRequerimientoPago'])->name('listar-todo-archivos-adjuntos-requerimiento-pago');
                Route::post('guardar-adjuntos-adicionales-requerimiento-pago', [RequerimientoPagoController::class, 'guardarAdjuntosAdicionales'])->name('guardar-adjuntos-adicionales-requerimiento-pago');
                Route::post('anular-adjunto-requerimiento-pago-cabecera', [RequerimientoPagoController::class, 'anularAdjuntoRequerimientoPagoCabecera'])->name('anular-adjunto-requerimiento-pago-cabecera');
                Route::post('anular-adjunto-requerimiento-pago-detalle', [RequerimientoPagoController::class, 'anularAdjuntoRequerimientoPagoDetalle'])->name('anular-adjunto-requerimiento-pago-detalle');
                Route::get('listar_trabajadores', [ProyectosController::class, 'listar_trabajadores'])->name('listar-trabajadores');
                Route::get('combo-presupuesto-interno/{idGrupo?}/{idArea?}', [PresupuestoInternoController::class, 'comboPresupuestoInterno'])->name('combo-presupuesto-interno');
                Route::get('obtener-detalle-presupuesto-interno/{idPresupuesto?}', [PresupuestoInternoController::class, 'obtenerDetallePresupuestoInterno'])->name('obtener-detalle-presupuesto-interno');
                Route::get('obtener-lista-proyectos/{idGrupo?}', [RequerimientoController::class, 'obtenerListaProyectos'])->name('obtener-lista-proyectos');
    
            });
        });
    
        Route::name('revisar-aprobar.')->prefix('revisar-aprobar')->group(function () {
            Route::name('listado.')->prefix('listado')->group(function () {
    
                Route::get('index', [RevisarAprobarController::class, 'viewListaRequerimientoPagoPendienteParaAprobacion'])->name('index');
                Route::post('documentos-pendientes', [RevisarAprobarController::class, 'mostrarListaDeDocumentosPendientes'])->name('documentos-pendientes');
                Route::post('documentos-aprobados', [RevisarAprobarController::class, 'mostrarListaDeDocumentosAprobados'])->name('documentos-aprobados');
                Route::get('imprimir-requerimiento-pago-pdf/{id}', [RequerimientoPagoController::class, 'imprimirRequerimientoPagoPdf'])->name('imprimir-requerimiento-pago-pdf');
                Route::post('guardar-respuesta', [RevisarAprobarController::class, 'guardarRespuesta'])->name('guardar-respuesta');
                Route::get('mostrar-requerimiento-pago/{idRequerimientoPago}', [RequerimientoPagoController::class, 'mostrarRequerimientoPago'])->name('mostrar-requerimiento-pago');
                Route::get('listar-categoria-adjunto', [ContabilidadController::class, 'listaTipoDocumentos'])->name('listar-categoria-adjunto');
                Route::get('listar-adjuntos-requerimiento-pago-cabecera/{idRequerimentoPago}', [RequerimientoPagoController::class, 'listaAdjuntosRequerimientoPagoCabecera'])->name('listar-adjuntos-requerimiento-pago-cabecera');
                Route::get('listar-adjuntos-requerimiento-pago-detalle/{idRequerimentoPagoDetalle}', [RequerimientoPagoController::class, 'listaAdjuntosRequerimientoPagoDetalle'])->name('listar-adjuntos-requerimiento-pago-detalle');
                Route::get('mostrar-requerimiento/{id?}/{codigo?}', [RequerimientoController::class, 'mostrarRequerimiento'])->name('mostrar-requerimiento');
                Route::get('test-operacion/{idTipoDocumento}/{idTipoRequerimientoCompra}/{idGrupo}/{idDivision}/{idPrioridad}/{idMoneda}/{montoTotal}/{idTipoRequerimientoPago}/{idRolUsuarioDocList}', [RequerimientoController::class, 'getOperacion'])->name('test-operacion');
            });
        });
    
        Route::name('ecommerce.')->prefix('ecommerce')->group(function () {
            Route::get('index', [EcommerceController::class, 'index'])->name('index');
            Route::get('crear', [EcommerceController::class, 'crear'])->name('crear');
            Route::post('guardar', [EcommerceController::class, 'guardar'])->name('guardar');
            Route::post('buscar-trabajador', [EcommerceController::class, 'buscarTrabajador'])->name('buscar-trabajador');
    
        });
    
    });
});



