@extends('themes.base')

@section('cabecera') Kardex General @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
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
        button.botones {
            margin-top: 35px;
        }
        table {
            font-size: smaller;
        }
        table.table-bordered.dataTable tbody td {
            vertical-align: middle;
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
<div class="page-main" type="kardex_general">
    @if (in_array(168,$array_accesos)||in_array(169,$array_accesos)||in_array(170,$array_accesos))
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <div class="row" style="padding-left:0px;padding-right:0px;">
                    <div class="col-md-12">
                        @if (in_array(168,$array_accesos))
                        <button type="submit" class="btn btn-success" data-toggle="tooltip"
                            data-placement="bottom" title="Descargar Kardex Sunat"
                            onClick="exportar();"> <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                        @endif
                        @if (in_array(169,$array_accesos))
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                            data-placement="bottom" title="Descargar Kardex Sunat"
                            onClick="downloadKardexSunat();"> <i class="fas fa-download"></i> Kardex Sunat
                        </button>
                        @endif
                        @if (in_array(170,$array_accesos))
                        <button type="button" class="btn btn-default" data-toggle="tooltip"
                            data-placement="bottom" title="Ingrese los filtros"
                            onClick="open_filtros();"> <i class="fas fa-filter"></i> Filtros
                        </button>@endif
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="mytable table table-condensed table-bordered table-okc-view" id="kardexGeneral">
                                <thead>
                                    <tr>
                                        <th hidden></th>
                                        <th>Código</th>
                                        <th>Part Number</th>
                                        <th>Categoría</th>
                                        <th>SubCategoría</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Almacén</th>
                                        <th>Und</th>
                                        <th>Ing.</th>
                                        <th>Sal.</th>
                                        <th>Saldo</th>
                                        <th>Ing.</th>
                                        <th>Sal.</th>
                                        <th>Valoriz.</th>
                                        <th>Cod.Mov.</th>
                                        <th>Op</th>
                                        <th>Movimiento</th>
                                        <th>Guía </th>
                                        <th>Transf.</th>
                                        <th>O.C.</th>
                                        <th>Fact.</th>
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
@include('almacen.reportes.kardex_filtro')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>



    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    {{-- <script src="{{ asset('template/plugins/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}"></script> --}}

    <script src="{{ asset('js/almacen/reporte/kardex_general.js')}}"></script>
    <script>
    $(document).ready(function(){
        
    });
    </script>
@endsection
{{-- --------------------------------------------- --}}
