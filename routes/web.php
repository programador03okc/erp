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

Route::get('test-claves', [TestController::class, 'actualizarClaves'])->name('test-claves');

Route::middleware(['auth'])->group(function () {
    Route::get('cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');
    Route::get('inicio', [HomeController::class, 'index'])->name('inicio');


    /**Almacén */
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

	Route::group(['as' => 'cas.', 'prefix' => 'cas'], function () {

		Route::get('index', [TransformacionController::class,'view_main_cas'])->name('index');

		Route::group(['as' => 'customizacion.', 'prefix' => 'customizacion'], function () {

			Route::group(['as' => 'tablero-transformaciones.', 'prefix' => 'tablero-transformaciones'], function () {

				Route::get('index', [OrdenesTransformacionController::class,'view_tablero_transformaciones'])->name('index');
				Route::get('listarDespachosInternos/{fec}', [OrdenesDespachoInternoController::class,'listarDespachosInternos']);
				Route::get('subirPrioridad/{id}', [OrdenesDespachoInternoController::class,'subirPrioridad']);
				Route::get('bajarPrioridad/{id}', [OrdenesDespachoInternoController::class,'bajarPrioridad']);
				Route::get('pasarProgramadasAlDiaSiguiente/{fec}', [OrdenesDespachoInternoController::class,'pasarProgramadasAlDiaSiguiente']);
				Route::get('listarPendientesAnteriores/{fec}', [OrdenesDespachoInternoController::class,'listarPendientesAnteriores']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class,'imprimir_transformacion']);
				Route::post('cambiaEstado', [OrdenesDespachoInternoController::class,'cambiaEstado']);
			});

			Route::group(['as' => 'gestion-customizaciones.', 'prefix' => 'gestion-customizaciones'], function () {
				//Transformaciones
				Route::get('index', [TransformacionController::class,'view_listar_transformaciones'])->name('index');
				Route::get('listarTransformacionesProcesadas', [TransformacionController::class,'listarTransformacionesProcesadas']);
				Route::post('listar_transformaciones_pendientes', [TransformacionController::class,'listar_transformaciones_pendientes']);
				Route::post('listarCuadrosCostos', [TransformacionController::class,'listarCuadrosCostos']);
				Route::post('generarTransformacion', [TransformacionController::class,'generarTransformacion']);
				Route::get('obtenerCuadro/{id}/{tipo}', [TransformacionController::class,'obtenerCuadro']);
				Route::get('mostrar_prods', [ProductoController::class,'mostrar_prods']);
				Route::get('id_ingreso_transformacion/{id}', [TransformacionController::class,'id_ingreso_transformacion']);
				Route::get('id_salida_transformacion/{id}', [TransformacionController::class,'id_salida_transformacion']);
				Route::get('imprimir_ingreso/{id}', [IngresoPdfController::class,'imprimir_ingreso']);
				Route::get('imprimir_salida/{id}', [SalidaPdfController::class,'imprimir_salida']);
				Route::get('imprimir_transformacion/{id}', [TransformacionController::class,'imprimir_transformacion']);
				Route::get('recibido_conforme_transformacion/{id}', [TransformacionController::class,'recibido_conforme_transformacion']);
				Route::get('no_conforme_transformacion/{id}', [TransformacionController::class,'no_conforme_transformacion']);
				Route::get('iniciar_transformacion/{id}', [TransformacionController::class,'iniciar_transformacion']);
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
});
