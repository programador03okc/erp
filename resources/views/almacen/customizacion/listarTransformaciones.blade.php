@extends('themes.base')
@include('layouts.menu_cas')

@section('cabecera') Gestión de Transformaciones @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <style>
        #listaTransformacionesPendientes_filter,
        #listaTransformacionesPendientes_filter{
            margin-top:10px;
        }
        .invisible{
            display: none;
        }
    </style>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li>Transformación</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="transformaciones">

    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" id="tab-transformaciones" style="padding-top:10px;padding-bottom:10px;">

                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a data-toggle="tab" href="#pendientes">Ordenes de transformación pendientes</a></li>
                    <li class=""><a data-toggle="tab" href="#procesadas">Ordenes de transformación procesadas</a></li>
                </ul>

                <div class="tab-content">
                    <div id="pendientes" class="tab-pane fade in active">
                        <br>

                        <form id="formFiltrosTransformacionesPendientes" method="POST" target="_blank" action="{{route('almacen.movimientos.pendientes-ingreso.ordenesPendientesExcel')}}">
                            @csrf()
                            {{-- <input type="hidden" name="select_mostrar_pendientes" value="0"> --}}
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view" id="listaTransformacionesPendientes" width="100%">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th></th>
                                            <th>Código</th>
                                            <th>Fecha Entrega</th>
                                            <th>OCAM</th>
                                            <th>CDP</th>
                                            <th>Cliente/Entidad</th>
                                            <th>Requerim.</th>
                                            <th>Fecha Despacho</th>
                                            <th>Fecha Inicio</th>
                                            <!-- <th>Fecha Procesado</th> -->
                                            <th>Almacén</th>
                                            <th>Estado</th>
                                            <th width="8%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 11px;"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div id="procesadas" class="tab-pane fade in ">
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view" id="listaTransformaciones" width="100%">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th>Código</th>
                                            <th>Fecha Entrega</th>
                                            <th>OCAM</th>
                                            <!-- <th>Cuadro Costo</th>
                                            <th>Oportunidad</th> -->
                                            <th>Entidad</th>
                                            <th>Requerim.</th>
                                            <th>Fecha registro</th>
                                            <th>Fecha inicio</th>
                                            <th>Fecha fin</th>
                                            <th>Almacén</th>
                                            <th>Responsable</th>
                                            <th>Obs.</th>
                                            <!-- <th>Estado</th> -->
                                            <th width="8%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 11px;"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('tesoreria.facturacion.archivos_oc_mgcp')


<!-- Modal -->
<div class="modal fade" id="modal-filtros-exportables" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Reporte con filtros</h5>

            </div>
            <form action="" id="form-filtros-ordenes">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="">Fecha</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="filtro-ordenes-transformaciones-pendientes">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
    {{-- <script src="{{ asset('datatables/Buttons/js/buttons.procesadasl5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/js-xlsx/xlsx.full.min.js') }}"></script> --}}


    <script src="{{ asset('js/almacen/customizacion/listarTransformaciones.js')}}"></script>
    <script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js')}}"></script>
    <script>
        $(document).ready(function() {

            vista_extendida();
            let gestionCustomizacion = new GestionCustomizacion('{{Auth::user()->tieneAccion(125)}}');

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let tab = $(e.target).attr("href") // activated tab
                if (tab == '#pendientes') {
                    if ($('#listaTransformacionesPendientes tbody tr').length > 0) {
                        // $('#listaTransformacionesPendientes').DataTable().ajax.reload();
                        $("#listaTransformacionesPendientes").DataTable().ajax.reload(null, false);
                    } else {
                        gestionCustomizacion.listarTransformacionesPendientes();
                    }
                } else if (tab == '#procesadas') {
                    if ($('#listaTransformacionesMadres tbody tr').length > 0) {
                        $('#listaTransformacionesMadres').DataTable().ajax.reload();
                    } else {
                        gestionCustomizacion.listarTransformaciones();
                    }
                }
            });
        });
    </script>

@endsection
