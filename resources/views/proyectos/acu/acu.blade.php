@extends('themes.base')
@include('layouts.menu_proyectos')

@section('cabecera') Análisis de Costos Unitarios @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('proyectos.index')}}"><i class="fas fa-tachometer-alt"></i> Proyectos</a></li>
    <li>Catálogos</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="acu">
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <div class="row">
                    <div class="col-md-12">
                    <div style="text-align: right;">
                    <button type="submit" class="btn btn-success" data-toggle="tooltip"
                            data-placement="bottom" title="Crear un ACU"
                            onClick="open_acu_partida_create();">Crear ACU</button>
                    </div>
                        <table class="mytable table table-condensed table-bordered table-okc-view"
                            id="listaAcu">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Categoría</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Rend</th>
                                    <th>Und</th>
                                    <th>Total</th>
                                    <th>Presupuestos</th>
                                    <th>Estado</th>
                                    <th width="160px">Acción</th>
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

@include('proyectos.acu.acuPartidaCreate')
@include('proyectos.acu.acuCreate')
@include('proyectos.acu.acuModal')
@include('proyectos.acu.acuPresupuesto')
@include('proyectos.insumo.insumoModal')
@include('proyectos.insumo.insumoCreate')
@include('proyectos.insumo.insumoPrecioModal')
@include('proyectos.variables.add_unid_med')
@include('proyectos.presupuesto.verAcu')

@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

    <script src="{{ asset('js/proyectos/acus/acu.js') }}"></script>
    <script src="{{ asset('js/proyectos/acus/acuPartidaCreate.js') }}"></script>
    <script src="{{ asset('js/proyectos/acus/acuCreate.js') }}"></script>
    <script src="{{ asset('js/proyectos/acus/acuModal.js') }}"></script>
    <script src="{{ asset('js/proyectos/insumos/insumoModal.js') }}"></script>
    <script src="{{ asset('js/proyectos/insumos/insumoPrecioModal.js') }}"></script>
    <script src="{{ asset('js/proyectos/insumos/insumoCreate.js') }}"></script>
    <script src="{{ asset('js/proyectos/variables/add_unid_med.js') }}"></script>
    <script src="{{ asset('js/proyectos/presupuesto/verAcu.js') }}"></script>
@endsection
