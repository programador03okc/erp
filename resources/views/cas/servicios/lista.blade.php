
@extends('themes.base')

@section('cabecera') Gestión de incidencias @endsection
@include('layouts.menu_cas')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
    <style>
        .invisible{
            display: none;
        }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li>Servicio</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')

    <div class="box box-solid">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="mytable table table-condensed table-bordered table-okc-view" id="tabla-data" style="width:100%;">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th>Código</th>
                                <th>Estado</th>
                                <th>Empresa</th>
                                <th>Cliente</th>
                                <th>Nro. de caso</th>
                                <th>Nro orden trabajo (WO)</th>

                                <th>Nombre contacto</th>
                                <th>Fecha de labor en sitio</th>
                                <th>Serie</th>
                                <th>Fecha registro</th>
                                <th>Responsable</th>
                                <th>Falla</th>
                                <th width="70px">Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="modal-fecha-cierre" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="form-modal-cierre" action="">
                @csrf
                <input type="hidden" name="id_servicio" value="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title">Fecha de cierre</h5>

                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="fecha_cierre">Fecha de cierre</label>
                      <input type="date" name="fecha_cierre" id="fecha_cierre" class="form-control" placeholder="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>

    {{-- <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script> --}}

    <script src="{{ asset('js/cas/servicios/servicio-model.js')}}?v={{filemtime(public_path('js/cas/servicios/servicio-model.js'))}}"></script>
    <script src="{{ asset('js/cas/servicios/servicio-view.js')}}?v={{filemtime(public_path('js/cas/servicios/servicio-view.js'))}}"></script>

    <script>
        $(document).ready(function() {
            const view = new ServicioView(new ServicioModel(token));
            view.listar();
            view.eventosLista();
        });
    </script>

@endsection


{{-- ---- --}}
