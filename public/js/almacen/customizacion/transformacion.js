function nuevo_transformacion() {
    $('#form-transformacion')[0].reset();
    console.log('nuevo_transformacion:' + auth_user.id_usuario);
    limpiarCampos();
}
function limpiarCampos() {
    $('[name=id_transformacion]').val('');
    $('[name=codigo]').val('');
    $('[name=almacen_descripcion]').val('');
    $('[name=codigo_oportunidad]').val('');
    $('[name=codigo_od]').val('');
    $('[name=serie-numero]').val('');
    $('[name=id_estado]').val(1);

    $('#estado_doc').text('');
    $('#fecha_registro').text('');
    $('#fecha_transformacion').text('');
    $('#nombre_responsable').text('');
    $('#observacion').text('');

    $('#listaMateriasPrimas tbody').html('');
    $('#listaServiciosDirectos tbody').html('');
    $('#listaCostosIndirectos tbody').html('');
    $('#listaSobrantes tbody').html('');
    $('#listaProductoTransformado tbody').html('');

}
$(function () {
    var id_transformacion = localStorage.getItem("id_transfor");
    console.log('id_transfor' + id_transformacion);
    if (id_transformacion !== null && id_transformacion !== undefined) {
        mostrar_transformacion(id_transformacion);
        localStorage.removeItem("id_transfor");
        changeStateButton('historial');
    }
    vista_extendida();
});

function mostrar_transformacion(id) {
    $.ajax({
        type: 'GET',
        url: 'mostrar_transformacion/' + id,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            $('[name=id_transformacion]').val(response.id_transformacion);
            $('[name=id_od]').val(response.id_od);
            $('#codigo_oportunidad').text(response.codigo_oportunidad);
            $('#orden_am').text(response.orden_am);
            $('#almacen_descripcion').text(response.almacen_descripcion);
            $('[name=total_materias]').val(formatNumber.decimal(response.total_materias, '', -2));
            $('[name=total_directos]').val(formatNumber.decimal(response.total_directos, '', -2));
            $('[name=costo_primo]').val(formatNumber.decimal(response.costo_primo, '', -2));
            $('[name=total_indirectos]').val(formatNumber.decimal(response.total_indirectos, '', -2));
            $('[name=total_sobrantes]').val(formatNumber.decimal(response.total_sobrantes, '', -2));
            $('[name=costo_transformacion]').val(formatNumber.decimal(response.costo_transformacion, '', -2));

            $('#codigo').text(response.codigo);
            $('#codigo_od').text(response.cod_od);
            $('#codigo_req').text(response.codigo_req);
            $('#serie-numero').text(response.serie !== null ? (response.serie + '-' + response.numero) : '');

            $('#fecha_transformacion').text(response.fecha_transformacion !== null ? formatDateHour(response.fecha_transformacion) : '');
            $('#fecha_inicio').text(response.fecha_inicio !== null ? formatDateHour(response.fecha_inicio) : '');
            $('#nombre_responsable').text(response.nombre_corto);
            $('#observacion').text(response.observacion);
            $('#descripcion_sobrantes').text(response.descripcion_sobrantes);

            if (response.estado == 24) {
                $('#addCostoIndirecto').show();
                $('#addServicio').show();
                $('#addTransformado').show();
                $('#addSobrante').show();
                $('#addMateriaPrima').show();
            } else {
                $('#addCostoIndirecto').hide();
                $('#addServicio').hide();
                $('#addTransformado').hide();
                $('#addSobrante').hide();
                $('#addMateriaPrima').hide();
            }

            $('[name=id_estado]').val(response.estado);
            $('#estado_doc').text(response.estado_doc);
            $('#estado_doc').removeClass();
            $('#estado_doc').addClass("label label-" + response.bootstrap_color);

            listar_materias(response.id_transformacion);
            listar_directos(response.id_transformacion);
            listar_indirectos(response.id_transformacion);
            listar_sobrantes(response.id_transformacion);
            listar_transformados(response.id_transformacion);

            // calcula_totales();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_transformacion(data, action) {
    if (action == 'register') {
        baseUrl = 'guardar_transformacion';
    } else if (action == 'edition') {
        baseUrl = 'actualizar_transformacion';
    }
    $.ajax({
        type: 'POST',
        // headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            if (response['id_transformacion'] > 0) {
                alert('Transformación registrada con éxito');

                changeStateButton('guardar');
                $('#form-transformacion').attr('type', 'register');
                changeStateInput('form-transformacion', true);

                mostrar_transformacion(response['id_transformacion']);
                $('.boton').removeClass('desactiva');
                // var id = $('[name=id_transformacion]').val();
                // listar_materias(id);
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}
function ceros_numero(numero) {
    if (numero == 'numero') {
        var num = $('[name=numero]').val();
        $('[name=numero]').val(leftZero(7, num));
    }
}

$("#form-procesarTransformacion").on("submit", function (e) {
    console.log('submit');
    e.preventDefault();
    var data = $(this).serialize();
    console.log(data);
    var res = $('[name=responsable]').val();

    if (res !== "0") {
        procesar_transformacion(data);
    }
    else {
        alert('Es necesario que seleccione un Responsable');
    }
});

function procesar_transformacion(data) {
    $.ajax({
        type: 'POST',
        url: 'procesar_transformacion',
        data: data,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            if (response == 'ok') {
                $('#modal-procesarTransformacion').modal('hide');
                // alert('Transformación procesada con éxito');
                Lobibox.notify("success", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: 'Transformación procesada con éxito.'
                });
                var id_trans = $('[name=id_transformacion]').val();
                mostrar_transformacion(id_trans);
            } else {
                Lobibox.notify("error", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: 'Algo salió mal. Inténtelo nuevamente.'
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function openProcesar() {
    var id_trans = $('[name=id_transformacion]').val();

    if (id_trans !== '') {
        var est = $('[name=id_estado]').val();
        var inputs = $('#listaSobrantes tbody').find('input').length;

        if (inputs > 0) {
            Swal.fire({
                title: "Debe guardar o eliminar el sobrante agregado.",
                icon: "error",
            });
        }
        else if (est == '9') {
            Swal.fire({
                title: "La transformación ya fue procesada.",
                icon: "error",
            });
        }
        else if (est == '7') {
            Swal.fire({
                title: "No puede procesar. La transformación esta Anulada.",
                icon: "error",
            });
        }
        else if (est == '1') {
            Swal.fire({
                title: "A la espera de que Almacén genere la salida de los productos.",
                icon: "error",
            });
        }
        else if (est == '21') {
            Swal.fire({
                title: "Es necesario que inicie la transformación.",
                icon: "error",
            });
        }
        else if (est == '24') {
            $('#modal-procesarTransformacion').modal({
                show: true
            });
            $('[name=responsable]').val('');
            $('[name=observacion]').val('');
        }
    } else {
        Swal.fire({
            title: "No ha seleccionado una Hoja de Transformación!",
            icon: "error",
        });
    }
}

function openIniciar() {
    var id_transformacion = $('[name=id_transformacion]').val();
    var est = $('[name=id_estado]').val();
    if (est == '1') {
        Swal.fire({
            title: "A la espera de que Almacén genere la salida de los productos.",
            icon: "error",
        });
    }
    else if (est == '9') {
        Swal.fire({
            title: "La transformación ya fue procesada.",
            icon: "error",
        });
    }
    else if (est == '7') {
        Swal.fire({
            title: "No puede procesar. La transformación esta Anulada.",
            icon: "error",
        });
    }
    // else if (est == '24') {
    //     Swal.fire({
    //         title: "Ésta Transformación ya fue iniciada.",
    //         icon: "error",
    //     });
    // }
    else /*if (est == '21')*/ {
        $.ajax({
            type: 'GET',
            url: 'iniciar_transformacion/' + id_transformacion,
            dataType: 'JSON',
            success: function (response) {
                console.log(response);
                mostrar_transformacion(id_transformacion);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }
}

function anular_transformacion(ids) {
    $.ajax({
        type: 'GET',
        url: 'anular_transformacion/' + ids,
        dataType: 'JSON',
        success: function (response) {
            if (response.length > 0) {
                alert(response);
                changeStateButton('anular');
                mostrar_transformacion(ids);
                // clearForm('form-guia_compra');
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

let origen = null;
function openProductoMateriaModal() {
    origen = 'materia';
    productoModal();
}
function openProductoTransformadoModal() {
    origen = 'transformado';
    productoModal();
}
function openProductoSobranteModal() {
    origen = 'sobrante';
    productoModal();
}
// Calcula total
function actualizaTotales() {
    var total_materias = parseFloat($('[name=total_materias]').text());
    var total_directos = parseFloat($('[name=total_directos]').text());
    var total_indirectos = parseFloat($('[name=total_indirectos]').text());
    var total_sobrantes = parseFloat($('[name=total_sobrantes]').text());
    console.log('actualiza');
    $('[name=costo_primo]').text(formatNumber.decimal((total_materias + total_directos), '', -2));
    $('[name=costo_transformacion]').text(formatNumber.decimal((total_materias + total_directos + total_indirectos - total_sobrantes), '', -2));
}

function imprimirTransformacion() {
    var id = $('[name=id_transformacion]').val();
    if (id !== null && id !== '') {
        window.open('imprimir_transformacion/' + id);
    } else {
        // alert('Debe seleccionar una Hoja de Transformación.');
        Swal.fire({
            title: "Debe seleccionar una Hoja de Transformación!",
            icon: "error",
        });
    }
}