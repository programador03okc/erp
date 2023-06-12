<?php

use App\Exports\CatalogoProductoExport;
use App\Http\Controllers\Almacen\Catalogo\CategoriaController;
use App\Http\Controllers\Almacen\Catalogo\ClasificacionController;
use App\Http\Controllers\Almacen\Catalogo\MarcaController;
use App\Http\Controllers\Almacen\Catalogo\ProductoController;
use App\Http\Controllers\Almacen\Catalogo\SubCategoriaController;
use App\Http\Controllers\Almacen\Movimiento\CustomizacionController;
use App\Http\Controllers\Almacen\Movimiento\DevolucionController;
use App\Http\Controllers\Almacen\Movimiento\GuiaSalidaExcelFormatoOKCController;
use App\Http\Controllers\Almacen\Movimiento\GuiaSalidaExcelFormatoSVSController;
use App\Http\Controllers\Almacen\Movimiento\IngresoPdfController;
use App\Http\Controllers\Almacen\Movimiento\OrdenesPendientesController;
use App\Http\Controllers\Almacen\Movimiento\ProrrateoCostosController;
use App\Http\Controllers\Almacen\Movimiento\ReservasAlmacenController;
use App\Http\Controllers\Almacen\Movimiento\SaldoProductoController;
use App\Http\Controllers\Almacen\Movimiento\SalidaPdfController;
use App\Http\Controllers\Almacen\Movimiento\SalidasPendientesController;
use App\Http\Controllers\Almacen\Movimiento\TransferenciaController;
use App\Http\Controllers\Almacen\Movimiento\TransformacionController;
use App\Http\Controllers\Almacen\Reporte\KardexSerieController;
use App\Http\Controllers\Almacen\Reporte\ListaRequerimientosAlmacenController;
use App\Http\Controllers\Almacen\Reporte\ListaSalidasController;
use App\Http\Controllers\Almacen\Reporte\ReportesController;
use App\Http\Controllers\Almacen\Reporte\SaldosController;
use App\Http\Controllers\Almacen\StockController;
use App\Http\Controllers\Almacen\Ubicacion\AlmacenController as UbicacionAlmacenController;
use App\Http\Controllers\Almacen\Ubicacion\PosicionController;
use App\Http\Controllers\Almacen\Ubicacion\TipoAlmacenController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cas\CasMarcaController;
use App\Http\Controllers\Cas\CasModeloController;
use App\Http\Controllers\Cas\CasProductoController;
use App\Http\Controllers\Cas\FichaReporteController;
use App\Http\Controllers\Cas\IncidenciaController;
use App\Http\Controllers\Comercial\ClienteController;
use App\Http\Controllers\ComprasPendientesController;
use App\Http\Controllers\ComprobanteCompraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Logistica\Distribucion\DistribucionController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoExternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoInternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesTransformacionController;
use App\Http\Controllers\Logistica\RequerimientoController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\Migraciones\MigrateFacturasSoftlinkController;
use App\Http\Controllers\Tesoreria\Facturacion\PendientesFacturacionController;
use App\Http\Controllers\Tesoreria\Facturacion\VentasInternasController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
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


	/**
	 * Configuración
	 */
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
    
	/**
	 * Necesidades
	 */
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

	/**
	 * Almacén
	 */
	Route::group(['as' => 'almacen.', 'prefix' => 'almacen'], function () {
		#script 1
		Route::get('script-categoria', [AlmacenController::class,'scripCategoria']);
		#script 2
		Route::get('script-actualizar-categoria-softlink', [AlmacenController::class,'scripActualizarCategoriasSoftlink']);

		Route::get('index', [AlmacenController::class,'view_main_almacen'])->name('index');

		Route::get('getEstadosRequerimientos/{filtro}', [DistribucionController::class,'getEstadosRequerimientos']);
		Route::get('listarEstadosRequerimientos/{id}/{filtro}', [DistribucionController::class,'listarEstadosRequerimientos']);

		Route::group(['as' => 'catalogos.', 'prefix' => 'catalogos'], function () {
			Route::group(['as' => 'clasificaciones.', 'prefix' => 'clasificaciones'], function () {
				//Clasificacion
				Route::get('index', [ClasificacionController::class,'view_clasificacion'])->name('index');
				Route::get('listarClasificaciones', [ClasificacionController::class,'listarClasificaciones']);
				Route::get('mostrarClasificacion/{id}', [ClasificacionController::class,'mostrarClasificacion']);
				Route::post('guardarClasificacion', [ClasificacionController::class,'guardarClasificacion']);
				Route::post('actualizarClasificacion', [ClasificacionController::class,'actualizarClasificacion']);
				Route::get('anularClasificacion/{id}', [ClasificacionController::class,'anularClasificacion']);
				Route::get('revisarClasificacion/{id}', [ClasificacionController::class,'revisarClasificacion']);
			});

			Route::group(['as' => 'categorias.', 'prefix' => 'categorias'], function () {
				//Categoria
				Route::get('index', [CategoriaController::class,'view_categoria'])->name('index');
				Route::get('listarCategorias', [CategoriaController::class,'listarCategorias']);
				Route::get('mostrarCategoria/{id}', [CategoriaController::class,'mostrarCategoria']);
				Route::post('guardarCategoria', [CategoriaController::class,'guardarCategoria']);
				Route::post('actualizarCategoria', [CategoriaController::class,'actualizarCategoria']);
				Route::get('anularCategoria/{id}', [CategoriaController::class,'anularCategoria']);
				Route::get('revisarCategoria/{id}', [CategoriaController::class,'revisarCategoria']);
			});

			Route::group(['as' => 'sub-categorias.', 'prefix' => 'sub-categorias'], function () {
				//SubCategoria
				Route::get('index', [SubCategoriaController::class,'view_sub_categoria'])->name('index');
				Route::get('listar_categorias', [SubCategoriaController::class,'mostrar_categorias']);
				Route::get('mostrar_categoria/{id}', [SubCategoriaController::class,'mostrar_categoria']);
				Route::post('guardar_categoria', [SubCategoriaController::class,'guardar_categoria']);
				Route::post('actualizar_categoria', [SubCategoriaController::class,'update_categoria']);
				Route::get('anular_categoria/{id}', [SubCategoriaController::class,'anular_categoria']);
				Route::get('revisarCat/{id}', [SubCategoriaController::class,'cat_revisar']);

				Route::get('mostrar_tipos_clasificacion/{id}', [CategoriaController::class,'mostrarCategoriasPorClasificacion']);
			});

			Route::group(['as' => 'marcas.', 'prefix' => 'marcas'], function () {
				//Marca
				Route::get('index', [MarcaController::class,'viewMarca'])->name('index');
				Route::get('listarMarcas', [MarcaController::class,'listarMarcas']);
				Route::get('mostrarMarca/{id}', [MarcaController::class,'mostrarMarca']);
				Route::post('guardarMarca', [MarcaController::class,'guardarMarca']);
				Route::post('actualizarMarca', [MarcaController::class,'actualizarMarca']);
				Route::get('anularMarca/{id}', [MarcaController::class,'anularMarca']);
				Route::get('revisarMarca/{id}', [MarcaController::class,'revisarMarca']);

				//Route::post('guardar-marca', [MarcaController::class,'@guardar')->name('guardar-marca');
			});

			Route::group(['as' => 'productos.', 'prefix' => 'productos'], function () {
				//Producto
				Route::get('index', [ProductoController::class,'view_producto'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class,'mostrar_prods_almacen']);
				Route::get('mostrar_producto/{id}', [ProductoController::class,'mostrar_producto']);
				Route::get('mostrarCategoriasPorClasificacion/{id}', [CategoriaController::class,'mostrarCategoriasPorClasificacion']);
				Route::get('mostrarSubCategoriasPorCategoria/{id}', [SubCategoriaController::class,'mostrarSubCategoriasPorCategoria']);
				Route::post('guardar_producto', [ProductoController::class,'guardar_producto']);
				Route::post('actualizar_producto', [ProductoController::class,'update_producto']);
				Route::get('anular_producto/{id}', [ProductoController::class,'anular_producto']);
				Route::post('guardar_imagen', [ProductoController::class,'guardar_imagen']);

				Route::get('listar_promociones/{id}', [ProductoController::class,'listar_promociones']);
				Route::post('crear_promocion', [ProductoController::class,'crear_promocion']);
				Route::get('anular_promocion/{id}', [ProductoController::class,'anular_promocion']);

				Route::get('listar_ubicaciones_producto/{id}', [ProductoController::class,'listar_ubicaciones_producto']);
				Route::get('mostrar_ubicacion/{id}', [ProductoController::class,'mostrar_ubicacion']);
				Route::post('guardar_ubicacion', [ProductoController::class,'guardar_ubicacion']);
				Route::post('actualizar_ubicacion', [ProductoController::class,'update_ubicacion']);
				Route::get('anular_ubicacion/{id}', [ProductoController::class,'anular_ubicacion']);

				Route::get('listar_series_producto/{id}', [ProductoController::class,'listar_series_producto']);
				Route::get('mostrar_serie/{id}', [ProductoController::class,'mostrar_serie']);
				Route::post('guardar_serie', [ProductoController::class,'guardar_serie']);
				Route::post('actualizar_serie', [ProductoController::class,'update_serie']);
				Route::get('anular_serie/{id}', [ProductoController::class,'anular_serie']);

				Route::get('obtenerProductoSoftlink/{id}', [MigrateProductoSoftlinkController::class,'obtenerProductoSoftlink']);
			});

			Route::group(['as' => 'catalogo-productos.', 'prefix' => 'catalogo-productos'], function () {
				Route::get('index', [ProductoController::class,'view_prod_catalogo'])->name('index');
				Route::get('listar_productos', [ProductoController::class,'mostrar_productos']);
				// Route::post('productosExcel', [ProductoController::class,'productosExcel')->name('productosExcel');
				Route::post('catalogoProductosExcel', function () {
					return Excel::download(new CatalogoProductoExport, 'Catalogo_Productos.xlsx');
				})->name('catalogoProductosExcel');
			});
		});

		Route::group(['as' => 'ubicaciones.', 'prefix' => 'ubicaciones'], function () {
			Route::group(['as' => 'tipos-almacen.', 'prefix' => 'tipos-almacen'], function () {
				//Tipos Almacen
				Route::get('index', [TipoAlmacenController::class,'view_tipo_almacen'])->name('index');
				Route::get('listar_tipo_almacen', [TipoAlmacenController::class,'mostrar_tipo_almacen']);
				Route::get('cargar_tipo_almacen/{id}', [TipoAlmacenController::class,'mostrar_tipo_almacenes']);
				Route::post('guardar_tipo_almacen', [TipoAlmacenController::class,'guardar_tipo_almacen']);
				Route::post('editar_tipo_almacen', [TipoAlmacenController::class,'update_tipo_almacen']);
				Route::get('anular_tipo_almacen/{id}', [TipoAlmacenController::class,'anular_tipo_almacen']);
			});

			Route::group(['as' => 'almacenes.', 'prefix' => 'almacenes'], function () {
				//Almacen
				Route::get('index', [UbicacionAlmacenController::class,'view_almacenes'])->name('index');
				Route::get('listar_almacenes', [UbicacionAlmacenController::class,'mostrar_almacenes']);
				Route::get('mostrar_almacen/{id}', [UbicacionAlmacenController::class,'mostrar_almacen']);
				Route::post('guardar_almacen', [UbicacionAlmacenController::class,'guardar_almacen']);
				Route::post('editar_almacen', [UbicacionAlmacenController::class,'update_almacen']);
				Route::get('anular_almacen/{id}', [UbicacionAlmacenController::class,'anular_almacen']);
				Route::get('listar_ubigeos', [UbicacionAlmacenController::class,'listar_ubigeos']);

				Route::get('almacen_posicion/{id}', [PosicionController::class,'almacen_posicion']);
				Route::get('listarUsuarios', [UbicacionAlmacenController::class,'listarUsuarios']);
				Route::post('guardarAlmacenUsuario', [UbicacionAlmacenController::class,'guardarAlmacenUsuario']);
				Route::get('listarAlmacenUsuarios/{id}', [UbicacionAlmacenController::class,'listarAlmacenUsuarios']);
				Route::get('anularAlmacenUsuario/{id}', [UbicacionAlmacenController::class,'@anularAlmacenUsuario']);
			});

			Route::group(['as' => 'posiciones.', 'prefix' => 'posiciones'], function () {
				//Almacen
				Route::get('index', [PosicionController::class,'view_ubicacion'])->name('index');
				Route::get('listar_estantes', [PosicionController::class,'mostrar_estantes']);
				Route::get('listar_estantes_almacen/{id}', [PosicionController::class,'mostrar_estantes_almacen']);
				Route::get('mostrar_estante/{id}', [PosicionController::class,'mostrar_estante']);
				Route::post('guardar_estante', [PosicionController::class,'guardar_estante']);
				Route::post('actualizar_estante', [PosicionController::class,'update_estante']);
				Route::get('anular_estante/{id}', [PosicionController::class,'anular_estante']);
				Route::get('revisar_estante/{id}', [PosicionController::class,'revisar_estante']);
				Route::post('guardar_estantes', [PosicionController::class,'guardar_estantes']);
				Route::get('listar_niveles', [PosicionController::class,'mostrar_niveles']);
				Route::get('listar_niveles_estante/{id}', [PosicionController::class,'mostrar_niveles_estante']);
				Route::get('mostrar_nivel/{id}', [PosicionController::class,'mostrar_nivel']);
				Route::post('guardar_nivel', [PosicionController::class,'guardar_nivel']);
				Route::post('actualizar_nivel', [PosicionController::class,'update_nivel']);
				Route::get('anular_nivel/{id}', [PosicionController::class,'anular_nivel']);
				Route::get('revisar_nivel/{id}', [PosicionController::class,'revisar_nivel']);
				Route::post('guardar_niveles', [PosicionController::class,'guardar_niveles']);
				Route::get('listar_posiciones', [PosicionController::class,'mostrar_posiciones']);
				Route::get('listar_posiciones_nivel/{id}', [PosicionController::class,'mostrar_posiciones_nivel']);
				Route::get('mostrar_posicion/{id}', [PosicionController::class,'mostrar_posicion']);
				Route::post('guardar_posiciones', [PosicionController::class,'guardar_posiciones']);
				Route::get('anular_posicion/{id}', [PosicionController::class,'anular_posicion']);
				Route::get('select_posiciones_almacen/{id}', [PosicionController::class,'select_posiciones_almacen']);
				Route::get('listar_almacenes', [UbicacionAlmacenController::class,'mostrar_almacenes']);
			});
		});

		Route::group(['as' => 'control-stock.', 'prefix' => 'control-stock'], function () {
			Route::group(['as' => 'importar.', 'prefix' => 'importar'], function () {
				Route::get('index', [StockController::class,'view_importar'])->name('index');
			});

			Route::group(['as' => 'toma-inventario.', 'prefix' => 'toma-inventario'], function () {
				Route::get('index', [StockController::class,'view_toma_inventario'])->name('index');
			});
		});

		Route::group(['as' => 'movimientos.', 'prefix' => 'movimientos'], function () {
			Route::group(['as' => 'pendientes-ingreso.', 'prefix' => 'pendientes-ingreso'], function () {
				//Pendientes de Ingreso
				Route::get('index', [OrdenesPendientesController::class,'view_ordenesPendientes'])->name('index');
				Route::post('listarOrdenesPendientes', [OrdenesPendientesController::class,'listarOrdenesPendientes']);
				Route::post('listarIngresos', [OrdenesPendientesController::class,'listarIngresos']);
				Route::get('detalleOrden/{id}/{soloProductos}', [OrdenesPendientesController::class,'detalleOrden']);
				Route::post('guardar_guia_com_oc', [OrdenesPendientesController::class,'guardar_guia_com_oc']);
				Route::get('verGuiasOrden/{id}', [OrdenesPendientesController::class,'verGuiasOrden']);
				// Route::post('guardar_guia_transferencia', [OrdenesPendientesController::class,'guardar_guia_transferencia');
				Route::post('anular_ingreso', [OrdenesPendientesController::class,'anular_ingreso']);
				Route::get('cargar_almacenes/{id}', [UbicacionAlmacenController::class,'cargar_almacenes']);
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class,'imprimir_ingreso']);

				Route::post('detalleOrdenesSeleccionadas', [OrdenesPendientesController::class,'detalleOrdenesSeleccionadas']);
				Route::get('detalleMovimiento/{id}', [OrdenesPendientesController::class,'detalleMovimiento']);
				Route::post('listarTransformacionesFinalizadas', [TransformacionController::class,'listarTransformacionesFinalizadas']);
				Route::get('listarDetalleTransformacion/{id}', [TransformacionController::class,'listarDetalleTransformacion']);
				// Route::get('transferencia/{id}', [OrdenesPendientesController::class,'transferencia');
				Route::get('obtenerGuia/{id}', [OrdenesPendientesController::class,'obtenerGuia']);
				Route::post('guardar_doc_compra', [OrdenesPendientesController::class,'guardar_doc_compra']);
				Route::get('documentos_ver/{id}', [OrdenesPendientesController::class,'documentos_ver']);

				Route::get('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::post('guardar_producto', [ProductoController::class,'guardar_producto'])->name('guardar-producto');

				Route::get('mostrar_series/{id}', [OrdenesPendientesController::class,'mostrar_series']);
				Route::post('guardar_series', [OrdenesPendientesController::class,'guardar_series'])->name('guardar-series');
				Route::post('actualizar_series', [OrdenesPendientesController::class,'actualizar_series'])->name('actualizar-series');
				Route::post('cambio_serie_numero', [OrdenesPendientesController::class,'cambio_serie_numero'])->name('cambio-series');

				Route::get('verGuiaCompraTransferencia/{id}', [TransferenciaController::class,'verGuiaCompraTransferencia']);
				Route::get('transferencia/{id}', [OrdenesPendientesController::class,'transferencia']);
				Route::post('obtenerGuiaSeleccionadas', [OrdenesPendientesController::class,'obtenerGuiaSeleccionadas']);
				Route::get('anular_doc_com/{id}', [OrdenesPendientesController::class,'anular_doc_com']);

				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class,'obtenerArchivosOc'])->name('obtener-archivos-oc');

				Route::post('listarProductosSugeridos', [ProductoController::class,'listarProductosSugeridos']);
				Route::get('mostrar_prods_sugeridos/{part}/{desc}', [ProductoController::class,'mostrar_prods_sugeridos']);
				Route::get('mostrar_categorias_tipo/{id}', [SubCategoriaController::class,'mostrarSubCategoriasPorCategoria']);
				Route::get('mostrar_tipos_clasificacion/{id}', [CategoriaController::class,'mostrarCategoriasPorClasificacion']);

				Route::get('sedesPorUsuario', [OrdenesPendientesController::class,'sedesPorUsuario']);
				Route::post('actualizarFiltrosPendientes', [OrdenesPendientesController::class,'actualizarFiltrosPendientes']);

				Route::post('ordenesPendientesExcel', [OrdenesPendientesController::class,'ordenesPendientesExcel'])->name('ordenesPendientesExcel');
				Route::post('ingresosProcesadosExcel', [OrdenesPendientesController::class,'ingresosProcesadosExcel'])->name('ingresosProcesadosExcel');
				Route::get('seriesExcel/{id}', [OrdenesPendientesController::class,'seriesExcel']);
				Route::post('actualizarIngreso', [OrdenesPendientesController::class,'actualizarIngreso']);

				Route::get('sedesPorUsuarioArray', [OrdenesPendientesController::class,'sedesPorUsuarioArray']);
				Route::get('getTipoCambioVenta/{fec}', [TransformacionController::class,'getTipoCambioVenta']);
				Route::get('pruebaOrdenesPendientesLista', [OrdenesPendientesController::class,'pruebaOrdenesPendientesLista']);

				Route::get('listarDevolucionesRevisadas', [DevolucionController::class,'listarDevolucionesRevisadas']);
				Route::get('listarDetalleDevolucion/{id}', [DevolucionController::class,'listarDetalleDevolucion']);
				Route::get('verFichasTecnicasAdjuntas/{id}', [DevolucionController::class,'verFichasTecnicasAdjuntas'])->name('ver-fichas-tecnicas');
			});

			Route::group(['as' => 'pendientes-salida.', 'prefix' => 'pendientes-salida'], function () {
				//Pendientes de Salida
				Route::get('index', [SalidasPendientesController::class,'view_despachosPendientes'])->name('index');
				Route::post('listarOrdenesDespachoPendientes', [SalidasPendientesController::class,'listarOrdenesDespachoPendientes']);
				Route::post('guardarSalidaGuiaDespacho', [SalidasPendientesController::class,'guardarSalidaGuiaDespacho']);
				Route::post('listarSalidasDespacho', [SalidasPendientesController::class,'listarSalidasDespacho']);
				Route::post('anular_salida', [SalidasPendientesController::class,'anular_salida']);
				Route::post('cambio_serie_numero', [SalidasPendientesController::class,'cambio_serie_numero']);
				Route::get('verDetalleDespacho/{id}/{od}/{ac}/{tra}', [SalidasPendientesController::class,'verDetalleDespacho']);
				Route::get('marcar_despachado/{id}/{tra}', [SalidasPendientesController::class,'marcar_despachado']);
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class,'imprimir_salida']);
				// Route::get('anular_orden_despacho/{id}', [SalidasPendientesController::class,'anular_orden_despacho');
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class,'listarSeriesGuiaVen']);
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class,'verDetalleRequerimientoDI']);
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class,'obtenerArchivosOc'])->name('obtener-archivos-oc');

				Route::post('actualizarSalida', [SalidasPendientesController::class,'actualizarSalida'])->name('actualizar-salida');
				Route::get('detalleMovimientoSalida/{id}', [SalidasPendientesController::class,'detalleMovimientoSalida']);
				Route::get('guia-salida-excel/{idGuia}', [SalidasPendientesController::class,'guiaSalidaExcel']);
				Route::get('guia-salida-excel-formato-okc', [GuiaSalidaExcelFormatoOKCController::class,'construirExcel']);
				Route::get('guia-salida-excel-formato-svs', [GuiaSalidaExcelFormatoSVSController::class,'construirExcel']);

				Route::get('validaStockDisponible/{id}/{alm}', [SalidasPendientesController::class,'validaStockDisponible']);

				Route::get('seriesVentaExcel/{id}', [SalidasPendientesController::class,'seriesVentaExcel']);
				Route::post('salidasPendientesExcel', [SalidasPendientesController::class,'salidasPendientesExcel'])->name('salidasPendientesExcel');
				Route::post('salidasProcesadasExcel', [SalidasPendientesController::class,'salidasProcesadasExcel'])->name('salidasProcesadasExcel');

				Route::get('actualizaItemsODE/{id}', [SalidasPendientesController::class,'actualizaItemsODE']);
				Route::get('actualizaItemsODI/{id}', [SalidasPendientesController::class,'actualizaItemsODI']);
				Route::get('atencion-ver-adjuntos', [SalidasPendientesController::class,'verAdjuntos']);
				Route::get('mostrarClientes', [SalidasPendientesController::class,'mostrarClientes'])->name('mostrarClientes');
				Route::post('guardarCliente', [SalidasPendientesController::class,'guardarCliente'])->name('guardarCliente');

				Route::get('listarDevolucionesSalidas', [DevolucionController::class,'listarDevolucionesSalidas']);
				Route::get('verDetalleDevolucion/{id}', [SalidasPendientesController::class,'verDetalleDevolucion']);
			});

			Route::group(['as' => 'customizacion.', 'prefix' => 'customizacion'], function () {
				//Pendientes de Salida
				Route::get('index', [CustomizacionController::class,'viewCustomizacion'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::post('listarProductosAlmacen', [SaldoProductoController::class,'listarProductosAlmacen']);
				Route::post('guardar_materia', [TransformacionController::class,'guardar_materia']);
				Route::post('guardarCustomizacion', [CustomizacionController::class,'guardarCustomizacion']);
				Route::post('actualizarCustomizacion', [CustomizacionController::class,'actualizarCustomizacion']);
				Route::get('anularCustomizacion/{id}', [CustomizacionController::class,'anularCustomizacion']);
				Route::get('listar_transformaciones/{tp}', [TransformacionController::class,'listar_transformaciones']);
				Route::get('mostrarCustomizacion/{id}', [CustomizacionController::class,'mostrarCustomizacion']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class,'imprimir_transformacion']);
				Route::post('actualizarCostosBase', [CustomizacionController::class,'actualizarCostosBase']);
				Route::get('procesarCustomizacion/{id}', [CustomizacionController::class,'procesarCustomizacion']);
				Route::get('obtenerTipoCambio/{fec}/{mon}', [CustomizacionController::class,'obtenerTipoCambio']);
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class,'listarSeriesGuiaVen']);
				Route::get('validarEdicion/{id}', [CustomizacionController::class,'validarEdicion']);
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class,'imprimir_ingreso']);
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class,'imprimir_salida']);
			});

			Route::group(['as' => 'devolucion.', 'prefix' => 'devolucion'], function () {
				//Devoluciones
				Route::get('index', [DevolucionController::class,'viewDevolucion'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::get('listarDevoluciones', [DevolucionController::class,'listarDevoluciones']);
				Route::post('mostrarContribuyentes', [DevolucionController::class,'mostrarContribuyentes']);
				Route::get('mostrarDevolucion/{id}', [DevolucionController::class,'mostrarDevolucion']);
				Route::post('guardarDevolucion', [DevolucionController::class,'guardarDevolucion']);
				Route::post('actualizarDevolucion', [DevolucionController::class,'actualizarDevolucion']);
				Route::get('validarEdicion/{id}', [DevolucionController::class,'validarEdicion']);
				Route::get('anularDevolucion/{id}', [DevolucionController::class,'anularDevolucion']);
				Route::get('listarSalidasVenta/{alm}/{id}', [DevolucionController::class,'listarSalidasVenta']);
				Route::get('listarIngresos/{alm}/{id}', [DevolucionController::class,'listarIngresos']);
				Route::get('obtenerMovimientoDetalle/{id}', [DevolucionController::class,'obtenerMovimientoDetalle']);
				Route::get('listarIncidencias', [IncidenciaController::class,'listarIncidencias']);
			});

			Route::group(['as' => 'prorrateo.', 'prefix' => 'prorrateo'], function () {
				//Pendientes de Salida
				Route::get('index', [ProrrateoCostosController::class,'view_prorrateo_costos'])->name('index');
				Route::get('mostrar_prorrateos', [ProrrateoCostosController::class,'mostrar_prorrateos']);
				Route::get('mostrar_prorrateo/{id}', [ProrrateoCostosController::class,'mostrar_prorrateo']);
				Route::get('mostrar_proveedores', [LogisticaController::class,'mostrar_proveedores']);
				Route::get('guardar_tipo_prorrateo/{nombre}', [ProrrateoCostosController::class,'guardar_tipo_prorrateo']);
				Route::get('obtenerTipoCambio/{fec}/{mon}', [CustomizacionController::class,'obtenerTipoCambio']);
				Route::get('listar_guias_compra', [ProrrateoCostosController::class,'listar_guias_compra']);
				Route::get('listar_docs_prorrateo/{id}', [ProrrateoCostosController::class,'listar_docs_prorrateo']);
				Route::get('listar_guia_detalle/{id}', [ProrrateoCostosController::class,'listar_guia_detalle']);
				Route::post('guardarProrrateo', [ProrrateoCostosController::class,'guardarProrrateo']);
				Route::post('updateProrrateo', [ProrrateoCostosController::class,'updateProrrateo']);
				Route::get('anular_prorrateo/{id}', [ProrrateoCostosController::class,'anular_prorrateo']);
				Route::post('guardarProveedor', [ProrrateoCostosController::class,'guardarProveedor']);
			});

			Route::group(['as' => 'reservas.', 'prefix' => 'reservas'], function () {
				//Pendientes de Salida
				Route::get('index', [ReservasAlmacenController::class,'viewReservasAlmacen'])->name('index');
				Route::post('listarReservasAlmacen', [ReservasAlmacenController::class,'listarReservasAlmacen'])->name('listarReservasAlmacen');
				Route::post('anularReserva', [ReservasAlmacenController::class,'anularReserva']);
				Route::post('actualizarReserva', [ReservasAlmacenController::class,'actualizarReserva']);
				Route::get('actualizarReservas', [ReservasAlmacenController::class,'actualizarReservas']);
				Route::post('actualizarEstadoReserva', [ReservasAlmacenController::class,'actualizarEstadoReserva']);
			});

			Route::group(['as' => 'requerimientos-almacen.', 'prefix' => 'requerimientos-almacen'], function () {
				//Pendientes de Salida
				Route::get('index', [ListaRequerimientosAlmacenController::class,'viewRequerimientosAlmacen'])->name('index');
				Route::post('listarRequerimientosAlmacen', [ListaRequerimientosAlmacenController::class,'listarRequerimientosAlmacen'])->name('listarRequerimientosAlmacen');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class,'verDetalleRequerimientoDI']);
				Route::get('listarDetalleTransferencias/{id}', [TransferenciaController::class,'listarDetalleTransferencias']);
				Route::post('cambioAlmacen', [ListaRequerimientosAlmacenController::class,'cambioAlmacen']);
				Route::get('listarDetalleRequerimiento/{id}', [ListaRequerimientosAlmacenController::class,'listarDetalleRequerimiento']);
				Route::post('anularDespachoInterno', [OrdenesDespachoInternoController::class,'anularDespachoInterno'])->name('anularDespachoInterno');
				Route::post('guardar-ajuste-transformacion-requerimiento', [ComprasPendientesController::class,'guardarAjusteTransformacionRequerimiento'])->name('guardar-ajuste-transformacion-requerimiento');
				Route::get('mostrar-requerimiento/{idRequerimiento?}', [RequerimientoController::class,'requerimiento']);
				Route::get('detalle-requerimiento/{idRequerimiento?}', [RequerimientoController::class,'detalleRequerimiento'])->name('detalle-requerimientos');
			});
		});

		Route::group(['as' => 'comprobantes.', 'prefix' => 'comprobantes'], function () {
			Route::get('mostrar_proveedores', [LogisticaController::class,'mostrar_proveedores']);
			Route::get('listar_guias_proveedor/{id?}', [AlmacenController::class,'listar_guias_proveedor']);
			Route::get('listar_detalle_guia_compra/{id?}', [ComprobanteCompraController::class,'listar_detalle_guia_compra']);
			Route::get('tipo_cambio_compra/{fecha}', [AlmacenController::class,'tipo_cambio_compra']);
			Route::post('guardar_doc_compra', [ComprobanteCompraController::class,'guardar_doc_compra']);
			// Route::get('listar_guias_prov/{id?}', [ComprobanteCompraController::class,'listar_guias_prov');
			Route::post('listar_docs_compra', [ComprobanteCompraController::class,'listar_docs_compra']);

			Route::get('lista_comprobante_compra', [ComprobanteCompraController::class,'view_lista_comprobantes_compra'])->name('lista_comprobante_compra');
			Route::get('documentoAPago/{id}', [ComprobanteCompraController::class,'documentoAPago']);
			Route::get('enviarComprobanteSoftlink/{id}', [MigrateFacturasSoftlinkController::class,'enviarComprobanteSoftlink']);
			Route::get('documentos_ver/{id}', [OrdenesPendientesController::class,'documentos_ver']);
			Route::get('actualizarSedesFaltantes', [MigrateFacturasSoftlinkController::class,'actualizarSedesFaltantes']);
			Route::get('actualizarProveedorComprobantes', [MigrateFacturasSoftlinkController::class,'actualizarProveedorComprobantes']);
			Route::get('migrarComprobantesSoftlink', [MigrateFacturasSoftlinkController::class,'migrarComprobantesSoftlink']);
			Route::get('migrarItemsComprobantesSoftlink', [MigrateFacturasSoftlinkController::class,'migrarItemsComprobantesSoftlink']);

			Route::get('lista-comprobantes-pago-export-excel', [ComprobanteCompraController::class,'exportListaComprobantesPagos'])->name('lista.comprobante.pago.export.excel');
		});

		Route::group(['as' => 'transferencias.', 'prefix' => 'transferencias'], function () {
			Route::group(['as' => 'gestion-transferencias.', 'prefix' => 'gestion-transferencias'], function () {
				//Transferencias
				Route::get('index', [TransferenciaController::class,'view_listar_transferencias'])->name('index');
				Route::post('listarRequerimientos', [TransferenciaController::class,'listarRequerimientos']);
				Route::get('listarTransferenciaDetalle/{id}', [TransferenciaController::class,'listarTransferenciaDetalle']);
				Route::post('guardarIngresoTransferencia', [TransferenciaController::class,'guardarIngresoTransferencia']);
				Route::post('guardarSalidaTransferencia', [TransferenciaController::class,'guardarSalidaTransferencia']);
				Route::post('anularTransferenciaIngreso', [TransferenciaController::class,'anularTransferenciaIngreso']);
				Route::get('ingreso_transferencia/{id}', [TransferenciaController::class,'ingreso_transferencia']);
				// Route::get('transferencia_nextId/{id}', [TransferenciaController::class,'transferencia_nextId');
				Route::post('anularTransferenciaSalida', [TransferenciaController::class,'anularTransferenciaSalida']);
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class,'imprimir_ingreso']);
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class,'imprimir_salida']);
				Route::post('listarTransferenciasPorEnviar', [TransferenciaController::class,'listarTransferenciasPorEnviar']);
				Route::post('listarTransferenciasPorRecibir', [TransferenciaController::class,'listarTransferenciasPorRecibir']);
				Route::post('listarTransferenciasRecibidas', [TransferenciaController::class,'listarTransferenciasRecibidas']);
				// Route::get('cargar_almacenes/{id}', [UbicacionAlmacenController::class,'@cargar_almacenes');
				Route::post('listarDetalleTransferencia', [TransferenciaController::class,'listarDetalleTransferencia']);
				// Route::get('listarDetalleTransferencia/{id}', [TransferenciaController::class,'listarDetalleTransferencia');
				// Route::post('listarDetalleTransferenciasSeleccionadas', [TransferenciaController::class,'listarDetalleTransferenciasSeleccionadas');
				Route::get('listarGuiaTransferenciaDetalle/{id}', [TransferenciaController::class,'listarGuiaTransferenciaDetalle']);
				Route::get('listarSeries/{id}', [TransferenciaController::class,'listarSeries']);
				Route::get('listarSeriesVen/{id}', [TransferenciaController::class,'listarSeriesVen']);
				Route::get('anular_transferencia/{id}', [TransferenciaController::class,'anular_transferencia']);
				// Route::get('listar_guias_compra', [TransferenciaController::class,'listar_guias_compra');
				Route::get('transferencia/{id}', [OrdenesPendientesController::class,'transferencia']);
				Route::get('verGuiaCompraTransferencia/{id}', [TransferenciaController::class,'verGuiaCompraTransferencia']);

				Route::get('verRequerimiento/{id}', [TransferenciaController::class,'verRequerimiento']);
				Route::post('generarTransferenciaRequerimiento', [TransferenciaController::class,'generarTransferenciaRequerimiento']);
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class,'listarSeriesGuiaVen']);
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class,'obtenerArchivosOc'])->name('obtener-archivos-oc');
				Route::get('mostrarTransportistas', [DistribucionController::class,'mostrarTransportistas']);

				Route::get('autogenerarDocumentosCompra/{id}/{tr}', [VentasInternasController::class,'autogenerarDocumentosCompra'])->name('autogenerarDocumentosCompra');
				Route::get('verDocumentosAutogenerados/{id}', [VentasInternasController::class,'verDocumentosAutogenerados']);
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class,'verDetalleRequerimientoDI']);
				Route::get('almacenesPorUsuario', [TransferenciaController::class,'almacenesPorUsuario']);

				Route::post('listarProductosAlmacen', [SaldoProductoController::class,'listarProductosAlmacen']);
				Route::post('nuevaTransferencia', [TransferenciaController::class,'nuevaTransferencia']);
				Route::get('pruebaSaldos', [SaldoProductoController::class,'pruebaSaldos']);

				Route::get('getAlmacenesPorEmpresa/{id}', [TransferenciaController::class,'getAlmacenesPorEmpresa']);
				Route::get('imprimir_transferencia/{id}', [TransferenciaController::class,'imprimir_transferencia']);

				Route::post('actualizarCostosVentasInternas', [VentasInternasController::class,'actualizarCostosVentasInternas']);
				Route::post('actualizarValorizacionesIngresos', [VentasInternasController::class,'actualizarValorizacionesIngresos']);
			});
		});

		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {

			Route::group(['as' => 'saldos.', 'prefix' => 'saldos'], function () {

				Route::get('tipo_cambio_compra/{fecha}', [SaldosController::class,'tipo_cambio_compra']);

				Route::get('index', [SaldosController::class,'view_saldos'])->name('index');
				Route::post('filtrar', [SaldosController::class,'filtrar'])->name('filtrar');
				Route::post('listar', [SaldosController::class,'listar'])->name('listar');
				Route::get('verRequerimientosReservados/{id}/{alm}', [SaldosController::class,'verRequerimientosReservados']);
				Route::get('exportar', [SaldosController::class,'exportar'])->name('exportar');
				Route::get('exportarSeries', [SaldosController::class,'exportarSeries'])->name('exportarSeries');
				Route::get('exportarAntiguedades', [SaldosController::class,'exportarAntiguedades'])->name('exportarAntiguedades');
				Route::post('exportar-valorizacion', [SaldosController::class,'valorizacion'])->name('exportar-valorizacion');
				Route::get('actualizarFechasIngresoSoft/{id}', [MigrateProductoSoftlinkController::class,'actualizarFechasIngresoSoft'])->name('actualizarFechasIngresoSoft');
				Route::get('actualizarFechasIngresoAgile/{id}', [MigrateProductoSoftlinkController::class,'actualizarFechasIngresoAgile'])->name('actualizarFechasIngresoSoft');
			});

			Route::group(['as' => 'lista-ingresos.', 'prefix' => 'lista-ingresos'], function () {

				Route::get('index', [AlmacenController::class,'view_ingresos'])->name('index');
				Route::get('listar_ingresos/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}/{tra}', [AlmacenController::class,'listar_ingresos_lista']);
				Route::get('update_revisado/{id}/{rev}/{obs}', [AlmacenController::class,'update_revisado']);

				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class,'select_almacenes_empresa']);
				Route::get('mostrar_proveedores', [LogisticaController::class,'mostrar_proveedores']);
				Route::get('listar_transportistas_com', [AlmacenController::class,'listar_transportistas_com']);
				Route::get('listar_transportistas_ven', [AlmacenController::class,'listar_transportistas_ven']);

				Route::get('listar-ingresos-excel/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}/{tra}', [AlmacenController::class,'ExportarExcelListaIngresos']);
				// reportes con modelos
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class,'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				Route::post('listar-ingresos', 'Almacen\Reporte\ListaIngresosController@listarIngresos');
			});

			Route::group(['as' => 'lista-salidas.', 'prefix' => 'lista-salidas'], function () {

				Route::get('index', [AlmacenController::class,'view_salidas'])->name('index');
				Route::get('listar_salidas/{alm}/{docs}/{cond}/{fini}/{ffin}/{cli}/{usu}/{mon}/{ref}', [AlmacenController::class,'listar_salidas']);
				Route::get('update_revisado/{id}/{rev}/{obs}', [AlmacenController::class,'update_revisado']);

				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class,'select_almacenes_empresa']);
				Route::get('mostrar_clientes', [ClienteController::class,'mostrar_clientes']);
				Route::get('mostrar_clientes_empresa', [ClienteController::class,'mostrar_clientes_empresa']);
				Route::get('listar_transportistas_com', [AlmacenController::class,'listar_transportistas_com']);
				Route::get('listar_transportistas_ven', [AlmacenController::class,'listar_transportistas_ven']);

				Route::get('listar-salidas-excel/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}', [AlmacenController::class,'ExportarExcelListaSalidas']);
				// reportes con modelos
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class,'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				Route::post('listar-salidas', [ListaSalidasController::class,'listarSalidas']);
			});

			Route::group(['as' => 'detalle-ingresos.', 'prefix' => 'detalle-ingresos'], function () {

				Route::get('index', [AlmacenController::class,'view_busqueda_ingresos'])->name('index');
				Route::get('listar_busqueda_ingresos/{alm}/{tp}/{des}/{doc}/{fini}/{ffin}', [AlmacenController::class,'listar_busqueda_ingresos']);
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class,'select_almacenes_empresa']);
				Route::get('imprimir_ingreso/{id}', [OrdenesPendientesController::class,'imprimir_ingreso']);
				Route::get('imprimir_guia_ingreso/{id}', [AlmacenController::class,'imprimir_guia_ingreso']);
			});

			Route::group(['as' => 'detalle-salidas.', 'prefix' => 'detalle-salidas'], function () {

				Route::get('index', [AlmacenController::class,'view_busqueda_salidas'])->name('index');
				Route::get('listar_busqueda_salidas/{alm}/{tp}/{des}/{doc}/{fini}/{ffin}', [AlmacenController::class,'listar_busqueda_salidas']);
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class,'select_almacenes_empresa']);
				Route::get('imprimir_salida/{id}', [AlmacenController::class,'imprimir_salida']);
			});

			Route::group(['as' => 'kardex-general.', 'prefix' => 'kardex-general'], function () {

				Route::get('index', [AlmacenController::class,'view_kardex_general'])->name('index');
				Route::get('kardex_general/{id}/{fini}/{ffin}', [AlmacenController::class,'kardex_general']);
				Route::get('kardex_sunat/{id}/{fini}/{ffin}', [AlmacenController::class,'download_kardex_sunat']);
				// Route::get('kardex_sunatx/{id}', [AlmacenController::class,'kardex_sunat');
				Route::get('exportar_kardex_general/{id}/{fini}/{ffin}', [ReportesController::class,'exportarKardex']);
			});

			Route::group(['as' => 'kardex-productos.', 'prefix' => 'kardex-productos'], function () {

				Route::get('index', [AlmacenController::class,'view_kardex_detallado'])->name('index');
				Route::get('kardex_producto/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class,'kardex_producto']);
				Route::get('listar_kardex_producto/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class,'kardex_producto']);
				Route::get('kardex_detallado/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class,'download_kardex_producto']);
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class,'select_almacenes_empresa']);
				Route::get('datos_producto/{id}', [KardexSerieController::class,'datos_producto']);
				Route::post('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class,'mostrar_prods_almacen']);
			});

			Route::group(['as' => 'kardex-series.', 'prefix' => 'kardex-series'], function () {

				Route::get('index', [KardexSerieController::class,'view_kardex_series'])->name('index');
				Route::get('listar_serie_productos/{serie}/{des}/{cod}/{part}', [KardexSerieController::class,'listar_serie_productos']);
				Route::get('listar_kardex_serie/{serie}/{id_prod}', [KardexSerieController::class,'listar_kardex_serie']);
				Route::get('datos_producto/{id}', [KardexSerieController::class,'datos_producto']);
				Route::get('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class,'mostrar_prods_almacen']);
			});

			Route::group(['as' => 'documentos-prorrateo.', 'prefix' => 'documentos-prorrateo'], function () {

				Route::get('index', [AlmacenController::class,'view_docs_prorrateo'])->name('index');
				Route::get('listar_documentos_prorrateo', [AlmacenController::class,'listar_documentos_prorrateo']);
			});

			Route::group(['as' => 'stock-series.', 'prefix' => 'stock-serie'], function () {

				Route::get('index', [AlmacenController::class,'view_stock_series'])->name('index');
				Route::post('listar_stock_series', [AlmacenController::class,'listar_stock_series']);
				Route::get('prueba_exportar_excel', [AlmacenController::class,'obtener_data_stock_series']);
				Route::get('exportar_excel', [AlmacenController::class,'exportar_stock_series_excel']);
			});
		});

		Route::group(['as' => 'variables.', 'prefix' => 'variables'], function () {

			Route::group(['as' => 'series-numeros.', 'prefix' => 'series-numeros'], function () {

				Route::get('index', [AlmacenController::class,'view_serie_numero'])->name('index');
				Route::get('listar_series_numeros', [AlmacenController::class,'listar_series_numeros']);
				Route::get('mostrar_serie_numero/{id}', [AlmacenController::class,'mostrar_serie_numero']);
				Route::post('guardar_serie_numero', [AlmacenController::class,'guardar_serie_numero']);
				Route::post('actualizar_serie_numero', [AlmacenController::class,'update_serie_numero']);
				Route::get('anular_serie_numero/{id}', [AlmacenController::class,'anular_serie_numero']);
				Route::get('series_numeros/{desde}/{hasta}/{num}/{serie}', [AlmacenController::class,'series_numeros']);
			});

			Route::group(['as' => 'tipos-movimiento.', 'prefix' => 'tipos-movimiento'], function () {

				Route::get('index', [AlmacenController::class,'view_tipo_movimiento'])->name('index');
				Route::get('listar_tipoMov', [AlmacenController::class,'mostrar_tipos_mov']);
				Route::get('mostrar_tipoMov/{id}', [AlmacenController::class,'mostrar_tipo_mov']);
				Route::post('guardar_tipoMov', [AlmacenController::class,'guardar_tipo_mov']);
				Route::post('actualizar_tipoMov', [AlmacenController::class,'update_tipo_mov']);
				Route::get('anular_tipoMov/{id}', [AlmacenController::class,'anular_tipo_mov']);
			});

			Route::group(['as' => 'tipos-documento.', 'prefix' => 'tipos-documento'], function () {

				Route::get('index', [AlmacenController::class,'view_tipo_doc_almacen'])->name('index');
				Route::get('listar_tp_docs', [AlmacenController::class,'listar_tp_docs']);
				Route::get('mostrar_tp_doc/{id}', [AlmacenController::class,'mostrar_tp_doc']);
				Route::post('guardar_tp_doc', [AlmacenController::class,'guardar_tp_doc']);
				Route::post('update_tp_doc', [AlmacenController::class,'update_tp_doc']);
				Route::get('anular_tp_doc/{id}', [AlmacenController::class,'anular_tp_doc']);
			});

			Route::group(['as' => 'unidades-medida.', 'prefix' => 'unidades-medida'], function () {

				Route::get('index', [AlmacenController::class,'view_unid_med'])->name('index');
				Route::get('listar_unidmed', [AlmacenController::class,'mostrar_unidades_med']);
				Route::get('mostrar_unidmed/{id}', [AlmacenController::class,'mostrar_unid_med']);
				Route::post('guardar_unidmed', [AlmacenController::class,'guardar_unid_med']);
				Route::post('actualizar_unidmed', [AlmacenController::class,'update_unid_med']);
				Route::get('anular_unidmed/{id}', [AlmacenController::class,'anular_unid_med']);
			});
		});
	});
});



