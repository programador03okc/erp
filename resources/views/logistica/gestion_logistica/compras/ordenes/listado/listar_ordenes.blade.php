@extends('themes.base')
@include('layouts.menu_logistica')
@section('option')
@endsection

@section('cabecera')
Gestión de ordenes
@endsection
@section('estilos')
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Compras</li>
    <li>Ordenes</li>
    <li class="active">Gestión de ordenes</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="listar_ordenes" id="listar_ordenes">
    <legend class="mylegend">
    </legend>
    {{-- @if (in_array(33,$array_accesos) || in_array(19,$array_accesos) || in_array(36,$array_accesos) || in_array(35,$array_accesos) || in_array(34,$array_accesos) || in_array(18,$array_accesos)) --}}
    @if (in_array(243,$array_accesos) || in_array(250,$array_accesos))
    <fieldset class="group-table">
        <div class="row">
            <div class="col-sm-3">
                <div class="input-group">
                    <div class="input-group-btn">
                        @if (in_array(243,$array_accesos))
                        <button type="button" class="btn btn-default handleClickTipoVistaPorCabecera" id="btnTipoVistaPorCabecera" title="Ver tabla a nivel de cabecera"><i class="fas fa-columns"></i> Vista a nivel de Cabecera</button>
                        @endif
                        @if (in_array(250,$array_accesos))
                        <button type="button" class="btn btn-default handleClickTipoVistaPorItem" id="btnTipoVistaPorItemPara" title="Ver tabla a nivel de Items"><i class="fas fa-table"></i> Vista a nivel de Items</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if (in_array(243,$array_accesos))
        <div class="row" id="contenedor-tabla-nivel-cabecera">
            <div class="col-sm-12">
                <div class="box box-widget">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="mytable table table-hover table-condensed table-bordered table-okc-view" id="listaOrdenes">
                                <thead>
                                    <tr>
                                        <th>cod. ord.</th>
                                        <th>Cod. softlink</th>
                                        <th>Cod. req.</th>
                                        <th>Cod. CDP</th>
                                        <th>Empresa - sede</th>
                                        <th>Mnd.</th>
                                        <th>Fech. emisión</th>
                                        <th>Fech. llegada</th>
                                        <th>Tiempo Atención Log.</th>
                                        <th>Proveedor</th>
                                        <th>Condicón</th>
                                        <th>Estado de orden</th>
                                        <th>Estado del pago</th>
                                        <th>Importe total orden</th>
                                        <th>Importe total CDP</th>
                                        <th>Acción</th>
                                    </tr>

                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if (in_array(250,$array_accesos))
        <div class="row" id="contenedor-tabla-nivel-item">
            <div class="col-sm-12">
                <div class="box box-widget">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="mytable table table-hover table-condensed table-bordered table-okc-view" id="listaItemsOrden" style="font-size: 0.9rem;">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th>F. Fecha de Emision </th>
                                        <th>cod. ord.</th>
                                        <th>Cod. req.</th>
                                        <th>Cod OC Soft.</th>
                                        <th>Concepto Req.</th>
                                        <th>Cliente</th>
                                        <th>Proveedor</th>
                                        <th>Marca</th>
                                        <th>Categoría</th>
                                        <th>Cod. prod.</th>
                                        <th>Part number</th>
                                        <th>Cod. soft.</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Und. medida</th>
                                        <th>Precio Un.</th>
                                        <th>Precio Un. CDP</th>
                                        <th>Fecha emisión ord.</th>
                                        <th>Plazo entrega</th>
                                        <th>Fecha ingreso almacén</th>
                                        <th>Empresa - sede</th>
                                        <th>Estado</th>
                                        <th style="width:5%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </fieldset>
    @else
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger pulse" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error de Accesos:</span>
                Solicite los accesos
            </div>
        </div>
    </div>
    @endif

</div>



@include('tesoreria.facturacion.archivos_oc_mgcp')
@include('logistica.gestion_logistica.compras.ordenes.listado.modal_aprobar_orden')
@include('logistica.gestion_logistica.compras.ordenes.listado.registrar_pago')
@include('logistica.gestion_logistica.compras.ordenes.listado.modal_adjunto_orden')

@include('logistica.gestion_logistica.compras.ordenes.listado.modal_editar_estado_orden')
@include('logistica.gestion_logistica.compras.ordenes.listado.modal_editar_estado_detalle_orden')
@include('logistica.gestion_logistica.compras.ordenes.listado.modal_documentos_vinculados')

@include('logistica.gestion_logistica.compras.ordenes.listado.modal_enviar_solicitud_pago')
@include('tesoreria.requerimiento_pago.modal_nueva_cuenta_bancaria_destinatario')
@include('tesoreria.requerimiento_pago.modal_nuevo_contribuyente')
@include('tesoreria.requerimiento_pago.modal_nueva_persona')
<!-- Modal -->
<div class="modal fade" id="modal-info-adicional-cuenta-seleccionada" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información de cuenta</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-info-adicional-tipo-impuesto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lista de requerimientos con tipo impuesto</h4>
            </div>
            <div class="modal-body">
                <ul class="list-inline" id="listaRequerimientosSeleccionadosConImpuesto">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal filtro lista ordenes cabecera -->

<div class="modal fade" tabindex="-1" role="dialog" id="modalFiltrosListaOrdenes" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width: 38%;">
        <div class="modal-content">
            <form id="formulario-filtros_ordenes_elaboradas" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" style="font-weight:bold;">Filtros</h3>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal" id="formFiltroListaOrdenesElaboradas">
                        <div class="form-group">
                            <div class="col-md-12">
                                <small>Seleccione los filtros que desee aplicar y cierre este cuadro para continuar</small>
                            </div>
                        </div>
                        <div class="container-filter" style="margin: 0 auto;">

                            <fieldset class="group-table">

                                <div class="form-group">
                                    <label class="col-sm-4">
                                        <div class="checkbox">
                                            <label title="Empresa">
                                                <input type="checkbox" name="chkEmpresa" @if (session('clEmpresa') !==null) checked @endif> Empresa
                                            </label>
                                        </div>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control input-sm handleChangeFiltroEmpresa" name="empresa">
                                            <option value="0">Todas</option>
                                            @foreach ($empresas as $emp)
                                            <option value="{{$emp->id_empresa}}" @if (session('clEmpresa')==$emp->id_empresa) selected @else '' @endif>{{$emp->razon_social}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4">
                                        <div class="checkbox">
                                            <label title="Fecha de creación">
                                                <input type="checkbox" name="chkFechaRegistro" @if (session('clFechaRegistroDesde') !==null) checked @endif> Fecha creación
                                            </label>
                                        </div>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="date" name="fechaRegistroDesde" class="form-control" value="@if (session('ocFiltroFechaPublicacionDesde') !== null){{ session('ocFiltroFechaPublicacionDesde') }}@else{{ date('Y').'-01-01' }}@endif">
                                        <small class="help-block">Desde (dd-mm-aaaa)</small>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="date" name="fechaRegistroHasta" class="form-control" value="@if (session('ocFiltroFechaPublicacionHasta') !== null){{ session('ocFiltroFechaPublicacionHasta') }}@else{{ date('Y-m-d') }}@endif">
                                        <small class="help-block">Hasta (dd-mm-aaaa)</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4">
                                        <div class="checkbox">
                                            <label title="Estado del pago">
                                                <input type="checkbox" name="chkEstadoPago" @if (session('clEstadoPago') !==null) checked @endif> Estado de pago
                                            </label>
                                        </div>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control input-sm" name="estadoPago">
                                            <option value="0">Todos</option>
                                            @foreach ($estadosPago as $estadoPago)
                                            <option value="{{$estadoPago->id_requerimiento_pago_estado}}" @if (session('clEstadoPago')==$estadoPago->id_requerimiento_pago_estado) selected @else '' @endif>{{$estadoPago->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" class="close" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="my-modal-title">Filtros</h5>

            </div>
            <div class="modal-body">
                <div class="row" data-select="fecha">
                    <div class="col-md-3">
                        <div class="form-group text-right">
                            <input id="fecha" class="form-check-input" type="checkbox" name="fecha" value="true">
                            <label for="fecha">Fechas :</label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group text-center">
                                    <input class="form-control text-center" type="date" name="fecha_inicio" data-action="change" data-select="fecha" disabled>
                                    <span class="help-block">Desde (dd-mm-aaaa)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-center">
                                    <input class="form-control text-center" type="date" name="fecha_final" data-action="change" data-select="fecha" disabled>
                                    <span class="help-block">Hasta (dd-mm-aaaa)</span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="filtros-detalle">Aceptar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/js/locales/bootstrap-datepicker.es.min.js') }}"></script>

<!-- <script src="{{('/js/logistica/orden/listar_ordenes.js')}}"></script> -->
<!-- <script src="{{('/js/logistica/orden/orden_ver_detalle.js')}}"></script> -->
<script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/archivosMgcp.js'))}}"></script>
<script src="{{('/js/logistica/orden/listaOrdenView.js')}}?v={{filemtime(public_path('/js/logistica/orden/listaOrdenView.js'))}}"></script>
<script src="{{('/js/logistica/orden/listaOrdenController.js')}}?v={{filemtime(public_path('/js/logistica/orden/listaOrdenController.js'))}}"></script>
<script src="{{('/js/logistica/orden/listaOrdenModel.js')}}?v={{filemtime(public_path('/js/logistica/orden/listaOrdenModel.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/nuevaCuentaBancariaDestinatario.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/nuevaCuentaBancariaDestinatario.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/nuevoDestinatario.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/nuevoDestinatario.js'))}}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/moment/datetime-moment.js') }}"></script>

<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    window.onload = function() {

        $.fn.dataTable.moment('DD-MM-YYYY HH:mm');
        $.fn.dataTable.moment('DD-MM-YYYY');
        const listaOrdenModel = new ListaOrdenModel();
        const listaOrdenCtrl = new ListaOrdenCtrl(listaOrdenModel);
        const listaOrdenView = new ListaOrdenView(listaOrdenCtrl);

        listaOrdenView.init();
        listaOrdenView.initializeEventHandler();
        listaOrdenView.actualizarFiltros();
        listaOrdenView.actualizarFiltrosItems();
        // Util.activarFiltros('#listaOrdenes', listaOrdenView  );

        $('[name=monto_a_pagar]').number(true, 2);
        $('[name=saldo]').number(true, 2);
    };
</script>
@endsection
