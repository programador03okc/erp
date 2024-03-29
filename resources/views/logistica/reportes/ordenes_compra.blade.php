@extends('themes.base')
@include('layouts.menu_logistica')

@section('option')
@endsection

@section('cabecera')
Reportes de ordenes compra
@endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Reportes</li>
    <li class="active">Ordenes de compra</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="reporte_ordenes_compra">
    @if (in_array(268,$array_accesos)||in_array(269,$array_accesos)||in_array(270,$array_accesos))
    <div class="row">
        <div class="col-md-12">
            <fieldset class="group-table">
                <table class="mytable table table-condensed table-striped table-hover table-bordered table-okc-view" id="listaOrdenesCompra">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Req.</th>
                            <th style="text-align:center;">Cuadro costos</th>
                            <th style="text-align:center;">Orden compra</th>
                            <th style="text-align:center;">Cod. Softlink</th>
                            <th style="text-align:center;">Empresa - Sede</th>
                            <th style="text-align:center;">Estado</th>
                            <th style="text-align:center;">Fecha vencimiento CC</th>
                            <th style="text-align:center;">Estado aprobación CC</th>
                            <th style="text-align:center;">Fecha aprobación CC</th>
                            <th style="text-align:center;">Días de atención CC</th>
                            <th style="text-align:center;">Condición</th>
                            <th style="text-align:center;">Fecha generación OC</th>
                            <th style="text-align:center;">Días de entrega</th>
                            <th style="text-align:center;">Condición 2</th>
                            <th style="text-align:center;">Fecha entrega</th>
                            <th style="text-align:center;">Observacion</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
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

@include('logistica.reportes.modal_filtro_reporte_ordenes_compra')

@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/moment/datetime-moment.js?v=1') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/moment/datetime-moment.js?v=1') }}"></script>

<script src="{{ asset('js/logistica/reportes/ordenesCompra.js')}}?v={{filemtime(public_path('/js/logistica/reportes/ordenesCompra.js'))}}"></script>
<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {

        
        const ordenesCompra = new OrdenesCompra();
        ordenesCompra.mostrar();
        ordenesCompra.initializeEventHandler();
    });
</script>

@endsection
