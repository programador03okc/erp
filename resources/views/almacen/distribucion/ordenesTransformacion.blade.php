@extends('themes.base')
@include('layouts.menu_logistica')

@section('cabecera')
Envío de Transformaciones
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Distribución</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="listaOrdenesDespacho">
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <div class="row">
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-bordered table-okc-view" id="requerimientosEnProceso">
                            <thead>
                                <tr>
                                    <th hidden></th>
                                    <th width="8%">Cod.Req.</th>
                                    <th>Fecha Entrega</th>
                                    <th>Orden Elec.</th>
                                    <th>Cod.CP</th>
                                    <th>Cliente/Entidad</th>
                                    <th>Generado por</th>
                                    <th>Sede Req.</th>
                                    <th>Estado</th>
                                    <th>Transf.</th>
                                    <th width="60px">Acción</th>
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
@include('almacen.distribucion.transferenciasDetalle')
@include('almacen.distribucion.ordenDespachoInternoCreate')
@include('almacen.distribucion.ordenDespachoTransformacion')
@include('tesoreria.facturacion.archivos_oc_mgcp')
@include('almacen.transferencias.ver_series')

@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('js/almacen/distribucion/ordenesTransformacion.js')}}"></script>
<script src="{{ asset('js/almacen/distribucion/verDetalleRequerimiento.js')}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoInternoCreate.js')}}"></script>
<script src="{{ asset('js/almacen/distribucion/ordenDespachoTransformacion.js')}}"></script>
<script src="{{ asset('js/tesoreria/facturacion/archivosMgcp.js')}}"></script>

<script>
    $(document).ready(function() {
        
        listarRequerimientosPendientes();
    });
</script>
@endsection
