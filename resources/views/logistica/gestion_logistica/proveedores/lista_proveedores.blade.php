@extends('themes.base')
@include('layouts.menu_logistica')


@section('cabecera')
Lista de proveedores
@endsection
@section('estilos')
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
    <li>Proveedores</li>
    <li class="active">Lista proveedores</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="lista_proveedores">
    @if (in_array(257,$array_accesos) || in_array(254,$array_accesos) || in_array(255,$array_accesos) || in_array(256,$array_accesos))
    <div class="row">
        <div class="col-md-12">
            <fieldset class="group-table">
                <div id="form-listaProveedores">
                    <table class="mytable table table-hover table-condensed table-striped table-bordered table-okc-view" id="listaProveedores" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">Tipo doc.</th>
                                <th class="text-center" style="width:5%">Doc. identidad</th>
                                <th class="text-center" style="width:20%">Razon social</th>
                                <th class="text-center" style="width:10%">Tipo empresa</th>
                                <th class="text-center" style="width:8%">País</th>
                                <th class="text-center" style="width:10%">Ubigeo</th>
                                <th class="text-center" style="width:20%">Dirección</th>
                                <th class="text-center" style="width:8%">Teléfono</th>
                                <th class="text-center" style="width:8%">Estado</th>
                                <th class="text-center" style="width:8%">Acción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger pulse" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error de Accesos:</span>
                Solicite los accesos
            </div>
        </div>
    </div>
    @endif

</div>


@include('logistica.gestion_logistica.proveedores.modal_ver_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_agregar_cuenta_bancaria')
@include('logistica.gestion_logistica.proveedores.modal_agregar_adjunto_proveedor')
@include('logistica.gestion_logistica.proveedores.modal_agregar_contacto')
@include('logistica.gestion_logistica.proveedores.modal_agregar_establecimiento')
@include('publico.ubigeoModal')


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

<script src="{{('/js/logistica/proveedores/listarProveedorView.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/listarProveedorView.js'))}}"></script>
<script src="{{('/js/logistica/proveedores/ProveedorController.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/ProveedorController.js'))}}"></script>
<script src="{{('/js/logistica/proveedores/ProveedorModel.js')}}?v={{filemtime(public_path('/js/logistica/proveedores/ProveedorModel.js'))}}"></script>
<script src="{{ asset('js/publico/ubigeoModal.js')}}?v={{filemtime(public_path('js/publico/ubigeoModal.js'))}}"></script>



<script>
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    $(document).ready(function() {
        

        const proveedorModel = new ProveedorModel();
        const proveedorController = new ProveedorCtrl(proveedorModel);
        const listarProveedorView = new ListarProveedorView(proveedorController);

        listarProveedorView.mostrar();
        listarProveedorView.initializeEventHandler();

    });
</script>
@endsection