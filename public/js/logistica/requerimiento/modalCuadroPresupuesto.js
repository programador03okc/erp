$('#form-requerimiento').on("click", "button.handleClickModalListaCuadroDePresupuesto", () => {
    this.modalListaCuadroDePresupuesto();
});
$('#form-requerimiento-pago').on("click", "button.handleClickModalListaCuadroDePresupuesto", () => {
    this.modalListaCuadroDePresupuesto();
});
$('#form-requerimiento').on("change", "select.handleChangeEstadoEnvio", (e) => {
    this.seleccionarEstadoEnvio(e.currentTarget);
});
$('#form-requerimiento-pago').on("change", "select.handleChangeEstadoEnvio", (e) => {
    this.seleccionarEstadoEnvio(e.currentTarget);
});
$('#listaCuadroPresupuesto').on("click", "button.handleClickAgregarCDP", (e) => {
    this.agregarCDP(e.currentTarget);
});

var cdpVinculadoConRequerimientoList = [];

function modalListaCuadroDePresupuesto() {
    $('#modal-lista-cuadro-presupuesto').modal({
        show: true
    });
    this.listarCuadroPresupuesto();
}

function listarCuadroPresupuesto() {
    var vardataTables = funcDatatables();
    $tablaListaCuadroPresupuesto = $('#listaCuadroPresupuesto').DataTable({
        'dom': vardataTables[1],
        'buttons': [],
        'language': vardataTables[0],
        'order': [[7, 'desc']],
        'bLengthChange': false,
        'serverSide': true,
        'destroy': true,
        'ajax': {
            'url': 'lista-cuadro-presupuesto',
            'type': 'POST',
            beforeSend: data => {

                $("#listaCuadroPresupuesto").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },

        },
        'columns': [
            { 'data': 'codigo_oportunidad', 'name': 'cc_view.codigo_oportunidad', 'className': 'text-center' },
            { 'data': 'descripcion_oportunidad', 'name': 'cc_view.descripcion_oportunidad', 'className': 'text-left' },
            { 'data': 'fecha_creacion', 'name': 'cc_view.fecha_creacion', 'className': 'text-center' },
            { 'data': 'fecha_limite', 'name': 'cc_view.fecha_limite', 'className': 'text-center' },
            { 'data': 'nombre_entidad', 'name': 'cc_view.nombre_entidad', 'className': 'text-left' },
            { 'data': 'name', 'name': 'cc_view.name', 'className': 'text-center' },
            { 'data': 'estado_aprobacion', 'name': 'cc_view.estado_aprobacion', 'className': 'text-center' },
            { 'data': 'id', 'name': 'cc_view.id', }
        ],
        'columnDefs': [


            {
                'render': function (data, type, row) {
                    let containerOpenBrackets = '<center><div class="btn-group" role="group" style="margin-bottom: 5px;">';
                    let containerCloseBrackets = '</div></center>';
                    let btnSeleccionar = `<button type="button" class="btn btn-xs btn-success handleClickAgregarCDP"  data-id-cc="${row.id}"  data-codigo-oportunidad="${row.codigo_oportunidad}"  data-nombre-entidad="${row.nombre_entidad}" title="Agregar">Agregar</button>`;
                    return containerOpenBrackets + btnSeleccionar + containerCloseBrackets;
                }, targets: 7
            },

        ],
        'initComplete': function () {
            // that.updateContadorFiltroRequerimientosElaborados();

            //Boton de busqueda
            const $filter = $('#listaCuadroPresupuesto_filter');
            const $input = $filter.find('input');
            $filter.append('<button id="btnBuscarCDP" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
            $input.off();
            $input.on('keyup', (e) => {
                if (e.key == 'Enter') {
                    $('#btnBuscarCDP').trigger('click');
                }
            });
            $('#btnBuscarCDP').on('click', (e) => {
                $tablaListaCuadroPresupuesto.search($input.val()).draw();
            })
            //Fin boton de busqueda

        },
        "drawCallback": function (settings) {
            if ($tablaListaCuadroPresupuesto.rows().data().length == 0) {
                Lobibox.notify('info', {
                    title: false,
                    size: 'mini',
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: `No se encontro data disponible para mostrar`
                });
            }
            //Botón de búsqueda
            $('#listaCuadroPresupuesto_filter input').prop('disabled', false);
            $('#btnBuscarCDP').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
            $('#listaCuadroPresupuesto_filter input').trigger('focus');
            //fin botón búsqueda
            $("#listaCuadroPresupuesto").LoadingOverlay("hide", true);
        }
    });
    //Desactiva el buscador del DataTable al realizar una busqueda
    $tablaListaCuadroPresupuesto.on('search.dt', function () {
        $('#tableDatos_filter input').prop('disabled', true);
        $('#btnBuscarCDP').html('<span class="glyphicon glyphicon-time" aria-hidden="true"></span>').attr('disabled', true);
    });

}

function makeId() {
    let ID = "";
    let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (let i = 0; i < 12; i++) {
        ID += characters.charAt(Math.floor(Math.random() * 36));
    }
    return ID;
}

function agregarCDP(obj) {
    if (obj.dataset.idCc > 0) {

        if (!cdpVinculadoConRequerimientoList.includes(obj.dataset.codigoOportunidad)) {
            cdpVinculadoConRequerimientoList.push(obj.dataset.codigoOportunidad);
            const element =
            {
                id_cdp_requerimiento: makeId(),
                id_cc: obj.dataset.idCc,
                codigo_oportunidad: obj.dataset.codigoOportunidad,
                nombre_entidad: obj.dataset.nombreEntidad,
                monto: 0
            };
            agregarEnTablaCuadroPresupuestoVinculados(element);

            Lobibox.notify('success', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: "Se agrego a la lista el CDP: " + obj.dataset.codigoOportunidad
            });

        } else {
            Lobibox.notify('warning', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: "El CDP: " + obj.dataset.codigoOportunidad + " ya fue agregado"
            });
        }

    } else {
        Swal.fire(
            '',
            'Lo sentimos hubo un error al intentar obtener el id del cuadro de presupuesto, por favor vuelva a intentarlo',
            'error'
        );
    }
    // $('#modal-lista-cuadro-presupuesto').modal('hide');
}


function limpiarTabla(idElement) {
    let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
    if (nodeTbody != null) {
        while (nodeTbody.children.length > 0) {
            nodeTbody.removeChild(nodeTbody.lastChild);
        }

    }
}


function obtenerEstadosEnvioTrazabilidadDespacho() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: `obtener-estados-envio-trazabilidad-despacho`,
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


function seleccionarEstadoEnvio(obj){
    if(obj.value >0){
        Lobibox.notify('info', {
            title: false,
            size: 'mini',
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: `Al guardar los cambios en el requerimiento se agregara a la trazabilidad de la ODE`
        });
    }

}

function agregarEnTablaCuadroPresupuestoVinculados(element) {
    // console.log(element);

    let html='';
    obtenerEstadosEnvioTrazabilidadDespacho().then((estadosEnvio) => {
        html+= `
        <tr>
            <td style="text-align:center;">
                <input type="text" name="id_cpd_vinculado[]" value="${element.id_cdp_requerimiento}" hidden>
                <input type="text" name="id_cc_cpd_vinculado[]" value="${element.id_cc}" hidden>
                <input type="text" name="codigo_oportunidad_cpd_vinculado[]" value="${element.codigo_oportunidad}" hidden> ${element.codigo_oportunidad}</td>
            <td style="text-align:left;"><input type="text" name="nombre_entidad_cpd_vinculado[]" value="${element.nombre_entidad}" hidden> ${element.nombre_entidad}</td>
            <td style="text-align:right;"><span>S/</span> <input type="numeric" min="0" name="monto_cpd_vinculado[]" value="${parseFloat(element.monto)}"> </td>
            <td style="text-align:right;"> <select class="form-control handleChangeEstadoEnvio" name="id_estado_envio[]"><option value="0">Seleccione un estado para enviar a trazabilidad</option>`;
            estadosEnvio.forEach(ee => {
                if(element.id_estado_envio >0 && ee.id_estado == element.id_estado_envio){
                        html += `<option value="${ee.id_estado}" selected>${ee.descripcion}</option>`;
                }else{
                    html += `<option value="${ee.id_estado}">${ee.descripcion}</option>`;
                }
            });
            html+=`</select> </td>
            <td style="text-align:right;"><input type="date" name="fecha_estado[]" value="${element.fecha_estado !=null ?element.fecha_estado:moment().format("YYYY-MM-DD")}"> </td>
            <td style="text-align:center;">
                <button type="button" class="btn btn-danger btn-xs activation" name="btnEliminarVinculoConCdp" data-id="${element.id}" data-codigo-oportunidad="${element.codigo_oportunidad}" onClick="eliminarVinculoConCdp(event);"title="Eliminar">
                <i class="fas fa-trash"></i>
                </button>
            </td>
            </tr>`;

            document.querySelector("tbody[id='body_cdp_vinculados']").insertAdjacentHTML('beforeend',html );
    });
 
}

function eliminarVinculoConCdp(e) {
    const obj = e.currentTarget;
    var regExp = /[a-zA-Z]/g; //expresión regular
    if ((regExp.test(obj.dataset.id) == true)) {
        obj.closest("tr").remove();
        Lobibox.notify('info', {
            title: false,
            size: 'mini',
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: "Vínculo con " + e.currentTarget.dataset.codigoOportunidad + " eliminado"
        });
    } else {
        obj.closest("tr").remove();

        Lobibox.notify('info', {
            title: false,
            size: 'mini',
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: "Para hacer efecto los cambios se requeire guardar los cambios del formulario."
        });
    }

    cdpVinculadoConRequerimientoList.forEach((element,key) => {
        if(element ==e.currentTarget.dataset.codigoOportunidad){
            cdpVinculadoConRequerimientoList.splice(key, 1);
        }
    });


}