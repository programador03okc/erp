@extends('themes.base')
@include('layouts.menu_logistica')

@section('cabecera')
Mapeo de Productos
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{route('logistica.index')}}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
  <li>Requerimientos</li>
  <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('estilos')
<style>
.text-success{
    color: #09b336;
}
.text-warning{
    color: #baab07;
}
</style>
@endsection

@section('cuerpo')
<div class="page-main" type="mapeoProductos">

    <div class="box box-solid">
        <div class="box-body">
            <div class="col-md-12" style="padding-top:10px;padding-bottom:10px;">

                <div class="row">
                    <div class="col-md-12">
                        <table class="mytable table table-condensed table-bordered table-okc-view"
                            id="listaRequerimientos">
                            <thead>
                                <tr>
                                    <th hidden></th>
                                    <th >Codigo</th>
                                    <th style="width:10%">Tipo</th>
                                    <th style="width:30%">Concepto/Motivo</th>
                                    <th style="width:10%">Fecha entrega</th>
                                    <th style="width:10%">Sede</th>
                                    <th style="width:15%">Responsable</th>
                                    <th style="width:5%">Estado</th>
                                    <th style="width:5%">Mapear</th>
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

@include('logistica.requerimientos.mapeo.mapeoItemsRequerimiento')
@include('logistica.requerimientos.mapeo.mapeoAsignarProducto')

@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    

    <script src="{{ asset('js/logistica/mapeo/mapeo.js')}}"></script>
    <script src="{{ asset('js/logistica/mapeo/mapeoItemsRequerimiento.js')}}"></script>
    <script src="{{ asset('js/logistica/mapeo/mapeoAsignarProducto.js')}}"></script>

    <script>
    $(document).ready(function(){
        

        let mapeo = new MapeoProductos();
        mapeo.listarRequerimientos();

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            let tab = $(e.target).attr("href") // activated tab
            console.log('tab: '+tab);

            if (tab=='#seleccionar'){
                $('#productosSugeridos').DataTable().ajax.reload();
                $('#productosCatalogo').DataTable().ajax.reload();
            }
            else if (tab=='#crear'){
                // $('#listaComprobantes').DataTable().ajax.reload();
            }
         });

    });
    </script>
@endsection
