@extends('themes.base')
@include('layouts.menu_config')

@section('option')
    @include('layouts.option')
@endsection

@section('cabecera') Aplicaciones del Sistema @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="aplicaciones">
    <legend><h2>Aplicaciones del Sistema</h2></legend>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listaAplicaciones">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sub modulo</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body">
                    <form id="form-tipo_aporte" type="register" form="formulario">
                        <input type="hidden" name="id_aplicacion" primary="ids">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Módulo</h5>
                                <select class="form-control activation" name="modulo" disabled="true" onchange="cambiarModulo(this.value);">
                                    <option value="0" selected disabled>Elija una opción</option>
                                    @foreach ($modulos as $modulos)
                                        <option value="{{$modulos->id_modulo}}">{{$modulos->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <h5>Sub Módulo</h5>
                                <select class="form-control activation" name="sub_modulo" id="sub_modulo" disabled="true"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Descripción</h5>
                                <input type="text" class="form-control activation" name="descripcion" disabled="true" placeholder="Descripcion de la aplicacion">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Link</h5>
                                <input type="text" class="form-control activation" name="ruta" disabled="true" placeholder="Link (ruta de la aplicacion)">
                            </div>
                        </div>
                    </form>
                </div>
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
    
    <script src="{{ asset('js/configuracion/aplicaciones.js') }}"></script>
@endsection
