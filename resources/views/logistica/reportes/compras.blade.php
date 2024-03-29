@extends('themes.base')
@include('layouts.menu_logistica')

@section('option')
@endsection

@section('cabecera') Reporte de compras @endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Reportes</li>
    <li class="active">Compras</li>
</ol>
@endsection

@section('cuerpo')
<div class="box box-solid">
    <div class="box-body">
        <div class="page-main" type="compras">
            <div class="col-md-12" id="tab-compras" style="padding-top: 10px; padding-bottom: 10px;">
                <ul class="nav nav-tabs" id="myTabOrdenesPendientes">
                    <li class="active"><a data-toggle="tab" href="#locales">Compras locales <span id="nro_compras_locales" class="badge badge-info">{{ 0 }}</span></a></li>
                    <li class=""><a data-toggle="tab" href="#general">Compras por proyecto <span id="nro_devoluciones" class="badge badge-info">{{ 0 }}</span></a></li>
                </ul>

                <div class="tab-content">
                    <div id="locales" class="tab-pane fade in active">
                    </div>
                    <div id="general" class="tab-pane fade">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    
    <script src='{{ asset("template/adminlte2-4/plugins/moment/datetime-moment.js?v=1") }}'></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>

    <script src="{{('/js/logistica/reportes/comprasLocales.js')}}?v={{filemtime(public_path('/js/logistica/reportes/comprasLocales.js'))}}"></script>

    <script>
        var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
        $(document).ready(function() {
            
            const comprasLocales = new ComprasLocales();
            comprasLocales.mostrar('SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 3 , 'SIN_FILTRO', 6);

                comprasLocales.ActualParametroGrupo = 3;
                comprasLocales.ActualParametroEstadoPago = 6;

            comprasLocales.initializeEventHandler();
        });
    </script>
@endsection
