@extends('themes.base')

@section('cabecera')
    Lista de Presupuestos Interno
@endsection
@include('layouts.menu_finanzas')
@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
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
        <li><a href="{{ route('finanzas.index') }}"><i class="fa fa-usd"></i> Finanzas</a></li>
        <li class="active"> @yield('cabecera')</li>
    </ol>
@endsection

@section('cuerpo')
    @if (in_array(303, $array_accesos))

    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Elaborados/Aprobados</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Finalizados</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Grafica</a></li>
                        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">

                            <table class="mytable table table-condensed table-bordered table-okc-view" id="lista-presupuesto-interno">
                                <thead>
                                    <tr>
                                        <th hidden></th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Fecha Emisión</th>
                                        <th scope="col">Grupo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Sede</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Total Ejecutado</th>
                                        <th scope="col">-</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <table class="mytable table table-condensed table-bordered table-okc-view" id="data-table-finalizados">
                                <thead>
                                    <tr>
                                        <th hidden></th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Fecha Emisión</th>
                                        <th scope="col">Grupo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Sede</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Total Ejecutado</th>
                                        <th scope="col">-</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            {{-- <div class="chart">
                                <canvas id="barChart" style="height:230px"></canvas>
                            </div> --}}
                            <div>
                                <div class="form-group">
                                    <label for="presupuesto_id">Seleccione el Presupuesto</label>
                                    <select class="form-control" name="presupuesto_id" id="presupuesto_id">
                                        <option value="">Seleccione el Presupuesto...</option>
                                        @foreach ($dataPPTI as $value)
                                            <option value="{{$value->id_presupuesto_interno}}">
                                                {{$value->codigo.'-'.$value->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>
        {{-- // ver el presupuesto  --}}
    <div id="modal-presupuesto" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="my-modal-title">Presupuesto Interno <span
                            class="codigo text-primary"></span> </h3>
                </div>
                <div class="modal-body">
                    <div class="row" data-presupuesto="table">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-dismiss="modal" type="button"><i class="fa fa-times"></i>
                        CERRAR</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-editar-monto-partida" class="modal fade " tabindex="-1" role="dialog"
        aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="" data-form="editar-monto-partida">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="my-modal-title">Editar monto de Presupuesto Interno <span
                                class="codigo text-primary"></span> </h3>
                    </div>
                    <input class="form-control" type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="partida">Partida : </label>
                            <select class="form-control search-partidas" name="partida" required>
                                <option value="" hidden>Seleccione...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mes">Meses : </label>
                            <select class="form-control" name="mes" id="mes" required>
                                <option value="" hidden>Seleccione...</option>
                                <option value="enero">ENERO</option>
                                <option value="febrero">FEBRERO</option>
                                <option value="marzo">MARZO</option>
                                <option value="abril">ABRIL</option>
                                <option value="mayo">MAYO</option>
                                <option value="junio">JUNIO</option>
                                <option value="julio">JULIO</option>
                                <option value="agosto">AGOSTO</option>
                                <option value="setiembre">SETIEMBRE</option>
                                <option value="octubre">OCTUBRE</option>
                                <option value="noviembre">NOVIEMBRE</option>
                                <option value="diciembre">DICIEMBRE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="monto">Monto : </label>
                            <input id="monto" class="form-control" type="number" name="monto" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-dismiss="modal" type="button"><i
                                class="fa fa-times"></i> Cerrar</button>
                        <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
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

    <!-- ChartJS -->
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/chart.js/chart.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/plugins/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/finanzas/presupuesto_interno/lista.js') }}"></script>
    <script src="{{ asset('js/finanzas/presupuesto_interno/graficos.js') }}"></script>

    <script>
        const route_editar = "{{ route('finanzas.presupuesto.presupuesto-interno.editar') }}";
        const route_editar_presupuesto_aprobado =
            "{{ route('finanzas.presupuesto.presupuesto-interno.editar-presupuesto-aprobado') }}";
        const array_accesos = JSON.parse('{!! json_encode($array_accesos) !!}');
        $(document).ready(function() {

        });
        $('.search-partidas').select2({
            dropdownParent: $('#modal-editar-monto-partida'),
            placeholder: 'Seleccione una partida...',
            language: "es",
            allowClear: true,
            ajax: {
                url: 'buscar-partida-combo',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        page: params.page,
                        id_presupuesto_interno: $('[data-form="editar-monto-partida"]').find('[name="id"]')
                        .val()
                    };
                    // return query;
                },
                processResults: function(data, params) {
                    // params.page = params.page || 1;
                    return {
                        // results: data.items,
                        // pagination: {
                        //     more: (params.page * 30) < data.total_count
                        // }
                        results: $.map(data, function(item) {
                            return {
                                text: item.partida + '(' + item.descripcion + ')',
                                // descripcion:item.descripcion,
                                id: item.partida
                            }
                        })

                    };
                },
                cache: true,
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (!repo.id) {

                return repo.text;
            }
            var state = $(
                `<span>` + repo.text + `</span>`
            );
            console.log(state);
            return state;

        }

        function formatRepoSelection(repo) {
            return repo.partida || repo.text;
        }
    </script>
@endsection


{{-- ------------------------------------- --}}
