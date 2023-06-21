
@extends('themes.base')

@section('cabecera') Series-Numeros @endsection
@include('layouts.menu_almacen')
@if(Auth::user()->tieneAccion(129))
    @section('option')
        @include('layouts.option')
    @endsection
@elseif(Auth::user()->tieneAccion(128))
    @section('option')
        @include('layouts.option_historial')
    @endsection
@endif
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
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
<div class="page-main" type="serie_numero">

    @if (sizeof($array_accesos_botonera)!==0 || in_array(181,$array_accesos) ||in_array(182,$array_accesos)||in_array(183,$array_accesos)||in_array(184,$array_accesos))
    <div class="row">
        <div class="col-md-7">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-okc-view"
                    id="listaSerieNumero">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Tipo Doc</th>
                            <th>Empresa-Sede</th>
                            <th>Serie</th>
                            <th>Numero</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-5">
            <form id="form-serie_numero" type="register" form="formulario">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <input type="hidden" name="id_serie_numero" primary="ids">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Tipo de Documento</h5>
                        <select class="form-control activation js-example-basic-single" name="id_tp_documento" disabled="true">
                            <option value="0">Elija una opción</option>
                            @foreach ($tipos as $tp)
                                <option value="{{$tp->id_tp_doc}}">{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Empresa-Sede</h5>
                        <select class="form-control activation js-example-basic-single" name="id_sede" disabled="true">
                            <option value="0">Elija una opción</option>
                            @foreach ($sedes as $tp)
                                <option value="{{$tp->id_sede}}">{{$tp->razon_social}} - {{$tp->codigo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Serie-Número</h5>
                        <div class="input-group">
                            <input type="text" class="form-control activation" name="serie"
                                onBlur="ceros_serie('serie');" placeholder="0000" disabled="true">
                            <span class="input-group-addon">-</span>
                            <input type="text" class="form-control activation" name="numero"
                                onBlur="ceros_numero('numero');" placeholder="0000000" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="group-importes"><legend><h6>Crear Números</h6></legend>
                            <div class="input-group">
                                <span class="input-group-addon"> Desde: </span>
                                <input type="number" name="numero_desde" class="form-control activation" disabled="true">
                                <span class="input-group-addon"> Hasta: </span>
                                <input type="number" name="numero_hasta" class="form-control activation" disabled="true">
                            </div>
                        </fieldset>
                    </div>
                </div>
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
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/almacen/variables/serie_numero.js')}}"></script>
    <script>
        var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function(){
        
    });
    </script>
@endsection
{{-- ----------------------------------- --}}
