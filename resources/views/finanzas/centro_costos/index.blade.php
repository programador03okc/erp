@extends('themes.base')

@section('cabecera') Centros de Costos @endsection
@include('layouts.menu_finanzas')
@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">

    <style>
        .lbl-codigo:hover{
            color:#007bff !important;
            cursor:pointer;
        }
        .invisible{
            display: none;
        }
	    .d-none{
	    display: none;
    	}
        /* .bg- */
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><i class="fa fa-usd"></i> Finanzas</li>
    <li class="active"> @yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="box box-solid">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <table class="mytable table table-condensed table-bordered table-okc-view table-hover" id="listaCentroCostos">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Periodo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <form id="form-centro-costos" style="padding-right: 20px; padding-top: 10px;">
                    <input style="display: none" name="id_centro_costo"/>
                    <div class="row">
                        <div class="col-md-5">
                            <h5>Grupo</h5>
                            <select class="form-control" name="id_grupo" required>
                                <option value="">Elija una opción</option>
                                @foreach ($grupos as $grupo)
                                <option value="{{$grupo->id_grupo}}">{{$grupo->descripcion}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-5">
                            <h5>Periodo</h5>
                            <select class="form-control" name="periodo" required>
                                <option value="">Elija una opción</option>
                                @foreach ($periodos as $periodo)
                                <option value="{{$periodo->descripcion}}">{{$periodo->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Código</h5>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Descripción</h5>
                            <input type="text" name="descripcion" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" style="padding-top: 24px;">
                            <input type="submit" id="submit-cc" class="btn btn-success" value="Guardar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 ">

                <div class="row">
                    {{-- <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered dt-responsive nowrap"
                            id="listaCentroCostos" width="100%">
                                <thead>
                                    <tr style="background: gainsboro;">
                                        <th scope="col">Código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Grupo</th>
                                        <th scope="col">Periodo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div> --}}

                </div>

            </div>
            <div class="col-md-6">
                {{-- <form id="form-centro-costos" style="padding-right: 20px; padding-top: 10px;">
                    <input style="display: none" name="id_centro_costo"/>
                    <div class="row">
                        <div class="col-md-5">
                            <h5>Grupo</h5>
                            <select class="form-control" name="id_grupo" required>
                                <option value="">Elija una opción</option>
                                @foreach ($grupos as $grupo)
                                <option value="{{$grupo->id_grupo}}">{{$grupo->descripcion}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-5">
                            <h5>Periodo</h5>
                            <select class="form-control" name="periodo" required>
                                <option value="">Elija una opción</option>
                                @foreach ($periodos as $periodo)
                                <option value="{{$periodo->descripcion}}">{{$periodo->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Código</h5>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Descripción</h5>
                            <input type="text" name="descripcion" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" style="padding-top: 24px;">
                            <input type="submit" id="submit-cc" class="btn btn-success" value="Guardar"/>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}">
    </script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}">
    </script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}">
    </script>
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{('/js/finanzas/centro_costos/centro_costos.js')}}"></script>
<script>
    $(document).ready(function () {

        // mostrarCentroCostos();
        listarCentroCostos();
    });
</script>
@endsection

{{-- ---------------------- --}}
