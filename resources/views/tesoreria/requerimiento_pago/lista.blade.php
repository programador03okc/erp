@extends('themes.base')
@include('layouts.menu_necesidades')

@section('option')
@endsection

@section('cabecera')
Listado de requerimientos de pago
@endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">

@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('necesidades.index')}}"><i class="fas fa-tachometer-alt"></i> Necesidades</a></li>
    <li>Requerimientos de pago</li>
    <li class="active">Listado</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="lista_requerimiento_pago">

    @if (in_array(13,$array_accesos) || in_array(20,$array_accesos) || in_array(30,$array_accesos) || in_array(21,$array_accesos) || in_array(31,$array_accesos) || in_array(22,$array_accesos))
        <div class="row">
            <div class="col-md-12">
                <fieldset class="group-table">
                    <div class="box box-widget">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="mytable table table-hover table-condensed table-bordered table-okc-view" id="ListaRequerimientoPago" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center" style="width:2%">Prio.</th>
                                            <th class="text-center" style="width:8%">Código</th>
                                            <th class="text-center" style="width:20%">Concepto</th>
                                            <th class="text-center" style="width:15%">Tipo Req.</th>
                                            <th class="text-center" style="width:8%">Fecha registro</th>
                                            <th class="text-center" style="width:10%">Empresa - sede</th>
                                            <th class="text-center">Grupo</th>
                                            <th class="text-center">División</th>
                                            <th class="text-center">Proyecto</th>
                                            <th class="text-center">Presupuesto Interno</th>
                                            <th class="text-center">Monto Total</th>
                                            <th class="text-center">Creado por</th>
                                            <th class="text-center" style="width:5%;">Estado</th>
                                            <th class="text-center" style="width:10%">Acción</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
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


<div class="hidden" id="divOculto">
    <select id="selectUnidadMedida" onchange="updateUM(this);">
        @foreach ($unidadesMedida as $unidad)
        <option value="{{$unidad->id_unidad_medida}}">{{$unidad->descripcion}}</option>
        @endforeach
    </select>
</div>

@include('tesoreria.requerimiento_pago.modal_vista_rapida_requerimiento_pago')
@include('tesoreria.requerimiento_pago.modal_requerimiento_pago')
@include('tesoreria.requerimiento_pago.modal_lista_cuadro_presupuesto')

@include('logistica.requerimientos.modal_partidas')
@include('logistica.requerimientos.modal_centro_costos')
@include('logistica.requerimientos.modal_filtros_listado de requerimientos de pago')

    @include('tesoreria.requerimiento_pago.modal_adjuntar_archivos_requerimiento_pago')
@include('tesoreria.requerimiento_pago.modal_adjuntar_archivos_requerimiento_pago_detalle')

@include('tesoreria.requerimiento_pago.modal_ver_adjuntos_requerimiento_pago_cabecera')
@include('tesoreria.requerimiento_pago.modal_ver_adjuntos_requerimiento_pago_detalle')


@include('tesoreria.requerimiento_pago.modal_nuevo_contribuyente')
@include('tesoreria.requerimiento_pago.modal_nueva_persona')
@include('tesoreria.requerimiento_pago.modal_nueva_cuenta_bancaria_destinatario')

@include('tesoreria.requerimiento_pago.modal_ver_agregar_adjuntos_requerimiento_pago')
@include('logistica.requerimientos.modal_lista_trabajadores')

@include('tesoreria.requerimiento_pago.modal_factura_requerimiento_pago')
@include('logistica.requerimientos.modal_requerimientos_vinculados_con_partida')

@endsection

@section('scripts')


<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('js/tesoreria/requerimientoPago/ListarRequerimientoPagoView.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/ListarRequerimientoPagoView.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/FacturaView.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/FacturaView.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/nuevoDestinatario.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/nuevoDestinatario.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/nuevaCuentaBancariaDestinatario.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/nuevaCuentaBancariaDestinatario.js'))}}"></script>
<script src="{{ asset('js/tesoreria/requerimientoPago/verTodoAdjuntosYAdicionalesRequerimientoPago.js')}}?v={{filemtime(public_path('js/Tesoreria/requerimientoPago/verTodoAdjuntosYAdicionalesRequerimientoPago.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/modal_lista_trabajadores.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/modal_lista_trabajadores.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/presupuesto-interno-view.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/presupuesto-interno-view.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/presupuesto-interno-model.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/presupuesto-interno-model.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/modalCuadroPresupuesto.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/modalCuadroPresupuesto.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/verRequerimientosVinculadosConPartida.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/verRequerimientosVinculadosConPartida.js'))}}"></script>
<script>

    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');
    function updateUM(val) {
        val.options[val.selectedIndex].setAttribute("selected", "");
    }
    var gruposUsuario = JSON.parse('{!!$gruposUsuario!!}');

    $(document).ready(function() {

        // console.log('initializeEventHandlerListaRequerimientoPago');

        const presupuestoInternoView = new PresupuestoInternoView(new PresupuestoInternoModel('{{csrf_token()}}'));
        presupuestoInternoView.eventos();


        const listarRequerimientoPagoView = new ListarRequerimientoPagoView(presupuestoInternoView);

        listarRequerimientoPagoView.mostrarListaRequerimientoPago('ALL');

        listarRequerimientoPagoView.initializeEventHandlerListaRequerimientoPago();

        listarRequerimientoPagoView.filtrosRequerimientosPago();

        const facturaView = new FacturaView(presupuestoInternoView);
        facturaView.eventos();

    });

    var tieneAccionCrearRequerimientoPago = '{{Auth::user()->tieneAccion(142) }}';

    // window.onload = function() {
    //     listarRequerimientoView.mostrar('ALL');
    // };



</script>
@endsection
