@extends('themes.base')
@include('layouts.menu_logistica')
@section('option')
<!-- @include('layouts.option') -->
@endsection

@section('cabecera')
    Programación de Mantenimientos de Equipos
@endsection

@section('cuerpo')
<div class="page-main" type="mtto_pendientes">
    <legend class="mylegend">
        <h2>Programación de Mantenimientos de Equipos</h2>
        {{-- <ol class="breadcrumb">
            <li>
                <button type="submit" class="btn btn-success" data-toggle="tooltip"
                data-placement="bottom" title="Generar Mantenimiento"
                onClick="mantenimiento_create();">Generar Mantenimiento</button>
            </li>
        </ol> --}}
    </legend>
    <div class="row">
        <div class="col-md-12">
            <table class="mytable table table-striped table-condensed table-bordered"
             id="listaMttoPendientes">
                <thead>
                    <tr>
                        <th hidden>Id</th>
                        <th></th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Mantenimiento</th>
                        <th>Kilom.Inicial</th>
                        <th>Rango Kilom.</th>
                        <th>Kilom.Vcmto</th>
                        <th>Fecha Inicial</th>
                        <th>Tiempo</th>
                        <th>Fecha Vcmto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
{{-- @include('equipo.mtto_programacion') --}}
@endsection
@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    <script src="{{('/js/equipo/mtto_pendientes.js')}}"></script>
@endsection
