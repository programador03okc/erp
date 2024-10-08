let detalle = [];
function limpiarTabla(idElement) {
    let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
    if (nodeTbody != null) {
        while (nodeTbody.children.length > 0) {
            nodeTbody.removeChild(nodeTbody.lastChild);
        }

    }
}

$('#detalleItemsRequerimiento tbody').on("blur", "input.handleBlurUpdateCantidadItem", (e) => {
    updateCantidadItem(e.currentTarget);
});

function updateCantidadItem(obj){

    if(typeof ( detalle.find(element => element.id_detalle_requerimiento == obj.dataset.id))=='object' && detalle.find(element => element.id_detalle_requerimiento == obj.dataset.id).hasOwnProperty('cantidad') ){

        detalle.find(element => element.id_detalle_requerimiento == obj.dataset.id).cantidad=parseFloat(obj.value);
        prod= detalle.find(element => element.id_detalle_requerimiento == obj.dataset.id);
        // console.log(prod);
    }else{
        Swal.fire(
            '',
            'Hubo un problema al actualizar la cantidad del item, vuelva a cargar la página F5 y vuelva a intentar',
            'error'
        );

    }

}

function listarItemsRequerimientoMapeo(id_requerimiento) {
    limpiarTabla('detalleItemsRequerimiento');
    detalle = [];

    $.ajax({
        type: 'GET',
        url: 'itemsRequerimiento/' + id_requerimiento,
        dataType: 'JSON',
        beforeSend: data => {
            $("#modal-mapeoItemsRequerimiento .modal-body").LoadingOverlay("show", {
                imageAutoResize: true,
                progress: true,
                imageColor: "#3c8dbc"
            });
        },
        success: function (response) {
            response.forEach(element => {
                if (element.id_tipo_item == 1) {
                    // console.log(element);
                    detalle.push({
                        'id_detalle_requerimiento': element.id_detalle_requerimiento,
                        'id_producto': element.id_producto,
                        'cod_softlink': element.cod_softlink,
                        'codigo': element.codigo,
                        'part_number_requerimiento': (element.part_number !== null ? element.part_number : ''),
                        'part_number': (element.id_producto !== null ? element.part_number_prod : (element.part_number !== null ? element.part_number : '')),
                        'descripcion': (element.id_producto !== null ? element.descripcion_prod : (element.descripcion !== null ? element.descripcion : '')),
                        'descripcion_requerimiento': (element.descripcion !== null ? element.descripcion : ''),
                        'cantidad': element.cantidad,
                        'descripcion_moneda': element.descripcion_moneda??'',
                        'tiene_transformacion': element.tiene_transformacion,
                        'abreviatura': (element.abreviatura !== null ? element.abreviatura : ''),
                        'id_categoria': null,
                        'id_clasif': null,
                        'id_subcategoria': null,
                        'id_moneda': element.id_moneda,
                        'id_unidad_medida': element.id_unidad_medida,
                        'ordenes_compra': element.ordenes_compra,
                        'reserva': element.reserva,
                        'estado': element.estado
                    });
                }

            });
            mostrar_detalle();
            $("#modal-mapeoItemsRequerimiento .modal-body").LoadingOverlay("hide", true);

        },
        "drawCallback": function (settings) {
            $("#modal-mapeoItemsRequerimiento .modal-body").LoadingOverlay("hide", true);
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });

}

function mostrar_detalle() {
    var html = '';
    var i = 1;
    // console.log(detalle);
    detalle.sort();
    // console.log(detalle);
    let idDetalleRequerimientoConDescomposicionList=[];
    detalle.forEach(element=> {
        if(!idDetalleRequerimientoConDescomposicionList.includes(element.id_detalle_requerimiento_origen)){
            idDetalleRequerimientoConDescomposicionList.push(element.id_detalle_requerimiento_origen);
        }
    });


    detalle.forEach(element => {
        let cantidadRervas=0;
        let cantidadOrdenes=0;
        var pn = element.part_number ?? '';
        var dsc = encodeURIComponent(element.descripcion);
        var link_pn = '';
        var link_des = '';

        var regExp = /[a-zA-Z]/g; //expresión regular

        if ((regExp.test(element.id_detalle_requerimiento) != true) || element.estado !=7) {
            console.log(element);
            if(element.reserva!= null){
                cantidadRervas = (element.reserva).filter(function(item){
                    if (item.estado != 7) {
                        return true;
                    } else {
                        return false;
                    }
                }).length;
            }else{
                cantidadRervas=0;
            }
            if(element.ordenes_compra != null){
                cantidadOrdenes = (element.ordenes_compra).filter(function(item){
                    if (item.estado != 7) {
                        return true;
                    } else {
                        return false;
                    }
                }).length;
            }else{
                cantidadOrdenes=0;
            }

        if (pn !== null) {
            link_pn = `
            <a href="javascript: void(0);"
                onclick="openAsignarProducto('`+ pn + `', '` + dsc + `', ` + element.id_detalle_requerimiento + `, 1);">
            `+ pn + `
            </a>`;
        }
        if (dsc !== null) {
            link_des = `
            <a href="javascript: void(0);"
                onclick="openAsignarProducto('`+ pn + `', '` + dsc + `', ` + element.id_detalle_requerimiento + `, 2);">
            `+ decodeURIComponent(dsc) + `
            </a>`;
        }
        // console.log(element);
        html += `<tr ${element.estado == 7 ? 'class="bg-danger"' : ''}>
            <td>${i}</td>
            <td>${(element.codigo !== null && element.codigo !== '') ? element.codigo :
            ((element.id_categoria !== null && element.id_producto == null) ? '(Por crear)' : '')}</td>
            <td>${element.cod_softlink??''}</td>
            <td>`+ link_pn + (element.tiene_transformacion ? ' <span class="badge badge-secondary">Transformado</span> ' : '') + `</td>
            <td>`+ link_des + `</td>
            <td name="tdCantidad">
            ${((element.id_detalle_requerimiento_origen !=undefined && element.id_detalle_requerimiento_origen >0) || (idDetalleRequerimientoConDescomposicionList.includes(element.id_detalle_requerimiento))) ? `<input type="number" class="form-control handleBlurUpdateCantidadItem" name="cantidad" step="0.1" max="${element.cantidad_original??0}" value="${element.cantidad}" data-id="${element.id_detalle_requerimiento}" data-id-detalle-requerimiento-origen="${element.id_detalle_requerimiento_origen}" >` : (element.cantidad !== null ? element.cantidad : '')}

            </td>
            <td>${element.abreviatura !== null ? element.abreviatura : ''}</td>
            <td>${element.descripcion_moneda !== null ? element.descripcion_moneda : ''}</td>
            <td style="display:flex;">
                <button type="button" style="padding-left:8px;padding-right:7px;"
                    class="asignar btn btn-xs btn-info boton" data-toggle="tooltip"
                    data-placement="bottom" data-partnumber="${element.part_number_requerimiento??element.part_number}"
                    data-desc="${encodeURIComponent(element.descripcion_requerimiento??element.descripcion)}" data-id="${element.id_detalle_requerimiento}"
                    title="${(cantidadRervas > 0 || cantidadOrdenes >0)?'No se puede asignar si tiene atención':'Asignar producto'}"  ${(cantidadRervas > 0 || cantidadOrdenes >0)?'disabled':''} >
                    <i class="fas fa-angle-double-right"></i>
                </button>`;

                        html += `
                            <button type="button" title="Duplicar para descomponer producto"
                            data-id="${element.id_detalle_requerimiento}"
                            data-id-producto="${element.id_producto}"
                            data-codigo="${element.codigo !=null?element.codigo:''}"
                            data-partnumber="${element.part_number_requerimiento}"
                            data-desc="${encodeURIComponent(element.descripcion_requerimiento)}"
                            data-cantidad="${element.cantidad}"
                            data-id-unidad-medida="${element.id_unidad_medida}"
                            data-unidad-medida="${element.abreviatura}"
                            data-id-moneda="${element.id_moneda}"
                            data-moneda="${element.descripcion_moneda}"
                            data-tiene-transformacion="${element.tiene_transformacion}"
                            class="duplicarParaDescomponer btn-xs btn btn-warning" title="${(cantidadRervas > 0 || cantidadOrdenes >0)?'No se puede descomponer si tiene atención':'Descomponer'}" ${(cantidadRervas > 0 || cantidadOrdenes >0)?'disabled':''}><i class="fas fa-clone"></i></button>
                        `;


                }
        if (element.estado == 7) {
            html += `
                <button type="button" style="padding-left:8px;padding-right:7px;"
                    class="anular btn btn-xs btn-danger boton oculto" data-toggle="tooltip"
                    data-placement="bottom" data-partnumber="${element.part_number_requerimiento}"
                    data-desc="${encodeURIComponent(element.descripcion_requerimiento)}" data-id="${element.id_detalle_requerimiento}"
                    title="Anular" >
                    <i class="fas fa-times"></i>
                </button>
                <button type="button" title="Restablecer" data-id="${element.id_detalle_requerimiento}" class="restablecer btn-xs btn btn-primary"><i class="fas fa-undo"></i></button>
                `;

        } else {
            html += `
                <button type="button" style="padding-left:8px;padding-right:7px;"
                    class="anular btn btn-xs btn-danger boton" data-toggle="tooltip"
                    data-placement="bottom" data-partnumber="${element.part_number_requerimiento}"
                    data-desc="${encodeURIComponent(element.descripcion_requerimiento)}" data-id="${element.id_detalle_requerimiento}"
                    title="Anular" >
                    <i class="fas fa-times"></i>
                </button>
                `;

        }
        html += `</td>
        </tr>`;
        i++;
    });
    $('#detalleItemsRequerimiento tbody').html(html);

}

$('#detalleItemsRequerimiento tbody').on("click", "button.asignar", function () {
    var partnumber = $(this).data('partnumber');
    var desc = $(this).data('desc');
    var id = $(this).data('id');
    openAsignarProducto(partnumber, desc, id, 0);
});

$('#detalleItemsRequerimiento tbody').on("click", "button.duplicarParaDescomponer", function (e) {
    var id = $(this).data('id');
    // var cod = $(this).data('codigo');
    // var partnumber = $(this).data('partnumber');
    // var desc = $(this).data('desc');
    // var cant = $(this).data('cantidad');
    // var unid = $(this).data('unidad');
    // var mone = $(this).data('moneda');
    // var trans = $(this).data('tieneTransformacion');
    // duplicarParaDescomponerProducto(id,cod,partnumber, decodeURIComponent(desc),cant,unid,mone,trans, e.currentTarget);
    duplicarParaDescomponerProducto(id, e.currentTarget);
});
$('#detalleItemsRequerimiento tbody').on("click", "button.anular", function (e) {
    var partnumber = $(this).data('partnumber');
    var desc = $(this).data('desc');
    var id = $(this).data('id');
    anularProducto(partnumber, desc, id, e.currentTarget);
});
$('#detalleItemsRequerimiento tbody').on("click", "button.restablecer", function (e) {
    var id = $(this).data('id');
    restablecerItemAnulado(id, e.currentTarget);
});

function anularProducto(partnumber, desc, id, obj) {

    detalle.forEach((element, index) => {
        if (element.id_detalle_requerimiento == id) {
            detalle[index].estado = 7;
            var regExp = /[a-zA-Z]/g; //expresión regular
            if ((regExp.test(element.id_detalle_requerimiento) == true)) {
                obj.closest('tr').remove();
                detalle.splice(index,1);
            }else{
                obj.closest("tr").classList.add('bg-danger');
                obj.closest("td").querySelector("button[class~='anular']").classList.add("oculto")

                let tdBotoneraAccionMapeo = obj.closest("td");
                if (tdBotoneraAccionMapeo.querySelector("button[class~='restablecer']") == null) {
                    let buttonRestablecerItem = document.createElement("button");
                    buttonRestablecerItem.type = "button";
                    buttonRestablecerItem.dataset.id = id;
                    buttonRestablecerItem.title = "Restablecer";
                    buttonRestablecerItem.className = "restablecer btn-xs btn btn-primary";
                    buttonRestablecerItem.innerHTML = "<i class='fas fa-undo'></i>";

                    tdBotoneraAccionMapeo.appendChild(buttonRestablecerItem);
                } else {
                    obj.closest("td").querySelector("button[class~='restablecer']").classList.remove("oculto")

                }
            }
            Lobibox.notify('success', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: `Item anulado. Haga click en guardar para grabar los cambios.`
            });
        }
    });


}

function restablecerItemAnulado(id, obj) {

    detalle.forEach((element, index) => {
        if (element.id_detalle_requerimiento == id) {
            detalle[index].estado = 1;
            Lobibox.notify('info', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: `Item restablecido`
            });
        }
    });

    obj.closest("td").querySelector("button[class~='anular']").classList.remove("oculto")
    obj.closest("td").querySelector("button[class~='restablecer']").classList.add("oculto")
    obj.closest("tr").classList.remove('bg-danger');
}

function openAsignarProducto(partnumber, desc, id, type) {
    // console.log(partnumber, desc, id, type);
    $('#part_number').text(partnumber);
    $('#descripcion_producto').text(decodeURIComponent(desc));
    $('[name=id_detalle_requerimiento]').val(id);
    $('[name=part_number]').val(partnumber);
    $('[name=descripcion]').val(decodeURIComponent(desc));
    $('[name=id_tipo_producto]').val(8);
    $('[name=id_categoria]').selectpicker('val', ''); // $('[name=id_categoria]').val('');
    $('[name=id_subcategoria]').selectpicker('val', ''); //  $('[name=id_subcategoria]').val('');
    $('[name=id_clasif]').val(2);
    $('[name=id_unidad_medida]').selectpicker('val', '1'); // $('[name=id_unidad_medida]').val(1);
    $('[name=series]').iCheck('uncheck');

    listarProductosCatalogo();
    listarProductosSugeridos(partnumber, decodeURIComponent(desc), type);

    $('#modal-mapeoAsignarProducto').modal('show');
    $('[href="#seleccionar"]').tab('show');
    $('#submit_mapeoAsignarProducto').removeAttr('disabled');

    $(".nav-tabs a[href='#crear']").on("click", function(e) {
        Swal.fire({
            title: "Para crear nuevos productos contactar con el responsable de mantenimiento de catálogo",
            icon: "info",
        }).then (function() {
            window.open('/almacen/catalogos/productos/index', '_blank');
        });
        e.preventDefault();
        return false;


    });

}

$("#form-mapeoItemsRequerimiento").on("submit", function (e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Está seguro que desea guardar los productos mapeados?',
        text: "No podrás revertir esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, Guardar'

    }).then((result) => {
        if (result.isConfirmed) {

            $("#submit_orden_despacho").attr('disabled', 'true');
            let lista = [];
            detalle.forEach(element => {

                lista.push({
                    'id_detalle_requerimiento': element.id_detalle_requerimiento,
                    'id_detalle_requerimiento_origen': element.id_detalle_requerimiento_origen,
                    'id_producto': element.id_producto,
                    'part_number': (element.id_producto !== null ? '' : element.part_number),
                    'descripcion': (element.id_producto !== null ? '' : element.descripcion),
                    'codigo': element.codigo,
                    'cantidad': element.cantidad,
                    'abreviatura': element.abreviatura,
                    'id_moneda': element.id_moneda,
                    'id_categoria': element.id_categoria,
                    'id_clasif': element.id_clasif,
                    'id_subcategoria': element.id_subcategoria,
                    'id_unidad_medida': element.id_unidad_medida,
                    'series': element.series,
                    'estado': element.estado
                });
                // }
            });

            $.ajax({
                type: 'POST',
                url: 'guardar_mapeo_productos',
                data: {
                    detalle: lista
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                dataType: 'JSON',
                beforeSend: data => {

                    $("#modal-mapeoItemsRequerimiento .modal-dialog").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                success: function (response) {
                    if (response.response == 'ok') {
                        $("#modal-mapeoItemsRequerimiento .modal-dialog").LoadingOverlay("hide", true);
                        // console.log(response);
                        Lobibox.notify('success', {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: response.mensaje.toString()
                        });

                        if(response.status_migracion_occ!=null){
                            Lobibox.notify(response.status_migracion_occ.tipo, {
                                title: false,
                                size: 'mini',
                                rounded: true,
                                sound: false,
                                delayIndicator: false,
                                msg: response.status_migracion_occ.mensaje
                            });
                        }

                        $('#modal-mapeoItemsRequerimiento').modal('hide');

                        if (objBtnMapeo != undefined) {
                            let cantidadPorMapear = parseInt(response.cantidad_total_items) - parseInt(response.cantidad_items_mapeados);
                            // console.log(objBtnMapeo.closest("div"));
                            // console.log(cantidadTotalItemBase);
                            // console.log(contidadMapeado);
                            if (response.cantidad_items_mapeados > 0) {
                                let divBtnGroup = objBtnMapeo.closest("div");
                                let idRequerimiento = document.querySelector("form[id='form-mapeoItemsRequerimiento'] input[name='id_requerimiento']").value;

                                if (divBtnGroup.querySelector("button[name='btnOpenModalAtenderConAlmacen']") == null) {
                                    let btnOpenModalAtenderConAlmacen = document.createElement("button");
                                    btnOpenModalAtenderConAlmacen.type = "button";
                                    btnOpenModalAtenderConAlmacen.name = "btnOpenModalAtenderConAlmacen";
                                    btnOpenModalAtenderConAlmacen.className = "btn btn-primary btn-xs handleClickAtenderConAlmacen";
                                    btnOpenModalAtenderConAlmacen.title = "Reserva en almacén";
                                    btnOpenModalAtenderConAlmacen.dataset.idRequerimiento = idRequerimiento;
                                    btnOpenModalAtenderConAlmacen.innerHTML = "<i class='fas fa-dolly fa-sm'></i>";
                                    btnOpenModalAtenderConAlmacen.disabled = auth_user.id_usuario==78?false:true;
                                    divBtnGroup.appendChild(btnOpenModalAtenderConAlmacen);
                                }
                                if (divBtnGroup.querySelector("button[name='btnCrearOrdenCompraPorRequerimiento']") == null) {
                                    let btnCrearOrdenCompraPorRequerimiento = document.createElement("button");
                                    btnCrearOrdenCompraPorRequerimiento.type = "button";
                                    btnCrearOrdenCompraPorRequerimiento.name = "btnCrearOrdenCompraPorRequerimiento";
                                    btnCrearOrdenCompraPorRequerimiento.className = "btn btn-warning btn-xs handleClickCrearOrdenCompraPorRequerimiento";
                                    btnCrearOrdenCompraPorRequerimiento.title = "Crear Orden de Compra";
                                    btnCrearOrdenCompraPorRequerimiento.dataset.idRequerimiento = idRequerimiento;
                                    btnCrearOrdenCompraPorRequerimiento.innerHTML = "OC";
                                    btnCrearOrdenCompraPorRequerimiento.disabled=auth_user.id_usuario==78?false:true;
                                    divBtnGroup.appendChild(btnCrearOrdenCompraPorRequerimiento);

                                }
                            }

                            // actualizar cantidad de items por mapear
                            objBtnMapeo.querySelector("span[class='badge']").textContent = cantidadPorMapear;
                            objBtnMapeo.closest("tr").querySelector("input[type='checkbox']").dataset.mapeosPendientes = cantidadPorMapear;
                            objBtnMapeo.closest("tr").querySelector("input[type='checkbox']").dataset.mapeados = response.cantidad_items_mapeados;

                            if (response.estado_requerimiento != null && response.estado_requerimiento.hasOwnProperty('descripcion')) {
                                objBtnMapeo.closest("tr").querySelector("span[class~='estadoRequerimiento']").textContent = response.estado_requerimiento.descripcion;

                            }

                        }

                        if (document.querySelector("div[id='modal-por-regularizar']").classList.contains('in') == true) {
                            construirTablaItemsPorRegularizar(document.querySelector("div[id='modal-mapeoItemsRequerimiento'] input[name='id_requerimiento']").value); // Regularizar.js
                        }

                    }else{
                        $("#modal-mapeoItemsRequerimiento .modal-dialog").LoadingOverlay("hide", true);
                        console.log(response);
                        if(response.response=='warning'){
                            Swal.fire(
                                '',
                                response.mensaje,
                                'warning'
                            );
                        }else{
                            Lobibox.notify('warning', {
                                title: false,
                                size: 'large',
                                rounded: true,
                                sound: false,
                                delayIndicator: false,
                                msg: response.mensaje
                            });
                        }

                    }
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $("#modal-mapeoItemsRequerimiento .modal-dialog").LoadingOverlay("hide", true);
                Swal.fire(
                    '',
                    'Lo sentimos hubo un error en el servidor al intentar guardar el mapeo de producto(s), por favor vuelva a intentarlo',
                    'error'
                );
                console.log(textStatus);
                console.log(errorThrown);
            });


        }
    })
});
function makeId() {
    let ID = "";
    let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for (var i = 0; i < 12; i++) {
        ID += characters.charAt(Math.floor(Math.random() * 36));
    }
    return ID;
}

function duplicarParaDescomponerProducto(id, obj){
    let newIdTemporal= makeId();
    indexProductoOrigen= detalle.findIndex(element => element.id_detalle_requerimiento == id);
    detalle[indexProductoOrigen].cantidad_original=detalle[indexProductoOrigen].cantidad;
    detalle.splice((indexProductoOrigen+1), 0, {
        'id_detalle_requerimiento':newIdTemporal,
        'id_detalle_requerimiento_origen':id,
        'id_producto' : parseInt(obj.dataset.idProducto)>0?parseInt(obj.dataset.idProducto):null,
        'codigo' : obj.dataset.codigo,
        'part_number' : (obj.dataset.partnumber).length>0?obj.dataset.partnumber:null,
        'cantidad' :0,
        'cantidad_original':detalle[indexProductoOrigen].cantidad_original,
        'descripcion' : decodeURIComponent(obj.dataset.desc),
        'id_categoria' : null,
        'id_subcategoria' : null,
        'id_clasif' : null,
        'id_unidad_medida' : parseInt(obj.dataset.idUnidadMedida)>0?parseInt(obj.dataset.idUnidadMedida):null,
        'abreviatura' : obj.dataset.unidadMedida,
        'series' : null,
        'id_moneda' : parseInt(obj.dataset.idMoneda)??null,
        'descripcion_moneda' : obj.dataset.moneda,
        'estado' :1,
        'tiene_transformacion' : obj.dataset.tieneTransformacion ==true?true:null
        });

    // poner tambien un input para cantidad en el item que se quier clonar
    obj.closest('tr').querySelector("td[name='tdCantidad']").innerHTML=`<input type="number" class="form-control handleBlurUpdateCantidadItem" step="0.1" name="cantidad" max="${detalle[indexProductoOrigen].cantidad_original}" data-id="${id}" value="${detalle[indexProductoOrigen].cantidad}"   >`;
    //poner linea clonada
    obj.closest('tr').insertAdjacentHTML('afterend', `<tr>
    <td></td>
    <td>${obj.dataset.codigo}</td>
    <td></td>
    <td>${obj.dataset.partnumber} ${obj.dataset.tieneTransformacion==true?'<span class="badge badge-secondary">Transformado</span>':''}</td>
    <td>${decodeURIComponent(obj.dataset.desc)}</td>
    <td name="tdCantidad"> <input type="number" class="form-control handleBlurUpdateCantidadItem" step="0.1" name="cantidad" max="${obj.dataset.cantidad}" data-id="${newIdTemporal}" data-id-detalle-requerimiento-origen="${id}" value="0" ></td>
    <td>${obj.dataset.unidadMedida}</td>
    <td>${obj.dataset.moneda}</td>
    <td>
        <button type="button" style="padding-left:8px;padding-right:7px;"
        class="asignar btn btn-xs btn-info boton" data-toggle="tooltip"
        data-placement="bottom" data-partnumber="${obj.dataset.partnumber}"
        data-desc="${obj.dataset.desc}" data-id="${newIdTemporal}"
        title="Asignar producto" >
        <i class="fas fa-angle-double-right"></i>
        </button>

        <button type="button" style="padding-left:8px;padding-right:7px;"
        class="anular btn btn-xs btn-danger boton" data-toggle="tooltip"
        data-placement="bottom" data-partnumber="${obj.dataset.partnumber}"
        data-desc="${obj.dataset.desc}" data-id="${newIdTemporal}"
        title="Anular" >
        <i class="fas fa-times"></i>
    </button>
    </td>
    </tr>
    `);

}
