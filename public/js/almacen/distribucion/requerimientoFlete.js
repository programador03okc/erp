
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



function consultaGuardarRequerimientoFlete(id_od){
    
    if(id_od>0){
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
                $('#modal-requerimiento_flete').modal({
                    show: true
                });
                llenarFormularioRequerimientoFlete(id_od);
    
            }
        });

    }
}



function getRequerimientoOrdenDespacho(id_od){

    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`mostrar-requerimiento-orden-despacho/${id_od}`,
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

function llenarFormularioRequerimientoFlete(id_od){

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
        if(fleteObject.hasOwnProperty('importe_total')){
            console.log(fleteObject);
            $("[name=precio_unitario]").val(fleteObject.precio_unitario);
            $("[name=importe_igv]").val(fleteObject.importe_igv);
            $("[name=importe_total]").val(fleteObject.importe_total);
            
            $("input[name=fecha_entrega]").val(fleteObject.fecha_entrega);
            $("span[id='precio_unitario']").text($.number(fleteObject.precio_unitario, 2, ".", ","));
            $("span[id='importe_igv']").text($.number(fleteObject.importe_igv, 2, ".", ","));
            $("span[id='importe_total']").text($.number(fleteObject.importe_total, 2, ".", ","));
            let conceptoPersonalizado ='REQUERIMIENTO DE PAGO DE FLETE '+(res.requerimiento[0].codigo_oportunidad!="" && res.requerimiento[0].codigo_oportunidad!=null ?res.requerimiento[0].codigo_oportunidad:"")+(fleteObject.estado!="" && fleteObject.estado!=null?(', '+fleteObject.estado):"");
            $("[name=concepto]").val(conceptoPersonalizado.trim());
            let observacionPersonalizado = res.requerimiento[0].concepto+' '+(fleteObject.transportista !="" && fleteObject.transportista !=null ? ("/ TRANSPORTISTA: "+fleteObject.transportista):"" );
            $("[name=observacion]").val(observacionPersonalizado.trim());
            fleteObject={};

        }

    }).catch(function (err) {
        console.log(err)
    }).finally(() => {
        // $('#modal-requerimiento_flete .modal-content').LoadingOverlay("hide", true);
    });

}








$("#form-requerimiento_flete").on("submit", function (e) {
    e.preventDefault();    
    let mensajeList=[];
    let continuar = true;

    if (document.querySelector("div[id='modal-requerimiento_flete'] input[name='id_od']").value == '') {
        mensajeList.push("Orden de despacho");
        continuar = false;
    }
    if (["","0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='empresa']").value)) {
        mensajeList.push("Empresa");
        continuar = false;
    }
    if (["","0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='sede']").value)) {
        mensajeList.push("Sede");
        continuar = false;
    }
    if (["","0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='division']").value)) {
        mensajeList.push("Division");
        continuar = false;
    }
    if (["","0"].includes(document.querySelector("div[id='modal-requerimiento_flete'] select[name='cdp']").value)) {
        mensajeList.push("CDP");
        continuar = false;
    }

    if(continuar){
        guardarRequerimientoFlete();
    }else{
        Swal.fire({
            title:  'No seleccionó una '+mensajeList.toString()+', vuelva al listado y selecciona una opción valida',
            icon: "warning",
        });
    }
    
});

function guardarRequerimientoFlete(){
    $('#submit_od_requerimiento_flete').attr('disabled', 'true');
    $.ajax({
        type: 'POST',
        url: 'guardar-requerimiento-flete',
        data: $('form[id="form-requerimiento_flete"]').serialize(),
        dataType: 'JSON',
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
            }else{
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
            document.querySelector("input[name='almacen']").value=element.id_almacen;
        }
        option.setAttribute('data-ubigeo', element.id_ubigeo);
        option.setAttribute('data-name-ubigeo', element.ubigeo_descripcion);
        option.setAttribute('data-id-almacen', element.id_almacen);
        selectElement.add(option);
    });

    $('.selectpicker').selectpicker('refresh')

}

function handleChangeSede(obj){
    var id_almacen = obj.options[obj.selectedIndex].getAttribute('data-id-almacen');
    document.querySelector("input[name='almacen']").value=id_almacen;
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







// aux funcions
function limpiarTabla(idElement) {
    let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
    if (nodeTbody != null) {
        while (nodeTbody.children.length > 0) {
            nodeTbody.removeChild(nodeTbody.lastChild);
        }

    }
}

