
// $('#modal-requerimiento_flete').on("change", "select.handleChangeAlmacen", (e) => {
//     this.handleChangeAlmacen(e.currentTarget);
// });

// $('#modal-requerimiento_flete').on("click", "button.handleClickCargarModalPartidas", (e) => {
//     this.handleClickCargarModalPartidas(e);
// });
// $('#modal-partidas').on("click", "h5.handleClickapertura", (e) => {
//     this.apertura(e.currentTarget.dataset.idPresup);
//     this.changeBtnIcon(e);
// });
// $('#modal-partidas').on("click", "button.handleClickSelectPartida", (e) => {
//     this.selectPartida(e.currentTarget.dataset.idPartida);
// });
$('#modal-requerimiento_flete').on("change", "select.handleChangeEmpresa", (e) => {
    this.handleChangeEmpresa(e.currentTarget);
});
$('#modal-requerimiento_flete').on("change", "select.handleChangeSede", (e) => {
    this.handleChangeSede(e.currentTarget);
});
$('#modal-requerimiento_flete').on("change", "select.handleChangeDivision", (e) => {
    this.handleChangeDivision(e.currentTarget);
});
$('#modal-requerimiento_flete').on("click", "button.handleClickCargarModalCentroCostos", (e) => {
    this.cargarModalCentroCostos(e);
});
$('#modal-centro-costos').on("click", "h5.handleClickapertura", (e) => {
    this.apertura(e.currentTarget.dataset.idPresup);
    this.changeBtnIcon(e);
});
$('#modal-centro-costos').on("click", "button.handleClickSelectCentroCosto", (e) => {
    this.selectCentroCosto(e.currentTarget.dataset.idCentroCosto, e.currentTarget.dataset.codigo, e.currentTarget.dataset.descripcionCentroCosto);
});

$('#modal-requerimiento_flete').on("click", "button.handleClickAdjuntarArchivoItem", (e) => {
    this.modalAdjuntarArchivosDetalle(e.currentTarget);
});
$('#modal-requerimiento_flete').on("change", "select.cambiarTipoDestinatario", (e) => {
    this.cambiarTipoDestinatario();
});

$('#modal-adjuntar-archivos-detalle-requerimiento').on("change", "input.handleChangeAgregarAdjuntoDetalle", (e) => {
    this.agregarAdjuntoRequerimientoPagoDetalle(e.currentTarget);
});

$('#modal-adjuntar-archivos-detalle-requerimiento').on("click", "button.handleClickEliminarArchivoRequerimientoDetalle", (e) => {
    this.eliminarArchivoRequerimientoDetalle(e.currentTarget);
});

var objBotonAdjuntoRequerimientoDetalleSeleccionado = '';
var tempArchivoAdjuntoItemsRequerimientoList = [];

function consultaGuardarRequerimientoFlete(id_od) {

    if (id_od > 0) {
        Swal.fire({
            title: '¿Desea crear un requerimiento de flete?',
            text: "Se utilizará parte de la data del requerimiento base",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, generar'

        }).then((result) => {
            if (result.isConfirmed) {
                openModalRequerimientoFlete(id_od);
            }
        });

    }
}

function cambiarTipoDestinatario(){
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_persona']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_trabajador']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_contribuyente']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='nombre_completo_destinatario']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='numero_documento_destinatario']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] select[name='id_cuenta_destinatario']").innerHTML = "";

}

function openModalRequerimientoFlete(id_od) {
    $('#modal-requerimiento_flete').modal({
        show: true
    });
    mostrarCamposSegunTipoDocumentoSeleccionado();
    llenarFormularioRequerimientoFlete(id_od);
}


function mostrarCamposSegunTipoDocumentoSeleccionado() {
    const tipoDocumento = document.querySelector("div[id='modal-requerimiento_flete'] select[name='tipo_documento']").value;
    console.log(tipoDocumento);
    if (tipoDocumento == 1) {
        document.querySelector("div[id='modal-requerimiento_flete'] div[id='contenedor-destinatario']").setAttribute("hidden", true);
    } else if (tipoDocumento == 11) {
        document.querySelector("div[id='modal-requerimiento_flete'] div[id='contenedor-destinatario']").removeAttribute("hidden");
    } else {
        Lobibox.notify("warning", {
            title: false,
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: "Seleccione un tipo de documento"
        });
    }
}


function abrirModalBuscadorDeDestinatario() {
    const tipoDestinatario = document.querySelector("div[id='modal-requerimiento_flete'] select[name='tipo_destinatario']").value;
    $('#modal-destinatario').modal({
        show: true
    });

    if (tipoDestinatario == 1) {
        $("span[name='tipo_destinatario']").text("Persona");
        listarDestinatariosTipoPersona();
    } else if (tipoDestinatario == 2) {
        $("span[name='tipo_destinatario']").text("Contribuyente");
        listarDestinatariosTipoContribuyente();
    }

}


function listarDestinatariosTipoPersona() {
    var vardataTables = funcDatatables();
    tableElaborado = $("#ListaDestinatario").DataTable({
        dom: vardataTables[1],
        buttons: [],
        language: vardataTables[0],
        destroy: true,
        ajax: "lista-destinatario-persona",
        // 'serverSide' : true,
        columns: [
            { data: "nro_documento",'className': 'text-left' },
            { data: "nombre_completo", 'className': 'text-left'},
            {
                render: function (data, type, row) {
                    return (
                        '<button type="button" class="detalle btn btn-primary boton"' +
                        'data-placement="bottom" title="Seleccionar" data-id-persona="' +row["id_persona"] +'" data-id-trabajador="' +(row["id_trabajador"] !=null ? row["id_trabajador"]:"") +'"  data-nombre-completo-destinatario="' +(row["nombre_completo"] !=null?row["nombre_completo"]:"") +'"  data-numero-documento-destinatario="' +(row["nro_documento"]!=null?row["nro_documento"]:"") +'" onClick="seleccionarPersona(event)">' +
                        'Seleccionar</button>'
                    );
                },'className': 'text-center'
            }
        ],
        columnDefs: [
        ]
    });
}

function seleccionarPersona(event){
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_persona']").value = event.currentTarget.dataset.idPersona;
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_trabajador']").value = event.currentTarget.dataset.idTrabajador;
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_contribuyente']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='nombre_completo_destinatario']").value = event.currentTarget.dataset.nombreCompletoDestinatario??"";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='numero_documento_destinatario']").value = event.currentTarget.dataset.numeroDocumentoDestinatario??"";
    $('#modal-destinatario').modal('hide');
    cargarCuentasDePersona(event.currentTarget.dataset.idPersona);

}
function seleccionarContribuyente(event){
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_persona']").value ="";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_trabajador']").value = "";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_contribuyente']").value = event.currentTarget.dataset.idContribuyente;
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='nombre_completo_destinatario']").value = event.currentTarget.dataset.nombreCompletoDestinatario??"";
    document.querySelector("div[id='modal-requerimiento_flete'] input[name='numero_documento_destinatario']").value = event.currentTarget.dataset.numeroDocumentoDestinatario??"";
    $('#modal-destinatario').modal('hide');
    cargarCuentasDeContribuyente(event.currentTarget.dataset.idContribuyente);

}

function cargarCuentasDeContribuyente(idContribuyente){

    $('#id_cuenta_destinatario').LoadingOverlay("show", {
        imageAutoResize: true,
        progress: true,
        imageColor: "#3c8dbc"
    });

    getSelectCuentasDeContribuyente(idContribuyente).then((res) => {
        $('#id_cuenta_destinatario').LoadingOverlay("hide", true);
        // console.log(res);
        let html='';
        res.forEach(element => {
            html+=`<option value="${element.id_cuenta_contribuyente}">${(element.nro_cuenta!=null && element.nro_cuenta!="") ? element.nro_cuenta:""} ${(element.nro_cuenta_interbancaria!=null && element.nro_cuenta_interbancaria!="") ? (', CCI: '+element.nro_cuenta_interbancaria):""}</option>`;
        });

        const selectCuentas = document.querySelector('div[id="modal-requerimiento_flete"] select[id="id_cuenta_destinatario"]');
        selectCuentas.innerHTML = html;

    });
    
}
function cargarCuentasDePersona(idPersona){

    $('#id_cuenta_destinatario').LoadingOverlay("show", {
        imageAutoResize: true,
        progress: true,
        imageColor: "#3c8dbc"
    });

    getSelectCuentasDePersona(idPersona).then((res) => {
        $('#id_cuenta_destinatario').LoadingOverlay("hide", true);
        // console.log(res);
        let html='';
        res.forEach(element => {
            html+=`<option value="${element.id_cuenta_bancaria}">${(element.nro_cuenta!=null && element.nro_cuenta!="") ? element.nro_cuenta:""} ${(element.nro_cci!=null && element.nro_cci!="") ? (', CCI: '+element.nro_cci):""}</option>`;
        });

        const selectCuentas = document.querySelector('div[id="modal-requerimiento_flete"] select[id="id_cuenta_destinatario"]');
        selectCuentas.innerHTML = html;

    });
    
}

function getSelectCuentasDeContribuyente(idContribuyente){
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `obtener-data-cuentas-de-contribuyente/${idContribuyente}`,
            dataType: 'JSON',
            beforeSend: function (data) {
            },
            success(response) {
                resolve(response);

            },
            fail: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
}
function getSelectCuentasDePersona(idPersona){
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `obtener-data-cuentas-de-persona/${idPersona}`,
            dataType: 'JSON',
            beforeSend: function (data) {
            },
            success(response) {
                resolve(response);

            },
            fail: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
}

function listarDestinatariosTipoContribuyente() {
    var vardataTables = funcDatatables();
    tableElaborado = $("#ListaDestinatario").DataTable({
        dom: vardataTables[1],
        buttons: [],
        language: vardataTables[0],
        destroy: true,
        ajax: "lista-destinatario-contribuyente",
        columns: [
            { data: "documento",'className': 'text-left' },
            { data: "nombre", 'className': 'text-left'},
            {
                render: function (data, type, row) {
                    return (
                        '<button type="button" class="detalle btn btn-primary boton"' +
                        'data-placement="bottom" title="Seleccionar" data-id-contribuyente="' +row["id_contribuyente"] +'" data-nombre-completo-destinatario="' +(row["nombre"] !=null?row["nombre"]:"") +'"  data-numero-documento-destinatario="' +(row["documento"]!=null?row["documento"]:"") +'" onClick="seleccionarContribuyente(event)">' +
                        'Seleccionar</button>'
                    );
                },'className': 'text-center'
            }
        ],
        columnDefs: [
        ]
    });
}


function getRequerimientoOrdenDespacho(id_od) {

    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `mostrar-requerimiento-orden-despacho/${id_od}`,
            dataType: 'JSON',
            beforeSend: function (data) {

                $('#modal-requerimiento_flete .modal-content').LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },
            success(response) {
                resolve(response);
                $('#modal-requerimiento_flete .modal-content').LoadingOverlay("hide", true);

            },
            fail: function (jqXHR, textStatus, errorThrown) {
                $('#modal-requerimiento_flete .modal-content').LoadingOverlay("hide", true);
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
}

function llenarFormularioRequerimientoFlete(id_od) {

    getRequerimientoOrdenDespacho(id_od).then((res) => {
        console.log(res);
        $("[name=empresa]").val(res.requerimiento[0].id_empresa);
        getDataSelectSede(res.requerimiento[0].id_empresa, res.requerimiento[0].id_sede);
        $("[name=sede]").val(res.requerimiento[0].id_sede);
        $("[name=grupo]").val(res.requerimiento[0].id_grupo);
        $("[name=division]").val(res.requerimiento[0].division_id);
        $("[name=cdp]").val(res.requerimiento[0].id_cc);

        $('.selectpicker').selectpicker('refresh');

        // $("[name=proyecto]").val(res.requerimiento[0].id_proyecto);
        // $("[name=partida]").val(res.det_req[0].id_partida);
        // document.querySelector("input[name='partida']").closest("td").querySelector("p[class='descripcion-partida']").textContent= res.det_req[0].codigo_partida;
        // document.querySelector("input[name='centro_costo']").closest("td").querySelector("p[class='descripcion-centro-costo']").textContent= res.det_req[0].codigo_centro_costo;
        // $("[name=descripcion_item]").val(res.det_req[0].descripcion);
        if (fleteObject.hasOwnProperty('importe_total')) {
            console.log(fleteObject);
            $("[name=precio_unitario]").val(fleteObject.precio_unitario);
            $("[name=importe_igv]").val(fleteObject.importe_igv);
            $("[name=importe_total]").val(fleteObject.importe_total);

            $("input[name=fecha_entrega]").val(fleteObject.fecha_entrega);
            $("span[id='precio_unitario']").text($.number(fleteObject.precio_unitario, 2, ".", ","));
            $("span[id='importe_igv']").text($.number(fleteObject.importe_igv, 2, ".", ","));
            $("span[id='importe_total']").text($.number(fleteObject.importe_total, 2, ".", ","));
            let conceptoPersonalizado = 'REQUERIMIENTO DE PAGO DE FLETE ' + (res.requerimiento[0].codigo_oportunidad != "" && res.requerimiento[0].codigo_oportunidad != null ? res.requerimiento[0].codigo_oportunidad : "") + (fleteObject.estado != "" && fleteObject.estado != null ? (', ' + fleteObject.estado) : "");
            $("[name=concepto]").val(conceptoPersonalizado.trim());
            let observacionPersonalizado = res.requerimiento[0].concepto + ' ' + (fleteObject.transportista != "" && fleteObject.transportista != null ? ("/ TRANSPORTISTA: " + fleteObject.transportista) : "");
            $("[name=observacion]").val(observacionPersonalizado.trim());
            fleteObject = {};

        }

    }).catch(function (err) {
        console.log(err)
    }).finally(() => {
        // $('#modal-requerimiento_flete .modal-content').LoadingOverlay("hide", true);
    });

}








$("#form-requerimiento_flete").on("submit", function (e) {
    e.preventDefault();
    let mensajeList = [];
    let continuar = true;

    if (document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_od']").value == '') {
        mensajeList.push("Orden de despacho");
        continuar = false;
    }
    if (["", "0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='empresa']").value)) {
        mensajeList.push("Empresa");
        continuar = false;
    }
    if (["", "0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='sede']").value)) {
        mensajeList.push("Sede");
        continuar = false;
    }
    if (["", "0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='division']").value)) {
        mensajeList.push("Division");
        continuar = false;
    }
    if (["", "0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='cdp']").value)) {
        mensajeList.push("CDP");
        continuar = false;
    }

    if (continuar) {
        guardarRequerimientoFlete();
    } else {
        Swal.fire({
            title: 'No seleccionó una ' + mensajeList.toString() + ', vuelva al listado y selecciona una opción valida',
            icon: "warning",
        });
    }

});

function guardarRequerimientoFlete() {
    $('#submit_od_requerimiento_flete').attr('disabled', 'true');

    let formData = new FormData($('#form-requerimiento_flete')[0]);

    tempArchivoAdjuntoItemsRequerimientoList.forEach(element => {
        if (element.action == 'GUARDAR') {
            formData.append(`archivoAdjuntoRequerimientoDetalleGuardar${element.id_detalle_requerimiento}[]`, element.file);
        }
    });


    $.ajax({
        type: 'POST',
        url: 'guardar-requerimiento-flete',
        data: formData,
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log(response);
            if (response.status == 'success') {
                $('#modal-requerimiento_flete').modal('hide');
                Lobibox.notify("success", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: response.mensaje
                });

                tempArchivoAdjuntoItemsRequerimientoList = [];
                objBotonAdjuntoRequerimientoDetalleSeleccionado = '';
            } else {
                Lobibox.notify("error", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: response.mensaje
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    }).always(function (dataOrjqXHR, textStatus, jqXHRorErrorThrown) {
        $('#submit_od_requerimiento_flete').removeAttr('disabled');
    });

}


//sección para generar requerimiento

function handleChangeEmpresa(obj) {
    getDataSelectSede(obj.value);
}

function obtenerSede(idEmpresa) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `listar-sedes-por-empresa/${idEmpresa}`,
            dataType: 'JSON',
            success(response) {
                resolve(response);
            },
            error: function (err) {
                reject(err)
            }
        });
    });
}

function getDataSelectSede(idEmpresa = null, idSede = null) {
    if (idEmpresa > 0) {
        obtenerSede(idEmpresa).then((res) => {
            this.llenarSelectSede(res, idSede);
            // this.cargarAlmacenes($('[name=sede]').val());
            // this.seleccionarAlmacen();
            // this.llenarUbigeo();
        }).catch(function (err) {
            console.log(err)
        })
    }
    return false;
}

function llenarSelectSede(array, idSede) {
    // console.log(idSede);
    let selectElement = document.querySelector("select[name='sede']");
    if (selectElement.options.length > 0) {
        let i, L = selectElement.options.length - 1;
        for (i = L; i >= 0; i--) {
            selectElement.remove(i);
        }
    }

    array.forEach(element => {
        let option = document.createElement("option");
        option.text = element.descripcion;
        option.value = element.id_sede;
        if (element.id_sede == idSede) {
            option.setAttribute('selected', 'selected');
            document.querySelector("input[name='almacen']").value = element.id_almacen;
        }
        option.setAttribute('data-ubigeo', element.id_ubigeo);
        option.setAttribute('data-name-ubigeo', element.ubigeo_descripcion);
        option.setAttribute('data-id-almacen', element.id_almacen);
        selectElement.add(option);
    });

    $('.selectpicker').selectpicker('refresh')

}

function handleChangeSede(obj) {
    var id_almacen = obj.options[obj.selectedIndex].getAttribute('data-id-almacen');
    document.querySelector("input[name='almacen']").value = id_almacen;
}

function handleChangeDivision(obj) {
    const idGrupo = obj.options[obj.selectedIndex].getAttribute('data-id-grupo');
    $("input[name='grupo']").val(idGrupo);

    // if (idGrupo == 2) { // grupo comercial, habilitar select CDP,  deshabilitar select proyectos y setear a vacio
    //     $(".selectpicker[name='proyecto']").prop("disabled", true);
    //     $(".selectpicker[name='proyecto']").val('');
    //     $(".selectpicker[name='cdp']").prop("disabled", false);

    // } else if (idGrupo == 3) { // grupo proyectos, habilitar select proyectos, deshabilitar select CDP y setear a vacio
    //     $(".selectpicker[name='cdp']").prop("disabled", true);
    //     $(".selectpicker[name='cdp']").val('');
    //     $(".selectpicker[name='proyecto']").prop("disabled", false);
    // }
    $('.selectpicker').selectpicker('refresh');
}


// function handleChangeAlmacen(obj) {
//     const idAlmacen = obj.options[obj.selectedIndex].getAttribute('data-id-almacen');
//     $("input[name='almacen']").val(idAlmacen);
// }





//Modal Partidas 

// function listarPartidas(idGrupo, idProyecto) {
//     limpiarTabla('listaPartidas');
//     obtenerListaPartidas(idGrupo, idProyecto).then((res) => {
//         construirListaPartidas(res);

//     }).catch(function (err) {
//         console.log(err)
//     })
// }

// function obtenerListaPartidas(idGrupo, idProyecto) {
//     return new Promise(function (resolve, reject) {
//         $.ajax({
//             type: 'GET',
//             url: `mostrar-partidas/${idGrupo}/${idProyecto}`,
//             dataType: 'JSON',
//             beforeSend: function (data) {
//                 var customElement = $("<div>", {
//                     "css": {
//                         "font-size": "24px",
//                         "text-align": "center",
//                         "padding": "0px",
//                         "margin-top": "-400px"
//                     },
//                     "class": "your-custom-class"
//                 });

//                 $('#modal-partidas div.modal-body').LoadingOverlay("show", {
//                     imageAutoResize: true,
//                     progress: true,
//                     custom: customElement,
//                     imageColor: "#3c8dbc"
//                 });
//             },
//             success(response) {
//                 resolve(response);
//             },
//             fail: function (jqXHR, textStatus, errorThrown) {
//                 $('#modal-partidas div.modal-body').LoadingOverlay("hide", true);
//                 alert("Hubo un problema al cargar las partidas. Por favor actualice la página e intente de nuevo");
//                 console.log(jqXHR);
//                 console.log(textStatus);
//                 console.log(errorThrown);
//             }
//         });
//     });
// }

// function construirListaPartidas(data) {

//     let html = '';
//     let isVisible = '';

//     if (data['presupuesto'].length > 0) {
//         data['presupuesto'].forEach(presupuesto => {
//             html += `
//                 <div id='${presupuesto.codigo}' class="panel panel-primary" style="width:100%; overflow: auto;">
//                     <h5 class="panel-heading handleClickapertura" data-id-presup="${presupuesto.id_presup}" style="margin: 0; cursor: pointer;">
//                     <i class="fas fa-chevron-right"></i>
//                         &nbsp; ${presupuesto.descripcion}
//                     </h5>
//                     <div id="pres-${presupuesto.id_presup}" class="oculto" style="width:100%;">
//                         <table class="table table-bordered table-condensed partidas" id="listaPartidas" width="100%" style="font-size:0.9em">
//                             <tbody>
//                 `;

//             data['titulos'].forEach(titulo => {
//                 if (titulo.id_presup == presupuesto.id_presup) {
//                     html += `
//                         <tr id="com-${titulo.id_titulo}">
//                             <td><strong>${titulo.codigo}</strong></td>
//                             <td><strong>${titulo.descripcion}</strong></td>
//                             <td class="right ${isVisible}"><strong>S/${Util.formatoNumero(titulo.total, 2)}</strong></td>
//                         </tr> `;

//                     data['partidas'].forEach(partida => {
//                         if (partida.id_presup == presupuesto.id_presup) {
//                             if (titulo.codigo == partida.cod_padre) {
//                                 html += `<tr id="par-${partida.id_partida}">
//                                         <td style="width:15%; text-align:left;" name="codigo">${partida.codigo}</td>
//                                         <td style="width:75%; text-align:left;" name="descripcion">${partida.descripcion}</td>
//                                         <td style="width:15%; text-align:right;" name="importe_total" class="right ${isVisible}" data-presupuesto-total="${partida.importe_total}" >S/${Util.formatoNumero(partida.importe_total, 2)}</td>
//                                         <td style="width:5%; text-align:center;"><button class="btn btn-success btn-xs handleClickSelectPartida" data-id-partida="${partida.id_partida}">Seleccionar</button></td>
//                                     </tr>`;
//                             }
//                         }
//                     });

//                 }


//             });
//             html += `
//                         </tbody>
//                     </table>
//                 </div>
//             </div>`;
//         });
//     } else {
//         html += `
//         <div class="panel panel-warning" style="width:100%; overflow: auto; text-align:center;">
//             <h5 class="panel-heading" style="margin: 0;">
//                 &nbsp; Sin data para mostrar
//             </h5>
//         </div>
//         `;
//     }

//     document.querySelector("div[id='listaPartidas']").innerHTML = html;





//     $('#modal-partidas div.modal-body').LoadingOverlay("hide", true);

// }


function apertura(idPresup) {
    // let idPresup = e.target.dataset.idPresup;
    if ($("#pres-" + idPresup + " ").hasClass('oculto')) {
        $("#pres-" + idPresup + " ").removeClass('oculto');
        $("#pres-" + idPresup + " ").addClass('visible');
    } else {
        $("#pres-" + idPresup + " ").removeClass('visible');
        $("#pres-" + idPresup + " ").addClass('oculto');
    }


}

function changeBtnIcon(obj) {

    if (obj.currentTarget.children[0].className == 'fas fa-chevron-right') {

        obj.currentTarget.children[0].classList.replace('fa-chevron-right', 'fa-chevron-down')
    } else {
        obj.currentTarget.children[0].classList.replace('fa-chevron-down', 'fa-chevron-right')
    }
}

// function selectPartida(idPartida) {
//     // console.log(idPartida);
//     let codigo = $("#par-" + idPartida + " ").find("td[name=codigo]")[0].innerHTML;
//     let descripcion = $("#par-" + idPartida + " ").find("td[name=descripcion]")[0].innerHTML;
//     let presupuestoTotal = $("#par-" + idPartida + " ").find("td[name=importe_total]")[0].dataset.presupuestoTotal;
//     let presupuestoMes = ($("#par-" + idPartida + " ").find("td[name=importe_mes]")[0]) != null ? $("#par-" + idPartida + " ").find("td[name=importe_mes]")[0].dataset.presupuestoMes : 0;

//     tempObjectBtnPartida.nextElementSibling.querySelector("input[class='partida']").value = idPartida;
//     tempObjectBtnPartida.textContent = 'Cambiar';

//     let tr = tempObjectBtnPartida.closest("tr");
//     tr.querySelector("p[class='descripcion-partida']").dataset.idPartida = idPartida;
//     tr.querySelector("p[class='descripcion-partida']").textContent = codigo
//     tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal = presupuestoTotal;
//     tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoMes = presupuestoMes;
//     tr.querySelector("p[class='descripcion-partida']").setAttribute('title', descripcion);
//     $('#modal-partidas').modal('hide');
// }

// function handleClickCargarModalPartidas(obj) {
//     limpiarTabla('listaPartidas');
//     let continuar= false;
//     tempObjectBtnPartida = obj.target;
//     let id_grupo = document.querySelector("input[name='grupo']").value;
//     let id_proyecto = document.querySelector("select[name='proyecto']").value;

//     if (id_grupo > 0) {
//         if(id_grupo == 3){ // si la seleccionó un grupo
//             if(id_proyecto >0){ // si seleccionó un proyecto
//                 continuar=true;
//             }else{
//                 Swal.fire(
//                     '',
//                     'Debe seleccionar primero un proyecto',
//                     'warning'
//                 ); 
//             }
//         }

//         if(id_grupo==2){
//             continuar=true;
//         }


//         if(continuar){
//             $('#modal-partidas').modal({
//                 show: true,
//                 backdrop: 'true'
//             });

//             if (!$("select[name='id_presupuesto_interno']").val() > 0) { //* si presupuesto interno fue seleccionado, no cargar presupuesto antiguo.

//                 listarPartidas(id_grupo, (id_proyecto > 0 ? id_proyecto : 0));
//             }
//         }

//     } else {
//         Swal.fire(
//             '',
//             'Debe seleccionar primero la división para cargar las opciones.',
//             'warning'
//         );
//     }
// }


//Modal centro de costos 

function cargarModalCentroCostos(obj) {
    tempObjectBtnCentroCostos = obj.target;

    $('#modal-centro-costos').modal({
        show: true
    });
    listarCentroCostos();
}

function obtenerCentroCostos() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `mostrar-centro-costos`,
            dataType: 'JSON',
            beforeSend: function (data) {

                $('#modal-centro-costos div.modal-body').LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },
            success(response) {
                resolve(response);
            },
            fail: function (jqXHR, textStatus, errorThrown) {
                $('#modal-centro-costos div.modal-body').LoadingOverlay("hide", true);
                alert("Hubo un problema al cargar los centro de costo. Por favor actualice la página e intente de nuevo");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
}

function listarCentroCostos() {
    limpiarTabla('listaCentroCosto');

    obtenerCentroCostos().then((res) => {
        construirCentroCostos(res);
    }).catch(function (err) {
        console.log(err)
    })
}

function construirCentroCostos(data) {
    let html = '';
    data.forEach((padre, index) => {
        if (padre.id_padre == null) {
            html += `
                <div id='${index}' class="panel panel-primary" style="width:100%; overflow: auto;">
                <h5 class="panel-heading handleClickapertura" style="margin: 0; cursor: pointer;" data-id-presup="${index}">
                <i class="fas fa-chevron-right"></i>
                    &nbsp; ${padre.descripcion}
                </h5>
                <div id="pres-${index}" class="oculto" style="width:100%;">
                    <table class="table table-bordered table-condensed partidas" id='listaCentroCosto' width="" style="font-size:0.9em">
                        <thead>
                            <tr>
                            <td style="width:5%"></td>
                            <td style="width:90%"></td>
                            <td style="width:5%"></td>
                            </tr>
                        </thead>
                        <tbody>`;

            data.forEach(hijo => {
                if (padre.id_centro_costo == hijo.id_padre) {
                    if ((hijo.id_padre > 0) && (hijo.estado == 1)) {
                        if (hijo.nivel == 2) {
                            html += `
                                <tr id="com-${hijo.id_centro_costo}">
                                    <td><strong>${hijo.codigo}</strong></td>
                                    <td><strong>${hijo.descripcion}</strong></td>
                                    <td style="width:5%; text-align:center;"></td>
                                </tr> `;
                        }
                    }
                    data.forEach(hijo3 => {
                        if (hijo.id_centro_costo == hijo3.id_padre) {
                            if ((hijo3.id_padre > 0) && (hijo3.estado == 1)) {
                                // console.log(hijo3);
                                if (hijo3.nivel == 3) {
                                    html += `
                                        <tr id="com-${hijo3.id_centro_costo}">
                                            <td>${hijo3.codigo}</td>
                                            <td>${hijo3.descripcion}</td>
                                            <td style="width:5%; text-align:center;">${hijo3.seleccionable ? `<button class="btn btn-success btn-xs handleClickSelectCentroCosto" data-id-centro-costo="${hijo3.id_centro_costo}" data-codigo="${hijo3.codigo}" data-descripcion-centro-costo="${hijo3.descripcion}" >Seleccionar</button>` : ''}</td>
                                        </tr> `;
                                }
                            }
                            data.forEach(hijo4 => {
                                if (hijo3.id_centro_costo == hijo4.id_padre) {
                                    // console.log(hijo4);
                                    if ((hijo4.id_padre > 0) && (hijo4.estado == 1)) {
                                        if (hijo4.nivel == 4) {
                                            html += `
                                                <tr id="com-${hijo4.id_centro_costo}">
                                                    <td>${hijo4.codigo}</td>
                                                    <td>${hijo4.descripcion}</td>
                                                    <td style="width:5%; text-align:center;">${hijo4.seleccionable ? `<button class="btn btn-success btn-xs handleClickSelectCentroCosto" data-id-centro-costo="${hijo4.id_centro_costo}" data-codigo="${hijo4.codigo}" data-descripcion-centro-costo="${hijo4.descripcion}">Seleccionar</button>` : ''}</td>
                                                </tr> `;
                                        }
                                    }
                                }
                            });
                        }

                    });
                }


            });
            html += `
                </tbody>
            </table>
        </div>
    </div>`;
        }
    });
    document.querySelector("div[name='centro-costos-panel']").innerHTML = html;



    $('#modal-centro-costos div.modal-body').LoadingOverlay("hide", true);

}

function selectCentroCosto(idCentroCosto, codigo, descripcion) {
    // console.log(idCentroCosto);
    tempObjectBtnCentroCostos.nextElementSibling.querySelector("input").value = idCentroCosto;
    tempObjectBtnCentroCostos.textContent = 'Cambiar';

    let tr = tempObjectBtnCentroCostos.closest("tr");
    tr.querySelector("p[class='descripcion-centro-costo']").textContent = codigo
    tr.querySelector("p[class='descripcion-centro-costo']").setAttribute('title', descripcion);
    $('#modal-centro-costos').modal('hide');
    tempObjectBtnCentroCostos = null;
}


function modalAdjuntarArchivosDetalle(obj) {
    objBotonAdjuntoRequerimientoDetalleSeleccionado = obj;
    $('#modal-adjuntar-archivos-detalle-requerimiento').modal({
        show: true,
        backdrop: 'true'
    });
    this.limpiarTabla('listaArchivos');

    $(":file").filestyle('clear');

    this.listarArchivosAdjuntosDetalle(obj.dataset.id);

}

function listarArchivosAdjuntosDetalle(idDetalleRequerimiento) {
    if (idDetalleRequerimiento.length > 0) {
        this.limpiarTabla('listaArchivos');
        let html = '';
        tempArchivoAdjuntoItemsRequerimientoList.forEach(element => {
            if (idDetalleRequerimiento.length > 0 && idDetalleRequerimiento == element.id_detalle_requerimiento) {

                html += `<tr id="${element.id}" style="text-align:center">
            <td style="text-align:left;">${element.nameFile}</td>
            <td style="text-align:center;">
                <div class="btn-group" role="group">`;
                if (Number.isInteger(element.id)) {
                    html += `<button type="button" class="btn btn-info btn-md handleClickDescargarArchivoRequerimientoDetalle" name="btnDescargarArchivoRequerimientoDetalle" title="Descargar" data-id="${element.id}" ><i class="fas fa-paperclip"></i></button>`;
                }

                html += `<button type="button" class="btn btn-danger btn-md handleClickEliminarArchivoRequerimientoDetalle" name="btnEliminarArchivoRequerimientoDetalle" title="Eliminar" data-id="${element.id}" ><i class="fas fa-trash-alt"></i></button>

                </div>
            </td>
            </tr>`;
            }
        });
        document.querySelector("tbody[id='body_archivos_item']").insertAdjacentHTML('beforeend', html);
    }
}


function agregarAdjuntoRequerimientoPagoDetalle(obj) {
    if (obj.files != undefined && obj.files.length > 0) {
        Array.prototype.forEach.call(obj.files, (file) => {
            if (this.estaHabilitadoLaExtension(file) == true) {
                let payload = {
                    id: this.makeId(),
                    id_detalle_requerimiento: objBotonAdjuntoRequerimientoDetalleSeleccionado.dataset.id,
                    nameFile: file.name,
                    action: 'GUARDAR',
                    file: file
                };
                this.agregarRegistroEnTablaAdjuntoRequerimientoDetalle(payload);
                tempArchivoAdjuntoItemsRequerimientoList.push(payload);
            } else {
                Swal.fire(
                    'Este tipo de archivo no esta permitido adjuntar',
                    file.name,
                    'warning'
                );
            }
        });
    }
    return false;
}

function makeId() {
    let ID = "";
    let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (let i = 0; i < 12; i++) {
        ID += characters.charAt(Math.floor(Math.random() * 36));
    }
    return ID;
}


function estaHabilitadoLaExtension(file) {
    let extension = file.name.match(/(?<=\.)\w+$/g)[0].toLowerCase(); // assuming that this file has any extension
    if (extension === 'dwg'
        || extension === 'dwt'
        || extension === 'cdr'
        || extension === 'back'
        || extension === 'backup'
        || extension === 'psd'
        || extension === 'sql'
        || extension === 'exe'
        || extension === 'html'
        || extension === 'js'
        || extension === 'php'
        || extension === 'ai'
        || extension === 'mp4'
        || extension === 'mp3'
        || extension === 'avi'
        || extension === 'mkv'
        || extension === 'flv'
        || extension === 'mov'
        || extension === 'wmv'
    ) {
        return false;
    } else {
        return true;
    }
}

function agregarRegistroEnTablaAdjuntoRequerimientoDetalle(payload) {
    let html = '';
    html = `<tr id="${payload.id}" style="text-align:center">
    <td style="text-align:left;">${payload.nameFile}</td>
    <td style="text-align:center;">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-danger btn-xs handleClickEliminarArchivoRequerimientoDetalle" name="btnEliminarArchivoRequerimientoDetalle" title="Eliminar" data-id="${payload.id}" ><i class="fas fa-trash-alt"></i></button>
        </div>
    </td>
    </tr>`;

    document.querySelector("tbody[id='body_archivos_item']").insertAdjacentHTML('beforeend', html);
}

function eliminarArchivoRequerimientoDetalle(obj) {

    // tempIdArchivoAdjuntoRequerimientoPagoDetalleToDeleteList.push(obj.dataset.id);
    var regExp = /[a-zA-Z]/g; //expresión regular
    if ((regExp.test(obj.dataset.id) == true)) {

        tempArchivoAdjuntoItemsRequerimientoList = tempArchivoAdjuntoItemsRequerimientoList.filter((element, i) => element.id != obj.dataset.id);
        obj.closest("tr").remove();
    } else {
        if (tempArchivoAdjuntoItemsRequerimientoList.length > 0) {
            let indice = tempArchivoAdjuntoItemsRequerimientoList.findIndex(elemnt => elemnt.id == obj.dataset.id);
            tempArchivoAdjuntoItemsRequerimientoList[indice].action = 'ELIMINAR';
            obj.closest("tr").remove();
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar eliminar el adjunto del item, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }

    }
}







// aux funcions
function limpiarTabla(idElement) {
    let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
    if (nodeTbody != null) {
        while (nodeTbody.children.length > 0) {
            nodeTbody.removeChild(nodeTbody.lastChild);
        }

    }
}

