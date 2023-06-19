@extends('themes.base')
@include('layouts.menu_config')

@section('option')
    @include('layouts.option')
@endsection

@section('cabecera') Documentos @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="documento">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-7">
                    <fieldset class="group-table">
                        <table class="mytable table table-hover table-condensed table-bordered table-result-form" id="listarDocumentos">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="5">id</th>
                                    <th width="60">Estado Documento</th>
                                    <th width="10">Color</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </fieldset>
                </div>
                <div class="col-md-5">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form id="form-documento" type="register" form="formulario">
                                <input type="hidden" name="id_documento" primary="ids">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Estado Documento</h5>
                                        <input type="text" class="form-control activation" name="estado_documento" disabled="true" placeholder="Estado de documento">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Color</h5>
                                        <input type="text" class="form-control activation" name="color" disabled="true" placeholder="Color de estado">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
    <script src="{{ asset('js/configuracion/flujo_aprobacion/documentos.js')}}"></script>
@endsection
