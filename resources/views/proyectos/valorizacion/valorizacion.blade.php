@extends('themes.base')
@include('layouts.menu_proyectos')
@section('option')
    @include('layouts.option')
@endsection

@section('cabecera')
Valorización
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{route('proyectos.index')}}"><i class="fas fa-tachometer-alt"></i> Proyectos</a></li>
  <li>Propuestas</li>
  <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="valorizacion">
    <form id="form-valorizacion" type="register" form="formulario">
        <div class="thumbnail" style="padding-left: 10px;padding-right: 10px;">
            <legend class="mylegend">
                <h2>Datos Generales</h2>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><label id="codigo"></label></li>
                    <li><label id="numero"></label></li>
                    <li>Total Proyectado: <label id="total"></label></li>
                    <li><i class="fas fa-file-excel icon-tabla green boton"
                        data-toggle="tooltip" data-placement="bottom"
                        title="Exportar a Excel" onclick="exportTableToExcel('listaPartidas','Valorizacion')"></i></li>
                </ol>
            </legend>
            <div class="row">
                <div class="col-md-1">
                    <h5>Propuesta:</h5>
                </div>
                <div class="col-md-7">
                    <div class="input-group-okc">
                        <input type="text" class="oculto" name="id_valorizacion" primary="ids">
                        <input type="text" class="oculto" name="id_presup">
                        <input type="text" class="oculto" name="id_periodo">
                        <input type="text" class="oculto" name="numero">
                        <input type="text" class="oculto" name="modo">
                        <input type="text" class="form-control" aria-describedby="basic-addon2"
                            readonly name="nombre_opcion" disabled="true">
                    </div>
                </div>
                <div class="col-md-2">
                    <h5>Fecha Valorización:</h5>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="fecha_valorizacion" disabled="true"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <h5>Encargado del Servicio:</h5>
                </div>
                <div class="col-md-7">
                    <input type="text" class="oculto" name="id_residente">
                    <input type="text" class="form-control" name="nombre_residente" disabled="true"/>
                </div>
                <div class="col-md-1">
                    <h5>Periodo:</h5>
                </div>
                <div class="col-md-3">
                    <div style="display:flex;">
                        <input type="date" class="form-control" name="fecha_inicio" disabled="true"/>
                        <input type="date" class="form-control" name="fecha_fin" disabled="true"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="div-scroll">
                        <table class="mytable table table-condensed table-bordered table-okc-view" width="100%"
                            id="listaPartidas" style="margin-top:10px;">
                            <thead>
                                <tr>
                                    <th rowSpan="2">Código</th>
                                    <th rowSpan="2">Descripción</th>
                                    <th rowSpan="2" width="30">Und</th>
                                    <th colSpan="3" style="text-align: center;">Programado</th>
                                    <th colSpan="2" style="text-align: center;">Ejec.Anterior</th>
                                    <th colSpan="2" style="text-align: center;">Ejec.Actual</th>
                                    <th colSpan="2" style="text-align: center;">Acum.Total</th>
                                    <th colSpan="2" style="text-align: center;">Saldo</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">Metrado</th>
                                    <th style="text-align: center;">Unitario</th>
                                    <th style="text-align: center;">Parcial</th>
                                    <th style="text-align: center;">Metrado</th>
                                    <th style="text-align: center;">Parcial</th>
                                    <th style="text-align: center;">Metrado</th>
                                    <th style="text-align: center;">Parcial</th>
                                    <th style="text-align: center;">Metrado</th>
                                    <th style="text-align: center;">Parcial</th>
                                    <th style="text-align: center;">Metrado</th>
                                    <th style="text-align: center;">Parcial</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('proyectos.presupuesto.propuestaModal')
@include('proyectos.valorizacion.valorizacionModal')
@endsection

@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>
    

    <script src="{{ asset('js/proyectos/valorizacion/valorizacion.js')}}"></script>
    <script src="{{ asset('js/proyectos/presupuesto/propuestaModal.js')}}"></script>
    <script src="{{ asset('js/proyectos/valorizacion/valorizacionModal.js')}}"></script>
    <script>
    $(document).ready(function(){
        
    });
    </script>
@endsection
