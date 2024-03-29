@extends('themes.base')
@include('layouts.menu_proyectos')

@section('cabecera')
Opciones y sus Relaciones
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{route('proyectos.index')}}"><i class="fas fa-tachometer-alt"></i> Proyectos</a></li>
  <li>Reportes</li>
  <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="opciones_todo">
    <div class="thumbnail" style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
        <div class="row">
            <div class="col-md-12">
                <table class="mytable table table-condensed table-bordered table-okc-view"
                    id="listaOpcionesTodo">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Fecha Emisión</th>
                            <th>Pres.Int.</th>
                            <th>Propuesta</th>
                            <th>Proyecto</th>
                            <th>Pres.Eje.</th>
                            <th>SubTotal</th>
                            <th>IGV</th>
                            <th>Total</th>
                            <th>Imp.Req.</th>
                            <th>OC/OS</th>
                            {{-- <th>OC con IGV</th> --}}
                            {{-- <th width="90px">Acción</th> --}}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> -->
    

    <script src="{{ asset('js/proyectos/reportes/opciones_todo.js')}}"></script>
    <!-- <script src="{{ asset('js/proyectos/proyectoContrato.js')}}"></script> -->
    <!-- <script src="{{ asset('js/logistica/clienteModal.js')}}"></script> -->
    <script>
    $(document).ready(function(){
        
    });
    </script>
@endsection
