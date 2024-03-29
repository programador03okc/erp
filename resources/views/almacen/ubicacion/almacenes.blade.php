
@extends('themes.base')

@section('cabecera') Almacenes @endsection
@include('layouts.menu_almacen')
@if(Auth::user()->tieneAccion(74))
@section('option')
@include('layouts.option')
@endsection
@elseif(Auth::user()->tieneAccion(75))
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
<div class="page-main" type="almacenes">

    @if (sizeof($array_accesos_botonera)!==0)
    <div class="row">
        <div class="col-md-6">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-okc-view" id="listaAlmacen">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sede</th>
                            <th>Cód.</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="group-table">
                <form id="form-almacenes" type="register" form="formulario">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <input type="hidden" name="id_almacen" primary="ids">
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-size: 15px;">Datos del Almacén</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Sede</h5>
                            <select class="form-control activation" name="id_sede" disabled="true">
                                <option value="0">Elija una opción</option>
                                @foreach ($sedes as $sede)
                                <option value="{{$sede->id_sede}}">{{$sede->razon_social}} - {{$sede->codigo}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <h5>Código</h5>
                            <input type="number" class="form-control activation" name="codigo" disabled="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Descripción</h5>
                            <input type="text" class="form-control activation" name="descripcion" disabled="true">
                        </div>
                        <div class="col-md-4">
                            <h5>Tipo de Almacén</h5>
                            <select class="form-control activation" name="id_tipo_almacen" disabled="true">
                                <option value="0">Elija una opción</option>
                                @foreach ($tipos as $tipo)
                                <option value="{{$tipo->id_tipo_almacen}}">{{$tipo->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Dirección</h5>
                            <input type="text" class="form-control activation" name="ubicacion" disabled="true">
                        </div>
                        <div class="col-md-4">
                            <h5>Ubigeo</h5>
                            <div class="input-group-okc">
                                <input type="text" class="oculto" name="ubigeo">
                                <input type="text" class="form-control" name="name_ubigeo" readonly placeholder="Seleccione un ubigeo">
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text activation" onclick="ubigeoModal();">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <label style="font-size: 15px;">Lista de Accesos de Usuario por Almacén</label>
                        <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" id="listaAlmacenUsuarios" style="margin-top:10px; margin-bottom: 0px;">
                            <thead style="color: black;background-color: #c7cacc;">
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Crear/Editar</th>
                                    <th>Ver</th>
                                    <th>
                                        <i class="fas fa-plus-circle icon-tabla green boton text-center" style="padding: 0px;" data-toggle="tooltip" data-placement="bottom" title="Agregar Usuario" onClick="usuarioModal();"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </fieldset>
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
@include('almacen.ubicacion.almacenUsuario')
@include('publico.ubigeoModal')
@include('publico.usuarioModal')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>


    <script src="{{ asset('js/almacen/ubicacion/almacenes.js')}}"></script>
    <script src="{{ asset('js/almacen/ubicacion/almacenUsuario.js')}}"></script>
    <script src="{{ asset('js/publico/ubigeoModal.js')}}"></script>
    <script src="{{ asset('js/publico/usuarioModal.js')}}"></script>
    <script>
        $(document).ready(function() {

        });
    </script>

@endsection
{{-- ------------------ --}}
