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

    div .inner.open{
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
        <input type="hidden" name="tipo_cambio_compra">
        <input class="oculto" name="monto_subtotal">
        <input class="oculto" name="monto_igv">
        <input class="oculto" name="monto_total">




        <div class="row">
            <div class="col-md-12">
                <h4 style="display:flex;justify-content: space-between;">
                    <div>
                        <button type="button" name="btn-nuevo" class="btn btn-default btn-sm" title="Nuevo"><i class="fas fa-file"></i> Nuevo</button>
                        <button type="button" name="btn-editar" class="btn btn-default btn-sm" title="Editar"><i class="fas fa-edit"></i> Editar</button>
                        <button type="button" name="btn-guardar" class="btn btn-success btn-sm" title="Guardar"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" name="btn-nuevo" class="btn btn-default btn-sm" title="Vincular requerimiento"><i class="fas fa-file-prescription"></i> Vincular Requerimiento</button>
                        <button type="button" name="btn-historial" class="btn btn-default btn-sm" title="Historial"><i class="fas fa-folder"></i> Historial</button>
                        <button type="button" name="btn-migrar-orden-softlink" class="btn btn-default btn-sm handleClickMigrarOrdenASoftlink" title="Migrar orden a softlink" disabled><i class="fas fa-file-export"></i> Migrar Orden a soflink</button>


                    </div>
                    <div>




                    </div>
                </h4>
            </div>
        </div>

        <div class="row">
       
            <div class="col-md-12">
                <fieldset class="group-table">
                    <div id="contenedor_orden"></div>

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
@include('logistica.requerimientos.modal_vincular_item_requerimiento') revisar uso -->
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