
@extends('themes.base')

@section('cabecera') Tipos de Almacén @endsection
@include('layouts.menu_almacen')
@if(Auth::user()->tieneAccion(72))
    @section('option')
        @include('layouts.option')
    @endsection
@elseif(Auth::user()->tieneAccion(73))
    @section('option')
        @include('layouts.option_historial')
    @endsection
@endif

@section('estilos')
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
    <li>Ubicaciones</li>
    <li class="active">@yield('cabecera')</li>
  </ol>
@endsection

@section('cuerpo')
<div class="page-main" type="tipo_almacen">
    <!-- <div class="thumbnail" > -->

        @if (sizeof($array_accesos_botonera)!==0)
        <div class="row">
            <div class="col-md-7">
                <fieldset class="group-table">
                    <table class="mytable table table-hover table-condensed table-bordered table-okc-view" id="listaTipoAlmacen">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </fieldset>
            </div>
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-body">
                        <form id="form-tipo_almacen" type="register" form="formulario">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                            <input type="hidden" name="id_tipo_almacen" primary="ids">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Descripción</h5>
                                    <input type="text" class="form-control activation" name="descripcion" disabled="true">
                                </div>
                            </div>
                        </form>
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
    <!-- </div> -->
</div>
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/almacen/ubicacion/tipo_almacen.js')}}"></script>
    <script>
    $(document).ready(function(){
        
    });
    </script>
@endsection

{{-- -------------------------------------- --}}
