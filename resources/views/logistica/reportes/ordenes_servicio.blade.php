@extends('themes.base')
@include('layouts.menu_logistica')

@section('option')
@endsection

@section('cabecera')
Reportes de ordenes servicio
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
    <li class="active">Ordenes de servicio</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="reporte_ordenes_servicio">
    @if (in_array(275,$array_accesos))
    <div class="row">
        <div class="col-md-12">
            <fieldset class="group-table">
                <table class="mytable table table-condensed table-striped table-hover table-bordered table-okc-view" id="listaOrdenesServicio">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Req.</th>
                            <th style="text-align:center;">Orden compra</th>
                            <th style="text-align:center;">Cod. Softlink</th>
                            <th style="text-align:center;">Empresa - Sede</th>
                            <th style="text-align:center;">Estado</th>
                            <th style="text-align:center;">Fecha generación OS</th>
                            <th style="text-align:center;">Fecha entrega servicio</th>
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
<script src="{{ asset('js/logistica/reportes/ordenesServicio.js')}}?v={{filemtime(public_path('/js/logistica/reportes/ordenesServicio.js'))}}"></script>
<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {
        
        const ordenesServicio = new OrdenesServicio();
        ordenesServicio.mostrar();
        ordenesServicio.initializeEventHandler();
    });
</script>

@endsection
