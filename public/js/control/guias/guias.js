const data_filtros = {
    _token: token,
    empresa_id:'',
    estado:'',
    fecha_inicio:'',
    fecha_final:''
}

$(document).ready(function() {
    $(".sidebar-mini").addClass("sidenav-toggled");
    $(".numero").number(true, 2);

    listar();

    $(".select-sede").select2({
        placeholder: "Elija una sede",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalRegistro")
    });

    $(".select-tipo-movimiento").select2({
        placeholder: "Elija un tipo de movimiento",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalRegistro")
    });

    $(".select-responsable").select2({
        placeholder: "Elija un responsable",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalRegistro")
    });

    $(".select-estado").select2({
        placeholder: "Elija un estado",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalRegistro")
    });

    $(".select-empresa").select2({
        placeholder: "Elija una empresa",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalRegistro")
    });

    $(".custom-checkbox-md").on("click", function (e) {
        alert('check');
        return false;
    });

    $("#formulario").on("submit", function(e) {
        $.ajax({
            type: "POST",
            url : route('control.guias.guardar-almacen'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function (response) {
                Util.mensaje(response.alerta, response.mensaje);
                if (response.respuesta == "ok") {
                    $('#tabla').DataTable().ajax.reload(null, false);
                    $("#modalRegistro").modal("hide");
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    $("#formulario-transportista").on("submit", function(e) {
        let input_this = $(this).find('button[type="submit"]');
        input_this.attr('disabled','true');
        $.ajax({
            type: "POST",
            url : route('control.guias.guardar-despacho'),
            data: new FormData($(this)[0]),
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                Util.mensaje(response.alerta, response.mensaje);
                if (response.respuesta == "ok") {
                    $('#tabla').DataTable().ajax.reload(null, false);
                    $("#modalTransportista").modal("hide");
                    input_this.removeAttr('disabled');
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    $("#formulario-transportista-actualizacion").on("submit", function(e) {
        $.ajax({
            type: "POST",
            url : route('control.guias.actualizar-despacho'),
            data: new FormData($(this)[0]),
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                Util.mensaje(response.alerta, response.mensaje);
                if (response.respuesta == "ok") {
                    $('#tabla').DataTable().ajax.reload(null, false);
                    $("#modalTransportistaActualizacion").modal("hide");
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    $("#formulario-archivador").on("submit", function(e) {
        $.ajax({
            type: "POST",
            url : route('control.guias.guardar-archivador'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function (response) {
                Util.mensaje(response.alerta, response.mensaje);
                if (response.respuesta == "ok") {
                    $('#tabla').DataTable().ajax.reload(null, false);
                    $("#modalArchivador").modal("hide");
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    $("#formulario-observacion").on("submit", function(e) {
        $.ajax({
            type: "POST",
            url : route('control.guias.guardar-observacion'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function (response) {
                Util.mensaje(response.alerta, response.mensaje);
                if (response.respuesta == "ok") {
                    $('#tabla').DataTable().ajax.reload(null, false);
                    $("#modalObservacion").modal("hide");
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    $("#btnBuscarCDP").on("click", (e) => {
        busquedaCDP($("[name=codigo_cdp]").val());
    });

    $(".select2-transporte").select2({
        placeholder: "Busque un transportista",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalTransportista"),
        minimumInputLength: 3,

        ajax: {
            url: route("control.guias.agencia-transportista"),
            type: "POST",
            data: function (params) {
              var query = {
                search: params.term,
                page: params.page || 1,
                _token: token,
              }

              // Query parameters will be ?search=[term]&page=[page]
              return query;
            },
            processResults: function (data) {

                return {
                    results:data
                };
            }
        }


    });

    $("#formulario-masivo").on("submit", function(e) {
        let button = $(this).find('button[type="submit"]');
        button.attr('disabled','true');
        $.ajax({
            type: "POST",
            url : route('control.guias.guardar-almacen-masivo'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function (response) {
                button.removeAttr('disabled');
                Util.mensaje(response.alerta, response.mensaje);
                $("#modalRegistroMasivo").modal("hide");
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        return false;
    });

    document.getElementById("serie_gr").addEventListener("keyup", function() {
        this.value = this.value.replace(/[^0-9]/g, "");
    });

    $('#formulario [name="codigo"]').on("change", function (e) {
        let codigo = $(this).val();
        let id = $('#formulario [name="id"]').val();
        let empresa = $('#formulario [name="empresa_id"]').val();
        let input_this = $(this);
        $.ajax({
            type: "POST",
            url : route('control.guias.buscar-codigo'),
            data: {
                _token: token,
                codigo: codigo,
                id: id,
                empresa: empresa
            },
            dataType: "JSON",
            success: function (response) {
                if (response.status == 200) {
                    $('#span-codigo').text(response.mensaje);
                    $('#span-codigo').removeClass('d-none');

                    input_this.val('');
                }else{
                    $('#span-codigo').addClass('d-none');
                }
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });

    });

    $(".select2-orden-despacho").select2({
        placeholder: "Buscar codigo de orden de despacho",
        allowClear: true,
        language: "es",
        width: "100%",
        dropdownParent: $("#modalTransportista"),
        minimumInputLength: 7,

        ajax: {
            url: route("control.guias.buscar-codigo-orden-despacho"),
            type: "POST",
            data: function (params) {
              var query = {
                search: params.term,
                page: params.page || 1,
                _token: token,
              }

              // Query parameters will be ?search=[term]&page=[page]
              return query;
            },
            processResults: function (data) {

                return {
                    results:data
                };
            }
        }


    });

    $('.buscar-codigo-od').click(function (e) {
        e.preventDefault();
        let codigo = $('[name="orden_depacho"]').val();
        let html = '';
        $.ajax({
            type: "POST",
            url : route('control.guias.buscar-codigo-orden-despacho'),
            dataType: "JSON",
            data:{
                _token: token,
                codigo:codigo
            },
            success: function (response) {
                if (response.status===200) {
                    $('#modal-despachos-externos').modal('show');
                    $.each(response.data, function (index, element) {
                        html+='<tr>'+
                            '<td>'+(element.codigo?element.codigo:'-')+'</td>'+
                            '<td>'+(element.tipo_requerimiento_descripcion?element.tipo_requerimiento_descripcion:'-')+'</td>'+
                            '<td>'+(element.fecha_entrega?element.fecha_entrega:'-/-/-')+'</td>'+
                            '<td>'+(element.nro_orden?element.nro_orden:'-')+'</td>'+
                            '<td>'+(element.monto_total?element.monto_total:'-')+'</td>'+
                            '<td>'+(element.orden_compra?element.orden_compra:'')+' - '+(element.siaf?element.siaf:'-')+'</td>'+
                            '<td>'+(element.occ?element.occ:'-')+'</td>'+
                            '<td>'+(element.codigo_oportunidad?element.codigo_oportunidad:'-')+'</td>'+
                            '<td>'+(element.nombre_entidad?element.nombre_entidad:'-')+'</td>'+
                            '<td>'+(element.responsable?element.responsable:'-')+'</td>'+
                            '<td><button typo="button" class="btn btn-sm btn-default" data-id="'+element.id_requerimiento+'" data-action="seleccionar">Seleccionar</button></td>'+
                        '</tr>'
                    });
                    $('#despachos-externos').find('tbody').html(html);
                }
                $('#despachos-externos').find('tbody').html(html);

            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
    $(document).on('click','[data-action="seleccionar"]',function () {
        let requerimiento_id = $(this).attr('data-id');
        let html = '';
        $('[data-alert="mensaje"]').html(html);
        $.ajax({
            type: "POST",
            url : route('control.guias.buscar-od'),
            dataType: "JSON",
            data:{
                _token: token,
                requerimiento_id:requerimiento_id
            },
            success: function (response) {
                if (response.status===200) {
                    $('#modal-despachos-externos').modal('hide');
                    $('#formulario-transportista').find('[name="requerimiento_id"]').val(requerimiento_id);
                }else{
                    html = `<div class="alert alert-`+response.tipo+`" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-frown-o me-2" aria-hidden="true"></i>`+response.mensaje+`
                    </div>`;
                    $('[data-alert="mensaje"]').html(html);
                }
                $('[data-alert="mensaje"]').html(html);
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
});

function listar() {
    // console.log(data_filtros);
    var vardataTables = funcDatatables();

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
            url: route('control.guias.listar'),
            method: 'POST',
            // headers: {'X-CSRF-TOKEN': token},
            data: data_filtros
        },
        columns: [
            {data: 'empresa_codigo', className: 'text-center'},
            {data: 'fecha_guia'},
            {data: 'estado_gci'},
            {data: 'codigo', className: 'text-center'},
            {data: 'destino'},
            {data: 'orden', className: 'text-center'},
            {data: 'documentos_agile', className: 'text-center'},
            {data: 'descripcion_guia'},
            {data: 'transportista'},
            {data: 'documentos_transportista', className: 'text-center'},
            {data: 'adj_guia', className: 'text-center'},
            {data: 'adj_guia_sellada', className: 'text-center'},
            {data: 'responsable', className: 'text-center'},
            {data: 'estado', orderable: false, searchable: false, className: 'text-center'},
            {data: 'accion', orderable: false, searchable: false, className: 'text-center'}
        ],
        buttons: [
            {
                text: '<i class="fa fa-filter"></i> Filtros',
                action: function () {
                    // $("#formulario-masivo")[0].reset();
                    $("#modal-filtros").modal("show");
                 },
                className: 'btn btn-default btn-sm',
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary')
                }
            },
            {
                text: '<i class="fa fa-plus"></i> Agregar GR',
                action: function () {
                    $("#formulario")[0].reset();
                    $('[name=id]').val(0);
                    $(".select2").val(null).trigger('change');
                    $('[name="empresa_id"]').val('').trigger('change');
                    $("#modalRegistro").modal("show");

                    $('#span-codigo').addClass('d-none');
                 },
                className: 'btn btn-default btn-sm',
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary')
                }
            },
            {
                text: '<i class="fa fa-list-ul"></i> Agregar GR Automatico',
                action: function () {
                    $("#formulario-masivo")[0].reset();
                    $("#modalRegistroMasivo").modal("show");
                 },
                className: 'btn btn-default btn-sm',
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary')
                }
            },
            {
                text: '<i class="fa fa-file-excel text-black"></i> Reporte',
                action: function () {
                    // $("#formulario-masivo")[0].reset();
                    // $("#modalRegistroMasivo").modal("show");
                    // window.open(route('control.guias.reporte-filtros',{empresa_id:data_filtros.empresa_id,estado:data_filtros.estado,fecha_final:data_filtros.fecha_final,fecha_inicio:data_filtros.fecha_inicio}), "Diseño Web", "width=300, height=200")
                    // window.open(`areporte-filtros`);
                    console.log(data_filtros);
                    let form = $('<form action="reporte-filtros" method="POST" target="_blank"> '+
                            '<input type="hidden" name="_token" value="'+token+'" >'+
                            '<input type="hidden" name="empresa_id" value="'+data_filtros.empresa_id+'" >'+
                            '<input type="hidden" name="estado" value="'+data_filtros.estado+'" >'+
                            '<input type="hidden" name="fecha_final" value="'+data_filtros.fecha_final+'" >'+
                            '<input type="hidden" name="fecha_inicio" value="'+data_filtros.fecha_inicio+'" >'+
                        '</form>');
                    $('body').append(form);
                    form.submit();
                 },
                className: 'btn btn-default btn-sm',
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary')
                }
            }

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
}

function cargarTransportista(id) {
    $("#formulario-transportista")[0].reset();
    $('[name=id_control]').val(id);
    $("#modalTransportista").modal("show");
    $('#formulario-transportista').find('[name="requerimiento_id"]').val(0);
    $('#formulario-transportista').find('[name="contribuyente_id"]').val(null).trigger('change');

}

function agregarObservacion(almacen, logistica) {
    $("#formulario-observacion")[0].reset();
    $('[name=id_control_obs]').val(almacen);
    $('[name=id_control_logistica]').val(logistica);
    $("#modalObservacion").modal("show");
}

function actualizarDatosLogistica(id) {
    $("#formulario-transportista-actualizacion")[0].reset();
    $.ajax({
        type: "GET",
        url : route('control.guias.informacion-despacho', {id: id}),
        dataType: "JSON",
        success: function (response) {
            $('[name=id_despacho_act]').val(response.id);
            if (response.cargo_guia) {
                $('[name=cargo_guia_act]').prop('checked', true);
            } else {
                $('[name=cargo_guia_act]').prop('checked', false);
            }
            if (response.envio_adjunto_guia) {
                $('[name=envio_adjunto_guia_act]').prop('checked', true);
            } else {
                $('[name=envio_adjunto_guia_act]').prop('checked', false);
            }
            if (response.envio_adjunto_guia_sellada) {
                $('[name=envio_adjunto_guia_sellada_act]').prop('checked', true);
            } else {
                $('[name=envio_adjunto_guia_sellada_act]').prop('checked', false);
            }
            $("#modalTransportistaActualizacion").modal("show");


        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
    return false;
}

function verHistorial(id) {
    let $contenido = '';
    let $listaHist = '';
    let $listaObs = '';

    $.ajax({
        type: "GET",
        url : route('control.guias.historial', {id: id}),
        dataType: "JSON",
        success: function (response) {
            let guia = response.guia;
            let historial = response.historial;
            let observaciones = response.observaciones;

            let $ocam = (guia.ocam != '') ? guia.ocam : '--';
            let $oc_virtual = (guia.oc_virtual != '') ? guia.oc_virtual : '--';
            let $documento = (guia.documento != null) ? guia.documento : '--';
            let $codigo_oportunidad = (guia.codigo_oportunidad != '') ? guia.codigo_oportunidad : '--';
            let $codigo_requerimiento = (guia.codigo_requerimiento != '') ? guia.codigo_requerimiento : '--';
            let $empresa = (guia.empresa != '') ? guia.empresa : '--';
            let $entidad = (guia.entidad != '') ? guia.entidad : '--';

            let titulo = "Información de la GR [" + guia.codigo + "] .:: " + guia.estado_gr + " ::.";
            $("#tituloHistorial").text(titulo);

            $contenido += `
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="text-primary subrayado">Descripción de la GR:</label>
                    <p>`+ guia.descripcion_guia +`</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="text-primary subrayado">Fecha:</label>
                    <p>`+ guia.formato_fecha +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Tipo:</label>
                    <p>`+ guia.tipo_movimiento +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Factura:</label>
                    <p>`+ $documento +`</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="text-primary subrayado">Destino:</label>
                    <p>`+ guia.destino +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Marca:</label>
                    <p>`+ guia.marca +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">OCAM:</label>
                    <p>`+ $ocam +`</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="text-primary subrayado">Orden Virtual:</label>
                    <p>`+ $oc_virtual +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Código CDP:</label>
                    <p>`+ $codigo_oportunidad +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Código Requerimiento:</label>
                    <p>`+ $codigo_requerimiento +`</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="text-primary subrayado">Empresa:</label>
                    <p>`+ $empresa +`</p>
                </div>
                <div class="col-md-4">
                    <label class="text-primary subrayado">Cliente:</label>
                    <p>`+ $entidad +`</p>
                </div>
            </div>`;

            if (historial.length > 0) {
                historial.forEach(elemento => {
                    $listaHist += `<tr>
                        <td>`+ elemento.fecha_registro +`</td>
                        <td>`+ elemento.descripcion +`</td>
                        <td>`+ elemento.usuario.nombre_corto +`</td>
                    </tr>`
                });
            } else {
                $listaHist += '<tr><td colspan="3">No se encontraron resultados</td></tr>';
            }

            if (observaciones.length > 0) {
                observaciones.forEach(elemento => {
                    $listaObs += `<tr>
                        <td>`+ elemento.fecha_registro +`</td>
                        <td>`+ elemento.observacion +`</td>
                        <td>`+ elemento.usuario.nombre_corto +`</td>
                    </tr>`
                });
            } else {
                $listaObs += '<tr><td colspan="3">No se encontraron resultados</td></tr>';
            }

            $("#resultado").html($contenido);
            $("#resultado-historial").html($listaHist);
            $("#resultado-observaciones").html($listaObs);
            $("#modalHistorial").modal("show");
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
    return false;
}

function archivarGuia(almacen, logistica) {
    $("#formulario-archivador")[0].reset();
    $('[name=id_control_arch]').val(almacen);
    $('[name=id_despacho_arch]').val(logistica);
    $("#modalArchivador").modal("show");

    // $.ajax({
    //     type: "POST",
    //     url : route('control.guias.busqueda'),
    //     data: { _token: token, valor: valor},
    //     dataType: "JSON",
    //     success: function (response) {
    //         if (response.respuesta == 'ok') {
    //             $("[name=empresa]").val(response.orden.nombre_empresa);
    //             $("[name=entidad]").val(response.orden.nombre_entidad);
    //             $("[name=orden]").val(response.orden.nro_orden);
    //         } else {
    //             Util.mensaje('info', 'No se encontró datos del CDP');
    //         }
    //     }
    // }).fail( function(jqXHR, textStatus, errorThrown) {
    //     console.log(jqXHR);
    //     console.log(textStatus);
    //     console.log(errorThrown);
    // });
}

function busquedaCDP(valor) {
    $('#btnBuscarCDP').find('i.fa').removeClass('fa-search');
    $('#btnBuscarCDP').find('i.fa').addClass('fa-spinner fa-spin');
    $.ajax({
        type: "POST",
        url : route('control.guias.busqueda'),
        data: { _token: token, valor: valor},
        dataType: "JSON",
        success: function (response) {
            if (response.respuesta == 'ok') {
                // $("[name=empresa]").val(response.orden.nombre_empresa);
                $("[name=entidad]").val(response.orden.nombre_entidad);
                $("[name=orden]").val(response.orden.nro_orden);

                $('#formulario').find('[name="empresa_id"] option').removeAttr('selected');
                $('#formulario [name="empresa_id"] option[value="'+response.orden.id_empresa+'"]').attr('selected','true');

                $('#btnBuscarCDP').find('i.fa').removeClass('fa-spinner fa-spin');
                $('#btnBuscarCDP').find('i.fa').addClass('fa-search');
            } else {
                Util.mensaje('info', 'No se encontró datos del CDP');
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
    return false;
}

function anular(id) {
    Swal.fire({
        title: '¿Desea anular el registro?',
        text: '',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, anular',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "PUT",
                url : route('control.guias.anular', {id: id}),
                data: { _token: token },
                dataType: "JSON",
                success: function (response) {
                    Util.mensaje(response.alerta, response.mensaje);
                    $('#tabla').DataTable().ajax.reload(null, false);
                }
            }).fail( function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
            return false;
        }
    });
}

function eliminar(id) {
    Swal.fire({
        title: '¿Desea eliminar el registro?',
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
                type: "PUT",
                url : route('control.guias.eliminar', {id: id}),
                data: { _token: token },
                dataType: "JSON",
                success: function (response) {
                    Util.mensaje(response.alerta, response.mensaje);
                    $('#tabla').DataTable().ajax.reload(null, false);
                }
            }).fail( function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
            return false;
        }
    });
}
function editar(id) {
    let formulario =  $('#formulario');
    $('#span-codigo').addClass('d-none');
    $.ajax({
        type: "GET",
        url : route('control.guias.editar', {id: id}),
        data: { _token: token },
        dataType: "JSON",
        success: function (response) {
            formulario[0].reset();
            formulario.find('[name=id]').val(response.id);
            $(".select2").val(null).trigger('change');

            formulario.find('[name="fecha"]').val(response.fecha_guia);
            formulario.find('[name="codigo"]').val(response.codigo);
            formulario.find('[name="documento"]').val(response.documento);
            formulario.find('[name="sede"]').val(response.sede).trigger('change');
            formulario.find('[name="tipo_movimiento_id"]').val(response.tipo_movimiento_id).trigger('change');
            formulario.find('[name="destino"]').val(response.destino);
            formulario.find('[name="codigo_cdp"]').val(response.codigo_oportunidad);
            formulario.find('[name="orden"]').val(response.ocam);
            formulario.find('[name="orden_virtual"]').val(response.oc_virtual);
            formulario.find('[name="codigo_requerimiento"]').val(response.codigo_requerimiento);
            formulario.find('[name="empresa"]').val(response.empresa);
            formulario.find('[name="entidad"]').val(response.entidad);
            formulario.find('[name="estado_gr"]').val(response.estado_gr).trigger('change');
            formulario.find('[name="fecha_ingreso"]').val(response.fecha_ingreso);
            // formulario.find('[name="procesado_softlink"]').val(response.procesado_softlink);
            if (response.procesado_softlink==true) {
                formulario.find('[name="procesado_softlink"]').attr('checked','true');
            }else{
                formulario.find('[name="procesado_softlink"]').removeAttr('checked');
            }

            if (response.procesado_agile==true) {
                formulario.find('[name="procesado_agile"]').attr('checked','true');
            }else{
                formulario.find('[name="procesado_agile"]').removeAttr('checked');
            }

            formulario.find('[name="id_responsable"]').val(response.id_responsable).trigger('change');
            formulario.find('[name="marca"]').val(response.marca);
            formulario.find('[name="descripcion"]').val(response.descripcion);
            // formulario.find('[name="observacion"]').val(response.observacion);
            // formulario.find('[name="empresa_id"]').val(response.empresa_id).trigger('change');
            formulario.find('[name="empresa_id"] option').removeAttr('selected');
            formulario.find('[name="empresa_id"] option[value="'+response.empresa_id+'"]').attr('selected','true');

            $("#modalRegistro").modal("show");
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function eviarGRControl(id) {

    $.ajax({
        type: "POST",
        url : route('control.guias.enviar-gr-control'),
        data: { _token: token, id:id },
        dataType: "JSON",
        success: function (response) {
            Util.mensaje('success', 'Se recepciono con éxito');
            $('#tabla').DataTable().ajax.reload(null, false);
        }
    }).fail( function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
 }
