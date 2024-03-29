// function ver_transferencia(id_guia) {
//     $("#submit_guia_transferencia").removeAttr("disabled");
//     $.ajax({
//         type: "GET",
//         url: "verGuiaCompraTransferencia/" + id_guia,
//         dataType: "JSON",
//         success: function (response) {
//             console.log(response);
//             $("#modal-guia_com_ver").modal({
//                 show: true
//             });
//             $("[name=id_guia_com]").val(response["guia"].id_guia);
//             $("[name=serie_numero]").text(
//                 response["guia"].serie + "-" + response["guia"].numero
//             );
//             $("[name=fecha_emision]").text(response["guia"].fecha_emision);
//             $("[name=fecha_almacen]").text(response["guia"].fecha_almacen);
//             $("[name=almacen]").text(response["guia"].almacen_descripcion);
//             $("[name=operacion]").text(response["guia"].operacion);
//             $("[name=clasificacion]").text(response["guia"].clasificacion);

//             var html = "";
//             var html_serie = "";
//             var i = 1;

//             response["detalle"].forEach(element => {
//                 html_serie = "";
//                 element.series.forEach(ser => {
//                     if (html_serie == "") {
//                         html_serie += "<br>" + ser.serie;
//                     } else {
//                         html_serie += ", " + ser.serie;
//                     }
//                 });

//                 html += `<tr>
//                 <td>${i}</td>
//                 <td>${element.codigo_orden !== null
//                         ? element.codigo_orden
//                         : element.codigo_transfor !== null
//                             ? element.codigo_transfor
//                             : ""
//                     }</td>
//                 <td>${element.codigo_req !== null ? element.codigo_req : ""
//                     }</td>
//                 <td><strong>${element.sede_req !== null ? element.sede_req : ""
//                     }</strong></td>
//                 <td>${element.codigo}</td>
//                 <td>${element.part_number !== null ? element.part_number : ""
//                     }</td>
//                 <td>${element.descripcion} <strong>${html_serie}</strong></td>
//                 <td>${element.cantidad}</td>
//                 <td>${element.abreviatura}</td>
//                 </tr>`;
//                 i++;
//             });
//             $("#detalleGuiaCompra tbody").html(html);
//         }
//     }).fail(function (jqXHR, textStatus, errorThrown) {
//         console.log(jqXHR);
//         console.log(textStatus);
//         console.log(errorThrown);
//     });
// }

// $("#form-guia_com_ver").on("submit", function (e) {
//     e.preventDefault();
//     var data = $(this).serialize();
//     console.log(data);
//     $("#submit_guia_transferencia").attr("disabled", "true");
//     generar_transferencia();
// });

// function generar_transferencia() {
//     var id_guia = $("[name=id_guia_com]").val();
//     $.ajax({
//         type: "GET",
//         url: "transferencia/" + id_guia,
//         dataType: "JSON",
//         success: function (response) {
//             console.log(response);
//             alert(response);
//             $("#modal-guia_com_ver").modal("hide");
//             let formName = document
//                 .getElementsByClassName("page-main")[0]
//                 .getAttribute("type");

//             if (formName == "transferencias") {
//                 listarTransferenciasPorEnviar();
//             } else if (formName == "ordenesPendientes") {
//                 $("#listaIngresosAlmacen").DataTable().ajax.reload(null, false);//no reiniciar paginacion
//             }
//         }
//     }).fail(function (jqXHR, textStatus, errorThrown) {
//         console.log(jqXHR);
//         console.log(textStatus);
//         console.log(errorThrown);
//     });
// }

let detalle = [];

function ver_requerimiento(id_requerimiento) {
    $("#detalleRequerimiento tbody").html("");
    $("[name=id_almacen_destino]").html("");
    $('[name=fecha_emision_transferencia]').val(fecha_actual());
    detalle = [];
    $("#modal-ver_requerimiento").modal({
        show: true
    });

    $.ajax({
        type: "GET",
        url: "verRequerimiento/" + id_requerimiento,
        dataType: "JSON",
        success: function (response) {
            console.log(response);

            $("[name=id_requerimiento]").val(response["requerimiento"].id_requerimiento);
            $("[name=codigo_req]").text(response["requerimiento"].codigo);
            $("[name=concepto]").text(response["requerimiento"].concepto);
            $("[name=fecha_requerimiento]").text(response["requerimiento"].fecha_requerimiento);
            $("[name=sede_requerimiento]").text(response["requerimiento"].sede_requerimiento);
            $("[name=estado_requerimiento]").text(response["requerimiento"].estado_doc);

            var option = '';
            if (response["almacenes"].length == 1) {
                option += `<option value="${response["almacenes"][0].id_almacen}" selected>${response["almacenes"][0].descripcion}</option>`;
            } else {
                response["almacenes"].forEach(element => {
                    if (element.id_tipo_almacen == 1) {//almacen principal sugerido
                        option += `<option value="${element.id_almacen}" selected>${element.descripcion}</option>`;
                    } else {
                        option += `<option value="${element.id_almacen}">${element.descripcion}</option>`;
                    }
                });
            }
            $("[name=id_almacen_destino_create]").html(option);

            response["detalle"].forEach(element => {
                if (element.sede !== "") {
                    detalle.push(element);
                }
            });
            mostrarDetalleRequerimiento();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function mostrarDetalleRequerimiento() {
    var html = "";
    var html_serie = "";
    var i = 1;

    if (detalle.length > 0) {
        detalle.forEach(element => {
            html_serie = "";
            element.series.forEach(ser => {
                if (html_serie == "") {
                    html_serie += "<br>" + ser.serie;
                } else {
                    html_serie += ", " + ser.serie;
                }
            });

            html += `<tr id="${element.id_reserva}">
            <td>${i}</td>
            <td>${element.codigo_orden !== null
                    ? element.codigo_orden
                    : element.guia !== null
                        ? element.guia
                        : ""
                }</td>
            <td>${element.almacen_descripcion}</td>
            <td>${element.codigo + (element.tiene_transformacion
                    ? ' <span class="badge badge-secondary">Transformado</span> '
                    : "")}</td>
            <td>${element.part_number !== null ? element.part_number : ""}</td>
            <td>${element.descripcion} <strong>${html_serie}</strong></td>
            <td>${element.stock_comprometido}</td>
            <td>${element.abreviatura}</td>
            <td>${element.sede !== ""
                    ? `<button type="button" class="quitar btn btn-danger btn-xs" data-toggle="tooltip" 
                        data-placement="bottom" title="Quitar item" 
                        data-id="${element.id_detalle_requerimiento}">
                        <i class="fas fa-minus"></i></button>` : ""}
            <td/>
            </tr>`;
            i++;
        });
    } else {
        html += `<tr>
            <td colSpan="9">No hay nada que transferir!</td>
            </tr>`;
    }

    $("#detalleRequerimiento tbody").html(html);
}

$("#detalleRequerimiento tbody").on("click", ".quitar", function () {
    let id = $(this).data("id");
    console.log(id);
    let index = detalle.findIndex(function (item, i) {
        return item.id_detalle_requerimiento == id;
    });
    detalle.splice(index, 1);
    mostrarDetalleRequerimiento();
});

$("#form-ver_requerimiento").on("submit", function (e) {
    e.preventDefault();

    Swal.fire({
        title: "¿Está seguro que desea guardar la transferencia?",
        // text: "No podrás revertir esto.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00a65a", //"#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Si, Guardar"
    }).then(result => {
        if (result.isConfirmed) {
            var data = $(this).serialize();
            console.log(data);
            var id_requerimiento = $("[name=id_requerimiento]").val();
            var id_almacen_destino = $("[name=id_almacen_destino_create]").val();
            var fecha = $("[name=fecha_emision_transferencia]").val();
            var listaItemsDetalle = [];

            detalle.forEach(element => {
                nuevo = {
                    id_reserva: element.id_reserva,
                    id_detalle_requerimiento: element.id_detalle_requerimiento,
                    id_producto: element.id_producto,
                    id_guia_com_det: element.id_guia_com_det,
                    stock_comprometido: element.stock_comprometido,
                    id_almacen_reserva: element.id_almacen_reserva
                };
                listaItemsDetalle.push(nuevo);
            });
            var data = {
                id_requerimiento: id_requerimiento,
                id_almacen_destino: id_almacen_destino,
                detalle: listaItemsDetalle,
                fecha: fecha,
            };
            console.log(data);
            generarTransferenciaRequerimiento(data);
        }
    });
});

function generarTransferenciaRequerimiento(data) {
    $.ajax({
        type: "POST",
        url: "generarTransferenciaRequerimiento",
        data: data,
        dataType: "JSON",
        success: function (response) {
            console.log(response);
            Lobibox.notify(response.tipo, {
                title: false,
                size: "mini",
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: response.mensaje
            });
            if (response.tipo == 'success') {
                $("#modal-ver_requerimiento").modal("hide");
                $("#listaRequerimientos").DataTable().ajax.reload(null, false);
                $("#nro_pendientes").text(response.nroPendientes);
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}
