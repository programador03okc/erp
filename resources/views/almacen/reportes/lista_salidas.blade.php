
@extends('themes.base')

@section('cabecera') Lista de Salidas @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
    <style>
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
        .dataTables_scrollBody thead tr[role="row"]{
    visibility: collapse !important;
}
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('almacen.index')}}"><i class="fas fa-tachometer-alt"></i> Almacenes</a></li>
    <li>Reportes</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="lista_salidas">
    @if (in_array(164,$array_accesos) || in_array(165,$array_accesos) )
    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <div class="col-md-12">
                    <table class="mytable table table-condensed table-bordered table-okc-view"
                        id="listaSalidas">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th hidden></th>
                                <th></th>
                                <th>Fecha Emisión</th>
                                <th>Cod.Sal</th>
                                <th>Fecha Guía</th>
                                <th>Guía</th>
                                <th>Fecha Doc</th>
                                <th>Tp</th>
                                <th>Serie-Número</th>
                                <th>RUC</th>
                                <th width="500px">Razon Social</th>
                                <th>Mn</th>
                                <th>Valor Neto</th>
                                <th>IGV</th>
                                <th>Total</th>
                                <th>Saldo</th>
                                <th>Condicion</th>
                                <th>Días</th>
                                <th>Operación</th>
                                <th>Fecha Vcmto</th>
                                <th>Responsable</th>
                                <th>T.Cambio</th>
                                <th>Almacén</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="checkbox" name="no_revisado" onClick="search();" style="width:30px;"/>
                                </td>
                                <td><label>No Revisado(s)</label></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="revisado" onClick="search();" style="width:30px;"/>
                                </td>
                                <td><label>Revisado(s)</label></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="observado" onClick="search();" style="width:30px;"/>
                                </td>
                                <td><label>Observado(s)</label></td>
                            </tr>
                        </tbody>
                    </table>
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

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-filtros">
    <div class="modal-dialog">
        <div class="modal-content" style="width:600px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Filtros de Salidas de Almacén</h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" id="formFiltroReporteIngresos">
                    <div class="form-group">
                        <div class="col-md-12">
                            <small>Seleccione los filtros que desee aplicar y cierre este cuadro para continuar</small>
                        </div>
                    </div>
                    <div class="container-filter" style="margin: 0 auto;">

                        <fieldset class="group-table">

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Empresa">
                                            <input type="checkbox" name="chkEmpresa"> Empresa
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleChangeFiltroEmpresa handleUpdateValorFiltro" name="empresa" readOnly>
                                        @foreach ($empresas as $emp)
                                        <option value="{{$emp->id_empresa}}">{{$emp->razon_social}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Sede">
                                            <input type="checkbox" name="chkSede"> Sede
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleUpdateValorFiltro" name="sede" readOnly>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Almacén">
                                            <input type="checkbox" name="chkAlmacen"> Almacén
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control selectpicker input-sm handleUpdateValorFiltro" multiple data-actions-box="true" name="almacen" readOnly>
                                        @foreach ($almacenes as $alm)
                                        <option value="{{$alm->id_almacen}}">{{$alm->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Condición">
                                            <input type="checkbox" name="chkCondicion"> Condición
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                <select class="form-control selectpicker input-sm" multiple data-actions-box="true" name="condicion" readOnly>
                                    @foreach ($tp_operacion as $alm)
                                    <option value="{{$alm->id_operacion}}">{{$alm->cod_sunat}} - {{$alm->descripcion}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Fecha de creación">
                                            <input type="checkbox" name="chkFechaRegistro"> Fecha creación
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-4">
                                    <input type="date" name="fecha_inicio" class="form-control input-sm handleUpdateValorFiltro" readOnly>
                                    <small class="help-block">Desde (dd-mm-aaaa)</small>
                                </div>
                                <div class="col-sm-4">
                                    <input type="date" name="fecha_fin" class="form-control input-sm handleUpdateValorFiltro" readOnly>
                                    <small class="help-block">Hasta (dd-mm-aaaa)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Cliente">
                                            <input type="checkbox" name="chkCliente"> Cliente
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <div style="display:flex;">
                                        <input class="oculto" name="id_cliente" />
                                        <input class="oculto" name="id_contrib" />
                                        <input type="text" class="form-control input-sm" name="cliente_razon_social" placeholder="Seleccione un cliente..." aria-describedby="basic-addon1" readOnly>
                                        <button type="button" class="input-group-text btn-primary btn-xs" id="basic-addon1" onClick="clienteModal();">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="input-group-text btn-danger btn-xs" id="basic-addon1" onClick="limpiar_cliente();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Moneda">
                                            <input type="checkbox" name="chkMoneda"> Moneda
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm" name="moneda" readOnly>
                                        <option value="0">Elija una opción</option>
                                        <option value="1">Docs en Soles (S/)</option>
                                        <option value="2">Docs en Dólares (US$)</option>
                                        <option value="3">Docs en Soles y Dólares</option>
                                        <option value="4">Convertir Docs en Soles</option>
                                        <option value="5">Convertir Docs en Dólares</option>
                                    </select>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <label id="mid_doc_com" style="display: none;"></label>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('logistica.cotizaciones.clienteModal')
@include('logistica.cotizaciones.transportistaModal')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/almacen/reporte/lista_salidas.js')}}"></script>
    <script src="{{ asset('js/almacen/reporte/filtros.js')}}"></script>
    <script src="{{ asset('js/logistica/clienteModal.js')}}"></script>
    <script src="{{ asset('js/logistica/transportistaModal.js')}}"></script>
    <script>
        var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function(){
        
    });
    </script>


@endsection

{{-- ------------------------------------------ --}}
