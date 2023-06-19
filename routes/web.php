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
use App\Http\Controllers\Almacen\Reporte\ListaIngresosController;
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
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Finanzas\CentroCosto\CentroCostoController;
use App\Http\Controllers\Finanzas\Normalizar\NormalizarController;
use App\Http\Controllers\Finanzas\Presupuesto\PartidaController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoInternoController;
use App\Http\Controllers\Finanzas\Presupuesto\ScriptController;
use App\Http\Controllers\Finanzas\Presupuesto\TituloController;
use App\Http\Controllers\Finanzas\Reportes\ReporteGastoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Logistica\Distribucion\DistribucionController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoExternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoInternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesTransformacionController;
use App\Http\Controllers\Logistica\RequerimientoController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\Migraciones\MigrateFacturasSoftlinkController;
use App\Http\Controllers\NecesidadesController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\RequerimientoController as ControllersRequerimientoController;
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
	Route::group(['as' => 'configuracion.', 'prefix' => 'configuracion'], function () {
		Route::get('dashboard', [ConfiguracionController::class, 'view_main_configuracion'])->name('dashboard');
		// Route::post('validar-documento', [ConfiguracionController::class, 'validarDocumento'])->name('validar-documento');
		// Route::post('validar-usuario', [ConfiguracionController::class, 'validarUsuario'])->name('validar-usuario');

		// Route::post('usuarios/asignar/modulos', [ConfiguracionController::class, 'asiganrModulos'])->name('');
		// Route::get('listar_trabajadores', 'ProyectosController@listar_trabajadores')->name('listar_trabajadores');
		// Route::get('anular_usuario/{id}', [ConfiguracionController::class, 'anular_usuario'])->name('anular_usuario');
		// Route::get('lista-roles-usuario/{id}', [ConfiguracionController::class, 'lista_roles_usuario'])->name('lista-roles-usuario');
		// Route::get('arbol-acceso/{id_rol}', [ConfiguracionController::class, 'arbol_modulos'])->name('arbol-acceso');
		// Route::put('actualizar-accesos-usuario', [ConfiguracionController::class, 'actualizar_accesos_usuario'])->name('actualizar-accesos-usuario');

		// Route::get('usuarios/asignar', [ConfiguracionController::class, 'usuarioAsignar']);
		// Route::get('modulos', [ConfiguracionController::class, 'getModulos']);

		// Route::group(['as' => 'accesos.', 'prefix' => 'accesos'], function () {
			// 	Route::post('guardar-accesos', [ConfiguracionController::class, 'guardarAccesos']);
			// });

		Route::group(['as' => 'usuario.', 'prefix' => 'usuario'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_usuario'])->name('index');
			Route::get('listar_usuarios', [ConfiguracionController::class, 'mostrar_usuarios'])->name('listar_usuarios');
			Route::post('cambiar-clave', [ConfiguracionController::class, 'cambiarClave'])->name('cambiar-clave');
			Route::post('guardar_usuarios', [ConfiguracionController::class, 'guardar_usuarios'])->name('guardar_usuarios');
			Route::post('actualizar_usuario', [ConfiguracionController::class, 'savePerfil'])->name('actualizar_usuario');
			Route::get('decodificar-clave/{id}', [ConfiguracionController::class, 'getPasswordUserDecode'])->name('decodificar-clave');
			Route::get('perfil/{id}', [ConfiguracionController::class, 'getPerfil'])->name('perfil');

			Route::group(['as' => 'accesos.', 'prefix' => 'accesos'], function () {
				Route::get('ver/{id}', [ConfiguracionController::class, 'viewAccesos'])->name('ver');
				Route::get('datos-usuario/{id}', [ConfiguracionController::class, 'accesoUsuario'])->name('datos-usuario');
				Route::post('modulos', [ConfiguracionController::class, 'getModulosAccion'])->name('modulos');
			});
		});

		// Route::group(['as' => 'script.', 'prefix' => 'script'], function () {
		// 	Route::get('prueba', [ConfiguracionController::class, 'prueba']);
		// 	Route::get('scripts/{var}', [ConfiguracionController::class, 'scripts']);
		// 	Route::get('scripts-usuario', [ConfiguracionController::class, 'scriptsAccesos']);

		// });
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
                Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
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
                Route::put('eliminar-archivo-adjunto-detalle-requerimiento/{id_archivo}', [LogisticaController::class, 'eliminar_archivo_adjunto_detalle_requerimiento'])->name('eliminar-archivo-adjunto-detalle-requerimiento');
                Route::post('guardar-archivos-adjuntos-requerimiento', [LogisticaController::class,'guardar_archivos_adjuntos_requerimiento'])->name('guardar-archivos-adjuntos-requerimiento');
                Route::put('eliminar-archivo-adjunto-requerimiento/{id_archivo}', [LogisticaController::class,'eliminar_archivo_adjunto_requerimiento'])->name('eliminar-archivo-adjunto-requerimiento');
                Route::get('mostrar-archivos-adjuntos-requerimiento/{id_requerimiento?}/{categoria?}', [RequerimientoController::class,'mostrarArchivosAdjuntosRequerimiento'])->name('mostrar-archivos-adjuntos-requerimiento');
                Route::get('listar_almacenes', [AlmacenController::class,'mostrar_almacenes'])->name('listar-almacenes');
                Route::get('mostrar-sede', [ConfiguracionController::class,'mostrarSede'])->name('mostrar-sede');
                Route::get('mostrar_proveedores', [LogisticaController::class,'mostrar_proveedores'])->name('mostrar-proveedores');
                Route::post('guardar_proveedor', [LogisticaController::class,'guardar_proveedor'])->name('guardar-proveedor');
                Route::get('getCodigoRequerimiento/{id}', [LogisticaController::class,'getCodigoRequerimiento'])->name('getCodigoRequerimiento');
                Route::get('mostrar-archivos-adjuntos/{id_detalle_requerimiento}', [RequerimientoController::class,'mostrarArchivosAdjuntos'])->name('mostrar-archivos-adjuntos');
                Route::post('save_cliente', [LogisticaController::class,'save_cliente'])->name('save-cliente');
                Route::get('listar_saldos', [AlmacenController::class, 'listar_saldos'])->name('listar-saldos');
                Route::get('listar_opciones', [ProyectosController::class, 'listar_opciones'])->name('listar-opciones');
                Route::get('listar-saldos-por-almacen', [AlmacenController::class, 'listar_saldos_por_almacen'])->name('listar-saldos-por-almacen');
                Route::get('listar-saldos-por-almacen/{id_producto}', [AlmacenController::class, 'listar_saldos_por_almacen_producto'])->name('listar-saldos-por-almacen');
                Route::get('obtener-promociones/{id_producto}/{id_almacen}', [LogisticaController::class, 'obtener_promociones'])->name('obtener-promociones');
                Route::get('migrar_venta_directa/{id}', [MigrateRequerimientoSoftLinkController::class, 'migrar_venta_directa'])->name('migrar-venta-directa');
                Route::post('guardar-producto', [AlmacenController::class, 'guardar_producto'])->name('guardar-producto');
                Route::get('cuadro-costos/{id_cc?}', [RequerimientoController::class, 'cuadro_costos'])->name('cuadro-costos');
                Route::get('detalle-cuadro-costos/{id_cc?}', [RequerimientoController::class, 'detalle_cuadro_costos'])->name('detalle-cuadro-costos');
                Route::post('obtener-construir-cliente', [RequerimientoController::class, 'obtenerConstruirCliente'])->name('obtener-construir-cliente');
                Route::get('proyectos-activos', [ProyectosController::class, 'listar_proyectos_activos'])->name('proyectos-activos');
                Route::get('grupo-select-item-para-compra', [ComprasPendientesController::class, 'getGrupoSelectItemParaCompra'])->name('grupo-select-item-para-compra');
                Route::get('mostrar-fuente', [LogisticaController::class, 'mostrarFuente'])->name('mostrar-fuente');
                Route::post('guardar-fuente', [LogisticaController::class, 'guardarFuente'])->name('guardar-fuente');
                Route::post('anular-fuente', [LogisticaController::class, 'anularFuente'])->name('anular-fuente');
                Route::post('actualizar-fuente', [LogisticaController::class, 'actualizarFuente'])->name('actualizar-fuente');
                Route::post('guardar-detalle-fuente', [LogisticaController::class, 'guardarDetalleFuente'])->name('guardar-detalle-fuente');
                Route::get('mostrar-fuente-detalle/{fuente_id?}', [LogisticaController::class, 'mostrarFuenteDetalle'])->name('mostrar-fuente-detalle');
                Route::post('anular-detalle-fuente', [LogisticaController::class, 'anularDetalleFuente'])->name('anular-detalle-fuente');
                Route::post('actualizar-detalle-fuente', [LogisticaController::class, 'actualizarDetalleFuente'])->name('actualizar-detalle-fuente');
                Route::get('buscar-stock-almacenes/{id_item?}', [RequerimientoController::class, 'buscarStockEnAlmacenes'])->name('buscar-stock-almacenes');
                Route::get('listar_trabajadores', [ProyectosController::class, 'listar_trabajadores'])->name('listar-trabajadores');
                Route::post('lista-cuadro-presupuesto', [RequerimientoPagoController::class, 'listaCuadroPresupuesto'])->name('lista-cuadro-presupuesto');
                Route::post('listarIncidencias', [IncidenciaController::class, 'listarIncidencias'])->name('listar-incidencias');
                Route::get('combo-presupuesto-interno/{idGrupo?}/{idArea?}', [PresupuestoInternoController::class, 'comboPresupuestoInterno'])->name('combo-presupuesto-interno');
                Route::get('obtener-detalle-presupuesto-interno/{idPresupuesto?}', [PresupuestoInternoController::class, 'PresupuestoInternoController'])->name('obtener-detalle-presupuesto-interno');
                Route::get('obtener-lista-proyectos/{idGrupo?}', [RequerimientoController::class, 'obtenerListaProyectos'])->name('obtener-lista-proyectos');
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
		Route::get('script-categoria', [AlmacenController::class, 'scripCategoria'])->name('script-categoria');
		#script 2
		Route::get('script-actualizar-categoria-softlink', [AlmacenController::class, 'scripActualizarCategoriasSoftlink'])->name('script-actualizar-categoria-softlink');

		Route::get('index', [AlmacenController::class, 'view_main_almacen'])->name('index');

		Route::get('getEstadosRequerimientos/{filtro}', [DistribucionController::class, 'getEstadosRequerimientos'])->name('get-estados-requerimiento');
		Route::get('listarEstadosRequerimientos/{id}/{filtro}', [DistribucionController::class, 'listarEstadosRequerimientos'])->name('listar-estados-requerimientos');

		Route::group(['as' => 'catalogos.', 'prefix' => 'catalogos'], function () {
			Route::group(['as' => 'clasificaciones.', 'prefix' => 'clasificaciones'], function () {
				//Clasificacion
				Route::get('index', [ClasificacionController::class, 'view_clasificacion'])->name('index');
				Route::get('listarClasificaciones', [ClasificacionController::class, 'listarClasificaciones'])->name('listarClasificaciones');
				Route::get('mostrarClasificacion/{id}', [ClasificacionController::class, 'mostrarClasificacion'])->name('mostrar-clasifiaccion');
				Route::post('guardarClasificacion', [ClasificacionController::class, 'guardarClasificacion'])->name('guardar-clasificacion');
				Route::post('actualizarClasificacion', [ClasificacionController::class, 'actualizarClasificacion'])->name('actualizar-clasificacion');
				Route::get('anularClasificacion/{id}', [ClasificacionController::class, 'anularClasificacion'])->name('anular-clasificacion');
				Route::get('revisarClasificacion/{id}', [ClasificacionController::class, 'revisarClasificacion'])->name('revisar-clasificacion');
			});

			Route::group(['as' => 'categorias.', 'prefix' => 'categorias'], function () {
				//Categoria
				Route::get('index', [CategoriaController::class, 'view_categoria'])->name('index');
				Route::get('listarCategorias', [CategoriaController::class, 'listarCategorias'])->name('listar-categorias');
				Route::get('mostrarCategoria/{id}', [CategoriaController::class, 'mostrarCategoria'])->name('mostrar-categoria');
				Route::post('guardarCategoria', [CategoriaController::class, 'guardarCategoria'])->name('guardar-categoria');
				Route::post('actualizarCategoria', [CategoriaController::class, 'actualizarCategoria'])->name('actualizar-categria');
				Route::get('anularCategoria/{id}', [CategoriaController::class, 'anularCategoria'])->name('anular-categoria');
				Route::get('revisarCategoria/{id}', [CategoriaController::class, 'revisarCategoria'])->name('revisar-categoria');
			});

			Route::group(['as' => 'sub-categorias.', 'prefix' => 'sub-categorias'], function () {
				//SubCategoria
				Route::get('index', [SubCategoriaController::class, 'view_sub_categoria'])->name('index');
				Route::get('listar_categorias', [SubCategoriaController::class, 'mostrar_categorias'])->name('listar-categorias');
				Route::get('mostrar_categoria/{id}', [SubCategoriaController::class, 'mostrar_categoria'])->name('mostrar-categorias');
				Route::post('guardar_categoria', [SubCategoriaController::class, 'guardar_categoria'])->name('guardar-categoria');
				Route::post('actualizar_categoria', [SubCategoriaController::class, 'update_categoria'])->name('actualizar-categoria');
				Route::get('anular_categoria/{id}', [SubCategoriaController::class, 'anular_categoria'])->name('anular-categoria');
				Route::get('revisarCat/{id}', [SubCategoriaController::class, 'cat_revisar'])->name('revisar-cat');

				Route::get('mostrar_tipos_clasificacion/{id}', [CategoriaController::class, 'mostrarCategoriasPorClasificacion'])->name('mostrar-tipos-clasificacion');
			});

			Route::group(['as' => 'marcas.', 'prefix' => 'marcas'], function () {
				//Marca
				Route::get('index', [MarcaController::class, 'viewMarca'])->name('index');
				Route::get('listarMarcas', [MarcaController::class, 'listarMarcas'])->name('listar-marcas');
				Route::get('mostrarMarca/{id}', [MarcaController::class, 'mostrarMarca'])->name('mostrar-marca');
				Route::post('guardarMarca', [MarcaController::class, 'guardarMarca'])->name('guardar-marca');
				Route::post('actualizarMarca', [MarcaController::class, 'actualizarMarca'])->name('actualizar-marca');
				Route::get('anularMarca/{id}', [MarcaController::class, 'anularMarca'])->name('anular-marca');
				Route::get('revisarMarca/{id}', [MarcaController::class, 'revisarMarca'])->name('revisar-marca');

				//Route::post('guardar-marca', [MarcaController::class, '@guardar')->name('guardar-marca');
			});

			Route::group(['as' => 'productos.', 'prefix' => 'productos'], function () {
				//Producto
				Route::get('index', [ProductoController::class, 'view_producto'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class, 'mostrar_prods_almacen'])->name('mostrar-prods-almacen');
				Route::get('mostrar_producto/{id}', [ProductoController::class, 'mostrar_producto'])->name('mostrar-producto');
				Route::get('mostrarCategoriasPorClasificacion/{id}', [CategoriaController::class, 'mostrarCategoriasPorClasificacion'])->name('mostrar-categorias-por-clasificacion');
				Route::get('mostrarSubCategoriasPorCategoria/{id}', [SubCategoriaController::class, 'mostrarSubCategoriasPorCategoria'])->name('mostrar-sub-categorias-por-categoria');
				Route::post('guardar_producto', [ProductoController::class, 'guardar_producto'])->name('guardar-producto');
				Route::post('actualizar_producto', [ProductoController::class, 'update_producto'])->name('actualizar-producto');
				Route::get('anular_producto/{id}', [ProductoController::class, 'anular_producto'])->name('anular-producto');
				Route::post('guardar_imagen', [ProductoController::class, 'guardar_imagen'])->name('guardar-imagen');

				Route::get('listar_promociones/{id}', [ProductoController::class, 'listar_promociones'])->name('listar-promociones');
				Route::post('crear_promocion', [ProductoController::class, 'crear_promocion'])->name('crear-promocion');
				Route::get('anular_promocion/{id}', [ProductoController::class, 'anular_promocion'])->name('anular-promocion');

				Route::get('listar_ubicaciones_producto/{id}', [ProductoController::class, 'listar_ubicaciones_producto'])->name('listar-ubicaciones-producto');
				Route::get('mostrar_ubicacion/{id}', [ProductoController::class, 'mostrar_ubicacion'])->name('mostrar-ubicacion');
				Route::post('guardar_ubicacion', [ProductoController::class, 'guardar_ubicacion'])->name('guardar-ubicacion');
				Route::post('actualizar_ubicacion', [ProductoController::class, 'update_ubicacion'])->name('actualizar-ubicacion');
				Route::get('anular_ubicacion/{id}', [ProductoController::class, 'anular_ubicacion'])->name('anular-ubicacion');

				Route::get('listar_series_producto/{id}', [ProductoController::class, 'listar_series_producto'])->name('listar-series-producto');
				Route::get('mostrar_serie/{id}', [ProductoController::class, 'mostrar_serie'])->name('mostrar-serie');
				Route::post('guardar_serie', [ProductoController::class, 'guardar_serie'])->name('guardar-serie');
				Route::post('actualizar_serie', [ProductoController::class, 'update_serie'])->name('actualizar-serie');
				Route::get('anular_serie/{id}', [ProductoController::class, 'anular_serie'])->name('anular-serie');

				Route::get('obtenerProductoSoftlink/{id}', [MigrateProductoSoftlinkController::class, 'obtenerProductoSoftlink'])->name('obtener-producto-softlink');
			});

			Route::group(['as' => 'catalogo-productos.', 'prefix' => 'catalogo-productos'], function () {
				Route::get('index', [ProductoController::class, 'view_prod_catalogo'])->name('index');
				Route::get('listar_productos', [ProductoController::class, 'mostrar_productos'])->name('listar-productos');
				// Route::post('productosExcel', [ProductoController::class, 'productosExcel')->name('productosExcel');
				Route::post('catalogoProductosExcel', function () {
					return Excel::download(new CatalogoProductoExport, 'Catalogo_Productos.xlsx');
				})->name('catalogoProductosExcel');
			});
		});

		Route::group(['as' => 'ubicaciones.', 'prefix' => 'ubicaciones'], function () {
			Route::group(['as' => 'tipos-almacen.', 'prefix' => 'tipos-almacen'], function () {
				//Tipos Almacen
				Route::get('index', [TipoAlmacenController::class, 'view_tipo_almacen'])->name('index');
				Route::get('listar_tipo_almacen', [TipoAlmacenController::class, 'mostrar_tipo_almacen'])->name('listar-tipo-almacen');
				Route::get('cargar_tipo_almacen/{id}', [TipoAlmacenController::class, 'mostrar_tipo_almacenes'])->name('cargar-tipo-almacen');
				Route::post('guardar_tipo_almacen', [TipoAlmacenController::class, 'guardar_tipo_almacen'])->name('guardar-tipo-almacen');
				Route::post('editar_tipo_almacen', [TipoAlmacenController::class, 'update_tipo_almacen'])->name('editar-tipo-almacen');
				Route::get('anular_tipo_almacen/{id}', [TipoAlmacenController::class, 'anular_tipo_almacen'])->name('anular-tipo-almacen');
			});

			Route::group(['as' => 'almacenes.', 'prefix' => 'almacenes'], function () {
				//Almacen
				Route::get('index', [UbicacionAlmacenController::class, 'view_almacenes'])->name('index');
				Route::get('listar_almacenes', [UbicacionAlmacenController::class, 'mostrar_almacenes'])->name('listar-almacenes');
				Route::get('mostrar_almacen/{id}', [UbicacionAlmacenController::class, 'mostrar_almacen'])->name('mostrar-almacen');
				Route::post('guardar_almacen', [UbicacionAlmacenController::class, 'guardar_almacen'])->name('guardar-almacen');
				Route::post('editar_almacen', [UbicacionAlmacenController::class, 'update_almacen'])->name('editar-almacen');
				Route::get('anular_almacen/{id}', [UbicacionAlmacenController::class, 'anular_almacen'])->name('anular-almacen');
				Route::get('listar_ubigeos', [UbicacionAlmacenController::class, 'listar_ubigeos'])->name('listar-ubigeos');

				Route::get('almacen_posicion/{id}', [PosicionController::class, 'almacen_posicion'])->name('almacen-posicion');
				Route::get('listarUsuarios', [UbicacionAlmacenController::class, 'listarUsuarios'])->name('listar-usuarios');
				Route::post('guardarAlmacenUsuario', [UbicacionAlmacenController::class, 'guardarAlmacenUsuario'])->name('guardar-almacen-usuario');
				Route::get('listarAlmacenUsuarios/{id}', [UbicacionAlmacenController::class, 'listarAlmacenUsuarios'])->name('listar-almacen-usuarios');
				Route::get('anularAlmacenUsuario/{id}', [UbicacionAlmacenController::class, '@anularAlmacenUsuario'])->name('anular-almacen-usuario');
			});

			Route::group(['as' => 'posiciones.', 'prefix' => 'posiciones'], function () {
				//Almacen
				Route::get('index', [PosicionController::class, 'view_ubicacion'])->name('index');
				Route::get('listar_estantes', [PosicionController::class, 'mostrar_estantes'])->name('listar-estantes');
				Route::get('listar_estantes_almacen/{id}', [PosicionController::class, 'mostrar_estantes_almacen'])->name('listar-estantes-almacen');
				Route::get('mostrar_estante/{id}', [PosicionController::class, 'mostrar_estante'])->name('mostrar-estante');
				Route::post('guardar_estante', [PosicionController::class, 'guardar_estante'])->name('guardar-estante');
				Route::post('actualizar_estante', [PosicionController::class, 'update_estante'])->name('guardar-estante');
				Route::get('anular_estante/{id}', [PosicionController::class, 'anular_estante'])->name('anular-estante');
				Route::get('revisar_estante/{id}', [PosicionController::class, 'revisar_estante'])->name('revisar-estante');
				Route::post('guardar_estantes', [PosicionController::class, 'guardar_estantes'])->name('guardar-estantes');
				Route::get('listar_niveles', [PosicionController::class, 'mostrar_niveles'])->name('listar-niveles');
				Route::get('listar_niveles_estante/{id}', [PosicionController::class, 'mostrar_niveles_estante'])->name('listar-niveles-estante');
				Route::get('mostrar_nivel/{id}', [PosicionController::class, 'mostrar_nivel'])->name('mostrar-nivel');
				Route::post('guardar_nivel', [PosicionController::class, 'guardar_nivel'])->name('guardar-nivel');
				Route::post('actualizar_nivel', [PosicionController::class, 'update_nivel'])->name('actualizar-nivel');
				Route::get('anular_nivel/{id}', [PosicionController::class, 'anular_nivel'])->name('anular-nivel');
				Route::get('revisar_nivel/{id}', [PosicionController::class, 'revisar_nivel'])->name('revisar-nivel');
				Route::post('guardar_niveles', [PosicionController::class, 'guardar_niveles'])->name('guardar-niveles');
				Route::get('listar_posiciones', [PosicionController::class, 'mostrar_posiciones'])->name('listar-posiciones');
				Route::get('listar_posiciones_nivel/{id}', [PosicionController::class, 'mostrar_posiciones_nivel'])->name('listar-posiciones-nivel');
				Route::get('mostrar_posicion/{id}', [PosicionController::class, 'mostrar_posicion'])->name('mostrar-posicion');
				Route::post('guardar_posiciones', [PosicionController::class, 'guardar_posiciones'])->name('guardar-posiciones');
				Route::get('anular_posicion/{id}', [PosicionController::class, 'anular_posicion'])->name('anular-posicion');
				Route::get('select_posiciones_almacen/{id}', [PosicionController::class, 'select_posiciones_almacen'])->name('select-posiciones-almacen');
				Route::get('listar_almacenes', [UbicacionAlmacenController::class, 'mostrar_almacenes'])->name('listar-almacenes');
			});
		});

		Route::group(['as' => 'control-stock.', 'prefix' => 'control-stock'], function () {
			Route::group(['as' => 'importar.', 'prefix' => 'importar'], function () {
				Route::get('index', [StockController::class, 'view_importar'])->name('index');
			});

			Route::group(['as' => 'toma-inventario.', 'prefix' => 'toma-inventario'], function () {
				Route::get('index', [StockController::class, 'view_toma_inventario'])->name('index');
			});
		});

		Route::group(['as' => 'movimientos.', 'prefix' => 'movimientos'], function () {
			Route::group(['as' => 'pendientes-ingreso.', 'prefix' => 'pendientes-ingreso'], function () {
				//Pendientes de Ingreso
				Route::get('index', [OrdenesPendientesController::class, 'view_ordenesPendientes'])->name('index');
				Route::post('listarOrdenesPendientes', [OrdenesPendientesController::class, 'listarOrdenesPendientes'])->name('listar-ordenes-pendientes');
				Route::post('listarIngresos', [OrdenesPendientesController::class, 'listarIngresos'])->name('listar-ingresos');
				Route::get('detalleOrden/{id}/{soloProductos}', [OrdenesPendientesController::class, 'detalleOrden'])->name('detalle-orden');
				Route::post('guardar_guia_com_oc', [OrdenesPendientesController::class, 'guardar_guia_com_oc'])->name('guardar-guia-com-oc');
				Route::get('verGuiasOrden/{id}', [OrdenesPendientesController::class, 'verGuiasOrden'])->name('ver-guias-orden');
				// Route::post('guardar_guia_transferencia', [OrdenesPendientesController::class, 'guardar_guia_transferencia');
				Route::post('anular_ingreso', [OrdenesPendientesController::class, 'anular_ingreso'])->name('anular-ingreso');
				Route::get('cargar_almacenes/{id}', [UbicacionAlmacenController::class, 'cargar_almacenes'])->name('cargar-almacenes');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');

				Route::post('detalleOrdenesSeleccionadas', [OrdenesPendientesController::class, 'detalleOrdenesSeleccionadas'])->name('detalle-ordenes-seleccionadas');
				Route::get('detalleMovimiento/{id}', [OrdenesPendientesController::class, 'detalleMovimiento'])->name('detalle-movimiento');
				Route::post('listarTransformacionesFinalizadas', [TransformacionController::class, 'listarTransformacionesFinalizadas'])->name('listar-transformaciones-finalizadas');
				Route::get('listarDetalleTransformacion/{id}', [TransformacionController::class, 'listarDetalleTransformacion'])->name('listar-detalle-transformacion');
				// Route::get('transferencia/{id}', [OrdenesPendientesController::class, 'transferencia');
				Route::get('obtenerGuia/{id}', [OrdenesPendientesController::class, 'obtenerGuia'])->name('obtener-guia');
				Route::post('guardar_doc_compra', [OrdenesPendientesController::class, 'guardar_doc_compra'])->name('guardar-doc-compra');
				Route::get('documentos_ver/{id}', [OrdenesPendientesController::class, 'documentos_ver'])->name('documentos-ver');

				Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::post('guardar_producto', [ProductoController::class, 'guardar_producto'])->name('guardar-producto');

				Route::get('mostrar_series/{id}', [OrdenesPendientesController::class, 'mostrar_series'])->name('mostrar-series');
				Route::post('guardar_series', [OrdenesPendientesController::class, 'guardar_series'])->name('guardar-series');
				Route::post('actualizar_series', [OrdenesPendientesController::class, 'actualizar_series'])->name('actualizar-series');
				Route::post('cambio_serie_numero', [OrdenesPendientesController::class, 'cambio_serie_numero'])->name('cambio-series');

				Route::get('verGuiaCompraTransferencia/{id}', [TransferenciaController::class, 'verGuiaCompraTransferencia'])->name('ver-guia-compra-transferencia');
				Route::get('transferencia/{id}', [OrdenesPendientesController::class, 'transferencia'])->name('transferencia');
				Route::post('obtenerGuiaSeleccionadas', [OrdenesPendientesController::class, 'obtenerGuiaSeleccionadas'])->name('obtener-guia-seleccionadas');
				Route::get('anular_doc_com/{id}', [OrdenesPendientesController::class, 'anular_doc_com'])->name('anular-doc-com');

				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');

				Route::post('listarProductosSugeridos', [ProductoController::class, 'listarProductosSugeridos'])->name('listar-productos-sugeridos');
				Route::get('mostrar_prods_sugeridos/{part}/{desc}', [ProductoController::class, 'mostrar_prods_sugeridos'])->name('mostrar-prods-sugeridos');
				Route::get('mostrar_categorias_tipo/{id}', [SubCategoriaController::class, 'mostrarSubCategoriasPorCategoria'])->name('mostrar-categorias-tipo');
				Route::get('mostrar_tipos_clasificacion/{id}', [CategoriaController::class, 'mostrarCategoriasPorClasificacion'])->name('mostrar-tipos-clasificacion');

				Route::get('sedesPorUsuario', [OrdenesPendientesController::class, 'sedesPorUsuario'])->name('sedes-por-usuario');
				Route::post('actualizarFiltrosPendientes', [OrdenesPendientesController::class, 'actualizarFiltrosPendientes'])->name('actualizar-filtros-pendientes');

				Route::post('ordenesPendientesExcel', [OrdenesPendientesController::class, 'ordenesPendientesExcel'])->name('ordenesPendientesExcel');
				Route::post('ingresosProcesadosExcel', [OrdenesPendientesController::class, 'ingresosProcesadosExcel'])->name('ingresosProcesadosExcel');
				Route::get('seriesExcel/{id}', [OrdenesPendientesController::class, 'seriesExcel'])->name('series-excel');
				Route::post('actualizarIngreso', [OrdenesPendientesController::class, 'actualizarIngreso'])->name('actualizar-ingreso');

				Route::get('sedesPorUsuarioArray', [OrdenesPendientesController::class, 'sedesPorUsuarioArray'])->name('sedes-por-usuario-array');
				Route::get('getTipoCambioVenta/{fec}', [TransformacionController::class, 'getTipoCambioVenta'])->name('get-tipo-cambio-venta');
				Route::get('pruebaOrdenesPendientesLista', [OrdenesPendientesController::class, 'pruebaOrdenesPendientesLista'])->name('prueba-ordenes-pendientes-lista');

				Route::get('listarDevolucionesRevisadas', [DevolucionController::class, 'listarDevolucionesRevisadas'])->name('listar-devoluciones-revisadas');
				Route::get('listarDetalleDevolucion/{id}', [DevolucionController::class, 'listarDetalleDevolucion'])->name('listar-detalle-devolucion');
				Route::get('verFichasTecnicasAdjuntas/{id}', [DevolucionController::class, 'verFichasTecnicasAdjuntas'])->name('ver-fichas-tecnicas');
			});

			Route::group(['as' => 'pendientes-salida.', 'prefix' => 'pendientes-salida'], function () {
				//Pendientes de Salida
				Route::get('index', [SalidasPendientesController::class, 'view_despachosPendientes'])->name('index');
				Route::post('listarOrdenesDespachoPendientes', [SalidasPendientesController::class, 'listarOrdenesDespachoPendientes'])->name('listar-ordenes-despacho-pendientes');
				Route::post('guardarSalidaGuiaDespacho', [SalidasPendientesController::class, 'guardarSalidaGuiaDespacho'])->name('guardar-salida-guia-despacho');
				Route::post('listarSalidasDespacho', [SalidasPendientesController::class, 'listarSalidasDespacho'])->name('listar-salidas-despacho');
				Route::post('anular_salida', [SalidasPendientesController::class, 'anular_salida'])->name('anular-salida');
				Route::post('cambio_serie_numero', [SalidasPendientesController::class, 'cambio_serie_numero'])->name('cambio-serie-numero');
				Route::get('verDetalleDespacho/{id}/{od}/{ac}/{tra}', [SalidasPendientesController::class, 'verDetalleDespacho'])->name('ver-detalle-despacho');
				Route::get('marcar_despachado/{id}/{tra}', [SalidasPendientesController::class, 'marcar_despachado'])->name('marcar-despachado');
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
				// Route::get('anular_orden_despacho/{id}', [SalidasPendientesController::class, 'anular_orden_despacho');
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class, 'listarSeriesGuiaVen'])->name('listar-series-guia-ven');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimientoDI');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');

				Route::post('actualizarSalida', [SalidasPendientesController::class, 'actualizarSalida'])->name('actualizar-salida');
				Route::get('detalleMovimientoSalida/{id}', [SalidasPendientesController::class, 'detalleMovimientoSalida'])->name('detalle-movimiento-salida');
				Route::get('guia-salida-excel/{idGuia}', [SalidasPendientesController::class, 'guiaSalidaExcel'])->name('guia-salida-excel');
				Route::get('guia-salida-excel-formato-okc', [GuiaSalidaExcelFormatoOKCController::class, 'construirExcel'])->name('guia-salida-excel-formato-okc');
				Route::get('guia-salida-excel-formato-svs', [GuiaSalidaExcelFormatoSVSController::class, 'construirExcel'])->name('guia-salida-excel-formato-svs');

				Route::get('validaStockDisponible/{id}/{alm}', [SalidasPendientesController::class, 'validaStockDisponible'])->name('valida-stock-disponible');

				Route::get('seriesVentaExcel/{id}', [SalidasPendientesController::class, 'seriesVentaExcel'])->name('series-venta-excel');
				Route::post('salidasPendientesExcel', [SalidasPendientesController::class, 'salidasPendientesExcel'])->name('salidasPendientesExcel');
				Route::post('salidasProcesadasExcel', [SalidasPendientesController::class, 'salidasProcesadasExcel'])->name('salidasProcesadasExcel');

				Route::get('actualizaItemsODE/{id}', [SalidasPendientesController::class, 'actualizaItemsODE'])->name('actualiza-itemsODE');
				Route::get('actualizaItemsODI/{id}', [SalidasPendientesController::class, 'actualizaItemsODI'])->name('actualiza-itemsODI');
				Route::get('atencion-ver-adjuntos', [SalidasPendientesController::class, 'verAdjuntos'])->name('atencion-ver-adjuntos');
				Route::get('mostrarClientes', [SalidasPendientesController::class, 'mostrarClientes'])->name('mostrarClientes');
				Route::post('guardarCliente', [SalidasPendientesController::class, 'guardarCliente'])->name('guardarCliente');

				Route::get('listarDevolucionesSalidas', [DevolucionController::class, 'listarDevolucionesSalidas'])->name('listar-devoluciones-salidas');
				Route::get('verDetalleDevolucion/{id}', [SalidasPendientesController::class, 'verDetalleDevolucion'])->name('ver-detalle-devolucion');
			});

			Route::group(['as' => 'customizacion.', 'prefix' => 'customizacion'], function () {
				//Pendientes de Salida
				Route::get('index', [CustomizacionController::class, 'viewCustomizacion'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::post('listarProductosAlmacen', [SaldoProductoController::class, 'listarProductosAlmacen'])->name('listar-productos-almacen');
				Route::post('guardar_materia', [TransformacionController::class, 'guardar_materia'])->name('guardar-materia');
				Route::post('guardarCustomizacion', [CustomizacionController::class, 'guardarCustomizacion'])->name('guardar-customizacion');
				Route::post('actualizarCustomizacion', [CustomizacionController::class, 'actualizarCustomizacion'])->name('actualizar-customizacion');
				Route::get('anularCustomizacion/{id}', [CustomizacionController::class, 'anularCustomizacion'])->name('anular-customizacion');
				Route::get('listar_transformaciones/{tp}', [TransformacionController::class, 'listar_transformaciones'])->name('listar-transformaciones');
				Route::get('mostrarCustomizacion/{id}', [CustomizacionController::class, 'mostrarCustomizacion'])->name('mostrar-customizacion');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
				Route::post('actualizarCostosBase', [CustomizacionController::class, 'actualizarCostosBase'])->name('actualizar-costos-base');
				Route::get('procesarCustomizacion/{id}', [CustomizacionController::class, 'procesarCustomizacion'])->name('procesar-customizacion');
				Route::get('obtenerTipoCambio/{fec}/{mon}', [CustomizacionController::class, 'obtenerTipoCambio'])->name('obtener-tipo-cambio');
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class, 'listarSeriesGuiaVen'])->name('listar-series-guia-ven');
				Route::get('validarEdicion/{id}', [CustomizacionController::class, 'validarEdicion'])->name('validar-edicion');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
			});

			Route::group(['as' => 'devolucion.', 'prefix' => 'devolucion'], function () {
				//Devoluciones
				Route::get('index', [DevolucionController::class, 'viewDevolucion'])->name('index');
				Route::post('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::get('listarDevoluciones', [DevolucionController::class, 'listarDevoluciones'])->name('listar-devoluciones');
				Route::post('mostrarContribuyentes', [DevolucionController::class, 'mostrarContribuyentes'])->name('mostrar-contribuyentes');
				Route::get('mostrarDevolucion/{id}', [DevolucionController::class, 'mostrarDevolucion'])->name('mostrar-devolucion');
				Route::post('guardarDevolucion', [DevolucionController::class, 'guardarDevolucion'])->name('guardar-devolucion');
				Route::post('actualizarDevolucion', [DevolucionController::class, 'actualizarDevolucion'])->name('actualizar-devolucion');
				Route::get('validarEdicion/{id}', [DevolucionController::class, 'validarEdicion'])->name('validar-edicion');
				Route::get('anularDevolucion/{id}', [DevolucionController::class, 'anularDevolucion'])->name('anular-devolucion');
				Route::get('listarSalidasVenta/{alm}/{id}', [DevolucionController::class, 'listarSalidasVenta'])->name('listar-salidas-venta');
				Route::get('listarIngresos/{alm}/{id}', [DevolucionController::class, 'listarIngresos'])->name('listar-ingresos');
				Route::get('obtenerMovimientoDetalle/{id}', [DevolucionController::class, 'obtenerMovimientoDetalle'])->name('obtener-movimiento-detalle');
				Route::get('listarIncidencias', [IncidenciaController::class, 'listarIncidencias'])->name('listar-incidencias');
			});

			Route::group(['as' => 'prorrateo.', 'prefix' => 'prorrateo'], function () {
				//Pendientes de Salida
				Route::get('index', [ProrrateoCostosController::class, 'view_prorrateo_costos'])->name('index');
				Route::get('mostrar_prorrateos', [ProrrateoCostosController::class, 'mostrar_prorrateos'])->name('mostrar-prorrateos');
				Route::get('mostrar_prorrateo/{id}', [ProrrateoCostosController::class, 'mostrar_prorrateo'])->name('mostrar-prorrateo');
				Route::get('mostrar_proveedores', [LogisticaController::class, 'mostrar_proveedores'])->name('mostrar-proveedores');
				Route::get('guardar_tipo_prorrateo/{nombre}', [ProrrateoCostosController::class, 'guardar_tipo_prorrateo'])->name('guardar-tipo-prorrateo');
				Route::get('obtenerTipoCambio/{fec}/{mon}', [CustomizacionController::class, 'obtenerTipoCambio'])->name('obtener-tipo-cambio');
				Route::get('listar_guias_compra', [ProrrateoCostosController::class, 'listar_guias_compra'])->name('listar-guias-compra');
				Route::get('listar_docs_prorrateo/{id}', [ProrrateoCostosController::class, 'listar_docs_prorrateo'])->name('listar-docs-prorrateo');
				Route::get('listar_guia_detalle/{id}', [ProrrateoCostosController::class, 'listar_guia_detalle'])->name('listar-guia-detalle');
				Route::post('guardarProrrateo', [ProrrateoCostosController::class, 'guardarProrrateo'])->name('guardar-prorrateo');
				Route::post('updateProrrateo', [ProrrateoCostosController::class, 'updateProrrateo'])->name('update-prorrateo');
				Route::get('anular_prorrateo/{id}', [ProrrateoCostosController::class, 'anular_prorrateo'])->name('anular-prorrateo');
				Route::post('guardarProveedor', [ProrrateoCostosController::class, 'guardarProveedor'])->name('guardar-proveedor');
			});

			Route::group(['as' => 'reservas.', 'prefix' => 'reservas'], function () {
				//Pendientes de Salida
				Route::get('index', [ReservasAlmacenController::class, 'viewReservasAlmacen'])->name('index');
				Route::post('listarReservasAlmacen', [ReservasAlmacenController::class, 'listarReservasAlmacen'])->name('listarReservasAlmacen');
				Route::post('anularReserva', [ReservasAlmacenController::class, 'anularReserva'])->name('anular-reserva');
				Route::post('actualizarReserva', [ReservasAlmacenController::class, 'actualizarReserva'])->name('actualizar-reserva');
				Route::get('actualizarReservas', [ReservasAlmacenController::class, 'actualizarReservas'])->name('actualizar-reservas');
				Route::post('actualizarEstadoReserva', [ReservasAlmacenController::class, 'actualizarEstadoReserva'])->name('actualizar-estado-reserva');
			});

			Route::group(['as' => 'requerimientos-almacen.', 'prefix' => 'requerimientos-almacen'], function () {
				//Pendientes de Salida
				Route::get('index', [ListaRequerimientosAlmacenController::class, 'viewRequerimientosAlmacen'])->name('index');
				Route::post('listarRequerimientosAlmacen', [ListaRequerimientosAlmacenController::class, 'listarRequerimientosAlmacen'])->name('listarRequerimientosAlmacen');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimientoDI');
				Route::get('listarDetalleTransferencias/{id}', [TransferenciaController::class, 'listarDetalleTransferencias'])->name('listar-detalle-transferencias');
				Route::post('cambioAlmacen', [ListaRequerimientosAlmacenController::class, 'cambioAlmacen'])->name('cambio-almacen');
				Route::get('listarDetalleRequerimiento/{id}', [ListaRequerimientosAlmacenController::class, 'listarDetalleRequerimiento'])->name('listar-detalle-requerimiento');
				Route::post('anularDespachoInterno', [OrdenesDespachoInternoController::class, 'anularDespachoInterno'])->name('anularDespachoInterno');
				Route::post('guardar-ajuste-transformacion-requerimiento', [ComprasPendientesController::class, 'guardarAjusteTransformacionRequerimiento'])->name('guardar-ajuste-transformacion-requerimiento');
				Route::get('mostrar-requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'requerimiento'])->name('mostrar-requerimiento');
				Route::get('detalle-requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'detalleRequerimiento'])->name('detalle-requerimientos');
			});
		});

		Route::group(['as' => 'comprobantes.', 'prefix' => 'comprobantes'], function () {
			Route::get('mostrar_proveedores', [LogisticaController::class, 'mostrar_proveedores']);
			Route::get('listar_guias_proveedor/{id?}', [AlmacenController::class, 'listar_guias_proveedor'])->name('listar-guias-proveedor');
			Route::get('listar_detalle_guia_compra/{id?}', [ComprobanteCompraController::class, 'listar_detalle_guia_compra'])->name('listar-detalle-guia-compra');
			Route::get('tipo_cambio_compra/{fecha}', [AlmacenController::class, 'tipo_cambio_compra'])->name('tipo-cambio-compra');
			Route::post('guardar_doc_compra', [ComprobanteCompraController::class, 'guardar_doc_compra'])->name('guardar-doc-compra');
			// Route::get('listar_guias_prov/{id?}', [ComprobanteCompraController::class, 'listar_guias_prov');
			Route::post('listar_docs_compra', [ComprobanteCompraController::class, 'listar_docs_compra'])->name('listar-docs-compra');

			Route::get('lista_comprobante_compra', [ComprobanteCompraController::class, 'view_lista_comprobantes_compra'])->name('lista_comprobante_compra');
			Route::get('documentoAPago/{id}', [ComprobanteCompraController::class, 'documentoAPago'])->name('documento-a-pago');
			Route::get('enviarComprobanteSoftlink/{id}', [MigrateFacturasSoftlinkController::class, 'enviarComprobanteSoftlink'])->name('enviar-comprobante-softlink');
			Route::get('documentos_ver/{id}', [OrdenesPendientesController::class, 'documentos_ver'])->name('documentos-ver');
			Route::get('actualizarSedesFaltantes', [MigrateFacturasSoftlinkController::class, 'actualizarSedesFaltantes'])->name('actualizar-sedes-faltantes');
			Route::get('actualizarProveedorComprobantes', [MigrateFacturasSoftlinkController::class, 'actualizarProveedorComprobantes'])->name('actualizar-proveedor-comprobantes');
			Route::get('migrarComprobantesSoftlink', [MigrateFacturasSoftlinkController::class, 'migrarComprobantesSoftlink'])->name('migrar-comprobantes-softlink');
			Route::get('migrarItemsComprobantesSoftlink', [MigrateFacturasSoftlinkController::class, 'migrarItemsComprobantesSoftlink'])->name('migrar-items-comprobantes-softlink');

			Route::get('lista-comprobantes-pago-export-excel', [ComprobanteCompraController::class, 'exportListaComprobantesPagos'])->name('lista.comprobante.pago.export.excel');
		});

		Route::group(['as' => 'transferencias.', 'prefix' => 'transferencias'], function () {
			Route::group(['as' => 'gestion-transferencias.', 'prefix' => 'gestion-transferencias'], function () {
				//Transferencias
				Route::get('index', [TransferenciaController::class, 'view_listar_transferencias'])->name('index');
				Route::post('listarRequerimientos', [TransferenciaController::class, 'listarRequerimientos']);
				Route::get('listarTransferenciaDetalle/{id}', [TransferenciaController::class, 'listarTransferenciaDetalle'])->name('listar-transferencia-detalle');
				Route::post('guardarIngresoTransferencia', [TransferenciaController::class, 'guardarIngresoTransferencia'])->name('guardar-ingreso-transferencia');
				Route::post('guardarSalidaTransferencia', [TransferenciaController::class, 'guardarSalidaTransferencia'])->name('guardar-salida-transferencia');
				Route::post('anularTransferenciaIngreso', [TransferenciaController::class, 'anularTransferenciaIngreso'])->name('anular-transferencia-ingreso');
				Route::get('ingreso_transferencia/{id}', [TransferenciaController::class, 'ingreso_transferencia'])->name('ingreso-transferencia');
				// Route::get('transferencia_nextId/{id}', [TransferenciaController::class, 'transferencia_nextId');
				Route::post('anularTransferenciaSalida', [TransferenciaController::class, 'anularTransferenciaSalida'])->name('anular-transferencia-salida');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
				Route::post('listarTransferenciasPorEnviar', [TransferenciaController::class, 'listarTransferenciasPorEnviar'])->name('listar-transferencias-por-enviar');
				Route::post('listarTransferenciasPorRecibir', [TransferenciaController::class, 'listarTransferenciasPorRecibir'])->name('listar-transferencias-por-recibir');
				Route::post('listarTransferenciasRecibidas', [TransferenciaController::class, 'listarTransferenciasRecibidas'])->name('listar-transferencias-recibidas');
				// Route::get('cargar_almacenes/{id}', [UbicacionAlmacenController::class, '@cargar_almacenes');
				Route::post('listarDetalleTransferencia', [TransferenciaController::class, 'listarDetalleTransferencia'])->name('listar-detalle-transferencia');
				// Route::get('listarDetalleTransferencia/{id}', [TransferenciaController::class, 'listarDetalleTransferencia');
				// Route::post('listarDetalleTransferenciasSeleccionadas', [TransferenciaController::class, 'listarDetalleTransferenciasSeleccionadas');
				Route::get('listarGuiaTransferenciaDetalle/{id}', [TransferenciaController::class, 'listarGuiaTransferenciaDetalle'])->name('listar-guia-transferencia-detalle');
				Route::get('listarSeries/{id}', [TransferenciaController::class, 'listarSeries'])->name('listar-series');
				Route::get('listarSeriesVen/{id}', [TransferenciaController::class, 'listarSeriesVen'])->name('listar-series-ven');
				Route::get('anular_transferencia/{id}', [TransferenciaController::class, 'anular_transferencia'])->name('anular-transferencia');
				// Route::get('listar_guias_compra', [TransferenciaController::class, 'listar_guias_compra');
				Route::get('transferencia/{id}', [OrdenesPendientesController::class, 'transferencia'])->name('transferencia');
				Route::get('verGuiaCompraTransferencia/{id}', [TransferenciaController::class, 'verGuiaCompraTransferencia'])->name('ver-guia-compra-transferencia');

				Route::get('verRequerimiento/{id}', [TransferenciaController::class, 'verRequerimiento'])->name('ver-requerimiento');
				Route::post('generarTransferenciaRequerimiento', [TransferenciaController::class, 'generarTransferenciaRequerimiento'])->name('generar-transferencia-requerimiento');
				Route::get('listarSeriesGuiaVen/{id}/{alm}', [SalidasPendientesController::class, 'listarSeriesGuiaVen'])->name('listar-series-guia-ven');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
				Route::get('mostrarTransportistas', [DistribucionController::class, 'mostrarTransportistas'])->name('mostrar-transportistas');

				Route::get('autogenerarDocumentosCompra/{id}/{tr}', [VentasInternasController::class, 'autogenerarDocumentosCompra'])->name('autogenerarDocumentosCompra');
				Route::get('verDocumentosAutogenerados/{id}', [VentasInternasController::class, 'verDocumentosAutogenerados'])->name('ver-documentos-autogenerados');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimientoDI');
				Route::get('almacenesPorUsuario', [TransferenciaController::class, 'almacenesPorUsuario'])->name('almacenes-por-usuario');

				Route::post('listarProductosAlmacen', [SaldoProductoController::class, 'listarProductosAlmacen'])->name('listar-productos-almacen');
				Route::post('nuevaTransferencia', [TransferenciaController::class, 'nuevaTransferencia'])->name('nueva-transferencia');
				Route::get('pruebaSaldos', [SaldoProductoController::class, 'pruebaSaldos'])->name('prueba-saldos');

				Route::get('getAlmacenesPorEmpresa/{id}', [TransferenciaController::class, 'getAlmacenesPorEmpresa'])->name('get-almacenes-por-empresa');
				Route::get('imprimir_transferencia/{id}', [TransferenciaController::class, 'imprimir_transferencia'])->name('imprimir-transferencia');

				Route::post('actualizarCostosVentasInternas', [VentasInternasController::class, 'actualizarCostosVentasInternas'])->name('actualizar-costos-ventas-internas');
				Route::post('actualizarValorizacionesIngresos', [VentasInternasController::class, 'actualizarValorizacionesIngresos'])->name('actualizar-valorizaciones-ingresos');
			});
		});

		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {

			Route::group(['as' => 'saldos.', 'prefix' => 'saldos'], function () {

				Route::get('tipo_cambio_compra/{fecha}', [SaldosController::class, 'tipo_cambio_compra']);

				Route::get('index', [SaldosController::class, 'view_saldos'])->name('index');
				Route::post('filtrar', [SaldosController::class, 'filtrar'])->name('filtrar');
				Route::post('listar', [SaldosController::class, 'listar'])->name('listar');
				Route::get('verRequerimientosReservados/{id}/{alm}', [SaldosController::class, 'verRequerimientosReservados'])->name('ver-requerimientos-reservados');
				Route::get('exportar', [SaldosController::class, 'exportar'])->name('exportar');
				Route::get('exportarSeries', [SaldosController::class, 'exportarSeries'])->name('exportarSeries');
				Route::get('exportarAntiguedades', [SaldosController::class, 'exportarAntiguedades'])->name('exportarAntiguedades');
				Route::post('exportar-valorizacion', [SaldosController::class, 'valorizacion'])->name('exportar-valorizacion');
				Route::get('actualizarFechasIngresoSoft/{id}', [MigrateProductoSoftlinkController::class, 'actualizarFechasIngresoSoft'])->name('actualizarFechasIngresoSoft');
				Route::get('actualizarFechasIngresoAgile/{id}', [MigrateProductoSoftlinkController::class, 'actualizarFechasIngresoAgile'])->name('actualizarFechasIngresoSoft');
			});

			Route::group(['as' => 'lista-ingresos.', 'prefix' => 'lista-ingresos'], function () {

				Route::get('index', [AlmacenController::class, 'view_ingresos'])->name('index');
				Route::get('listar_ingresos/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}/{tra}', [AlmacenController::class, 'listar_ingresos_lista'])->name('listar-ingresos-get');
				Route::get('update_revisado/{id}/{rev}/{obs}', [AlmacenController::class, 'update_revisado'])->name('update-revisado');

				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class, 'select_almacenes_empresa'])->name('select-almacenes-empresa');
				Route::get('mostrar_proveedores', [LogisticaController::class, 'mostrar_proveedores'])->name('mostrar-proveedores');
				Route::get('listar_transportistas_com', [AlmacenController::class, 'listar_transportistas_com'])->name('listar-transportistas-com');
				Route::get('listar_transportistas_ven', [AlmacenController::class, 'listar_transportistas_ven'])->name('listar-transportistas-ven');

				Route::get('listar-ingresos-excel/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}/{tra}', [AlmacenController::class, 'ExportarExcelListaIngresos'])->name('listar-ingresos-excel');
				// reportes con modelos
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				// Route::post('listar-ingresos', 'Almacen\Reporte\ListaIngresosController@listarIngresos');
				Route::post('listar-ingresos', [ListaIngresosController::class,'listarIngresos'])->name('listar-ingresos-post');
			});

			Route::group(['as' => 'lista-salidas.', 'prefix' => 'lista-salidas'], function () {

				Route::get('index', [AlmacenController::class, 'view_salidas'])->name('index');
				Route::get('listar_salidas/{alm}/{docs}/{cond}/{fini}/{ffin}/{cli}/{usu}/{mon}/{ref}', [AlmacenController::class, 'listar_salidas'])->name('listar-salidas');
				Route::get('update_revisado/{id}/{rev}/{obs}', [AlmacenController::class, 'update_revisado'])->name('update-revisado');

				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class, 'select_almacenes_empresa'])->name('select-almacenes-empresa');
				Route::get('mostrar_clientes', [ClienteController::class, 'mostrar_clientes'])->name('mostrar-clientes');
				Route::get('mostrar_clientes_empresa', [ClienteController::class, 'mostrar_clientes_empresa'])->name('mostrar-clientes-empresa');
				Route::get('listar_transportistas_com', [AlmacenController::class, 'listar_transportistas_com'])->name('listar-transportistas-com');
				Route::get('listar_transportistas_ven', [AlmacenController::class, 'listar_transportistas_ven'])->name('listar-transportistas-ven');

				Route::get('listar-salidas-excel/{empresa}/{sede}/{alm}/{cond}/{fini}/{ffin}/{prov}/{usu}/{mon}', [AlmacenController::class, 'ExportarExcelListaSalidas'])->name('listar-salidas-excel');
				// reportes con modelos
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				Route::post('listar-salidas', [ListaSalidasController::class, 'listarSalidas'])->name('listar-salidas');
			});

			Route::group(['as' => 'detalle-ingresos.', 'prefix' => 'detalle-ingresos'], function () {

				Route::get('index', [AlmacenController::class, 'view_busqueda_ingresos'])->name('index');
				Route::get('listar_busqueda_ingresos/{alm}/{tp}/{des}/{doc}/{fini}/{ffin}', [AlmacenController::class, 'listar_busqueda_ingresos'])->name('listar-busqueda-ingresos');
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class, 'select_almacenes_empresa'])->name('select-almacenes-empresa');
				Route::get('imprimir_ingreso/{id}', [OrdenesPendientesController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::get('imprimir_guia_ingreso/{id}', [AlmacenController::class, 'imprimir_guia_ingreso'])->name('imprimir-guia-ingreso');
			});

			Route::group(['as' => 'detalle-salidas.', 'prefix' => 'detalle-salidas'], function () {

				Route::get('index', [AlmacenController::class, 'view_busqueda_salidas'])->name('index');
				Route::get('listar_busqueda_salidas/{alm}/{tp}/{des}/{doc}/{fini}/{ffin}', [AlmacenController::class, 'listar_busqueda_salidas'])->name('listar-busqueda-salidas');
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class, 'select_almacenes_empresa'])->name('select-almacenes-empresa');
				Route::get('imprimir_salida/{id}', [AlmacenController::class, 'imprimir_salida'])->name('imprimir-salida');
			});

			Route::group(['as' => 'kardex-general.', 'prefix' => 'kardex-general'], function () {

				Route::get('index', [AlmacenController::class, 'view_kardex_general'])->name('index');
				Route::get('kardex_general/{id}/{fini}/{ffin}', [AlmacenController::class, 'kardex_general'])->name('kardex-general');
				Route::get('kardex_sunat/{id}/{fini}/{ffin}', [AlmacenController::class, 'download_kardex_sunat'])->name('kardex-sunat');
				// Route::get('kardex_sunatx/{id}', [AlmacenController::class, 'kardex_sunat');
				Route::get('exportar_kardex_general/{id}/{fini}/{ffin}', [ReportesController::class, 'exportarKardex'])->name('exportar-kardex-general');
			});

			Route::group(['as' => 'kardex-productos.', 'prefix' => 'kardex-productos'], function () {

				Route::get('index', [AlmacenController::class, 'view_kardex_detallado'])->name('index');
				Route::get('kardex_producto/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class, 'kardex_producto'])->name('kardex-producto');
				Route::get('listar_kardex_producto/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class, 'kardex_producto'])->name('listar-kardex-producto');
				Route::get('kardex_detallado/{id}/{alm}/{fini}/{ffin}', [AlmacenController::class, 'download_kardex_producto'])->name('kardex-detallado');
				Route::get('select_almacenes_empresa/{id}', [AlmacenController::class, 'select_almacenes_empresa'])->name('select-almacenes-empresa');
				Route::get('datos_producto/{id}', [KardexSerieController::class, 'datos_producto'])->name('datos-producto');
				Route::post('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class, 'mostrar_prods_almacen'])->name('mostrar-prods-almacen');
			});

			Route::group(['as' => 'kardex-series.', 'prefix' => 'kardex-series'], function () {

				Route::get('index', [KardexSerieController::class, 'view_kardex_series'])->name('index');
				Route::get('listar_serie_productos/{serie}/{des}/{cod}/{part}', [KardexSerieController::class, 'listar_serie_productos'])->name('listar-erie-productos');
				Route::get('listar_kardex_serie/{serie}/{id_prod}', [KardexSerieController::class, 'listar_kardex_serie'])->name('listar-kardex-serie');
				Route::get('datos_producto/{id}', [KardexSerieController::class, 'datos_producto'])->name('datos-producto');
				Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::get('mostrar_prods_almacen/{id}', [ProductoController::class, 'mostrar_prods_almacen'])->name('mostrar-prods-almacen');
			});

			Route::group(['as' => 'documentos-prorrateo.', 'prefix' => 'documentos-prorrateo'], function () {

				Route::get('index', [AlmacenController::class, 'view_docs_prorrateo'])->name('index');
				Route::get('listar_documentos_prorrateo', [AlmacenController::class, 'listar_documentos_prorrateo'])->name('listar-documentos-prorrateo');
			});

			Route::group(['as' => 'stock-series.', 'prefix' => 'stock-serie'], function () {

				Route::get('index', [AlmacenController::class, 'view_stock_series'])->name('index');
				Route::post('listar_stock_series', [AlmacenController::class, 'listar_stock_series'])->name('listar-stock-series');
				Route::get('prueba_exportar_excel', [AlmacenController::class, 'obtener_data_stock_series'])->name('prueba-exportar-excel');
				Route::get('exportar_excel', [AlmacenController::class, 'exportar_stock_series_excel'])->name('exportar-excel');
			});
		});

		Route::group(['as' => 'variables.', 'prefix' => 'variables'], function () {

			Route::group(['as' => 'series-numeros.', 'prefix' => 'series-numeros'], function () {

				Route::get('index', [AlmacenController::class, 'view_serie_numero'])->name('index');
				Route::get('listar_series_numeros', [AlmacenController::class, 'listar_series_numeros'])->name('listar-series-numeros');
				Route::get('mostrar_serie_numero/{id}', [AlmacenController::class, 'mostrar_serie_numero'])->name('mostrar-serie-numero');
				Route::post('guardar_serie_numero', [AlmacenController::class, 'guardar_serie_numero'])->name('guardar-serie-numero');
				Route::post('actualizar_serie_numero', [AlmacenController::class, 'update_serie_numero'])->name('actualizar-serie-numero');
				Route::get('anular_serie_numero/{id}', [AlmacenController::class, 'anular_serie_numero'])->name('anular-serie-numero');
				Route::get('series_numeros/{desde}/{hasta}/{num}/{serie}', [AlmacenController::class, 'series_numeros'])->name('series-numeros');
			});

			Route::group(['as' => 'tipos-movimiento.', 'prefix' => 'tipos-movimiento'], function () {

				Route::get('index', [AlmacenController::class, 'view_tipo_movimiento'])->name('index');
				Route::get('listar_tipoMov', [AlmacenController::class, 'mostrar_tipos_mov']);
				Route::get('mostrar_tipoMov/{id}', [AlmacenController::class, 'mostrar_tipo_mov']);
				Route::post('guardar_tipoMov', [AlmacenController::class, 'guardar_tipo_mov']);
				Route::post('actualizar_tipoMov', [AlmacenController::class, 'update_tipo_mov']);
				Route::get('anular_tipoMov/{id}', [AlmacenController::class, 'anular_tipo_mov']);
			});

			Route::group(['as' => 'tipos-documento.', 'prefix' => 'tipos-documento'], function () {

				Route::get('index', [AlmacenController::class, 'view_tipo_doc_almacen'])->name('index');
				Route::get('listar_tp_docs', [AlmacenController::class, 'listar_tp_docs']);
				Route::get('mostrar_tp_doc/{id}', [AlmacenController::class, 'mostrar_tp_doc']);
				Route::post('guardar_tp_doc', [AlmacenController::class, 'guardar_tp_doc']);
				Route::post('update_tp_doc', [AlmacenController::class, 'update_tp_doc']);
				Route::get('anular_tp_doc/{id}', [AlmacenController::class, 'anular_tp_doc']);
			});

			Route::group(['as' => 'unidades-medida.', 'prefix' => 'unidades-medida'], function () {

				Route::get('index', [AlmacenController::class, 'view_unid_med'])->name('index');
				Route::get('listar_unidmed', [AlmacenController::class, 'mostrar_unidades_med']);
				Route::get('mostrar_unidmed/{id}', [AlmacenController::class, 'mostrar_unid_med']);
				Route::post('guardar_unidmed', [AlmacenController::class, 'guardar_unid_med']);
				Route::post('actualizar_unidmed', [AlmacenController::class, 'update_unid_med']);
				Route::get('anular_unidmed/{id}', [AlmacenController::class, 'anular_unid_med']);
			});
		});
	});

	/**
	 * Garantías CAS
	 */
	Route::group(['as' => 'cas.', 'prefix' => 'cas'], function () {
		Route::get('index', [TransformacionController::class, 'view_main_cas'])->name('index');
		Route::group(['as' => 'customizacion.', 'prefix' => 'customizacion'], function () {
			Route::group(['as' => 'tablero-transformaciones.', 'prefix' => 'tablero-transformaciones'], function () {
				Route::get('index', [OrdenesTransformacionController::class, 'view_tablero_transformaciones'])->name('index');
				Route::get('listarDespachosInternos/{fec}', [OrdenesDespachoInternoController::class, 'listarDespachosInternos']);
				Route::get('subirPrioridad/{id}', [OrdenesDespachoInternoController::class, 'subirPrioridad']);
				Route::get('bajarPrioridad/{id}', [OrdenesDespachoInternoController::class, 'bajarPrioridad']);
				Route::get('pasarProgramadasAlDiaSiguiente/{fec}', [OrdenesDespachoInternoController::class, 'pasarProgramadasAlDiaSiguiente']);
				Route::get('listarPendientesAnteriores/{fec}', [OrdenesDespachoInternoController::class, 'listarPendientesAnteriores']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion']);
				Route::post('cambiaEstado', [OrdenesDespachoInternoController::class, 'cambiaEstado']);
			});

			Route::group(['as' => 'gestion-customizaciones.', 'prefix' => 'gestion-customizaciones'], function () {
				//Transformaciones
				Route::get('index', [TransformacionController::class, 'view_listar_transformaciones'])->name('index');
				Route::get('listarTransformacionesProcesadas', [TransformacionController::class, 'listarTransformacionesProcesadas']);
				Route::post('listar_transformaciones_pendientes', [TransformacionController::class, 'listar_transformaciones_pendientes']);
				Route::post('listarCuadrosCostos', [TransformacionController::class, 'listarCuadrosCostos']);
				Route::post('generarTransformacion', [TransformacionController::class, 'generarTransformacion']);
				Route::get('obtenerCuadro/{id}/{tipo}', [TransformacionController::class, 'obtenerCuadro']);
				Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods']);
				Route::get('id_ingreso_transformacion/{id}', [TransformacionController::class, 'id_ingreso_transformacion']);
				Route::get('id_salida_transformacion/{id}', [TransformacionController::class, 'id_salida_transformacion']);
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso']);
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion']);
				Route::get('recibido_conforme_transformacion/{id}', [TransformacionController::class, 'recibido_conforme_transformacion']);
				Route::get('no_conforme_transformacion/{id}', [TransformacionController::class, 'no_conforme_transformacion']);
				Route::get('iniciar_transformacion/{id}', [TransformacionController::class, 'iniciar_transformacion']);
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class,'obtenerArchivosOc'])->name('obtener-archivos-oc');
			});

			Route::group(['as' => 'hoja-transformacion.', 'prefix' => 'hoja-transformacion'], function () {
				//Transformaciones
				Route::get('index', [TransformacionController::class,'view_transformacion'])->name('index');
				Route::post('guardar_transformacion', [TransformacionController::class,'guardar_transformacion']);
				Route::post('update_transformacion', [TransformacionController::class,'update_transformacion']);
				Route::get('listar_transformaciones/{tp}', [TransformacionController::class,'listar_transformaciones']);
				Route::get('mostrar_transformacion/{id}', [TransformacionController::class,'mostrar_transformacion']);
				Route::get('anular_transformacion/{id}', [TransformacionController::class,'anular_transformacion']);
				Route::get('listar_materias/{id}', [TransformacionController::class,'listar_materias']);
				Route::get('listar_directos/{id}', [TransformacionController::class,'listar_directos']);
				Route::get('listar_indirectos/{id}', [TransformacionController::class,'listar_indirectos']);
				Route::get('listar_sobrantes/{id}', [TransformacionController::class,'listar_sobrantes']);
				Route::get('listar_transformados/{id}', [TransformacionController::class,'listar_transformados']);
				Route::get('iniciar_transformacion/{id}', [TransformacionController::class,'iniciar_transformacion']);
				Route::post('procesar_transformacion', [TransformacionController::class,'procesar_transformacion']);
				Route::post('guardar_materia', [TransformacionController::class,'guardar_materia']);
				Route::post('guardar_directo', [TransformacionController::class,'guardar_directo']);
				Route::post('guardar_indirecto', [TransformacionController::class,'guardar_indirecto']);
				Route::post('guardar_sobrante', [TransformacionController::class,'guardar_sobrante']);
				Route::post('guardar_transformado', [TransformacionController::class,'guardar_transformado']);
				Route::get('anular_materia/{id}', [TransformacionController::class,'anular_materia']);
				Route::get('anular_directo/{id}', [TransformacionController::class,'anular_directo']);
				Route::get('anular_indirecto/{id}', [TransformacionController::class,'anular_indirecto']);
				Route::get('anular_sobrante/{id}', [TransformacionController::class,'anular_sobrante']);
				Route::get('anular_transformado/{id}', [TransformacionController::class,'anular_transformado']);
				Route::get('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::post('guardar_producto', [ProductoController::class,'guardar_producto']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class,'imprimir_transformacion']);
			});
		});

		Route::group(['as' => 'garantias.', 'prefix' => 'garantias'], function () {
			Route::group(['as' => 'incidencias.', 'prefix' => 'incidencias'], function () {
				Route::get('index', [IncidenciaController::class,'view_incidencia'])->name('index');
				Route::get('listarIncidencias', [IncidenciaController::class,'listarIncidencias']);
				Route::get('mostrarIncidencia/{id}', [IncidenciaController::class,'mostrarIncidencia']);
				Route::get('listarSalidasVenta', [IncidenciaController::class,'listarSalidasVenta']);

				Route::post('verDatosContacto', [OrdenesDespachoExternoController::class,'verDatosContacto']);
				Route::get('listarContactos/{id}', [OrdenesDespachoExternoController::class,'listarContactos']);
				Route::post('actualizaDatosContacto', [OrdenesDespachoExternoController::class,'actualizaDatosContacto']);
				Route::get('seleccionarContacto/{id}/{req}', [OrdenesDespachoExternoController::class,'seleccionarContacto']);
				Route::get('mostrarContacto/{id}', [OrdenesDespachoExternoController::class,'mostrarContacto']);
				Route::get('anularContacto/{id}', [OrdenesDespachoExternoController::class,'anularContacto']);
				Route::get('listar_ubigeos', [AlmacenController::class,'listar_ubigeos']);

				Route::get('listarSeriesProductos/{id}', [IncidenciaController::class,'listarSeriesProductos']);
				Route::post('guardarIncidencia', [IncidenciaController::class,'guardarIncidencia']);
				Route::post('actualizarIncidencia', [IncidenciaController::class,'actualizarIncidencia']);
				Route::get('anularIncidencia/{id}', [IncidenciaController::class,'anularIncidencia']);

				Route::get('imprimirIncidencia/{id}', [IncidenciaController::class,'imprimirIncidencia']);
				Route::get('imprimirFichaAtencionBlanco/{id}', [IncidenciaController::class,'imprimirFichaAtencionBlanco']);

			});

			Route::group(['as' => 'devolucionCas.', 'prefix' => 'devolucionCas'], function () {
				//Devoluciones
				Route::get('index', [DevolucionController::class,'viewDevolucionCas'])->name('index');
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

			Route::group(['as' => 'fichas.', 'prefix' => 'fichas'], function () {
				Route::get('index', [FichaReporteController::class,'view_ficha_reporte'])->name('index');
				Route::post('listarIncidencias', [FichaReporteController::class,'listarIncidencias']);
				Route::post('guardarFichaReporte', [FichaReporteController::class,'guardarFichaReporte']);
				Route::post('actualizarFichaReporte', [FichaReporteController::class,'actualizarFichaReporte']);
				Route::get('anularFichaReporte/{id}', [FichaReporteController::class,'anularFichaReporte']);
				Route::get('listarFichasReporte/{id}', [FichaReporteController::class,'listarFichasReporte']);
				Route::post('cerrarIncidencia', [FichaReporteController::class,'cerrarIncidencia']);
				Route::post('cancelarIncidencia', [FichaReporteController::class,'cancelarIncidencia']);

				Route::get('verAdjuntosFicha/{id}', [FichaReporteController::class,'verAdjuntosFicha'])->name('ver-adjuntos-ficha');

				Route::get('imprimirFichaReporte/{id}', [FichaReporteController::class,'imprimirFichaReporte']);
				Route::get('incidenciasExcel', [FichaReporteController::class,'incidenciasExcel'])->name('incidenciasExcel');
				Route::get('incidenciasExcelConHistorial', [FichaReporteController::class,'incidenciasExcelConHistorial'])->name('incidenciasExcelConHistorial');

				Route::get('listarDevoluciones', [DevolucionController::class,'listarDevoluciones']);
				Route::post('guardarFichaTecnica', [DevolucionController::class,'guardarFichaTecnica']);
				Route::get('verFichasTecnicasAdjuntas/{id}', [DevolucionController::class,'verFichasTecnicasAdjuntas'])->name('ver-fichas-tecnicas');
				Route::post('conformidadDevolucion', [DevolucionController::class,'conformidadDevolucion'])->name('conformidad-devolucion');
				Route::get('revertirConformidad/{id}', [DevolucionController::class,'revertirConformidad'])->name('revertir-devolucion');

                Route::post('clonarIncidencia', [FichaReporteController::class,'clonarIncidencia']);
			});
            Route::group(['as' => 'marca.', 'prefix' => 'marca'], function () {
                Route::get('inicio', [CasMarcaController::class,'inicio'])->name('inicio');
                Route::post('listar', [CasMarcaController::class,'listar'])->name('listar');
                Route::post('guardar', [CasMarcaController::class,'guardar'])->name('guardar');
                Route::get('editar', [CasMarcaController::class,'editar'])->name('editar');
                Route::post('actualizar', [CasMarcaController::class,'actualizar'])->name('actualizar');
                Route::post('eliminar', [CasMarcaController::class,'eliminar'])->name('eliminar');
			});

            Route::group(['as' => 'modelo.', 'prefix' => 'modelo'], function () {
                Route::get('inicio', [CasModeloController::class,'inicio'])->name('inicio');
                Route::post('listar', [CasModeloController::class,'listar'])->name('listar');
                Route::post('guardar', [CasModeloController::class,'guardar'])->name('guardar');
                Route::get('editar', [CasModeloController::class,'editar'])->name('editar');
                Route::post('actualizar', [CasModeloController::class,'actualizar'])->name('actualizar');
                Route::post('eliminar', [CasModeloController::class,'eliminar'])->name('eliminar');
			});

            Route::group(['as' => 'producto.', 'prefix' => 'producto'], function () {
                Route::get('inicio', [CasProductoController::class,'inicio'])->name('inicio');
                Route::post('listar', [CasProductoController::class,'listar'])->name('listar');
                Route::post('guardar', [CasProductoController::class,'guardar'])->name('guardar');
                Route::get('editar', [CasProductoController::class,'editar'])->name('editar');
                Route::post('actualizar', [CasProductoController::class,'actualizar'])->name('actualizar');
                Route::post('eliminar', [CasProductoController::class,'eliminar'])->name('eliminar');
			});

		});
	});

    /**
	 * Finanzas
	 */
    Route::group(['as' => 'finanzas.', 'prefix' => 'finanzas'], function () {
		// Finanzas
		Route::get('index', function () {
			return view('finanzas.main');
		})->name('index');

		Route::group(['as' => 'lista-presupuestos.', 'prefix' => 'lista-presupuestos'], function () {
			// Lista de Presupuestos
			Route::get('index', [PresupuestoController::class,'index'])->name('index');
			Route::get('actualizarPartidas', [PartidaController::class,'actualizarPartidas'])->name('actualizar-partidas');
		});

		Route::group(['as' => 'presupuesto.', 'prefix' => 'presupuesto'], function () {
			// Presupuesto
			Route::get('create', [PresupuestoController::class,'create'])->name('index');
			Route::get('mostrarPartidas/{id}', [PresupuestoController::class,'mostrarPartidas'])->name('mostrar-partidas');
			Route::get('mostrarRequerimientosDetalle/{id}', [PresupuestoController::class,'mostrarRequerimientosDetalle'])->name('mostrar-requerimientos-detalle');
			Route::post('guardar-presupuesto', [PresupuestoController::class,'store'])->name('guardar-presupuesto');
			Route::post('actualizar-presupuesto', [PresupuestoController::class,'update'])->name('actualizar-presupuesto');

			Route::post('guardar-titulo', [TituloController::class,'store'])->name('guardar-titulo');
			Route::post('actualizar-titulo', [TituloController::class,'update'])->name('actualizar-titulo');
			Route::get('anular-titulo/{id}', [TituloController::class,'destroy'])->name('anular-titulo');

			Route::post('guardar-partida', [PartidaController::class,'store'])->name('guardar-partida');
			Route::post('actualizar-partida', [PartidaController::class,'update'])->name('actualizar-partida');
			Route::get('anular-partida/{id}', [PartidaController::class,'destroy'])->name('anular-partida');

			Route::get('mostrarGastosPorPresupuesto/{id}', [PresupuestoController::class,'mostrarGastosPorPresupuesto'])->name('mostrar-gastos-presupuesto');
			Route::post('cuadroGastosExcel', [PresupuestoController::class,'cuadroGastosExcel'])->name('cuadroGastosExcel');

            Route::group(['as' => 'presupuesto-interno.', 'prefix' => 'presupuesto-interno'], function () {
                //Presupuesto interno
                Route::get('lista', [PresupuestoInternoController::class,'lista'])->name('lista');
                Route::post('lista-presupuesto-interno', [PresupuestoInternoController::class,'listaPresupuestoInterno'])->name('lista-presupuesto-interno');
                Route::get('crear', [PresupuestoInternoController::class,'crear'])->name('crear');

                Route::get('presupuesto-interno-detalle', [PresupuestoInternoController::class,'presupuestoInternoDetalle'])->name('presupuesto-interno-detalle');
                Route::post('guardar', [PresupuestoInternoController::class,'guardar'])->name('guardar');

                Route::post('editar', [PresupuestoInternoController::class,'editar'])->name('editar');
                Route::post('editar-presupuesto-aprobado', [PresupuestoInternoController::class,'editarPresupuestoAprobado'])->name('editar-presupuesto-aprobado');
                Route::post('actualizar', [PresupuestoInternoController::class,'actualizar'])->name('actualizar');
                Route::post('eliminar', [PresupuestoInternoController::class,'eliminar'])->name('eliminar');

                Route::get('get-area', [PresupuestoInternoController::class,'getArea']);
                // exportable de presupiesto interno
                Route::post('get-presupuesto-interno', [PresupuestoInternoController::class,'getPresupuestoInterno']);

                //exportable de excel total ejecutado
                Route::post('presupuesto-ejecutado-excel', [PresupuestoInternoController::class,'presupuestoEjecutadoExcel']);

                Route::post('aprobar', [PresupuestoInternoController::class,'aprobar']);
                Route::post('editar-monto-partida', [PresupuestoInternoController::class,'editarMontoPartida']);
                // buscar partidas
                Route::post('buscar-partida-combo', [PresupuestoInternoController::class,'buscarPartidaCombo']);
                // prueba de presupuestos
				Route::get('cierre-mes', [PresupuestoInternoController::class,'cierreMes']);

				Route::get('listar-sedes-por-empresa/{id}', [PresupuestoInternoController::class,'listarSedesPorEmpresa']);

                Route::group(['as' => 'script.', 'prefix' => 'script'], function () {
                    Route::get('generar-presupuesto-gastos', [ScriptController::class,'generarPresupuestoGastos']);
                    Route::get('homologacion-partidas', [ScriptController::class,'homologarPartida']);
                    Route::get('total-presupuesto/{presup}/{tipo}', [ScriptController::class,'totalPresupuesto']);
                    Route::get('total-consumido-mes/{presup}/{tipo}/{mes}', [ScriptController::class,'totalConsumidoMes']);
                    Route::get('total-ejecutado', [ScriptController::class,'totalEjecutado']);
                    Route::get('regularizar-montos', [ScriptController::class,'montosRegular']);

                    Route::get('total-presupuesto-anual-niveles/{presupuesto_intero_id}/{tipo}/{nivel}/{tipo_campo}', [ScriptController::class,'totalPresupuestoAnualPartidasNiveles']);
                });
				Route::get('actualizaEstadoHistorial/{id}/{est}', [PresupuestoInternoController::class,'actualizaEstadoHistorial']);
            });

            Route::group(['as' => 'normalizar.', 'prefix' => 'normalizar'], function () {
                Route::get('presupuesto', [NormalizarController::class,'lista'])->name('presupuesto');
                Route::get('listar', [NormalizarController::class,'listar'])->name('listar');
                Route::post('listar-requerimientos-pagos', [NormalizarController::class,'listarRequerimientosPagos'])->name('listar-requerimientos-pagos');
                Route::post('listar-ordenes', [NormalizarController::class,'listarOrdenes'])->name('listar-ordenes');
                Route::post('obtener-presupuesto', [NormalizarController::class,'obtenerPresupuesto'])->name('obtener-presupuesto');
                Route::post('vincular-partida', [NormalizarController::class,'vincularPartida'])->name('vincular-partida');
                Route::get('detalle-requerimiento-pago/{id}', [NormalizarController::class,'detalleRequerimientoPago'])->name('detalle-requerimiento-pago');

            });
		});

		Route::group(['as' => 'centro-costos.', 'prefix' => 'centro-costos'], function () {
			//Centro de Costos
			Route::get('index', [CentroCostoController::class,'index'])->name('index');
			Route::get('mostrar-centro-costos', [CentroCostoController::class,'mostrarCentroCostos'])->name('mostrar-centro-costos');
			Route::post('guardarCentroCosto', [CentroCostoController::class,'guardarCentroCosto'])->name('guardar-centro-costo');
			Route::post('actualizar-centro-costo', [CentroCostoController::class,'actualizarCentroCosto'])->name('actualizar-centro-costo');
			Route::get('anular-centro-costo/{id}', [CentroCostoController::class,'anularCentroCosto'])->name('anular-centro-costo');
		});


		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {
			Route::group(['as' => 'gastos.', 'prefix' => 'gastos'], function () {
				Route::get('index-requerimiento-logistico', [ReporteGastoController::class,'indexReporteGastoRequerimientoLogistico'])->name('index-requerimiento-logistico');
				Route::get('index-requerimiento-pago', [ReporteGastoController::class,'indexReporteGastoRequerimientoPago'])->name('index-requerimiento-pago');
				Route::get('index-cdp', [ReporteGastoController::class,'indexReporteGastoCDP'])->name('index-cdp');

				Route::post('lista-requerimiento-logistico', [ReporteGastoController::class,'listaGastoDetalleRequerimientoLogistico'])->name('lista-requerimiento-logistico');
				Route::post('lista-requerimiento-pago', [ReporteGastoController::class,'listaGastoDetalleRequerimientoPago'])->name('lista-requerimiento-pago');
				Route::post('lista-cdp', [ReporteGastoController::class,'listaGastoCDP'])->name('lista-cdp');

				Route::get('exportar-requerimiento-logistico-excel', [ReporteGastoController::class,'listaGastoDetalleRequerimientoLogisticoExcel']);
				Route::get('exportar-requerimiento-pago-excel', [ReporteGastoController::class,'listaGastoDetalleRequerimienoPagoExcel']);
				Route::get('exportar-cdp-excel', [ReporteGastoController::class,'listaGastoCDPExcel']);

			});
		});
	});
});
