let $tableRequerimientos;

function listarRequerimientosAlmacen(id_usuario) {
    console.log('list');
    var vardataTables = funcDatatables();
    let botones = [];

    $("#requerimientosAlmacen").on('search.dt', function () {
        $('#requerimientosAlmacen_filter input').prop('disabled', true);
        $('#btnBuscar').html('<span class="glyphicon glyphicon-time" aria-hidden="true"></span>').prop('disabled', true);
    });

    $("#requerimientosAlmacen").on('processing.dt', function (e, settings, processing) {
        if (processing) {
            $(e.currentTarget).LoadingOverlay("show", {
                imageAutoResize: true,
                progress: true,
                zIndex: 10,
                imageColor: "#3c8dbc"
            });
        } else {
            $(e.currentTarget).LoadingOverlay("hide", true);
        }
    });

    $tableRequerimientos = $('#requerimientosAlmacen').DataTable({
        dom: vardataTables[1],
        buttons: botones,
        language: vardataTables[0],
        // destroy: true,
        pageLength: 10,
        serverSide: true,
        initComplete: function (settings, json) {
            const $filter = $("#requerimientosAlmacen_filter");
            const $input = $filter.find("input");
            $filter.append(
                '<button id="btnBuscar" class="btn btn-default btn-sm btn-flat" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>'
            );
            $input.off();
            $input.on("keyup", e => {
                if (e.key == "Enter") {
                    $("#btnBuscar").trigger("click");
                }
            });
            $("#btnBuscar").on("click", e => {
                $tableRequerimientos.search($input.val()).draw();
            });

            // const $form = $('#formFiltrosSalidasPendientes');
            // $('#requerimientosAlmacen_wrapper .dt-buttons').append(
            //     `<div style="display:flex">
            //         <label style="text-align: center;margin-top: 7px;margin-left: 10px;margin-right: 10px;">Mostrar: </label>
            //         <select class="form-control" id="selectMostrarPendientes">
            //             <option value="0" >Todos</option>
            //             <option value="1" >Priorizados</option>
            //             <option value="2" selected>Los de Hoy</option>
            //         </select>
            //     </div>`
            // );
            // $("#selectMostrarPendientes").on("change", function (e) {
            //     var sed = $(this).val();
            //     console.log('sel ' + sed);
            //     $('#formFiltrosSalidasPendientes').find('input[name=select_mostrar_pendientes]').val(sed);
            //     $("#requerimientosAlmacen").DataTable().ajax.reload(null, false);
            // });

        },
        drawCallback: function (settings) {
            $("#requerimientosAlmacen_filter input").prop("disabled", false);
            $("#btnBuscar").html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop("disabled", false);
            $("#requerimientosAlmacen_filter input").trigger("focus");
        },
        ajax: {
            url: 'listarRequerimientosAlmacen',
            type: 'POST',
            // data: function (params) {
            //     var x = $('[name=select_mostrar_pendientes]').val();
            //     console.log(x);
            //     return Object.assign(params, objectifyForm($('#formFiltrosSalidasPendientes').serializeArray()))
            // }
        },
        columns: [
            { data: 'id_requerimiento' },
            {
                data: 'codigo', className: "text-center",
                'render': function (data, type, row) {
                    return (row['codigo'] !== null ? `<a href="/necesidades/requerimiento/elaboracion/index?id=${row['id_requerimiento']}"
                        target="_blank" title="Abrir Requerimiento">${row['codigo'] ?? ''}</a>` : '') +
                        (row['estado'] == 38
                            ? ' <i class="fas fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="Requerimiento por regularizar"></i> '
                            : (row['estado'] == 39 ?
                                ' <i class="fas fa-pause orange" data-toggle="tooltip" data-placement="bottom" title="Requerimiento en pausa"></i> ' : ''))
                        + (row['tiene_transformacion'] ? ' <i class="fas fa-random red"></i>' : '');
                }
            },
            {
                data: 'estado_doc', name: 'adm_estado_doc.estado_doc', className: "text-center",
                'render': function (data, type, row) {
                    return '<span class="label label-' + row['bootstrap_color'] + '">' + row['estado_doc'] + '</span>'
                }
            },
            { data: 'concepto' },
            { data: 'grupo_descripcion', name: 'sis_grupo.descripcion' },
            { data: 'almacen_descripcion', name: 'alm_almacen.descripcion', className: "text-center" },
            { data: 'fecha_entrega', name: 'alm_req.fecha_entrega', className: "text-center" },
            // { data: 'estado_doc', name: 'adm_estado_doc.estado_doc', className: "text-center" },
            { data: 'nombre_corto', name: 'sis_usua.nombre_corto', className: "text-center" },
            {
                data: 'codigo_despacho_interno', name: 'despachoInterno.codigo', className: "text-center",
                'render': function (data, type, row) {
                    return (row['codigo_despacho_interno'] ?? '') + (row['codigo_transformacion'] !== null ? `<br>
                    ${id_usuario == '3' || id_usuario == '17' || id_usuario == '93' ?
                            `<button type="button" class="anular_odi btn btn-danger btn-flat btn-xs" data-toggle="tooltip"
                        data-placement="bottom" title="Anular Despacho Interno" data-id="${row['id_despacho_interno']}"
                        data-codigo="${row['codigo_despacho_interno']}" data-idreq="${row['id_requerimiento']}">
                        <i class="fas fa-trash"></i></button>`: ''}<br>
                        <label class="lbl-codigo" title="Abrir Transformación"
                    onClick="abrir_transformacion(${row['id_transformacion']})">${row['codigo_transformacion']}</label>
                    ` : '')
                        + (row['estado_di'] ?? '');
                }
            },
            {
                data: 'codigo_despacho_externo', name: 'orden_despacho.codigo', className: "text-center",
                'render': function (data, type, row) {
                    return (row['codigo_despacho_externo'] !== null ? row['codigo_despacho_externo'] + `<br>
                    <button type="button" class="anular_ode btn btn-danger btn-flat btn-xs" data-toggle="tooltip"
                        data-placement="bottom" title="Anular Despacho Externo" data-id="${row['id_despacho_externo']}"
                        data-codigo="${row['codigo_despacho_externo']}" data-idreq="${row['id_requerimiento']}">
                        <i class="fas fa-trash"></i></button>` : '');
                }
            },
            {
                data: 'estado_despacho_descripcion', name: 'estado_despacho.estado_doc', className: "text-center",
                'render': function (data, type, row) {
                    return '<span class="label label-default">' + (row['estado_despacho_descripcion'] == 'Aprobado' ? 'Pendiente' : row['estado_despacho_descripcion']) + '</span>'
                }
            },
        ],
        columnDefs: [
            { 'aTargets': [0], 'sClass': 'invisible' },
            {
                'render': function (data, type, row) {

                    return (array_accesos.find(element => element === 157)?`<button type="button" class="detalle btn btn-default btn-flat btn-xs " data-toggle="tooltip"
                    data-placement="bottom" title="Ver Detalle" data-id="${row['id_requerimiento']}">
                    <i class="fas fa-chevron-down"></i></button>`:``)+`

                    ${row['count_transferencias'] > 0 ?
                            `<button type="button" class="transferencia btn btn-success btn-flat btn-xs " data-toggle="tooltip"
                    data-placement="bottom" title="Ver transferencias" data-id="${row['id_requerimiento']}">
                    <i class="fas fa-exchange-alt"></i></button>`: ''
                        }
                    

                        ${row['tiene_transformacion'] ?
                        (array_accesos.find(element => element === 334)?`<button type="button" class="interno btn btn-${row['codigo_despacho_interno'] !== null ? 'danger' : 'default'} btn-flat btn-xs " data-toggle="tooltip"
                        data-placement="bottom" title="Despacho Interno" data-id="${row['id_requerimiento']}"
                        data-estado="${row['estado_di']}" data-estado-despacho="${row['estado_despacho']}"
                        data-idod="${row['id_despacho_interno']}" >
                        <strong>ODI</strong></button>`:'')
                        : ''}
                    `+(array_accesos.find(element => element === 158)?`<button type="button" class="cambio btn btn-warning btn-flat btn-xs " data-toggle="tooltip"
                    data-placement="bottom" title="Cambio de almacén" data-id="${row['id_requerimiento']}"
                    data-almacen="${row['id_almacen']}" data-codigo="${row['codigo']}">
                    <i class="fas fa-sync-alt"></i></button>`:``)+ ``
                    +(([17,27,1,3,77].includes(auth_user.id_usuario))? ('<button type="button" class="btn btn-default btn-xs handleClickAjustarTransformacion" style="color:red;" name="btnAjustarTransformacion" title="Ajustar transformación" data-id-requerimiento="' + row.id_requerimiento + '" data-codigo-requerimiento="' + row.codigo + '"><i class="fas fa-random"></i></button>'):'');

                }, targets: 11
            }
        ],
        'order': [[0, "desc"]],
    });
    vista_extendida();
}


$('#requerimientosAlmacen tbody').on("click", "button.transferencia", function () {
    var id = $(this).data('id');
    if (id !== null) {

        $.ajax({
            type: 'GET',
            url: 'listarDetalleTransferencias/' + id,
            dataType: 'JSON',
            success: function (response) {
                console.log(response);
                $('#modal-verTransferenciasPorRequerimiento').modal({
                    show: true
                });
                $('#transferenciasPorRequerimiento tbody').html(response);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }
});

$('#requerimientosAlmacen tbody').on("click", "button.cambio", function () {
    var id = $(this).data('id');
    var alm = $(this).data('almacen');
    var cod = $(this).data('codigo');

    $('[name=id_requerimiento]').val(id);
    $('[name=id_almacen]').val(alm);
    $('#codigo_req').text(cod);

    if (id !== null) {
        $('#modal-cambio_requerimiento').modal({
            show: true
        });
        listarDetalleRequerimiento(id);
    }
});

$('#requerimientosAlmacen tbody').on("click", "button.anular_odi", function () {
    var id_odi = $(this).data('id');
    var id_req = $(this).data('idreq');
    var cod = $(this).data('codigo');

    if (id_odi !== null) {
        Swal.fire({
            title: "¿Está seguro que desea anular ésta " + cod + "?",
            // text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00a65a", //"#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sí, Anular"
        }).then(result => {
            if (result.isConfirmed) {
                var data = 'id_od=' + id_odi + '&id_requerimiento=' + id_req;
                console.log(data);
                anularDespacho(data);
            }
        });
    }
});

$('#requerimientosAlmacen tbody').on("click", "button.anular_ode", function () {
    var id_ode = $(this).data('id');
    var id_req = $(this).data('idreq');
    var cod = $(this).data('codigo');

    if (id_ode !== null) {
        Swal.fire({
            title: "¿Está seguro que desea anular ésta " + cod + "?",
            // text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00a65a", //"#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sí, Anular"
        }).then(result => {
            if (result.isConfirmed) {
                var data = 'id_od=' + id_ode + '&id_requerimiento=' + id_req;
                console.log(data);
                anularDespacho(data);
            }
        });
    }
});

function anularDespacho(data) {
    $.ajax({
        type: 'POST',
        url: 'anularDespachoInterno',
        data: data,
        dataType: 'JSON',
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
                $("#requerimientosAlmacen").DataTable().ajax.reload(null, false);
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function abrir_transformacion(id_transformacion) {
    console.log('abrir_transformacio' + id_transformacion);
    localStorage.setItem("id_transfor", id_transformacion);
    var win = window.open("/cas/customizacion/hoja-transformacion/index", '_blank');
    win.focus();
}

var iTableCounter = 1;
var oInnerTable;

$('#requerimientosAlmacen tbody').on('click', 'td button.detalle', function () {
    var tr = $(this).closest('tr');
    var row = $tableRequerimientos.row(tr);
    var id = $(this).data('id');

    const $boton = $(this);
    // $boton.prop('disabled', true);

    if (row.child.isShown()) {
        //  This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
        // $boton.prop('disabled', false);
    }
    else {
        format(iTableCounter, id, row, $boton);
        tr.addClass('shown');

        oInnerTable = $('#requerimientosAlmacen_' + iTableCounter).dataTable({
            autoWidth: true,
            deferRender: true,
            info: false,
            lengthChange: false,
            ordering: false,
            paging: false,
            scrollX: false,
            scrollY: false,
            searching: false,
            columns: []
        });
        iTableCounter = iTableCounter + 1;

    }
});


$('#requerimientosAlmacen tbody').on("click", "button.handleClickAjustarTransformacion", (e) => {
    ajustarTransformacion(e.currentTarget);
});

$('#modal_ajustar_transformacion_requerimiento').on("click", "input.handleCheckTransformacion", (e) => {
    checkTransformacionCabecera(e.currentTarget);
});
$('#modal_ajustar_transformacion_requerimiento').on("click", "input.handleCheckItemAjustarTransformacion", (e) => {
    itemAjustarTransformacion(e.currentTarget);
});
$('#modal_ajustar_transformacion_requerimiento').on("click", "button.handleClickActualizarAjusteTransformacionRequerimiento", (e) => {
    actualizarAjusteTransformacionRequerimiento();
});

function obtenerRequerimiento(id){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`mostrar-requerimiento/${id}`,
            dataType: 'JSON',
            success(response) {
                resolve(response);
            },
            error: function(err) {
            reject(err)
            }
            });
        });
}

function obtenerDetalleRequerimientos(id){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`detalle-requerimiento/${id}`,
            dataType: 'JSON',
            success(response) {
                resolve(response);
            },
            error: function(err) {
            reject(err)
            }
            });
        });
}

function ajustarTransformacion(obj) {
    let tr = obj.closest('tr');
    var idRequerimiento = obj.dataset.idRequerimiento;
    var codigRequerimiento = obj.dataset.codigoRequerimiento;

    $('#modal_ajustar_transformacion_requerimiento').modal({
        show: true,
        backdrop: 'static'
    });

    obtenerRequerimiento(idRequerimiento).then((res) => {
        if(res.tiene_transformacion == true){
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] input[name='transformacionCabecera']").checked= true;
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] i[id='iconoTransformacion']").classList.add('fa-random');
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] span[id='textoTieneONoTransformacion']").innerHTML="<small>(Con transformación)</small>";

        }else{
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] input[name='transformacionCabecera']").checked= false;
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] i[id='iconoTransformacion']").classList.remove('fa-random');
            document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] span[id='textoTieneONoTransformacion']").innerHTML="<small>(Sin transformación)</small>";

        }
    });

    document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] input[name='idRequerimiento']").value= idRequerimiento;
    document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] span[id='codigoRequerimiento']").textContent= codigRequerimiento;
    document.querySelector("table[id='tablaListaItemsParaAjusteTransformacion'] tbody").innerHTML='';
    obtenerDetalleRequerimientos(idRequerimiento).then((res) => {
        res.map((element, index) => {
            // console.log(element);
                document.querySelector("table[id='tablaListaItemsParaAjusteTransformacion'] tbody").insertAdjacentHTML('beforeend', `
                <tr style="text-align:center;">
                <td><span id="itemTieneTransformacion">${element.tiene_transformacion==true?'<i class="fas fa-random" style="color:red;"></i>':''}</span> ${element.producto_part_number??''}</td>
                <td>${element.producto_codigo??''}</td>
                <td>${element.producto_codigo_softlink??''}</td>
                <td style="text-align: left;">${element.producto_descripcion??element.descripcion}</td>
                <td>${element.estado_doc??''}</td>
                <td><input type="checkbox" name="checkItem[]" value="${element.id_detalle_requerimiento}" class="handleCheckItemAjustarTransformacion" data-idDetalleRequerimiento="${element.id_detalle_requerimiento}" ${element.tiene_transformacion==true?'checked':''}></td>
            `);
        })

    }).catch((err) => {
        console.log(err)
        Swal.fire(
            'Error en el servidor al intentar obtener los items del requerimiento',
            err,
            'error'
        );
    })
}

function checkTransformacionCabecera(obj){
    if(obj.checked==true){
        document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] i[id='iconoTransformacion']").classList.add('fa-random');
        document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] span[id='textoTieneONoTransformacion']").innerHTML="<small>(Con transformación)</small>";
    }else{
        document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] i[id='iconoTransformacion']").classList.remove('fa-random');
        document.querySelector("div[id='modal_ajustar_transformacion_requerimiento'] span[id='textoTieneONoTransformacion']").innerHTML="<small>(Sin transformación)</small>";
    }
}

function itemAjustarTransformacion(obj){
    
    if(obj.checked==true){
        
        obj.closest("tr").children[0].querySelector("span[id='itemTieneTransformacion']").innerHTML='<i class="fas fa-random" style="color:red;"></i>'
    }else{
        obj.closest("tr").children[0].querySelector("span[id='itemTieneTransformacion']").innerHTML='';
    }
}

function actualizarAjusteTransformacionRequerimiento(){
    const data =  $('#form-ajustar-transformacion-requerimiento').serializeArray();
    if(data.length >0){
        $.ajax({
            type: 'POST',
            url: 'guardar-ajuste-transformacion-requerimiento',
            data: data,
            beforeSend: (data) => { // Are not working with dataType:'jsonp'

                $('#modal_ajustar_transformacion_requerimiento .modal-content').LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },
            success: (response) => {
                if (response.estado == 'success') {
                    $('#modal_ajustar_transformacion_requerimiento .modal-content').LoadingOverlay("hide", true);

                    Lobibox.notify('success', {
                        title: false,
                        size: 'mini',
                        rounded: true,
                        sound: false,
                        delayIndicator: false,
                        msg: `${response.mensaje}`
                    });

                    $('#modal_ajustar_transformacion_requerimiento').modal('hide');

                    $("#requerimientosAlmacen").DataTable().ajax.reload(null, false);


                } else {
                    $('#modal_ajustar_transformacion_requerimiento .modal-content').LoadingOverlay("hide", true);
                    // console.log(response);
                    Swal.fire(
                        '',
                        response.mensaje,
                        response.tipo_estado
                    );

                    $('#modal_ajustar_transformacion_requerimiento').modal('hide');

                }
            },
            fail: (jqXHR, textStatus, errorThrown) => {
                $('#modal_ajustar_transformacion_requerimiento .modal-content').LoadingOverlay("hide", true);
                Swal.fire(
                    '',
                    errorThrown,
                    'error'
                );
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }else{
        Swal.fire(
            'No hay nada que actualizar',
            err,
            'error'
        );
    }
}

$('#requerimientosAlmacen tbody').on("click", "button.interno", function (e) {
    $(e.preventDefault());
    var id = $(this).data("id");
    var od = $(this).data("idod");
    var estado = $(this).data("estado");
    var estado_despacho = $(this).data("estadoDespacho");
    var msj = '';

    if (estado == null || estado == 1) {
        openFechaProgramada(id, od);
    } else if (estado == 10) {
        msj = 'Ya se finalizó el proceso de transformación. No es posible modificar.';
    } else {
        msj = 'Ya se inició el proceso de transformación. No es posible modificar.';
    }
    
    if (msj !== '') {
        Lobibox.notify('warning', {
            title: false,
            size: "mini",
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: msj
        });
    }
});
