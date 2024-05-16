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

    dd {
        padding-bottom: 10px;
    }

    dl {
        margin-bottom: 0px;
    }

    .input-xs,
    .btn.dropdown-toggle.btn-default {
        height: 24px;
        font-size: 9px;
    }

    div .inner.open {
        max-width: 300px;
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
        <input type="hidden" name="tipo_cambio">




        <div class="row">
            <div class="col-md-12">
                <h4 style="display:flex;justify-content: space-between;">
                    <div>
                        <button type="button" name="btn-nuevo" class="btn btn-primary btn-sm handleClickCrearNuevaOrden" title="Nuevo"><i class="fas fa-file"></i> Crear nueva Orden</button>
                        <button type="button" name="btn-guardar" class="btn btn-success btn-sm handleClickGuardarOrden" title="Guardar"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" name="btn-migrar-orden-softlink" class="btn btn-warning btn-sm handleClickMigrarOrdenASoftlink" title="Migrar a softlink" disabled><i class="fas fa-file-export"></i> Migrar Orden a soflink</button>
                    </div>
                    <div>
                        <h5><span class="label label-info">Tipo cambio: <span name="tipo_cambio">0</span></span></h5>



                    </div>
                </h4>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <fieldset class="group-table">
                    <div id="contenedor_orden" style=" display: flex; flex-direction: row; flex-wrap: wrap; gap: 0.5rem;">
                        <div class="panel panel-default" style="width: auto; max-width:100%">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Lista de Ordenes
                                </div>
                            </div>
                            <div class="panel-body" style="overflow:auto; white-space:nowrap; padding-bottom:0px;">
                                <ul class="list-inline" id="contenedor_lista_ordenes">
                                </ul>
                            </div>
                        </div>
                        <br>


                        <div class="panel panel-info" style="flex:auto;">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Encabezado
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-2">
                                        <ul class="nav nav-pills nav-stacked" role="tablist">
                                            <li role="presentation" class="active"><a href="#seccionDetalle" aria-controls="seccionDetalle" role="tab" data-toggle="tab">Detalle documento</a></li>
                                            <li role="presentation"><a href="#seccionProveedor" aria-controls="seccionProveedor" role="tab" data-toggle="tab">Proveedor</a></li>
                                            <li role="presentation"><a href="#seccionCondicionCompra" aria-controls="seccionCondicionCompra" role="tab" data-toggle="tab">Condicion de compra</a></li>
                                            <li role="presentation"><a href="#seccionDespacho" aria-controls="seccionDespacho" role="tab" data-toggle="tab">Despacho</a></li>
                                        </ul>
                                    </div>

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
                                                                    <select class="form-control input-xs handleChangeUpdateTipoOrden" name="id_tipo_orden">
                                                                        @foreach ($tp_documento as $tp)
                                                                        @if($tp->descripcion == 'Orden de Compra')
                                                                        <option value="{{$tp->id_tp_documento}}" selected>{{$tp->descripcion}}</option>
                                                                        @else
                                                                        @if((!in_array(Auth::user()->id_usuario,[17,27,3,1,77]) && $tp->id_tp_documento == 13))
                                                                        @else
                                                                        <option value="{{$tp->id_tp_documento}}">{{$tp->descripcion}}</option>
                                                                        @endif
                                                                        @endif
                                                                        @endforeach

                                                                    </select>
                                                                </dd>
                                                                <dt>Periodo</dt>
                                                                <dd>
                                                                    <select class="form-control input-xs handleChangeUpdatePeriodo" name="id_periodo">
                                                                        @foreach ($periodos as $periodo)
                                                                        <option value="{{$periodo->id_periodo}}">{{$periodo->descripcion}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>

                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Código</dt>
                                                                <dd>
                                                                    <p class="form-control-static" name="codigo_orden_compra">(Debe crear o abrir una orden)</p>
                                                                </dd>
                                                                <dt>Cod.Softlink</dt>
                                                                <dd>
                                                                    <p class="form-control-static" name="codigo_softlink">(Debe migrar la OC/OS)</p>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Moneda</dt>
                                                                <dd>
                                                                    <select class="form-control input-xs handleChangeUpdateMoneda" name="id_moneda">
                                                                        @foreach ($tp_moneda as $tpm)
                                                                        <option value="{{$tpm->id_moneda}}" data-simbolo-moneda="{{$tpm->simbolo}}">{{$tpm->descripcion}} ( {{$tpm->simbolo}} )</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                                <dt>Fecha Emisión</dt>
                                                                <dd>
                                                                    <input class="form-control input-xs handleChangeUpdateFechaEmision" name="fecha_emision" type="datetime-local" value="2024-03-19T11:23">
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Empresa / Sede</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs handleChangeSede handleChangeUpdateSede " name="id_sede" title="Seleccionar empresa - sede" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($sedes as $sede)
                                                                        <option value="{{$sede->id_sede}}" data-descripcion-sede="{{$sede->codigo}}" data-id-empresa="{{$sede->id_empresa}}" data-descripcion-empresa="{{$sede->razon_social_empresa}}" data-direccion="{{$sede->direccion}}" data-id-ubigeo="{{$sede->id_ubigeo}}" data-ubigeo-descripcion="{{$sede->ubigeo_descripcion}}">{{$sede->descripcion}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                                <dt>
                                                                <dd><img id="logo_empresa" src="/images/img-wide.png" alt="" style="height:60px !important; width:100% !important;"></dd>
                                                                </dt>
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
                                                                <dt>RUC - Razón social</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs onChangeSeleccionarProveedor handleChangeUpdateProveedor" name="id_proveedor" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($proveedores as $proveedor)
                                                                        <option value="{{$proveedor->id_proveedor}}" data-id-contribuyente="{{$proveedor->id_contribuyente}}" data-razon-social="{{$proveedor->contribuyente->razon_social}}" data-numero-documento="{{$proveedor->contribuyente->nro_documento}}">{{$proveedor->contribuyente->nro_documento!=null?$proveedor->contribuyente->nro_documento.' - ':''}} {{$proveedor->contribuyente->razon_social}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                                <dt>Contacto</dt>
                                                                <dd>
                                                                    <select class="form-control input-xs seleccionarDatoCabeceraConcatoProveedor handleChangeUpdateContactoProveedor" name="id_contacto_proveedor">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                    </select>
                                                                </dd>


                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Dirección</dt>
                                                                <dd>
                                                                    <p class="form-control-static" name="direccion_proveedor">(seleccione un proveedor)</p>
                                                                </dd>
                                                                <dt>Telefono contacto</dt>
                                                                <dd>
                                                                    <p class="form-control-static" name="telefono_contacto">(seleccione un concacto)</p>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Cuenta Bancaria</dt>
                                                                <dd>
                                                                    <div style="display:flex;">
                                                                        <select class="form-control input-xs handleChangeUpdateCuentaBancariaProveedor" name="id_cuenta_bancaria_proveedor">
                                                                            <option value="" disabled>Elija una opción</option>
                                                                        </select>
                                                                        <button type="button" class="btn-primary agregarCuentaProveedor" title="Agregar cuenta bancaria"><i class="fas fa-plus"></i></button>

                                                                    </div>
                                                                </dd>
                                                                <dt>Rubro</dt>
                                                                <dd>
                                                                    <select class="selectpicker handleChangeUpdateRubroProveedor" title="Elija una opción" data-width="100%" data-container="body" data-live-search="true" name="id_rubro_proveedor">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($rubros as $rubro)
                                                                        <option value="{{$rubro->id_rubro}}">{{$rubro->descripcion}}</option>
                                                                        @endforeach
                                                                    </select>
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
                                                                    <select class="form-control input-xs handleChangeFormaPago handleChangeUpdateFormaPago" name="id_condicion_softlink">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($condiciones_softlink as $cond)
                                                                        <option value="{{$cond->id_condicion_softlink}}" data-dias="{{$cond->dias}}">{{$cond->descripcion}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div style="display:none;">
                                                                        <select class="form-control group-elemento activation" name="id_condicion" style="width:100%; text-align:center;">
                                                                            @foreach ($condiciones as $cond)
                                                                            <option value="{{$cond->id_condicion_pago}}">{{$cond->descripcion}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Plazo entrega</dt>
                                                                <dd>
                                                                    <div style="display:flex;">
                                                                        <input type="number" name="plazo_entrega" min="0" class="form-control input-xs handleKeyUpUpdatePlazoEntrega" style="text-align:right;">
                                                                        <input type="text" value="días" class="form-control group-elemento input-xs" style="text-align:center;" readonly="">
                                                                    </div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Requerimiento</dt>
                                                                <dd>
                                                                    <p class="form-control-static" name="requerimiento_vinculados">(Sin vinculo con requerimiento)</p>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Tipo Documento</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs handleChangeUpdateTipoDocumento" name="id_tipo_documento" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($tp_doc as $tp)
                                                                        @if($tp->descripcion == 'Factura')
                                                                        <option value="{{$tp->id_tp_doc}}" selected>{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                                                                        @else
                                                                        <option value="{{$tp->id_tp_doc}}">{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                                                                        @endif
                                                                        @endforeach
                                                                    </select>
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
                                                                    <input class="form-control input-xs handleChangeKeyUpDireccionEntrega" name="direccion_entrega" type="text">
                                                                </dd>
                                                                <dt>Compra locales</dt>
                                                                <dd>
                                                                    <input class="handleChangeUpdateCompraLocal" type="checkbox" name="compra_local"> Compras locales
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Ubigeo entrega</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs handleChangeUpdateUbigeoEntrega" name="id_ubigeo_destino" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($ubigeos as $ubigeo)
                                                                        <option value="{{$ubigeo->id_dis}}">{{$ubigeo->codigo}} - {{$ubigeo->descripcion}} - {{$ubigeo->provincia}} - {{$ubigeo->departamento}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                                <dt>Observación</dt>
                                                                <dd>
                                                                    <textarea class="form-control input-xs handleKeyUpUpdateObservacion" name="observacion" cols="50" rows="100" style="height:50px;"></textarea>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Personal autorizado #1</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs handleChangePersonalAutorizado1" name="id_trabajador_persona_autorizado_1" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($trabajadores as $trabajador)
                                                                        <option value="{{$trabajador->id_trabajador}}">{{$trabajador->nombre_trabajador}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <dl class="">
                                                                <dt>Personal autorizado #2</dt>
                                                                <dd>
                                                                    <select class="form-control selectpicker input-xs handleChangePersonalAutorizado2" name="id_trabajador_persona_autorizado_2" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                                        <option value="" disabled>Elija una opción</option>
                                                                        @foreach ($trabajadores as $trabajador)
                                                                        <option value="{{$trabajador->id_trabajador}}">{{$trabajador->nombre_trabajador}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-info" style="flex:auto;">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Detalle
                                </div>
                            </div>
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" class="btn btn-xs btn-default handleClickAbrirCatalogoProductos" id="btnAgregarProducto" data-toggle="tooltip" data-placement="bottom" title="Agregar producto"><i class="fas fa-plus"></i> Productos</button>
                                            <button type="button" class="btn btn-xs btn-default handleClickAgregarServicio" id="btnAgregarServicio" data-toggle="tooltip" data-placement="bottom" title="Agregar servicio"><i class="fas fa-plus"></i> Servicio</button>
                                        </div>
                                        <div class="box box-widget">
                                            <div class="box-body">
                                                <div class="table-responsive">
                                                    <table class="mytable table table-hover table-condensed table-bordered table-okc-view dataTable no-footer" name="listaDetalleOrden" width="100%">
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
                                                        <tbody name="body_detalle_orden"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="11" class="text-right"><strong>Monto neto:</strong></td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span><label name="montoNeto">
                                                                        0.00</label></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="11" class="text-right">
                                                                    <input class="handleClickIncluyeIGV handleChangeUpdateIncluyeIGV" type="checkbox" name="incluye_igv" checked> <strong>Incluye IGV</strong>
                                                                </td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span><label name="igv">
                                                                        0.00</label></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="11" class="text-right">
                                                                    <input class="handleClickIncluyeICBPER handleChangeUpdateIncluyeICBPER" type="checkbox" name="incluye_icbper"> <strong>Incluye ICBPER</strong>
                                                                </td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span><label name="icbper">
                                                                        0.00</label></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="11" class="text-right"><strong>Monto total:</strong></td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span><label name="montoTotal">
                                                                        0.00</label></td>
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



<div class="modal fade" tabindex="-1" role="dialog" id="modal-lista-requerimientos-pendientes">
    <div class="modal-dialog modal-lg" style="width: 140rem;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Lista de Requerimientos Pendientes</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-striped table-hover table-bordered table-okc-view" id="tablaRequerimientosPendientes" width="100%" style="font-size: 11px;">
                            <thead>
                                <tr>
                                    <th hidden>Id</th>
                                    <th style="text-align:center;">Prio.</th>
                                    <th style="text-align:center;">Empresa - Sede</th>
                                    <th style="text-align:center; width:10%;">Código</th>
                                    <th style="text-align:center;">Fecha creación</th>
                                    <th style="text-align:center;">Fecha limite</th>
                                    <th style="text-align:center;">Concepto</th>
                                    <th style="text-align:center;">Tipo Req.</th>
                                    <th style="text-align:center;">División</th>
                                    <th style="text-align:center;">Solicitado por</th>
                                    <th style="text-align:center;">Req. creado por</th>
                                    <th style="text-align:center;">Observación</th>
                                    <th style="text-align:center;">Estado</th>
                                    <th style="text-align:center;">Tipo Item</th>
                                    <th style="text-align:center;width:7%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" class="close" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_catalogo_items')

@include('logistica.gestion_logistica.proveedores.modal_cuentas_bancarias_proveedor')


<!-- @include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_lista_oc_softlink')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_estado_cuadro_presupuesto')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.vincularRequerimientoConOrdenModal')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.listaItemsRequerimientoParaVincularModal')
@include('logistica.gestion_logistica.compras.pendientes.modal_ver_orden_de_requerimiento')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_catalogo_items')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_ordenes_elaboradas')
@include('logistica.gestion_logistica.proveedores.modal_agregar_cuenta_bancaria_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_lista_proveedores')
@include('logistica.cotizaciones.add_proveedor')
@include('publico.ubigeoModal')
@include('logistica.gestion_logistica.proveedores.modal_contacto_proveedor')
@include('logistica.gestion_logistica.compras.ordenes.elaborar.modal_trabajadores')

@include('logistica.gestion_logistica.compras.pendientes.modal_ver_cuadro_costos')
@include('logistica.requerimientos.modal_vincular_item_requerimiento') 
-->
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


<script src="{{('/js/logistica/orden/OrdenMultipleView.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenMultipleView.js'))}}"></script>
<script src="{{('/js/logistica/orden/OrdenMultipleController.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenMultipleController.js'))}}"></script>
<script src="{{('/js/logistica/orden/OrdenMultipleModel.js')}}?v={{filemtime(public_path('/js/logistica/orden/OrdenMultipleModel.js'))}}"></script>



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
        ordenView.init();
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