@extends('themes.base')
@include('layouts.menu_config')

@section('option')
    @include('layouts.option')
@endsection

@section('cabecera') Correos Coorporativos @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="correo_coorporativo">
    <legend><h2>Correos Coorporativos</h2></legend>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listaCorreoCoorporativo">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Empresa</th>
                            <th>Correo</th>
                            <th>Servidor</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body">
                    <form id="form-correo_coorporativo" type="register" form="formulario">
                        <input type="hidden" name="id_smtp_authentication" primary="ids">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Empresa</h5>
                                <select class="form-control activation" name="empresa" disabled="true" onchange="cambiarModulo(this.value);">
                                    <option value="0" selected disabled>Elija una opción</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{$empresa->id_empresa}}">{{$empresa->razon_social}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <h5>Servidor SMTP</h5>
                                <input type="text" class="form-control activation" name="smtp_server" disabled="true">
                            </div>
                            <div class="col-md-3">
                                <h5>Encriptación</h5>
                                <select class="form-control activation" name="encryption" disabled="true">
                                    <option value="ssl" >SSL</option>
                                    <option value="tls" >TLS</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <h5>Puerto</h5>
                                <input type="number" min="0" class="form-control activation" name="port" disabled="true" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Correo</h5>
                                <input type="text" class="form-control activation" name="email" disabled="true" >
                            </div>
                            <div class="col-md-6">
                                <h5>Contraseña</h5>
                                <input type="password" class="form-control activation" name="password" disabled="true" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Estado</h5>
                                <select class="form-control activation" name="estado" disabled="true">
                                    <option value="0" selected disabled>Elija una opción</option>
                                    <option value="1" >Activo</option>
                                    <option value="7" >Anulado</option>
                                </select>
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
    <script src="{{ asset('js/configuracion/correo_coorporativo.js') }}"></script>
@endsection
