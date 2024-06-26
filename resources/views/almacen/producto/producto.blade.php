
@extends('themes.base')

@section('cabecera') Producto @endsection
@include('layouts.menu_almacen')

@if(Auth::user()->tieneAccion(69))
    @section('option')
        @include('layouts.option')
    @endsection
@elseif(Auth::user()->tieneAccion(70))
    @section('option')
        @include('layouts.option_historial')
    @endsection
@endif

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
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
    <li>Catálogo</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
@if (sizeof($array_accesos_botonera)!==0)
<div class="box box-solid">
    <div class="box-body">
        <div class="page-main" type="producto">
            <!-- <legend class="mylegend">
                <h2>Producto</h2>

            </legend> -->
            <div class="col-md-12" id="tab-producto" style="padding-right:0px;padding-left:0px">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a type="#general">Datos Generales</a></li>
                    <li class=""><a type="#promocion">Promociones</a></li>
                    <li class=""><a type="#ubicacion">Ubicaciones</a></li>
                    <li class=""><a type="#serie">Control de Series</a></li>
                </ul>
                <div class="content-tabs">
                    <section id="general" hidden>
                        <form id="form-general" type="register">
                            <!--form="formulario"-->
                            <input class="hidden" name="id_producto" primary="ids">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                            <!-- <input type="text" class="oculto" name="id_producto"> -->
                            <input type="text" class="oculto" name="codigo">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <img id="img" src="{{ asset('images/product-default.png')}}">
                                            </div>
                                            <div class="row">
                                                <input type="file" name="imagen" id="imagen" class="filestyle form-control" data-buttonName="btn-primary"
                                                data-buttonText="Seleccionar imagen" data-size="sm" data-iconName="fa fa-folder-open">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="padding-right: 0px; padding-left:0px;">

                                            <fieldset class="group-importes" style="margin-top: 0px">
                                                <legend>
                                                    <h6>Unidad Equivalente</h6>
                                                </legend>
                                                <table id="unidad" class="table-group">
                                                    <tbody>
                                                        <tr>
                                                            <td>Cantidad x Presentación:</td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control activation" name="cant_pres" disabled="true" />
                                                                    <span class="input-group-addon" name="abr_id_unid_equi"></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Unidad Equivalente:</td>
                                                            <td>
                                                                <select class="form-control activation " style="font-size:12px;" name="id_unid_equi" disabled="true" onChange="unid_abrev('id_unid_equi');">
                                                                    <option value="0">Elija una opción</option>
                                                                    @foreach ($unidades as $unid)
                                                                    <option value="{{$unid->id_unidad_medida}}">{{$unid->descripcion}} - {{$unid->abreviatura}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row" style="margin-bottom: 0;margin-top: 0;">
                                        <div class="col-md-12">
                                            <ol class="breadcrumb" style="padding-left: 20px;padding-right: 10px;margin-bottom:0px">
                                                <li>Agile: <label id="codigo"></label></li>
                                                <li>Softlink: <label id="codigo_softlink"></label></li>
                                                <li><label id="tipo_descripcion"></label></li>
                                                <li><label id="cat_descripcion"></label></li>
                                                <li><label id="subcat_descripcion"></label></li>
                                                <li><span id="estado" class="label "></span></li>
                                                <li>
                                                    <button type="button" class="btn btn-success btn-sm btn-flat" onClick="migrarProductoSoftlink();"
                                                        data-toggle="tooltip" data-placement="bottom" title="Migrar producto a Softlink2" >
                                                        Migrar a Softlink <i class="fas fa-step-forward"></i> </button>
                                                </li>
                                            </ol>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <h5>Clasificación</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_clasif" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                {{-- 
                                                @foreach ($clasificaciones as $clasif)
                                                <option value="{{$clasif->id_clasificacion}}">{{$clasif->descripcion}}</option>
                                                @endforeach 
                                                --}}
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Categoría</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_tipo_producto" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                {{-- @foreach ($tipos as $tp)
                                                <option value="{{$tp->id_tipo_producto}}">{{$tp->descripcion}}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>SubCategoría</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_categoria" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                {{-- @foreach ($categorias as $cat)
                                                <option value="{{$cat->id_categoria}}">{{$cat->descripcion}}</option>
                                                @endforeach --}}
                                            </select>
                                        </div> -->
                                        <div class="col-md-4">
                                            <h5>Grupo</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_grupo_sap" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($grupos as $grupo)
                                                <option value="{{$grupo->id}}">{{$grupo->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Categoría</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_categoria_sap" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($categorias as $categoria)
                                                <option value="{{$categoria->id}}">{{$categoria->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>SubCategoría</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_subcategoria_sap" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($subcategorias as $subcategoria)
                                                <option value="{{$subcategoria->id}}">{{$subcategoria->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-4">
                                            <h5>Marca</h5>
                                            <select class="form-control activation js-example-basic-single" name="id_subcategoria" disabled="true">
                                                <option value="0">Elija una opción</option>
                                                {{-- @foreach ($subcategorias as $subcat)
                                                <option value="{{$subcat->id_subcategoria}}">{{$subcat->descripcion}}</option>
                                                @endforeach --}}
                                            </select>
                                        </div> -->
                                        <div class="col-md-4">
                                            <h5>Part Number</h5>
                                            <input type="text" class="form-control activation" name="part_number" disabled="true">
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Unidad Medida</h5>
                                            <select class="form-control activation " name="id_unidad_medida" disabled="true" onChange="unid_abrev('id_unidad_medida');">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($unidades as $unid)
                                                <option value="{{$unid->id_unidad_medida}}">{{$unid->abreviatura}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 0;">
                                        <div class="col-md-12">
                                            <h5>Descripción</h5>
                                            <textarea name="descripcion" class="form-control activation" rows="5" cols="10" onkeyup="mayus(this);"></textarea>
                                            <!-- <input type="text" class="form-control activation" name="descripcion" onkeyup="mayus(this);" disabled="true"> -->
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 0;">
                                        <!-- <div class="col-md-4">
                                            <h5>Código Anexo</h5>
                                            <input type="text" class="form-control activation" name="codigo_anexo" disabled="true">
                                        </div>               -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>SUNAT UNSPSC</h5>
                                            <input type="text" class="form-control activation" name="sunat_unsps" disabled="true">
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Código compuesto</h5>
                                            <input type="text" class="form-control activation" name="codigo_compuesto" disabled="true">
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Peso (Kg.)</h5>
                                            <input type="number" class="form-control activation" name="peso" disabled="true">
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Largo (cm.)</h5>
                                            <input type="number" class="form-control activation" name="largo" disabled="true">
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Ancho (cm.)</h5>
                                            <input type="number" class="form-control activation" name="ancho" disabled="true">
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Alto (cm.)</h5>
                                            <input type="number" class="form-control activation" name="alto" disabled="true">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h5>Moneda</h5>
                                            <select class="form-control group-elemento activation" name="id_moneda" required>
                                                <option value='' selected>Elija una opción</option>
                                                @foreach ($monedas as $mon)
                                                <option value="{{$mon->id_moneda}}">{{$mon->descripcion}}</option>
                                                @endforeach
                                            </select>

                                            <h5></h5>
                                            <div class="icheckbox_flat-blue">
                                                <label style="display:flex;">
                                                    <input type="checkbox" class="flat-red activation" name="series" value="0">
                                                </label>
                                            </div> Control de Series

                                            <h5>Creado por:</h5>
                                            <label id="usuario_registro"></label>
                                            <label id="fecha_registro"></label>
                                        </div>
                                        <div class="col-md-8">
                                            <h5>Especificaciones del Producto</h5>
                                            <textarea name="notas" class="form-control activation" rows="8" cols="30"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                    <section id="promocion" hidden>
                        <form id="form-promocion" type="register">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" id="listaPromocion">
                                        <thead>
                                            <tr>
                                                <td hidden></td>
                                                <td>Producto</td>
                                                <td>Producto Promocionado</td>
                                                <td>Fecha Registro</td>
                                                <td>Estado</td>
                                                <td>Usuario Registro</td>
                                                <td>Acción</td>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </section>
                    <section id="ubicacion" hidden>
                        <form id="form-ubicacion" type="register">
                            <input type="hidden" name="id_producto">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" id="listaUbicacion">
                                        <thead>
                                            <tr>
                                                <td hidden></td>
                                                <td>Cod.Almacén</td>
                                                <td>Almacén</td>
                                                <td>Posición</td>
                                                <td>Stock</td>
                                                <td>Costo Promedio</td>
                                                <td>Valorización</td>
                                                <!-- <td>Estado</td> -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </section>
                    <section id="serie" hidden>
                        <form id="form-serie" type="register">
                            <input type="hidden" name="id_producto">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" id="listaSerie">
                                        <thead>
                                            <tr>
                                                <td hidden></td>
                                                <td>Almacén</td>
                                                <td>Serie</td>
                                                <td>Guía Compra</td>
                                                <td>Guía Venta</td>
                                                <td>Fecha Registro</td>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </section>
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
@include('almacen.producto.subcategoriaModal')
@include('almacen.producto.productoModal')
@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>

    
    <!-- <script src="{{ asset('js/almacen/producto/producto.js')}}?v={{filemtime(public_path('js/almacen/producto/producto.js'))}}"></script> --> <!-- * antigua clasificacion -->
    <script src="{{ asset('js/almacen/producto/producto_sap.js')}}?v={{filemtime(public_path('js/almacen/producto/producto_sap.js'))}}"></script><!-- * lasificacion sap -->
    
    <script src="{{ asset('js/almacen/producto/productoModal.js')}}?v={{filemtime(public_path('js/almacen/producto/productoModal.js'))}}"></script>
    <script src="{{ asset('js/almacen/producto/producto_ubicacion.js')}}?v={{filemtime(public_path('js/almacen/producto/producto_ubicacion.js'))}}"></script>
    <!-- <script src="{{ asset('js/almacen/producto/subcategoriaModal.js')}}"></script> -->
    <script src="{{ asset('js/almacen/producto/producto_serie.js')}}?v={{filemtime(public_path('js/almacen/producto/producto_serie.js'))}}"></script>
    <script>
        $(document).ready(function() {
            
            $("#imagen").filestyle();
        });
    </script>
@endsection
