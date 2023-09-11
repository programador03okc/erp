class ProgramacionDespachoView {

    constructor(model) {
        this.model = model;
    }

    /**
     * Listar despachos programados
     */

    listarODI = (page) => {
        // let page = 0;
        let fun = this;


        this.model.listarODI(page).then((respuesta) => {
            fun.programarDespachos(respuesta, 'odi');
        }).fail((respuesta) => {
            console.log(respuesta);
        }).always(() => {
        });

    };
    listarODE = (page) => {
        // let page = 0;
        let fun = this;
        this.model.listarODE(page).then((respuesta) => {
            fun.programarDespachos(respuesta, 'ode');
        }).fail((respuesta) => {
            console.log(respuesta);
        }).always(() => {
        });
    };

    // se renderisaran las progrmaciones
    programarDespachos = (respuesta, tipo) => {
        let html = '';
        let html_programacion = '';
        let html_fecha = '';
        $.each(respuesta.fechas, function (index, element) {


            if ($('[data-action="despachos-'+tipo+'"] [data-fecha="'+element+'"]').length===0) {
                html_fecha='<li class="time-label" data-fecha="'+element+'" data-tipo="header" data-od="'+tipo+'">'
                    +'<span class="bg-red">'
                        +moment(element).format('DD/MM/YYYY')
                    +'</span>'
                +'</li>';
                $('[data-action="despachos-'+tipo+'"]').append(html_fecha);
            }
        });



        $.each(respuesta.data.data, function (index, element) {

            let visible = $('[data-fecha="'+element.fecha_registro+'"][data-tipo="header"][data-od="'+tipo+'"]').attr('data-visible');
            if (visible=='true') {
                $('[data-fecha="'+element.fecha_registro+'"][data-tipo="header"][data-od="'+tipo+'"]').trigger('click');
            }


            html_programacion='<li data-despacho="'+element.id+'" data-fecha="'+element.fecha_registro+'" data-tipo="body" data-od="'+tipo+'">'
                +'<i class="fa fa-cube bg-blue"></i>'

                +'<div class="timeline-item-despachos">'
                    +'<span class="time text-black"><i class="fa fa-calendar-alt"></i> Programado para el '+moment(element.fecha_programacion).format('DD/MM/YYYY')+' </span>'

                    +'<h3 class="timeline-header"><a href="#">'+element.titulo+'</a> </h3>'

                    +'<div class="timeline-body">'
                        +element.descripcion
                        +(element.reprogramacion_id!==null?'<br><strong>ORDEN DE DESPACHO REPROGRAMADO</strong>':'')
                    +'</div>'
                    +'<div class="timeline-footer">'
                        +(array_accesos.find(element => element === 331)?'<a class="btn btn-primary btn-xs editar mr-5" data-id="'+element.id+'" data-despacho="'+tipo+'"><i class="fa fa-edit"> </i> Editar</a>':``)
                        +(array_accesos.find(element => element === 332)?'<a class="btn btn-danger btn-xs eliminar" data-id="'+element.id+'" data-despacho="'+tipo+'"><i class="fa fa-trash-alt" ></i> Eliminar</a>':``)

                        // +(array_accesos.find(element => element === 332)?'<a class="btn btn-danger btn-xs finalizar-despacho" data-id="'+element.id+'" data-despacho="'+tipo+'"><i class="fa fa-trash-alt" ></i> Eliminar</a>':``)

                        // +'<a class="btn btn-primary btn-xs editar mr-5" data-id="'+element.id+'" data-despacho="'+tipo+'"><i class="fa fa-edit"> </i> Editar</a>'
                        // +'<a class="btn btn-danger btn-xs eliminar" data-id="'+element.id+'" data-despacho="'+tipo+'"><i class="fa fa-trash-alt" ></i> Eliminar</a>'
                    +'</div>'
                +'</div>'
            +'</li>';
            // $('[data-action="despachos-'+tipo+'"] [data-fecha="'+element.fecha_registro+'"]').after(html_programacion);
            $('[data-action="despachos-'+tipo+'"] [data-fecha="'+element.fecha_registro+'"]').last().after(html_programacion);
        });
        $('#mostrar-'+tipo+'').remove();
        if (respuesta.data.next_page_url!==null) {

            html='<li class="time-label" id="mostrar-'+tipo+'">'
                +'<button type="button" class="btn bg-gray btn-sm" data-href="'+respuesta.data.next_page_url+'" data-action="mostar-mas-'+tipo+'"> <i class="fa fa-plus"></i> '
                    +'<span class="">'
                        +'Mostrar más'
                    +'</span>'
                +'</button>'
            +'</li>' ;
        }else{
            html='<li id="mostrar-'+tipo+'">'
                +'<i class="fa fa-clock bg-gray"></i>'
                +'<div class="timeline-item-despachos">'
                +'</div>'
            +'</li>';
        }
        $('[data-action="despachos-'+tipo+'"]').append(html);
        if (respuesta.data.total!==0) {
            $('[data-action="despachos-'+tipo+'"] [data-tipo="header"][data-li="inicio"]').remove();
        }
    }
    eventos = () => {
        let fun = this;
        $(document).on('click','button[data-action="mostar-mas-odi"]',function (e) {

            let href = $(e.currentTarget).attr('data-href');
            let page = href.split('=');
            fun.listarODI(page[1]);

        });
        $(document).on('click','button[data-action="mostar-mas-ode"]', (e) => {

            let href = $(e.currentTarget).attr('data-href');
            let array = href.split('=');
            fun.listarODE(array[1]);
        });
        $('#nuevo').on("click", (e) => {
            $('#modal-despachos').modal('show');
            $("#guardar")[0].reset();
            $("#modal-despachos").find('.modal-title').text('Nueva Programación de Despacho');
            $('#guardar [name=id]').val(0);
            $("#guardar").find('[name="aplica_cambios"]').removeAttr('checked');
            $("#guardar").find('[name="aplica_cambios"]').closest('div').removeClass('checked');
        });

        $('#guardar').on("submit", (e) => {
            e.preventDefault();
            let data = $(e.currentTarget).serialize();
            let modelo = this.model;

            let html_fecha = '';
            let html_programacion = '';
            let tipo = '';
            let curren = $('a[data-id="'+$(e.currentTarget).find('[name=id]').val()+'"]');

            Swal.fire({
                title: 'Guardar',
                text: "¿Está seguro de guardar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si, guardar!',
                cancelButtonText: 'No, cancelar!',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return modelo.guardar(data).then((respuesta) => {
                        return respuesta;
                        // swal(respuesta.titulo, respuesta.mensaje, respuesta.tipo)
                    }).fail((respuesta) => {
                        console.log(respuesta);
                    }).always(() => {
                    });
                },
                // allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: result.value.titulo,
                        text: result.value.mensaje,
                        icon: result.value.tipo,
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar'
                      }).then((resultado) => {
                        if (resultado.isConfirmed) {

                            if (result.value.success) {

                                if (result.value.data.aplica_cambios=="true") {
                                    tipo = 'odi';
                                } else {
                                    tipo = 'ode';
                                }
                                curren.closest('li[data-despacho="'+result.value.data.id+'"]').remove();


                                if ($('[data-action="despachos-'+tipo+'"] [data-tipo="header"][data-fecha="'+result.value.data.fecha_registro+'"]').length==0) {

                                    html_fecha=''
                                    +'<li class="time-label" data-fecha="'+result.value.data.fecha_registro+'" data-tipo="header" data-od="'+tipo+'">'
                                        +'<span class="bg-red">'
                                            +moment(result.value.data.fecha_registro).format('DD/MM/YYYY')
                                        +'</span>'
                                    +'</li>';
                                    // $('[data-action="despachos-'+tipo+'"]').find('[data-tipo="header"]:first').after(html_fecha);
                                    // $('[data-action="despachos-'+tipo+'"]').find('[data-tipo="header"]').before(html_fecha);
                                    $('[data-action="despachos-'+tipo+'"] [data-tipo="header"]:first').before(html_fecha);


                                    // $('[data-action="despachos-'+tipo+'"]').append(html_fecha);
                                }
                                let visible = $('[data-fecha="'+result.value.data.fecha_registro+'"][data-tipo="header"][data-od="'+tipo+'"]').attr('data-visible');
                                if (visible=='true') {
                                    $('[data-fecha="'+result.value.data.fecha_registro+'"][data-tipo="header"][data-od="'+tipo+'"]').trigger('click');
                                }

                                // $('[data-action="despachos-'+tipo+'"]').append(html_fecha);

                                html_programacion='<li data-despacho="'+result.value.data.id+'" data-fecha="'+result.value.data.fecha_registro+'" data-tipo="body" data-od="'+tipo+'">'
                                    +'<i class="fa fa-cube bg-blue"></i>'

                                    +'<div class="timeline-item-despachos">'
                                        +'<span class="time  text-black"><i class="fa fa-calendar-alt"></i> Programado para el '+moment(result.value.data.fecha_programacion).format('DD/MM/YYYY')+'</span>'

                                        +'<h3 class="timeline-header"><a href="#">'+result.value.data.titulo+'</a> </h3>'

                                        +'<div class="timeline-body">'
                                            +result.value.data.descripcion
                                        +'</div>'
                                        +'<div class="timeline-footer">'
                                            +'<a class="btn btn-primary btn-xs editar mr-5" data-id="'+result.value.data.id+'"><i class="fa fa-edit"></i> Editar</a>'
                                            +'<a class="btn btn-danger btn-xs eliminar" data-id="'+result.value.data.id+'" data-despacho="'+tipo+'"><i class="fa fa-trash-alt"></i> Eliminar</a>'
                                        +'</div>'
                                    +'</div>'
                                +'</li>';
                                $('[data-action="despachos-'+tipo+'"] [data-fecha="'+result.value.data.fecha_registro+'"]:last').after(html_programacion);

                                // $('[data-action="despachos-'+tipo+'"] [data-fecha="'+result.value.data.fecha_registro+'"]').last().after(html_programacion);

                            }
                            $('#modal-despachos').modal('hide');
                            // $('#tabla-data').DataTable().ajax.reload(null, false);
                        }
                    })

                }
            })
        });

        $(document).on("click", '.editar', (e) => {
            let id =$(e.currentTarget).attr('data-id');
            let form = $("#guardar");
            $('#modal-despachos').modal('show');
            form[0].reset();
            form.find('[name=id]').val(id);
            this.model.editar(id).then((respuesta) => {
                if (respuesta.success==true) {
                    form.find('[name="titulo"]').val(respuesta.data.titulo);
                    form.find('[name="descripcion"]').val(respuesta.data.descripcion);
                    form.find('[name="fecha_programacion"]').val(respuesta.data.fecha_programacion);

                    form.find('[name="aplica_cambios"]').closest('div').removeClass('checked');
                    form.find('[name="aplica_cambios"]').removeAttr('checked');
                    if (respuesta.data.aplica_cambios) {
                        form.find('[name="aplica_cambios"][value="true"]').attr('checked', 'true');
                        form.find('[name="aplica_cambios"][value="true"]').closest('div').addClass('checked');
                    }else{
                        form.find('[name="aplica_cambios"][value="false"]').attr('checked', 'true');
                        form.find('[name="aplica_cambios"][value="false"]').closest('div').addClass('checked');


                    }
                }
            }).fail((respuesta) => {
                console.log(respuesta);
            }).always((respuesta) => {
            });
        });

        $(document).on("click", 'a.eliminar', (e) => {
            let id = $(e.currentTarget).attr('data-id');
            let modelo = this.model;
            let curren = $(e.currentTarget);
            let tipo = $(e.currentTarget).attr('data-despacho');
            let fecha = $(e.currentTarget).closest('li[data-despacho="'+id+'"]').attr('data-fecha');

            Swal.fire({
                title: 'Alert',
                text: "¿Está seguro de eliminar este registro?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Si, Eliminar!',
                cancelButtonText: 'No, Cancelar!',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return modelo.eliminar(id).then((respuesta) => {
                        return respuesta;
                        // swal(respuesta.titulo, respuesta.mensaje, respuesta.tipo)
                    }).fail((respuesta) => {
                        console.log(respuesta);
                    }).always(() => {
                    });
                },
                // allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: result.value.titulo,
                        text: result.value.mensaje,
                        icon: result.value.tipo,
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar'
                      }).then((resultado) => {
                        if (resultado.isConfirmed) {
                            // $('[data-action="despachos-'+tipo+'"] [data-fecha="'+fecha+'"]')
                            curren.closest('li[data-despacho="'+id+'"]').remove();
                            if ($('[data-action="despachos-'+tipo+'"] [data-fecha="'+fecha+'"]').length===1) {
                                $('[data-action="despachos-'+tipo+'"] [data-fecha="'+fecha+'"]').remove();
                            }
                        }
                    })

                }
            })
        });

        $(document).on('click','li.time-label[data-tipo="header"]',function (e) {
            e.preventDefault();
            let fecha = $(e.currentTarget).attr('data-fecha');
            let od = $(e.currentTarget).attr('data-od');
            let visible = $(e.currentTarget).attr('data-visible');
            console.log('termino');
            if (visible == 'true') {
                $(e.currentTarget).attr('data-visible','false');
            }else{
                $(e.currentTarget).attr('data-visible','true');
            }

            $('[data-fecha="'+fecha+'"][data-tipo="body"][data-od="'+od+'"]').toggle(1000, function() {
                console.log('termino');
            });
        });
    }
}



