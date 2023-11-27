<?php

use App\Exports\CatalogoProductoExport;
use App\Http\Controllers\AdministracionController;
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
use App\Http\Controllers\ApiController;
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
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\Finanzas\CentroCosto\CentroCostoController;
use App\Http\Controllers\Finanzas\Normalizar\NormalizarController;
use App\Http\Controllers\Finanzas\Presupuesto\PartidaController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoController;
use App\Http\Controllers\Finanzas\Presupuesto\PresupuestoInternoController;
use App\Http\Controllers\Finanzas\Presupuesto\ScriptController;
use App\Http\Controllers\Finanzas\Presupuesto\TituloController;
use App\Http\Controllers\Finanzas\Reportes\ReporteGastoController;
use App\Http\Controllers\Gerencial\Cobranza\ClienteController as CobranzaClienteController;
use App\Http\Controllers\Gerencial\Cobranza\CobranzaController;
use App\Http\Controllers\Gerencial\Cobranza\CobranzaFondoController;
use App\Http\Controllers\Gerencial\Cobranza\DevolucionPenalidadController;
use App\Http\Controllers\Gerencial\Cobranza\RegistroController;
use App\Http\Controllers\Gerencial\GerencialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HynoTechController;
use App\Http\Controllers\Logistica\Distribucion\DistribucionController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoExternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesDespachoInternoController;
use App\Http\Controllers\Logistica\Distribucion\OrdenesTransformacionController;
use App\Http\Controllers\Logistica\Distribucion\ProgramacionDespachosController;
use App\Http\Controllers\Logistica\ProveedoresController;
use App\Http\Controllers\Logistica\RequerimientoController;
use App\Http\Controllers\Logistica\Requerimientos\MapeoProductosController;
use App\Http\Controllers\Logistica\Requerimientos\TrazabilidadRequerimientoController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\Migraciones\MigracionAlmacenSoftLinkController;
use App\Http\Controllers\Migraciones\MigrateFacturasSoftlinkController;
use App\Http\Controllers\Migraciones\MigrateOrdenSoftLinkController;
use App\Http\Controllers\Migraciones\MigrateProductoSoftlinkController;
use App\Http\Controllers\Migraciones\MigrateRequerimientoSoftLinkController;
use App\Http\Controllers\NecesidadesController;
use App\Http\Controllers\Notificaciones\NotificacionController;
use App\Http\Controllers\OCAMController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\Proyectos\Catalogos\AcuController;
use App\Http\Controllers\Proyectos\Catalogos\InsumoController;
use App\Http\Controllers\Proyectos\Catalogos\NombresAcuController;
use App\Http\Controllers\Proyectos\Opciones\ComponentesController;
use App\Http\Controllers\Proyectos\Opciones\CronogramaInternoController;
use App\Http\Controllers\Proyectos\Opciones\CronogramaValorizadoInternoController;
use App\Http\Controllers\Proyectos\Opciones\OpcionesController;
use App\Http\Controllers\Proyectos\Opciones\PartidasController;
use App\Http\Controllers\Proyectos\Opciones\PresupuestoInternoController as OpcionesPresupuestoInternoController;
use App\Http\Controllers\Proyectos\Variables\CategoriaAcuController;
use App\Http\Controllers\Proyectos\Variables\CategoriaInsumoController;
use App\Http\Controllers\Proyectos\Variables\IuController;
use App\Http\Controllers\Proyectos\Variables\SistemasContratoController;
use App\Http\Controllers\Proyectos\Variables\TipoInsumoController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\ReporteLogisticaController;
use App\Http\Controllers\RequerimientoController as ControllersRequerimientoController;
use App\Http\Controllers\RevisarAprobarController;
use App\Http\Controllers\Tesoreria\CierreAperturaController;
use App\Http\Controllers\Tesoreria\Facturacion\PendientesFacturacionController;
use App\Http\Controllers\Tesoreria\Facturacion\VentasInternasController;
use App\Http\Controllers\Tesoreria\RegistroPagoController;
use App\Http\Controllers\Tesoreria\RequerimientoPagoController;
use App\Http\Controllers\Tesoreria\TipoCambioController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\ApiController;
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
Route::get('test-encrypt', [TestController::class, 'encriptar'])->name('test-encrypt');
Route::get('test-lista-cliente', [TestController::class, 'clientes'])->name('test-lista-cliente');
// Route::get('test-ordenes-compra', [ReporteLogisticaController::class, 'listaOrdenesCompra'])->name('test-ordenes-compra');
// Route::get('test-ordenes-servicio', [ReporteLogisticaController::class, 'listaOrdenesServicio'])->name('test-ordenes-servicio');
Route::get('test-inicial-clave', [TestController::class, 'cargarClaves'])->name('test-lista-cliente');

Route::middleware(['auth'])->group(function () {
	Route::get('cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');
	Route::get('inicio', [HomeController::class, 'index'])->name('inicio');
	Route::get('validar-clave',  [UsuarioController::class, 'validarClave'])->name('validar-clave');
	Route::post('actualizar-clave',  [UsuarioController::class, 'modificarClave'])->name('actualizar-clave');
	Route::post('consulta_sunat', [HynoTechController::class, 'consulta_sunat'])->name('consulta_sunat');

	Route::get('cargar_departamento', [ConfiguracionController::class, 'select_departamento'])->name('cargar_departamento');
	Route::get('cargar_provincia/{id}', [ConfiguracionController::class, 'select_prov_dep'])->name('cargar_provincia');
	Route::get('cargar_distrito/{id}', [ConfiguracionController::class, 'select_dist_prov'])->name('cargar_distrito');
	Route::get('cargar_estructura_org/{id}', [ConfiguracionController::class, 'cargar_estructura_org'])->name('cargar_estructura_org');
	Route::get('migrar_orden_compra/{id}', [MigrateOrdenSoftLinkController::class, 'migrarOrdenCompra'])->name('migrar-orden-compra');
	Route::get('migrar_venta_directa/{id}', [MigrateRequerimientoSoftLinkController::class, 'migrar_venta_directa'])->name('migrar-venta-directa');
	// Route::get('anular_presup', 'ProyectosController@anular_presup');
	// Route::get('listarUsu', 'Almacen\Movimiento\TransferenciaController@listarUsu');

	/**
	 * Configuración
	 */
	Route::name('configuracion.')->prefix('configuracion')->group(function () {
		Route::get('dashboard', [ConfiguracionController::class, 'view_main_configuracion'])->name('dashboard');

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
				Route::post('ver/guardar-accesos', [ConfiguracionController::class, 'guardarAccesos'])->name('guardar-accesos');
			});
		});

		Route::group(['as' => 'modulo.', 'prefix' => 'modulo'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_modulos'])->name('index');
			Route::get('listar_modulo', [ConfiguracionController::class, 'mostrar_modulo_table'])->name('listar_modulo');
			Route::get('combo_modulos', [ConfiguracionController::class, 'mostrar_modulos_combo'])->name('combo_modulos');
			Route::get('cargar_modulo/{id}', [ConfiguracionController::class, 'mostrar_modulo_id'])->name('cargar_modulo');
			Route::post('guardar_modulo', [ConfiguracionController::class, 'guardar_modulo'])->name('guardar_modulo');
			Route::post('editar_modulo', [ConfiguracionController::class, 'actualizar_modulo'])->name('editar_modulo');
			Route::get('anular_modulo/{id}', [ConfiguracionController::class, 'anular_modulo'])->name('anular_modulo');
		});

		Route::group(['as' => 'aplicaciones.', 'prefix' => 'aplicaciones'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_aplicaciones'])->name('index');
			Route::get('cargar_submodulos/{id}', [ConfiguracionController::class, 'mostrar_submodulo_id'])->name('cargar_submodulos');
			Route::get('listar_aplicaciones', [ConfiguracionController::class, 'mostrar_aplicaciones_table'])->name('listar_aplicaciones');
			Route::get('cargar_aplicaciones/{id}', [ConfiguracionController::class, 'mostrar_aplicaciones_id'])->name('cargar_aplicaciones');
			Route::post('guardar_aplicaciones', [ConfiguracionController::class, 'guardar_aplicaciones'])->name('guardar_aplicaciones');
			Route::post('editar_aplicaciones', [ConfiguracionController::class, 'actualizar_aplicaciones'])->name('editar_aplicaciones');
			Route::get('anular_aplicaciones/{id}', [ConfiguracionController::class, 'anular_aplicaciones'])->name('anular_aplicaciones');
		});

		Route::group(['as' => 'correos.', 'prefix' => 'correos'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_correo_coorporativo'])->name('index');
			Route::get('mostrar_correo_coorporativo/{id}', [ConfiguracionController::class, 'mostrar_correo_coorporativo'])->name('mostrar_correo_coorporativo');
			Route::put('actualizar_correo_coorporativo', [ConfiguracionController::class, 'actualizar_correo_coorporativo'])->name('actualizar_correo_coorporativo');
			Route::post('guardar_correo_coorporativo', [ConfiguracionController::class, 'guardar_correo_coorporativo'])->name('guardar_correo_coorporativo');
			Route::delete('anular_correo_coorporativo/{id}', [ConfiguracionController::class, 'anular_correo_coorporativo'])->name('anular_correo_coorporativo');
		});

		Route::group(['as' => 'notas-lanzamiento.', 'prefix' => 'notas-lanzamiento'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_notas_lanzamiento'])->name('index');
			Route::get('mostrar', [ConfiguracionController::class, 'mostrar_notas_lanzamiento_select'])->name('mostrar');
			Route::get('listar_detalle/{id}', [ConfiguracionController::class, 'mostrar_detalle_notas_lanzamiento_table'])->name('listar_detalle');

			Route::put('actualizar_nota_lanzamiento', [ConfiguracionController::class, 'updateNotaLanzamiento'])->name('actualizar_nota_lanzamiento');
			Route::post('guardar_nota_lanzamiento', [ConfiguracionController::class, 'guardarNotaLanzamiento'])->name('guardar_nota_lanzamiento');
			Route::put('eliminar_nota_lanzamiento/{id_nota}', [ConfiguracionController::class, 'eliminarNotaLanzamiento'])->name('eliminar_nota_lanzamiento');
			Route::get('mostrar_detalle_nota_lanzamiento/{id}', [ConfiguracionController::class, 'mostrar_detalle_nota_lanzamiento'])->name('mostrar_detalle_nota_lanzamiento');
			Route::post('guardar_detalle_nota_lanzamiento', [ConfiguracionController::class, 'guardarDetalleNotaLanzamiento'])->name('guardar_detalle_nota_lanzamiento');
			Route::put('actualizar_detalle_nota_lanzamiento', [ConfiguracionController::class, 'updateDetalleNotaLanzamiento'])->name('actualizar_detalle_nota_lanzamiento');
			Route::put('eliminar_detalle_nota_lanzamiento/{id_detalle_nota}', [ConfiguracionController::class, 'eliminarDetalleNotaLanzamiento'])->name('eliminar_detalle_nota_lanzamiento');
		});

		Route::group(['as' => 'documentos.', 'prefix' => 'documentos'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_docuemtos'])->name('index');
			Route::get('listar-documentos', [ConfiguracionController::class, 'mostrar_documento_table'])->name('listar-documentos');
			Route::get('cargar-documento/{id}', [ConfiguracionController::class, 'mostrar_documento_id'])->name('cargar-documento');
			Route::post('guardar-documento', [ConfiguracionController::class, 'guardar_documento'])->name('guardar-documento');
			Route::post('actualizar-documento', [ConfiguracionController::class, 'actualizar_documento'])->name('actualizar-documento');
			Route::get('anular-documento/{id}', [ConfiguracionController::class, 'anular_documento'])->name('anular-documento');
		});

		Route::group(['as' => 'roles.', 'prefix' => 'roles'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_roles'])->name('index');
			Route::get('listar', [ConfiguracionController::class, 'mostrar_roles_table'])->name('listar');
			Route::get('cargar-roles/{id}', [ConfiguracionController::class, 'mostrar_roles_id'])->name('cargar-roles');
			Route::post('guardar_rol', [ConfiguracionController::class, 'guardar_rol'])->name('guardar_rol');
			Route::post('editar_rol', [ConfiguracionController::class, 'actualizar_rol'])->name('editar_rol');
			Route::get('anular_rol/{id}', [ConfiguracionController::class, 'anular_rol'])->name('anular_rol');
		});

		Route::group(['as' => 'historial-aprobaciones.', 'prefix' => 'historial-aprobaciones'], function () {
			Route::get('index', [ConfiguracionController::class, 'view_historial_aprobaciones'])->name('index');
			Route::get('listar', [ConfiguracionController::class, 'mostrar_historial_aprobacion'])->name('listar');
		});

		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {
			Route::group(['as' => 'log-actividad.', 'prefix' => 'log-actividad'], function () {
				Route::get('index', [ConfiguracionController::class, 'view_log_actividad'])->name('index');
				Route::get('listar', [ConfiguracionController::class, 'listar_log_actividad'])->name('listar');
			});
		});
	});

	/**
	 * Administración
	 */
	Route::name('administracion.')->prefix('administracion')->group(function () {
		Route::get('index', [AdministracionController::class, 'view_main_administracion'])->name('index');

		Route::name('empresas.')->prefix('empresas')->group(function () {
			Route::get('index', [AdministracionController::class, 'view_empresa'])->name('index');
			Route::get('listar_empresa', [AdministracionController::class, 'mostrar_empresa_table'])->name('listar_empresa');
			Route::get('cargar_empresa/{id}', [AdministracionController::class, 'mostrar_empresa_id'])->name('cargar_empresa');
			Route::post('guardar_empresa_contri', [AdministracionController::class, 'guardar_empresas'])->name('guardar_empresa_contri');
			Route::post('editar_empresa_contri', [AdministracionController::class, 'actualizar_empresas'])->name('editar_empresa_contri');

			Route::get('listar_contacto_empresa/{id}', [AdministracionController::class, 'mostrar_contacto_empresa'])->name('listar_contacto_empresa');
			Route::post('guardar_contacto_empresa', [AdministracionController::class, 'guardar_contacto_empresa'])->name('guardar_contacto_empresa');
			Route::post('editar_contacto_empresa', [AdministracionController::class, 'actualizar_contacto_empresa'])->name('editar_contacto_empresa');

			Route::get('listar_cuentas_empresa/{id}', [AdministracionController::class, 'mostrar_cuentas_empresa'])->name('listar_cuentas_empresa');
			Route::post('guardar_cuentas_empresa', [AdministracionController::class, 'guardar_cuentas_empresa'])->name('guardar_cuentas_empresa');
			Route::post('editar_cuentas_empresa', [AdministracionController::class, 'actualizar_cuentas_empresa'])->name('editar_cuentas_empresa');
		});

		Route::name('sedes.')->prefix('sedes')->group(function () {
			Route::get('index', [AdministracionController::class, 'view_sede'])->name('index');
			Route::get('listar_sede', [AdministracionController::class, 'mostrar_sede_table'])->name('listar_sede');
			Route::get('buscar_codigo_empresa/{value}/{type}', [AdministracionController::class, 'codigoEmpresa'])->name('buscar_codigo_empresa');
			Route::get('cargar_sede/{id}', [AdministracionController::class, 'mostrar_sede_id'])->name('cargar_sede');
			Route::post('guardar_sede', [AdministracionController::class, 'guardar_sede'])->name('guardar_sede');
			Route::post('editar_sede', [AdministracionController::class, 'actualizar_sede'])->name('editar_sede');
			Route::get('anular_sede/{id}', [AdministracionController::class, 'anular_sede'])->name('anular_sede');
		});

		Route::name('grupos.')->prefix('grupos')->group(function () {
			Route::get('index', [AdministracionController::class, 'view_grupo'])->name('index');
			Route::get('listar_grupo', [AdministracionController::class, 'mostrar_grupo_table'])->name('listar_grupo');
			Route::get('cargar_grupo/{id}', [AdministracionController::class, 'mostrar_grupo_id'])->name('cargar_grupo');
			Route::post('guardar_grupo', [AdministracionController::class, 'guardar_grupo'])->name('guardar_grupo');
			Route::post('editar_grupo', [AdministracionController::class, 'actualizar_grupo'])->name('editar_grupo');
			Route::get('anular_grupo/{id}', [AdministracionController::class, 'anular_grupo'])->name('anular_grupo');
			Route::get('combo_sede_empresa/{value}', [AdministracionController::class, 'combo_sede_empresa'])->name('combo_sede_empresa');
		});

		Route::name('areas.')->prefix('areas')->group(function () {
			Route::get('index', [AdministracionController::class, 'view_area'])->name('index');
			Route::get('listar_area', [AdministracionController::class, 'mostrar_area_table'])->name('listar_area');
			Route::get('cargar_area/{id}', [AdministracionController::class, 'mostrar_area_id'])->name('cargar_area');
			Route::post('guardar_area', [AdministracionController::class, 'guardar_area'])->name('guardar_area');
			Route::post('editar_area', [AdministracionController::class, 'actualizar_area'])->name('editar_area');
			Route::get('anular_area/{id}', [AdministracionController::class, 'anular_area'])->name('anular_area');
			Route::get('combo_grupo_sede/{value}', [AdministracionController::class, 'combo_grupo_sede'])->name('combo_grupo_sede');
		});
	});

	/**
	 * Notificaciones
	 */
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
				Route::get('imprimir-requerimiento-pdf/{id}/{codigo}', [RequerimientoController::class, 'generar_requerimiento_pdf'])->name('imprimir-requerimiento-pdf');
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
				Route::post('guardar-archivos-adjuntos-requerimiento', [LogisticaController::class, 'guardar_archivos_adjuntos_requerimiento'])->name('guardar-archivos-adjuntos-requerimiento');
				Route::put('eliminar-archivo-adjunto-requerimiento/{id_archivo}', [LogisticaController::class, 'eliminar_archivo_adjunto_requerimiento'])->name('eliminar-archivo-adjunto-requerimiento');
				Route::get('mostrar-archivos-adjuntos-requerimiento/{id_requerimiento?}/{categoria?}', [RequerimientoController::class, 'mostrarArchivosAdjuntosRequerimiento'])->name('mostrar-archivos-adjuntos-requerimiento');
				Route::get('listar_almacenes', [AlmacenController::class, 'mostrar_almacenes'])->name('listar-almacenes');
				Route::get('mostrar-sede', [ConfiguracionController::class, 'mostrarSede'])->name('mostrar-sede');
				Route::get('mostrar_proveedores', [LogisticaController::class, 'mostrar_proveedores'])->name('mostrar-proveedores');
				Route::post('guardar_proveedor', [LogisticaController::class, 'guardar_proveedor'])->name('guardar-proveedor');
				Route::get('getCodigoRequerimiento/{id}', [LogisticaController::class, 'getCodigoRequerimiento'])->name('getCodigoRequerimiento');
				Route::get('mostrar-archivos-adjuntos/{id_detalle_requerimiento}', [RequerimientoController::class, 'mostrarArchivosAdjuntos'])->name('mostrar-archivos-adjuntos');
				Route::post('save_cliente', [LogisticaController::class, 'save_cliente'])->name('save-cliente');
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
				Route::get('obtener-detalle-presupuesto-interno/{idPresupuesto?}', [PresupuestoInternoController::class, 'obtenerDetallePresupuestoInterno'])->name('obtener-detalle-presupuesto-interno');
				Route::get('obtener-lista-proyectos/{idGrupo?}', [RequerimientoController::class, 'obtenerListaProyectos'])->name('obtener-lista-proyectos');
				Route::post('obtener-requerimientos-vinculados-con-partida', [RequerimientoController::class, 'obtenerRequerimientosVinculadosConPartida'])->name('obtener-requerimientos-vinculados-con-partida');
				Route::get('obtener-items-requerimiento-con-partida-presupuesto-interno/{idRequerimiento?}/{idPartida?}', [RequerimientoController::class, 'obteneritemsRequerimientoConPartidaDePresupuestoInterno'])->name('obtener-items-requerimiento-con-partida-presupuesto-interno');

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
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
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
				Route::post('requerimiento-sustentado', [RequerimientoController::class, 'requerimientoSustentado'])->name('requerimiento-sustentado');
				Route::post('obtener-requerimientos-vinculados-con-partida', [RequerimientoController::class, 'obtenerRequerimientosVinculadosConPartida'])->name('obtener-requerimientos-vinculados-con-partida');
				Route::get('obtener-items-requerimiento-con-partida-presupuesto-interno/{idRequerimiento?}/{idPartida?}', [RequerimientoController::class, 'obteneritemsRequerimientoConPartidaDePresupuestoInterno'])->name('obtener-items-requerimiento-con-partida-presupuesto-interno');

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
				// Route::get('listado-requerimientos-pagos-export-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoPagoController::class, 'listadoRequerimientoPagoExportExcel'])->name('listado-requerimientos-pagos-export-excel');
				Route::post('listado-requerimientos-pagos-export-excel', [RequerimientoPagoController::class, 'listadoRequerimientoPagoExportExcel'])->name('listado-requerimientos-pagos-export-excel');
				// Route::get('listado-items-requerimientos-pagos-export-excel/{meOrAll}/{Empresa}/{Sede}/{Grupo}/{Division}/{FechaDesde}/{FechaHasta}/{Estado}', [RequerimientoPagoController::class, 'listadoItemsRequerimientoPagoExportExcel'])->name('listado-items-requerimientos-pagos-export-excel');
				Route::post('listado-items-requerimientos-pagos-export-excel', [RequerimientoPagoController::class, 'listadoItemsRequerimientoPagoExportExcel'])->name('listado-items-requerimientos-pagos-export-excel');

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
				Route::post('requerimiento-sustentado', [RequerimientoPagoController::class, 'requerimientoSustentado'])->name('requerimiento-sustentado');
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

				Route::get('listar-actualizacion/{id}', [DevolucionController::class, 'listarActualizacion'])->name('listar-actualizacion');
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

                Route::post('importar-excel-series', [SalidasPendientesController::class, 'importarExcelSeries'])->name('importar-excel-series');


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
			Route::get('anular_doc_com/{id}', [OrdenesPendientesController::class, 'anular_doc_com'])->name('anular-doc-com');

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
				Route::post('listar-ingresos', [ListaIngresosController::class, 'listarIngresos'])->name('listar-ingresos-post');
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
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
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
				Route::get('listar_tp_docs', [AlmacenController::class, 'listar_tp_docs'])->name('listar-tp-docs');
				Route::get('mostrar_tp_doc/{id}', [AlmacenController::class, 'mostrar_tp_doc'])->name('mostrar-tp-doc');
				Route::post('guardar_tp_doc', [AlmacenController::class, 'guardar_tp_doc'])->name('guardar-tp-doc');
				Route::post('update_tp_doc', [AlmacenController::class, 'update_tp_doc'])->name('update-tp-doc');
				Route::get('anular_tp_doc/{id}', [AlmacenController::class, 'anular_tp_doc'])->name('anular-tp-doc');
			});

			Route::group(['as' => 'unidades-medida.', 'prefix' => 'unidades-medida'], function () {

				Route::get('index', [AlmacenController::class, 'view_unid_med'])->name('index');
				Route::get('listar_unidmed', [AlmacenController::class, 'mostrar_unidades_med'])->name('listar-unidmed');
				Route::get('mostrar_unidmed/{id}', [AlmacenController::class, 'mostrar_unid_med'])->name('mostrar-unidmed');
				Route::post('guardar_unidmed', [AlmacenController::class, 'guardar_unid_med'])->name('guardar-unidmed');
				Route::post('actualizar_unidmed', [AlmacenController::class, 'update_unid_med'])->name('actualizar-unidmed');
				Route::get('anular_unidmed/{id}', [AlmacenController::class, 'anular-unidmed'])->name('anular-unidmed');
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
				Route::get('listarDespachosInternos/{fec}', [OrdenesDespachoInternoController::class, 'listarDespachosInternos'])->name('listar-despachos-internos');
				Route::get('subirPrioridad/{id}', [OrdenesDespachoInternoController::class, 'subirPrioridad'])->name('subir-prioridad');
				Route::get('bajarPrioridad/{id}', [OrdenesDespachoInternoController::class, 'bajarPrioridad'])->name('bajar-prioridad');
				Route::get('pasarProgramadasAlDiaSiguiente/{fec}', [OrdenesDespachoInternoController::class, 'pasarProgramadasAlDiaSiguiente'])->name('pasar-programadas-al-dia-siguiente');
				Route::get('listarPendientesAnteriores/{fec}', [OrdenesDespachoInternoController::class, 'listarPendientesAnteriores'])->name('listar-pendientes-anteriores');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
				Route::post('cambiaEstado', [OrdenesDespachoInternoController::class, 'cambiaEstado'])->name('cambia-estado');
			});

			Route::group(['as' => 'gestion-customizaciones.', 'prefix' => 'gestion-customizaciones'], function () {
				//Transformaciones
				Route::get('index', [TransformacionController::class, 'view_listar_transformaciones'])->name('index');
				Route::get('listarTransformacionesProcesadas', [TransformacionController::class, 'listarTransformacionesProcesadas'])->name('listar-transformaciones-procesadas');
				Route::post('listar_transformaciones_pendientes', [TransformacionController::class, 'listar_transformaciones_pendientes'])->name('listar-transformaciones-pendientes');;
				Route::post('listarCuadrosCostos', [TransformacionController::class, 'listarCuadrosCostos'])->name('listar-cuadros-costos');
				Route::post('generarTransformacion', [TransformacionController::class, 'generarTransformacion'])->name('generar-transformacion');
				Route::get('obtenerCuadro/{id}/{tipo}', [TransformacionController::class, 'obtenerCuadro'])->name('obtener-cuadro');
				Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::get('id_ingreso_transformacion/{id}', [TransformacionController::class, 'id_ingreso_transformacion'])->name('id-ingreso-transformacion');
				Route::get('id_salida_transformacion/{id}', [TransformacionController::class, 'id_salida_transformacion'])->name('id-salida-transformacion');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
				Route::get('recibido_conforme_transformacion/{id}', [TransformacionController::class, 'recibido_conforme_transformacion'])->name('recibido-conforme-transformacion');
				Route::get('no_conforme_transformacion/{id}', [TransformacionController::class, 'no_conforme_transformacion'])->name('no-conforme-transformacion');
				Route::get('iniciar_transformacion/{id}', [TransformacionController::class, 'iniciar_transformacion'])->name('iniciar-transformacion');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');

                Route::post('exportar-ordenes-transformaciones-pendientes', [TransformacionController::class, 'exportarOrdenesTransformacionesPendientes'])->name('exportar-ordenes-transformaciones-pendientes');
                Route::post('exportar-ordenes-transformaciones-procesadas', [TransformacionController::class, 'exportarOrdenesTransformacionesProcesadas'])->name('exportar-ordenes-transformaciones-procesadas');

                Route::post('exportar-ordenes-detalle-transformaciones-procesadas', [TransformacionController::class, 'exportarOrdenesDetalleTransformacionesProcesadas'])->name('exportar-ordenes-detalle-transformaciones-procesadas');
			});

			Route::group(['as' => 'hoja-transformacion.', 'prefix' => 'hoja-transformacion'], function () {
				//Transformaciones
				Route::get('index', [TransformacionController::class, 'view_transformacion'])->name('index');
				Route::post('guardar_transformacion', [TransformacionController::class, 'guardar_transformacion'])->name('guardar-transformacion');
				Route::post('update_transformacion', [TransformacionController::class, 'update_transformacion'])->name('update-transformacion');
				Route::get('listar_transformaciones/{tp}', [TransformacionController::class, 'listar_transformaciones'])->name('listar-transformaciones');
				Route::get('mostrar_transformacion/{id}', [TransformacionController::class, 'mostrar_transformacion'])->name('mostrar-transformacion');
				Route::get('anular_transformacion/{id}', [TransformacionController::class, 'anular_transformacion'])->name('anular-transformacion');
				Route::get('listar_materias/{id}', [TransformacionController::class, 'listar_materias'])->name('listar-materias');
				Route::get('listar_directos/{id}', [TransformacionController::class, 'listar_directos'])->name('listar-directos');
				Route::get('listar_indirectos/{id}', [TransformacionController::class, 'listar_indirectos'])->name('listar-indirectos');
				Route::get('listar_sobrantes/{id}', [TransformacionController::class, 'listar_sobrantes'])->name('listar-sobrantes');
				Route::get('listar_transformados/{id}', [TransformacionController::class, 'listar_transformados'])->name('listar-transformados');
				Route::get('iniciar_transformacion/{id}', [TransformacionController::class, 'iniciar_transformacion'])->name('iniciar-transformacion');
				Route::post('procesar_transformacion', [TransformacionController::class, 'procesar_transformacion'])->name('procesar-transformacion');
				Route::post('guardar_materia', [TransformacionController::class, 'guardar_materia'])->name('guardar-materia');
				Route::post('guardar_directo', [TransformacionController::class, 'guardar_directo'])->name('guardar-directo');
				Route::post('guardar_indirecto', [TransformacionController::class, 'guardar_indirecto'])->name('guardar-indirecto');
				Route::post('guardar_sobrante', [TransformacionController::class, 'guardar_sobrante'])->name('guardar-sobrante');
				Route::post('guardar_transformado', [TransformacionController::class, 'guardar_transformado'])->name('guardar-transformado');
				Route::get('anular_materia/{id}', [TransformacionController::class, 'anular_materia'])->name('anular-materia');
				Route::get('anular_directo/{id}', [TransformacionController::class, 'anular_directo'])->name('anular-directo');
				Route::get('anular_indirecto/{id}', [TransformacionController::class, 'anular_indirecto'])->name('anular-indirecto');
				Route::get('anular_sobrante/{id}', [TransformacionController::class, 'anular_sobrante'])->name('anular-sobrante');
				Route::get('anular_transformado/{id}', [TransformacionController::class, 'anular_transformado'])->name('anular-transformado');
				Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar-prods');
				Route::post('guardar_producto', [ProductoController::class, 'guardar_producto'])->name('guardar-producto');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
			});
		});

		Route::group(['as' => 'garantias.', 'prefix' => 'garantias'], function () {
			Route::group(['as' => 'incidencias.', 'prefix' => 'incidencias'], function () {
				Route::get('index', [IncidenciaController::class, 'view_incidencia'])->name('index');
				Route::get('listarIncidencias', [IncidenciaController::class, 'listarIncidencias'])->name('listar-incidencias');
				Route::get('mostrarIncidencia/{id}', [IncidenciaController::class, 'mostrarIncidencia'])->name('mostrar-incidencia');
				Route::get('listarSalidasVenta', [IncidenciaController::class, 'listarSalidasVenta'])->name('listar-salidas-venta');

				Route::post('verDatosContacto', [OrdenesDespachoExternoController::class, 'verDatosContacto'])->name('ver-datos-contacto');
				Route::get('listarContactos/{id}', [OrdenesDespachoExternoController::class, 'listarContactos'])->name('listar-contactos');
				Route::post('actualizaDatosContacto', [OrdenesDespachoExternoController::class, 'actualizaDatosContacto'])->name('actualiza-datos-contacto');
				Route::get('seleccionarContacto/{id}/{req}', [OrdenesDespachoExternoController::class, 'seleccionarContacto'])->name('seleccionar-contacto');
				Route::get('mostrarContacto/{id}', [OrdenesDespachoExternoController::class, 'mostrarContacto'])->name('mostrar-contacto');
				Route::get('anularContacto/{id}', [OrdenesDespachoExternoController::class, 'anularContacto'])->name('anular-contacto');
				Route::get('listar_ubigeos', [AlmacenController::class, 'listar_ubigeos'])->name('listar-ubigeos');

				Route::get('listarSeriesProductos/{id}', [IncidenciaController::class, 'listarSeriesProductos'])->name('listar-series-productos');
				Route::post('guardarIncidencia', [IncidenciaController::class, 'guardarIncidencia'])->name('guardar-incidencia');
				Route::post('actualizarIncidencia', [IncidenciaController::class, 'actualizarIncidencia'])->name('actualizar-incidencia');
				Route::get('anularIncidencia/{id}', [IncidenciaController::class, 'anularIncidencia'])->name('anular-incidencia');

				Route::get('imprimirIncidencia/{id}', [IncidenciaController::class, 'imprimirIncidencia'])->name('imprimir-incidencia');
				Route::get('imprimirFichaAtencionBlanco/{id}', [IncidenciaController::class, 'imprimirFichaAtencionBlanco'])->name('imprimir-ficha-atencion-blanco');
			});

			Route::group(['as' => 'devolucionCas.', 'prefix' => 'devolucionCas'], function () {
				//Devoluciones
				Route::get('index', [DevolucionController::class, 'viewDevolucionCas'])->name('index');
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

			Route::group(['as' => 'fichas.', 'prefix' => 'fichas'], function () {
				Route::get('index', [FichaReporteController::class, 'view_ficha_reporte'])->name('index');
				Route::post('listarIncidencias', [FichaReporteController::class, 'listarIncidencias'])->name('listar-incidencias');
				Route::post('guardarFichaReporte', [FichaReporteController::class, 'guardarFichaReporte'])->name('guardar-ficha-reporte');
				Route::post('actualizarFichaReporte', [FichaReporteController::class, 'actualizarFichaReporte'])->name('actualizar-ficha-reporte');
				Route::get('anularFichaReporte/{id}', [FichaReporteController::class, 'anularFichaReporte'])->name('anular-ficha-reporte');
				Route::get('listarFichasReporte/{id}', [FichaReporteController::class, 'listarFichasReporte'])->name('listar-fichas-reporte');
				Route::post('cerrarIncidencia', [FichaReporteController::class, 'cerrarIncidencia'])->name('cerrar-incidencia');
				Route::post('cancelarIncidencia', [FichaReporteController::class, 'cancelarIncidencia'])->name('cancelar-incidencia');
				Route::get('verAdjuntosFicha/{id}', [FichaReporteController::class, 'verAdjuntosFicha'])->name('ver-adjuntos-ficha');
				Route::get('imprimirFichaReporte/{id}', [FichaReporteController::class, 'imprimirFichaReporte'])->name('imprimir-ficha-reporte');
				Route::get('incidenciasExcel', [FichaReporteController::class, 'incidenciasExcel'])->name('incidenciasExcel');
				Route::get('incidenciasExcelConHistorial', [FichaReporteController::class, 'incidenciasExcelConHistorial'])->name('incidenciasExcelConHistorial');

				Route::get('listarDevoluciones', [DevolucionController::class, 'listarDevoluciones'])->name('listar-devoluciones');
				Route::post('guardarFichaTecnica', [DevolucionController::class, 'guardarFichaTecnica'])->name('guardar-ficha-tecnica');
				Route::get('verFichasTecnicasAdjuntas/{id}', [DevolucionController::class, 'verFichasTecnicasAdjuntas'])->name('ver-fichas-tecnicas');
				Route::post('conformidadDevolucion', [DevolucionController::class, 'conformidadDevolucion'])->name('conformidad-devolucion');
				Route::get('revertirConformidad/{id}', [DevolucionController::class, 'revertirConformidad'])->name('revertir-devolucion');

				Route::post('clonarIncidencia', [FichaReporteController::class, 'clonarIncidencia'])->name('clonar-incidencia');
			});
			Route::group(['as' => 'marca.', 'prefix' => 'marca'], function () {
				Route::get('inicio', [CasMarcaController::class, 'inicio'])->name('inicio');
				Route::post('listar', [CasMarcaController::class, 'listar'])->name('listar');
				Route::post('guardar', [CasMarcaController::class, 'guardar'])->name('guardar');
				Route::get('editar', [CasMarcaController::class, 'editar'])->name('editar');
				Route::post('actualizar', [CasMarcaController::class, 'actualizar'])->name('actualizar');
				Route::post('eliminar', [CasMarcaController::class, 'eliminar'])->name('eliminar');
			});

			Route::group(['as' => 'modelo.', 'prefix' => 'modelo'], function () {
				Route::get('inicio', [CasModeloController::class, 'inicio'])->name('inicio');
				Route::post('listar', [CasModeloController::class, 'listar'])->name('listar');
				Route::post('guardar', [CasModeloController::class, 'guardar'])->name('guardar');
				Route::get('editar', [CasModeloController::class, 'editar'])->name('editar');
				Route::post('actualizar', [CasModeloController::class, 'actualizar'])->name('actualizar');
				Route::post('eliminar', [CasModeloController::class, 'eliminar'])->name('eliminar');
			});

			Route::group(['as' => 'producto.', 'prefix' => 'producto'], function () {
				Route::get('inicio', [CasProductoController::class, 'inicio'])->name('inicio');
				Route::post('listar', [CasProductoController::class, 'listar'])->name('listar');
				Route::post('guardar', [CasProductoController::class, 'guardar'])->name('guardar');
				Route::get('editar', [CasProductoController::class, 'editar'])->name('editar');
				Route::post('actualizar', [CasProductoController::class, 'actualizar'])->name('actualizar');
				Route::post('eliminar', [CasProductoController::class, 'eliminar'])->name('eliminar');
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
			Route::get('index', [PresupuestoController::class, 'index'])->name('index');
			Route::get('actualizarPartidas', [PartidaController::class, 'actualizarPartidas'])->name('actualizar-partidas');
		});

		Route::group(['as' => 'presupuesto.', 'prefix' => 'presupuesto'], function () {
			// Presupuesto
			Route::get('create', [PresupuestoController::class, 'create'])->name('index');
			Route::get('mostrarPartidas/{id}', [PresupuestoController::class, 'mostrarPartidas'])->name('mostrar-partidas');
			Route::get('mostrarRequerimientosDetalle/{id}', [PresupuestoController::class, 'mostrarRequerimientosDetalle'])->name('mostrar-requerimientos-detalle');
			Route::post('guardar-presupuesto', [PresupuestoController::class, 'store'])->name('guardar-presupuesto');
			Route::post('actualizar-presupuesto', [PresupuestoController::class, 'update'])->name('actualizar-presupuesto');

			Route::post('guardar-titulo', [TituloController::class, 'store'])->name('guardar-titulo');
			Route::post('actualizar-titulo', [TituloController::class, 'update'])->name('actualizar-titulo');
			Route::get('anular-titulo/{id}', [TituloController::class, 'destroy'])->name('anular-titulo');

			Route::post('guardar-partida', [PartidaController::class, 'store'])->name('guardar-partida');
			Route::post('actualizar-partida', [PartidaController::class, 'update'])->name('actualizar-partida');
			Route::get('anular-partida/{id}', [PartidaController::class, 'destroy'])->name('anular-partida');

			Route::get('mostrarGastosPorPresupuesto/{id}', [PresupuestoController::class, 'mostrarGastosPorPresupuesto'])->name('mostrar-gastos-presupuesto');
			Route::post('cuadroGastosExcel', [PresupuestoController::class, 'cuadroGastosExcel'])->name('cuadroGastosExcel');

			Route::group(['as' => 'presupuesto-interno.', 'prefix' => 'presupuesto-interno'], function () {
				//Presupuesto interno
				Route::get('lista', [PresupuestoInternoController::class, 'lista'])->name('lista');
				Route::post('lista-presupuesto-interno', [PresupuestoInternoController::class, 'listaPresupuestoInterno'])->name('lista-presupuesto-interno');
				Route::get('crear', [PresupuestoInternoController::class, 'crear'])->name('crear');

				Route::get('presupuesto-interno-detalle', [PresupuestoInternoController::class, 'presupuestoInternoDetalle'])->name('presupuesto-interno-detalle');
				Route::post('guardar', [PresupuestoInternoController::class, 'guardar'])->name('guardar');

				Route::post('editar', [PresupuestoInternoController::class, 'editar'])->name('editar');
				Route::post('editar-presupuesto-aprobado', [PresupuestoInternoController::class, 'editarPresupuestoAprobado'])->name('editar-presupuesto-aprobado');
				Route::post('actualizar', [PresupuestoInternoController::class, 'actualizar'])->name('actualizar');
				Route::post('eliminar', [PresupuestoInternoController::class, 'eliminar'])->name('eliminar');

				Route::get('get-area', [PresupuestoInternoController::class, 'getArea'])->name('get-area');
				// exportable de presupiesto interno
				Route::post('get-presupuesto-interno', [PresupuestoInternoController::class, 'getPresupuestoInterno'])->name('get-presupuesto-interno');

				//exportable de excel total ejecutado
				Route::post('presupuesto-ejecutado-excel', [PresupuestoInternoController::class, 'presupuestoEjecutadoExcel'])->name('resupuesto-ejecutado-excel');

				Route::post('aprobar', [PresupuestoInternoController::class, 'aprobar'])->name('aprobar');
				Route::post('editar-monto-partida', [PresupuestoInternoController::class, 'editarMontoPartida'])->name('editar-monto-partida');
				// buscar partidas
				Route::post('buscar-partida-combo', [PresupuestoInternoController::class, 'buscarPartidaCombo'])->name('buscar-partida-combo');
				// prueba de presupuestos
				Route::get('cierre-mes', [PresupuestoInternoController::class, 'cierreMes'])->name('cierre-mes');

				Route::get('listar-sedes-por-empresa/{id}', [PresupuestoInternoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');

                //exportable de excel saldos ejecutado
				Route::post('saldos-presupuesto', [PresupuestoInternoController::class, 'saldosPresupuesto'])->name('saldos-presupuesto');

				Route::group(['as' => 'script.', 'prefix' => 'script'], function () {
					Route::get('generar-presupuesto-gastos', [ScriptController::class, 'generarPresupuestoGastos'])->name('generar-presupuesto-gastos');
					Route::get('homologacion-partidas', [ScriptController::class, 'homologarPartida'])->name('homologacion-partidas');
					Route::get('total-presupuesto/{presup}/{tipo}', [ScriptController::class, 'totalPresupuesto'])->name('total-presupuesto');
					Route::get('total-consumido-mes/{presup}/{tipo}/{mes}', [ScriptController::class, 'totalConsumidoMes'])->name('total-consumido-mes');
					Route::get('total-ejecutado/{id}', [ScriptController::class, 'totalEjecutado'])->name('total-ejecutado');
					Route::get('regularizar-montos', [ScriptController::class, 'montosRegular'])->name('regularizar-montos');

					Route::get('total-presupuesto-anual-niveles/{presupuesto_intero_id}/{tipo}/{nivel}/{tipo_campo}', [ScriptController::class, 'totalPresupuestoAnualPartidasNiveles'])->name('total-presupuesto-anual-niveles');

					Route::get('nivelar-partidas/{mes}', [ScriptController::class, 'nivelarPartidas'])->name('nivelar-partidas');
					Route::get('actualizar-saldos', [ScriptController::class, 'actualizarSaldos'])->name('actualizar-saldos');

				});
				Route::get('actualizaEstadoHistorial/{id}/{est}', [PresupuestoInternoController::class, 'actualizaEstadoHistorial'])->name('actualiza-estado-historial');
			});

			Route::group(['as' => 'normalizar.', 'prefix' => 'normalizar'], function () {
				Route::get('presupuesto', [NormalizarController::class, 'lista'])->name('presupuesto');
				Route::get('listar', [NormalizarController::class, 'listar'])->name('listar');
				Route::post('listar-requerimientos-pagos', [NormalizarController::class, 'listarRequerimientosPagos'])->name('listar-requerimientos-pagos');
				Route::post('listar-ordenes', [NormalizarController::class, 'listarOrdenes'])->name('listar-ordenes');
				Route::post('obtener-presupuesto', [NormalizarController::class, 'obtenerPresupuesto'])->name('obtener-presupuesto');
				Route::post('vincular-partida', [NormalizarController::class, 'vincularPartida'])->name('vincular-partida');
				Route::get('detalle-requerimiento-pago/{id}', [NormalizarController::class, 'detalleRequerimientoPago'])->name('detalle-requerimiento-pago');
			});
		});

		Route::group(['as' => 'centro-costos.', 'prefix' => 'centro-costos'], function () {
			//Centro de Costos
			Route::get('index', [CentroCostoController::class, 'index'])->name('index');
			Route::get('mostrar-centro-costos', [CentroCostoController::class, 'mostrarCentroCostos'])->name('mostrar-centro-costos');
			Route::post('guardarCentroCosto', [CentroCostoController::class, 'guardarCentroCosto'])->name('guardar-centro-costo');
			Route::post('actualizar-centro-costo', [CentroCostoController::class, 'actualizarCentroCosto'])->name('actualizar-centro-costo');
			Route::get('anular-centro-costo/{id}', [CentroCostoController::class, 'anularCentroCosto'])->name('anular-centro-costo');
			Route::get('listar-centro-costos', [CentroCostoController::class, 'listarCentroCostos'])->name('listar-centro-costos');
		});


		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {
			Route::group(['as' => 'gastos.', 'prefix' => 'gastos'], function () {
				Route::get('index-requerimiento-logistico', [ReporteGastoController::class, 'indexReporteGastoRequerimientoLogistico'])->name('index-requerimiento-logistico');
				Route::get('index-requerimiento-pago', [ReporteGastoController::class, 'indexReporteGastoRequerimientoPago'])->name('index-requerimiento-pago');
				Route::get('index-cdp', [ReporteGastoController::class, 'indexReporteGastoCDP'])->name('index-cdp');

				Route::post('lista-requerimiento-logistico', [ReporteGastoController::class, 'listaGastoDetalleRequerimientoLogistico'])->name('lista-requerimiento-logistico');
				Route::post('lista-requerimiento-pago', [ReporteGastoController::class, 'listaGastoDetalleRequerimientoPago'])->name('lista-requerimiento-pago');
				Route::post('lista-cdp', [ReporteGastoController::class, 'listaGastoCDP'])->name('lista-cdp');

				Route::get('exportar-requerimiento-logistico-excel', [ReporteGastoController::class, 'listaGastoDetalleRequerimientoLogisticoExcel'])->name('exportar-requerimiento-logistico-excel');
				Route::get('exportar-requerimiento-pago-excel', [ReporteGastoController::class, 'listaGastoDetalleRequerimienoPagoExcel'])->name('exportar-requerimiento-pago-excel');
				Route::get('exportar-cdp-excel', [ReporteGastoController::class, 'listaGastoCDPExcel'])->name('exportar-cdp-excel');
			});
		});
	});

	/**
	 * Logística
	 */
	Route::name('logistica.')->prefix('logistica')->group(function () {
		Route::get('index', [LogisticaController::class, 'view_main_logistica'])->name('index');
		Route::name('gestion-logistica.')->prefix('gestion-logistica')->group(function () {
			Route::name('ocam.')->prefix('ocam')->group(function () {
				Route::get('index', [OCAMController::class, 'view_lista_ocams'])->name('index');
				Route::name('listado.')->prefix('listado')->group(function () {
					Route::get('ordenes-propias/{empresa?}/{year_publicacion?}/{condicion?}', [OCAMController::class, 'lista_ordenes_propias'])->name('ordenes-propias');
					Route::get('producto-base-o-transformado/{id_requerimiento?}/{tiene_transformacion?}', [OCAMController::class, 'listaProductosBaseoTransformado'])->name('producto-base-o-transformado');
				});
			});

			Route::name('compras.')->prefix('compras')->group(function () {
				Route::name('pendientes.')->prefix('pendientes')->group(function () {
					Route::get('index', [ComprasPendientesController::class, 'viewComprasPendientes'])->name('index');
					Route::post('requerimientos-pendientes', [ComprasPendientesController::class, 'listarRequerimientosPendientes'])->name('requerimientos-pendientes');
					Route::post('requerimientos-atendidos', [ComprasPendientesController::class, 'listarRequerimientosAtendidos'])->name('requerimientos-atendidos');
					Route::get('reporte-requerimientos-atendidos-excel/{Empresa}/{Sede}/{FechaDesde}/{FechaHasta}/{Reserva}/{Orden}', [ComprasPendientesController::class, 'reporteRequerimientosAtendidosExcel'])->name('reporte-requerimientos-atendidos-excel');
					Route::get('solicitud-cotizacion-excel/{id}', [ComprasPendientesController::class, 'solicitudCotizacionExcel'])->name('solicitud-cotizacion-excel');
					Route::get('exportar-lista-requerimientos-pendientes-excel', [ComprasPendientesController::class, 'exportListaRequerimientosPendientesExcel'])->name('exportar-lista-requerimientos-pendientes-excel');
					Route::post('lista_items-cuadro-costos-por-requerimiento', [ComprasPendientesController::class, 'get_lista_items_cuadro_costos_por_id_requerimiento'])->name('lista_items-cuadro-costos-por-requerimiento');
					Route::get('grupo-select-item-para-compra', [ComprasPendientesController::class, 'getGrupoSelectItemParaCompra'])->name('grupo-select-item-para-compra');
					Route::post('guardar-reserva-almacen', [ComprasPendientesController::class, 'guardarReservaAlmacen'])->name('guardar-reserva-almacen');
					Route::post('anular-reserva-almacen', [ComprasPendientesController::class, 'anularReservaAlmacen'])->name('anular-reserva-almacen');
					Route::post('anular-toda-reserva-detalle-requerimiento', [ComprasPendientesController::class, 'anularTodaReservaAlmacenDetalleRequerimiento'])->name('anular-toda-reserva-detalle-requerimiento');
					Route::post('buscar-item-catalogo', [ComprasPendientesController::class, 'buscarItemCatalogo'])->name('buscar-item-catalogo');
					Route::post('guardar-items-detalle-requerimiento', [ComprasPendientesController::class, 'guardarItemsEnDetalleRequerimiento'])->name('guardar-items-detalle-requerimiento');
					Route::get('listar-almacenes', [ComprasPendientesController::class, 'mostrar_almacenes'])->name('listar-almacenes');
					Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
					Route::post('guardar-producto', [AlmacenController::class, 'guardar_producto'])->name('guardar-producto');
					Route::get('itemsRequerimiento/{id}', [MapeoProductosController::class, 'itemsRequerimiento'])->name('items-requerimiento');
					Route::get('mostrar_prods', [ProductoController::class, 'mostrar_prods'])->name('mostrar_productos');
					Route::post('listarProductosSugeridos', [ProductoController::class, 'listarProductosSugeridos'])->name('listar-productos-sugeridos');
					Route::get('mostrar_prods_sugeridos/{part}/{desc}', [ProductoController::class, 'mostrar_prods_sugeridos'])->name('mostrar-productos-sugeridos');
					Route::post('guardar_mapeo_productos', [MapeoProductosController::class, 'guardar_mapeo_productos'])->name('guardar-mapeo-productos');
					Route::get('mostrar_categorias_tipo/{id}', [SubCategoriaController::class, 'mostrarSubCategoriasPorCategoria'])->name('mostrar_categorias_tipo');
					Route::get('detalle-requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'detalleRequerimiento'])->name('detalle-requerimientos');
					Route::get('detalle-requeriento-para-reserva/{idDetalleRequerimiento?}', [RequerimientoController::class, 'detalleRequerimientoParaReserva'])->name('detalle-requerimiento-para-reserva');
					Route::get('almacen-requeriento/{idRequerimiento?}', [RequerimientoController::class, 'obtenerAlmacenRequerimiento'])->name('almacen-requeriento');
					Route::get('historial-reserva-producto/{idDetalleRequerimiento?}', [RequerimientoController::class, 'historialReservaProducto'])->name('historial-reserva-producto');
					Route::get('todo-detalle-requeriento/{idRequerimiento?}/{transformadosONoTransformados?}', [RequerimientoController::class, 'todoDetalleRequerimiento'])->name('todo-detalle-requerimiento');
					Route::get('mostrar_tipos_clasificacion/{id}', [CategoriaController::class, 'mostrarCategoriasPorClasificacion'])->name('mostrar_tipos_clasificacion');
					Route::get('por-regularizar-cabecera/{id}', [ComprasPendientesController::class, 'listarPorRegularizarCabecera'])->name('por-regularizar-cabecera');
					Route::get('por-regularizar-detalle/{id}', [ComprasPendientesController::class, 'listarPorRegularizarDetalle'])->name('por-regularizar-detalle');
					Route::post('realizar-resolver-estado-por-regularizar', [ComprasPendientesController::class, 'realizarResolverEstadoPorRegularizar'])->name('realizar-anular-item-en-toda-orden-y-reservas');
					Route::post('realizar-remplazo-de-producto-comprometido-en-toda-orden', [ComprasPendientesController::class, 'realizarRemplazoDeProductoEnTodaOrden'])->name('realizar-remplazo-de-producto-comprometido-en-toda-orden');
					Route::post('realizar-liberacion-de-producto-comprometido-en-toda-orden', [ComprasPendientesController::class, 'realizarLiberacionDeProductoEnTodaOrden'])->name('realizar-liberacion-de-producto-comprometido-en-toda-orden');
					Route::post('realizar-anular-item-en-toda-orden-y-reservas', [ComprasPendientesController::class, 'realizarAnularItemEnTodaOrdenYReservas'])->name('realizar-anular-item-en-toda-orden-y-reservas');
					Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir_ingreso');
					Route::get('items-orden-items-reserva-por-detalle-requerimiento/{idDetalleRequerimiento}', [ComprasPendientesController::class, 'itemsOrdenItemsReservaPorDetalleRequerimiento'])->name('items-orden-items-reserva-por-detalle-requerimiento');
					Route::get('finalizar-cuadro/{id}', [OrdenController::class, 'finalizarCuadroPresupuesto'])->name('finalizar-cuadro');
					Route::get('mostrar-archivos-adjuntos-detalle-requerimiento/{id_detalle_requerimiento}', [RequerimientoController::class, 'mostrarArchivosAdjuntos'])->name('mostrar-archivos-adjuntos-detalle-requerimiento');
					Route::get('listar-otros-adjuntos-tesoreria-orden-requerimiento/{id}', [RequerimientoController::class, 'listarOtrsAdjuntosTesoreriaOrdenRequerimiento'])->name('listar-otros-adjuntos-tesoreria-orden-requerimiento');
					Route::get('listar-adjuntos-logisticos/{id}', [RequerimientoController::class, 'listarAdjuntosLogisticos'])->name('listar-adjuntos-logisticos');
					Route::get('listar-todo-archivos-adjuntos-requerimiento-logistico/{id}', [RequerimientoController::class, 'listarTodoArchivoAdjuntoRequerimientoLogistico'])->name('listar-todo-archivos-adjuntos-requerimiento-logistico');
					Route::get('listar-archivos-adjuntos-pago/{id}', [RequerimientoController::class, 'listarArchivoAdjuntoPago'])->name('listar-archivos-adjuntos-pago');
					Route::get('listar-categoria-adjunto', [RequerimientoController::class, 'mostrarCategoriaAdjunto'])->name('listar-categoria-adjunto');
					Route::post('guardar-adjuntos-adicionales-requerimiento-compra', [RequerimientoController::class, 'guardarAdjuntosAdicionales'])->name('guardar-adjuntos-adicionales-requerimiento-compra');
					Route::get('almacenes-con-stock-disponible/{idProducto}', [ComprasPendientesController::class, 'mostrarAlmacenesConStockDisponible'])->name('almacenes-con-stock-disponible');
					Route::post('actualizar-tipo-item-detalle-requerimiento', [ComprasPendientesController::class, 'actualizarTipoItemDetalleRequerimiento'])->name('actualizar-tipo-item-detalle-requerimiento');
					Route::post('actualizar-ajuste-estado-requerimiento', [ComprasPendientesController::class, 'actualizarAjusteEstadoRequerimiento'])->name('actualizar-ajuste-estado-requerimiento');
					Route::post('actualizar-ajuste-estado-requerimiento', [ComprasPendientesController::class, 'actualizarAjusteEstadoRequerimiento'])->name('actualizar-ajuste-estado-requerimiento');
					Route::post('guardar-observacion-logistica', [ComprasPendientesController::class, 'guardarObservacionLogistica'])->name('guardar-observacion-logistica');
					Route::get('retornar-requerimiento-atendido-a-lista-pedientes/{id}', [ComprasPendientesController::class, 'retornarRequerimientoAtendidoAListaPendientes'])->name('retornar-requerimiento-atendido-a-lista-pedientes');
					Route::get('enviar-requerimiento-a-lista-atendidos/{id}', [ComprasPendientesController::class, 'enviarRequerimientoAListaAtendidos'])->name('enviar-requerimiento-a-lista-atendidos');
				});

				Route::name('ordenes.')->prefix('ordenes')->group(function () {
					Route::name('elaborar.')->prefix('elaborar')->group(function () {
						Route::get('index', [OrdenController::class, 'view_crear_orden_requerimiento'])->name('index');
						Route::post('requerimiento-detallado', [OrdenController::class, 'ObtenerRequerimientoDetallado'])->name('requerimiento-detallado');
						Route::post('detalle-requerimiento-orden', [OrdenController::class, 'get_detalle_requerimiento_orden'])->name('detalle-requerimiento-orden');
						Route::post('guardar', [OrdenController::class, 'guardar_orden_por_requerimiento'])->name('guardar');
						Route::post('actualizar', [OrdenController::class, 'actualizar_orden_por_requerimiento'])->name('actualizar');
						Route::post('mostrar-proveedores', [OrdenController::class, 'mostrarProveedores'])->name('mostrar-proveedores');
						Route::get('contacto-proveedor/{idProveedor?}', [OrdenController::class, 'obtenerContactoProveedor'])->name('contacto-proveedor');
						Route::post('guardar_proveedor', [LogisticaController::class, 'guardar_proveedor'])->name('guardar_proveedor');
						Route::put('actualizar-estado-detalle-requerimiento/{id_detalle_req?}/{estado?}', [OrdenController::class, 'update_estado_detalle_requerimiento'])->name('actualizar-estado-detalle-requerimiento');
						Route::post('guardar-producto', [AlmacenController::class, 'guardar_producto'])->name('guardar-producto');
						Route::get('listar-almacenes', [UbicacionAlmacenController::class, 'mostrar_almacenes'])->name('listar-almacenes');
						Route::get('listar_ubigeos', [UbicacionAlmacenController::class, 'listar_ubigeos'])->name('listar_ubigeos');
						Route::get('lista_contactos_proveedor/{id_proveedor?}', [OrdenController::class, 'lista_contactos_proveedor'])->name('lista_contactos_proveedor');
						Route::get('generar-orden-pdf/{id?}', [OrdenController::class, 'generar_orden_por_requerimiento_pdf'])->name('generar-orden-pdf');
						Route::get('listar_trabajadores', [ProyectosController::class, 'listar_trabajadores'])->name('listar_trabajadores');
						Route::post('guardar_contacto', [OrdenController::class, 'guardar_contacto'])->name('guardar-contacto');
						Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
						Route::post('listar-historial-ordenes-elaboradas', [OrdenController::class, 'listaHistorialOrdenes'])->name('listar-historial-ordenes-elaboradas');
						Route::get('mostrar-orden/{id_orden?}', [OrdenController::class, 'mostrarOrden'])->name('mostrar-orden');
						Route::post('anular', [OrdenController::class, 'anularOrden'])->name('anular');
						Route::get('tipo-cambio-compra/{fecha}', [SaldosController::class, 'tipo_cambio_compra'])->name('tipo-cambio-compra');
						Route::get('detalle-requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'detalleRequerimiento'])->name('detalle-requerimiento');
						Route::get('requerimiento/{idRequerimiento?}', [RequerimientoController::class, 'requerimiento'])->name('requerimiento');
						Route::post('listarRequerimientoLogisticosParaVincularView', [RequerimientoController::class, 'listarRequerimientoLogisticosParaVincularView'])->name('listar-requerimiento-logisticos-para-vincular');
						Route::get('listar-cuentas-bancarias-proveedor/{idProveedor?}', [OrdenController::class, 'listarCuentasBancariasProveedor'])->name('listar-cuentas-bancarias-proveedor');
						Route::post('guardar-cuenta-bancaria-proveedor', [OrdenController::class, 'guardarCuentaBancariaProveedor'])->name('guardar-cuenta-bancaria-proveedor');
						Route::get('migrarOrdenCompra/{id}', [MigrateOrdenSoftLinkController::class, 'migrarOrdenCompra'])->name('migrar-orden-compra');
						Route::get('listarOrdenesPendientesMigrar', [MigrateOrdenSoftLinkController::class, 'listarOrdenesPendientesMigrar'])->name('listar-ordenes-pendientes-migrar');
						Route::get('ordenesPendientesMigrar', [MigrateOrdenSoftLinkController::class, 'ordenesPendientesMigrar'])->name('ordenes-pendientes-migrar');
						Route::get('listarOrdenesSoftlinkNoVinculadas/{cod}/{ini}/{fin}', [MigrateOrdenSoftLinkController::class, 'listarOrdenesSoftlinkNoVinculadas'])->name('listar-ordenes-softlinkNo-vinculadas');
						Route::post('mostrar-catalogo-productos', [RequerimientoController::class, 'mostrarCatalogoProductos'])->name('mostrar-catalogo-productos');
						Route::post('enviar-notificacion-finalizacion-cdp', [OrdenController::class, 'enviarNotificacionFinalizacionCDP'])->name('enviar-notificacion-finalizacion-cdp');
						Route::post('validar-orden-agil-orden-softlink', [OrdenController::class, 'validarOrdenAgilOrdenSoftlink'])->name('validar-orden-agil-orden-softlink');
						Route::post('vincular-oc-softlink', [OrdenController::class, 'vincularOcSoftlink'])->name('vincular-oc-softlink');
						Route::get('imprimir_orden_servicio_o_transformacion/{idOportunidad}', [TransformacionController::class, 'imprimir_orden_servicio_o_transformacion'])->name('imprimir-orden-servicio-o-transformacion');
					});
					Route::name('listado.')->prefix('listado')->group(function () {

						Route::get('index', [OrdenController::class, 'view_listar_ordenes'])->name('index');
						Route::get('listas-categorias-adjunto', [OrdenController::class, 'listarCategoriasAdjuntos'])->name('listas-categorias-adjunto');
						Route::post('guardar-adjunto-orden', [OrdenController::class, 'guardarAdjuntoOrden'])->name('guardar-adjunto-orden');
						Route::get('listar-archivos-adjuntos-orden/{id_order}', [OrdenController::class, 'listarArchivosOrder'])->name('listar-archivos-adjuntos-orden');
						Route::get('historial-de-envios-a-pago-en-cuotas/{id_order}', [OrdenController::class, 'ObtenerHistorialDeEnviosAPagoEnCuotas'])->name('historial-de-envios-a-pago-en-cuotas');
						Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
						Route::get('generar-orden-pdf/{id?}', [OrdenController::class, 'generar_orden_por_requerimiento_pdf'])->name('generar-orden-pdf');
						Route::get('facturas/{id_orden}', [OrdenController::class, 'obtenerFacturas'])->name('facturas');
						//nivel cabecera
						Route::post('lista-ordenes-elaboradas', [OrdenController::class, 'listaOrdenesElaboradas'])->name('lista-ordenes-elaboradas');
						Route::get('detalle-orden/{id_orden}', [OrdenController::class, 'detalleOrden'])->name('detalle-orden');
						Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
						Route::get('verSession', [LogisticaController::class, 'verSession'])->name('ver-session');
						Route::get('exportar-lista-ordenes-elaboradas-nivel-cabecera-excel/{filtro?}', [OrdenController::class, 'exportListaOrdenesNivelCabeceraExcel'])->name('exportar-lista-ordenes-elaboradas-nivel-cabecera-excel');
						Route::get('exportar-lista-ordenes-elaboradas-nivel-detalle-excel', [OrdenController::class, 'exportListaOrdenesNivelDetalleExcel'])->name('facturas');

						// nivel
						Route::post('lista-items-ordenes-elaboradas', [OrdenController::class, 'listaItemsOrdenesElaboradas'])->name('lista-items-ordenes-elaboradas');
						Route::post('actualizar-estado', [OrdenController::class, 'update_estado_orden'])->name('actualizar-estado');
						Route::post('actualizar-estado-detalle', [OrdenController::class, 'update_estado_item_orden'])->name('actualizar-estado-detalle-orden');
						Route::post('anular', [OrdenController::class, 'anularOrden'])->name('anular');
						Route::get('documentos-vinculados/{id_orden?}', [OrdenController::class, 'documentosVinculadosOrden'])->name('documentos-vinculados');
						Route::get('obtener-contribuyente-por-id-proveedor/{id_proveedor?}', [OrdenController::class, 'obtenerContribuyentePorIdProveedor'])->name('obtener-contribuyente-por-id-proveedor');
						Route::get('obtener-cuenta-contribuyente/{idContribuyente}', [RequerimientoPagoController::class, 'obtenerCuentaContribuyente'])->name('obtener-cuenta-contribuyente');
						Route::get('obtener-cuenta-persona/{idPersona}', [RequerimientoPagoController::class, 'obtenerCuentaPersona'])->name('obtener-cuenta-persona');
						Route::post('guardar-persona', [RequerimientoPagoController::class, 'guardarPersona'])->name('guardar-persona');
						Route::post('guardar-cuenta-destinatario', [RequerimientoPagoController::class, 'guardarCuentaDestinatario'])->name('guardar-cuenta-destinatario');
						Route::post('registrar-solicitud-de-pago', [OrdenController::class, 'registrarSolicitudDePagar'])->name('registrar-solicitud-de-pago');
						Route::get('obtener-contribuyente/{id}', [OrdenController::class, 'obtenerContribuyente'])->name('obtener-contribuyente');
						Route::get('obtener-persona/{id}', [OrdenController::class, 'obtenerPersona'])->name('obtener-persona');
						Route::post('obtener-destinatario-por-nro-documento', [RequerimientoPagoController::class, 'obtenerDestinatarioPorNumeroDeDocumento'])->name('obtener-destinatario-por-nro-documento');
						Route::post('obtener-destinatario-por-nombre', [RequerimientoPagoController::class, 'obtenerDestinatarioPorNombre'])->name('obtener-destinatario-por-nombre');
						Route::get('listar-archivos-adjuntos-pago-requerimiento/{idOrden}', [OrdenController::class, 'listarArchivoAdjuntoPagoRequerimiento'])->name('listar-archivos-adjuntos-pago-requerimiento');
						Route::get('calcular-prioridad/{id?}', [OrdenController::class, 'calcularPrioridad'])->name('calcular-prioridad');
						Route::get('obtener-requerimientos-con-impuesto/{idOrden}', [OrdenController::class, 'obtenerRequerimientosConImpuesto'])->name('obtener-requerimientos-con-impuesto');
						Route::post('generar-filtros', [OrdenController::class, 'generarFiltros'])->name('generar-filtros');

						Route::post('reporte-filtros', [OrdenController::class, 'reporteFiltros'])->name('reporte-filtros');
					});
				});
			});
			Route::name('proveedores.')->prefix('proveedores')->group(function () {
				Route::post('guardar', [ProveedoresController::class, 'guardar'])->name('guardar');
				Route::get('mostrar/{idProveedor?}', [ProveedoresController::class, 'mostrar'])->name('mostrar');
				Route::post('actualizar', [ProveedoresController::class, 'actualizar'])->name('actualizar');
				Route::post('anular', [ProveedoresController::class, 'anular'])->name('anular');
				Route::get('index', [ProveedoresController::class, 'index'])->name('index');
				Route::post('obtener-data-listado', [ProveedoresController::class, 'obtenerDataListado'])->name('obtener-data-listado');
				Route::get('listar_ubigeos', [AlmacenController::class, 'listar_ubigeos'])->name('listar-ubigeos');
				Route::post('obtener-data-contribuyente-segun-nro-documento', [ProveedoresController::class, 'obtenerDataContribuyenteSegunNroDocumento'])->name('obtener-data-contribuyente-segun-nro-documento');
				Route::get('reporte-lista-proveedores', [ProveedoresController::class, 'reporteListaProveedores'])->name('reporte-lista-proveedores');
			});

			Route::name('reportes.')->prefix('reportes')->group(function () {
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				Route::get('ordenes-compra', [ReporteLogisticaController::class, 'viewReporteOrdenesCompra'])->name('ordenes-compra');
				Route::get('ordenes-servicio', [ReporteLogisticaController::class, 'viewReporteOrdenesServicio'])->name('ordenes-servicio');
				Route::post('lista-ordenes-compra', [ReporteLogisticaController::class, 'listaOrdenesCompra'])->name('lista-ordenes-compra');
				Route::post('lista-ordenes-servicio', [ReporteLogisticaController::class, 'listaOrdenesServicio'])->name('lista-ordenes-servicio');
				Route::get('transito-ordenes-compra', [ReporteLogisticaController::class, 'viewReporteTransitoOrdenesCompra'])->name('transito-ordenes-compra');
				Route::post('lista-transito-ordenes-compra', [ReporteLogisticaController::class, 'listaTransitoOrdenesCompra'])->name('lista-transito-ordenes-compra');
				Route::get('reporte-ordenes-compra-excel/{idEmpresa?}/{idSede?}/{fechaDesde?}/{fechaHasta?}', [OrdenController::class, 'reporteOrdenesCompraExcel'])->name('reporte-ordenes-compra-excel');
				Route::get('reporte-ordenes-servicio-excel/{idEmpresa?}/{idSede?}/{fechaDesde?}/{fechaHasta?}', [OrdenController::class, 'reporteOrdenesServicioExcel'])->name('reporte-ordenes-servicio-excel');
				Route::get('reporte-transito-ordenes-compra-excel/{idEmpresa?}/{idSede?}/{fechaDesde?}/{fechaHasta?}', [OrdenController::class, 'reporteTransitoOrdenesCompraExcel'])->name('reporte-transito-ordenes-compra-excel');
				Route::get('compras-locales', [ReporteLogisticaController::class, 'viewReporteComprasLocales'])->name('compras-locales');
				Route::post('lista-compras', [ReporteLogisticaController::class, 'listarCompras'])->name('lista-compras');
				Route::get('reporte-compras-locales-excel', [ReporteLogisticaController::class, 'reporteCompraLocalesExcel'])->name('reporte-compras-locales-excel');
				Route::get('listar-archivos-adjuntos-pago-requerimiento/{idOrden}', [OrdenController::class, 'listarArchivoAdjuntoPagoRequerimiento'])->name('listar-archivos-adjuntos-pago-requerimiento');
				Route::get('listar-archivos-adjuntos-orden/{id_order}', [OrdenController::class, 'listarArchivosOrder'])->name('listar-archivos-adjuntos-orden');
			});
			Route::name('cotizacion.')->prefix('cotizacion')->group(function () {
				Route::name('gestionar.')->prefix('gestionar')->group(function () {
					Route::get('index', [LogisticaController::class, 'view_gestionar_cotizaciones'])->name('index');
					Route::get('select-sede-by-empresa/{id?}', [LogisticaController::class, 'select_sede_by_empresa'])->name('select-sede-by-empresa');
					Route::get('listaCotizacionesPorGrupo/{id_cotizacion}', [LogisticaController::class, 'listaCotizacionesPorGrupo'])->name('lista-cotizaciones-por-grupo');
					Route::get('requerimientos_entrante_a_cotizacion/{id_empresa}/{id_sede}', [CotizacionController::class, 'requerimientos_entrante_a_cotizacion'])->name('requerimientos-entrante-a-cotizacion');
					Route::get('detalle_requerimiento', [RequerimientoController::class, 'detalle_requerimiento'])->name('detalle-requerimiento');
					Route::get('mostrar_proveedores', [LogisticaController::class, 'mostrar_proveedores'])->name('mostrar-proveedores');
					Route::post('guardar_cotizacion/{id_gru}', [LogisticaController::class, 'guardar_cotizacion'])->name('guardar_cotizacion/{id_gru}');
					Route::post('agregar-item-cotizacion/{id_cotizacion}', [LogisticaController::class, 'agregar_item_a_cotizacion'])->name('agregar-item-cotizacion');
					Route::post('eliminar-item-cotizacion/{id_cotizacion}', [LogisticaController::class, 'eliminar_item_a_cotizacion'])->name('eliminar-item-cotizacion');
					Route::put('actulizar-empresa-cotizacion', [LogisticaController::class, 'actualizar_empresa_cotizacion'])->name('actualizar-empresa-cotizacion');
					Route::put('actulizar-proveedor-cotizacion', [LogisticaController::class, 'actualizar_proveedor_cotizacion'])->name('actualizar-proveedor-cotizacion');
					Route::put('actulizar-contacto-cotizacion', [LogisticaController::class, 'actualizar_contacto_cotizacion'])->name('actualizar-contacto-cotizacion');
					Route::get('mostrar_email_proveedor/{id}', [LogisticaController::class, 'mostrar_email_proveedor'])->name('mostrar-email-proveedor');
					Route::post('guardar_contacto', [LogisticaController::class, 'guardar_contacto'])->name('guardar-contacto');
					Route::get('descargar_solicitud_cotizacion_excel/{id}', [LogisticaController::class, 'descargar_solicitud_cotizacion_excel'])->name('descargar_solicitud_cotizacion_excel');
					Route::get('anular_cotizacion/{id}', [LogisticaController::class, 'anular_cotizacion'])->name('anular-cotizacion');
					Route::get('saldo_por_producto/{id}', [AlmacenController::class, 'saldo_por_producto'])->name('saldo-por-producto');
					Route::post('enviar_correo', [CorreoController::class, 'enviar'])->name('enviar-correo');
					Route::get('estado_archivos_adjuntos_cotizacion/{id_cotizacion}', [CorreoController::class, 'getAttachFileStatus'])->name('estado-archivos-adjuntos-cotizacion');
					Route::post('guardar-archivos-adjuntos-cotizacion', [CorreoController::class, 'guardar_archivos_adjuntos_cotizacion'])->name('guardar-archivos-adjuntos-cotizacion');
					Route::get('mostrar_grupo_cotizacion/{id}', [LogisticaController::class, 'mostrar_grupo_cotizacion'])->name('mostrar-grupo-cotizacion');
					Route::get('mostrar_cotizacion/{id}', [LogisticaController::class, 'mostrar_cotizacion'])->name('mostrar-cotizacion');
					Route::get('get_cotizacion/{id}', [LogisticaController::class, 'get_cotizacion'])->name('get-cotizacion');
					Route::get('mostrar-archivos-adjuntos/{id_detalle_requerimiento}', [RequerimientoController::class, 'mostrarArchivosAdjuntos'])->name('mostrar-archivos-adjuntos');
					Route::post('guardar-archivos-adjuntos-detalle-requerimiento', [LogisticaController::class, 'guardar_archivos_adjuntos_detalle_requerimiento'])->name('guardar-archivos-adjuntos-detalle-requerimiento');
					Route::put('eliminar-archivo-adjunto-detalle-requerimiento/{id_archivo}', [LogisticaController::class, 'eliminar_archivo_adjunto_detalle_requerimiento'])->name('eliminar-archivo-adjunto-detalle-requerimiento');
					Route::put('descargar_olicitud_cotizacion_excel/{id_cotizacion}', [LogisticaController::class, 'descargar_olicitud_cotizacion_excel'])->name('descargar-solicitud-cotizacion-excel');
					Route::get('archivos_adjuntos_cotizacion/{id_cotizacion}', [LogisticaController::class, 'mostrar_archivos_adjuntos_cotizacion'])->name('archivos-adjuntos-cotizacion');
				});
			});
		});
		Route::get('getEstadosRequerimientos/{filtro}', [DistribucionController::class, 'getEstadosRequerimientos'])->name('get-estados-requerimientos');
		Route::get('listarEstadosRequerimientos/{id}/{filtro}', [DistribucionController::class, 'listarEstadosRequerimientos'])->name('listar-estados-requerimientos');

		Route::name('distribucion.')->prefix('distribucion')->group(function () {

			Route::name('guias-transportistas.')->prefix('guias-transportistas')->group(function () {
				Route::get('index', [DistribucionController::class, 'view_guias_transportistas'])->name('index');
				Route::get('listarGuiasTransportistas', [DistribucionController::class, 'listarGuiasTransportistas'])->name('listar-guias-transportistas');
				Route::get('verDetalleRequerimientoDI/{id}', [DistribucionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimiento-despacho-interno');
				Route::get('imprimir_despacho/{id}', [DistribucionController::class, 'imprimir_despacho'])->name('imprimir-despacho-interno');
			});

			Route::name('ordenes-transformacion.')->prefix('ordenes-transformacion')->group(function () {
				Route::get('index', [OrdenesTransformacionController::class, 'view_ordenes_transformacion'])->name('index');
				Route::get('listarRequerimientosEnProceso', [OrdenesTransformacionController::class, 'listarRequerimientosEnProceso'])->name('listar-requerimientos-en-proceso');
				Route::get('listarDetalleTransferencias/{id}', [TransferenciaController::class, 'listarDetalleTransferencias'])->name('listar-detalle-transferencias');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimiento-despacho-interno');
				Route::post('guardarOrdenDespachoInterno', [OrdenesTransformacionController::class, 'guardarOrdenDespachoInterno'])->name('guardar-orden-despacho-interno');
				Route::get('verDetalleInstrucciones/{id}', [OrdenesTransformacionController::class, 'verDetalleInstrucciones'])->name('ver-detalle-instrucciones');
				Route::get('anular_orden_despacho/{id}/{tp}', [SalidasPendientesController::class, 'anular_orden_despacho'])->name('anular-orden-despacho');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
				Route::get('verSeries/{id}', [DistribucionController::class, 'verSeries'])->name('ver-series');
			});

			Route::name('ordenes-despacho-externo.')->prefix('ordenes-despacho-externo')->group(function () {
				Route::get('index', [OrdenesDespachoExternoController::class, 'view_ordenes_despacho_externo'])->name('index');
				Route::post('listarRequerimientosPendientesDespachoExterno', [OrdenesDespachoExternoController::class, 'listarRequerimientosPendientesDespachoExterno'])->name('listar-requerimientos-pendientes-despacho-externo');
				Route::get('prueba/{id}', [OrdenesDespachoExternoController::class, 'prueba'])->name('prueba');
				Route::post('priorizar', [OrdenesDespachoExternoController::class, 'priorizar'])->name('priorizar');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
				Route::get('listarDetalleTransferencias/{id}', [TransferenciaController::class, 'listarDetalleTransferencias'])->name('listar-detalle-transferencias');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimiento-despacho-interno');
				Route::get('listar_ubigeos', [AlmacenController::class, 'listar_ubigeos'])->name('listar-ubigeos');
				Route::post('guardarOrdenDespachoExterno', [OrdenesDespachoExternoController::class, 'guardarOrdenDespachoExterno'])->name('guardar-orden-despacho-externo');
				Route::get('adjuntos-despacho', [OrdenesDespachoExternoController::class, 'adjuntosDespacho'])->name('adjuntos-despacho');
				Route::post('generarDespachoInterno', [OrdenesDespachoInternoController::class, 'generarDespachoInterno'])->name('generar-despacho-interno');
				Route::post('actualizarOrdenDespachoExterno', [OrdenesDespachoExternoController::class, 'actualizarOrdenDespachoExterno'])->name('actualizar-orden-despacho-externo');
				Route::get('anular_orden_despacho/{id}/{tp}', [SalidasPendientesController::class, 'anular_orden_despacho'])->name('anular-orden-despacho');
				Route::post('enviarFacturacion', [OrdenesDespachoExternoController::class, 'enviarFacturacion'])->name('enviar-facturacion');
				Route::post('despachoTransportista', [OrdenesDespachoExternoController::class, 'despachoTransportista'])->name('despacho-transportista');
				Route::get('mostrarTransportistas', [DistribucionController::class, 'mostrarTransportistas'])->name('mostrar-transportistas');
				Route::get('getTimelineOrdenDespacho/{id}', [DistribucionController::class, 'getTimelineOrdenDespacho'])->name('get-timeline-orden-despacho');
				Route::post('guardarEstadoEnvio', [DistribucionController::class, 'guardarEstadoEnvio'])->name('guardar-estado-envio');
				Route::get('eliminarTrazabilidadEnvio/{id}', [DistribucionController::class, 'eliminarTrazabilidadEnvio'])->name('eliminar-trazabilidad-envio');
				Route::get('mostrarDocumentosByRequerimiento/{id}', [TrazabilidadRequerimientoController::class, 'mostrarDocumentosByRequerimiento'])->name('mostrar-documentos-by-requerimiento');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
				Route::get('imprimir_transferencia/{id}', [TransferenciaController::class, 'imprimir_transferencia'])->name('imprimir-transferencia');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::post('verDatosContacto', [OrdenesDespachoExternoController::class, 'verDatosContacto'])->name('ver-datos-contacto');
				Route::get('listarContactos/{id}', [OrdenesDespachoExternoController::class, 'listarContactos'])->name('listar-contactos');
				Route::post('actualizaDatosContacto', [OrdenesDespachoExternoController::class, 'actualizaDatosContacto'])->name('actualiza-datos-contacto');
				Route::get('seleccionarContacto/{id}/{req}', [OrdenesDespachoExternoController::class, 'seleccionarContacto'])->name('seleccionar-contacto');
				Route::get('mostrarContacto/{id}', [OrdenesDespachoExternoController::class, 'mostrarContacto'])->name('mostrar-contacto');
				Route::get('anularContacto/{id}', [OrdenesDespachoExternoController::class, 'anularContacto'])->name('anular-contacto');
				Route::post('enviarDatosContacto', [OrdenesDespachoExternoController::class, 'enviarDatosContacto'])->name('enviar-datos-contacto');
				Route::post('guardarTransportista', [OrdenesDespachoExternoController::class, 'guardarTransportista'])->name('guardar-transportista');
				Route::post('despachosExternosExcel', [OrdenesDespachoExternoController::class, 'despachosExternosExcel'])->name('despachos-externos-excel');
				Route::post('listarPorOc', [OrdenesDespachoExternoController::class, 'listarPorOc'])->name('listar-por-oc');
				Route::post('actualizarOcFisica', [OrdenesDespachoExternoController::class, 'actualizarOcFisica'])->name('actualizar-oc-fisica');
				Route::post('actualizarSiaf', [OrdenesDespachoExternoController::class, 'actualizarSiaf'])->name('actualizar-siaf');
				Route::post('anularDespachoInterno', [OrdenesDespachoInternoController::class, 'anularDespachoInterno'])->name('anular-despacho-interno');
				Route::get('migrarDespachos', [OrdenesDespachoExternoController::class, 'migrarDespachos'])->name('migrar-despachos');
				Route::get('generarDespachoInternoNroOrden', [OrdenesDespachoInternoController::class, 'generarDespachoInternoNroOrden'])->name('generar-despacho-interno-nro-orden');
				Route::get('usuariosDespacho', [OrdenesDespachoExternoController::class, 'usuariosDespacho'])->name('usuarios-despacho');
				Route::get('listar-sedes-por-empresa/{id?}', [RequerimientoController::class, 'listarSedesPorEmpresa'])->name('listar-sedes-por-empresa');
				Route::get('mostrar-partidas/{idGrupo?}/{idProyecto?}', [PresupuestoController::class, 'mostrarPresupuestos'])->name('mostrar-partidas');
				Route::get('mostrar-centro-costos', [CentroCostoController::class, 'mostrarCentroCostosSegunGrupoUsuario'])->name('mostrar-centro-costos');
				Route::get('mostrar-requerimiento-orden-despacho/{idOd?}', [OrdenesDespachoExternoController::class, 'mostrarRequerimientoOrdenDespacho'])->name('mostrar-requerimiento-orden-despacho');
				Route::post('guardar-requerimiento-flete', [OrdenesDespachoExternoController::class, 'guardarRequerimientoFlete'])->name('guardar-requerimiento-flete');
				Route::get('lista-destinatario-persona', [NecesidadesController::class, 'listaDestinatarioPersona'])->name('lista-destinatario-persona');
				Route::get('lista-destinatario-contribuyente', [NecesidadesController::class, 'listaDestinatarioContribuyente'])->name('lista-destinatario-contribuyente');
				Route::get('obtener-data-cuentas-de-persona/{idPersona}', [NecesidadesController::class, 'obtenerDataCuentasDePersona'])->name('obtener-data-cuentas-de-persona');
				Route::get('obtener-data-cuentas-de-contribuyente/{idContribuyente}', [NecesidadesController::class, 'obtenerDataCuentasDeContribuyente'])->name('obtener-data-cuentas-de-contribuyente');


			});

			Route::name('ordenes-despacho-interno.')->prefix('ordenes-despacho-interno')->group(function () {

				Route::get('index', [OrdenesDespachoInternoController::class, 'view_ordenes_despacho_interno'])->name('index');
				Route::post('listarRequerimientosPendientesDespachoInterno', [OrdenesDespachoInternoController::class, 'listarRequerimientosPendientesDespachoInterno'])->name('listar-requerimientos-pendientes-despacho-interno');
				Route::post('priorizar', [OrdenesDespachoInternoController::class, 'priorizar'])->name('priorizar');
				Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
				Route::get('listarDetalleTransferencias/{id}', [TransferenciaController::class, 'listarDetalleTransferencias'])->name('listar-detalle-transferencias');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimiento-di');
				Route::get('listar_ubigeos', [AlmacenController::class, 'listar_ubigeos'])->name('listar-ubigeos');

				Route::post('guardarOrdenDespachoExterno', [OrdenesDespachoExternoController::class, 'guardarOrdenDespachoExterno'])->name('guardar-orden-despacho-externo');
				Route::get('generarDespachoInterno/{id}', [OrdenesDespachoInternoController::class, 'generarDespachoInterno'])->name('generar-despacho-interno');
				Route::get('anular_orden_despacho/{id}/{tp}', [SalidasPendientesController::class, 'anular_orden_despacho'])->name('anular-orden-despacho');
				Route::post('enviarFacturacion', [OrdenesDespachoExternoController::class, 'enviarFacturacion'])->name('enviar-facturacion');

				Route::get('mostrarDocumentosByRequerimiento/{id}', [TrazabilidadRequerimientoController::class, 'mostrarDocumentosByRequerimiento'])->name('mostrar-documentos-by-requerimiento');
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class, 'imprimir_transformacion'])->name('imprimir-transformacion');
				Route::get('imprimir_transferencia/{id}', [TransferenciaController::class, 'imprimir_transferencia'])->name('imprimir-transferencia');
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class, 'imprimir_ingreso'])->name('imprimir-ingreso');
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class, 'imprimir_salida'])->name('imprimir-salida');

				Route::get('listarDespachosInternos/{fec}', [OrdenesDespachoInternoController::class, 'listarDespachosInternos'])->name('listar-despachos-internos');
				Route::get('subirPrioridad/{id}', [OrdenesDespachoInternoController::class, 'subirPrioridad'])->name('subir-prioridad');
				Route::get('bajarPrioridad/{id}', [OrdenesDespachoInternoController::class, 'bajarPrioridad'])->name('bajar-prioridad');
				Route::get('pasarProgramadasAlDiaSiguiente/{fec}', [OrdenesDespachoInternoController::class, 'pasarProgramadasAlDiaSiguiente'])->name('pasar-programadas-al-dia-siguiente');
				Route::get('listarPendientesAnteriores/{fec}', [OrdenesDespachoInternoController::class, 'listarPendientesAnteriores'])->name('listar-pendientes-anteriores');
				Route::post('cambiaEstado', [OrdenesDespachoInternoController::class, 'cambiaEstado'])->name('cambia-estado');
			});
            Route::name('programacion-despachos.')->prefix('programacion-despachos')->group(function () {

                Route::get('lista', [ProgramacionDespachosController::class, 'lista'])->name('lista');
                Route::get('listar-odi', [ProgramacionDespachosController::class, 'listarODI'])->name('listar-odi');
                Route::get('listar-ode', [ProgramacionDespachosController::class, 'listarODE'])->name('listar-ode');
                Route::post('guardar', [ProgramacionDespachosController::class, 'guardar'])->name('guardar');
                Route::get('editar/{id}', [ProgramacionDespachosController::class, 'editar'])->name('editar');
                Route::put('eliminar/{id}', [ProgramacionDespachosController::class, 'eliminar'])->name('eliminar');
                Route::post('finalizar-programacion', [ProgramacionDespachosController::class, 'finalizarProgramacion'])->name('finalizar-programacion');

                Route::get('reprogramar', [ProgramacionDespachosController::class, 'reprogramar'])->name('reprogramar');
			});
		});
	});

	/**
	 * Gerencial
	 */
	Route::name('gerencial.')->prefix('gerencial')->group(function () {
		Route::get('index', [GerencialController::class, 'index'])->name('index');

		// cobranzas
		Route::group(['as' => 'cobranza.', 'prefix' => 'cobranza'], function () {
			Route::get('index', [CobranzaController::class, 'index'])->name('index');
			Route::post('listar', [CobranzaController::class, 'listar'])->name('listar');
			Route::post('buscar-registro', [CobranzaController::class, 'buscarRegistro'])->name('buscar-registro');
			Route::get('seleccionar-registro/{id_requerimiento}', [CobranzaController::class, 'cargarDatosRequerimiento'])->name('seleccionar-registro');
			Route::get('obtener-fases/{id}', [CobranzaController::class, 'obtenerFase'])->name('obtener-fases');
			Route::post('guardar-fase', [CobranzaController::class, 'guardarFase'])->name('guardar-fase');
			Route::post('eliminar-fase', [CobranzaController::class, 'eliminarFase'])->name('eliminar-fase');
			Route::get('obtener-observaciones/{id}', [CobranzaController::class, 'obtenerObservaciones'])->name('obtener-observaciones');
			Route::post('guardar-observaciones', [CobranzaController::class, 'guardarObservaciones'])->name('guardar-observaciones');
			Route::post('eliminar-observacion', [CobranzaController::class, 'eliminarObservaciones'])->name('eliminar-observacion');
			Route::post('guardar-registro-cobranza', [CobranzaController::class, 'guardarRegistro'])->name('guardar-registro-cobranza');
			Route::post('editar-registro', [CobranzaController::class, 'editarRegistro'])->name('editar-registro');
			Route::post('eliminar-registro-cobranza', [CobranzaController::class, 'eliminarRegistro'])->name('eliminar-registro-cobranza');
			Route::post('filtros-cobranzas', [CobranzaController::class, 'filtros'])->name('filtros-cobranzas');
			Route::post('obtener-penalidades', [CobranzaController::class, 'obtenerPenalidades'])->name('obtener-penalidades');
			Route::post('guardar-penalidad', [CobranzaController::class, 'guardarPenalidad'])->name('guardar-penalidad');
			Route::post('cambio-estado-penalidad', [CobranzaController::class, 'cambioEstadoPenalidad'])->name('cambio-estado-penalidad');
			Route::get('exportar-excel', [CobranzaController::class, 'exportarExcel'])->name('exportar-excel');

            Route::post('guardar-vededor', [CobranzaController::class,'guardarVededor'])->name('guardar-vededor');
			/**
			 * Script para recuperar la info de Gerencia e Iniciar en las nuevas tablas
			 */
			Route::group(['as' => 'script.', 'prefix' => 'script'], function () {
				Route::get('script-periodo', 'Gerencial\Cobranza\CobranzaController@scriptPeriodos')->name('script-periodo');
				Route::get('script-fases-inicial', 'Gerencial\Cobranza\CobranzaController@scriptRegistroFase')->name('script-fases-inicial');
				Route::get('script-cobranza-fase', 'Gerencial\Cobranza\CobranzaController@scriptFases')->name('script-cobranza-fase');
                #pasa los contribuyentes a clientes
                Route::get('script-contribuyentes-clientes', 'Gerencial\Cobranza\CobranzaController@scriptContribuyenteCliente')->name('script-contribuyente-cliente');
                #generar codigo para los clientes
                Route::get('script-generar-codigo-clientes', 'Gerencial\Cobranza\CobranzaController@scriptGenerarCodigoCliente')->name('script-generar-codigo-clientes');
                #generar codigo para los clientes
                Route::get('script-generar-codigo-proveedores', 'Gerencial\Cobranza\CobranzaController@scriptGenerarCodigoProveedores')->name('script-generar-codigo-proveedores');
				Route::get('carga-manual', 'Gerencial\Cobranza\CobranzaController@cargaManual')->name('carga-manual');
			});

			Route::get('cliente', [CobranzaClienteController::class, 'cliente'])->name('cliente');
			Route::get('crear-cliente', [CobranzaClienteController::class, 'nuevoCliente'])->name('crear-cliente');
			Route::post('listar-clientes', [CobranzaClienteController::class, 'listarCliente'])->name('listar-clientes');
			Route::post('crear-clientes', [CobranzaClienteController::class, 'crear'])->name('crear-clientes');
			Route::post('buscar-cliente-documento', [CobranzaClienteController::class, 'buscarClienteDocumento'])->name('buscar-cliente-documento');

			Route::get('ver-cliente/{id_contribuyente}', [CobranzaClienteController::class, 'ver'])->name('ver-cliente');
			Route::post('actualizar-cliente', [CobranzaClienteController::class, 'actualizar'])->name('actualizar-cliente');
			Route::post('eliminar-cliente', [CobranzaClienteController::class, 'eliminar'])->name('eliminar-cliente');
			Route::get('get-distrito/{id_provincia}', [CobranzaClienteController::class, 'getDistrito'])->name('get-distrito');

			Route::get('editar-contribuyente/{id_contribuyente}', [CobranzaClienteController::class, 'editarContribuyente'])->name('editar-contribuyente');
			Route::post('buscar-cliente-documento-editar', [CobranzaClienteController::class, 'buscarClienteDocumentoEditar'])->name('buscar-cliente-documento-editar');
			Route::post('nuevo-cliente', [RegistroController::class, 'nuevoCliente'])->name('nuevo-cliente');

			Route::get('get-provincia/{id_departamento}', [RegistroController::class, 'provincia'])->name('get-provincia');
			Route::get('get-distrito/{id_provincia}', [RegistroController::class, 'distrito'])->name('get-distrito');

			Route::get('get-cliente/{id_cliente}', [RegistroController::class, 'getCliente'])->name('get-cliente');
			Route::get('buscar-factura/{factura}', [RegistroController::class, 'getFactura'])->name('buscar-factura');
			Route::get('actualizar-ven-doc-req', [RegistroController::class, 'actualizarDocVentReq'])->name('ctualizar-ven-doc-req');

			Route::post('modificar-registro', [RegistroController::class, 'modificarRegistro']);
			Route::post('buscar-vendedor', [RegistroController::class, 'buscarVendedor']);
			Route::get('buscar-cliente-seleccionado/{id}', [RegistroController::class, 'buscarClienteSeleccionado']);
			Route::post('exportar-excel-prueba', [RegistroController::class, 'exportarExcelPrueba']);
			Route::get('editar-penalidad/{id}', [RegistroController::class, 'editarPenalidad']);
			Route::post('eliminar-penalidad', [RegistroController::class, 'eliminarPenalidad']);

			Route::get('exportar-excel-power-bi/{request}', [RegistroController::class, 'exportarExcelPowerBI']);

            Route::get('editar-penalidad/{id}', [RegistroController::class,'editarPenalidad']);
            Route::post('eliminar-penalidad', [RegistroController::class,'eliminarPenalidad']);


		});

		// Fondos, Auspicios y Rebates
		Route::group(['as' => 'fondos.', 'prefix' => 'fondos'], function () {
			Route::get('index', [CobranzaFondoController::class, 'index'])->name('index');
			Route::post('listar', [CobranzaFondoController::class, 'lista'])->name('listar');
			Route::post('guardar', [CobranzaFondoController::class, 'guardar'])->name('guardar');
			Route::post('eliminar', [CobranzaFondoController::class, 'eliminar'])->name('eliminar');
			Route::post('cargar-cobro', [CobranzaFondoController::class, 'cargarCobro'])->name('cargar-cobro');
			Route::post('guardar-cobro', [CobranzaFondoController::class, 'guardarCobro'])->name('guardar-cobro');
			Route::get('exportar-excel', [CobranzaFondoController::class, 'exportarExcel'])->name('exportar-excel');
		});

		// Devolución de penalidades
		Route::group(['as' => 'devoluciones.', 'prefix' => 'devoluciones'], function () {
			Route::get('index', [DevolucionPenalidadController::class, 'index'])->name('index');
			Route::post('listar', [DevolucionPenalidadController::class, 'lista'])->name('listar');
			Route::post('guardar', [DevolucionPenalidadController::class, 'guardar'])->name('guardar');
			Route::post('guardar-pagador', [DevolucionPenalidadController::class, 'guardarPagador'])->name('guardar-pagador');
			Route::post('cargar-cobro-dev', [DevolucionPenalidadController::class, 'cargarCobroDev'])->name('cargar-cobro-dev');
			Route::post('eliminar', [DevolucionPenalidadController::class, 'eliminar'])->name('eliminar');
			Route::get('exportar-excel', [DevolucionPenalidadController::class, 'exportarExcel'])->name('exportar-excel');
		});
	});

	/**
	 * Tesorería
	 */
	Route::name('tesoreria.')->prefix('tesoreria')->group(function () {

		Route::get('index', [RegistroPagoController::class, 'view_main_tesoreria'])->name('index');

		Route::name('pagos.')->prefix('pagos')->group(function () {

			Route::name('procesar-pago.')->prefix('procesar-pago')->group(function () {

				Route::get('index', [RegistroPagoController::class, 'view_pendientes_pago'])->name('index');

				Route::post('guardar-adjuntos-tesoreria', [RegistroPagoController::class, 'guardarAdjuntosTesoreria'])->name('guardar-adjuntos-tesoreria');
				Route::post('listarComprobantesPagos', [RegistroPagoController::class, 'listarComprobantesPagos'])->name('lista-comprobantes-pagos');
				Route::post('listarOrdenesCompra', [RegistroPagoController::class, 'listarOrdenesCompra'])->name('lista-ordenes-compra');
				Route::post('listarRequerimientosPago', [RegistroPagoController::class, 'listarRequerimientosPago'])->name('listar-requerimientos-pago');
				Route::post('procesarPago', [RegistroPagoController::class, 'procesarPago'])->name('procesar-pagos');
				Route::get('listarPagos/{tp}/{id}', [RegistroPagoController::class, 'listarPagos'])->name('listar-pagos');
				Route::get('listarPagosEnCuotas/{tp}/{id}', [RegistroPagoController::class, 'listarPagosEnCuotas'])->name('listar-pagos-en-cuotas');
				// Route::get('pagosComprobante/{id}', 'Tesoreria\RegistroPagoController@pagosComprobante')->name('pagos-comprobante');
				// Route::get('pagosRequerimientos/{id}', 'Tesoreria\RegistroPagoController@pagosRequerimientos')->name('pagos-requerimientos');
				Route::get('cuentasOrigen/{id}', [RegistroPagoController::class, 'cuentasOrigen'])->name('cuentas-origen');
				Route::get('anularPago/{id}', [RegistroPagoController::class, 'anularPago'])->name('anular-pago');
				Route::post('enviarAPago', [RegistroPagoController::class, 'enviarAPago'])->name('enviar-pago');
				Route::post('revertirEnvio', [RegistroPagoController::class, 'revertirEnvio'])->name('revertir-envio');
				Route::get('verAdjuntos/{id}', [RegistroPagoController::class, 'verAdjuntos'])->name('ver-adjuntos');
				Route::get('verAdjuntosRegistroPagoOrden/{id}', [RegistroPagoController::class, 'verAdjuntosRegistroPagoOrden'])->name('ver-adjuntos-registro-pago-orden');
				Route::get('verAdjuntosRequerimientoDeOrden/{id}', [RegistroPagoController::class, 'verAdjuntosRequerimientoDeOrden'])->name('ver-adjuntos-requerimiento-de-orden');
				Route::post('anular-adjunto-requerimiento-pago-tesoreria', [RegistroPagoController::class, 'anularAdjuntoTesoreria'])->name('anular-adjunto-requerimiento-pago');
				Route::get('listar-archivos-adjuntos-pago/{id}', [RequerimientoController::class, 'listarArchivoAdjuntoPago'])->name('listar-archivos-adjuntos-pago');
				Route::get('lista-adjuntos-pago/{idRequerimientoPago}', [RegistroPagoController::class, 'listarAdjuntosPago'])->name('listar-adjuntos-pago');
				Route::get('verAdjuntosPago/{id}', [RegistroPagoController::class, 'verAdjuntosPago'])->name('ver-adjuntos-pago');
				Route::get('actualizarEstadoPago', [RegistroPagoController::class, 'actualizarEstadoPago'])->name('actualizar-estados-pago');
				Route::get('mostrar-requerimiento-pago/{idRequerimientoPago}', [RequerimientoPagoController::class, 'mostrarRequerimientoPago'])->name('mostrar-requerimiento-pago');
				Route::get('registro-pagos-exportar-excel', [RegistroPagoController::class, 'registroPagosExportarExcel'])->name('registro-pagos-exportar-excel');
				Route::get('ordenes-compra-servicio-exportar-excel', [RegistroPagoController::class, 'ordenesCompraServicioExportarExcel'])->name('ordenes-compra-servicio-exportar-excel');
				Route::get('listar-archivos-adjuntos-orden/{id_order}', [OrdenController::class, 'listarArchivosOrder'])->name('listar-archivos-adjuntos-orden');

				#exportar excel con los fltros aplicados
				Route::post('exportar-requerimientos-pagos', [RegistroPagoController::class, 'exportarRequerimientosPagos'])->name('exportar-requerimientos-pagos');
				Route::post('exportar-requerimientos-pagos-items', [RegistroPagoController::class, 'exportarRequerimientosPagosItems'])->name('exportar-requerimientos-pagos-items');

				#exportar excel con los fltros aplicados
				Route::post('exportar-ordeners-compras-servicios', [RegistroPagoController::class, 'exportarOrdenesComprasServicios'])->name('exportar-ordeners-compras-servicios');
				Route::post('exportar-ordeners-compras-servicios-items', [RegistroPagoController::class, 'exportarOrdenesComprasServiciosItems'])->name('exportar-ordeners-compras-servicios-items');

				// lista adjuntos pago
				// Route::get('adjuntos-pago/{id}', 'OrdenController@listarArchivosOrder');

                Route::get('cuadro-comparativo-pagos', [RegistroPagoController::class, 'cuadroComparativoPagos'])->name('cuadro-comparativo-pagos');
                Route::get('cuadro-comparativo-ordenes', [RegistroPagoController::class, 'cuadroComparativoOrdenes'])->name('cuadro-comparativo-ordenes');
			});

			Route::name('confirmacion-pagos.')->prefix('confirmacion-pagos')->group(function () {

				Route::get('index', [DistribucionController::class, 'view_confirmacionPago'])->name('index');

				Route::post('listarRequerimientosPendientesPagos', [DistribucionController::class, 'listarRequerimientosPendientesPagos'])->name('listar-requerimientos-pendientes-pagos');
				Route::post('listarRequerimientosConfirmadosPagos', [DistribucionController::class, 'listarRequerimientosConfirmadosPagos'])->name('listar-requerimientos-confirmados-pagos');
				Route::post('pago_confirmado', [DistribucionController::class, 'pago_confirmado'])->name('confirmar-pago');
				Route::post('pago_no_confirmado', [DistribucionController::class, 'pago_no_confirmado'])->name('pago-no-confirmado');
				Route::get('verDetalleRequerimientoDI/{id}', [OrdenesTransformacionController::class, 'verDetalleRequerimientoDI'])->name('ver-detalle-requerimiento-di');
				Route::get('verRequerimientoAdjuntos/{id}', [OrdenesTransformacionController::class, 'verRequerimientoAdjuntos'])->name('ver-requerimiento-adjunto');
			});
		});

		Route::name('facturacion.')->prefix('facturacion')->group(function () {

			Route::get('index', [PendientesFacturacionController::class, 'view_pendientes_facturacion'])->name('index');
			Route::post('listarGuiasVentaPendientes', [PendientesFacturacionController::class, 'listarGuiasVentaPendientes'])->name('listar-guias-pendientes');
			Route::post('listarRequerimientosPendientes', [PendientesFacturacionController::class, 'listarRequerimientosPendientes'])->name('listar-requerimientos-pendientes');
			Route::post('guardar_doc_venta', [PendientesFacturacionController::class, 'guardar_doc_venta'])->name('guardar-doc-venta');
			Route::get('documentos_ver/{id}', [PendientesFacturacionController::class, 'documentos_ver'])->name('ver-doc-venta');
			Route::post('anular_doc_ven', [PendientesFacturacionController::class, 'anular_doc_ven'])->name('anular-doc-venta');
			Route::get('obtenerGuiaVenta/{id}', [PendientesFacturacionController::class, 'obtenerGuiaVenta'])->name('obtener-guia-venta');
			Route::post('obtenerGuiaVentaSeleccionadas', [PendientesFacturacionController::class, 'obtenerGuiaVentaSeleccionadas'])->name('obtener-guias-ventas');
			Route::get('obtenerRequerimiento/{id}', [PendientesFacturacionController::class, 'obtenerRequerimiento'])->name('obtener-requerimiento');
			Route::get('detalleFacturasGuias/{id}', [PendientesFacturacionController::class, 'detalleFacturasGuias'])->name('detalle-facturas-guia');
			Route::get('detalleFacturasRequerimientos/{id}', [PendientesFacturacionController::class, 'detalleFacturasRequerimientos'])->name('detalle-facturas-guia');
			Route::post('obtenerArchivosOc', [PendientesFacturacionController::class, 'obtenerArchivosOc'])->name('obtener-archivos-oc');
			Route::get('autogenerarDocumentosCompra/{id}', [VentasInternasController::class, 'autogenerarDocumentosCompra'])->name('autogenerar-documentos-compra');
			Route::get('listado-ventas-internas-exportar-excel', [PendientesFacturacionController::class, 'listadoVentasInternasExportarExcel'])->name('listado-ventas-internas-exportar-excel');
			Route::get('listado-ventas-externas-exportar-excel', [PendientesFacturacionController::class, 'listadoVentasExternasExportarExcel'])->name('listado-ventas-externas-exportar-excel');
			Route::post('guardar-adjuntos-factura', [PendientesFacturacionController::class, 'guardarAdjuntosFactura'])->name('guardar-adjuntos-factura');
			Route::get('ver-adjuntos', [PendientesFacturacionController::class, 'verAdjuntos'])->name('ver-adjuntos');
			Route::post('eliminar-adjuntos', [PendientesFacturacionController::class, 'eliminarAdjuntos'])->name('eliminar-adjuntos');
		});

		Route::name('comprobante-compra.')->prefix('comprobante-compra')->group(function () {
			Route::get('index', [ContabilidadController::class, 'view_comprobante_compra'])->name('index');
			Route::get('ordenes_sin_facturar/{id_empresa}/{all_or_id_orden}', [ContabilidadController::class, 'ordenes_sin_facturar'])->name('ordenes-sin-facturar');
			Route::post('guardar_comprobante_compra', [ContabilidadController::class, 'guardar_comprobante_compra'])->name('guardar-comprobante-compra');
			Route::get('lista_comprobante_compra/{id_sede}/{all_or_id_doc_com}', [ContabilidadController::class, 'lista_comprobante_compra'])->name('lista-comprobante-compra');
		});

		Route::name('documento-compra.')->prefix('documento-compra')->group(function () {
			//Documento de compra
			Route::get('index', [ComprobanteCompraController::class, 'view_crear_comprobante_compra'])->name('index');
			// Route::post('mostrar_prods', 'Almacen\Catalogo\ProductoController@mostrar_prods');
			// Route::get('listarDevoluciones', 'Almacen\Movimiento\DevolucionController@listarDevoluciones');
		});

		Route::name('tipo-cambio.')->prefix('tipo-cambio')->group(function () {
			Route::get('index', [TipoCambioController::class, 'index'])->name('index');
			Route::post('listar', [TipoCambioController::class, 'listar'])->name('listar');
			Route::post('editar', [TipoCambioController::class, 'editar'])->name('editar');
			Route::post('guardar', [TipoCambioController::class, 'guardar'])->name('guardar');
		});

		Route::name('cierre-apertura.')->prefix('cierre-apertura')->group(function () {
			Route::get('index', [CierreAperturaController::class, 'index'])->name('index');
			Route::post('listar', [CierreAperturaController::class, 'listar'])->name('listar');
			Route::get('mostrarSedesPorEmpresa/{id}', [CierreAperturaController::class, 'mostrarSedesPorEmpresa'])->name('mostrar-sedes-empresa');
			Route::get('mostrarAlmacenesPorSede/{id}', [CierreAperturaController::class, 'mostrarAlmacenesPorSede'])->name('mostrar-almacenes-sede');
			Route::post('guardar', [CierreAperturaController::class, 'guardarAccion'])->name('guardar');
			Route::post('guardarVarios', [CierreAperturaController::class, 'guardarVarios'])->name('guardar-varios');
			Route::post('guardarCierreAnual', [CierreAperturaController::class, 'guardarCierreAnual'])->name('guardar-cierre-anual');
			Route::post('guardarCierreAnualOperativo', [CierreAperturaController::class, 'guardarCierreAnualOperativo'])->name('guardar-cierre-anual-operativo');
			Route::get('cargarMeses/{id}', [CierreAperturaController::class, 'cargarMeses'])->name('cargar-meses');
			Route::get('listaHistorialAcciones/{id}', [CierreAperturaController::class, 'listaHistorialAcciones'])->name('lista-historial-acciones');
			Route::get('consultarPeriodo/{fec}/{id}', [CierreAperturaController::class, 'consultarPeriodo'])->name('consultar-periodo');
			Route::get('autogenerarPeriodos/{aaaa}', [CierreAperturaController::class, 'autogenerarPeriodos'])->name('autogenerar-periodos');
		});
	});

	/**
	 * Migración
	 */
	Route::name('migracion.')->prefix('migracion')->group(function () {
		Route::get('index', [MigracionAlmacenSoftLinkController::class, 'index'])->name('index');
		Route::get('movimientos', [MigracionAlmacenSoftLinkController::class, 'movimientos'])->name('movimientos');
		Route::post('importar', [MigracionAlmacenSoftLinkController::class, 'importar'])->name('importar');

		Route::name('softlink.')->prefix('softlink')->group(function () {
			Route::get('index', [MigracionAlmacenSoftLinkController::class, 'view_migracion_series'])->name('index');
			Route::post('importar', [MigracionAlmacenSoftLinkController::class, 'importarSeries'])->name('importar');
			Route::get('exportar', [MigracionAlmacenSoftLinkController::class, 'exportarSeries'])->name('exportar');
			Route::get('test', [MigracionAlmacenSoftLinkController::class, 'testSeries'])->name('test');

			# actualizar productos al softlink
			Route::get('actualizar-productos', [MigracionAlmacenSoftLinkController::class, 'view_actualizar_productos'])->name('actualizar-productos-softlink');
			Route::get('descargar-modelo', [MigracionAlmacenSoftLinkController::class, 'descargarModelo'])->name('descargar-modelo');
			Route::post('enviar-modelo-agil-softlink', [MigracionAlmacenSoftLinkController::class, 'enviarModeloAgilSoftlink'])->name('actualizar');
		});
	});

	/**
	 * Power Bi
	 */
	Route::group(['as' => 'power-bi.', 'prefix' => 'power-bi'], function () {
		Route::group(['as' => 'ventas.', 'prefix' => 'ventas'], function () {
			Route::get('index', function () {
				return view('power-bi/ventas');
			})->name('index');
		});
		Route::group(['as' => 'cobranzas.', 'prefix' => 'cobranzas'], function () {
			Route::get('index', function () {
				return view('power-bi/cobranzas');
			})->name('index');
		});
		Route::group(['as' => 'inventario.', 'prefix' => 'inventario'], function () {
			Route::get('index', function () {
				return view('power-bi/inventario');
			})->name('index');
		});
	});

	/**
	 * Proyectos
	 */
	Route::group(['as' => 'proyectos.', 'prefix' => 'proyectos'], function () {
		Route::get('getProyectosActivos', [ProyectosController::class, 'getProyectosActivos'])->name('getProyectosActivos');
		Route::get('index', [ProyectosController::class, 'index'])->name('index');

		Route::group(['as' => 'variables-entorno.', 'prefix' => 'variables-entorno'], function () {
			Route::group(['as' => 'tipos-insumo.', 'prefix' => 'tipos-insumo'], function () {
				Route::get('index', [TipoInsumoController::class, 'view_tipo_insumo'])->name('index');
				Route::get('listar_tipo_insumos', [TipoInsumoController::class, 'mostrar_tipos_insumos'])->name('listar_tipo_insumos');
				Route::get('mostrar_tipo_insumo/{id}', [TipoInsumoController::class, 'mostrar_tp_insumo'])->name('mostrar_tipo_insumo');
				Route::post('guardar_tipo_insumo', [TipoInsumoController::class, 'guardar_tp_insumo'])->name('guardar_tipo_insumo');
				Route::post('actualizar_tipo_insumo', [TipoInsumoController::class, 'update_tp_insumo'])->name('actualizar_tipo_insumo');
				Route::get('anular_tipo_insumo/{id}', [TipoInsumoController::class, 'anular_tp_insumo'])->name('anular_tipo_insumo');
				Route::get('revisar_tipo_insumo/{id}', [TipoInsumoController::class, 'buscar_tp_insumo'])->name('revisar_tipo_insumo');
			});

			Route::group(['as' => 'sistemas-contrato.', 'prefix' => 'sistemas-contrato'], function () {
				Route::get('index', [SistemasContratoController::class, 'view_sis_contrato'])->name('index');
				Route::get('listar', [SistemasContratoController::class, 'mostrar_sis_contratos'])->name('listar');
				Route::get('mostrar/{id?}', [SistemasContratoController::class, 'mostrar_sis_contrato'])->name('mostrar');
				Route::post('guardar', [SistemasContratoController::class, 'guardar_sis_contrato'])->name('guardar');
				Route::post('actualizar', [SistemasContratoController::class, 'update_sis_contrato'])->name('actualizar');
				Route::get('anular/{id}', [SistemasContratoController::class, 'anular_sis_contrato'])->name('anular');
			});

			Route::group(['as' => 'iu.', 'prefix' => 'iu'], function () { // Indices Unificados
				Route::get('index', [IuController::class, 'view_iu'])->name('index');
				Route::get('listar_ius', [IuController::class, 'mostrar_ius'])->name('listar_ius');
				Route::get('mostrar_iu/{id}', [IuController::class, 'mostrar_iu'])->name('mostrar_iu');
				Route::post('guardar_iu', [IuController::class, 'guardar_iu'])->name('guardar_iu');
				Route::post('actualizar_iu', [IuController::class, 'update_iu'])->name('actualizar_iu');
				Route::get('anular_iu/{id}', [IuController::class, 'anular_iu'])->name('anular_iu');
				Route::get('revisar_iu/{id}', [IuController::class, 'buscar_iu'])->name('revisar_iu');
			});

			Route::group(['as' => 'categorias-insumo.', 'prefix' => 'categorias-insumo'], function () {
				Route::get('index', [CategoriaInsumoController::class, 'view_cat_insumo'])->name('index');
				Route::get('listar_cat_insumos', [CategoriaInsumoController::class, 'listar_cat_insumos'])->name('listar_cat_insumos');
				Route::get('mostrar_cat_insumo/{id}', [CategoriaInsumoController::class, 'mostrar_cat_insumo'])->name('mostrar_cat_insumo');
				Route::post('guardar_cat_insumo', [CategoriaInsumoController::class, 'guardar_cat_insumo'])->name('guardar_cat_insumo');
				Route::post('update_cat_insumo', [CategoriaInsumoController::class, 'update_cat_insumo'])->name('update_cat_insumo');
				Route::get('anular_cat_insumo/{id}', [CategoriaInsumoController::class, 'anular_cat_insumo'])->name('anular_cat_insumo');
			});

			Route::group(['as' => 'categorias-acu.', 'prefix' => 'categorias-acu'], function () {
				Route::get('index', [CategoriaAcuController::class, 'view_cat_acu'])->name('index');
				Route::get('listar_cat_acus', [CategoriaAcuController::class, 'listar_cat_acus'])->name('listar_cat_acus');
				Route::get('mostrar_cat_acu/{id}', [CategoriaAcuController::class, 'mostrar_cat_acu'])->name('mostrar_cat_acu');
				Route::post('guardar_cat_acu', [CategoriaAcuController::class, 'guardar_cat_acu'])->name('guardar_cat_acu');
				Route::post('update_cat_acu', [CategoriaAcuController::class, 'update_cat_acu'])->name('update_cat_acu');
				Route::get('anular_cat_acu/{id}', [CategoriaAcuController::class, 'anular_cat_acu'])->name('anular_cat_acu');
			});
		});

		Route::group(['as' => 'catalogos.', 'prefix' => 'catalogos'], function () {
			Route::group(['as' => 'insumos.', 'prefix' => 'insumos'], function () {
				Route::get('index', [InsumoController::class, 'view_insumo'])->name('index');
				Route::get('listar_insumos', [InsumoController::class, 'listar_insumos'])->name('listar_insumos');
				Route::get('mostrar_insumo/{id}', [InsumoController::class, 'mostrar_insumo'])->name('mostrar_insumo');
				Route::post('guardar_insumo', [InsumoController::class, 'guardar_insumo'])->name('guardar_insumo');
				Route::post('actualizar_insumo', [InsumoController::class, 'update_insumo'])->name('actualizar_insumo');
				Route::get('anular_insumo/{id}', [InsumoController::class, 'anular_insumo'])->name('anular_insumo');
				Route::get('listar_insumo_precios/{id}', [InsumoController::class, 'listar_insumo_precios'])->name('listar_insumo_precios');
				Route::post('add_unid_med', [InsumoController::class, 'add_unid_med'])->name('add_unid_med');
			});

			Route::group(['as' => 'nombres-cu.', 'prefix' => 'nombres-cu'], function () {
				Route::get('index', [NombresAcuController::class, 'view_nombres_cu'])->name('index');
				Route::get('listar_cus', [NombresAcuController::class, 'listar_nombres_cus'])->name('listar_cus');
				Route::post('guardar_cu', [NombresAcuController::class, 'guardar_cu'])->name('guardar_cu');
				Route::post('update_cu', [NombresAcuController::class, 'update_cu'])->name('update_cu');
				Route::get('anular_cu/{id}', [NombresAcuController::class, 'anular_cu'])->name('anular_cu');
				Route::get('listar_partidas_cu/{id}', [NombresAcuController::class, 'listar_partidas_cu'])->name('listar_partidas_cu');
			});

			Route::group(['as' => 'acus.', 'prefix' => 'acus'], function () {
				Route::get('index', [AcuController::class, 'view_acu'])->name('index');
				Route::get('listar_acus', [AcuController::class, 'listar_acus'])->name('listar_acus');
				Route::get('listar_acus_sin_presup', [AcuController::class, 'listar_acus_sin_presup'])->name('listar_acus_sin_presup');
				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu'])->name('mostrar_acu');
				Route::get('listar_acu_detalle/{id}', [AcuController::class, 'listar_acu_detalle'])->name('listar_acu_detalle');
				Route::get('listar_insumo_precios/{id}', [InsumoController::class, 'listar_insumo_precios'])->name('listar_insumo_precios');

				Route::post('guardar_acu', [AcuController::class, 'guardar_acu'])->name('guardar_acu');
				Route::post('actualizar_acu', [AcuController::class, 'update_acu'])->name('actualizar_acu');
				Route::get('anular_acu/{id}', [AcuController::class, 'anular_acu'])->name('anular_acu');
				Route::get('valida_acu_editar/{id}', [AcuController::class, 'valida_acu_editar'])->name('valida_acu_editar');

				Route::get('partida_insumos_precio/{id}/{ins}', [AcuController::class, 'partida_insumos_precio'])->name('partida_insumos_precio');
				Route::post('guardar_insumo', [InsumoController::class, 'guardar_insumo'])->name('guardar_insumo');
				Route::get('listar_insumos', [InsumoController::class, 'listar_insumos'])->name('listar_insumos');

				Route::post('guardar_cu', [AcuController::class, 'guardar_cu'])->name('guardar_cu');
				Route::post('update_cu', [AcuController::class, 'update_cu'])->name('update_cu');
				Route::get('listar_cus', [NombresAcuController::class, 'listar_nombres_cus'])->name('listar_cus');
				Route::get('mostrar_presupuestos_acu/{id}', [AcuController::class, 'mostrar_presupuestos_acu'])->name('mostrar_presupuestos_acu');
			});
		});

		//////////////////////// falta ordenar
		Route::group(['as' => 'opciones.', 'prefix' => 'opciones'], function () {

			Route::group(['as' => 'opciones.', 'prefix' => 'opciones'], function () {
				//Opciones
				Route::get('index', [OpcionesController::class, 'view_opcion'])->name('index');
				Route::get('listar_opciones', [OpcionesController::class, 'listar_opciones'])->name('lista-opciones');
				Route::post('guardar_opcion', [OpcionesController::class, 'guardar_opcion'])->name('guardar-opciones');
				Route::post('actualizar_opcion', [OpcionesController::class, 'update_opcion'])->name('actualizar-opciones');
				Route::get('anular_opcion/{id}', [OpcionesController::class, 'anular_opcion'])->name('anular-opciones');
				Route::post('guardar_cliente', [ClienteController::class, 'guardar_cliente'])->name('guardar-cliente');
				Route::get('mostrar_clientes', [ClienteController::class, 'mostrar_clientes'])->name('mostrar-clientes');
			});

			Route::group(['as' => 'presupuestos-internos.', 'prefix' => 'presupuestos-internos'], function () {
				/**Presupuesto Interno */
				Route::get('index', [OpcionesPresupuestoInternoController::class, 'view_presint'])->name('index');
				Route::get('mostrar_presint/{id}', [OpcionesPresupuestoInternoController::class, 'mostrar_presint']);
				Route::post('guardar_presint', [OpcionesPresupuestoInternoController::class, 'guardar_presint']);
				Route::post('update_presint', [OpcionesPresupuestoInternoController::class, 'update_presint']);
				Route::get('anular_presint/{id}', [OpcionesPresupuestoInternoController::class, 'anular_presint']);

				Route::get('generar_estructura/{id}/{tp}', [OpcionesPresupuestoInternoController::class, 'generar_estructura']);
				Route::get('listar_presupuesto_proyecto/{id}', [OpcionesPresupuestoInternoController::class, 'listar_presupuesto_proyecto']);
				Route::get('totales/{id}', [OpcionesPresupuestoInternoController::class, 'totales']);
				Route::get('download_presupuesto/{id}', [OpcionesPresupuestoInternoController::class, 'download_presupuesto']);
				Route::get('actualiza_moneda/{id}', [OpcionesPresupuestoInternoController::class, 'actualiza_moneda']);
				Route::get('mostrar_presupuestos/{id}', [OpcionesPresupuestoInternoController::class, 'mostrar_presupuestos']);
				Route::get('listar_presupuestos_copia/{tp}/{id}', [OpcionesPresupuestoInternoController::class, 'listar_presupuestos_copia']);
				Route::get('generar_partidas_presupuesto/{id}/{ida}', [OpcionesPresupuestoInternoController::class, 'generar_partidas_presupuesto']);

				Route::get('listar_acus_cd/{id}', [ComponentesController::class, 'listar_acus_cd']);
				Route::get('listar_cd/{id}', [ComponentesController::class, 'listar_cd']);
				Route::get('listar_ci/{id}', [ComponentesController::class, 'listar_ci']);
				Route::get('listar_gg/{id}', [ComponentesController::class, 'listar_gg']);
				Route::post('guardar_componente_cd', [ComponentesController::class, 'guardar_componente_cd']);
				Route::post('guardar_componente_ci', [ComponentesController::class, 'guardar_componente_ci']);
				Route::post('guardar_componente_gg', [ComponentesController::class, 'guardar_componente_gg']);
				Route::post('update_componente_cd', [ComponentesController::class, 'update_componente_cd']);
				Route::post('update_componente_ci', [ComponentesController::class, 'update_componente_ci']);
				Route::post('update_componente_gg', [ComponentesController::class, 'update_componente_gg']);
				Route::post('anular_compo_cd', [ComponentesController::class, 'anular_compo_cd']);
				Route::post('anular_compo_ci', [ComponentesController::class, 'anular_compo_ci']);
				Route::post('anular_compo_gg', [ComponentesController::class, 'anular_compo_gg']);

				Route::post('guardar_partida_cd', [PartidasController::class, 'guardar_partida_cd']);
				Route::post('guardar_partida_ci', [PartidasController::class, 'guardar_partida_ci']);
				Route::post('guardar_partida_gg', [PartidasController::class, 'guardar_partida_gg']);
				Route::post('update_partida_cd', [PartidasController::class, 'update_partida_cd']);
				Route::post('update_partida_ci', [PartidasController::class, 'update_partida_ci']);
				Route::post('update_partida_gg', [PartidasController::class, 'update_partida_gg']);
				Route::post('anular_partida_cd', [PartidasController::class, 'anular_partida_cd']);
				Route::post('anular_partida_ci', [PartidasController::class, 'anular_partida_ci']);
				Route::post('anular_partida_gg', [PartidasController::class, 'anular_partida_gg']);
				Route::get('subir_partida_cd/{id}', [PartidasController::class, 'subir_partida_cd']);
				Route::get('subir_partida_ci/{id}', [PartidasController::class, 'subir_partida_ci']);
				Route::get('subir_partida_gg/{id}', [PartidasController::class, 'subir_partida_gg']);
				Route::get('bajar_partida_cd/{id}', [PartidasController::class, 'bajar_partida_cd']);
				Route::get('bajar_partida_ci/{id}', [PartidasController::class, 'bajar_partida_ci']);
				Route::get('bajar_partida_gg/{id}', [PartidasController::class, 'bajar_partida_gg']);
				Route::get('crear_titulos_ci/{id}', [PartidasController::class, 'crear_titulos_ci']);
				Route::get('crear_titulos_gg/{id}', [PartidasController::class, 'crear_titulos_gg']);

				Route::post('add_unid_med', [InsumoController::class, 'add_unid_med']);
				Route::post('update_unitario_partida_cd', [OpcionesPresupuestoInternoController::class, 'update_unitario_partida_cd']);
				Route::get('listar_acus_sin_presup', [AcuController::class, 'listar_acus_sin_presup']);

				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu']);
				Route::get('partida_insumos_precio/{id}/{ins}', [AcuController::class, 'partida_insumos_precio']);
				Route::get('listar_acu_detalle/{id}', [AcuController::class, 'listar_acu_detalle']);
				Route::post('guardar_acu', [AcuController::class, 'guardar_acu']);
				Route::post('actualizar_acu', [AcuController::class, 'update_acu']);

				Route::post('guardar_cu', [AcuController::class, 'guardar_cu']);
				Route::post('update_cu', [AcuController::class, 'update_cu']);
				// Route::get('listar_cus', [AcuController::class, 'listar_cus']);
				Route::get('listar_cus', [NombresAcuController::class, 'listar_nombres_cus']);

				Route::get('listar_insumos', [InsumoController::class, 'listar_insumos']);
				Route::get('mostrar_insumo/{id}', [InsumoController::class, 'mostrar_insumo']);
				Route::post('guardar_insumo', [InsumoController::class, 'guardar_insumo']);
				Route::get('listar_insumo_precios/{id}', [InsumoController::class, 'listar_insumo_precios']);
				// Route::post('guardar_precio', [ProyectosController::class, 'guardar_precio']);
				Route::post('actualizar_insumo', [InsumoController::class, 'update_insumo']);
				Route::get('listar_opciones_sin_presint', [OpcionesController::class, 'listar_opciones_sin_presint']);

				Route::get('listar_obs_cd/{id}', [PartidasController::class, 'listar_obs_cd']);
				Route::get('listar_obs_ci/{id}', [PartidasController::class, 'listar_obs_ci']);
				Route::get('listar_obs_gg/{id}', [PartidasController::class, 'listar_obs_gg']);
				Route::get('anular_obs_partida/{id}', [PartidasController::class, 'anular_obs_partida']);
				Route::post('guardar_obs_partida', [PartidasController::class, 'guardar_obs_partida']);
			});

			Route::group(['as' => 'cronogramas-internos.', 'prefix' => 'cronogramas-internos'], function () {
				//Cronograma Interno
				Route::get('index', [CronogramaInternoController::class, 'view_cronoint'])->name('index');
				Route::get('nuevo_cronograma/{id}', [CronogramaInternoController::class, 'nuevo_cronograma']);
				Route::get('listar_cronograma/{id}', [CronogramaInternoController::class, 'listar_cronograma']);
				Route::post('guardar_crono', [CronogramaInternoController::class, 'guardar_crono']);
				Route::get('anular_crono/{id}', [CronogramaInternoController::class, 'anular_crono']);
				Route::get('ver_gant/{id}', [CronogramaInternoController::class, 'ver_gant']);
				Route::get('listar_pres_crono/{tc}/{tp}', [CronogramaInternoController::class, 'listar_pres_crono']);
				Route::get('actualizar_partidas_cronograma/{id}', [CronogramaInternoController::class, 'actualizar_partidas_cronograma']);

				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu']);
				Route::get('listar_obs_cd/{id}', [PartidasController::class, 'listar_obs_cd']);
				Route::get('listar_obs_ci/{id}', [PartidasController::class, 'listar_obs_ci']);
				Route::get('listar_obs_gg/{id}', [PartidasController::class, 'listar_obs_gg']);
				Route::get('anular_obs_partida/{id}', [PartidasController::class, 'anular_obs_partida']);
				Route::post('guardar_obs_partida', [PartidasController::class, 'guardar_obs_partida']);
			});

			Route::group(['as' => 'cronogramas-valorizados-internos.', 'prefix' => 'cronogramas-valorizados-internos'], function () {
				//Cronograma Valorizado Interno
				Route::get('index', [CronogramaValorizadoInternoController::class, 'view_cronovalint'])->name('index');
				Route::get('nuevo_crono_valorizado/{id}', [CronogramaValorizadoInternoController::class, 'nuevo_crono_valorizado']);
				Route::get('mostrar_crono_valorizado/{id}', [CronogramaValorizadoInternoController::class, 'mostrar_crono_valorizado']);
				Route::get('download_cronoval/{id}/{nro}', [CronogramaValorizadoInternoController::class, 'download_cronoval']);
				Route::post('guardar_cronoval_presupuesto', [CronogramaValorizadoInternoController::class, 'guardar_cronoval_presupuesto']);
				Route::get('anular_cronoval/{id}', [CronogramaValorizadoInternoController::class, 'anular_cronoval']);
				Route::get('listar_pres_cronoval/{tc}/{tp}', [CronogramaValorizadoInternoController::class, 'listar_pres_cronoval']);
			});
		});

		Route::group(['as' => 'propuestas.', 'prefix' => 'propuestas'], function () {
			Route::group(['as' => 'propuestas-cliente.', 'prefix' => 'propuestas-cliente'], function () {
				//Propuesta Cliente
				Route::get('index', [ProyectosController::class, 'view_propuesta'])->name('index');
				Route::get('listar_propuestas', [ProyectosController::class, 'listar_propuestas']);
				Route::get('mostrar_propuesta/{id}', [ProyectosController::class, 'mostrar_propuesta']);
				Route::get('listar_partidas_propuesta/{id}', [ProyectosController::class, 'listar_partidas_propuesta']);
				Route::post('guardar_presup', [ProyectosController::class, 'guardar_presup']);
				Route::post('update_presup', [ProyectosController::class, 'update_presup']);
				Route::get('anular_propuesta/{id}', [ProyectosController::class, 'anular_propuesta']);
				Route::post('guardar_titulo', [ProyectosController::class, 'guardar_titulo']);
				Route::post('update_titulo', [ProyectosController::class, 'update_titulo']);
				Route::post('anular_titulo', [ProyectosController::class, 'anular_titulo']);
				Route::post('guardar_partida', [ProyectosController::class, 'guardar_partida']);
				Route::post('update_partida_propuesta', [ProyectosController::class, 'update_partida_propuesta']);
				Route::get('anular_partida/{id}', [ProyectosController::class, 'anular_partida']);
				Route::get('subir_partida/{id}', [ProyectosController::class, 'subir_partida']);
				Route::get('bajar_partida/{id}', [ProyectosController::class, 'bajar_partida']);
				Route::get('mostrar_detalle_partida/{id}', [ProyectosController::class, 'mostrar_detalle_partida']);
				Route::post('guardar_detalle_partida', [ProyectosController::class, 'guardar_detalle_partida']);
				Route::post('update_detalle_partida', [ProyectosController::class, 'update_detalle_partida']);

				Route::get('download_propuesta/{id}', [ProyectosController::class, 'download_propuesta']);
				Route::get('totales_propuesta/{id}', [ProyectosController::class, 'totales_propuesta']);
				Route::get('mostrar_total_presint/{id}', [ProyectosController::class, 'mostrar_total_presint']);
				Route::get('copiar_partidas_presint/{id}/{pr}', [ProyectosController::class, 'copiar_partidas_presint']);
				Route::get('listar_opciones', [OpcionesController::class, 'listar_opciones']);

				Route::get('listar_obs_cd/{id}', [ProyectosController::class, 'listar_obs_cd']);
				Route::get('listar_obs_ci/{id}', [ProyectosController::class, 'listar_obs_ci']);
				Route::get('listar_obs_gg/{id}', [ProyectosController::class, 'listar_obs_gg']);
				Route::get('anular_obs_partida/{id}', [ProyectosController::class, 'anular_obs_partida']);
				Route::post('guardar_obs_partida', [ProyectosController::class, 'guardar_obs_partida']);

				Route::get('listar_par_det', [ProyectosController::class, 'listar_par_det']);
			});

			Route::group(['as' => 'cronogramas-cliente.', 'prefix' => 'cronogramas-cliente'], function () {
				//Cronograma Cliente
				Route::get('index', [ProyectosController::class, 'view_cronopro'])->name('index');
				Route::get('listar_crono_propuesta/{id}', [ProyectosController::class, 'listar_crono_propuesta']);
				Route::get('listar_cronograma_propuesta/{id}', [ProyectosController::class, 'listar_cronograma_propuesta']);
				Route::post('guardar_crono_propuesta', [ProyectosController::class, 'guardar_crono_propuesta']);
				Route::get('listar_propuesta_crono/{id}', [ProyectosController::class, 'listar_propuesta_crono']);
				Route::get('ver_gant_propuesta/{id}', [ProyectosController::class, 'ver_gant_propuesta']);
				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu']);

				Route::get('listar_obs_cd/{id}', [ProyectosController::class, 'listar_obs_cd']);
				Route::get('listar_obs_ci/{id}', [ProyectosController::class, 'listar_obs_ci']);
				Route::get('listar_obs_gg/{id}', [ProyectosController::class, 'listar_obs_gg']);
				Route::get('anular_obs_partida/{id}', [ProyectosController::class, 'anular_obs_partida']);
				Route::post('guardar_obs_partida', [ProyectosController::class, 'guardar_obs_partida']);
			});

			Route::group(['as' => 'cronogramas-valorizados-cliente.', 'prefix' => 'cronogramas-valorizados-cliente'], function () {
				//Cronograma Valorizado Cliente
				Route::get('index', [ProyectosController::class, 'view_cronovalpro'])->name('index');
				Route::get('mostrar_cronoval_propuesta/{id}', [ProyectosController::class, 'mostrar_cronoval_propuesta']);
				Route::get('listar_cronoval_propuesta/{id}', [ProyectosController::class, 'listar_cronoval_propuesta']);
				Route::post('guardar_cronoval_propuesta', [ProyectosController::class, 'guardar_cronoval_propuesta']);
				Route::get('download_cronopro/{id}/{nro}', [ProyectosController::class, 'download_cronopro']);
				Route::get('listar_propuesta_cronoval/{id}', [ProyectosController::class, 'listar_propuesta_cronoval']);
			});

			Route::group(['as' => 'valorizaciones.', 'prefix' => 'valorizaciones'], function () {
				//Valorizacion
				Route::get('index', [ProyectosController::class, 'view_valorizacion'])->name('index');
				Route::get('listar_propuestas_activas', [ProyectosController::class, 'listar_propuestas_activas']);
				Route::get('mostrar_valorizacion/{id}', [ProyectosController::class, 'mostrar_valorizacion']);
				Route::get('listar_valorizaciones', [ProyectosController::class, 'listar_valorizaciones']);
				Route::get('nueva_valorizacion/{id}', [ProyectosController::class, 'nueva_valorizacion']);
				Route::post('guardar_valorizacion', [ProyectosController::class, 'guardar_valorizacion']);
				Route::post('update_valorizacion', [ProyectosController::class, 'update_valorizacion']);
				Route::get('anular_valorizacion', [ProyectosController::class, 'anular_valorizacion']);
			});
		});

		Route::group(['as' => 'ejecucion.', 'prefix' => 'ejecucion'], function () {
			Route::group(['as' => 'proyectos.', 'prefix' => 'proyectos'], function () {
				//Proyectos
				Route::get('index', [ProyectosController::class, 'view_proyecto'])->name('index');
				Route::get('listar_proyectos', [ProyectosController::class, 'listar_proyectos']);
				Route::get('mostrar_opcion/{id}', [ProyectosController::class, 'mostrar_opcion']);
				Route::get('mostrar_proyecto/{id}', [ProyectosController::class, 'mostrar_proyecto']);
				Route::post('guardar_proyecto', [ProyectosController::class, 'guardar_proyecto']);
				Route::post('actualizar_proyecto', [ProyectosController::class, 'actualizar_proyecto']);
				Route::get('anular_proyecto/{id}', [ProyectosController::class, 'anular_proyecto']);
				Route::get('listar_contratos_proy/{id}', [ProyectosController::class, 'listar_contratos_proy']);
				Route::post('guardar_contrato', [ProyectosController::class, 'guardar_contrato']);
				Route::get('abrir_adjunto/{adjunto}', [ProyectosController::class, 'abrir_adjunto']);
				// Route::get('abrir_adjunto_partida/{adjunto}', [ProyectosController::class, 'abrir_adjunto_partida']);
				Route::get('anular_contrato/{id}', [ProyectosController::class, 'anular_contrato']);
				Route::get('mostrar_presupuestos_acu/{id}', [ProyectosController::class, 'mostrar_presupuestos_acu']);
				Route::get('html_presupuestos_acu/{id}', [ProyectosController::class, 'html_presupuestos_acu']);
				Route::get('listar_opciones', [OpcionesController::class, 'listar_opciones']);
			});

			Route::group(['as' => 'residentes.', 'prefix' => 'residentes'], function () {
				//Residentes
				Route::get('index', [ProyectosController::class, 'view_residentes'])->name('index');
				Route::get('listar_trabajadores', [ProyectosController::class, 'listar_trabajadores']);
				Route::get('listar_residentes', [ProyectosController::class, 'listar_residentes']);
				Route::get('listar_proyectos_residente/{id}', [ProyectosController::class, 'listar_proyectos_residente']);
				// Route::get('anular_proyecto_residente/{id}', [ProyectosController::class, 'anular_proyecto_residente']);
				Route::post('guardar_residente', [ProyectosController::class, 'guardar_residente']);
				Route::post('update_residente', [ProyectosController::class, 'update_residente']);
				Route::get('anular_residente/{id}', [ProyectosController::class, 'anular_residente']);
				Route::get('listar_proyectos', [ProyectosController::class, 'listar_proyectos']);
				// Route::get('listar_proyectos_contratos', [ProyectosController::class, 'listar_proyectos_contratos']);
			});

			Route::group(['as' => 'presupuestos-ejecucion.', 'prefix' => 'presupuestos-ejecucion'], function () {
				//Presupuesto Ejecución
				Route::get('index', [ProyectosController::class, 'view_preseje'])->name('index');
				Route::get('mostrar_presint/{id}', [OpcionesPresupuestoInternoController::class, 'mostrar_presint'])->name('mostrar-presupuesto-interno');
				Route::post('guardar_preseje', [ProyectosController::class, 'guardar_preseje']);
				Route::post('update_preseje', [ProyectosController::class, 'update_preseje']);
				Route::get('anular_presint/{id}', [ProyectosController::class, 'anular_presint']);

				Route::get('generar_estructura/{id}/{tp}', [ProyectosController::class, 'generar_estructura']);
				Route::get('listar_presupuesto_proyecto/{id}', [OpcionesPresupuestoInternoController::class, 'listar_presupuesto_proyecto'])->name('listar-presupuesto-proyecto');
				Route::get('anular_estructura/{id}', [ProyectosController::class, 'anular_estructura']);
				Route::get('totales/{id}', [ProyectosController::class, 'totales']);
				Route::get('download_presupuesto/{id}', [ProyectosController::class, 'download_presupuesto']);
				Route::get('generar_preseje/{id}', [ProyectosController::class, 'generar_preseje']);
				Route::get('actualiza_moneda/{id}', [ProyectosController::class, 'actualiza_moneda']);
				Route::get('mostrar_presupuestos/{id}', [OpcionesPresupuestoInternoController::class, 'mostrar_presupuestos'])->name('mostrar-presupuestos');
				Route::get('listar_presupuestos_copia/{tp}/{id}', [ProyectosController::class, 'listar_presupuestos_copia']);
				Route::get('generar_partidas_presupuesto/{id}/{ida}', [ProyectosController::class, 'generar_partidas_presupuesto']);
				Route::get('listar_proyectos', [ProyectosController::class, 'listar_proyectos']);

				Route::get('listar_acus_cd/{id}', [ComponentesController::class, 'listar_acus_cd']);
				Route::get('listar_cd/{id}', [ComponentesController::class, 'listar_cd']);
				Route::get('listar_ci/{id}', [ComponentesController::class, 'listar_ci']);
				Route::get('listar_gg/{id}', [ComponentesController::class, 'listar_gg']);
				Route::post('guardar_componente_cd', [ComponentesController::class, 'guardar_componente_cd']);
				Route::post('guardar_componente_ci', [ComponentesController::class, 'guardar_componente_ci']);
				Route::post('guardar_componente_gg', [ComponentesController::class, 'guardar_componente_gg']);
				Route::post('update_componente_cd', [ComponentesController::class, 'update_componente_cd']);
				Route::post('update_componente_ci', [ComponentesController::class, 'update_componente_ci']);
				Route::post('update_componente_gg', [ComponentesController::class, 'update_componente_gg']);
				Route::post('anular_compo_cd', [ComponentesController::class, 'anular_compo_cd']);
				Route::post('anular_compo_ci', [ComponentesController::class, 'anular_compo_ci']);
				Route::post('anular_compo_gg', [ComponentesController::class, 'anular_compo_gg']);
				Route::post('guardar_partida_cd', [PartidasController::class, 'guardar_partida_cd']);
				Route::post('guardar_partida_ci', [PartidasController::class, 'guardar_partida_ci']);
				Route::post('guardar_partida_gg', [PartidasController::class, 'guardar_partida_gg']);
				Route::post('update_partida_cd', [PartidasController::class, 'update_partida_cd']);
				Route::post('update_partida_ci', [PartidasController::class, 'update_partida_ci']);
				Route::post('update_partida_gg', [PartidasController::class, 'update_partida_gg']);
				Route::post('anular_partida_cd', [PartidasController::class, 'anular_partida_cd']);
				Route::post('anular_partida_ci', [PartidasController::class, 'anular_partida_ci']);
				Route::post('anular_partida_gg', [PartidasController::class, 'anular_partida_gg']);
				Route::get('subir_partida_cd/{id}', [PartidasController::class, 'subir_partida_cd']);
				Route::get('subir_partida_ci/{id}', [PartidasController::class, 'subir_partida_ci']);
				Route::get('subir_partida_gg/{id}', [PartidasController::class, 'subir_partida_gg']);
				Route::get('bajar_partida_cd/{id}', [PartidasController::class, 'bajar_partida_cd']);
				Route::get('bajar_partida_ci/{id}', [PartidasController::class, 'bajar_partida_ci']);
				Route::get('bajar_partida_gg/{id}', [PartidasController::class, 'bajar_partida_gg']);
				Route::get('crear_titulos_ci/{id}', [ProyectosController::class, 'crear_titulos_ci']);
				Route::get('crear_titulos_gg/{id}', [ProyectosController::class, 'crear_titulos_gg']);

				Route::post('add_unid_med', [InsumoController::class, 'add_unid_med']);
				Route::post('update_unitario_partida_cd', [ProyectosController::class, 'update_unitario_partida_cd']);
				Route::get('listar_acus_sin_presup', [ProyectosController::class, 'listar_acus_sin_presup']);

				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu']);
				Route::get('partida_insumos_precio/{id}/{ins}', [ProyectosController::class, 'partida_insumos_precio']);
				Route::get('listar_acu_detalle/{id}', [ProyectosController::class, 'listar_acu_detalle']);
				Route::post('guardar_acu', [ProyectosController::class, 'guardar_acu']);
				Route::post('actualizar_acu', [ProyectosController::class, 'update_acu']);

				Route::post('guardar_cu', [ProyectosController::class, 'guardar_cu']);
				Route::post('update_cu', [ProyectosController::class, 'update_cu']);
				Route::get('listar_cus', [NombresAcuController::class, 'listar_nombres_cus']);

				Route::get('listar_insumos', [ProyectosController::class, 'listar_insumos']);
				Route::get('mostrar_insumo/{id}', [ProyectosController::class, 'mostrar_insumo']);
				Route::post('guardar_insumo', [ProyectosController::class, 'guardar_insumo']);
				Route::get('listar_insumo_precios/{id}', [ProyectosController::class, 'listar_insumo_precios']);
				Route::post('guardar_precio', [ProyectosController::class, 'guardar_precio']);
				Route::post('guardar_insumo', [ProyectosController::class, 'guardar_insumo']);
				Route::post('actualizar_insumo', [ProyectosController::class, 'update_insumo']);
				// Route::get('listar_opciones_sin_preseje', [ProyectosController::class, 'listar_opciones_sin_preseje']);

				Route::get('listar_obs_cd/{id}', [ProyectosController::class, 'listar_obs_cd']);
				Route::get('listar_obs_ci/{id}', [ProyectosController::class, 'listar_obs_ci']);
				Route::get('listar_obs_gg/{id}', [ProyectosController::class, 'listar_obs_gg']);
				Route::get('anular_obs_partida/{id}', [ProyectosController::class, 'anular_obs_partida']);
				Route::post('guardar_obs_partida', [ProyectosController::class, 'guardar_obs_partida']);
			});

			Route::group(['as' => 'cronogramas-ejecucion.', 'prefix' => 'cronogramas-ejecucion'], function () {

				Route::get('index', [ProyectosController::class, 'view_cronoeje'])->name('index');
				Route::get('nuevo_cronograma/{id}', [CronogramaInternoController::class, 'nuevo_cronograma']);

				Route::get('listar_acus_cronograma/{id}', [ProyectosController::class, 'listar_acus_cronograma']); // ! no existe método
				Route::get('listar_pres_crono/{tc}/{tp}', [CronogramaInternoController::class, 'listar_pres_crono']);
				Route::get('listar_pres_cronoval/{tc}/{tp}', [CronogramaValorizadoInternoController::class, 'listar_pres_cronoval']);
				Route::post('guardar_crono', [CronogramaInternoController::class, 'guardar_crono']);
				Route::get('anular_crono/{id}', [CronogramaInternoController::class, 'anular_crono']);
				Route::get('ver_gant/{id}', [CronogramaInternoController::class, 'ver_gant']);
				Route::get('listar_cronograma/{id}', [CronogramaInternoController::class, 'listar_cronograma']);
				Route::get('mostrar_acu/{id}', [AcuController::class, 'mostrar_acu']);
				Route::get('listar_obs_cd/{id}', [PartidasController::class, 'listar_obs_cd']);
			});

			Route::group(['as' => 'cronogramas-valorizados-ejecucion.', 'prefix' => 'cronogramas-valorizados-ejecucion'], function () {
				//Cronograma Valorizado Ejecucion
				Route::get('index', [ProyectosController::class, 'view_cronovaleje'])->name('index');
				Route::get('nuevo_crono_valorizado/{id}', [CronogramaValorizadoInternoController::class, 'nuevo_crono_valorizado']);
				Route::get('mostrar_crono_valorizado/{id}', [CronogramaValorizadoInternoController::class, 'mostrar_crono_valorizado']);
				Route::get('download_cronoval/{id}/{nro}', [CronogramaValorizadoInternoController::class, 'download_cronoval']);
				Route::post('guardar_cronoval_presupuesto', [CronogramaValorizadoInternoController::class, 'guardar_cronoval_presupuesto']);
				Route::get('anular_cronoval/{id}', [CronogramaValorizadoInternoController::class, 'anular_cronoval']);
				Route::get('listar_pres_cronoval/{tc}/{tp}', [CronogramaValorizadoInternoController::class, 'listar_pres_cronoval']);
			});
		});

		Route::group(['as' => 'reportes.', 'prefix' => 'reportes'], function () {
			Route::group(['as' => 'curvas.', 'prefix' => 'curvas'], function () {
				//Curvas S
				Route::get('index', [ProyectosController::class, 'view_curvas'])->name('index');
				Route::get('getProgramadoValorizado/{id}/{pre}', [ProyectosController::class, 'getProgramadoValorizado']);
				Route::get('listar_propuestas_preseje', [ProyectosController::class, 'listar_propuestas_preseje']);
			});

			Route::group(['as' => 'saldos.', 'prefix' => 'saldos'], function () {
				//Saldos
				Route::get('index', [ProyectosController::class, 'view_saldos_pres'])->name('index');
				Route::get('listar_saldos_presupuesto/{id}', [ProyectosController::class, 'listar_saldos_presupuesto']);
				Route::get('listar_estructuras_preseje', [ProyectosController::class, 'listar_estructuras_preseje']);
				Route::get('ver_detalle_partida/{id}', [ProyectosController::class, 'ver_detalle_partida']);
			});

			Route::group(['as' => 'opciones-relaciones.', 'prefix' => 'opciones-relaciones'], function () {
				//Opciones y Relaciones
				Route::get('index', [ProyectosController::class, 'view_opciones_todo'])->name('index');
				Route::get('listar_opciones_todo', [ProyectosController::class, 'listar_opciones_todo']);
			});

			Route::group(['as' => 'cuadro-gastos.', 'prefix' => 'cuadro-gastos'], function () {
				//Opciones y Relaciones
				Route::get('index', [ProyectosController::class, 'view_cuadro_gastos'])->name('index');
				Route::get('listar', [ProyectosController::class, 'listar_cuadro_gastos']);
				Route::post('cuadroGastosExcel', [PresupuestoController::class, 'cuadroGastosExcel'])->name('cuadro-gastos-excel');
				Route::get('mostrarGastosPorPresupuesto/{id}', [PresupuestoController::class, 'mostrarGastosPorPresupuesto'])->name('mostrar-gastos-presupuesto');
			});
		});

		Route::group(['as' => 'configuraciones.', 'prefix' => 'configuraciones'], function () {
			Route::group(['as' => 'estructuras.', 'prefix' => 'estructuras'], function () {
				//Estructura Presupuestos
				Route::get('index', [ProyectosController::class, 'view_presEstructura'])->name('index');
				Route::get('listar_pres_estructura', [ProyectosController::class, 'listar_pres_estructura']);
				Route::get('mostrar_pres_estructura/{id}', [ProyectosController::class, 'mostrar_pres_estructura']);
				Route::post('guardar_pres_estructura', [ProyectosController::class, 'guardar_pres_estructura']);
				Route::post('update_pres_estructura', [ProyectosController::class, 'update_pres_estructura']);
				Route::get('listar_presupuesto/{id}', [ProyectosController::class, 'listar_presupuesto']);
				Route::get('listar_par_det', [ProyectosController::class, 'listar_par_det']);
				Route::get('cargar_grupos/{id}', [ProyectosController::class, 'cargar_grupos']);
				Route::post('guardar_titulo', [ProyectosController::class, 'guardar_titulo']);
				Route::post('update_titulo', [ProyectosController::class, 'update_titulo']);
				Route::post('anular_titulo', [ProyectosController::class, 'anular_titulo']);
				Route::post('guardar_partida', [ProyectosController::class, 'guardar_partida']);
				Route::post('update_partida', [ProyectosController::class, 'update_partida']);
				Route::get('anular_partida/{id}', [ProyectosController::class, 'anular_partida']);
			});
		});
	});
});
Route::group(['as' => 'api-consulta.', 'prefix' => 'api-consulta'], function () {
	Route::get('tipo_cambio_masivo/{desde}/{hasta}', [ApiController::class, 'tipoCambioMasivo'])->name('tipo_cambio_masivo');
	Route::get('tipo_cambio_actual', [ApiController::class, 'tipoCambioActual'])->name('tipo_cambio_actual');
});
