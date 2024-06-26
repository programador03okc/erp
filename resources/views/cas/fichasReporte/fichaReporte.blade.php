
@extends('themes.base')

@section('cabecera') Gestión de incidencias @endsection
@include('layouts.menu_cas')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
    <style>
        .invisible{
            display: none;
        }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li>Garantías</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')

    <div class="box box-solid">
        <div class="box-body">
            <div class="page-main" type="incidencia">
                <div class="col-md-12" id="tab-incidencias" style="padding-top:10px;padding-bottom:10px;">

                    <ul class="nav nav-tabs" id="myTabIncidencias">
                        <li class="active"><a data-toggle="tab" href="#incidencias">Lista de Incidencias</span></a></li>
                        <li class=""><a data-toggle="tab" href="#devoluciones">Lista de Devoluciones</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="incidencias" class="tab-pane fade in active">

                            <div class="row" style="padding-top:10px;">
                                <div class="col-md-12">
                                    <form id="formFiltrosIncidencias" method="POST" target="_blank"
                                    action="{{route('cas.garantias.fichas.incidenciasExcel')}}">
                                        @csrf()
                                    </form>
                                    <table class="mytable table table-condensed table-bordered table-okc-view"
                                        id="listaIncidencias" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th hidden></th>
                                                <th>Código</th>
                                                <th>Estado</th>
                                                <th>Empresa</th>
                                                <th>Cliente</th>
                                                <th>Nro Orden</th>
                                                <th>Factura</th>
                                                <th>Nombre contacto</th>
                                                <th>Fecha reporte</th>
                                                <th>Serie</th>
                                                <th>Fecha registro</th>
                                                <th>Responsable</th>
                                                <th>Falla</th>
                                                <th width="70px">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div style="display:flex;">
                                        <select class="form-control" name="selectPeriodoIncidencia" onChange="generarFiltrosIncidencias(event);" style="width: 100px;">
                                            @foreach ($periodos as $periodo)
                                            <option value="{{$periodo->id_periodo}}" @if (session('clPeriodoIncidencia')==$periodo->descripcion) selected @else '' @endif>{{$periodo->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="devoluciones" class="tab-pane fade ">

                            <div class="row" style="padding-top:10px;">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" id="listaDevoluciones">
                                        <thead>
                                            <tr>
                                                <th hidden></th>
                                                <th width="5%">Código</th>
                                                <th width="10%">Estado</th>
                                                <th width="10%">Fecha registro</th>
                                                <th width="5%">Tipo</th>
                                                <th width="10%">Razón Social</th>
                                                <th width="10%">Almacén</th>
                                                <th width="20%">Concepto</th>
                                                <th width="10%">Fichas Técnicas</th>
                                                <th width="10%">Elaborado Por</th>
                                                <th width="10%">Revisado Por</th>
                                                <th width="6%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <div style="display:flex;">
                                        <select class="form-control" name="selectPeriodoDevoluciones" onChange="generarFiltrosDevoluciones(event);" style="width: 100px;">
                                            @foreach ($periodos as $periodo)
                                            <option value="{{$periodo->id_periodo}}" @if (session('clPeriodoDevolucion')==$periodo->descripcion) selected @else '' @endif>{{$periodo->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filtros-->
    <div class="modal fade" id="model-filtros" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title">Filtros</h5>

                </div>
                <form action="" id="form-filtros">


                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group text-right">
                                    <label for="">Fechas :</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group text-center">
                                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control text-center">
                                            <small id="helpId" class="text-muted">Fecha de Inicio</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-center">
                                            <input type="date" name="fecha_final" id="fecha_final" class="form-control text-center">
                                            <small id="helpId" class="text-muted">Fecha de Final</small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="reporte-excel-filtros">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('cas.fichasReporte.fichaReporteCreate')
    @include('almacen.devoluciones.fichaTecnicaCreate')
    @include('cas.fichasReporte.cierreIncidencia')
    @include('cas.fichasReporte.cancelarIncidencia')
    @include('cas.fichasReporte.verDatosContacto')
    @include('cas.fichasReporte.verAdjuntosFicha')
    @include('almacen.devoluciones.verFichasTecnicasAdjuntas')
    @include('almacen.devoluciones.devolucionRevisar')

@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>

    <script src="{{ asset('js/cas/fichasReporte/fichaReporte.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/fichaReporte.js'))}}"></script>
    <script src="{{ asset('js/cas/fichasReporte/fichaReporteCreate.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/fichaReporteCreate.js'))}}"></script>
    <script src="{{ asset('js/cas/fichasReporte/cierreIncidencia.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/cierreIncidencia.js'))}}"></script>
    <script src="{{ asset('js/cas/fichasReporte/cancelarIncidencia.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/cancelarIncidencia.js'))}}"></script>
    <script src="{{ asset('js/cas/fichasReporte/verDetalleReportes.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/verDetalleReportes.js'))}}"></script>
    <script src="{{ asset('js/cas/fichasReporte/gestionDevoluciones.js')}}?v={{filemtime(public_path('js/cas/fichasReporte/gestionDevoluciones.js'))}}"></script>

    <script>
        $(document).ready(function() {

            $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
            vista_extendida();
            listarIncidencias();
            listarDevoluciones();
        });
    </script>

@endsection


{{-- ---- --}}
