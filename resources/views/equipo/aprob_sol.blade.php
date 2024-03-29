@extends('themes.base')
@include('layouts.menu_logistica')

@section('option')
@endsection

@section('cabecera')
    Listado de Solicitud de Equipos
@endsection

@section('cuerpo')
<div class="page-main" type="aprob_sol">
    <legend class="mylegend">
        <h2>Listado de Solicitud de Equipos</h2>
    </legend>
    <div class="col-md-12" id="tab-sol_aprob">
        <table class="mytable table table-condensed table-bordered table-okc-view"
            id="listaSolTodas">
            <thead>
                <tr>
                    <th hidden>Id</th>
                    <th>Código</th>
                    <th>Fecha Solicitud</th>
                    <th>Solicitado por</th>
                    <th>Area</th>
                    <th>Concepto</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Categoria</th>
                    <th>Cant. Pend.</th>
                    <th>Estado</th>
                    <th width="90px">Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@include('equipo.aprob_flujos')
@include('equipo.asignacionCreate')
@include('equipo.asignacion_equipos')
@include('publico.fechas')
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
    <script src="{{('/js/equipo/aprob_sol.js')}}"></script>
    <script src="{{('/js/equipo/asignacionCreate.js')}}"></script>
    <script src="{{('/js/equipo/asignacion_equipos.js')}}"></script>
@endsection
