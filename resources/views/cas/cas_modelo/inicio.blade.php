@extends('themes.base')

@section('cabecera') Modelo @endsection
@include('layouts.menu_cas')
@section('estilos')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
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
    <li><a href="{{route('cas.index')}}"><i class="fa fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li>Garantías</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="incidencia">

    {{-- <form id="form-incidencia"> --}}
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Listado de modelos</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-success" data-button="nuevo" type="button"><i class="fa fa-plus"></i> Nuevo Modelo</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="mytable table table-condensed table-bordered table-okc-view"
                                            id="lista-modelos" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th hidden></th>
                                            <th>codigo</th>
                                            <th>descripcion</th>
                                            <th width="90px">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- </form> --}}
</div>
<div id="nuevo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('cas.garantias.modelo.guardar')}}" method="POST" data-form="guardar" >
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Nuevo Modelo</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion_nuevo">Descripcion</label>
                                <input id="descripcion_nuevo" class="form-control" type="text" name="descripcion" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="editar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('cas.garantias.modelo.actualizar')}}" method="POST" data-form="actualizar" >
                <input type="hidden" name="id" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Editar Modelo</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion_editar">Descripcion</label>
                                <input id="descripcion_editar" class="form-control" type="text" name="descripcion" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('cas.incidencias.incidenciaModal')
@include('cas.incidencias.salidasVentaModal')
@include('cas.incidencias.seriesProductosModal')
@include('almacen.distribucion.ordenDespachoContacto')
@include('almacen.distribucion.agregarContacto')
@include('publico.ubigeoModal')
@endsection

@section('scripts')

<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>


<script src="{{ asset('js/cas/cas_modelo/modelo.js') }}"></script>


<script>
    const route_listar = "{{ route('cas.garantias.modelo.listar') }}";
    const route_editar = "{{ route('cas.garantias.modelo.editar') }}";
    const route_eliminar = "{{ route('cas.garantias.modelo.eliminar') }}";
    $(document).ready(function() {

        
        $(".select2").select2({
            tags: true
        });
    });
</script>
@endsection


{{-- ------------------------------------ --}}
