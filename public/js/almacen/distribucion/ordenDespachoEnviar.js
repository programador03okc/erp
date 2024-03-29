function openOrdenDespachoEnviar(data) {

    if (data.id_tipo_requerimiento == 1) {

        const $modal = $('#modal-orden_despacho_enviar');
        $modal.modal('show');
        //Limpieza para seleccionar archivo
        $modal.find('input[type=file]').val(null);
        $modal.find('div.bootstrap-filestyle').find('input[type=text]').val('');

        $('[name=id_requerimiento]').val(data.id_requerimiento ?? '');
        $('[name=fecha_documento_ode]').val(fecha_actual());
        $('[name=id_oportunidad]').val(data.id_oportunidad ?? '');
        $('#codigo_cdp').text((data.codigo_oportunidad ?? '') + ' - ' + (data.codigo ?? ''));
        $('[name="codigo"]').val((data.codigo_oportunidad ?? '') + '-' + (data.codigo ?? ''));

        var msj = "Por favor hacer seguimiento a este pedido. Vence: " + (formatDate(data.fecha_entrega) ?? '') +
            "\nFECHA DE DESPACHO: \n" +
            "\nFavor de generar documentación: " +
            "\n• FACTURA " +
            "\n• GUIA" +
            "\n• CERTIFICADO DE GARANTIA " +
            "\n• CCI" +
            "\n\nSaludos, \n" + usuarioSesion;

        $('[name=mensaje]').val(msj);

    } else {

        const $modal = $('#modal-despacho_externo_fecha');
        $modal.modal('show');
        $('[name=fecha_documento_odex]').val(fecha_actual());
        $('[name=req_id_requerimiento_odex]').val(data.id_requerimiento);

    }
}

function generarDespachoExterno() {
    var id_req = $('[name=req_id_requerimiento_odex]').val();
    var fdoc = $('[name=fecha_documento_odex]').val();
    var formData = 'id_requerimiento=' + id_req +
        '&fecha_documento_ode=' + fdoc +
        '&id_oportunidad=0'
    '&envio=no';
    console.log(formData);

    $.ajax({
        type: 'POST',
        url: 'guardarOrdenDespachoExterno',
        data: formData,
        dataType: 'JSON',
    }).done(function (response) {
        console.log(response);
        Lobibox.notify(response.tipo, {
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: response.mensaje
        });
        if (response.tipo == 'success') {
            $('#modal-despacho_externo_fecha').modal('hide');
            $("#requerimientosEnProceso").DataTable().ajax.reload(null, false);
        } else {
            console.log('Error devuelto: ' + response.error);
        }
    }).always(function () {
        if (typeof $submit != 'undefined') {
            if ($submit !== null) {

                $submit.prop('disabled', false);
                $submit.html('Enviar');
            }
        }
    }).fail(function (jqXHR) {
        Lobibox.notify('error', {
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: 'Hubo un problema. Por favor actualice la página e intente de nuevo.'
        });
        console.log('Error devuelto: ' + jqXHR.responseText);
    });
}

function generarOrdenDespacho(data) {
    $('[name=id_requerimiento]').val(data.id_requerimiento ?? '');
    $('[name=id_oportunidad]').val(data.id_oportunidad ?? '');
    guardar_orden_despacho();
}

$('#submit_orden_despacho_enviar').on('click', function (params) {

    // Swal.fire({
    //     title: "¿Está seguro que desea enviar la Orden de Despacho?",
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#00a65a", //"#3085d6",
    //     cancelButtonColor: "#d33",
    //     cancelButtonText: "Cancelar",
    //     confirmButtonText: "Sí, Enviar"
    // }).then(result => {
    //     if (result.isConfirmed) {
    guardar_orden_despacho();
    //     }
    // });
});

function guardar_orden_despacho() {
    const $submit = $("#submit_orden_despacho_enviar");
    $submit.prop('disabled', 'true');
    $submit.html('Enviando...');
    let formData = new FormData(document.getElementById("form-orden_despacho_enviar"));
    guardarOrdenDespacho(formData, $submit);
}

function guardarOrdenDespacho(formData, $submit) {
    $.ajax({
        type: 'POST',
        url: 'guardarOrdenDespachoExterno',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
    }).done(function (response) {
        console.log(response);
        Lobibox.notify(response.tipo, {
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: response.mensaje
        });
        if (response.tipo == 'success') {
            $('#modal-orden_despacho_enviar').modal('hide');
            $("#requerimientosEnProceso").DataTable().ajax.reload(null, false);
        } else {
            console.log('Error devuelto: ' + response.error);
        }
    }).always(function () {
        if ($submit !== null && $submit !== undefined) {
            console.log($submit);
            $submit.prop('disabled', false);
            $submit.html('Enviar');
        }
    }).fail(function (jqXHR) {
        Lobibox.notify('error', {
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: 'Hubo un problema. Por favor actualice la página e intente de nuevo.'
        });
        console.log('Error devuelto: ' + jqXHR.responseText);
    });
}
