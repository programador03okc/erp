@extends('themes.base')
@include('layouts.menu_logistica')

@section('cabecera')
    Gestión de Programación de Despachos
@endsection

@section('estilos')
    {{-- <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}"> --}}

    <link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
    <style>
        .timeline>li>.timeline-item-despachos {
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            border-radius: 3px;
            margin-top: 0;
            background: #fff;
            color: #444;
            margin-left: 60px;
            margin-right: 15px;
            padding: 0;
            position: relative;
        }

        .timeline>li>.timeline-item-despachos>.time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }

        .timeline>li>.timeline-item-despachos>.timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline>li>.timeline-item-despachos>.timeline-header>a {
            font-weight: 600;
        }

        .timeline>li>.timeline-item-despachos>.timeline-body,
        .timeline>li>.timeline-item-despachos>.timeline-footer {
            padding: 10px !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('logistica.index') }}"><i class="fas fa-tachometer-alt"></i> Logística</a></li>
        <li>Distribución</li>
        <li class="active">@yield('cabecera')</li>
    </ol>
@endsection

@section('cuerpo')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 ">
            <div class="form-group">
                <button type="button" id="nuevo" class="btn btn-success btn-sm" ><i class="fa fa-plus"></i> Nuava Programación</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>ORDEN DE DESPACHO INTERNO (ODI)</h3>
            <ul class="timeline" data-action="despachos-odi">
            </ul>
        </div>
        <div class="col-md-6">
            <h3>ORDEN DE DESPACHO EXTERNO (ODE)</h3>
            <ul class="timeline" data-action="despachos-ode">
            </ul>
        </div>
    </div>
    <!-- /.row -->

    <!-- Modal -->
    <div class="modal fade" id="modal-despachos" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title">Modal title</h5>
                </div>
                <form action="" id="guardar">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Titulo : <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="titulo" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha de Programación : <span class="text-red">*</span></label>
                                    <input type="date" class="form-control" name="fecha_programacion" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Orden de Despacho</label>
                                <div class="form-group">

                                    <label>
                                      <input type="radio" name="aplica_cambios" class="minimal" value="true"  required>
                                      Interno
                                    </label>
                                    <label>

                                      <input type="radio" name="aplica_cambios" class="minimal" value="false" required>
                                      Externo
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Descripcion : <span class="text-red">*</span></label>
                                    <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="../../plugins/iCheck/icheck.min.js"></script> --}}
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script> --}}

    <script src="{{ asset('js/almacen/distribucion/programacion_despachos/programacion_despacho-model.js') }}"></script>
    <script src="{{ asset('js/almacen/distribucion/programacion_despachos/programacion_despacho-view.js') }}"></script>

    <script>
        $(document).ready(function() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            })

            $('body').addClass('fixed');
            const view = new ProgramacionDespachoView(new ProgramacionDespachoModel(token));
            view.listarODI(1);
            view.listarODE(1);
            view.eventos();
            // view.programacionDespachos();
            // console.log(view);
            // console.log(view.listar());
            vista_extendida();
        });
    </script>
@endsection
