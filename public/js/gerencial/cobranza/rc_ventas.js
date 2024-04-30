$(function () {

    actualizarCantidadFiltrosAplicados();

    $('#modal-filtros').find('input[type=checkbox]').change(function () {
        actualizar = true;
    });
    $('#modal-filtros').find('input[type=text], select').change((e) => {
        if ($(e.currentTarget).closest('div.row').find('input[type=checkbox]').is(':checked') == true) {
            actualizar = true;
        }
    });
    $("#modal-filtros").on("hidden.bs.modal", () => {
        if (actualizar) {
            actualizar = false;
            generarFiltros();
        }
        actualizarCantidadFiltrosAplicados();
    });

    $('#modal-observaciones').on("keyup", "input.revisarValidacionIngresoTexto", (e) => {
        revisarValidacionIngresoTexto(e);
    });
    $('#modal-observaciones').on("keyup", "input.actualizarValidacionIngresoTexto", (e) => {
        actualizarValidacionIngresoTexto(e);
    });
    $('#modal-observaciones').on("change", "select.actualizarValidacionIngresoTexto", (e) => {
        actualizarValidacionIngresoTexto(e);
    });
    
    $('#modal-lista-contactos').on("click", "button.seleccionar-contacto", (e) => {
        document.querySelector("div[id='modal-observaciones'] input[id='nombre_contacto']").value=e.currentTarget.dataset.nombreContacto??'';
        document.querySelector("div[id='modal-observaciones'] input[id='telefono_contacto']").value=e.currentTarget.dataset.telefonoContacto??'';
        document.querySelector("div[id='modal-observaciones'] select[id='area_contacto']").value=e.currentTarget.dataset.areaContactoId??'';
        $('#modal-lista-contactos').modal('hide');

    });
    
 
    listar();

    $('#formulario').on('submit', function (e) {
        e.preventDefault();
        let data = $(this).serialize();

        Swal.fire({
            title: 'Guardar cobranza',
            text: "¿Esta seguro de guardar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.guardar-registro-cobranza'),
                    data: data,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status == 200) {
                            $('#tablaCobranza').DataTable().ajax.reload(null, false);
                            $('#modal-cobranza').modal('hide');
                        }
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
                return false;
            }
        });
    });

    $('#formulario-fase').on('submit', function (e) {
        e.preventDefault();
        let data = $(this).serialize();

        Swal.fire({
            title: 'Guardar fase',
            text: "¿Esta seguro de guardar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.guardar-fase'),
                    data: data,
                    dataType: 'JSON',
                    success: function(response) {
                        listarFases(response.data.id_registro_cobranza);
                        $('#tablaCobranza').DataTable().ajax.reload(null, false);
                        $('#formulario-fase')[0].reset();
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
                return false;
            }
        });
    });

    $('#formulario-observaciones').on('submit', function (e) {
        e.preventDefault();
        // let data = $(this).serialize();
        let esValido=validarFormularioListaObservaciones();
        console.log(esValido);

        if(esValido==true){
            let formData = new FormData($('#formulario-observaciones')[0]);

            Array.prototype.forEach.call(document.querySelector("input[id='adjunto']").files, (file) => {
                formData.append(`archivo_adjunto[]`, file);
            });
    
            Swal.fire({
                title: 'Guardar observación',
                text: "¿Esta seguro de guardar este registro?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: route('gerencial.cobranza.guardar-observaciones'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response) {
                            listarObservaciones(response.data.cobranza_id);
                            $('#formulario-observaciones')[0].reset();
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }).fail( function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    });
                    return false;
                }
            });
        }else{
            Lobibox.notify('warning', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: 'Por favor ingrese los datos faltantes en el formulario.'
            });
        }



    });

    $('#formulario-penalidad').on('submit', function (e) {
        e.preventDefault();
        let data = $(this).serialize();
        let evento = $(this).data('formulario');

        Swal.fire({
            title: 'Guardar ' + evento,
            text: "¿Esta seguro de guardar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.guardar-penalidad'),
                    data: data,
                    dataType: 'JSON',
                    success: function(response) {
                        listarAcciones(response.data.id_registro_cobranza, (response.data.tipo).toLowerCase());
                        $('#formulario-penalidad')[0].reset();
                        $('[name="id_cobranza"]').val(response.data.id_registro_cobranza);
                        $('[name=tipo_registro]').val((response.data.tipo).toLowerCase());
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
                return false;
            }
        });
    });

    $('#tablaCobranza').on('click', 'a.editar', function (e) {
        e.preventDefault();
        $("#formulario")[0].reset();
        $('[name="vendedor"]').val(null).trigger('change');

        $.ajax({
            type: 'POST',
            url: route('gerencial.cobranza.editar-registro'),
            data: {id: $(e.currentTarget).data('id')},
            dataType: 'JSON',
            success: function(response) {
                console.log(response);
                let datos = response.data;
                let clientes = response.cliente;
                let pagos = response.programacion_pago;
                $("[name=id]").val(response.id);
                $('[name="empresa"] option').removeAttr('selected');
                $('[name="empresa"] option[value="'+datos.id_empresa+'"]').attr('selected','true');
                $('[name="sector"] option').removeAttr('selected');
                $('[name="sector"] option[value="'+datos.id_sector+'"]').attr('selected','true');
                $('[name="tramite"] option').removeAttr('selected');
                $('[name="tramite"] option[value="'+datos.id_tipo_tramite+'"]').attr('selected','true');

                $('[name="periodo"] option').removeAttr('selected');
                $('[name="periodo"] option[value="'+datos.id_periodo+'"]').attr('selected','true');

                $('[name="id_cliente"]').val(datos.id_cliente);
                if (clientes) {
                    $('[name="cliente"]').val(clientes.razon_social);
                }

                $('[name="cdp"]').val(datos.cdp);
                $('[name="oc"]').val(datos.ocam);
                $('[name="orden_compra"]').val(datos.oc_fisica);
                $('[name="fact"]').val(datos.factura);
                $('[name="siaf"]').val(datos.siaf);
                $('[name="ue"]').val(datos.uu_ee);
                $('[name="ff"]').val(datos.fuente_financ);
                $('[name="moneda"] option').removeAttr('selected');
                $('[name="moneda"] option[value="'+ datos.moneda +'"]').attr('selected','true');
                $('[name="importe"]').val(datos.importe);
                $('[name="categ"]').val(datos.categoria);
                $('[name="fecha_emi"]').val(datos.fecha_emision);
                $('[name="fecha_rec"]').val(datos.fecha_recepcion);
                $('[name="estado_doc"] option').removeAttr('selected');
                $('[name="estado_doc"] option[value="'+ datos.id_estado_doc +'"]').attr('selected','true');

                if (pagos) {
                    $('[name="fecha_ppago"]').val(pagos.fecha);
                }
                diasAtraso();

                $('[name="plazo_credito"]').val(datos.plazo_credito);
                $('[name="area"] option').removeAttr('selected');
                $('[name="area"] option[value="'+ datos.id_area +'"]').attr('selected','true');
                $('[name="fecha_inicio"]').val(datos.inicio_entrega);
                $('[name="fecha_entrega"]').val(datos.fecha_entrega);
                $('[name="vendedor"]').val(datos.vendedor).trigger('change');

                $('[name="id_doc_ven"]').val(datos.id_doc_ven);
                $('[name="id"]').val(datos.id_registro_cobranza);

                $("#modal-cobranza").find(".modal-title").text("Editar el registro de Cobranza");
                $('#modal-cobranza').modal('show');
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        })
    });

    $('#tablaCobranza').on('click', 'a.eliminar', function (e) {
        let id = $(e.currentTarget).data('id');
        Swal.fire({
            title: '¿Desea eliminar la fase?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.eliminar-registro-cobranza'),
                    data: {id: id},
                    dataType: 'JSON',
                    success: function(response) {
                        $('#tablaCobranza').DataTable().ajax.reload(null, false);
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        });
    });

    $('#tablaCobranza').on('click', 'a.fases', function (e) {
        e.preventDefault();
        $("#formulario-fase")[0].reset();
        $('[name="id_registro_cobranza"]').val($(e.currentTarget).data('id'));
        listarFases($(e.currentTarget).data('id'));
    });

    $('#tablaCobranza').on('click', 'a.acciones', function (e) {
        e.preventDefault();
        $("#formulario-penalidad")[0].reset();
        $('[name="id_cobranza"]').val(0);
        $('[name=tipo_registro]').val('');

        listarAcciones($(e.currentTarget).data('id'), $(e.currentTarget).data('accion'))
    });

    $('#tablaCobranza').on('click', 'a.observaciones', function (e) {
        e.preventDefault();
        $("#formulario-observaciones")[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $('[name="cobranza_id"]').val($(e.currentTarget).data('id'));
        listarObservaciones($(e.currentTarget).data('id'));
    });

    $('#tablaClientes tbody').on('click', 'tr', function(e) {
        if ($(this).hasClass('eventClick')){
            $(this).removeClass('eventClick');
        } else {
            $('#tablaClientes').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        idCliente = $(this).find("td:eq(0)").text();
        nombreCliente = $(this).find("td:eq(2)").text();
    });

    $('#tablaVentasProcesadas tbody').on('click', 'tr', function(e) {
        if ($(this).hasClass('eventClick')){
            $(this).removeClass('eventClick');
        } else {
            $('#tablaVentasProcesadas').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        idRequerimiento = $(this).find("td:eq(0)").text();
    });

    $('#btnAgregarCliente').on('click', function (e) {
        if (idCliente > 0) {
            $('[name=id_cliente]').val(idCliente);
            $('[name=cliente]').val(nombreCliente);
            $('#modal-lista-cliente').modal('hide');
            idCliente = 0;
            nombreCliente = '';
        } else {
            Util.notify("info", "Debe seleccionar un cliente");
        }
    });

    $('#btnAgregarMgc').on('click', function (e) {

        if (idRequerimiento > 0) {
            cargarValores(idRequerimiento);
            idRequerimiento = 0;
            $('#lista-procesadas').modal('hide');
        } else {
            Util.notify("info", "Debe seleccionar una venta");
        }
    });

    $('.buscarMgc').on('click', function (e) {
        let tipo = $(e.currentTarget).data('action');
        let valor;
        if (tipo == 'cdp') {
            valor = $('#cdp').val();
        } else {
            valor = $('#oc').val();
        }
        buscarRegistro(tipo, valor);
    });

    $('#btnAgregarFuente').on('click', function (e) {
        let fuente = $('#fuente').val();
        let rubro = $('#rubro').val();
        let text = fuente.concat('-', rubro);
        $('[name="ff"]').val(text);
        $('#modal-fue-fin').modal('hide');
    });

    $('.dias-atraso').on('change', function (e) {
        diasAtraso();
    });

    $('#resultadoFase').on('click', '.eliminar-fase', function (e) {
        let id = $(e.currentTarget).data('id');
        let idCobranza = $(e.currentTarget).data('cobranza');
        Swal.fire({
            title: '¿Desea eliminar la fase?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.eliminar-fase'),
                    data: {id: id},
                    dataType: 'JSON',
                    success: function(response) {
                        listarFases(idCobranza);
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        });
    });

    $('#resultadoObservaciones').on('click', '.ver-mas-observacion', function (e) {
        // let id = $(e.currentTarget).data('id');
        // let idCobranza = $(e.currentTarget).data('cobranza');
        let telefonoContacto = $(e.currentTarget).data('telefono-contacto');
        let nombreContacto = $(e.currentTarget).data('nombre-contacto');
        let areaContacto = $(e.currentTarget).data('area-contacto');
        let resultado='';
        if (telefonoContacto !=null && ((telefonoContacto).toString()).length > 0) {
                resultado += `<tr>
                    <td class="text-center">`+ nombreContacto +`</td>
                    <td class="text-center">`+ telefonoContacto +`</td>
                    <td class="text-center">`+ areaContacto +`</td>
                </tr>`;
        } else {
            resultado = '<tr><td colspan="3">No se encontraron resultados</td></tr>';
        }
        $('#verMasResultadoObservaciones').html(resultado);
        $('#modal-ver-mas').modal('show');

    });

    $('#resultadoObservaciones').on('click', '.eliminar-observacion', function (e) {
        let id = $(e.currentTarget).data('id');
        let idCobranza = $(e.currentTarget).data('cobranza');
        Swal.fire({
            title: '¿Desea eliminar la fase?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: route('gerencial.cobranza.eliminar-observacion'),
                    data: {id: id, id_registro_cobranza: idCobranza},
                    dataType: 'JSON',
                    success: function(response) {
                        listarObservaciones(idCobranza);
                    }
                }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        });
    });

    $("#tablaPenalidad").on('click','.estados', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let tipo = $('[name="tipo_registro"]').val();
        let estado = $(this).attr('data-evento');
        let id_registro_cobranza = $(this).attr('data-cobranza');

        if (estado == 'DEVOLUCION') {
            Swal.fire({
                title: '¿Quién es responsable de la devolución?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Entidad',
                denyButtonText: 'Marca',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    estadoPenalidad(tipo, id, id_registro_cobranza, estado, 'ENTIDAD');
                } else if (result.isDenied) {
                    estadoPenalidad(tipo, id, id_registro_cobranza, estado, 'MARCA');
                }
            });
        } else {
            console.log(tipo+'-'+id+'-'+id_registro_cobranza);
            estadoPenalidad(tipo, id, id_registro_cobranza, estado);
        }

    });
});

function actualizarCantidadFiltrosAplicados() {
    spanFiltro = $('#modal-filtros').find('input[type=checkbox]:checked').length;
}

function listar() {
    const button_nuevo_registro=(array_accesos.find(element => element === 309)?{
        text: '<i class="fas fa-plus"></i> Nuevo registro',
        action: () => {
            $('#formulario')[0].reset();
            $('#id').val(0);
            $('.selectpicker').val(null).trigger('change');
            // $('[name="vendedor"]').val(null).trigger('change');
            $("#modal-cobranza").find(".modal-title").text("Nuevo el registro de Cobranza");
            $('#modal-cobranza').modal('show');
        },
        className: 'btn btn-primary btn-sm',
        init: function(api, node, config) {
            $(node).removeClass('btn-default')
        }
    }:[]);
    const button_descargar_excel=(array_accesos.find(element => element === 311)?{
        text: '<i class="fas fa-file-excel"></i> Descargar',
        action: () => {
            exportarExcel();
        },
        className: 'btn btn-success btn-sm',
        init: function(api, node, config) {
            $(node).removeClass('btn-default')
        }
    }:[]);

    const $tabla = $('#tablaCobranza').DataTable({
        dom: 'Bfrtip',
        pageLength: 30,
        language: idioma,
        serverSide: true,
        destroy: true,
        initComplete: function (settings, json) {
            const $filter = $('#tablaCobranza_filter');
            const $input = $filter.find('input');
            $filter.append('<button id="btnBuscar" class="btn btn-default btn-sm pull-right" type="button"><i class="fas fa-search"></i></button>');
            $input.off();
            /**
             * Buscador general
             */
            $input.on('keyup', (e) => {
                if (e.key == 'Enter') {
                    $('#btnBuscar').trigger('click');
                }
            });
            $('#btnBuscar').on('click', (e) => {
                $tabla.search($input.val()).draw();
            });

            /**
             * Combo para el periodo
             */
            let div = $('#tablaCobranza_wrapper');
            let combo = periodoCombo();
            div.find("#tablaCobranza_filter").prepend(`<label>Periodo:` + combo + `</label>`);
            this.api().column(10).each(function() {
                $('#nombrePeriodo').on('change', function() {
                    periodoActivo = $(this).val();
                    generarFiltros();
                });
            });
        },
        drawCallback: function (settings) {
            $('#tablaCobranza_filter input').prop('disabled', false);
            $('#btnBuscar').html('<i class="fas fa-search"></i>').prop('disabled', false);
            $('#tablaCobranza_filter input').trigger('focus');
        },
        order: [[0, 'asc']],
        ajax: {
            url: route('gerencial.cobranza.listar'),
            method: 'POST',
            headers: {'X-CSRF-TOKEN': token},
        },
        columns: [
            {data: 'semaforo', className: "text-center", searchable: false, orderable: false},
            {data: 'empresa', className: "text-center"},
            {data: 'ocam', className: "text-center"},
            {data: 'cliente'},
            {data: 'factura', className: "text-center"},
            {data: 'uu_ee', className: "text-center"},
            {data: 'fuente_financ', className: "text-center"},
            {data: 'oc_fisica', className: "text-center"},
            {data: 'siaf', className: "text-center"},
            {data: 'fecha_emision', className: "text-center"},
            {data: 'fecha_recepcion', className: "text-center"},
            {data: 'periodo', className: "text-center"},
            {data: 'atraso', className: "text-center"},
            {data: 'moneda', className: "text-center"},
            {data: 'importe', className: "text-right"},
            {data: 'estado_cobranza', className: "text-center"},
            {data: 'area', className: "text-center"},
            {data: 'fase', className: "text-center", searchable: false, orderable: false},
            {
                render: function (data, type, row) {
                    var fecha_inicio = row['inicio_entrega'] ? row['inicio_entrega']:'-';
                    var fecha_entrega = row['fecha_entrega'] ? row['fecha_entrega']:'-';
                    return (`${fecha_inicio} <br> ${fecha_entrega}`);
                },
                className: "text-center", searchable: false, orderable: false
            },
            {data: 'fecha_entrega_real', className: "text-center", searchable: false, orderable: false},
            {data: 'accion', className: "text-center", searchable: false, orderable: false},
        ],
        buttons: [
            {
                text: '<i class="fas fa-filter"></i> Filtros <span class="badge badge-secondary right" id="spanCantFiltros">'+ spanFiltro +'</span>',
                action: () => {
                    $('#modal-filtros').modal('show');
                }, className: 'btn btn-default btn-sm'
            },
            button_descargar_excel
            ,
            // {
            //     text: '<i class="fas fa-file-excel"></i> Descargar Power BI',
            //     action: () => {
            //         exportarExcelPowerBi();
            //     }, className: 'btn-default btn-sm'
            // },
            button_nuevo_registro,
        ],
        rowCallback: function(row, data) {
            if (data.id_oc == null) {
                $($(row).find("td")[1]).addClass('flag-rojo');
            }

            if (data.tiene_penalidad) {
                $($(row).find("td")[16]).addClass('flag-amarillo');
            }
        },
    });
    $tabla.on('search.dt', function() {
        $('#tablaCobranza_filter input').attr('disabled', true);
        $('#btnBuscar').html('<i class="fas fa-clock" aria-hidden="true"></i>').prop('disabled', true);
    });
    $tabla.on('init.dt', function(e, settings, processing) {
        $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
    });
    $tabla.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        } else {
            $(e.currentTarget).LoadingOverlay("hide", true);
        }
    });
}

function listaClientes() {
    $('#modal-lista-cliente').modal('show');
    const $tablaCliente = $("#tablaClientes").DataTable({
        language: idioma,
        pageLength: 15,
        destroy: true,
        serverSide: true,
        ajax: {
            url: route('gerencial.cobranza.listar-clientes'),
            type: "POST",
            headers: {'X-CSRF-TOKEN': token},
        },
        order: [[3, "asc"]],
        columns: [
            {data: 'id'},
            {data: 'id_contribuyente', visible: false},
            {data: 'documento'},
            {data: 'nombre'},
        ],
    });
    $tablaCliente.on('init.dt', function(e, settings, processing) {
        $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
    });
    $tablaCliente.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        } else {
            $(e.currentTarget).LoadingOverlay("hide", true);
        }
    });
}

function buscarRegistro(tipo, valor) {
    $('#lista-procesadas').modal('show');
    // console.log(token);
    const $tablaVenta = $("#tablaVentasProcesadas").DataTable({
        language: idioma,
        pageLength: 15,
        destroy: true,
        serverSide: true,
        ajax: {
            url: route('gerencial.cobranza.buscar-registro'),
            type: "POST",
            data: { valor: valor, tipo: tipo },
            headers: {'X-CSRF-TOKEN': token},
        },
        order: [[2, "asc"]],
        columns: [
            {data: 'id'},
            {data: 'nro_orden'},
            {data: 'codigo_oportunidad'},
            {data: 'documento'},
            // {data: 'inicio_entrega'},
            { className: "text-center selecionar",
                render: function (data, type, row) {
                    return ('<p>'+row['inicio_entrega']+'</p>'+'<p>'+row['fecha_entrega']+'</p>')
                }
            },
        ],
    });
    $tablaVenta.on('init.dt', function(e, settings, processing) {
        $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
    });
    $tablaVenta.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        } else {
            $(e.currentTarget).LoadingOverlay("hide", true);
        }
    });
}

function cargarValores(idReq) {
    $.ajax({
        type: 'GET',
        url: route('gerencial.cobranza.seleccionar-registro', {id: idReq}),
        dataType: 'JSON',
        success: function(response) {
            console.log(response);
            if (response.status == 200) {
                $('[name="moneda"]').removeAttr('selected');
                if (response.data.moneda_oc==='s') {
                    $('[name="moneda"] option[value="1"]').attr('selected', 'true');
                }
                if (response.data.moneda_oc==='d') {
                    $('[name="moneda"] option[value="2"]').attr('selected', 'true');
                }

                $('[name="importe"]').val(response.data.monto_total);
                $('[name="fecha_emi"]').val(response.data.fecha_salida);
                $('[name="oc"]').val(response.data.nro_orden);
                $('[name="cdp"]').val(response.data.codigo_oportunidad);

                if (response.data.factura && response.data.factura) {
                    $('[name="fact"]').val(response.data.factura);
                }

                $('[name="empresa"]').removeAttr('selected');
                $('[name="fecha_inicio"]').val(response.data.inicio_entrega);
                $('[name="fecha_entrega"]').val(response.data.fecha_entrega);
                $('[name="id_oc"]').val(response.data.id);

                $('[name="orden_compra"]').val(response.data.orden_compra);
                $('[name="siaf"]').val(response.data.siaf);
                console.log(response.data);
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function buscarFuente() {
    $('#modal-fue-fin').modal('show');
    $('#modal-fue-fin').on('shown.bs.modal', function(){
        $('[name=fuente]').select();
    });
}

function fuenteFinan(valor) {
    let opcion = '<option value="" disabled selected>Elija una opción</option>';
    $('#rubro').empty();
    if (valor == 1){
        opcion += '<option value="00">RECURSOS ORDINARIOS</option>';
    } else if (valor == 2){
        opcion += '<option value="09">RECURSOS DIRECTAMENTE RECAUDADOS</option>';
    } else if (valor == 3){
        opcion += '<option value="19">RECURSOS POR OPERACIONES OFICIALES DE CREDITO</option>';
    } else if (valor == 4){
        opcion += '<option value="13">DONACIONES Y TRANSFERENCIAS</option>';
    } else if (valor == 5){
        opcion += `<option value="04">CONTRIBUCIONES A FONDOS</option>
        <option value="07">FONDO DE COMPENSACION MUNICIPAL</option>
        <option value="08">IMPUESTOS MUNICIPALES</option>
        <option value="15">FONDO DE COMPENSACION REGIONAL</option>
        <option value="18">CANON Y SOBRECANON, REGALIAS, RENTA DE ADUANAS Y PARTICIPACIONES</option>`;
    }
    $('#rubro').append(opcion);
}

function listarFases(id) {
    let resultado = '';
    $.ajax({
        type: 'GET',
        url: route('gerencial.cobranza.obtener-fases', {id: id}),
        dataType: 'JSON',
        success: function(response) {
            let datos = response.fases;
            if (response.status == 200) {
                if (datos.length > 0) {
                    datos.forEach(element => {
                        resultado += `<tr>
                            <td class="text-center">`+ element.fase +`</td>
                            <td class="text-center">`+ element.fecha +`</td>
                            <td class="text-center"><button class="btn btn-xs btn-danger eliminar-fase" data-id="`+ element.id +`" data-cobranza="`+ element.id_registro_cobranza +`"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
                    });
                } else {
                    resultado += '<tr><td colspan="3">No se encontraron resultados</td></tr>';
                }
            } else {
                resultado += '<tr><td colspan="3">No se encontraron resultados</td></tr>';
            }
            $('#resultadoFase').html(resultado);
            $('#modal-agregar-fase').modal('show');
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function listarObservaciones(id) {
    let resultado = '';
    $.ajax({
        type: 'GET',
        url: route('gerencial.cobranza.obtener-observaciones', {id: id}),
        dataType: 'JSON',
        success: function(response) {
            let datosObservaciones = response.observaciones;
            let datosGuia = response.guia;
            if (response.status == 200) {
                if (datosGuia.hasOwnProperty('fecha_entrega_real') && datosGuia.hasOwnProperty('adjunto')) {
                    resultado += `<tr>
                    <td class="text-center"></td>
                    <td class="text-center">${datosGuia.estado_entrega}</td>
                    <td class="text-center"><a href="/files/almacen/trazabilidad_envio/${datosGuia.adjunto}" target="_blank">${datosGuia.adjunto}</a></td>
                    <td class="text-center">${(datosGuia.fecha_entrega_real??'')}</td>
                    <td class="text-center">Guía</td>
                    <td class="text-center"></td>
                    <td class="text-center">${(datosGuia.fecha_registro??'')}</td>
                    <td class="text-center"></td>
                </tr>`;
                }

                if (datosObservaciones.length > 0) {
                    let linkAdjuntos='';
                    datosObservaciones.forEach(element => {
                        let fechaRegistro = moment(element.created_at).format("MM-DD-YY");
                        let usuario = (element.usuario_id != null) ? element.usuario.nombre_corto : '-';
                         
                        element.adjunto.forEach(adj => {
                            linkAdjuntos += `<a href="/files/cobranzas/${adj.archivo}" target="_blank">${adj.archivo}</a>`;
                        });

                        resultado += `<tr>
                            <td class="text-center">`+ (element.fecha_observacion??'') +`</td>
                            <td class="text-center">`+ (element.estado_documento!=null? element.estado_documento.nombre:'') +`</td>
                            <td class="text-center">`+ linkAdjuntos +`</td>
                            <td class="text-center">`+ (element.fecha_documento??'') +`</td>
                            <td class="text-center">`+ (element.descripcion??'') +`</td>
                            <td class="text-center">`+ (usuario??'') +`</td>
                            <td class="text-center">`+ (fechaRegistro??'') +`</td>
                            <td class="text-center">
                                <div style="display:flex; flex-direction:row;">
                                <button class="btn btn-xs btn-${(element.telefono_contacto!=null && element.telefono_contacto.length>0)?'info':'default'} ver-mas-observacion" 
                                    data-id="`+ element.id +`" 
                                    data-cobranza="`+ element.cobranza_id +`"
                                    data-telefono-contacto="`+ (element.telefono_contacto??'') +`"
                                    data-nombre-contacto="`+ (element.nombre_contacto??'') +`"
                                    data-area-contacto="`+ (element.area_contacto!=null?element.area_contacto.nombre:'') +`"
                                    ><i class="fa fa-eye"></i></button> 
                                <button class="btn btn-xs btn-danger eliminar-observacion" data-id="`+ element.id +`" data-cobranza="`+ element.cobranza_id +`"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>`;
                    });
                } 

                if(datosObservaciones.length==0 && (datosGuia.hasOwnProperty('fecha_entrega_real')==false && datosGuia.hasOwnProperty('adjunto')==false)) {
                    resultado = '<tr><td colspan="5">No se encontraron resultados</td></tr>';
                }
            } else {
                resultado = '<tr><td colspan="5">No se encontraron resultados</td></tr>';
            }
            $('#resultadoObservaciones').html(resultado);
            $('#modal-observaciones').modal('show');
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function listarAcciones(id, tipo) {
    let resultado = '';
    let totalCol = (tipo == 'penalidad') ? 7 : 6;
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        url: route('gerencial.cobranza.obtener-penalidades'),
        data: {tipo: tipo, id: id},
        dataType: 'JSON',
        success: function(response) {
            let datos = response.penalidades;
            if (response.status == 200) {
                if (datos.length > 0) {
                    datos.forEach(element => {
                        let fecha = moment(element.fecha).format("DD-MM-YYYY");
                        let estado = (element.estado == 1) ? 'ELABORADO' : 'ANULADO';
                        let estado_pen = (element.estado_penalidad != null) ? element.estado_penalidad : '';
                        let monto = $.number(element.monto, 2);
                        let opcion = '';

                        if (tipo == 'penalidad') {
                            if (estado_pen != 'DEVOLUCION' || estado_pen != 'ANULADA') {
                                opcion +=
                                `<button class="btn btn-xs btn-success estados" data-id="`+ element.id_penalidad +`" data-cobranza="`+ element.id_registro_cobranza +`" data-evento="DEVOLUCION" title="Devolución"><i class="fa fa-exchange-alt"></i></button>
                                <button class="btn btn-xs btn-warning estados" data-id="`+ element.id_penalidad +`" data-cobranza="`+ element.id_registro_cobranza +`" data-evento="ANULADA" title="Anular"><i class="fa fa-ban"></i></button>`;
                            }
                        }

                        if (estado_pen != 'DEVOLUCION' || estado_pen != 'ANULADA') {
                            opcion += `<button class="btn btn-xs btn-primary" data-action="editar-penalidad" data-id="`+ element.id_penalidad +`" data-cobranza="`+ element.id_registro_cobranza +`" title="Editar"><i class="fa fa-edit"></i></button>`;
                        }

                        opcion += `<button class="btn btn-xs btn-danger" data-action="eliminar-penalidad" data-id="`+ element.id_penalidad +`"data-cobranza="`+ element.id_registro_cobranza +`" title="Eliminar" data-estado="7"><i class="fa fa-times"></i></button>`;
                        resultado += `<tr>
                            <td class="text-center">`+ element.tipo +`</td>
                            <td class="text-center">`+ element.documento +`</td>
                            <td class="text-center">`+ monto +`</td>
                            <td class="text-center">`+ estado +`</td>
                            <td class="text-center" `+ (tipo != 'penalidad' ? 'hidden' : '') +`>`+ estado_pen +`</td>
                            <td class="text-center">`+ fecha +`</td>
                            <td class="text-center">`+ opcion +`</td>
                        </tr>`;
                    });
                } else {
                    resultado = `<tr><td colspan="`+ totalCol +`">No se encontraron resultados</td></tr>`;
                }
            } else {
                resultado = `<tr><td colspan="`+ totalCol +`">No se encontraron resultados</td></tr>`;
            }

            if (tipo != 'penalidad') {
                $('.estado-penalidad').attr('hidden', 'true');
            } else {
                $('.estado-penalidad').removeAttr('hidden');
            }

            $('#resultadoPenalidades').html(resultado);
            $('#modal-penalidad .modal-title').text('Registro de  ' + tipo);
            $('#modal-penalidad .titulo-form').text('1° Historial de ' + tipo + 'es');
            $('#formulario-penalidad').data('formulario', tipo);
            $('[name="id_cobranza"]').val(id);
            $('[name=tipo_registro]').val(tipo);
            $('#modal-penalidad #btnPenalidad').text('Grabar ' + tipo);
            $('#modal-penalidad').modal('show');
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function estadoPenalidad(tipo, id, id_registro_cobranza, estado_penalidad, gestion = '') {
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        url: route('gerencial.cobranza.cambio-estado-penalidad'),
        data: {tipo: tipo, id: id, id_registro_cobranza: id_registro_cobranza, estado_penalidad: estado_penalidad, gestion: gestion},
        dataType: 'JSON',
        success: function(response) {
            listarAcciones(id_registro_cobranza, 'penalidad');
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function periodoCombo() {
    let option = '<select class="form-control input-sm select-filter" id="nombrePeriodo">';
    periodoSelect.forEach(element => {
        let selected = (element.descripcion == periodoActivo) ? 'selected' : '';
        option += `<option value="`+ element.descripcion +`" `+ selected +`>`+ element.descripcion +`</option>`;
    });
    option += '</select>';
    return option;
}

function generarFiltros() {
    let data = $('#form-filtros').serializeArray();
    data.push({ name: 'filterPeriodo', value: $('#nombrePeriodo').val() });
    $.ajax({
        type: 'POST',
        url: route('gerencial.cobranza.filtros-cobranzas'),
        data: data,
        dataType: 'JSON',
        success: function (response) {
            listar();
        }
    }).fail( function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function diasAtraso() {
    let fecha_emision = new Date($('[name="fecha_rec"]').val().split('/').reverse().join('-')).getTime();
    let fecha_vencimiento = new Date($('[name="fecha_ppago"]').val().split('/').reverse().join('-')).getTime();
    let numero_dias = 0;

    numero_dias = fecha_vencimiento - fecha_emision  ;
    numero_dias = numero_dias / (1000 * 60 * 60 * 24)
    numero_dias = numero_dias * (-1);
    if (numero_dias <= 0) {
        numero_dias = 0;
    }

    let fecha_actual = new Date().getTime();
    let atraso = fecha_actual - fecha_emision;
    atraso = atraso / (1000 * 60 * 60 * 24);
    let diasAtraso = (atraso > 0) ? atraso = Math.trunc(atraso) : atraso = 0;

    $('[name="atraso"]').val(diasAtraso);
    $('[name="dias_atraso"]').val(diasAtraso);
}

function exportarExcel() {
    window.open('exportar-excel');
}

$("#tablaPenalidad").on('click','[data-action="editar-penalidad"]',function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'editar-penalidad/'+id,
        data: {},
        dataType: 'JSON'
    }).done(function( data ) {

        $('#formulario-penalidad').find('[name="fecha_penal"]').val(data.fecha);
        $('#formulario-penalidad').find('[name="doc_penal"]').val(data.documento);
        $('#formulario-penalidad').find('[name="importe_penal"]').val(data.monto);
        $('#formulario-penalidad').find('[name="obs_penal"]').val(data.observacion);
        $('#formulario-penalidad').find('[name="id_penalidad"]').val(data.id_penalidad);
        $('#formulario-penalidad').find('[name="id_cobranza"]').val(data.id_registro_cobranza);
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    })
});
$("#tablaPenalidad").on('click','[data-action="eliminar-penalidad"]',function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id'),
        titulo =$('[data-form="guardar-penalidad"]').find('[name="tipo_penal"]').val(),
        id_registro_cobranza =$(this).attr('data-cobranza'),
        estado = $(this).attr('data-estado');
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'eliminar-penalidad',
        data: {tipo:titulo,id:id,id_registro_cobranza:id_registro_cobranza,estado:estado},
        dataType: 'JSON'
    }).done(function( data ) {
        // obtenerPenalidades(id_registro_cobranza, 'PENALIDAD');
        // listarAcciones(id_registro_cobranza, 'penalidad');
        listarAcciones(id_registro_cobranza, 'PENALIDAD');
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    })
});
$('.nuevo-vendedor').click(function (e) {
    e.preventDefault();
    $('#modal-vendedor').modal('show');
    $('#modal-vendedor').find('[name="id"]').val(0);
    $('#modal-vendedor').find('.modal-title').text('Nuevo Vendedor');
});
$('#formulario-vendedor').submit(function (e) {
    e.preventDefault();
    let data = $(this).serialize();


    // $('.selectpicker[name="vendedor"]').selectpicker('destroy');
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: route("gerencial.cobranza.guardar-vededor"),
        data: data,
        dataType: 'JSON'
    }).done(function( jqs ) {
        $('#modal-vendedor').modal('hide');
        var newOption = new Option(jqs.nombre, jqs.id_vendedor, false, false);
        $('[name="vendedor"]').append(newOption).trigger('change');
        $('.selectpicker[name="vendedor"]').selectpicker('refresh');
        $('.selectpicker[name="vendedor"]').val(jqs.id_vendedor);
        $('.selectpicker[name="vendedor"]').selectpicker('render');

    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    })
});


function validarFormularioListaObservaciones(){
    let validoEstado=true;
    let validoNombre=true;
    let validoArea=true;

    if (document.querySelector("select[name='estado']").value == '') {
        validoEstado = false;
    }
    

    if (document.querySelector("input[name='telefono_contacto']").value != '') {
        if (document.querySelector("input[name='nombre_contacto']").value == '') {
            validoNombre = false;
        }
        if (document.querySelector("select[name='area_contacto']").value == '') {
            validoArea = false;
        }
    }

    if(validoEstado ==false ){
            let newSpanInfo = document.createElement("span");
            newSpanInfo.classList.add('text-danger');
            newSpanInfo.textContent = '(Seleccione un estado)';
            if( document.querySelector("select[name='estado']").closest('div[class="form-group"]')!=null){
                document.querySelector("select[name='estado']").closest('div[class="form-group"]').querySelector("label").appendChild(newSpanInfo);
                document.querySelector("select[name='estado']").closest('div[class="form-group"]').classList.add('has-warning');
            }

    }
    if(validoNombre ==false ){
            let newSpanInfo = document.createElement("span");
            newSpanInfo.classList.add('text-danger');
            newSpanInfo.textContent = '(Ingrese el nombre del contacto)';
            document.querySelector("input[name='nombre_contacto']").closest('div').querySelector("label").appendChild(newSpanInfo);
            document.querySelector("input[name='nombre_contacto']").closest('div').classList.add('has-warning');

    }
    if(validoArea ==false ){
            let newSpanInfo = document.createElement("span");
            newSpanInfo.classList.add('text-danger');
            newSpanInfo.textContent = '(Ingrese el área del contacto)';
            document.querySelector("select[name='area_contacto']").closest('div').querySelector("label").appendChild(newSpanInfo);
            document.querySelector("select[name='area_contacto']").closest('div').classList.add('has-warning');

    }
    return (validoNombre*validoArea * validoEstado);
    
}


function actualizarValidacionIngresoTexto (obj) {
    if (obj.target.value.length > 0) {
         obj.target.closest('div[class~="form-group"]').classList.remove("has-warning");
        if (obj.target.closest('div[class~="form-group"]').querySelector("span")) {
            obj.target.closest('div[class~="form-group"]').querySelector("span").remove();
        }
    } else {
        obj.target.closest('div[class~="form-group"]').classList.add("has-warning");
    }
}

function revisarValidacionIngresoTexto(obj){
    if (obj.target.value.length > 0) {
        if(document.querySelector("input[name='nombre_contacto']").value ==""){
            document.querySelector("input[name='nombre_contacto']").closest('div').classList.add('has-warning');

        }
        if(document.querySelector("select[name='area_contacto']").value == ""){
            document.querySelector("select[name='area_contacto']").closest('div').classList.add('has-warning');
        }
    }

}


function listaContactoModal(){
    $('#modal-lista-contactos').modal('show');
    // console.log(token);

    let cobranza_id= document.querySelector("div[id='modal-observaciones'] input[name='cobranza_id']").value;
    const $tablaContacto = $("#tablaContacto").DataTable({
        language: idioma,
        pageLength: 15,
        destroy: true,
        serverSide: true,
        ajax: {
            url: route('gerencial.cobranza.buscar-contacto'),
            type: "POST",
            data: { cobranza_id: cobranza_id },
            headers: {'X-CSRF-TOKEN': token},
        },
        order: [[0, "asc"]],
        columns: [
            {data: 'nombre_contacto'},
            {data: 'telefono_contacto'},
            {data: 'area_contacto.nombre'},
            { className: "text-center selecionar",
                render: function (data, type, row) {
                    return `<button class="btn btn-xs btn-success seleccionar-contacto" 
                    data-nombre-contacto="`+ row.nombre_contacto +`"
                    data-telefono-contacto="`+ row.telefono_contacto +`"
                    data-area-contacto-descripcion="`+ (row.area_contacto !=null?row.area_contacto.nombre:'') +`"
                    data-area-contacto-id="`+ (row.area_contacto_id??'' ) +`"
                    >Seleccionar</button>`;
                }
            },
        ],
    });
    $tablaContacto.on('init.dt', function(e, settings, processing) {
        $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
    });
    $tablaContacto.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            $(e.currentTarget).LoadingOverlay('show', { imageAutoResize: true, progress: true, imageColor: '#3c8dbc' });
        } else {
            $(e.currentTarget).LoadingOverlay("hide", true);
        }
    });
}