@extends('themes.base')

@section('cabecera') Estado de Atención de Requerimientos @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
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
        #despachosPendientes_filter,
    #despachosEntregados_filter{
        margin-top:10px;
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

@if (in_array(158,$array_accesos) || in_array(157,$array_accesos) )
<div class="box box-solid">
    <div class="box-body">
        <div class="page-main" type="requerimientosAlmacen">
            <div class="row" style="padding-top:10px;padding-right:10px;padding-left:10px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="mytable table table-condensed table-bordered table-okc-view"
                            id="requerimientosAlmacen" style="width:100%;">
                            <thead>
                                <tr>
                                    <th hidden></th>
                                    <th>Codigo</th>
                                    <th>Estado</th>
                                    <th>Concepto</th>
                                    <th>Grupo</th>
                                    <th>Almacen</th>
                                    <th>Fecha entrega</th>
                                    <th>Registrado por</th>
                                    <th>Despacho Interno</th>
                                    <th>Despacho Externo</th>
                                    <th>Estado despacho</th>
                                    <th width="7%"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
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
            Solicite los accesos
        </div>
    </div>
</div>
@endif
@include('almacen.transferencias.verTransferenciasPorRequerimiento')
@include('almacen.reportes.cambioRequerimiento')
@include('logistica.gestion_logistica.compras.pendientes.modal_ver_orden_de_requerimiento')
@include('almacen.reportes.modal_ajustar_transformacion_requerimiento')
@include('almacen.distribucion.ordenDespachoProgramar')

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
{{-- 
 --}}
<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('js/almacen/reporte/requerimientosAlmacen.js') }}?v={{filemtime(public_path('js/almacen/reporte/requerimientosAlmacen.js'))}}"></script>
<script src="{{ asset('js/almacen/reporte/cambioRequerimiento.js') }}?v={{filemtime(public_path('js/almacen/reporte/cambioRequerimiento.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/verDetalleRequerimiento.js?') }}?v={{filemtime(public_path('js/almacen/distribucion/verDetalleRequerimiento.js'))}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoProgramar.js?')}}?v={{filemtime(public_path('js/almacen/distribucion/ordenDespachoProgramar.js'))}}"></script>

<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {
        
        listarRequerimientosAlmacen('{{Auth::user()->id_usuario}}');
        // $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
    //     iniciar('{{Auth::user()->tieneAccion(85)}}');
    });
</script>


@endsection
{{-- ----------------------------------------------- --}}
