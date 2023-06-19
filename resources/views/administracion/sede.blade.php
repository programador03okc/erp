@extends('themes.base')
@include('layouts.menu_admin')

@section('option')
    @include('layouts.option')
@endsection

@section('cabecera') Sedes @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="sede">
    <div class="row">
        <div class="col-md-7">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listaSede">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Empresa</th>
                            <th>Nombre la Sede</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-body">
                    <form id="form-sede" type="register" form="formulario">
                        <input type="hidden" name="id_sede" primary="ids">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Empresa</h5>
                                <div class="input-group">
                                    <select class="form-control activation" name="empresa" disabled="true" onchange="buscarCodigo(this.value);">
                                        <option value="0" selected disabled>Elija una empresa</option>
                                        @foreach ($emp as $emp)
                                            <option value="{{$emp->id_empresa}}">{{$emp->razon_social}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon" id="abrev">----</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Abreviatura / Nombre de la Sede</h5>
                                <div class="flexAccion">
                                    <input type="text" class="form-control activation" name="abt" disabled="true" maxlength="4" placeholder="----"
                                    style="width: 20%; text-align: center;">
                                    <input type="text" class="form-control activation" name="descripcion" disabled="true" placeholder="Nommbre de la sede"
                                    style="width: 80%;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Dirección</h5>
                                <input type="text" class="form-control activation" name="direccion" disabled="true" placeholder="Dirección actual">
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

    <script src="{{ asset('js/administracion/sede.js') }}"></script>
@endsection
