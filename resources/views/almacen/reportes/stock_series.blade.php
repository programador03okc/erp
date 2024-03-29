
@extends('themes.base')

@section('cabecera') Stock de Series @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
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
    <li>Reportes</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="kardex_detallado">

    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">
                <div class="row">
                    <div class="col-md-12">
                        <table id="datos_producto" class="table-group">
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-bordered table-okc-view" id="lista_stock_series">
                            <thead>
                                <tr>
                                    <th>Almacén</th>
                                    <th>Cód. producto</th>
                                    <th>Part number</th>
                                    <th>Serie</th>
                                    <th>Descripción producto</th>
                                    <th>Unidad</th>
                                    <th>Afecto IGV</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Fecha Guía Emisión</th>
                                    <th>Doc. com</th>
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
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
{{--  --}}

<script src="{{ asset('js/almacen/reporte/stock_series.js')}}"></script>
<script>
    $(document).ready(function() {
        
    });
</script>
@endsection
{{-- ----------------------- --}}
