@extends('themes.base')
@include('layouts.menu_config')

@section('option')
@endsection

@section('cabecera') Lista de Requerimientos sin atender @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="aplicaciones">
    <legend><h2>Requerimientos logísticos y requerimientos de pago</h2></legend>
    <div class="row">
        <div class="col-md-12">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listaRequerimientosSinAtender">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Código</th>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th>Empresa</th>
                            <th>Sede</th>
                            <th>Grupo</th>
                            <th>División</th>
                            <th>Fecha Emisión</th>
                            <th>Monto</th>
                            <th>Creado por</th>
                            <th>Estado</th>
                            <th>Acción</th>
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
    
    <script src="{{ asset('js/configuracion/requerimientos_sin_atender.js') }}"></script>
@endsection
