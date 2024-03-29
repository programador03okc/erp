
@extends('themes.base')

@section('cabecera') Prorrateo de Costos @endsection
@include('layouts.menu_almacen')
@section('option')
    @include('layouts.option')
@endsection
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
    <li><a href="{{route('almacen.index')}}"><i class="fas fa-tachometer-alt"></i> Almacenes</a></li>
    <li>Movimientos</li>
    <li class="active">@yield('cabecera')</li>
  </ol>
@endsection

@section('cuerpo')

@if (sizeof($array_accesos_botonera)!==0)
<div class="page-main" type="prorrateo">
    <!-- <div class="row"> -->
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <form id="form-prorrateo" type="register"  form="formulario">

                    <input class="oculto" name="id_prorrateo" primary="ids"/>
                    <div class="row">
                        <div class="col-md-12">

                            <!-- <div class="panel panel-default">
                                <div class="panel-heading">Ingreso(s) por Compra</div> -->

                                    <div class="row">
                                        <div class="col-md-2"><h5>Seleccione la Moneda </h5></div>
                                        <div class="col-md-2">
                                            <div style="display:flex;">
                                                <select class="form-control activation" name="id_moneda_global" required>
                                                    <option value="" selected disabled>Elija una opción</option>
                                                    @foreach ($monedas as $mn)
                                                        <option value="{{$mn->id_moneda}}">{{$mn->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 style="display:flex;justify-content: space-between;">Ingreso(s) por Compra
                                        <div>
                                            <span class="label" id="estado_doc">&nbsp;</span>
                                            <span style="color:blue;" id="codigo"></span>
                                            <!-- <button type="button" name="btn-imprimir-requerimento-pdf" class="btn btn-info btn-sm" onclick="ImprimirRequerimientoPdf()" disabled><i class="fas fa-print"></i> Imprimir</button> -->
                                        </div>
                                    </h4>

                                    <table class="mytable table table-condensed table-bordered table-okc-view" width="100%"
                                        id="listaGuiaDetalleProrrateo">
                                        <thead>
                                            <tr>
                                                <th>Guía</th>
                                                <th>Fecha Emisión</th>
                                                <th width='5%'>Código</th>
                                                <th width='5%'>Part Number</th>
                                                <th width='30%'>Descripción</th>
                                                <th>Cant.</th>
                                                <th>Unid.</th>
                                                <th>Mnd.</th>
                                                <th>Valor Compra</th>
                                                <th>Tipo Cambio</th>
                                                <th style="background-color: rgb(30, 139, 255);"><span id="valor">Valor</span></th>
                                                <th>Peso Volumen</th>
                                                <th>Adicional Valor</th>
                                                <th>Adicional Peso</th>
                                                <th>Importe Prorrateado</th>
                                                <th style="background-color: rgb(30, 139, 255);">Importe en kardex</th>
                                                <th>
                                                    <!-- <i class="fas fa-plus-square icon-tabla green boton "
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Agregar Guia Compra" onClick="guia_compraModal();"></i> -->
                                                    <button type="button" class="btn btn-success btn-xs boton activation" data-toggle="tooltip"
                                                        data-placement="bottom" title="Agregar Guía Compra" onClick="guia_compraModal();">
                                                        <i class="fas fa-plus"></i></button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colSpan="7" class="right">
                                                    Registrado por: <label id="registrado_por"></label>
                                                </td>
                                                <td style="text-align: right"><label id="moneda"></label></td>
                                                <td style="text-align: right"><label id="total_valor_compra"></label></td>
                                                <td style="text-align: right"><label id="soles"></label></td>
                                                <td style="text-align: right"><label id="total_valor"></label></td>
                                                <td style="text-align: right"><label id="total_peso"></label></td>
                                                <td style="text-align: right"><label id="total_adicional_valor"></label></td>
                                                <td style="text-align: right"><label id="total_adicional_peso"></label></td>
                                                <td style="text-align: right"><label id="total_costo"></label></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="borde-group-verde">
                                                <h4 style="margin:0;">Valorización de Ingreso</h4>
                                                <table width="100%">
                                                    <tr height="20px">
                                                        <td></td>
                                                        <td>Moneda</td>
                                                        <td width="20">:</td>
                                                        <td style="color: #398439;">Soles</td>
                                                        <td>Total</td>
                                                        <td width="20">:</td>
                                                        <td width="130"><input type="number" class="form-control right" name="total_ingreso" readOnly/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="borde-group-rojo">
                                                <h4 style="margin:0;">Documento(s) Adicionales</h4>
                                                <table width="100%">
                                                    <tr>
                                                        <td></td>
                                                        <td>Sumatoria x Valor</td>
                                                        <td width="20">:</td>
                                                        <td width="130"><input type="number" class="form-control right" name="total_comp_valor" readOnly/></td>
                                                        <td class="right">Sumatoria x Peso Volumen</td>
                                                        <td width="20">:</td>
                                                        <td width="130"><input type="number" class="form-control right" name="total_comp_peso" readOnly/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                <!-- </div>
                            </div> -->
                        </div>
                    </div>

                    <div class="row" style="padding-top:10px;padding-bottom:10px;">
                        <div class="col-md-12" >

                            <!-- <div class="panel panel-default">
                                <div class="panel-heading">Documentos Adicionales</div> -->
                                <h4 style="display:flex;justify-content: space-between;">Documento(s) Adicionales </h4>

                                    <table class="mytable table table-condensed table-bordered table-okc-view" width="100%"
                                        id="listaProrrateos">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo de Doc</th>
                                                <th>Serie-Número</th>
                                                <th>Razon Social</th>
                                                <th>Fecha Emisión</th>
                                                <th>Mnd</th>
                                                <th>Total</th>
                                                <th>Tipo Cambio</th>
                                                <th>Importe S/</th>
                                                <th>Importe Aplicado</th>
                                                <th>Tipo Prorrateo</th>
                                                <th>
                                                    <!-- <i class="fas fa-plus-square icon-tabla green boton"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Agregar Documento de Prorrateo" onClick="open_doc_prorrateo();"></i> -->
                                                    <button type="button" class="btn btn-success btn-xs boton activation" data-toggle="tooltip"
                                                        data-placement="bottom" title="Agregar Documento de Prorrateo" onClick="open_doc_prorrateo();">
                                                        <i class="fas fa-plus"></i></button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                <!-- </div>
                            </div> -->
                        </div>
                    </div>
                </form>

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

@include('almacen.prorrateo.doc_prorrateo_create')
@include('logistica.cotizaciones.proveedorModal')
@include('almacen.prorrateo.agregarProveedor')
@include('almacen.guias.guia_compraModal')
@include('almacen.prorrateo.doc_prorrateoModal')

@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>


    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    

    <script src="{{ asset('js/almacen/prorrateo/doc_prorrateo.js')}}?v={{filemtime(public_path('js/almacen/prorrateo/doc_prorrateo.js'))}}"></script>
    <script src="{{ asset('js/almacen/prorrateo/doc_prorrateo_create.js')}}?v={{filemtime(public_path('js/almacen/prorrateo/doc_prorrateo_create.js'))}}"></script>
    <script src="{{ asset('js/almacen/prorrateo/doc_prorrateo_detalle.js')}}?v={{filemtime(public_path('js/almacen/prorrateo/doc_prorrateo_detalle.js'))}}"></script>
    <script src="{{ asset('js/logistica/proveedorModal.js')}}?v={{filemtime(public_path('js/logistica/proveedorModal.js'))}}"></script>
    <script src="{{ asset('js/almacen/prorrateo/agregarProveedor.js')}}?v={{filemtime(public_path('js/almacen/prorrateo/agregarProveedor.js'))}}"></script>
    <script src="{{ asset('js/almacen/guia/guia_compraModal.js')}}?v={{filemtime(public_path('js/almacen/guia/guia_compraModal.js'))}}"></script>
    <script src="{{ asset('js/almacen/prorrateo/doc_prorrateoModal.js')}}?v={{filemtime(public_path('js/almacen/prorrateo/doc_prorrateoModal.js'))}}"></script>

    <script>
        vista_extendida();
    </script>
@endsection

{{-- ----------------------------------------------- --}}
