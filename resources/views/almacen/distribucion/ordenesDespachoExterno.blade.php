@extends('themes.base')
@include('layouts.menu_logistica')

@section('cabecera')
Gestión de Despachos
@endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}">

<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/stepperHorizontal.css')}}">
<link rel="stylesheet" href="{{ asset('css/stepper.css')}}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">

<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">


@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Distribución</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="listaOrdenesDespachoExterno">

    @if (in_array(266,$array_accesos) || in_array(265,$array_accesos) || in_array(264,$array_accesos) || in_array(263,$array_accesos) || in_array(262,$array_accesos) || in_array(261,$array_accesos)|| in_array(260,$array_accesos)|| in_array(259,$array_accesos)|| in_array(267,$array_accesos))
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">
                @if (Auth::user()->id_usuario == 3)
                <button id="btn_cerrar" class="btn btn-default" onClick="migrarDespachos();">Migrar</button>
                @endif
                <form id="formFiltrosDespachoExterno" method="POST" target="_blank" action="{{route('logistica.distribucion.ordenes-despacho-externo.despachos-externos-excel')}}">
                    @csrf()
                    <input type="hidden" name="select_mostrar" value="0">
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="mytable table table-condensed table-bordered table-hover table-striped table-okc-view" id="requerimientosEnProceso" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th hidden></th>
                                        {{-- <th></th> --}}
                                        <th>Cod.Req.</th>
                                        <th>Tipo Req.</th>
                                        <th>Fecha Fin Entrega</th>
                                        <th>Nro O/C</th>
                                        {{-- <th>Estado O/C</th> --}}
                                        <th>Monto total</th>
                                        <th>OC.fís / SIAF</th>
                                        <th>OCC</th>
                                        <th>Cod.CDP</th>
                                        <th width="30%">Cliente/Entidad</th>
                                        <th>Generado por</th>
                                        {{-- <th>Sede Req.</th> --}}
                                        <th>Fecha Despacho Real</th>
                                        <th>Flete <small>(sin IGV)</small></th>
                                        <th>Gasto Adic. <small>(+ IGV)</small></th>
                                        <th>Fecha Entrega</th>
                                        <th>Adj. Cargos.</th>
                                        <th>Estado despacho</th>
                                        <th width="10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
                <span class="sr-only">Error de Accesos:</span>
                Solicite los accesos
            </div>
        </div>
    </div>
    @endif
</div>

<div class="modal fade" id="modal-adjuntos-despacho">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Adjuntos <span id="codigo_adjunto"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" data-loading="loading">
                        <table class="table table-striped">
                            <tbody data-table="adjuntos-archivos">
                                <tr>
                                    <td> Sin adjuntos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@include('almacen.distribucion.ordenDespachoContacto')
@include('almacen.distribucion.ordenDespachoTransportista')
@include('almacen.distribucion.enviarFacturacion')
@include('almacen.distribucion.ordenDespachoEnviar')
@include('almacen.distribucion.agregarContacto')
@include('almacen.distribucion.contactoEnviar')
@include('almacen.distribucion.ordenDespachoEstados')
@include('almacen.distribucion.comentarios_oc_mgcp')
@include('almacen.distribucion.ordenDespachoProgramar')
@include('almacen.distribucion.ordenDespachoExternoFecha')
@include('almacen.distribucion.priorizarDespachoExterno')
@include('tesoreria.facturacion.archivos_oc_mgcp')
@include('publico.ubigeoModal')
@include('almacen.transferencias.transportistaModal')
@include('almacen.distribucion.agregarTransportista')
@include('logistica.gestion_logistica.compras.pendientes.modal_ver_orden_de_requerimiento')
@include('almacen.distribucion.requerimientoFlete')
@include('necesidades.destinatarioModal')
@include('logistica.requerimientos.modal_adjuntar_archivos_detalle_requerimiento')
@include('logistica.requerimientos.modal_centro_costos')

@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/js-xlsx/xlsx.full.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-datepicker/dist/js/locales/bootstrap-datepicker.es.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}"></script>


<script src="{{ asset('js/almacen/distribucion/ordenesDespachoExterno.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenesDespachoExterno.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoContacto.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoContacto.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoEnviar.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoEnviar.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/verDetalleRequerimiento.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/verDetalleRequerimiento.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoEstado.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoEstado.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoTransportista.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoTransportista.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/contacto.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/contacto.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/contactoEnviar.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/contactoEnviar.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/agregarTransportista.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/agregarTransportista.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoProgramar.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoProgramar.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/requerimientoFlete.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/requerimientoFlete.js'))}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js?')}}?v={{filemtime(public_path('js/tesoreria/facturacion/archivosMgcp.js'))}}"></script>
{{-- <script src="{{ asset('js/almacen/distribucion/priorizarDespachoExterno.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/priorizarDespachoExterno.js'))}}"></script> --}}
{{-- <script src="{{ asset('js/logistica/requerimiento/trazabilidad.js')}}"></script> --}}

<script src="{{ asset('js/publico/ubigeoModal.js?')}}?v={{filemtime(public_path('js/publico/ubigeoModal.js'))}}"></script>
<script src="{{ asset('js/almacen/transferencias/transportistaModal.js?')}}?v={{filemtime(public_path('js/almacen/transferencias/transportistaModal.js'))}}"></script>

<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {

        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';

        let usuario = '{{Auth::user()->nombre_corto}}';
        // console.log(usuario);
        listarRequerimientosPendientes(usuario);

        $('input.date-picker').datepicker({
            language: "es",
            orientation: "bottom auto",
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        // *How can I set fixed width for options(dropdown) in bootstrap selectpicker =>
        $('.selectpicker').on('show.bs.select', function() {
            var $dropdownMenu = $(this).nextAll('div.dropdown-menu').first();
            if ($dropdownMenu.length > 0) {
                $dropdownMenu.css('min-width', '').css('max-width', '100%');
                var $inner = $dropdownMenu.find('div.inner');
                if ($inner.length > 0) {
                    $inner.css('overflow-x', 'hidden');
                }
            }
        });
    });
</script>
@endsection