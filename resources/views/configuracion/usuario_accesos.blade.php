@extends('themes.base')
@include('layouts.menu_config')

@section('titulo') Gestión de accesos @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('configuracion.dashboard')}}"><i class="fas fa-tachometer-alt"></i> Configuraciones</a></li>
    <li>Usuarios</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="usuarios">
    <legend class="mylegend">
        <h2>Accesos</h2>
        <ol class="breadcrumb">
            <li>

            </li>
        </ol>
    </legend>

    <div class="box box-danger">
        <div class="box-header">
            <h3 class="box-title">Nombres y Apellidos : {{$usuario->nombres.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno}} </h3>
            <div class="pull-right box-tools">
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="modulos">Modulos : </label>
                        <select id="modulos" class="form-control" name="modulos" data-select="modulos-select" required>
                            <option value="">Seleccione...</option>
                            @foreach ($modulos as $modulos)
                                <option value="{{$modulos->id_modulo}}">{{$modulos->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8 text-right">
                    <button class="btn btn-success" data-action="guardar"><i class="fa fa-save"></i> Guardar accesos</button>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary overflow-y">
                <div class="box-header with-border">
                    <h3 class="box-title">Accesos a seleccionar</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" data-accesos="accesos">
                            {{-- Accesos --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success overflow-y">
                <div class="box-header with-border">
                    <h3 class="box-title">Accesos seleccionados</h3>
                </div>
                <form action="" data-form="accesos-seleccionados">
                    <div class="box-body">
                        <input type="hidden" name="id_usuario" value="{{$id}}">
                        <div class="row">
                            <div class="col-md-12" data-accesos="select-accesos">
                                {{-- <label for="" data-action="text-selct">Accesos asignados.</label> --}}
                            </div>
                        </div>
                    </div>
                    <div class="loading"></div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}"></script>


    {{-- <script src="{{('/js/configuracion/usuario.js')}}"></script>
    <script src="{{('/js/configuracion/modal_asignar_accesos.js')}}"></script> --}}
    <script src="{{ asset('js/proyectos/residentes/trabajadorModal.js')}}"></script>
    <script src="{{ asset('js/configuracion/usuario_accesos.js') }}"></script>
@endsection
