
@extends('themes.base')

@section('cabecera') Customización @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
    <style>
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Almacén</a></li>
    <li>Movimientos</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')

@if (in_array(138,$array_accesos) || in_array(139,$array_accesos) || in_array(140,$array_accesos) || in_array(141,$array_accesos)|| in_array(142,$array_accesos))
<div class="page-main" type="customizacion">

    <div class="box">
        <form id="form-customizacion">
            <div class="box-header with-border">

                <h3 class="box-title">Customización N° <span class="badge badge-secondary" id="codigo">CU 00-000</span></h3>
                <div class="box-tools pull-right">
                    @if (in_array(138,$array_accesos))
                    <button type="button" class="btn btn-sm btn-warning nueva-customizacion" data-toggle="tooltip" data-placement="bottom"
                        title="Nueva Customización">
                        <i class="fas fa-copy"></i> Nuevo
                    </button>
                    @endif
                    <input id="submit_customizacion" class="btn btn-sm btn-success guardar-customizacion" type="submit" style="display: none;"
                        data-toggle="tooltip" data-placement="bottom" title="Actualizar customizacion" value="Guardar">
                    @if (in_array(139,$array_accesos))
                    <button type="button" class="btn btn-sm btn-primary edit-customizacion"
                        data-toggle="tooltip" data-placement="bottom" title="Editar customizacion">
                        <i class="fas fa-pencil-alt"></i> Editar
                    </button>
                    @endif
                    @if (in_array(140,$array_accesos))
                    <button type="button" class="btn btn-sm btn-danger anular-customizacion" data-toggle="tooltip" data-placement="bottom"
                        title="Anular customizacion" onClick="anularCustomizacion();">
                        <i class="fas fa-trash"></i> Anular
                    </button>
                    @endif
                    @if (in_array(141,$array_accesos))
                    <button type="button" class="btn btn-sm btn-info buscar-customizacion" data-toggle="tooltip" data-placement="bottom"
                        title="Buscar historial de registros" onClick="transformacionModal('C');">
                        <i class="fas fa-search"></i> Buscar</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-secondary cancelar" data-toggle="tooltip" data-placement="bottom"
                        title="Cancelar" style="display: none;">
                            Cancelar</button>
                    @if (in_array(142,$array_accesos))
                    <button type="button" class="btn btn-sm btn-success procesar-customizacion" data-toggle="tooltip" data-placement="bottom"
                        title="Procesar customizacion" onClick="procesarCustomizacion();">
                        <i class="fas fa-share"></i> Procesar
                    </button>
                    @endif

                    <button type="button" class="btn btn-sm btn-default imprimir-ingreso" data-toggle="tooltip" data-placement="bottom"
                        title="Imprimir Ingreso" onClick="imprimirIngreso();"><i class="fas fa-file-pdf"></i> Ingreso</button>

                    <button type="button" class="btn btn-sm btn-default imprimir-salida" data-toggle="tooltip" data-placement="bottom"
                        title="Imprimir Salida" onClick="imprimirSalida();"><i class="fas fa-file-pdf"></i> Salida</button>

                </div>
            </div>
            <div class="box-body">

                <div class="row" style="padding-left: 10px;padding-right: 10px;margin-bottom: 0px;">
                    <div class="col-md-12">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                        <input type="hidden" name="id_customizacion" primary="ids">
                        <input type="hidden" name="id_ingreso">
                        <input type="hidden" name="id_salida">

                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-sm-4 control-label">Almacén: </label>
                                <div class="col-sm-8">
                                    <select class="form-control js-example-basic-single edition limpiarCustomizacion"
                                        name="id_almacen" required>
                                        <option value="">Elija una opción</option>
                                        @foreach ($almacenes as $almacen)
                                        <option value="{{$almacen->id_almacen}}">{{$almacen->codigo}} - {{$almacen->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="col-sm-2 control-label">Comentario: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control edition limpiarCustomizacion"
                                        name="observacion" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-left: 10px;padding-right: 10px;margin-top: 0px;">
                    <div class="col-md-4">
                        <label class="col-sm-4 control-label">Responsable: </label>
                        <div class="col-sm-8">
                            <select class="form-control js-example-basic-single edition limpiarCustomizacion"
                                name="id_usuario" required>
                                <option value="">Elija una opción</option>
                                @foreach ($usuarios as $usuario)
                                <option value="{{$usuario->id_usuario}}">{{$usuario->nombre_corto}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-5 control-label">Fecha del documento: </label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control edition limpiarCustomizacion" name="fecha_proceso"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Registrado por:</label>
                        <span id="nombre_registrado_por" class="limpiarTexto"></span>
                    </div>
                </div>
                <div class="row" style="padding-left: 10px;padding-right: 10px;margin-top: 0px;">
                    <div class="col-md-4">
                        <label class="col-sm-4 control-label">Moneda: </label>
                        <div class="col-sm-8">
                            <select class="form-control js-example-basic-single edition limpiarCustomizacion"
                                name="id_moneda" required>
                                <option value="">Elija una opción</option>
                                @foreach ($monedas as $moneda)
                                <option value="{{$moneda->id_moneda}}">{{$moneda->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-sm-5 control-label">Tipo de cambio: </label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control edition limpiarCustomizacion" name="tipo_cambio" step="0.0001"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Fecha registro:</label>
                        <span id="fecha_registro" class="limpiarTexto"></span>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info" style="margin-bottom: 0px;">
                        <div class="panel-heading"><strong>Productos Base</strong></div>
                        <table id="listaMateriasPrimas" class="table">
                            <thead>
                                <tr style="background: lightskyblue;">
                                    <th>Código</th>
                                    <th>Part Number</th>
                                    <th width='30%'>Descripción</th>
                                    <th>Cant.</th>
                                    <th>Unid.</th>
                                    <th>Cos.Prom</th>
                                    <th>Unit.</th>
                                    <th>Total</th>
                                    <th width='8%' style="padding:0px;">
                                        <i class="fas fa-plus-square icon-tabla green boton add-new-sobrante edition"
                                        id="addProductoBase" data-toggle="tooltip" data-placement="bottom"
                                        title="Agregar Producto" onClick="agregarProductoBase();"></i>
                                        <i class="fas fa-sync-alt icon-tabla boton add-new-sobrante edition"
                                        id="addProductoBase" data-toggle="tooltip" data-placement="bottom"
                                        title="Obtener el costo promedio actual" onClick="actualizarCostosBase();"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-danger" style="margin-bottom: 0px;">
                        <div class="panel-heading"><strong>Productos Sobrantes</strong></div>
                        <table id="listaSobrantes" class="table">
                            <thead>
                                <tr style="background: lightcoral;">
                                    <th>Código</th>
                                    <th>Part Number</th>
                                    <th width='40%'>Descripción</th>
                                    <th>Cant.</th>
                                    <th>Unid.</th>
                                    <th>Unit.</th>
                                    <th>Total</th>
                                    <th width='8%' style="padding:0px;">
                                        <i class="fas fa-plus-square icon-tabla green boton add-new-sobrante edition"
                                        id="addSobrante" data-toggle="tooltip" data-placement="bottom"
                                        title="Agregar Producto" onClick="agregarProductoSobrante();"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success" style="margin-bottom: 0px;">
                        <div class="panel-heading"><strong>Productos Transformados</strong></div>
                        <table id="listaProductoTransformado" class="table">
                            <thead>
                                <tr style="background: palegreen;">
                                    <th>Código</th>
                                    <th>Part Number</th>
                                    <th width='40%'>Descripción</th>
                                    <th>Cant.</th>
                                    <th>Unid.</th>
                                    <th>Unit.</th>
                                    <th>Total</th>
                                    <th width='8%' style="padding:0px;">
                                        <i class="fas fa-plus-square icon-tabla green boton add-new-sobrante edition"
                                        id="addSobrante" data-toggle="tooltip" data-placement="bottom"
                                        title="Agregar Producto" onClick="agregarProductoTransformado();"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="totalSobrantesTransformados" width="100%">
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger pulse" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            Solicite los accesos
        </div>
    </div>
</div>
@endif

@include('almacen.customizacion.transformacionModal')
@include('almacen.customizacion.transformacionProcesar')
@include('almacen.customizacion.productoCatalogoModal')
@include('almacen.transferencias.productosAlmacenModal')
@include('almacen.guias.guia_com_series')
@include('almacen.guias.guia_ven_series')


@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script> --}}

<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/js-xlsx/xlsx.full.min.js') }}"></script>

<script src="{{ asset('js/almacen/customizacion/customizacion.js')}}?v={{filemtime(public_path('js/almacen/customizacion/customizacion.js'))}}"></script>
<script src="{{ asset('js/almacen/customizacion/transformacionModal.js')}}?v={{filemtime(public_path('js/almacen/customizacion/transformacionModal.js'))}}"></script>
<script src="{{ asset('js/almacen/customizacion/productoCatalogoModal.js')}}?v={{filemtime(public_path('js/almacen/customizacion/productoCatalogoModal.js'))}}"></script>
<script src="{{ asset('js/almacen/transferencias/productosAlmacenModal.js')}}?v={{filemtime(public_path('js/almacen/transferencias/productosAlmacenModal.js'))}}"></script>
<script src="{{ asset('js/almacen/customizacion/customizacionDetalleBase.js')}}?v={{filemtime(public_path('js/almacen/customizacion/customizacionDetalleBase.js'))}}"></script>
<script src="{{ asset('js/almacen/customizacion/customizacionDetalleSobrante.js')}}?v={{filemtime(public_path('js/almacen/customizacion/customizacionDetalleSobrante.js'))}}"></script>
<script src="{{ asset('js/almacen/customizacion/customizacionDetalleTransformado.js')}}?v={{filemtime(public_path('js/almacen/customizacion/customizacionDetalleTransformado.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/guia_com_det_series.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_com_det_series.js'))}}"></script>
<script src="{{ asset('js/almacen/guia/guia_ven_series.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_ven_series.js'))}}"></script>

{{-- para leer archivos excel con js --}}
<script src="{{ asset('template/adminlte2-4/plugins/reed-excel-file/read-excel-file.min.js')}}?v={{filemtime(public_path('template/adminlte2-4/plugins/reed-excel-file/read-excel-file.min.js'))}}"></script>

<script>
    $(document).ready(function() {

        usuarioSession = '{{Auth::user()->id_usuario}}';
        usuarioNombreSession = '{{Auth::user()->nombre_corto}}';

        $('#import-serie-excel').change( async function (e) {
            e.preventDefault();

            // let data = new FormData($('#form-impor-excel')[0]);
            let numero_marcados = 0;
            let t_body = $('#listaSeriesVen').find('tbody');
            let contenido = await readXlsxFile($(this)[0].files[0]);
            let total = contenido.length - 1;

            $.each(contenido, function (index, element) {

                $.each(t_body.find('tr'), function (index, element_tr) {
                    if (element_tr.children[2].innerText==element[0]) {
                        numero_marcados = numero_marcados +1;
                        $('#listaSeriesVen').find('tbody').find('input[data-serie="'+element[0]+'"]').attr('checked','true');
                    }
                });
            });
            $('#form-impor-excel').find('#total-excel').text('Total de series en el excel: '+total+' - Total de seleccionados : '+numero_marcados+'');

            $("#form-impor-excel")[0].reset();
            $('#modal-guia_ven_series').find('#total-excel').removeClass('d-none');
        });
        $("#modal-guia_ven_series").on("hidden.bs.modal", () => {
            $(this).find('#total-excel').addClass('d-none')
        });
    });
</script>
@endsection
