

@extends('themes.base')

@section('cabecera') Atención de Salidas @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}">

<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
    <style>
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('almacen.index')}}"><i class="fas fa-tachometer-alt"></i> Almacenes</a></li>
    <li>Movimientos</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
@if (in_array(117,$array_accesos) || in_array(112,$array_accesos))
<div class="box box-solid">
    <div class="box-body">
        <div class="page-main" type="despachosPendientes">
            <div class="col-md-12" id="tab-despachosPendientes" style="padding-top:10px;padding-bottom:10px;">
                <ul class="nav nav-tabs" id="myTabDespachosPendientes">
                    @if (in_array(112,$array_accesos))
                    <li class="active"><a data-toggle="tab" href="#pendientes">Despachos Pendientes <span id="nro_despachos" class="badge badge-info">{{$nro_od_pendientes}}</span></a></li>
                    @endif
                    @if (in_array(112,$array_accesos))
                    <li class=""><a data-toggle="tab" href="#devoluciones">Devoluciones Pendientes </a></li>
                    @endif
                    @if (in_array(117,$array_accesos))
                    <li class=""><a data-toggle="tab" href="#salidas">Salidas Procesadas</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    @if (in_array(112,$array_accesos))
                    <div id="pendientes" class="tab-pane fade in active">
                        <form id="formFiltrosSalidasPendientes" method="POST" target="_blank" action="{{route('almacen.movimientos.pendientes-salida.salidasPendientesExcel')}}">
                            @csrf()
                            <input type="hidden" name="select_mostrar_pendientes" value="0">
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view" id="despachosPendientes" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th>Despacho</th>
                                            <th>Cod.Req.</th>
                                            <th>Fecha Despacho</th>
                                            <th>Comentario</th>
                                            <th>OCAM</th>
                                            <th>CDP</th>
                                            <th>Cliente</th>
                                            <th>Almacén</th>
                                            <th width="90px">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (in_array(112,$array_accesos))
                    <div id="devoluciones" class="tab-pane fade ">
                        <div class="row" style="padding-top:10px;">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view" id="listaDevoluciones">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th width="5%">Código</th>
                                            <th width="10%">Estado</th>
                                            <th width="10%">Fecha registro</th>
                                            <th width="5%">Tipo</th>
                                            <th width="10%">Razón Social</th>
                                            <th width="10%">Almacén</th>
                                            <th width="20%">Concepto</th>
                                            <th width="10%">Elaborado Por</th>
                                            <th width="6%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (in_array(117,$array_accesos))
                    <div id="salidas" class="tab-pane fade ">
                        <form id="formFiltrosSalidasProcesadas" method="POST" target="_blank" action="{{route('almacen.movimientos.pendientes-salida.salidasProcesadasExcel')}}">
                            @csrf()
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view" id="despachosEntregados">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th>Orden Despacho</th>
                                            <th>Req.</th>
                                            <th>Cliente</th>
                                            <th>OCAM</th>
                                            <th>Dev.</th>
                                            <th>Guia venta</th>
                                            <th>Fecha Salida</th>
                                            <th>Comprobantes</th>
                                            <th>Almacén</th>
                                            <th>Salida</th>
                                            <th>Operación</th>
                                            <th>Responsable</th>
                                            <th width="70px"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger pulse" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            Solicite los accesos
        </div>
    </div>
</div>
@endif
<div class="modal fade" tabindex="-1" role="dialog" id="modal-ver-adjuntos" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content">
            <form id="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Adjuntos</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="group-table">
                                <div class="form-group">
                                    <label for="">Adjuntos Contabilidad</label>
                                </div>
                                <table class="table">
                                    <tbody data-table="ver-table-body">

                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-default"data-dismiss="modal" >Cerrar</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

@include('almacen.guias.guia_ven_create')
@include('almacen.distribucion.despachoDetalle')
@include('almacen.guias.guia_ven_obs')
@include('almacen.guias.guia_ven_cambio')
@include('almacen.guias.guia_ven_series')
@include('almacen.guias.salidaAlmacen')
@include('almacen.guias.clienteModal')
@include('almacen.guias.agregarCliente')
@include('tesoreria.facturacion.archivos_oc_mgcp')
@include('logistica.gestion_logistica.compras.pendientes.modal_ver_orden_de_requerimiento')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
{{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script> --}}

{{-- <script src="{{ asset('template/adminlte2-4/plugins/js-xlsx/xlsx.full.min.js') }}"></script> --}}

<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

{{-- para leer archivos excel con js --}}
<script src="{{ asset('template/adminlte2-4/plugins/reed-excel-file/read-excel-file.min.js')}}?v={{filemtime(public_path('template/adminlte2-4/plugins/reed-excel-file/read-excel-file.min.js'))}}"></script>

<script src="{{ asset('js/almacen/guia/despachosPendientes.js')}}?v={{filemtime(public_path('js/almacen/guia/despachosPendientes.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/guia_ven_create.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_ven_create.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/despachoDetalle.js')}}?v={{filemtime(public_path('js/almacen/distribucion/despachoDetalle.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/guia_ven_cambio.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_ven_cambio.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/guia_ven_series.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_ven_series.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/salidaAlmacen.js')}}?v={{filemtime(public_path('js/almacen/guia/salidaAlmacen.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/clienteModal.js')}}?v={{filemtime(public_path('js/almacen/guia/clienteModal.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/agregarCliente.js')}}?v={{filemtime(public_path('js/almacen/guia/agregarCliente.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/devolucionesSalidasPendientes.js')}}?v={{filemtime(public_path('js/almacen/guia/devolucionesSalidasPendientes.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/verDetalleRequerimiento.js')}}?v={{filemtime(public_path('js/almacen/distribucion/verDetalleRequerimiento.js'))}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/archivosMgcp.js'))}}"></script>
<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {

        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
        iniciar('{{Auth::user()->tieneAccion(85)}}');

        $('#import-serie-excel').change( async function (e) {
            e.preventDefault();

            // let data = new FormData($('#form-impor-excel')[0]);
            let numero_marcados = 0;
            let t_body = $('#listaSeriesVen').find('tbody');
            let contenido = await readXlsxFile($(this)[0].files[0]);
            let total = contenido.length - 1;

            $.each(contenido, function (index, element) {

                $.each(t_body.find('tr'), function (index, element_tr) {
                    if (element_tr.children[2].innerText==element[0]) {
                        numero_marcados = numero_marcados +1;
                        $('#listaSeriesVen').find('tbody').find('input[data-serie="'+element[0]+'"]').attr('checked','true');
                    }
                });
            });
            $('#form-impor-excel').find('#total-excel').text('Total de series en el excel: '+total+' - Total de seleccionados : '+numero_marcados+'');

            $("#form-impor-excel")[0].reset();
            $('#modal-guia_ven_series').find('#total-excel').removeClass('d-none');

        });
        $("#modal-guia_ven_series").on("hidden.bs.modal", () => {
            $(this).find('#total-excel').addClass('d-none');
        });

    });
</script>

@endsection

{{-- -------------------------------- --}}
