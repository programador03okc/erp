@extends('themes.base')
@include('layouts.menu_finanzas')

@section('cabecera')
Normalizar
@endsection

@section('estilos')
{{-- <link rel="stylesheet" href="{{asset('template/plugins/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}"> --}}
<style>
    .contenido-detalle{
        height: 0;
        transition: .3s height;
    }
</style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{route('finanzas.index')}}"><i class="fa fa-usd"></i> Finanzas</a></li>
        <li class="active"> @yield('cabecera')</li>
    </ol>
@endsection

@section('cuerpo')
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Normalizar Requerimientos de pago/Ordenes</h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="" data-form="buscar">
                        @csrf
                        {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Meses</label>
                                    <select class="form-control" name="mes" required>
                                        <option value="01" selected>Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                    </select>
                                </div>
                            </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Divisiones</label>
                                <select class="form-control" name="division" required>
                                    @foreach ($division as $key=>$item)
                                    @if ($key===0)
                                    <option value="{{$item->id_division}}" selected>{{$item->descripcion}}</option>
                                    @else
                                    <option value="{{$item->id_division}}">{{$item->descripcion}}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Tipo de pago</label>
                                <select class="form-control" name="tipo_pago" required>
                                    <option value="1" selected>Sin saldo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Requerimientos de Pagos</a></li>
                <li><a href="#tab_2" data-toggle="tab">Ordenes</a></li>
                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <table width="100%" class="table table-bordered table-hover dataTable" id="lista-requerimientos-pagos">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th>Código</th>
                                <th>Concepto</th>
                                <th>Fecha Aprobación</th>
                                <th>Creado por</th>
                                <th>Monto total</th>
                                <th>Saldo <small>(Tesorería)</small></th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <table class="table table-bordered table-hover dataTable" id="lista-ordenes" width="100%">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th>Código</th>
                                <th>Req.</th>
                                <th>Fecha de emisión</th>
                                <th>Importe total orden</th>
                                <th>Total pagado <small>(Tesorería)</small></th>
                                <th>Saldo <small>(Tesorería)</small></th>
                                <th>Estado de pago <small>(Tesorería)</small></th>
                                <th>N° de Cuotas</th>
                                <th>Estado Cuota</th>
                                <th>Comentario del Pago <small>(Logística)</small></th>
                                <th>Tipo Impuesto</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>


<!-- Modal -->
<!-- <div class="modal fade" id="normalizar-definir-criterio-para-saldo" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Normalizar - Definir para a Saldo</h5>
            </div>
            <div class="modal-body">
                <p>Elgir una opción</p>

                <div class="list-group">
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> Saldo pendiente de pago</h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto a las partidas de cada item, se ignorará el saldo, afectando solamente el monto pagado sin aplicar impuestos. </p>
                    </a>
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> Orden con impuesto por detracción sin cuotas </h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto a las partidas de cada item, se  marcará a la orden que tiene impuesto por detracción y el saldo considerado como detracción, el presupuesto ejecutado se incluirá la detracción</p>
                    </a>
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> Orden con Impuesto por detracción y con cuotas</h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto a las partidas de cada item, se  marcará a la orden que tiene impuesto por detracción y el saldo considerado como impuesto en cada cuota, el presupuesto ejecutado se incluirá la detracción</p>
                    </a>
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> Orden con impuesto a la renta y sin cuotas</h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto a las partidas de cada item, se  marcará a la orden que tiene impuesto por renta y el saldo considerado como impuesto, el presupuesto ejecutado se incluirá la renta</p>
                    </a>
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> Orden con impuesto a la renta y con cuotas</h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto a las partidas de cada item, se  marcará a la orden que tiene impuesto por renta y el saldo considerado como impuesto en cada cuota, el presupuesto ejecutado se incluirá la renta</p>
                    </a>
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading"> <input type="radio" name="optionsRadioSaldo" value="0"> El Saldo no aplicable a pago. (ignorar)</h4>
                        <p class="list-group-item-text">Acción: Se afectará el presupuesto de cada item con los monto sin considerar el saldo </p>
                    </a>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success">Grabar</button>
            </div>
        </div>
    </div>
</div> -->
<!-- Modal -->
<div class="modal fade" id="normalizar-partida" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Normalizar Requerimientos de pago / Partidas de Presupuesto Interno</h5>

            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>


{{-- modal de requerimiento de pago  --}}

<div class="modal fade" tabindex="-1" role="dialog" id="modal-vista-rapida-requerimiento-pago" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Detalle del requerimiento pago</h3>
            </div>
            <div class="modal-body">
                <div id="botonera-accion"></div>
                <input type="hidden" name="id_requerimiento_pago">
                <input type="hidden" name="id_estado">
                <input type="hidden" name="id_usuario">
                <fieldset class="group-importes">
                    <legend>Datos generales</legend>
                    <div class="box box-widget">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table" border="0" id="tablaDatosGenerales">
                                    <tbody>
                                        <tr>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Código</td>
                                            <td id="codigo" style="width:10%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Motivo</td>
                                            <td id="concepto" style="width:auto;" colspan="2"></td>
                                            <td></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Empresa</td>
                                            <td id="razon_social_empresa" style="width:20%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Grupo/División</td>
                                            <td id="grupo_division" style="width:10%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Periodo</td>
                                            <td id="periodo" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Tipo Req.</td>
                                            <td id="tipo_requerimiento" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Prioridad</td>
                                            <td id="prioridad" style="width:10%;"></td>
                                            <td style="width:14%; font-weight:bold; text-align:right;">Fecha Registro</td>
                                            <td id="fecha_registro" style="width:10%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Creado por</td>
                                            <td id="creado_por" style="width:10%;"></td>

                                        </tr>
                                        <tr>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Solicitado por</td>
                                            <td id="solicitado_por" style="width:10%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Comentario</td>
                                            <td id="comentario" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Tipo Impuesto</td>
                                            <td id="tipo_impuesto" style="width:5%;"></td>
                                            <td style="width:10%; font-weight:bold; text-align:right;">Archivos adjuntos</td>
                                            <td id='adjuntosRequerimientoPago'>-</td>
                                            <td></td>
                                        </tr>
                                        <tr class="oculto" id="contenedor_presupuesto_old">
                                            <td style="width:5%; font-weight:bold; text-align:right;">Presupuesto <em>(Proy)</em></td>
                                            <td id="presupuesto_old" style="width:5%;" colspan="7"></td>
                                        </tr>
                                        <tr class="oculto" id="contenedor_presupuesto_interno">
                                            <td style="width:5%; font-weight:bold; text-align:right;">Presupuesto <em>(Interno)</em></td>
                                            <td id="presupuesto_interno" style="width:5%;" colspan="7"></td>
                                        </tr>
                                        <tr class="oculto" id="contenedor_cdp">
                                            <td style="width:5%; font-weight:bold; text-align:right;">CDP</td>
                                            <td id="codigo_cdp" style="width:5%;"></td>
                                        </tr>
                                        <tr class="oculto" id="contenedor_proyecto">
                                            <td style="width:5%; font-weight:bold; text-align:right;">Proyecto</td>
                                            <td id="proyecto_presupuesto" style="width:5%;" colspan="6"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="group-importes">
                    <legend>Datos destinatario de pago</legend>
                    <div class="box box-widget">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table" border="0" id="tablaDatosDestinatario">
                                    <tbody>
                                        <tr>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Tipo Destinatario</td>
                                            <td id="tipo_destinatario" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Nombre destinatario</td>
                                            <td id="destinatario" style="width:10%;"></td>
                                            <td id="tipo_documento_destinatario" style="width:5%; font-weight:bold; text-align:right;">Doc.</td>
                                            <td id="nro_documento_destinatario" style="width:5%;"></td>
                                            <td style="width:10%; font-weight:bold; text-align:right;">Banco</td>
                                            <td id="banco" style="width:10%;"></td>


                                        </tr>
                                        <tr>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Tipo Cuenta</td>
                                            <td id='tipo_cuenta' style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Moneda</td>
                                            <td id="moneda" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">Nro Cuenta</td>
                                            <td id="nro_cuenta" style="width:5%;"></td>
                                            <td style="width:5%; font-weight:bold; text-align:right;">CCI</td>
                                            <td id="nro_cci" style="width:5%;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <br>
                <fieldset class="group-importes">
                    <legend>
                        Items de requerimiento de pago
                    </legend>
                    <div class="box box-widget">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed table-bordered" id="listaDetalleRequerimientoPago">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%">#</th>
                                            <th style="width: 10%">Partida</th>
                                            <th style="width: 10%">C.Costo</th>
                                            <th style="width: 30%">Descripción de item</th>
                                            <th style="width: 5%">Unidad</th>
                                            <th style="width: 5%">Cantidad</th>
                                            <th style="width: 8%">Precio U. <span name="simboloMoneda">S/</span></th>
                                            <th style="width: 8%">Subtotal</th>
                                            <th style="width: 10%">Motivo</th>
                                            <th style="width: 10%">Estado</th>
                                            <th style="width: 2%">Adjuntos</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body_requerimiento_pago_detalle_vista">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-right"><strong>Monto total:</strong></td>
                                            <td class="text-right"><span name="simbolo_moneda">S/</span><label name="total"> 0.00</label></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </fieldset>

                     <br>
                    <div class="row">
                        <div class="col-md-7">
                            <!-- <h4 style="display:flex;justify-content: space-between;"></h4> -->
                            <fieldset class="group-importes">
                            <legend>
                                Partidas activas
                            </legend>
                                <div class="box box-widget">
                                    <div class="box-body">
                                        <table class="table table-striped table-bordered" id="listaPartidasActivas" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="10">Codigo</th>
                                                    <th width="70">Descripción</th>
                                                    <th width="10" style="background-color: #ddeafb;">Presupuesto Total</th>
                                                    <th width="10" style="background-color: #ddeafb;">Presupuesto Mes <small>(actual)</small></th>
                                                    <th width="10" style="background-color: #fbdddd;">Presupuesto Utilizado</th>
                                                    <th width="10" style="background-color: #e5fbdd;">Saldo Total</th>
                                                    <th width="10" style="background-color: #e5fbdd;">Saldo Mes</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body_partidas_activas">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-5">
                            <!-- <h4 style="display:flex;justify-content: space-between;"></h4> -->
                            <fieldset class="group-importes">
                            <legend>
                                Historial de revisiones/aprobaciones
                            </legend>
                                <div class="box box-widget">
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="listaHistorialRevision">
                                                <thead>
                                                    <tr>
                                                        <th>Revisado por</th>
                                                        <th>Acción</th>
                                                        <th>Comentario</th>
                                                        <th>Fecha revisión</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body_requerimiento_pago_historial_revision"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" class="close" data-dismiss="modal">Cerrar</button>
            </div>
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
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
{{-- <script src="{{ asset('template/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script> --}}
{{-- <script src="{{ asset('template/plugins/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}"></script> --}}
{{-- <script src="{{asset('template/plugins/select2/select2.min.js')}}"></script> --}}

    <script>
        // console.log(ruta);
    </script>
    <script src="{{asset('js/normalizar/lista-normalizar.js')}}"></script>


@endsection
