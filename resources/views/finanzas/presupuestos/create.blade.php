
@extends('themes.base')

@section('cabecera') Orden de Transformación @endsection
@include('layouts.menu_finanzas')
@section('estilos')
{{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script> --}}

    <style>
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
    .lbl-codigo:hover{
        color:#007bff !important;
        cursor:pointer;
    }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><i class="fa fa-usd"></i> Finanzas </li>
    <li class="active"> @yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Datos Generales</h3>
        <div class="box-tools pull-right">
            <div class="btn-group" role="group">
                <button data-toggle="modal" title="Nuevo Presupuesto"
                    class="btn btn-sm btn-success nuevo-presupuesto">
                    <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
                </button>
                <button data-toggle="modal" title="Editar Datos Generales"
                    class="btn btn-sm btn-warning editar-presupuesto">
                    <i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>
                </button>
                <button type="button" data-toggle="modal" data-target="#presupuestosModal"
                    title="Buscar Presupuesto" class="btn btn-sm btn-info">
                    <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                </button>
                <!-- <a target="_blank" href="#" title="Imprimir" class="btn">
                    <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                </a> -->
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">

                <input style="display: none" name="id_presup"/>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-1 control-label">Código:</label>
                        <div class="col-md-2">
                            <div class="form-control-static" name="codigo"></div>
                        </div>
                        <label class="col-md-1 control-label">Tipo:</label>
                        <div class="col-md-2">
                            <div class="form-control-static" name="name_tipo"></div>
                        </div>
                        <label class="col-md-1 control-label">Empresa:</label>
                        <div class="col-md-2">
                            <div class="form-control-static" name="name_empresa"></div>
                        </div>
                        <label class="col-md-1 control-label">Fecha Em.:</label>
                        <div class="col-md-2">
                            <div class="form-control-static" name="fecha_emision"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-1 control-label">Moneda:</label>
                        <div class="col-md-2">
                            <div class="form-control-static" name="name_moneda"></div>
                        </div>
                        <label class="col-md-1 control-label">Descripción:</label>
                        <div class="col-md-8">
                            <div class="form-control-static" name="descripcion"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-header with-border">
        <div class="col-md-12" id="tab-partidas">

            <ul class="nav nav-tabs" id="myTabPartidas">
                <li class="active"><a data-toggle="tab" href="#partidas">Detalle </a></li>
                <li class=""><a data-toggle="tab" href="#gastos">Gastos por partidas</a></li>
            </ul>
            <div class="tab-content">
                <div id="partidas" class="tab-pane fade in active">

                    {{-- <div class="row" >
                        <div class="col-md-12"> --}}

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Partidas</h3>
                                    <div class="box-tools pull-right">
                                        <div class="btn-group" role="group">
                                            <button data-toggle="tooltip" data-placement="bottom" title="Nuevo Título"
                                                class="btn btn-success btn-sm nuevo-titulo">
                                                <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-sm table-hover table-bordered dt-responsive nowrap" id="listaPartidas">
                                                <thead style="background: gainsboro;">
                                                    <tr>
                                                        <th>Codigo</th>
                                                        <th>Descripción</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {{-- </div>
                    </div> --}}

                </div>
                <div id="gastos" class="tab-pane fade ">

                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cuadro de Gastos</h3>
                            <div class="box-tools pull-right">
                                <div class="btn-group" role="group">
                                    <button data-toggle="tooltip" data-placement="bottom" title="Exportar a excel"
                                        class="btn btn-success btn-sm exportar" style="color:#fff !important;" onClick="exportarCuadroCostos()">
                                        <i class="fas fa-file-excel"></i> Exportar a excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-hover table-bordered dt-responsive nowrap"
                                    id="listaGastosPartidas" style="font-size: 13px">
                                        <thead style="background: gainsboro;">
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Fecha pago</th>
                                                <th>Cod.Req.</th>
                                                {{-- <th>OC/OS</th> --}}
                                                <th>Titulo</th>
                                                <th>Partida</th>
                                                {{-- <th>Proveedor o persona asignada</th> --}}
                                                <th>Descripción</th>
                                                <th>Cant.</th>
                                                <th>Unid.</th>
                                                {{-- <th>Mnd.</th> --}}
                                                <th>P. Unitario</th>
                                                <th>SubTotal</th>
                                                <th>I.G.V.</th>
                                                <th>P. Compra</th>
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

        </div>
    </div>
</div>

@include('finanzas.presupuestos.presupuestoCreate')
@include('finanzas.presupuestos.presupuestosModal')
@include('finanzas.presupuestos.partidaCreate')
@include('finanzas.presupuestos.tituloCreate')

@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            
        });
    </script>

    <script src="{{('/js/finanzas/presupuestos/presupuesto.js')}}"></script>
    <script src="{{('/js/finanzas/presupuestos/titulo.js')}}"></script>
    <script src="{{('/js/finanzas/presupuestos/partida.js')}}"></script>
    <script src="{{('/js/finanzas/presupuestos/detalle.js')}}"></script>
    <script src="{{('/js/finanzas/presupuestos/cuadroGastos.js')}}"></script>

@endsection


{{-- ----------------------- --}}

