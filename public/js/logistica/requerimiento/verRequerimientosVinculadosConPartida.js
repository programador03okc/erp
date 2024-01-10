
var vardataTables = funcDatatables();
var $tablatablaRequerimientosVinculadosConPartida;
var iTableCounter = 1;
var oInnerTable;
var importeItemPorPartida=0;

$('#tablaRequerimientosVinculadosConPartida tbody').on("click", "button.handleClickVerDetalleRequerimiento", (e) => {
    verDetalleRequerimiento(e.currentTarget);
});

function verDetalleRequerimiento(obj) {
    let tr = obj.closest('tr');
    var row = $tablatablaRequerimientosVinculadosConPartida.row(tr);
    var idRequerimientoLogistico = obj.dataset.idRequerimientoLogistico;
    var idRequerimientoPago = obj.dataset.idRequerimientoPago;
    var idPartida = obj.dataset.idPartida;
    if (row.child.isShown()) {
        //  This row is already open - close it
        row.child.hide();
        tr.classList.remove('shown');
    }
    else {
        // Open this row
        //    row.child( format(iTableCounter, id) ).show();
        this.buildFormatListaRequerimientosPendientes(obj, iTableCounter, idRequerimientoLogistico,idRequerimientoPago, idPartida, row);
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

function obtenerDetalleRequerimientosLogisticos(idRequerimiento, idPartida){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`listar-items-requerimiento-logistico-con-partida-presupuesto-interno/${idRequerimiento}/${idPartida}`,
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
function obtenerDetalleRequerimientosPago(idRequerimiento, idPartida){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url:`listar-items-requerimiento-pago-con-partida-presupuesto-interno/${idRequerimiento}/${idPartida}`,
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

function buildFormatListaRequerimientosPendientes(obj, table_id, idRequerimientoLogistico, idRequerimientoPago, idPartida, row) {
    obj.setAttribute('disabled', true);

    if(parseInt(idRequerimientoLogistico) > 0){
        obtenerDetalleRequerimientosLogisticos(idRequerimientoLogistico, idPartida).then((res) => {
            obj.removeAttribute('disabled');
            construirDetalleRequerimientoListaRequerimientosPendientes(table_id, row, res);
        }).catch((err) => {
            console.log(err)
        })
    }

    if(parseInt(idRequerimientoPago) > 0){
        obtenerDetalleRequerimientosPago(idRequerimientoPago, idPartida).then((res) => {
            obj.removeAttribute('disabled');
            construirDetalleRequerimientoListaRequerimientosPendientes(table_id, row, res);
        }).catch((err) => {
            console.log(err)
        })
    }
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
                    <td style="border: none; text-align:center;">${(element.precio_unitario > 0 ? ((element.moneda_simbolo ? element.moneda_simbolo : ((element.moneda_simbolo ? element.moneda_simbolo : '') + '0.00')) + $.number((element.precio_unitario), 2)) : (element.moneda_simbolo ? element.moneda_simbolo : '') + '0.00')}</td>
                    <td style="border: none; text-align:center;">${element.moneda_simbolo} ${(parseFloat(element.subtotal) > 0 ? $.number(element.subtotal, 2, ".", ",") : '')}</td>
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

function verRequerimientosVinculadosConPartida(idPartida,descripcionPartida,codigoPartida,tipoPresupuesto,totalPresupuestoAnual,totalPresupuestoMes){
    importeItemPorPartida=0;
    if(idPartida>0 && tipoPresupuesto =='INTERNO'){
        $('#modal-requerimientos-vinculados-con-partida').modal({
            show: true,
            backdrop: 'true'
        });

        document.querySelector("div[id='modal-requerimientos-vinculados-con-partida'] span[id='partida']").textContent= codigoPartida;
        document.querySelector("div[id='modal-requerimientos-vinculados-con-partida'] span[id='partida']").setAttribute('title',descripcionPartida);


        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[name='ppto_partida_anual']").innerHTML= `<span style="color:blue;">S/${$.number((totalPresupuestoAnual!=null?(parseFloat(totalPresupuestoAnual.replace(/,/g, ''))):0), 2, ".", ",")}</span>`; 
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] input[name='ppto_partida_anual']").value=totalPresupuestoAnual;
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[name='ppto_partida_mes']").innerHTML=  `<span style="color:blue;">S/${$.number((totalPresupuestoMes!=null?(parseFloat(totalPresupuestoMes.replace(/,/g, ''))):0), 2, ".", ",")}</span>`;
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] input[name='ppto_partida_mes']").value= totalPresupuestoMes;

        let that = this;
        $tablatablaRequerimientosVinculadosConPartida = $('#tablaRequerimientosVinculadosConPartida').DataTable({
            'dom': 'Blfrtip',
            'buttons': [],
            'language': vardataTables[0],
            'order': [[13, 'desc']],
            'serverSide': true,
            'destroy': true,
            'stateSave': true,
            'bLengthChange': false,
            "pageLength": 20,
            'ajax': {
                'url': 'listar-requerimientos-vinculados-con-partida',
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
 
                { 'data': 'prioridad', 'name': 'prioridad', 'className': 'text-center' },

                { 'data': 'empresa', 'name': 'empresa', 'className': 'text-center',  'render': function (data, type, row) {
                    return `${row.empresa} - ${row.sede}`;
                }},
                { 'data': 'codigo', 'name': 'codigo', 'className': 'text-center',  'render': function (data, type, row) {
                    return `<a href="/necesidades/requerimiento/elaboracion/index?id=${row.id_requerimiento_logistico}" target="_blank" title="Abrir Requerimiento">${row.codigo}</a>`;
                }},
                { 'data': 'fecha_registro', 'name': 'fecha_registro', 'className': 'text-center' },
                { 'data': 'concepto', 'name': 'alm_req.concepto', 'className': 'text-left' },
                { 'data': 'tipo_requerimiento', 'name': 'tipo_requerimiento', 'className': 'text-center' },
                { 'data': 'grupo', 'name': 'grupo', 'className': 'text-center' },
                { 'data': 'division', 'name': 'division', 'className': 'text-center' },
                { 'data': 'solicitado_por', 'name': 'solicitado_por', 'className': 'text-left' },
                { 'data': 'creado_por', 'name': 'creado_por', 'className': 'text-left'},
                { 'data': 'comentario', 'name': 'comentario', 'className': 'text-left'},
                { 'data': 'monto_total', 'name': 'monto_total', 'className': 'text-center', 'render': function (data, type, row) {
                    let montoAlTipoCambio='';
                    // console.log(row.monto_total );
                    if(row.id_moneda==2){
                        montoAlTipoCambio = parseFloat(row.monto_total) * parseFloat(row.tipo_cambio);
                    }else{
                        
                    }
                    return (row.id_moneda ==1?('S/'):(row.id_moneda ==2 ? '$':'')) +$.number(((parseFloat(row.monto_total))), 2, ".", ",") + (montoAlTipoCambio!=''?`<br><small>S/(${$.number(montoAlTipoCambio,2,".",",")})</small> <em>(TC:${row.tipo_cambio})</em`:'');
                }},
                { 'data': 'estado', 'name': 'estado', 'className': 'text-center', 'render': function (data, type, row) {
                    return `<span class="labelEstado label label-${row.bootstrap_color}">${row.estado}</span>`;
                }},
                { 'data': 'id', 'name': 'id', 'className': 'text-center', "searchable": false,'render': function (data, type, row) {
                        let openDiv = '<div class="btn-group" role="group">';
                        let btnVerDetalleRequerimiento = `<button type="button" class="btn btn-default btn-xs handleClickVerDetalleRequerimiento" name="btnVerDetalleRequerimiento" title="Ver detalle requerimiento" 
                        data-id-requerimiento-logistico="${row.id_requerimiento_logistico!=null ? parseInt(row.id_requerimiento_logistico):0}"
                        data-id-requerimiento-pago="${row.id_requerimiento_pago!=null ?parseInt(row.id_requerimiento_pago):0}"
                        data-id-presupuesto_interno="${row.id_presupuesto_interno!=null ? parseInt(row.id_presupuesto_interno):0}"
                        data-id-partida="${parseInt(idPartida)}">
                        <i class="fas fa-chevron-down fa-sm"></i></button>`;
                        let closeDiv = '</div>';
                        let botones = '';
                        botones = openDiv + btnVerDetalleRequerimiento + closeDiv;
                        return botones;
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
        // console.log(data);
        if(data.id_moneda ==2){ // moneda es dolares
            importeItemPorPartida  += parseFloat(data.monto_total * data.tipo_cambio); 
        }else{
            importeItemPorPartida  += parseFloat(data.monto_total);
        }
        let colorFlag='blue';
        if(document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] input[name='ppto_partida_mes']").value <importeItemPorPartida ){
            colorFlag='red';
        }
        document.querySelector("table[id='tablaResumenRequerimientoVinculadosConPartida'] span[name='total_partida_de_requerimientos_incluido_igv']").innerHTML=  `<span style="color:${colorFlag};">S/${$.number((parseFloat(importeItemPorPartida)), 2, ".", ",")}</span>`; 

    }

}