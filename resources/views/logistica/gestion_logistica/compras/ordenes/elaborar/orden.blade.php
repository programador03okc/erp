@extends('themes.base')
@include('layouts.menu_logistica')
@section('option')
@endsection

@section('cabecera') Orden @endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
<style>
    .mt-4 {
        margin-top: 35px;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .label-check {
        font-weight: normal;
        font-size: 15px;
        cursor: pointer;
    }

    .panel-heading .accordion-toggle:after {
        /* symbol for "opening" panels */
        font-family: 'Glyphicons Halflings';
        /* essential for enabling glyphicon */
        content: "\e114";
        /* adjust as needed, taken from bootstrap.css */
        float: right;
        /* adjust as needed */
        color: grey;
        /* adjust as needed */
    }

    .panel-heading .accordion-toggle.collapsed:after {
        /* symbol for "collapsed" panels */
        content: "\e080";
        /* adjust as needed, taken from bootstrap.css */
    }

    dd{
        padding-bottom:10px;
    }
    dl{
        margin-bottom: 0px;
    }

    .input-xs,.btn.dropdown-toggle.btn-default {
        height: 24px;
        font-size: 9px;
    }
</style>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Órdenes</li>
    <li class="active">Orden</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="orden">
    <form id="form-orden" type="register" form="formulario">
        <input type="hidden" name="id_orden" primary="ids">
        <input type="hidden" name="tipo_cambio_compra">
        <input class="oculto" name="monto_subtotal">
        <input class="oculto" name="monto_igv">
        <input class="oculto" name="monto_total">




        <div class="row">
            <div class="col-md-12">
                <h4 style="display:flex;justify-content: space-between;">
                    <div>
                        <button type="button" name="btn-nuevo" class="btn btn-default btn-sm" title="Nuevo"><i class="fas fa-file"></i> Nuevo</button>
                        <button type="button" name="btn-editar" class="btn btn-default btn-sm" title="Editar"><i class="fas fa-edit"></i> Editar</button>
                        <button type="button" name="btn-guardar" class="btn btn-success btn-sm" title="Guardar"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" name="btn-nuevo" class="btn btn-default btn-sm" title="Vincular requerimiento"><i class="fas fa-file-prescription"></i> Vincular Requerimiento</button>
                        <button type="button" name="btn-historial" class="btn btn-default btn-sm" title="Historial"><i class="fas fa-folder"></i> Historial</button>
                        @if (in_array(285,$array_accesos))
                        <button type="button" name="btn-migrar-orden-softlink" class="btn btn-default btn-sm handleClickMigrarOrdenASoftlink" title="Migrar orden a softlink" disabled><i class="fas fa-file-export"></i> Migrar Orden a soflink</button>
                        @endif

                    </div>
                    <div>




                    </div>
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="col-md-12">
                    <fieldset class="group-table">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Datos de cabecera
                                </div>
                            </div>
                            <div class="panel-body collapse in" id="collapseExample1" aria-expanded="true">
                                <div class="list-group">
                                    <span class="list-group-item">
                                        <h4 class="list-group-item-heading">Detalle documento </h4>
                                        <div class="list-group-item-text">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <dl class="">
                                                        <dt>Tipo de orden</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoTipoOrden"></span>
                                                            <select  class="form-control input-xs handleChangeSede" name="id_tp_documento">
                                                                @foreach ($tp_documento as $tp)
                                                                @if($tp->descripcion == 'Orden de Compra')
                                                                <option value="{{$tp->id_tp_documento}}" selected>{{$tp->descripcion}}</option>
                                                                @else
                                                                @if((!in_array(Auth::user()->id_usuario,[17,27,3,1,77]) && $tp->id_tp_documento == 13))
                                                                @else
                                                                <option value="{{$tp->id_tp_documento}}"  >{{$tp->descripcion}}</option>
                                                                @endif
                                                                @endif
                                                                @endforeach

                                                            </select>
                                                        </dd>
                                                        <dt>Moneda</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoMoneda"></span>
                                                            <select class="form-control input-xs" name="id_moneda">
                                                                @foreach ($tp_moneda as $tpm)
                                                                    <option value="{{$tpm->id_moneda}}" data-simbolo-moneda="{{$tpm->simbolo}}">{{$tpm->descripcion}} ( {{$tpm->simbolo}} )</option>
                                                                @endforeach
                                                            </select>
                                                        </dd>
                                                        <dt>Periodo</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoPeriodo"></span>
                                                            <select class="form-control input-xs" name="id_periodo">
                                                            @foreach ($periodos as $periodo)
                                                                <option value="{{$periodo->id_periodo}}">{{$periodo->descripcion}}</option>
                                                            @endforeach
                                                            </select>
                                                        </dd>
                                                        <dt>Fecha Emisión</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoFechaEmision"></span>
                                                            <input class="form-control input-xs" name="fecha_emision" type="datetime-local" value="2024-03-19T11:23">
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">
                                                        <dt>Código orden</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoCodigoOrden"></span>
                                                            <p class="form-control-static" name="codigo_orden">OC-240240</p>
                                                        </dd>
                                                        <dt>Empresa / sede</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoEmpresaSede"></span>
                                                            <select  class="form-control selectpicker input-xs handleChangeSede " name="id_sede" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                @foreach ($sedes as $sede)
                                                                <option value="{{$sede->id_sede}}" data-id-empresa="{{$sede->id_empresa}}" data-direccion="{{$sede->direccion}}" data-id-ubigeo="{{$sede->id_ubigeo}}" data-ubigeo-descripcion="{{$sede->ubigeo_descripcion}}">{{$sede->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </dd>
                                                        <dt>Cod. softlink</dt>
                                                        <dd>
                                                            <span id="textoCabeceraDetalleDocumentoCodigoSoftlink"></span>
                                                            <p class="form-control-static" name="codigo_softlink">00240240</p>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="list-group">
                                    <span class="list-group-item">
                                        <h4 class="list-group-item-heading">Proveedor </h4>
                                        <div class="list-group-item-text">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <dl class="">
                                                            <dt>Razon Social</dt>
                                                            <dd>
                                                                <select class="form-control selectpicker input-xs onChangeSeleccionarProveedor" name="razon_social_proveedor" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                    @foreach ($proveedores as $proveedor)
                                                                    <option value="{{$proveedor->id_proveedor}}" data-id-contribuyente="{{$proveedor->id_contribuyente}}" >{{$proveedor->contribuyente->nro_documento!=null?$proveedor->contribuyente->nro_documento.' - ':''}} {{$proveedor->contribuyente->razon_social}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">
                                                        <dt>Dirección</dt>
                                                        <dd>
                                                            <p class="form-control-static" name="direccion_proveedor"></p>
                                                        </dd>
                                                        <dt>Contacto</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="contacto_proveedor">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                        <dt>Rubro</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="rubro_proveedor">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">

                                                    <dt>Cuenta Bancaria</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="cuenta_bancaria_proveedor">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                        <dt>Telefono contacto</dt>
                                                        <dd>
                                                            <p class="form-control-static" name="telefono_proveedor">000-0000</p>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="list-group">
                                    <span class="list-group-item">
                                        <h4 class="list-group-item-heading">Condición de compra </h4>
                                        <div class="list-group-item-text">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <dl class="">
                                                        <dt>Forma de pago</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="forma_pago">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                        <dt>Requerimiento</dt>
                                                        <dd>
                                                            <p class="form-control-static" name="requerimiento_vinculados">RM-240240</p>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">
                                                        <dt>Plazo de entrega</dt>
                                                        <dd>
                                                            <div style="display:flex;">
                                                                <input type="number" name="plazo_entrega" min="0" class="form-control input-xs" style="text-align:right;">
                                                                <input type="text" value="días" class="form-control group-elemento input-xs" style="text-align:center;" readonly="">
                                                            </div>
                                                        </dd>
                                                        <dt>Tipo Documento</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="tipo_documento">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="list-group">
                                    <span class="list-group-item">
                                        <h4 class="list-group-item-heading">Datos para el despacho </h4>
                                        <div class="list-group-item-text">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <dl class="">
                                                        <dt>Dirección entrega</dt>
                                                        <dd>
                                                            <input class="form-control input-xs" name="direccion_entrega" type="text">
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">
                                                    <dt>Ubigeo</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="ubigeo">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                        <dt>Personal autorizado #2</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="personal_autorizado_2">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>

                                                        

                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="">

                                                        <dt>Personal autorizado #1</dt>
                                                        <dd>
                                                            <select class="form-control input-xs" name="personal_autorizado_1">
                                                                <option value="" disabled>Elija una opción</option>
                                                            </select>
                                                        </dd>
                                                        <dt>Compra local</dt>
                                                        <dd>
                                                                <label>
                                                                    <input type="checkbox" id="compra_local" name="compra_local"> Compras locales
                                                                </label>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-12">
                                                    <dl>
                                                    <dt>Observación</dt>
                                                        <dd>
                                                            <textarea class="form-control input-xs" name="observacion" cols="100" rows="100" style="height:50px;"></textarea>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </fieldset>

                </div>
            </div>
            <div class="col-md-8">
                <fieldset class="group-table">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#collapseExample">
                                    <span>
                                        <span class="label label-default">Cod. Orden: <span class="text-primary" name="tituloDocumentoCodigoOrden[]">OC-240240</span></span>
                                        <span class="label label-default">Cod. Softlink: <span class="text-primary" name="tituloDocumentoCodigoSoftlink[]">00100189</span></span>
                                        <span class="label label-default">Proveedor: <span class="text-primary" name="tituloDocumentoProveedor[]">MAXIMA S.A.C</span></span>
                                        <span class="label label-default">Empresa: <span class="text-primary" name="tituloDocumentoEmpresa[]">OK COMPUTER EIRL</span></span>
                                        <span class="label label-default">Sede: <span class="text-primary" name="tituloDocumentoSede[]">LIMA</span></span>
                                    </span>

                                </a>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acción <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Imprimir orden</a></li>
                                        <li><a href="#">Editar orden</a></li>
                                        <li><a href="#">Anular orden</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#">Migrar orden a Softlink</a></li>
                                        <li><a href="#">Vincular con orden Softlink</a></li>
                                    </ul>
                                </div>
                            </div>



                        </div>
                        <div class="panel-body collapse" id="collapseExample">
                            <div class="row">
                                <!-- Nav tabs -->
                                <div class="col-md-2">
                                    <ul class="nav nav-pills nav-stacked" role="tablist">
                                        <li role="presentation" class="active"><a href="#seccionDetalle" aria-controls="seccionDetalle" role="tab" data-toggle="tab">Detalle documento</a></li>
                                        <li role="presentation"><a href="#seccionProveedor" aria-controls="seccionProveedor" role="tab" data-toggle="tab">Proveedor</a></li>
                                        <li role="presentation"><a href="#seccionCondicionCompra" aria-controls="seccionCondicionCompra" role="tab" data-toggle="tab">Condicion de compra</a></li>
                                        <li role="presentation"><a href="#seccionDespacho" aria-controls="seccionDespacho" role="tab" data-toggle="tab">Despacho</a></li>
                                    </ul>
                                </div>
                                <!-- Tab panes -->
                                <div class="col-md-10">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="seccionDetalle">
                                            <fieldset class="group-table">
                                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Tipo Orden</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoTipoOrden[]">Compra</p>
                                                            </dd>
                                                            <dt>Periodo</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoPeriodo[]"></p>
                                                            </dd>

                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Código</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoCodigoOrden[]"></p>
                                                            </dd>
                                                            <dt>Cod.Softlink</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoCodigoSoftlink[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Moneda</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoMoneda[]"></p>
                                                            </dd>
                                                            <dt>Fecha Emisión</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="descripcionUbigeoFechaEmision[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Empresa / Sede</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDetalleDocumentoEmpresaSede[]"></p>
                                                            </dd>
                                                            <dt></dt>
                                                            <dd><img id="logo_empresa" src="/images/img-wide.png" alt="" style="height:56px;!important;width:100%;!important;margin-top:-20px;"></dd>
                                                        </dl>
                                                    </div>
                                                </div>


                                            </fieldset>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="seccionProveedor">
                                            <fieldset class="group-table">
                                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Razón social</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorRazonSocial[]"></p>
                                                            </dd>
                                                            <dt>Contacto</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorContacto[]"></p>
                                                            </dd>


                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Ruc</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorRuc[]"></p>
                                                            </dd>
                                                            <dt>Telefono contacto</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorTelefonoContacto[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Dirección</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorDireccion[]"></p>
                                                            </dd>
                                                            <dt>Rubro</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorRubro[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Cuenta Bancaria</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenProveedorCuentaBancaria[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="seccionCondicionCompra">
                                            <fieldset class="group-table">
                                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Forma de pago</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenCondicionCompraFormaPago[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Plazo entrega</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenCondicionCompraPlazoEntrega[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Requerimiento</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenCondicionCompraRequerimiento[]"></span></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Tipo Documento</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenCondicionCompraTipoDocumento[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="seccionDespacho">
                                            <fieldset class="group-table">
                                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Direccion de Entrega</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoDireccionEntrega[]"></p>
                                                            </dd>
                                                            <dt>Compra locales</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoCompraLocal[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Ubigeo entrega</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoUbigeoEntrega[]"></p>
                                                            </dd>
                                                            <dt>Observación</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoObservacion[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Personal autorizado #1</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoPersonalAutorizado1[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <dl class="">
                                                            <dt>Personal autorizado #2</dt>
                                                            <dd>
                                                                <p class="form-control-static" name="textoOrdenDespachoPersonalAutorizado2[]"></p>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <legend>
                                        <h6>Items de orden</h6>
                                    </legend>
                                    <div class="btn-group" role="group" aria-label="...">
                                        @if((in_array(Auth::user()->id_usuario,[3,17,27,1,77])))
                                        <button type="button" class="btn btn-xs btn-success activation handleClickCatalogoProductosModal" id="btnAgregarProducto" data-toggle="tooltip" data-placement="bottom" title="Agregar producto"><i class="fas fa-plus"></i> Productos</button>
                                        @endif
                                        <button type="button" class="btn btn-xs btn-info activation handleClickCatalogoProductosObsequioModal" id="btnAgregarProductoObsequio" data-toggle="tooltip" data-placement="bottom" title="Agregar producto para obsequio"><i class="fas fa-plus"></i> Productos para obsequio</button>
                                        <button type="button" class="btn btn-xs btn-primary activation handleClickAgregarServicio" id="btnAgregarServicio" data-toggle="tooltip" data-placement="bottom" title="Agregar servicio"><i class="fas fa-plus"></i> Servicio</button>
                                        <button type="button" class="btn btn-xs btn-default activation handleClickVincularRequerimientoAOrdenModalOLD" onClick="openVincularRequerimientoConOrden();" id="btnAgregarVinculoRequerimiento" data-toggle="tooltip" data-placement="bottom" title="Agregar items de otro requerimiento" disabled><i class="fas fa-plus"></i> Vincular otro requerimiento
                                        </button>
                                    </div>
                                    <div class="box box-widget">
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="mytable table table-hover table-condensed table-bordered table-okc-view dataTable no-footer" name="listaDetalleOrden[]" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%">Req.</th>
                                                            <th style="width: 5%">Cod. producto</th>
                                                            <th style="width: 5%">Cod. softlink</th>
                                                            <th style="width: 5%">Part number</th>
                                                            <th>Descripción del producto/servicio</th>
                                                            <th style="width: 8%">Unid. Med.</th>
                                                            <th style="width: 5%">Cantidad solicitada</th>
                                                            <th style="width: 5%">Cantidad Reservada</th>
                                                            <th style="width: 5%">Cantidad atendida por orden</th>
                                                            <th style="width: 8%">Cantidad a comprar</th>
                                                            <th style="width: 10%">Precio Unitario</th>
                                                            <th style="width: 6%">Total</th>
                                                            <th style="width: 5%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody name="body_detalle_orden[]"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="11" class="text-right"><strong>Monto neto:</strong></td>
                                                            <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="montoNeto[]"> 0.00</label></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11" class="text-right">
                                                                <input class="activation handleClickIncluyeIGV" type="checkbox" name="incluye_igv[]" checked> <strong>Incluye IGV</strong>
                                                            </td>
                                                            <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="igv[]"> 0.00</label></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11" class="text-right">
                                                                <input class="activation handleClickIncluyeICBPER" type="checkbox" name="incluye_icbper[]"> <strong>Incluye ICBPER</strong>
                                                            </td>
                                                            <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="icbper[]"> 0.00</label></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11" class="text-right"><strong>Monto total:</strong></td>
                                                            <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="montoTotal[]"> 0.00</label></td>
                                                            <td></td>
                                                        </tr>

                                                    </tfoot>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>
            </div>
        </div>
</div>
<br>


<div class="form-inline">
    <div class="checkbox" id="check-guarda_en_requerimiento" style="display:none">
        <label>
            <input type="checkbox" name="guardarEnRequerimiento"> Guardar nuevos items en requerimiento?
        </label>
    </div>
</div>


</form>
</div>

<div class="hidden" id="divOculto">
    <select id="selectUnidadMedida">
        @foreach ($unidades_medida as $unidad)
        <option value="{{$unidad->id_unidad_medida}}" {{$unidad->id_unidad_medida=='1' ? 'selected' : ''}}>{{$unidad->abreviatura}}</option>
        @endforeach
    </select>
</div>
<div class="hidden">
    <h5>Empresa - Sede</h5>
    <select name="selectEmpresa">
        @foreach ($empresas as $empresa)
        @if($empresa->id_empresa ==1)
        <option value="{{$empresa->id_empresa}}" data-codigo-empresa="{{$empresa->codigo}}" selected>{{$empresa->razon_social}}</option>
        @else
        <option value="{{$empresa->id_empresa}}" data-codigo-empresa="{{$empresa->codigo}}">{{$empresa->razon_social}}</option>
        @endif
        @endforeach
    </select>
</div>
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_lista_oc_softlink')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_estado_cuadro_presupuesto')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.vincularRequerimientoConOrdenModal')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.listaItemsRequerimientoParaVincularModal')
@include('logistica.gestion_logistica.compras.pendientes.modal_ver_orden_de_requerimiento')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_catalogo_items')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_ordenes_elaboradas')
@include('logistica.gestion_logistica.proveedores.modal_cuentas_bancarias_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_agregar_cuenta_bancaria_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_lista_proveedores')
@include('logistica.cotizaciones.add_proveedor')
@include('publico.ubigeoModal')
@include('logistica.gestion_logistica.proveedores.modal_contacto_proveedor')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_trabajadores')

@include('logistica.gestion_logistica.compras.pendientes.modal_ver_cuadro_costos')
@include('logistica.requerimientos.modal_vincular_item_requerimiento') <!--revisar uso -->
@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>


<script src="{{('/js/logistica/orden/OrdenView.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenView.js'))}}"></script>
<script src="{{('/js/logistica/orden/OrdenController.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenController.js'))}}"></script>
<script src="{{('/js/logistica/orden/OrdenModel.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenModel.js'))}}"></script>



<script>
    $(document).ready(function() {
        $(".sidebar-mini").addClass("sidebar-collapse");
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue'
        });
    });
    
    window.onload = function() {

        const ordenModel = new OrdenModel();
        const ordenController = new OrdenCtrl(ordenModel);
        const ordenView = new OrdenView(ordenController);
        ordenView.init();7      
    };
</script>

<!-- <script src="{{('/js/logistica/proveedores/listaProveedoresModal.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/listaProveedoresModal.js'))}}"></script>
<script src="{{('/js/logistica/proveedores/cuentasBancariasProveedor.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/cuentasBancariasProveedor.js'))}}"></script>
<script src="{{('/js/logistica/add_proveedor.js')}}?v={{filemtime(public_path('/js/logistica/add_proveedor.js'))}}"></script>
<script src="{{ asset('js/publico/ubigeoModal.js')}}?v={{filemtime(public_path('js/publico/ubigeoModal.js'))}}"></script>
<script src="{{('/js/logistica/proveedores/proveedorContactoModal.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/proveedorContactoModal.js'))}}"></script>
<script src="{{('/js/logistica/orden/trabajadorModal.js')}}?v={{filemtime(public_path('/js/logistica/orden/trabajadorModal.js'))}}"></script>
<script src="{{ asset('js/publico/consulta_sunat.js')}}?v={{filemtime(public_path('js/publico/consulta_sunat.js'))}}"></script> -->
<!-- <script src="{{('/js/logistica/orden/relacionarOcSoftlink.js')}}?v={{filemtime(public_path('/js/logistica/orden/relacionarOcSoftlink.js'))}}"></script> -->
<!-- <script src="{{('/js/logistica/orden/vincularRequerimientoConOrdenModal.js')}}?v={{filemtime(public_path('/js/logistica/orden/vincularRequerimientoConOrdenModal.js'))}}"></script> -->


 
@endsection