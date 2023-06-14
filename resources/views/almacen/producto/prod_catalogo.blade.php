@extends('themes.base')

@section('cabecera') Catálogo de Productos @endsection
@include('layouts.menu_almacen')
@section('estilos')
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
        <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Almacen</a></li>
        <li>Catalogo de Productos</li>
        <li class="active">@yield('cabecera')</li>
    </ol>
@endsection

@section('cuerpo')
<div class="page-main" type="producto">

    @if (in_array(37,$array_accesos) || in_array(38,$array_accesos) || in_array(39,$array_accesos) || in_array(40,$array_accesos))
    <div class="thumbnail" style="padding-left: 10px;padding-right: 10px;padding-top: 10px;">
        <div class="row">
            <div class="col-md-12">
                <table class="mytable table table-condensed table-bordered table-okc-view" id="listaProductoCatalogo">
                    <thead>
                        <tr>
                        <th></th>
                        <th>Part Number</th>
                        <th>Código Agile</th>
                        <th>Código Softlink</th>
                        <th width="30%">Descripción</th>
                        <th>Notas</th>
                        <th>Moneda</th>
                        <th>Control de Series</th>
                        <th>Unid. Med.</th>
                        <th>Marca</th>
                        <th>Subcatgoría</th>
                        <th>Categoría</th>
                        <th>Clasificación</th>
                        <th>Fecha registro</th>
                        <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>

{{--


<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script> --}}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>


{{--
<script src="{{ asset('datatables/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('datatables/pdfmake/vfs_fonts.js') }}"></script> --}}


<script src="{{ asset('js/almacen/producto/prod_catalogo.js')}}"></script>
<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {
        Util.seleccionarMenu(window.location);
    });
</script>
@endsection
{{-- ---------------- --}}
