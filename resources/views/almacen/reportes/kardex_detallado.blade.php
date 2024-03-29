
@extends('themes.base')

@section('cabecera') Kardex por Producto @endsection
@include('layouts.menu_almacen')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
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

<div class="page-main" type="kardex_detallado">


    @if (in_array(172,$array_accesos) || in_array(171,$array_accesos) )
    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">
                @if (in_array(171,$array_accesos))
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group-okc">
                            <input class="oculto" name="id_producto">
                            <input type="text" class="form-control" readonly
                                placeholder="Seleccione un producto..." aria-describedby="basic-addon2" name="descripcion">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" id="basic-addon2" onClick="productoModal();">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <h5>Empresas</h5>
                        <select class="form-control js-example-basic-single" name="id_empresa" >
                            <option value="0">Elija una opción</option>
                            @foreach ($empresas as $alm)
                                <option value="{{$alm->id_empresa}}">{{$alm->razon_social}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <h5>Almacén</h5>
                        <select class="form-control js-example-basic-single" name="almacen">
                            {{-- @foreach ($almacenes as $alm)
                                <option value="{{$alm->id_almacen}}">{{$alm->descripcion}}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <h5>Rango de Fechas</h5>
                        <div class="input-group">
                            <span class="input-group-addon"> Desde: </span>
                            <input type="date" class="form-control" name="fecha_inicio">
                            <span class="input-group-addon"> Hasta: </span>
                            <input type="date" class="form-control" name="fecha_fin">
                        </div>
                    </div>
                </div>
                @if (in_array(172,$array_accesos))
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip"
                            data-placement="bottom" title="Generar Kardex"
                            onClick="generar_kardex();">Actualizar Kardex</button>
                        {{-- <button type="submit" class="btn btn-success" data-toggle="tooltip"
                            data-placement="bottom" title="Exportar Kardex"
                            onClick="download_kardex_excel();">Excel</button> --}}
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <table id ="datos_producto" class="table-group">
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-bordered table-okc-view" id="kardex_producto">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Movimiento</th>
                                    <th>Documento</th>
                                    <th>Factura</th>
                                    <th>Proveedor</th>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Saldo</th>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Valorizacion</th>
                                    <th>Costo unitario</th>
                                    <th>Ope</th>
                                    <th>Tipo de Operacion</th>
                                    <th>Transferencia</th>
                                    <th>Transformación</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <th colSpan="5"></th>
                                <th class="right"><label name="suma_ing_cant"></label></th>
                                <th class="right"><label name="suma_sal_cant"></label></th>
                                <th></th>
                                <th class="right"><label name="suma_ing_val"></label></th>
                                <th class="right"><label name="suma_sal_val"></label></th>
                                <th colSpan="5"></th>
                            </tfoot>
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
@include('almacen.producto.productoModal')

@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/almacen/reporte/kardex_detallado.js')}}"></script>
    <script src="{{ asset('js/almacen/producto/productoModal.js')}}"></script>
    <script>
    $(document).ready(function(){
        
    });
    </script>

@endsection
{{-- ---------------------------- --}}
