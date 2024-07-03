// $('#modal-ordenDespachoEstados').on("keyup", "input.handleUpdateGastoExtraConIGV", (e) => {
//     updateGastoExtraConIGV(e.currentTarget);
// });
// $('#modal-ordenDespachoEstados').on("keyup", "input.handleUpdateGastoExtraSinIGV", (e) => {
//     updateGastoExtraSinIGV(e.currentTarget);
// });
// $('#modal-ordenDespachoEstados').on("change", "input.hadleChangeExtraAplicaIGVGasto", (e) => {
//     updateGastoExtraAplicaIGV(e.currentTarget);
// });


function agregarEstadoEnvio(id) {
    $('#modal-ordenDespachoEstados').modal({
        show: true
    });
    console.log('agregarEstadoEnvio');
    $('[name=id_od]').val(id);
    $('[name=estado]').val('');
    $('[name=fecha_estado]').val('');
    $('[name=observacion]').val('');
    $('[name=adjunto]').val('');
    $('[name=gasto_extra]').val('');
    $('[name=plazo_excedido]').prop('checked', false);
    $('#submit_ordenDespachoEstados').removeAttr('disabled');
}

$("#form-ordenDespachoEstados").on("submit", function (e) {
    e.preventDefault();
    Swal.fire({
        title: "¿Está seguro que desea guardar este estado de envío?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00a65a", //"#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sí, Guardar"
    }).then(result => {
        if (result.isConfirmed) {
            guardarEstadoEnvio();
        }
    });
});

function guardarEstadoEnvio() {
    $('#submit_ordenDespachoEstados').attr('disabled', 'true');
    var formData = new FormData($('#form-ordenDespachoEstados')[0]);
    calcularImportesDetalleRequerimientoEstado($("input[name='gasto_extra']").val());

    $.ajax({
        type: 'POST',
        url: 'guardarEstadoEnvio',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            $('#modal-ordenDespachoEstados').modal('hide');
            Lobibox.notify("success", {
                title: false,
                size: "mini",
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: 'Se guardó el estado con éxito.'
            });
            $('#requerimientosEnProceso').DataTable().ajax.reload(null, false);

            consultaGuardarRequerimientoFlete($("input[name='id_od']").val());


        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function formatTimeLine(table_id, id, row) {
    // console.log(id);
    $.ajax({
        type: 'GET',
        url: 'getTimelineOrdenDespacho/' + id,
        dataType: 'JSON',
        success: function (response) {
            // console.log(response.length);

            if (response.length > 0) {
                var html = `<div style="overflow-x:scroll;">
                <div class="row" >
                <div class="col-md-12">
                
                  <div style="display:inline-block;width:100%;">
                    <ul class="timeline timeline-horizontal" style="padding: 80px !important">
                    <input type="button" id="btn_cerrar_transportista" class="btn btn-success" style="position: absolute;left: 0px;top: 0px;"
                        onClick="agregarEstadoEnvio(${id});" value="Agregar"/>`;

                response.forEach(element => {

                    fleteObject = {
                        'estado': '',
                        'transportista': element.razon_social_transportista ?? '',
                        'fecha_entrega': element.fecha_transportista ?? '',
                        'precio_unitario': element.importe_flete_sin_igv ?? '',
                        'importe_igv': element.importe_flete ?? '',
                        'importe_total': element.importe_flete != null ? element.importe_flete : (element.importe_flete_sin_igv != null ? importe_flete_sin_igv : '')
                    }

                    if (element.accion == 2) {
                        html += `<li class="timeline-item">
                        <div class="timeline-badge bggreendark"><i class="glyphicon glyphicon-time"></i></div>
                        <div class="timeline-panel bordergreendark">
                            <div class="timeline-heading">
                            <p><small class="text-muted colorgreendark">${element.fecha_transportista !== null
                                ? formatDate(element.fecha_transportista) + '<br>'
                                : ''}
                            <strong>${element.estado_doc.toUpperCase()}</strong><br>
                            ${element.observacion !== null ? element.observacion + '<br>' : ''}
                            ${element.razon_social_transportista !== null ? element.razon_social_transportista + '<br>' : 'Propia'}
                            ${element.importe_flete_sin_igv !== null ? ('<strong>Flete real: S/' + element.importe_flete_sin_igv + (element.credito ? ' (Crédito)' : '') + '</strong>') : ''}<br>
                            ${element.importe_flete !== null ? ('<strong>Flete real + IGV: S/' + element.importe_flete + (element.credito ? ' (Crédito)' : '') + '</strong>') : ''}</small><br></p>
                            </div>
                            <p class="text-center"><input type="button" id="btn_cerrar_transportista" class="btn btn-xs btn-success"
                            onClick="openModalRequerimientoFlete(${id});" value="Nuevo req. Flete"/></p>
                        </div>
                        </li>`;
                    }
                    else {
                        html += `<li class="timeline-item">
                        <div class="timeline-badge ${element.accion == 3 ? 'bggreenlight' :
                                ((element.accion == 4 || element.accion == 5) ? 'bgyellow' :
                                    (element.accion == 6 ? 'bgfuxia' :
                                        (element.accion == 7 ? 'bgorange' : 'bgdark')))}">
                        <i class="glyphicon glyphicon-time"></i></div>
                        <div class="timeline-panel ${element.accion == 3 ? 'bordergreenlight' :
                                ((element.accion == 4 || element.accion == 5) ? 'borderyellow' :
                                    (element.accion == 6 ? 'borderfuxia' :
                                        (element.accion == 7 ? 'borderorange' : 'borderdark')))} ">

                            ${element.accion !== 1 ?
                                `<i class="fas fa-trash-alt red" style="cursor:pointer;" title="Eliminar estado de envío"
                                onClick="eliminarTrazabilidadEnvio(${element.id_obs});"></i>`
                                : ''}
    
                            <div class="timeline-heading">
                            <p><small class="text-muted ${element.accion == 3 ? 'colorgreenlight' :
                                ((element.accion == 4 || element.accion == 5) ? 'coloryellow' :
                                    (element.accion == 6 ? 'colorfuxia' :
                                        (element.accion == 7 ? 'colororange' : 'colordark')))}">
                            ${element.accion == 1 ?
                                (element.fecha_despacho_real !== null ? formatDate(element.fecha_despacho_real) + '<br>' :
                                    (element.fecha_despacho !== null ? formatDate(element.fecha_despacho) + '<br>' : ''))
                                : (element.fecha_estado !== null ? formatDate(element.fecha_estado) + '<br>' : '')}
                            <strong>${element.estado_doc.toUpperCase()}</strong><br>
                            ${element.observacion !== null ? element.observacion + '<br>' : ''}
                            ${element.nombre_corto}<br>
                            ${element.gasto_extra_sin_igv !== null ? ('<strong>Gasto extra: S/' + element.gasto_extra_sin_igv + '</strong><br>') : ''}
                            ${element.gasto_extra !== null ? ('<strong>Gasto extra + IGV: S/' + element.gasto_extra + '</strong><br>') : ''}
                            ${element.adjunto !== null ? (`<a target="_blank" href="/files/almacen/trazabilidad_envio/${element.adjunto}">Adjunto</a><br>`) : ''}
                            </small></p>
                            <p class="text-center"><input type="button" id="btn_cerrar_transportista" class="btn btn-xs btn-success"
                            onClick="openModalRequerimientoFlete(${id});" value="Nuevo req.flete"/></p>
                            </div>
                        </div>
                        </li>`;
                    }
                });
                // ${element.plazo_excedido ? '<strong class="red">PLAZO EXCEDIDO</strong><br>' : ''}
                html += `</ul>
                        </div>
                    </div>
                </div>
                </div>`;
                row.child(html).show();
            } else {
                Lobibox.notify("warning", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: "Aún no hay estados de envío ingresados."
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function eliminarTrazabilidadEnvio(id) {
    console.log(id);
    if (id !== null) {
        Swal.fire({
            title: "¿Está seguro que desea anular este estado de envío?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00a65a", //"#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sí, Anular"
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "eliminarTrazabilidadEnvio/" + id,
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        Lobibox.notify("success", {
                            title: false,
                            size: "mini",
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: 'El estado de envío fue anulado con éxito.'
                        });
                        $('#requerimientosEnProceso').DataTable().ajax.reload(null, false);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        });
    }
}


function calcularImportesDetalleRequerimientoEstado(importeFlete) {
    let importeUnitario = 0;
    let importeIGV = 0;
    let importeTotal = 0;
    if (parseFloat(importeFlete) > 0) {
        importeUnitario = (parseFloat(importeFlete) / 1.18);
        importeIGV = (parseFloat(importeFlete) * 0.18);
        importeTotal = (parseFloat(importeFlete));
    }

    fleteObject = {

        'transportista': '',
        'estado': $("select[name='estado'] option:selected").text(),
        'fecha_entrega': $("input[name='fecha_estado']").val(),
        'precio_unitario': importeUnitario,
        'importe_igv': importeIGV,
        'importe_total': importeTotal
    }
}


// function updateGastoExtraConIGV(obj) {
//     if (typeof parseFloat(obj.value) == 'number') {
//         document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra_sin_igv']").value = $.number((parseFloat(obj.value) / 1.18), 2, '.', '');
//     }
// }
// function updateGastoExtraSinIGV(obj) {
//     if (typeof parseFloat(obj.value) == 'number') {

//         if (document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='aplica_igv']").checked == false) {
//             document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").value = $.number(parseFloat(obj.value), 2, '.', '');
//         } else {
//             document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").value = $.number((parseFloat(obj.value) * 1.18), 2, '.', '');
//         }
//     }
// }
// function updateGastoExtraAplicaIGV(obj) {
//     if (obj.checked == false) {
//         document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").setAttribute("readOnly", true);
//         document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").value = document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra_sin_igv']").value;
//     } else {
//         document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").removeAttribute("readOnly");
//         let importeSinIGV = document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra_sin_igv']").value;
//         document.querySelector("div[id='modal-ordenDespachoEstados'] input[name='gasto_extra']").value = $.number((parseFloat(importeSinIGV) * 1.18), 2, '.', '')
//     }

// }