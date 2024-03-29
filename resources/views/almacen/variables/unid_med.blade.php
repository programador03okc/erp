
@extends('themes.base')

@section('cabecera') Unidades de Medida @endsection
@include('layouts.menu_almacen')
@if(Auth::user()->tieneAccion(135))
    @section('option')
        @include('layouts.option')
    @endsection
@elseif(Auth::user()->tieneAccion(134))
    @section('option')
        @include('layouts.option_historial')
    @endsection
@endif
@section('estilos')
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
    <li>Variables</li>
    <li class="active">@yield('cabecera')</li>
  </ol>
@endsection

@section('cuerpo')
<div class="page-main" type="unidmed">

    @if (sizeof($array_accesos_botonera)!==0 || in_array(214,$array_accesos) ||in_array(215,$array_accesos)||in_array(216,$array_accesos)||in_array(217,$array_accesos))
        <div class="row">
            <div class="col-md-6">
                <fieldset class="group-table">
                    <table class="mytable table table-condensed table-bordered table-okc-view"
                    id="listaUnidMed">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Abreviatura</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </fieldset>
            </div>
            <div class="col-md-6">
                <form id="form-unidmed" type="register" form="formulario">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Id</h5>
                            <input type="text" class="form-control" readOnly name="id_unidad_medida" primary="ids">
                        </div>
                        <div class="col-md-4">
                            <h5>Abreviatura</h5>
                            <input type="text" class="form-control activation"  name="abreviatura" disabled="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Descripción</h5>
                            <input type="text" class="form-control activation" name="descripcion" disabled="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        <h5>Estado</h5>
                        <select class="form-control activation" name="estado" readonly>
                            <option value="1" selected>Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                        <h5 id="fecha_registro">Fecha Registro: <label></label></h5>
                        </div>
                    </div> --}}
                </form>
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
    </div>
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    

    <script src="{{ asset('js/almacen/variables/unid_med.js')}}"></script>
    <script>
        var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function(){
        
    });
    </script>
@endsection
{{-- -------------------- --}}
