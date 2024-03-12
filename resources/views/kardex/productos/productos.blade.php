@extends('themes.base')

@section('cabecera')
    Productos
@endsection
@include('layouts.menu_kardex')
@section('estilos')
    <style>
        .invisible {
            display: none;
        }

        .d-none {
            display: none;
        }
    </style>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('kardex.index') }}"><i class="fas fa-tachometer-alt"></i> Kardex</a></li>
        <li class="active">@yield('cabecera')</li>
    </ol>
@endsection

@section('cuerpo')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Productos</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-inbox mb-0 dataTable no-footer dtr-inline collapsed table-responsive" id="tabla">
                                <thead>
                                    <tr>
                                        <th>Cod.Agil</th>
                                        <th>Cod.Softlink</th>
                                        <th>P. Number</th>
                                        <th>Almacen</th>
                                        <th>Empresa</th>
                                        <th>Estado Kardex</th>
                                        <th>Responsable</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade effect-flip-vertical" id="modal-carga-inicial">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Carga Inicial</h4>
                </div>
                {{-- <div class="modal-header">

                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div> --}}
                <form id="carga-inicial" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label >Seleccione un archivo</label>
                                    <input type="file" name="carga_inicial">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-primary">Seleccionar</button> --}}
                        <button type="submit" class="btn btn-info" >Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade effect-flip-vertical" id="modal-lista-series">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Series</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-inbox mb-0 dataTable no-footer dtr-inline collapsed table-responsive" id="tabla-series">
                                <thead>
                                    <tr>
                                        <th>Serie</th>
                                        <th>Fecha</th>
                                        <th>Precio</th>
                                        <th>P. Unitario</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>

<script src="{{ asset('js/kardex/kardex-model.js') }}"></script>
<script src="{{ asset('js/kardex/kardex-view.js') }}"></script>
<script>


    $(document).ready(function() {
        console.log(token);
        const view = new KardexView(new KardexModel(token));

        view.listar();
        view.eventos();
    });
    </script>
@endsection
{{-- --------------------- --}}
