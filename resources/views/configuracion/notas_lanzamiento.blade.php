@extends('themes.base')
@include('layouts.menu_config')

@section('cabecera') Notas de Lanzamiento @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('cuerpo')
<div class="page-main" type="modulo">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Notas</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-sm btn-success activation" name="btnAgregarNotaLanzamiento" onClick="agregarNotaLanzamiento();" id="btn-add"
                            data-toggle="tooltip" data-placement="bottom"  title="Agregar Nota Lanzamiento"><i class="fas fa-plus"></i> Agregar nueva vesión
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Nota Lanzamiento</h5>
                                    <select class="form-control" name="nota_lanzamiento"  onchange="cambiarNotaLanzamiento(this.value);"></select>
                                </div>
                                <div class="col-md-9">
                                    <table class="tableNotas table table-hover table-condensed table-bordered table-result-form" id="listarNotasLanzamiento">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th width="10">Versión</th>
                                                <th width="10">Version Actual?</th>
                                                <th width="40">Fecha Registro</th>
                                                <th width="20">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Detalles de la Nota</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-sm btn-success activation" name="btnAgregarDetalleNotaLanzamiento" onClick="agregarDetalleNotaLanzamiento();" id="btn-add"
                            data-toggle="tooltip" data-placement="bottom"  title="Agregar Detalle Nota Lanzamiento"><i class="fas fa-plus"></i> Agregar detalle
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="tableDetalleNotas table table-hover table-condensed table-bordered table-result-form" id="listarDetalleNotasLanzamiento" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th width="10%">Fecha Registro</th>
                                        <th width="8%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('configuracion.modal_nota_lanzamiento')
@include('configuracion.modal_detalle_nota_lanzamiento')
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
    <script src="{{ asset('js/configuracion/notas_lanzamiento.js')}}"></script>
@endsection
