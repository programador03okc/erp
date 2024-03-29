@extends('themes.base')
@include('layouts.menu_tesoreria')

@section('cabecera')
Facturación
@endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/dataTables.checkboxes.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('tesoreria.index')}}"><i class="fas fa-tachometer-alt"></i> Tesorería</a></li>
    <li>Comprobantes</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="pendientesFacturacion">

    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a data-toggle="tab" href="#guias">Ventas Internas</a></li>
                    <li class=""><a data-toggle="tab" href="#requerimientos">Ventas Externas</a></li>
                    {{-- <li class=""><a data-toggle="tab" href="#individuales">Comprobantes Individuales</a></li> --}}
                </ul>

                <div class="tab-content">

                    <div id="guias" class="tab-pane fade in active">
                        <br>
                        <form id="form-guias" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- <div style="display: flex;justify-content: flex-end;">
                                        <button type="button" class="btn btn-success btn-flat" data-toggle="tooltip" data-placement="bottom" title="Seleccione varias Guias para ingresar Factura" onClick="open_doc_ven_create_guias_seleccionadas();">
                                            Ingresar Factura</button>
                                    </div> -->
                                    <table class="mytable table table-condensed table-bordered table-okc-view" id="listaGuias">
                                        <thead>
                                            <tr>
                                                <th hidden></th>
                                                <th></th>
                                                <th>Guía</th>
                                                <th>Fecha Guía</th>
                                                <th>Sede Guía</th>
                                                <th>Entidad/Cliente</th>
                                                <th>Responsable</th>
                                                <th>Cod.Trans.</th>
                                                <th style="width:8%;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="requerimientos" class="tab-pane fade ">
                        <br>
                        <form id="form-requerimientos" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" id="listaRequerimientos">
                                        <thead>
                                            <tr>
                                                <th hidden>#</th>
                                                <th>Fecha Facturación Solicitada</th>
                                                <th>Obs Facturación</th>
                                                <th>Código</th>
                                                <th>Concepto</th>
                                                <th>Sede Req</th>
                                                <th>Entidad/Cliente</th>
                                                <th>Responsable</th>
                                                <th>OCAM</th>
                                                <th>C.P.</th>
                                                <th style="width:8%;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="Individuales" class="tab-pane fade ">
                        <br>
                        <form id="form-Individuales" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" id="listaIndividuales">
                                        <thead>
                                            <tr>
                                                <th hidden>#</th>
                                                <th>Empresa</th>
                                                <th>Tipo de Doc.</th>
                                                <th>Serie-Numero</th>
                                                <th>Fecha emisión</th>
                                                <th>Fecha vcto</th>
                                                <th>Mnd</th>
                                                <th>Monto</th>
                                                <th>Entidad/Cliente</th>
                                                <th>Responsable</th>
                                                <th>Estado</th>
                                                <th style="width:8%;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal-adjuntos-factura" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width:500px;">
        <div class="modal-content">
            <form action="" data-form="guardar-adjuntos" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Adjuntar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            {{-- <div class="form-group"> --}}

                                {{-- <input class="form-control" type="file" name="adjuntos[]" multiple data-action="adjuntos" required> --}}
                            {{-- </div> --}}
                            <fieldset class="group-table">
                                <div class="form-group">
                                    <label for="">Adjuntar nuevo</label>
                                </div>
                                <input type="hidden" name="id_doc_ven">
                                <input type="hidden" name="id_requerimiento">
                                <input type="file" multiple="multiple" class="filestyle" name="adjuntos[]" multiple data-action="adjuntos" data-buttonName="btn-primary" data-buttonText="Seleccionar archivo"  data-iconName="fa fa-folder-open" required/>
                                <br>
                                <div style="display:flex; justify-content: space-between;">
                                    <h6>seleccieon multiple y con un máximo de 2MB por subida.</h6>
                                    <h6>Carga actual: <span class="label label-default" id="peso-estimado">0MB</span></h6>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            <fieldset class="group-table">
                                <div class="form-group">
                                    <label for="">Archivos seleccionados</label>
                                </div>
                                <table class="table">
                                    <tbody data-action="table-body">
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="group-table">
                                <div class="form-group">
                                    <label for="">Archivos adjuntos</label>
                                </div>
                                <table class="table">
                                    <tbody data-action="ver-table-body">
                                    </tbody>
                                </table>
                            </fieldset>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success guardar-adjuntos"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('tesoreria.facturacion.doc_ven_create')
@include('tesoreria.facturacion.doc_ven_ver')
@include('tesoreria.facturacion.doc_ven_anula')
@include('tesoreria.facturacion.archivos_oc_mgcp')

@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<!--<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> -->

<script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script>


<script src="{{ asset('js/tesoreria/facturacion/pendientesFacturacion.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/pendientesFacturacion.js'))}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/facturacionGuia.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/facturacionGuia.js'))}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/facturacionRequerimiento.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/facturacionRequerimiento.js'))}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js')}}?v={{filemtime(public_path('js/tesoreria/facturacion/archivosMgcp.js'))}}"></script>
<script src="{{ asset('js/almacen/documentos/doc_ven_create.js')}}?v={{filemtime(public_path('js/almacen/documentos/doc_ven_create.js'))}}"></script>
<script src="{{ asset('js/almacen/documentos/doc_ven_ver.js')}}?v={{filemtime(public_path('js/almacen/documentos/doc_ven_ver.js'))}}"></script>
<script>
    $(document).ready(function() {
        
        vista_extendida();
        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
        // let facturacion = new Facturacion('{{Auth::user()->tieneAccion(78)}}');
        let facturacion = new Facturacion();
        facturacion.listarGuias();

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            let tab = $(e.target).attr("href");

            if (tab == '#guias') {
                // $('#listaGuias').DataTable().ajax.reload();
                facturacion.listarGuias();
            } else if (tab == '#requerimientos') {
                // $('#listaRequerimientos').DataTable().ajax.reload();
                facturacion.listarRequerimientos();
            }
        });

    });
</script>
@endsection
