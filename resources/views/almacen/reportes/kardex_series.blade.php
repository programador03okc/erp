

@extends('themes.base')

@section('titulo') Búsqueda sensitiva de Series @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
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
    <li>Reportes</li>
    <li class="active">@yield('cabecera')</li>
  </ol>
@endsection

@section('cuerpo')
<div class="page-main" type="kardex_series">
    @if (in_array(173,$array_accesos))
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <div class="row">
                    <div class="col-md-2">
                        <h5>Serie</h5>
                        <input type="text" class="form-control" name="serie" placeholder="Ingrese un Nro. de Serie..."/>
                    </div>
                    <div class="col-md-2">
                        <h5>Código</h5>
                        <input type="text" class="form-control" name="codigo" placeholder="Ingrese un Código..."/>
                    </div>
                    <div class="col-md-2">
                        <h5>Part-Number</h5>
                        <input type="text" class="form-control" name="part_number" placeholder="Ingrese un Part Number..."/>
                    </div>
                    <div class="col-md-6">
                        <h5>Descripción</h5>
                        <div class="input-group-okc">
                            <input class="oculto" name="id_producto"/>
                            <input type="text" class="form-control" placeholder="Ingrese la descripción de un producto..."
                                aria-describedby="basic-addon2" name="descripcion"/>
                        </div>
                    </div>
                </div>
                @if (in_array(173,$array_accesos))
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip"
                            data-placement="bottom" title="Generar Kardex"
                            onClick="listarKardexSeries();">Actualizar Kardex</button>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-bordered table-okc-view"
                            id="listaKardexSeries">
                            <thead>
                                <tr>
                                    <th hidden></th>
                                    <th>Serie</th>
                                    <th>Código</th>
                                    <th>Part Number</th>
                                    <th width="50%">Descripción</th>
                                    <th>Almacén</th>
                                </tr>
                            </thead>
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
</div>
@include('almacen.reportes.modalKardexSerie')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/almacen/reporte/kardex_series.js')}}?v={{filemtime(public_path('js/almacen/reporte/kardex_series.js'))}}"></script>

    <script>
    $(document).ready(function(){
        Util.seleccionarMenu(window.location);
    });
    </script>
@endsection
{{-- ------------------- --}}
