
class KardexView {

    constructor(model) {
        this.model = model;

    }

    listar = () => {
        // console.log(data_filtros);
        var vardataTables = funcDatatables();
        let model = this.model;
        const $tabla = $('#tabla').DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            destroy: true,
            language: vardataTables[0],
            // responsive: true,
            // processing: true,
            serverSide: true,
            initComplete: function (settings, json) {
                const $filter = $('#tabla_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscar" class="btn btn-primary pull-right" type="button" style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;"><i class="fa fa-search"></i></button>');
                $('#btnBuscar').addClass('btn-sm')
                $filter.find('input').addClass('form-control-sm');

                $input.off();

                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscar').trigger('click');
                    }
                });
                $('#btnBuscar').on('click', (e) => {
                    $tabla.search($input.val()).draw();
                });

            },
            drawCallback: function (settings) {
                $('#tabla_filter input').prop('disabled', false);
                $('#btnBuscar').html('<i class="fa fa-search"></i>').prop('disabled', false);
                $('#tabla_filter input').trigger('focus');
            },
            order: [[0, 'desc']],
            ajax: {
                url: route('kardex.productos.listar'),
                method: 'POST',
                // headers: {'X-CSRF-TOKEN': token},
                // data: data_filtros,
                beforeSend: data => {
                    $('#tabla').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
                }
            },
            columns: [
                {data: 'codigo_agil', className: 'text-center'},
                {data: 'codigo_softlink'},
                {data: 'part_number'},
                {data: 'almacen', className: 'text-center'},
                {data: 'empresa'},
                {data: 'estado_kardex', className: 'text-center'},
                {data: 'responsable'},
                {data: 'fecha_registro', className: 'text-center'},
                {data: 'accion', orderable: false, searchable: false, className: 'text-center'}
            ],
            buttons: [
                {
                    text: '<i class="fa fa-filter"></i> Importar carga Inicial',
                    action: function () {
                        // $("#formulario-masivo")[0].reset();
                        $("#modal-carga-inicial").modal("show");
                    },
                    className: 'btn btn-default btn-sm',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-primary')
                    }
                },
                {
                    text: '<i class="fa fa-filter"></i> Actualizar',
                    className: 'btn btn-default btn-sm actualizar-kardex',
                    action: function () {
                        // $("#formulario-masivo")[0].reset();
                        model.actualizarKardex().then((respuesta) => {
                            console.log(respuesta);
                        }).fail((respuesta) => {
                            // return respuesta;
                        }).always(() => {
                        });
                    },
                    init: function(api, node, config) {
                        $(node).removeClass('btn-primary')
                    }
                },
                // {
                //     text: '<i class="fa fa-plus"></i> Agregar GR',
                //     action: function () {
                //         $("#formulario")[0].reset();
                //         $('[name=id]').val(0);
                //         $(".select2").val(null).trigger('change');
                //         $('[name="empresa_id"]').val('').trigger('change');
                //         $("#modalRegistro").modal("show");

                //         $('#span-codigo').addClass('d-none');
                //     },
                //     className: 'btn btn-default btn-sm',
                //     init: function(api, node, config) {
                //         $(node).removeClass('btn-primary')
                //     }
                // },
                // {
                //     text: '<i class="fa fa-list-ul"></i> Agregar GR Automatico',
                //     action: function () {
                //         $("#formulario-masivo")[0].reset();
                //         $("#modalRegistroMasivo").modal("show");
                //     },
                //     className: 'btn btn-default btn-sm',
                //     init: function(api, node, config) {
                //         $(node).removeClass('btn-primary')
                //     }
                // },
                // {
                //     text: '<i class="fa fa-file-excel text-black"></i> Reporte',
                //     action: function () {
                //         // $("#formulario-masivo")[0].reset();
                //         // $("#modalRegistroMasivo").modal("show");
                //         // window.open(route('control.guias.reporte-filtros',{empresa_id:data_filtros.empresa_id,estado:data_filtros.estado,fecha_final:data_filtros.fecha_final,fecha_inicio:data_filtros.fecha_inicio}), "Diseño Web", "width=300, height=200")
                //         // window.open(`areporte-filtros`);
                //         console.log(data_filtros);
                //         let form = $('<form action="reporte-filtros" method="POST" target="_blank"> '+
                //                 '<input type="hidden" name="_token" value="'+token+'" >'+
                //                 '<input type="hidden" name="empresa_id" value="'+data_filtros.empresa_id+'" >'+
                //                 '<input type="hidden" name="estado" value="'+data_filtros.estado+'" >'+
                //                 '<input type="hidden" name="fecha_final" value="'+data_filtros.fecha_final+'" >'+
                //                 '<input type="hidden" name="fecha_inicio" value="'+data_filtros.fecha_inicio+'" >'+
                //             '</form>');
                //         $('body').append(form);
                //         form.submit();
                //     },
                //     className: 'btn btn-default btn-sm',
                //     init: function(api, node, config) {
                //         $(node).removeClass('btn-primary')
                //     }
                // }

            ],
            rowCallback: function(row, data) {
                let $class = '';
                if (data.estado_registro == 0) {
                    $class = 'text-danger';
                }

                $(row).addClass($class);
            }
        });
        $tabla.on('search.dt', function() {
            $('#tabla_filter input').attr('disabled', true);
            $('#btnBuscar').html('<i class="fa fa-stop-circle" aria-hidden="true"></i>').prop('disabled', true);
        });
        $tabla.on('init.dt', function(e, settings, processing) {
            // $('#tabla-data_length label').addClass('select2-sm');
            $('#tabla').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        });
        $tabla.on('processing.dt', function(e, settings, processing) {
            if (processing) {
                $('#tabla').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
            } else {
                $('#tabla').LoadingOverlay("hide", true);
            }
        });
    }
    eventos = () => {
        let model = this.model;

        $('#carga-inicial').submit(function (e) {
            e.preventDefault();
            let data = new FormData($(e.currentTarget)[0]);
            model.cargaInicialKardex(data).then((respuesta) => {
                $(e.currentTarget).trigger("reset")
                if(respuesta.tipo == "success"){
                    // $('#tabla').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
                    $('#tabla').DataTable().ajax.reload();
                }
                $("#modal-carga-inicial").modal("hide");
            }).fail((respuesta) => {
                // return respuesta;
            }).always(() => {
            });
        });

        $('#tabla').on('click','a.ver-series',function (e) {
            e.preventDefault();
            let id = $(e.currentTarget).attr('data-id');
            listarSeries(id);
            $('#modal-lista-series').modal('show');
        });
        function listarSeries(id) {
            var vardataTables = funcDatatables();

            const $tabla = $('#tabla-series').DataTable({
                dom: 'Bfrtip',
                pageLength: 20,
                destroy: true,
                language: vardataTables[0],
                // responsive: true,
                // processing: true,
                serverSide: true,
                initComplete: function (settings, json) {
                    const $filter = $('#tabla-series_filter');
                    const $input = $filter.find('input');
                    $filter.append('<button id="btnBuscar" class="btn btn-primary pull-right" type="button" style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;"><i class="fa fa-search"></i></button>');
                    $('#tabla-series_filter #btnBuscar').addClass('btn-sm')
                    $filter.find('input').addClass('form-control-sm');

                    $input.off();

                    $input.on('keyup', (e) => {
                        if (e.key == 'Enter') {
                            $('#tabla-series_filter #btnBuscar').trigger('click');
                        }
                    });
                    $('#tabla-series_filter #btnBuscar').on('click', (e) => {
                        $tabla.search($input.val()).draw();
                    });

                },
                drawCallback: function (settings) {
                    $('#tabla-series_filter input').prop('disabled', false);
                    $('#tabla-series_filter #btnBuscar').html('<i class="fa fa-search"></i>').prop('disabled', false);
                    $('#tabla-series_filter input').trigger('focus');
                },
                order: [[1, 'desc']],
                ajax: {
                    url: route('kardex.productos.listar-series'),
                    method: 'POST',
                    // headers: {'X-CSRF-TOKEN': token},
                    data: {
                        _token: token,
                        id:id
                    },
                    beforeSend: data => {
                        $('#tabla-series').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
                    }
                },
                columns: [
                    {data: 'serie', className: 'text-center'},
                    {data: 'fecha'},
                    {data: 'precio'},
                    {data: 'precio_unitario', className: 'text-center'},

                ],
                buttons:[],
                rowCallback: function(row, data) {
                    let $class = '';
                    if (data.estado_registro == 0) {
                        $class = 'text-danger';
                    }

                    $(row).addClass($class);
                }
            });
            $tabla.on('search.dt', function() {
                $('#tabla-series_filter input').attr('disabled', true);
                $('#tabla-series_filter #btnBuscar').html('<i class="fa fa-stop-circle" aria-hidden="true"></i>').prop('disabled', true);
            });
            $tabla.on('init.dt', function(e, settings, processing) {
                // $('#tabla-data_length label').addClass('select2-sm');
                $('#tabla-series').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
            });
            $tabla.on('processing.dt', function(e, settings, processing) {
                if (processing) {
                    $('#tabla-series').LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
                } else {
                    $('#tabla-series').LoadingOverlay("hide", true);
                }
            });
        }
    }
}
