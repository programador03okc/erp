@extends('themes.base')
@include('layouts.menu_tesoreria')

@section('cabecera')
Registro de pagos
@endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{route('tesoreria.index')}}"><i class="fas fa-tachometer-alt"></i> Tesorería</a></li>
  <li>Pagos</li>
  <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="requerimientoPagos">

    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">
                @if (Auth::user()->id_usuario == 3)
                <button id="btn_cerrar" class="btn btn-default" onClick="actualizarEstadoPago();">Actualizar estado con saldo</button>
                @endif
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a data-toggle="tab" href="#requerimientos">Requerimiento de pagos</a></li>
                    <li class=""><a data-toggle="tab" href="#ordenes">Ordenes Compra/Servicio</a></li>
                </ul>

                <div class="tab-content">

                    <div id="requerimientos" class="tab-pane fade in active">
                        {{-- <a class="btn btn-success" href="reistro-pagos-exportar-excel" >Exportar a Excel</a> --}}
                        <br>
                        <form id="form-requerimientos" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view"
                                        id="listaRequerimientos">
                                        <thead>
                                            <tr>
                                                <th hidden>#</th>
                                                <th>Prio.</th>
                                                <th>Emp.</th>
                                                <th>Código</th>
                                                {{-- <th>Grupo</th> --}}
                                                <th>Concepto</th>
                                                <th>Elaborado por</th>
                                                <th>Destinatario</th>
                                                <th>Fecha Emisión</th>
                                                <th>Mnd</th>
                                                <th>Total</th>
                                                <th>Saldo</th>
                                                <th>Estado</th>
                                                <th>Autorizado por</th>
                                                <th style="width:80px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 14px;"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <h3>Cuadro Informativo de los Requerimientos Pagados</h3>
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view table-hover text-center"
                                        id="cuadro-pagos">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Empresas</th>
                                                <th colspan="{{sizeof($estados)}}">Estados</th>
                                                <th colspan="{{sizeof($moneda)}}">Monedas</th>
                                            </tr>
                                            <tr>
                                                @foreach ($estados as $item)
                                                <th data-th="{{$item->id_requerimiento_pago_estado}}">{{$item->descripcion}}</th>
                                                @endforeach
                                                @foreach ($moneda as $item)
                                                <th data-th="{{$item->id_moneda}}">{{$item->descripcion}} ({{$item->simbolo}})</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="ordenes" class="tab-pane fade ">
                        {{-- <a class="btn btn-success" href="ordenes-compra-servicio-exportar-excel" >Exportar a Excel</a> --}}
                        <form id="form-ordenes" type="register">
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view"
                                        id="listaOrdenes">
                                        <thead>
                                            <tr>
                                                <th hidden></th>
                                                <th>Prio.</th>
                                                <th>Cod.Req.</th>
                                                <th>Emp.</th>
                                                <th>Codigo</th>
                                                {{-- <th>Codigo SoftLink</th> --}}
                                                {{-- <th>Nro. Doc.</th> --}}
                                                <th>Razon social del proveedor</th>
                                                <th>Fecha envío a pago</th>
                                                {{-- <th>Forma de Pago</th> --}}
                                                <th>Mnd</th>
                                                <th>Total Orden</th>
                                                <th>Saldo</th>
                                                <th>Valor cuota</th>
                                                <th>Estado</th>
                                                <th>Autorizado por</th>
                                                <th style="width:80px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 14px;"></tbody>
                                    </table>
                                </div>
                            </div>

                        </form>
                        <div class="row">
                            <h3>Cuadro Informativo de Ordenes Compra/Servicio</h3>
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view table-hover text-center"
                                    id="cuadro-ordenes">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Empresas</th>
                                            <th colspan="{{sizeof($estados)}}">Estados</th>
                                            <th colspan="{{sizeof($moneda)}}">Monedas</th>
                                        </tr>
                                        <tr>
                                            @foreach ($estados as $item)
                                            <th data-th="{{$item->id_requerimiento_pago_estado}}">{{$item->descripcion}}</th>
                                            @endforeach
                                            @foreach ($moneda as $item)
                                            <th data-th="{{$item->id_moneda}}">{{$item->descripcion}} ({{$item->simbolo}})</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- <div id="comprobantes" class="tab-pane fade ">
                        <br>
                        <form id="form-comprobantes" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view"
                                        id="listaComprobantes">
                                        <thead>
                                            <tr>
                                                <th hidden>#</th>
                                                <th>Tipo Doc.</th>
                                                <th>Serie</th>
                                                <th>Número</th>
                                                <th>Razon social del proveedor</th>
                                                <th>Fecha Emisión</th>
                                                <th>Condición</th>
                                                <th>Fecha Vencimiento</th>
                                                <th>Cta. Bancaria</th>
                                                <th>Mnd</th>
                                                <th>Total</th>
                                                <th>Saldo</th>
                                                <th>Estado</th>
                                                <th style="width:80px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div> --}}

                </div>

            </div>
        </div>
    </div>
</div>

{{-- #modal de filtrosss --}}
<div class="modal fade" id="modal-filtros">
    <div class="modal-dialog" style="width: 500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Filtros Requerimientos de Pago</h4>
        </div>
        <div class="modal-body">
            <div class="row" data-section="prioridad">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click" name="estado" value="prioridad"> Prioridad
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select" name="prioridad" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($prioridad as $item)
                            <option value="{{$item->id_prioridad}}">{{$item->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="empresa">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click" name="empresa" value="empresa"> Empresa
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select" name="empresa" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($empresas as $item)
                            <option value="{{$item->id_empresa}}">[{{$item->nro_documento}}] - {{$item->razon_social}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="estado">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click" name="proveedor" value="estado"> Estado
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select" name="estado" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($estados as $item)
                            <option value="{{$item->id_requerimiento_pago_estado}}"> {{$item->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="fechas">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click" name="persona" value="fechas"> Fechas
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group text-center">
                        <input type="date" class="form-control" data-action="select" name="fecha_inicio" value="" disabled>
                        <small id="" class="text-muted">Fecha de inicio</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group text-center">
                        <input type="date" class="form-control" data-action="select" name="fecha_final" value="" disabled>
                        <small id="" class="text-muted">Fecha final</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> --}}
          <button type="button" class="btn btn-primary"  data-dismiss="modal">Aplicar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{{-- #modal filtros de servicios compra --}}
<div class="modal fade" id="modal-filtros-ordenes-compra-servicio">
    <div class="modal-dialog" style="width: 500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Filtros Ordenes Compra/Servicio</h4>
        </div>
        <div class="modal-body">
            <div class="row" data-section="prioridad">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click-ordenes" name="estado" value="prioridad"> Prioridad
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select-orden" name="prioridad" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($prioridad as $item)
                            <option value="{{$item->id_prioridad}}">{{$item->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="empresa">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click-ordenes" name="empresa" value="empresa"> Empresa
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select-orden" name="empresa" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($empresas as $item)
                            <option value="{{$item->id_empresa}}">[{{$item->nro_documento}}] - {{$item->razon_social}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="estado">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click-ordenes" name="proveedor" value="estado"> Estado
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <select class="form-control" data-action="select-orden" name="estado" disabled>
                            <option value="" hidden>Seleccione...</option>
                            @foreach ($estados as $item)
                            <option value="{{$item->id_requerimiento_pago_estado}}"> {{$item->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" data-section="fechas">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-action="click-ordenes" name="persona" value="fechas"> Fechas
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group text-center">
                        <input type="date" class="form-control" data-action="select-orden" name="fecha_inicio" value="" disabled>
                        <small id="" class="text-muted">Fecha de inicio</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group text-center">
                        <input type="date" class="form-control" data-action="select-orden" name="fecha_final" value="" disabled>
                        <small id="" class="text-muted">Fecha final</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> --}}
          <button type="button" class="btn btn-primary"  data-dismiss="modal">Aplicar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@include('tesoreria.pagos.procesarPago')
@include('tesoreria.pagos.verAdjuntos')
@include('tesoreria.pagos.verAdjuntosPago')
@include('tesoreria.requerimiento_pago.modal_vista_rapida_requerimiento_pago')
@include('logistica.reportes.modal_lista_adjuntos')
@include('logistica.requerimientos.modal_requerimientos_vinculados_con_partida')
@include('tesoreria.requerimiento_pago.modal_ver_adjuntos_requerimiento_pago_cabecera')
@include('tesoreria.requerimiento_pago.modal_ver_adjuntos_requerimiento_pago_detalle')
@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> -->
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>



    <script>
                let auth_137 = '{{Auth::user()->tieneAccion(137)}}', auth_138 ='{{Auth::user()->tieneAccion(138)}}',auth_139 ='{{Auth::user()->tieneAccion(139)}}';
    </script>
    <script src="{{ asset('js/tesoreria/pagos/pendientesPago.js')}}?v={{filemtime(public_path('js/tesoreria/pagos/pendientesPago.js'))}}"></script>
    <script src="{{ asset('js/tesoreria/pagos/procesarPago.js')}}?v={{filemtime(public_path('js/tesoreria/pagos/procesarPago.js'))}}"></script>
    <script src="{{ asset('js/logistica/reportes/modalAdjuntosLogisticos.js')}}?v={{filemtime(public_path('js/logistica/reportes/modalAdjuntosLogisticos.js'))}}"></script>
    <script src="{{ asset('js/tesoreria/requerimientoPago/ListarRequerimientoPagoView.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/ListarRequerimientoPagoView.js'))}}"></script> 

    <script src="{{ asset('js/tesoreria/pagos/modalVistaRapidaRequerimiento.js')}}?v={{filemtime(public_path('js/tesoreria/pagos/modalVistaRapidaRequerimiento.js'))}}"></script>

    <script src="{{ asset('js/Tesoreria/Pagos/cuadro-comparativo.js')}}"></script>
    <script src="{{ asset('js/logistica/requerimiento/verRequerimientosVinculadosConPartida.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/verRequerimientosVinculadosConPartida.js'))}}"></script>

    <script>
    $(document).ready(function(){

        vista_extendida();
        iniciar();

        let requerimientoPago=new RequerimientoPago('{{Auth::user()->tieneAccion(137)}}','{{Auth::user()->tieneAccion(138)}}','{{Auth::user()->tieneAccion(139)}}');
        // let requerimientoPago=new RequerimientoPago('1','1','1');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            let tab = $(e.target).attr("href") // activated tab

            if (tab=='#ordenes'){
                $('#listaOrdenes').DataTable().ajax.reload();
            }
            else if (tab=='#comprobantes'){
                $('#listaComprobantes').DataTable().ajax.reload();
            }
            else if (tab=='#requerimientos'){
                $('#listaRequerimientos').DataTable().ajax.reload();
            }
         });
    });
    </script>
@endsection
