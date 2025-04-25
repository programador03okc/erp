class ServicioView {

    constructor(model) {
        this.model = model;
        this.tabla
    }

    listar = () => {
        var vardataTables = funcDatatables();
        const $tabla = $('#tabla-data').DataTable({
            destroy: true,
            dom: 'Bftip',
            autoWidth: false,
            responsive: true,
            pageLength: 50,
            language: vardataTables[0],
            serverSide: true,
            processing: true,
            buttons: [
                {
                    text: '<i class="fa fa-plus"></i> Nuevo',
                    attr: {
                        id: 'btn-cliente',
                    },
                    action: () => {
                        // vistaCrear();
                        location.href = route('cas.servicios.crear');
                    },
                    init: function(api, node, config) {

                        $(node).removeClass('btn-primary')
                    },
                    className: 'btn-light btn-sm btn-info'
                }
            ],
            // pagingType: 'full_numbers',
            // scrollCollapse: true,
            // scrollY: '60vh',
            // scrollX: '100vh',
            initComplete: function (settings, json) {
                const $filter = $('#tabla-data_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscar" class="btn btn-default btn-sm" type="button" style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;"><i class="fa fa-search"></i></button>');
                $input.addClass('form-control-sm');
                $input.attr('style','border-bottom-right-radius: 0px;border-top-right-radius: 0px;padding-top: 3px;');

                $('#tabla-data_wrapper .dt-buttons.btn-group.flex-wrap').addClass('btn-foto-posicion');
                $input.off();
                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscar').trigger('click');
                    }
                });
                $('#btnBuscar').on('click', (e) => {
                    $tabla.search($input.val()).draw();
                });
                // $('#tabla-data_length label').addClass('select2-sm');
                // //______Select2
                // $('[name="tabla-data_length"]').select2({
                //     minimumResultsForSearch: Infinity
                // });
                // const $paginate = $('#tabla-data_paginate');
                // $paginate.find('ul.pagination').addClass('pagination-sm');

            },
            drawCallback: function (settings) {
                $('#tabla-data_filter input').prop('disabled', false);
                $('#btnBuscar').html('<i class="fa fa-search"></i>').prop('disabled', false);
                $('#tabla-data_filter input').trigger('focus');
                const $paginate = $('#tabla-data_paginate');
                $paginate.find('ul.pagination').addClass('pagination-sm');

            },
            order: [[0, 'desc']],
            ajax: {
                url: route('cas.servicios.listar'),
                method: 'POST',
                // headers: {'X-CSRF-TOKEN': token},
                dataType: "JSON",
                // data: buscar,
                data: {_token : token},
            },
            columns: [
                // {data: 'id', className: 'text-center'},
                {data: 'codigo', className: 'text-center'},
                {data: 'estado_doc', className: 'text-center'},
                {data: 'empresa', className: 'text-center'},
                {data: 'cliente', className: 'text-center'},
                {data: 'nro_orden', className: 'text-center'},
                {data: 'factura', className: 'text-center'},
                {data: 'nombre_contacto', className: 'text-center'},
                {data: 'fecha_reporte', className: 'text-center'},
                {data: 'serie', className: 'text-center'},
                {data: 'fecha_registro', className: 'text-center'},
                {data: 'responsable', className: 'text-center'},
                {data: 'falla_reportada', className: 'text-center'},
                {data: 'accion', className: 'text-center'},
            ]
        });
        $tabla.on('search.dt', function() {
            $('#tabla-data_filter input').attr('disabled', true);
            $('#btnBuscar').html('<i class="fa fa-clock-o" aria-hidden="true"></i>').prop('disabled', true);
        });
        $tabla.on('init.dt', function(e, settings, processing) {
            // $('#tabla-data_length label').addClass('select2-sm');
            // $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        });
        $tabla.on('processing.dt', function(e, settings, processing) {
            if (processing) {
                // $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
            } else {
                // $(e.currentTarget).LoadingOverlay("hide", true);
            }
        });
        this.tabla = $tabla;
        // $tabla.buttons().container().appendTo('#tabla-data_wrapper .col-md-6:eq(0)');
    }

    eventos = () => {
        $('#form-incidencia').submit((e) => {
            e.preventDefault();
            let data = $(e.currentTarget).serialize();
            let button = $(e.currentTarget).find('button[type="submit"]')
            // let tabla = this.tabla;
            button.attr('disabled','true');
            button.find('i').removeClass('fa-save')
            button.find('i').addClass('fa-spinner fa-spin');

            this.model.guardar(data).then((respuesta) => {
                if (respuesta.status == true) {

                    Swal.fire({
                        title: respuesta.title,
                        text: respuesta.text,
                        icon: respuesta.icon,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                      }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = route('cas.servicios.lista');

                        }
                    });
                }
                button.removeAttr('disabled')
                button.find('i').removeClass('fa-spinner fa-spin')
                button.find('i').addClass('fa-save');

            }).always(() => {
            }).fail(() => {
                $('#modal-registro').modal('hide');
                button.removeAttr('disabled')
                button.find('i').removeClass('fa-spinner fa-spin')
                button.find('i').addClass('fa-save');
            });

        });

    }
    eventosLista = () => {
        $('#tabla-data').on('click', 'button.cerrar',(e) => {
            e.preventDefault();
            let id = $(e.currentTarget).attr('data-id');
            console.log('cerrar');
            $('#form-modal-cierre').find('input[name="id_servicio"]').val(id);
            $('#modal-fecha-cierre').modal('show');
        });
        $('#form-modal-cierre').submit((e) => {
            e.preventDefault();
            let data = $(e.currentTarget).serialize();
            let button = $(e.currentTarget).find('button[type="submit"]')
            button.attr('disabled','true');
            button.find('i').removeClass('fa-save')
            button.find('i').addClass('fa-spinner fa-spin');
            this.model.guardarFechaCierre(data).then((respuesta) => {
                if (respuesta.status == true) {

                    Swal.fire({
                        title: respuesta.title,
                        text: respuesta.text,
                        icon: respuesta.icon,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                      }).then((result) => {
                        if (result.isConfirmed) {
                            $('#tabla-data').DataTable().ajax.reload(null, false);
                            $('#modal-fecha-cierre').modal('hide');
                        }
                    });
                }
                button.removeAttr('disabled')
                button.find('i').removeClass('fa-spinner fa-spin')
                button.find('i').addClass('fa-save');

            }).always(() => {
            }).fail(() => {
                $('#modal-registro').modal('hide');
                button.removeAttr('disabled')
                button.find('i').removeClass('fa-spinner fa-spin')
                button.find('i').addClass('fa-save');
            });
        });
        $('#tabla-data').on('click', 'button.cancelar',(e) => {
            e.preventDefault();
            $('#alert-eliminar').modal('show');
            let id = $(e.currentTarget).attr('data-id');
            let model = this.model;
            // console.log(id);


            Swal.fire({
                title: "Cancelar Servicio",
                text: "Esta seguro de cancelar el servicio.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                showLoaderOnConfirm: true,
                preConfirm:
                async () => model.cancelar(id).then((respuesta) => {
                    return respuesta;
                }).always(() => {

                }).fail(() => {

                })
                // allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(result);
                    $('#tabla-data').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        title: result.value.title,
                        text: result.value.text,
                        icon: result.value.icon,
                    });
                }
            });


        });
    }
}
