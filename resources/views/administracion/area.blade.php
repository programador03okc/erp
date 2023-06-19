@extends('themes.base')
@include('layouts.menu_admin')

@section('option')
    @include('layouts.option')
@endsection

@section('cabecera') Areas @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="area">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <form id="form-area" type="register" form="formulario">
                        <input type="hidden" name="id_area" primary="ids">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Empresa</h5>
                                <select class="form-control activation" name="empresa" disabled="true" onchange="buscarSede(this.value, 'nuevo', 0);">
                                    <option value="0" selected disabled>Elija una empresa</option>
                                    @foreach ($emp as $emp)
                                        <option value="{{$emp->id_empresa}}">{{$emp->razon_social}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5>Sede</h5>
                                <select class="form-control activation" name="sede" disabled="true" onchange="buscarGrupo(this.value, 'nuevo', 0);">
                                    <option value="0" selected disabled>Elija una opción</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5>Grupo</h5>
                                <select class="form-control activation" name="grupo" disabled="true">
                                    <option value="0" selected disabled>Elija una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Código / Descripción</h5>
                                <div class="flexAccion">
                                    <input type="text" class="form-control activation" name="codigo" disabled="true" maxlength="4" placeholder="----"
                                    style="width: 20%; text-align: center;">
                                    <input type="text" class="form-control activation" name="descripcion" disabled="true" placeholder="Nombre del area"
                                    style="width: 80%;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listaArea">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Empresa</th>
                            <th>Sede</th>
                            <th>Grupo</th>
                            <th>Area</th>
                            <th>Código</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
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

    <script src="{{ asset('js/administracion/area.js') }}"></script>
@endsection
