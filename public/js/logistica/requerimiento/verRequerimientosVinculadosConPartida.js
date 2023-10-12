
var vardataTables = funcDatatables();
var $tablatablaRequerimientosVinculadosConPartida;
var iTableCounter = 1;
var oInnerTable;
var importeItemPorPartida=0;

$('#tablaRequerimientosVinculadosConPartida tbody').on("click", "button.handleClickVerDetalleRequerimiento", (e) => {
    verDetalleRequerimiento(e.currentTarget);
    console.log("x");
});

function verDetalleRequerimiento(obj) {
    let tr = obj.closest('tr');
    var row = $tablatablaRequerimientosVinculadosConPartida.row(tr);
    var idRequerimiento = obj.dataset.idRequerimiento;
    var idPartida = obj.dataset.idPartida;
    if (row.child.isShown()) {
        //  This row is already open - close it
        row.child.hide();
        tr.classList.remove('shown');
    }
    else {
        // Open this row
        //    row.child( format(iTableCounter, id) ).show();
        this.buildFormatListaRequerimientosPendientes(obj, iTableCounter, idRequerimiento, idPartida, row);
        tr.classList.add('shown');
        // try datatable stuff
        oInnerTable = $('#listaRequerimientosPendientes_' + iTableCounter).dataTable({
            //    data: sections,
            autoWidth: true,
            deferRender: true,
            info: false,
            lengthChange: false,
            ordering: false,
            paging: false,
            scrollX: false,
            scrollY: false,
            searching: false,
            columns: [
            ]
        });
        iTableCounter = iTableCounter + 1;
    }
}

function obtenerDetalleRequerimientos(idRequerimiento, idPartida){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`obtener-items-requerimiento-con-partida-presupuesto-interno/${idRequerimiento}/${idPartida}`,
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

function buildFormatListaRequerimientosPendientes(obj, table_id, idRequerimiento, idPartida, row) {
    obj.setAttribute('disabled', true);

    obtenerDetalleRequerimientos(idRequerimiento, idPartida).then((res) => {
        obj.removeAttribute('disabled');
        construirDetalleRequerimientoListaRequerimientosPendientes(table_id, row, res);
    }).catch((err) => {
        console.log(err)
    })
}

function construirDetalleRequerimientoListaRequerimientosPendientes(table_id, row, response) {

    var html = '';
    console.log(response);
    if (response.length > 0) {
        response.forEach(function (element) {

            html += `<tr>
                    <td style="border: none; text-align:center;">${element.codigo_partida_presupuesto_interno != null ? element.codigo_partida_presupuesto_interno : ''}</td>
                    <td style="border: none; text-align:center;">${element.descripcion_partida_presupuesto_interno != null ? element.descripcion_partida_presupuesto_interno : ''}</td>
                    <td style="border: none; text-align:center;" data-part-number="${element.part_number}" data-producto-part-number="${element.producto_part_number}">${(element.producto_part_number != null ? element.producto_part_number : (element.part_number != null ? element.part_number : ''))} ${element.tiene_transformacion == true ? '<br><span class="label label-default">Transformado</span>' : ''}</td>
                    <td style="border: none; text-align:left;">${element.producto_codigo != null ? element.producto_codigo : ''}</td>
                    <td style="border: none; text-align:left;">${element.producto_codigo_softlink != null ? element.producto_codigo_softlink : ''}</td>
                    <td style="border: none; text-align:left;">${element.producto_descripcion != null ? element.producto_descripcion : (element.descripcion ? element.descripcion : '')}</td>
                    <td style="border: none; text-align:center;">${element.unidad_medida_producto != null ? element.unidad_medida_producto : element.abreviatura}</td>
                    <td style="border: none; text-align:center;">${element.cantidad > 0 ? element.cantidad : ''}</td>
                    <td style="border: none; text-align:center;">${(element.precio_unitario > 0 ? ((element.moneda_simbolo ? element.moneda_simbolo : ((element.moneda_simbolo ? element.moneda_simbolo : '') + '0.00')) + $.number(element.precio_unitario, 2)) : (element.moneda_simbolo ? element.moneda_simbolo : '') + '0.00')}</td>
                    <td style="border: none; text-align:center;">${(parseFloat(element.subtotal) > 0 ? ((element.moneda_simbolo ? element.moneda_simbolo : '') + $.number(element.subtotal, 2)) : ((element.moneda_simbolo ? element.moneda_simbolo : '') + $.number((element.cantidad * element.precio_unitario), 2)))}</td>
                    <td style="border: none; text-align:center;">${element.motivo != null ? element.motivo : ''}</td>
                    <td style="border: none; text-align:center;">${element.estado_doc != null && element.tiene_transformacion == false ? element.estado_doc : ''}</td>
                    </tr>`;
            // }
        });
        var tabla = `<table class="table table-condensed table-bordered"
            id="detalle_${table_id}">
            <thead style="color: black;background-color: #c7cacc;">
                <tr>
                    <th style="border: none; text-align:center;">Cod. partida</th>
                    <th style="border: none; text-align:center;">Des. partida</th>
                    <th style="border: none; text-align:center;">Part number</th>
                    <th style="border: none; text-align:center;">Cód. producto</th>
                    <th style="border: none; text-align:center;">Cód. softlink</th>
                    <th style="border: none; text-align:center;">Descripcion</th>
                    <th style="border: none; text-align:center;">Unidad medida</th>
                    <th style="border: none; text-align:center;">Cantidad</th>
                    <th style="border: none; text-align:center;">Precio unitario</th>
                    <th style="border: none; text-align:center;">Subtotal</th>
                    <th style="border: none; text-align:center;">Motivo</th>
                    <th style="border: none; text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody style="background: #e7e8ea;">${html}</tbody>
            </table>`;
    } else {
        var tabla = `<table class="table table-sm" style="border: none;"
            id="detalle_${table_id}">
            <tbody>
                <tr><td>No hay registros para mostrar</td></tr>
            </tbody>
            </table>`;
    }
    row.child(tabla).show();
}


function verRequerimientosVinculadosConPartida(idPartida,descripcionPartida,codigoPartida,tipoPresupuesto){
  
    if(idPartida>0 && tipoPresupuesto =='INTERNO'){
        $('#modal-requerimientos-vinculados-con-partida').modal({
            show: true,
            backdrop: 'true'
        });

        document.querySelector("div[id='modal-requerimientos-vinculados-con-partida'] span[id='partida']").textContent= codigoPartida;
        document.querySelector("div[id='modal-requerimientos-vinculados-con-partida'] span[id='partida']").setAttribute('title',descripcionPartida);

        let that = this;
    
        $tablatablaRequerimientosVinculadosConPartida = $('#tablaRequerimientosVinculadosConPartida').DataTable({
            'dom': 'Blfrtip',
            'buttons': [],
            'language': vardataTables[0],
            'order': [[4, 'desc'],[1, 'desc']],
            'serverSide': true,
            'destroy': true,
            'stateSave': true,
            'bLengthChange': false,
            "pageLength": 20,
            'ajax': {
                'url': 'obtener-requerimientos-vinculados-con-partida',
                'type': 'POST',
                'data': {'id_partida':idPartida,'tipo_presupuesto':tipoPresupuesto },
                beforeSend: data => {

                    $("#tablaRequerimientosVinculadosConPartida").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                }

            },
            'columns': [
 
                { 'data': 'descripcion_prioridad', 'name': 'adm_prioridad.descripcion', 'render': function (data, type, row) {

                    return `${row['termometro']}`;
                }},
                { 'data': 'empresa_sede', 'name': 'sis_sede.descripcion', 'className': 'text-center' },
                { 'data': 'codigo', 'name': 'alm_req.codigo', 'className': 'text-center',  'render': function (data, type, row) {
                    return `${row.estado == 38 ? '<i class="fas fa-exclamation-triangle ' + (row.count_pendientes > 0 ? 'red' : 'orange') + ' handleClickAbrirModalPorRegularizar" style="cursor:pointer;" title="Por regularizar' + (row.count_pendientes > 0 ? '(Tiene ' + row.count_pendientes + ' item(s) pendientes por mapear)' : '') + '" data-id-requerimiento="' + row.id_requerimiento + '" ></i> &nbsp;' : ''}<a href="/necesidades/requerimiento/elaboracion/index?id=${row.id_requerimiento}" target="_blank" title="Abrir Requerimiento">${row.codigo}</a> ${row.tiene_transformacion == true ? '<i class="fas fa-random text-danger" title="Con transformación"></i>' : ''} `;
                }},
                { 'data': 'fecha_registro', 'name': 'alm_req.fecha_registro', 'className': 'text-center' },
                { 'data': 'concepto', 'name': 'alm_req.concepto', 'className': 'text-left' },
                { 'data': 'tipo_req_desc', 'name': 'alm_tp_req.descripcion', 'className': 'text-center' },
                { 'data': 'division', 'name': 'division.descripcion', 'className': 'text-center', "searchable": false, 'render': function (data, type, row) {
                    return row.division != null ? JSON.parse(row.division.replace(/&quot;/g, '"')).join(",") : '';
                }},
                { 'data': 'nombre_solicitado_por', 'name': 'nombre_solicitado_por', 'className': 'text-center'},
                { 'data': 'nombre_usuario', 'name': 'nombre_usuario', 'className': 'text-center' },
                { 'data': 'observacion', 'name': 'alm_req.observacion', 'className': 'text-center' },
                { 'data': 'importe_item_por_partida', 'name': 'importe_item_por_partida', 'className': 'text-center', 'render': function (data, type, row) {
                    return 'S/'+$.number((parseFloat(row['importe_item_por_partida'])), 2, ".", ",");
                }},
                { 'data': 'importe_item_por_partida', 'name': 'importe_item_por_partida', 'className': 'text-center', 'render': function (data, type, row) {
                    return 'S/'+$.number((parseFloat(row['importe_item_por_partida']) * 1.18), 2, ".", ",");
                }},
                { 'data': 'id_requerimiento', 'name': 'alm_req.id_requerimiento', 'className': 'text-center', "searchable": false,'render': function (data, type, row) {
                    // if(permisoCrearOrdenPorRequerimiento == '1') {
                    let observacionLogisticaSinSustento = '';

                    let tieneTransformacion = row.tiene_transformacion;
                    let cantidadItemBase = row.cantidad_items_base;
                    let btnRetornarAListaPendientes = '<button type="button" class="btn btn-default btn-xs handleClickRetornarAListaPendientes" style="color:red;" name="btnRetornarAListaPendientes" title="Retornar a lista de pendiente" data-id-requerimiento="' + row.id_requerimiento + '" data-codigo-requerimiento="' + row.codigo + '"><i class="fas fa-arrow-left fa-xs"></i></button>';
                    if (tieneTransformacion == true && cantidadItemBase == 0) {
                        return ('<div class="btn-group" role="group">' +
                            '</div>' +
                            '<div class="btn-group" role="group">' +  '<button type="button" class="btn btn-info btn-xs handleClickOpenModalCuadroCostos" name="btnVercuadroCostos" title="Ver Cuadro Costos" data-id-requerimiento="' + row.id_requerimiento + '" >' +
                                '<i class="fas fa-eye fa-sm"></i>' +
                                '</button>' ) +

                            (([17, 27, 1, 3, 77,78].includes(auth_user.id_usuario)) ? (btnRetornarAListaPendientes) : '') +

                            '</div>';
                    } else {
                        let openDiv = '<div class="btn-group" role="group">';
                        let btnVerDetalleRequerimiento = '<button type="button" class="btn btn-default btn-xs handleClickVerDetalleRequerimiento" name="btnVerDetalleRequerimiento" title="Ver detalle requerimiento" data-id-requerimiento="' + row.id_requerimiento + '" data-id-presupuesto_interno="' + row.id_presupuesto_interno + '"  data-id-partida="' + idPartida + '"><i class="fas fa-chevron-down fa-sm"></i></button>';
                        let closeDiv = '</div>';
                        let botones = '';
                        botones = openDiv + btnVerDetalleRequerimiento + closeDiv;

                        return botones;
                    }

                }}
            ],
            'columnDefs': [
            ],
            'rowCallback': function (row, data, dataIndex) {
                // Get row ID
                contruirResumenRequerimientosVinculadosConPartida(data);
            },
            'initComplete': function () {

                //Boton de busqueda
                const $filter = $('#tablaRequerimientosVinculadosConPartida_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscarRequerimientosPendientes" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
                $input.off();
                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscarRequerimientosPendientes').trigger('click');
                    }
                });
                $('#btnBuscarRequerimientosPendientes').on('click', (e) => {
                    $tablatablaRequerimientosVinculadosConPartida.search($input.val()).draw();
                })
                //Fin boton de busqueda
            },
            "drawCallback": function (settings) {
                //Botón de búsqueda
                $('#tablaRequerimientosVinculadosConPartida_filter input').prop('disabled', false);
                $('#btnBuscarRequerimientosPendientes').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#tablaRequerimientosVinculadosConPartida_filter input').trigger('focus');
                //fin botón búsqueda
                if ($tablatablaRequerimientosVinculadosConPartida.rows().data().length == 0) {
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
                $('#tablaRequerimientosVinculadosConPartida_filter input').prop('disabled', false);
                $('#btnBuscarRequerimientosPendientes').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#tablaRequerimientosVinculadosConPartida_filter input').trigger('focus');
                //fin botón búsqueda
                $("#tablaRequerimientosVinculadosConPartida").LoadingOverlay("hide", true);

            }

        });
    }

    function contruirResumenRequerimientosVinculadosConPartida(data){
        importeItemPorPartida +=parseFloat(data.importe_item_por_partida);
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[id='ppto_partida_anual']").textContent= 'S/'+$.number((parseFloat(data.presupuesto_interno_total_partida.replace(/,/g, ''))), 2, ".", ","); 
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[id='ppto_partida_mes']").textContent=  'S/'+$.number((parseFloat(data.presupuesto_interno_mes_partida.replace(/,/g, ''))), 2, ".", ",");
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[id='total_partida_de_requerimientos_aprobados']").textContent = 'S/'+$.number((parseFloat(importeItemPorPartida)), 2, ".", ",");
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[id='total_partida_de_requerimientos_aprobados_incluido_igv']").textContent= 'S/'+$.number((parseFloat(importeItemPorPartida) *1.18), 2, ".", ","); 

    }

}