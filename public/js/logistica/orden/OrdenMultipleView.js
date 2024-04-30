// ============== View =========================
var vardataTables = funcDatatables();
var simboloMoneda = '';
var tablaListaRequerimientosParaVincular;
var $tablaHistorialOrdenesElaboradas;
var $tablaListaCatalogoProductos;
var detalleOrdenList = [];
var iTableCounter = 1;
var oInnerTable;
var actionPage = null;

var $tablaListaRequerimientosPendientes;
var iTableCounter = 1;
var oInnerTable;
class OrdenView {
    constructor(ordenCtrl) {
        this.ordenCtrl = ordenCtrl;
        this.ordenArray = [];
        this.idOrdenSeleccionada = '';
    }

    getTipoCambioCompra() {

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        let fechaHoy = now.toISOString().slice(0, 10)

        this.ordenCtrl.getTipoCambioCompra(fechaHoy).then(function (tipoCambioCompra) {
            document.querySelector("input[name='tipo_cambio_compra']").value = tipoCambioCompra;
        }).catch(function (err) {
            console.log(err)
        })
    }

    limpiarTabla(idElement) {
        let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
        if (nodeTbody != null) {
            while (nodeTbody.children.length > 0) {
                nodeTbody.removeChild(nodeTbody.lastChild);
            }

        }
    }

    init() {


        // variable session storage: reqCheckedList -> continene un array de los id de requerimiento seleccionados en lista pendiente 
        // variable session storage: tipoOrden -> puede tener los valor: COMPRA , SERVICIO
        // variable session storage: action -> puede tener los valor: register, edition, historial (para mostrar una orden) 


        var reqTrueList = JSON.parse(sessionStorage.getItem('reqCheckedList'));

        var tipoOrden = sessionStorage.getItem('tipoOrden');
        if (reqTrueList != undefined && reqTrueList != null && (reqTrueList.length > 0)) {
            this.obtenerRequerimiento(reqTrueList, tipoOrden);
            sessionStorage.removeItem('reqCheckedList');
            sessionStorage.removeItem('tipoOrden');
        }
        var idOrden = sessionStorage.getItem('idOrden');
        actionPage = sessionStorage.getItem('action');
        // sessionStorage.removeItem('action');

        if (idOrden > 0) {
            this.mostrarOrden(idOrden);
            sessionStorage.removeItem('idOrden');
            sessionStorage.removeItem('action');
        }

        $('#form-orden').on("click", "button.handleClickCrearNuevaOrden", (e) => {
            this.crearNuevaOrden();
        });
        $('#form-orden').on("click", "button.handleClickGuardarOrden", (e) => {
            this.guardarOrdenes();
        });
        $('#form-orden').on("click", "button.handleClickMigrarOrdenASoftlink", (e) => {
            this.migrarOrdenes();
        });
        $('#form-orden').on("change", "select.onChangeSeleccionarProveedor", (e) => {
            this.llenarDatosCabeceraSeccionProveedor(e.currentTarget.value)
        });

        $('#form-orden').on("change", "select.seleccionarDatoCabeceraConcatoProveedor", (e) => {
            document.querySelector("p[name='telefono_contacto']").textContent = e.currentTarget.options[e.currentTarget.selectedIndex].dataset.telefono
        });
        $('#form-orden').on("click", "button.agregarCuentaProveedor", () => {
            this.agregarCuentaProveedor();
        });

        $("#form-agregar-cuenta-bancaria-proveedor").on("submit", (e) => {
            e.preventDefault();
            document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").setAttribute("disabled", true);
            this.guardarCuentaBancariaProveedor();
        });
        $('#form-orden').on("change", "select.handleChangeFormaPago", () => {
            this.actualizarFormaPago();
        });
        $('#form-orden').on("change", "select.handleChangeSede", (e) => {
            this.changeSede(e.currentTarget);
        });
        $('#modal-lista-requerimientos-pendientes').on("click", "button.handleClickSeleccionarRequerimientoPendiente", (e) => {
            this.seleccionarRequerimientoPendiente(e.currentTarget);
        });

        $('#contenedor_orden').on("click", "button.handleClickSeleccionarOrden", (e) => {
            this.seleccionarOrden(e.currentTarget.dataset.id);
        });
        $('#contenedor_orden').on("click", "button.handleClickImprimirOrden", (e) => {
            this.imprimirOrden(e.currentTarget.dataset.id);
        });
        $('#contenedor_orden').on("click", "button.handleClickEditarOrden", (e) => {
            this.editarOrden(e.currentTarget.dataset.id);
        });
        $('#contenedor_orden').on("click", "button.handleClickAnularOrden", (e) => {
            this.anularOrden(e.currentTarget.dataset.id);
        });
        $('#contenedor_orden').on("click", "button.handleClickAbrirCatalogoProductos", () => {
            this.abrirCatalogoProductos();
        });
        $('#listaCatalogoProductos tbody').on("click", "button.handleClickSelectProducto", (e) => {
            this.selectProducto(e.currentTarget);
        });
        $('#contenedor_orden').on("click", "button.handleClickAgregarServicio", () => {
            this.agregarServicio();
        });
        $('#contenedor_orden').on("click", "button.handleClickEliminarItemOrden", (e) => {
            this.eliminarItemOrden(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateTipoOrden", (e) => {
            this.updateTipoOrden(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdatePeriodo", (e) => {
            this.updatePeriodo(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateMoneda", (e) => {
            this.updateMoneda(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "input.handleChangeUpdateFechaEmision", (e) => {
            this.updateFechaEmision(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateSede", (e) => {
            this.updateSede(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateProveedor", (e) => {
            this.updateProveedor(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateCuentaBancariaProveedor", (e) => {
            this.updateCuentaBancariaProveedor(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateContactoProveedor", (e) => {
            this.updateContactoProveedor(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateRubroProveedor", (e) => {
            this.updateRubroProveedor(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateFormaPago", (e) => {
            this.updateFormaPago(e.currentTarget);
        });

        $('#contenedor_orden').on("keyup", "input.handleKeyUpUpdatePlazoEntrega", (e) => {
            this.updatePlazoEntrega(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangeUpdateTipoDocumento", (e) => {
            this.updateTipoDocumento(e.currentTarget);
        });
        
        $('#contenedor_orden').on("keyup", "input.handleChangeKeyUpDireccionEntrega", (e) => {
            this.updateDireccionEntrega(e.currentTarget);
        });
        
        $('#contenedor_orden').on("change", "select.handleChangeUpdateUbigeoEntrega", (e) => {
            this.updateUbigeoEntrega(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "input.handleChangeUpdateCompraLocal", (e) => {
            this.updateCompraLocal(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangePersonalAutorizado1", (e) => {
            this.updatePersonalAutorizado1(e.currentTarget);
        });

        $('#contenedor_orden').on("change", "select.handleChangePersonalAutorizado2", (e) => {
            this.updatePersonalAutorizado2(e.currentTarget);
        });

        $('#contenedor_orden').on("keyup", "textarea.handleKeyUpUpdateObservacion", (e) => {
            this.updateObservacion(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("keyup", "textarea.handleChangeUpdateDescripcionComplementaria", (e) => {
            this.updateDescripcionComplementaria(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("keyup", "textarea.handleChangeUpdateDescripcionServicio", (e) => {
            this.updateDescripcionServicio(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("keyup", "input.handleChangeUpdateCantidad", (e) => {
            this.updateCantidad(e.currentTarget);
        });
    
        $('table[name="listaDetalleOrden"]').on("change", "input.handleChangeUpdateCantidad", (e) => {
            this.updateCantidad(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("keyup", "input.handleChangeUpdatePrecio", (e) => {
            this.updatePrecio(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("change", "input.handleChangeUpdatePrecio", (e) => {
            this.updatePrecio(e.currentTarget);
        });
        
        $('table[name="listaDetalleOrden"]').on("change", "input.handleChangeUpdateIncluyeIGV", (e) => {
            this.updateIncluyeIGV(e.currentTarget);
        });

        $('table[name="listaDetalleOrden"]').on("change", "input.handleChangeUpdateIncluyeICBPER", (e) => {
            this.updateIncluyeICBPER(e.currentTarget);
        });
    }


    seleccionarOrden(id_orden) {
        let cardOrden = document.querySelector("ul[id='contenedor_lista_ordenes']").children;
        let cardOrdenArray = Array.from(cardOrden);
        cardOrdenArray.forEach(element => {
            element.querySelector("div[class~='panel']").classList.replace("panel-info", "panel-default");
        });
        document.querySelector("li[id='" + id_orden + "']").querySelector("div[class~='panel']").classList.replace("panel-default", "panel-info");
        (this.ordenArray).forEach(element => {
            if (element.id_orden == id_orden) {
                this.construirPanelEncabezadoOrden(element);
                this.construirPanelDetalleOrden(element.detalle);
            }
        });


    }

    imprimirOrden(id) {

    }

    editarOrden(id) {

    }

    anularOrden(id) {

        if ((this.ordenArray).length > 0) {

            this.ordenArray.forEach(element => {
                if (element.id == id) {
                    document.querySelector("li[id='" + id + "']").remove();
                }
                const filtrados = this.ordenArray.filter(item => item.id != id)
                this.ordenArray = filtrados;

                Lobibox.notify('success', {
                    title: false,
                    size: 'mini',
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: `No se eliminó la orden seleccionada`
                });

            });
        }
        // console.log(this.ordenArray);

    }

    migrarOrden(id) {
        if (/[a-zA-Z]/g.test(id) == true) {
            Swal.fire(
                '',
                'Debe guardar la orden antes de migrar',
                'warning'
            );
        }
    }



    construirCardOrden(data = null) {

        //   console.log(data);
        if (data != null) {

            let montototal = 0;
            (data.detalle).forEach(item => {
                montototal += (parseFloat(item.cantidad) * parseFloat(item.precio_unitario))
            });

            return `
            <li id="${data.id_orden}">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="display:flex; flex-direction:row; gap:0.5rem;">
                        <h5>Cód. orden: <span class="label label-default" title="Código de orden"><span name="tituloDocumentoCodigoOrden[]">${data.codigo_orden == '' ? '(Sin generar)' : data.codigo_orden}</span></span></h5>
                        <h5>Cód. Softlink: <span class="label label-default" title="Código de Softlink"><span name="tituloDocumentoCodigoSoftlink[]">${data.codigo_softlink == '' ? '(Sin generar)' : data.codigo_softlink}</span></span></h5>
                    </div>
                    <div class="panel-body">
                        <ul class="list-inline">
                            <li>
                                <dl>
                                    <dt>Empresa:</dt>
                                    <dd>${data.descripcion_empresa}</dd>
                                    <dt>Sede:</dt>
                                    <dd>${data.descripcion_sede}</dd>
                                    <dt>Proveedor:</dt>
                                    <dd style="cursor:help;" title="${data.descripcion_tipo_documento_proveedor} ${data.nro_documento_proveedor}">${(data.id_proveedor >0? data.razon_social_proveedor:'(Sin proveedor)')}</dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>Fecha emsión:</dt>
                                    <dd>${data.fecha_emision == '' ? '00/00/0000' : data.fecha_emision}</dd>
                                    <dt>Importe:</dt>
                                    <dd>${data.simbolo_moneda} ${$.number(montototal, 2, ".", ",")}</dd>
                                    <dt>Cta Proveedor:</dt>
                                    <dd>${data.numero_cuenta_proveedor != '' && data.numero_cuenta_proveedor != null ? data.numero_cuenta_proveedor : (data.numero_cuenta_interbancaria_proveedor != '' && data.numero_cuenta_interbancaria_proveedor != null ? data.numero_cuenta_interbancaria_proveedor : '<small>(Sin cuenta)</small>')}</dd>
                            </li>
                            <li>

                            </li>
                        </ul>
                        <div class="text-left">
                            <button type="button" class="btn btn-xs btn-default handleClickSeleccionarOrden" id="btnSeleccionarOrden" title="Seleccionar" data-id="${data.id}"><i class="fas fa-check"></i> Seleccionar</button>
                            <button type="button" class="btn btn-xs btn-default handleClickImprimirOrden" id="btnImprimirOrden" title="Imprimir" data-id="${data.id}"><i class="fas fa-print"></i> Imprimir</button>
                            <button type="button" class="btn btn-xs btn-default handleClickEditarOrden" id="btnEditarOrden" title="Editar" data-id="${data.id}"><i class="fas fa-edit"></i> Editar</button>
                            <button type="button" class="btn btn-xs btn-default handleClickAnularOrden" id="btnAnularOrden" title="Anular" data-id="${data.id}"><i class="fas fa-trash"></i> Anular</button>
                        </div>
                    </div>
                </div>
            </li>
        `;

        } else {
            Swal.fire(
                '',
                'No hay data para mostrar',
                'warning'
            );
        }
    }

    crearNuevaOrden() {

        Swal.fire({
            title: "Desea desde un requerimiento pendiente o genera una orden libre?",
            width: 500,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Mostrar lista de requerimientos",
            denyButtonText: `Crear en orden libre`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) { // mostrar lista de requerimientos pendientes

                $('#modal-lista-requerimientos-pendientes').modal('show');

                let idEmpresa = 'SIN_FILTRO';
                let idSede = 'SIN_FILTRO';
                let fechaRegistroDesde = 'SIN_FILTRO';
                let fechaRegistroHasta = 'SIN_FILTRO';
                let reserva = 'SIN_FILTRO';
                let orden = 'SIN_FILTRO';
                let estado = 'SIN_FILTRO';

                $tablaListaRequerimientosPendientes = $('#tablaRequerimientosPendientes').DataTable({
                    'dom': 'Blfrtip',
                    'buttons': [],
                    'language': vardataTables[0],
                    'order': [[3, 'desc']],
                    'serverSide': true,
                    'destroy': true,
                    'stateSave': true,
                    'bLengthChange': false,
                    "pageLength": 20,
                    'ajax': {
                        'url': route('logistica.gestion-logistica.compras.pendientes.requerimientos-pendientes'),
                        'type': 'POST',
                        'data': { 'idEmpresa': idEmpresa, 'idSede': idSede, 'fechaRegistroDesde': fechaRegistroDesde, 'fechaRegistroHasta': fechaRegistroHasta, 'reserva': reserva, 'orden': orden, 'estado': estado },
                        beforeSend: data => {

                            $("#tablaRequerimientosPendientes").LoadingOverlay("show", {
                                imageAutoResize: true,
                                progress: true,
                                imageColor: "#3c8dbc"
                            });
                        }

                    },
                    'columns': [
                        {
                            'data': 'descripcion_prioridad', 'name': 'adm_prioridad.descripcion', 'render': function (data, type, row) {

                                return `${row['termometro']}`;
                            }
                        },
                        { 'data': 'empresa_sede', 'name': 'sis_sede.descripcion', 'className': 'text-center' },
                        {
                            'data': 'codigo', 'name': 'alm_req.codigo', 'className': 'text-center', 'render': function (data, type, row) {
                                return `${row.estado == 38 ? '<i class="fas fa-exclamation-triangle ' + (row.count_pendientes > 0 ? 'red' : 'orange') + ' handleClickAbrirModalPorRegularizar" style="cursor:pointer;" title="Por regularizar' + (row.count_pendientes > 0 ? '(Tiene ' + row.count_pendientes + ' item(s) pendientes por mapear)' : '') + '" data-id-requerimiento="' + row.id_requerimiento + '" ></i> &nbsp;' : ''}<a href="/necesidades/requerimiento/elaboracion/index?id=${row.id_requerimiento}" target="_blank" title="Abrir Requerimiento">${row.codigo}</a> ${row.tiene_transformacion == true ? '<i class="fas fa-random text-danger" title="Con transformación"></i>' : ''} `;
                            }
                        },
                        { 'data': 'fecha_registro', 'name': 'alm_req.fecha_registro', 'className': 'text-center' },
                        {
                            'data': 'fecha_entrega', 'name': 'alm_req.fecha_entrega', 'className': 'text-center', render: function (data, type, row) {
                                // return (row.fecha_entrega!= '' && row.fecha_entrega != null)?(moment(row.fecha_entrega).format('DD-MM-YYYY')):'';
                                return row.fecha_entrega;
                            }
                        },
                        { 'data': 'concepto', 'name': 'alm_req.concepto', 'className': 'text-left' },
                        { 'data': 'tipo_req_desc', 'name': 'alm_tp_req.descripcion', 'className': 'text-center' },
                        {
                            'data': 'division', 'name': 'division.descripcion', 'className': 'text-center', "searchable": false, 'render': function (data, type, row) {
                                return row.division != null ? JSON.parse(row.division.replace(/&quot;/g, '"')).join(",") : '';
                            }
                        },
                        { 'data': 'nombre_solicitado_por', 'name': 'nombre_solicitado_por', 'className': 'text-center' },
                        { 'data': 'nombre_usuario', 'name': 'nombre_usuario', 'className': 'text-center' },
                        { 'data': 'observacion', 'name': 'alm_req.observacion', 'className': 'text-left td-lg-300' },
                        {
                            'data': 'estado_doc', 'name': 'adm_estado_doc.estado_doc', 'className': 'text-center', 'render': function (data, type, row) {
                                return row['estado_doc'];
                            }
                        },
                        {
                            'data': 'id_requerimiento', 'name': 'alm_req.id_requerimiento', 'className': 'text-center', "searchable": false, 'render': function (data, type, row) {

                                let openDiv = '<div class="btn-group" role="group" style="text-align:left;">';
                                let textCantidadMapeados = row.cantidad_tipo_producto > 0 ? `<span class="label label-default" title="Cantidad de items tipo producto {mapeados}/{total de item}" style="background-color:#a99cd1 !important;" >Productos: ${row.count_mapeados} / ${row.cantidad_tipo_producto} </span>` : '';
                                let textCantidadItemServicio = row.cantidad_tipo_servicio > 0 ? `<span class="label label-default" title="Cantidad de items tipo servicios" style="background-color: #83d7d3 !important;" >Servicios: ${row.cantidad_tipo_servicio} </span>` : '';
                                let closeDiv = '</div>';
                                return openDiv + textCantidadMapeados + '<br>' + textCantidadItemServicio + closeDiv;

                            }
                        },
                        {
                            'data': 'id_requerimiento', 'name': 'alm_req.id_requerimiento', 'className': 'text-center', "searchable": false, 'render': function (data, type, row) {
                                // let tieneTransformacion = row.tiene_transformacion;
                                // let cantidadItemBase = row.cantidad_items_base;

                                let openDiv = '<div class="btn-group" role="group">';
                                let closeDiv = '</div>';
                                let btnSeleccionarHasDisable = 'disabled';
                                let mensajeTitle = [];
                                if (row.count_mapeados > 0 || row.cantidad_tipo_servicio > 0) {
                                    btnSeleccionarHasDisable = '';
                                    if ((row.estado == 1 || row.estado == 12 || row.estado == 3 || row.estado == 38 || row.estado == 39)) { // estado por observado | regularizar |  en pausa
                                        btnSeleccionarHasDisable = 'disabled';
                                    }
                                }


                                if (row.estado == 1 || row.estado == 12) {
                                    mensajeTitle.push('Se requiere aprobar el requerimiento para poder atender');
                                }
                                if (row.estado == 3) {
                                    mensajeTitle.push('Se requiere resolver la observación');
                                }
                                if (row.estado == 38) {
                                    mensajeTitle.push('Se requiere resolver el estado por regularizar primero');
                                }
                                if (row.estado == 39) {
                                    mensajeTitle.push('Debe requiere que el estado de CDP sea aprobado en etapa de compra');
                                }

                                if (((row.cantidad_tipo_producto > 0 && row.count_mapeados > 0) || row.cantidad_tipo_servicio > 0) && (row.estado == 2 || row.estado == 15 || row.estado == 27)) {
                                    mensajeTitle.push('Seleccionar');
                                }

                                if (row.count_mapeados != row.cantidad_tipo_producto) {
                                    mensajeTitle.push("Aún tiene item's por mapear");
                                }


                                return openDiv + '<button type="button" class="btn btn-' + (btnSeleccionarHasDisable != '' ? 'default' : 'success') + ' btn-xs handleClickSeleccionarRequerimientoPendiente" name="btnSeleccionar" title="' + (mensajeTitle.length > 0 ? mensajeTitle.toString() : 'Seleccionar') + '" data-cantidad-item-tipo-producto="' + row.cantidad_tipo_producto + '"  data-cantidad-item-tipo-servicio="' + row.cantidad_tipo_servicio + '" data-id-requerimiento="' + row.id_requerimiento + '"  ' + btnSeleccionarHasDisable + ' ><i class="fas fa-check"></i> Seleccionar</button>' + closeDiv;



                            }
                        }
                    ],
                    'columnDefs': [
                    ],
                    'rowCallback': function (row, data, dataIndex) {
                        // Get row ID
                        // var rowId = data.id_requerimiento;
                        // // If row ID is in the list of selected row IDs
                        // if ($.inArray(rowId, reqTrueList) !== -1) {
                        //     $(row).find('input[type="checkbox"]').prop('checked', true);
                        //     $(row).addClass('selected');
                        // }

                    },
                    'initComplete': function () {

                        //Boton de busqueda
                        const $filter = $('#tablaRequerimientosPendientes_filter');
                        const $input = $filter.find('input');
                        $filter.append('<button id="btnBuscarRequerimientosPendientes" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
                        $input.off();
                        $input.on('keyup', (e) => {
                            if (e.key == 'Enter') {
                                $('#btnBuscarRequerimientosPendientes').trigger('click');
                            }
                        });
                        $('#btnBuscarRequerimientosPendientes').on('click', (e) => {
                            $tablaListaRequerimientosPendientes.search($input.val()).draw();
                        })
                        //Fin boton de busqueda
                    },
                    "drawCallback": function (settings) {
                        //Botón de búsqueda
                        $('#tablaRequerimientosPendientes_filter input').prop('disabled', false);
                        $('#btnBuscarRequerimientosPendientes').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                        $('#tablaRequerimientosPendientes_filter input').trigger('focus');
                        //fin botón búsqueda
                        if ($tablaListaRequerimientosPendientes.rows().data().length == 0) {
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
                        $('#tablaRequerimientosPendientes_filter input').prop('disabled', false);
                        $('#btnBuscarRequerimientosPendientes').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                        $('#tablaRequerimientosPendientes_filter input').trigger('focus');
                        //fin botón búsqueda
                        $("#tablaRequerimientosPendientes").LoadingOverlay("hide", true);

                    },
                    "createdRow": function (row, data, dataIndex) {

                        let color = '#ffffff';
                        switch (data.bootstrap_color) {
                            case 'default':
                                color = '#d7d7d7';
                                break;
                            case 'primary':
                                color = '#5caad9';
                                break;
                            case 'success':
                                color = '#a2c9a2';
                                break;
                            case 'secundary':
                                color = '#cbc0d6';
                                break;
                            case 'warning':
                                color = '#e8e9bc';
                                break;
                            case 'info':
                                color = '#72bcd4';
                                break;
                            case 'danger':
                                color = '#98beca';
                                break;

                            default:
                                color = '#f2f2f2';
                                break;
                        }
                        $(row.childNodes[11]).css('background-color', color);
                    }

                });
                // 

            } else if (result.isDenied) { // limpiar todo para genera orden libre

                this.agregarOrdenVacia();
                // $('select[name="id_sede[]"]').selectpicker();

            }
        });

    }


    agregarOrdenVacia(){

        let cabeceraOrdenObject = {
            'id_orden': 'ORDENVACIA' + this.makeId(),
            'id_tipo_orden': 1,
            'descripcion_tipo_orden': 'Orden compra',
            'codigo_orden': '',
            'id_moneda': 1,
            'simbolo_moneda': 'S/',
            'tipo_cambio': '',
            'id_softlink': '',
            'codigo_softlink': '',
            'id_periodo': '',
            'descripcion_periodo':'',
            'fecha_emision': '',
            'id_empresa': 1,
            'descripcion_empresa': 'OK Computer',
            'id_sede': 4,
            'descripcion_sede': 'Lima',
            'id_proveedor_mgc': '',
            'id_proveedor': '',
            'razon_social_proveedor_mgc': '',
            'razon_social_proveedor': '',
            'id_tipo_documento_proveedor': '',
            'descripcion_tipo_documento_proveedor': '',
            'nro_documento_proveedor': '',
            'direccion_fiscal_proveedor': '',
            'id_cuenta_proveedor': '',
            'numero_cuenta_proveedor': '',
            'numero_cuenta_interbancaria_proveedor': '',
            'simbolo_moneda_cuenta_proveedor': '',
            'id_moneda_cuenta_proveedor': '',
            'id_contacto_proveedor': '',
            'nombre_contacto_proveedor': '',
            'telefono_contacto_proveedor': '',
            'cargo_contacto_proveedor': '',
            'id_rubro_proveedor': '',
            'descripcion_rubro_proveedor': '',
            'id_condicion_compra': '',
            'descripcion_condicion_compra': '',
            'id_condicion_softlink': '',
            'descripcion_condicion_softlink': '',
            'plazo_entrega_dias': 1,
            'requerimiento_vinculado_list': [],
            'id_tipo_documento': 2,
            'descripcion_tipo_documento': '01 - Factura',
            'direccion_entrega': 'Jr. Domingo Martinez Lujan 1135, Surquillo',
            'id_ubigeo_entrega': 1321,
            'descripcion_ubigeo_entrega':  '150141 - SURQUILLO - LIMA - LIMA',
            'id_personal_autorizado_1': '',
            'nombre_personal_autorizado_1': '',
            'id_personal_autorizado_2': '',
            'nombre_personal_autorizado_2': '',
            'es_compra_local': false,
            'observacion': '',
            'id_estado_orden': '',
            'descripcion_estado_orden': '',
            'monto_neto':0,
            'monto_igv':0,
            'monto_icbper':0,
            'monto_total':0,
            'detalle': []

        };

        this.ordenArray.push(cabeceraOrdenObject);

        this.construirPanelListaOrdenes(this.ordenArray);

    }

    llenarDatosCabeceraSeccionProveedor(idProveedor, idCuentaProveedor = null) {
        this.ordenCtrl.obtenerDataProveedor(idProveedor).then((res) => {
            document.querySelector("p[name='direccion_proveedor']").textContent = res.contribuyente != null ? res.contribuyente.direccion_fiscal : '';
            // TODO : 1) ACtualizar direccion proveedor en this.ordenArray 
            this.llenarDatosCabeceraCuentaBancariaProveedor(res.cuenta_contribuyente, idCuentaProveedor);
            this.llenarDatosCabeceraConcactoProveedor(res.contacto_contribuyente);
            // document.querySelector("select[name='contacto_proveedor']").textContent = '';
            // document.querySelector("p[name='telefono_contacto']").textContent = '';
            // document.querySelector("select[name='rubro_proveedor']").textContent = '';

        });

    }

    llenarDatosCabeceraCuentaBancariaProveedor(data, idCuentaSelected = null) {
        let selectElement = document.querySelector("select[name='id_cuenta_bancaria_proveedor']");

        if (selectElement.options.length > 0) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        data.forEach(element => {
            let option = document.createElement("option");

            if (idCuentaSelected != null) {
                if (element.id_cuenta_contribuyente == idCuentaSelected) {
                    option.setAttribute('selected', true);
                }
            } else if (element.por_defecto == true) {
                option.setAttribute('selected', true);
            }

            option.text = element.nro_cuenta != null ? element.nro_cuenta : (element.nro_cuenta_interbancaria != null ? element.nro_cuenta_interbancaria : '');
            option.value = element.id_cuenta_contribuyente;
            selectElement.add(option);
        });
    }

    llenarDatosCabeceraConcactoProveedor(data) {
        let selectElement = document.querySelector("select[name='id_contacto_proveedor']");

        if (selectElement.options.length > 0) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        data.forEach(element => {
            let option = document.createElement("option");

            option.text = element.nombre;
            option.dataset.telefono = element.telefono;
            option.value = element.id_datos_contacto;
            selectElement.add(option);
        });

        // cargar telefono de contacto seleccionado
        if (document.querySelector("select[name='id_contacto_proveedor']").length > 0) {
            document.querySelector("p[name='telefono_contacto']").textContent = selectElement.options[selectElement.selectedIndex].dataset.telefono;
        }
    }



    //### proveedor

    limpiarFormularioCuentaBancaria() {
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta']").value = '' : false;
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta_interbancaria']").value = '' : false;
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='swift']").value = '' : false;
        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");
    }
    agregarCuentaProveedor() {

        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] strong[id='nombre_contexto']").textContent = "Proveedores";

        const selectProveedor = document.querySelector("select[name='id_proveedor']");
        let razonSocialProveedor = selectProveedor.options[selectProveedor.selectedIndex].dataset.razonSocial;
        let id = selectProveedor.value;

        if (id > 0) {
            $('#modal-agregar-cuenta-bancaria-proveedor').modal({
                show: true
            });
            this.limpiarFormularioCuentaBancaria();

            document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] span[id='razon_social_proveedor']").textContent = razonSocialProveedor;
            document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='id_proveedor']").value = id;

        } else {
            Swal.fire(
                '',
                'Debe seleccionar un proveedor',
                'warning'
            );
        }

    }

    guardarCuentaBancariaProveedor() {
        let idProveedor = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='id_proveedor']").value;
        let banco = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='banco']").value;
        let idMoneda = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='moneda']").value;
        let tipoCuenta = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='tipo_cuenta_banco']").value;
        let nroCuenta = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta']").value;
        let nroCuentaInter = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta_interbancaria']").value;
        let swift = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='swift']").value;
        let mensajeValidación = '';

        if (nroCuenta == '' || nroCuenta == null) {
            mensajeValidación += "Debe escribir un número de cuenta";
        }

        if (mensajeValidación.length > 0) {
            Lobibox.notify('warning', {
                title: false,
                size: 'normal',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: mensajeValidación
            });
            document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

        } else {
            $.ajax({
                type: 'POST',
                url: 'guardar-cuenta-bancaria-proveedor',
                data: {
                    'id_proveedor': idProveedor,
                    'id_banco': banco,
                    'id_moneda': idMoneda,
                    'id_tipo_cuenta': tipoCuenta,
                    'nro_cuenta': nroCuenta,
                    'nro_cuenta_interbancaria': nroCuentaInter,
                    'swift': swift
                },
                cache: false,
                dataType: 'JSON',
                success: (response) => {
                    console.log(response);
                    if (response.status == '200') {
                        $('#modal-agregar-cuenta-bancaria-proveedor').modal('hide');
                        Lobibox.notify('success', {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: 'Cuenta bancaria registrado con éxito'
                        });

                        this.actualizarSelectCuentasBancariasProveedor(idProveedor, response.id_cuenta_contribuyente);
                        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

                    } else {
                        Swal.fire(
                            '',
                            'Hubo un error al intentar guardar la cuenta bancaria del proveedor, por favor intente nuevamente',
                            'error'
                        );
                        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

                    }



                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    '',
                    'Hubo un error al intentar guardar la cuenta bancaria del proveedor. ' + errorThrown,
                    'error'
                );
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

            });
        }
    }

    getCuentasBancarias(idProveedor) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-cuentas-bancarias-proveedor/${idProveedor}`,
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

    actualizarSelectCuentasBancariasProveedor(idProveedor, idCuentaBancaria = null) {
        this.getCuentasBancarias(idProveedor).then((res) => {
            if (res[0].cuenta_contribuyente) {
                this.llenarDatosCabeceraCuentaBancariaProveedor(res[0].cuenta_contribuyente, idCuentaBancaria);
            }
        }).catch((err) => {
            Swal.fire(
                '',
                'Hubo un problema al intentar obtener la lista de cuentas bancarias, por favor vuelva a intentarlo',
                'error'
            );
            console.log(err)
        })
    }



    //###

    actualizarFormaPago() {
        let selectFormaPago = document.querySelector("select[name='id_condicion_softlink']");
        let dias_condicion_softlink = selectFormaPago.options[selectFormaPago.selectedIndex].dataset.dias;

        if (dias_condicion_softlink > 0) {
            document.getElementsByName('id_condicion').value = 2;
            document.getElementsByName('plazo_dias').value = dias_condicion_softlink;
        } else {
            document.getElementsByName('id_condicion').value = 1;
            document.getElementsByName('plazo_dias').value = 0;
        }
    }


    changeSede(obj) {
        var id_empresa = obj.options[obj.selectedIndex].getAttribute('data-id-empresa');
        var id_ubigeo = obj.options[obj.selectedIndex].getAttribute('data-id-ubigeo');
        var ubigeo_descripcion = obj.options[obj.selectedIndex].getAttribute('data-ubigeo-descripcion');
        var direccion = obj.options[obj.selectedIndex].getAttribute('data-direccion');
        this.changeLogoEmprsa(id_empresa);
        this.llenarUbigeo(direccion, id_ubigeo, ubigeo_descripcion);

    }

    llenarUbigeo(direccion, id_ubigeo, ubigeo_descripcion) {
        document.querySelector("input[name='direccion_entrega']").value = direccion;
        document.querySelector("select[name='id_ubigeo_destino']").value = id_ubigeo;
        $("select[name='id_ubigeo_destino']").trigger("change");
    }


    changeLogoEmprsa(id_empresa) {
        switch (id_empresa) {
            case '1':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_okc.png');
                break;
            case '2':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_proyectec.png');
                break;
            case '3':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_smart.png');
                break;
            case '4':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/jedeza_logo.png');
                break;
            case '5':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/rbdb_logo.png');
                break;
            case '6':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/protecnologia_logo.png');
                break;
            default:
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/img-wide.png');
                break;
        }
    }



    obtenerAtencionItemRequerimiento(id) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: route('logistica.gestion-logistica.compras.ordenes.elaborar.obtener-atencion-de-item-requerimiento', id),
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


    seleccionarRequerimientoPendiente(obj) {
        const idRequerimiento = obj.dataset.idRequerimiento;

        let continuar = true;
        this.ordenArray.forEach(arr => {
            (arr.requerimiento_vinculado_list).forEach(req => {
                if (req.id_requerimiento == idRequerimiento) {
                    continuar = false;
                }
            });
        });

        if (continuar) {
            $("#modal-lista-requerimientos-pendientes .modal-content").LoadingOverlay("show", {
                imageAutoResize: true,
                progress: true,
                imageColor: "#3c8dbc"
            });

            let cantidadItemsTipoProductosPorAtender = 0;
            let cantidadItemsTipoServiciosPorAtender = 0;
            let detalleRequerimientoList = [];
            let cabeceraRequerimiento = [];
            let DataFiltradaDetalleRequerimientoList = [];
            this.obtenerAtencionItemRequerimiento(idRequerimiento).then((response) => {
                // console.log(response);
                if (response.success == true) {
                    $("#modal-lista-requerimientos-pendientes .modal-content").LoadingOverlay("hide", true);
                    cabeceraRequerimiento = response.requerimiento;
                    DataFiltradaDetalleRequerimientoList = response.detalle_requerimiento_list;

                    response.estado_item_list.forEach(element => {
                        if (element.id_tipo_item == 1 && element.tiene_atencion_total == false) {
                            cantidadItemsTipoProductosPorAtender++;
                        }
                        if (element.id_tipo_item == 2 && element.tiene_atencion_total == false) {
                            cantidadItemsTipoServiciosPorAtender++;
                        }

                    });
                    // const cantidadItemTipoProducto = (parseInt(obj.dataset.cantidadItemTipoProducto) >0 ? parseInt(obj.dataset.cantidadItemTipoProducto): 0);
                    // const cantidadItemTipoServicio = (parseInt(obj.dataset.cantidadItemTipoServicio) >0 ? parseInt(obj.dataset.cantidadItemTipoServicio):0);

                    if (cantidadItemsTipoProductosPorAtender > 0 && cantidadItemsTipoServiciosPorAtender > 0) {
                        Swal.fire({
                            title: "Se detectó item's tipo producto y servicio, Qué desea hacer?",
                            width: 500,
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: "Cargar todos",
                            denyButtonText: `Seleccionar un tipo`
                        }).then((result) => {
                            if (result.isConfirmed) { //* cargar ambos tipos de item (productos mapeados y servicios)

                                this.construirOrdenConRequerimiento(cabeceraRequerimiento, DataFiltradaDetalleRequerimientoList, 'ORDEN_COMPRA');
                            } else if (result.isDenied) { //* dar opcion de elergir un tipo de item

                                Swal.fire({
                                    title: "Seleccione un tipo de item para cargar",
                                    width: 500,
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: "Productos",
                                    denyButtonText: `Servicios`
                                }).then((result) => {
                                    if (result.isConfirmed) { //* Seleccion de solo cargar productos mapeados
                                        detalleRequerimientoList.forEach(element => {
                                            if (element.id_tipo_item == 1 && element.id_producto != null) {
                                                DataFiltradaDetalleRequerimientoList.push(element);
                                            }
                                        });
                                        this.construirOrdenConRequerimiento(cabeceraRequerimiento, DataFiltradaDetalleRequerimientoList, 'ORDEN_COMPRA');

                                    } else if (result.isDenied) { //* Seleccion de solo cargar servicios
                                        detalleRequerimientoList.forEach(element => {
                                            if (element.id_tipo_item == 2) {
                                                DataFiltradaDetalleRequerimientoList.push(element);
                                            }
                                        });
                                        this.construirOrdenConRequerimiento(cabeceraRequerimiento, DataFiltradaDetalleRequerimientoList, 'ORDEN_SERVICIO');
                                    }
                                });

                            }
                        });

                    } else {

                        if (cantidadItemsTipoProductosPorAtender > 0) { //* cargar para tipo de item producto mapeados
                            detalleRequerimientoList.forEach(element => {
                                if (element.id_tipo_item == 1 && element.id_producto != null) {
                                    DataFiltradaDetalleRequerimientoList.push(element);
                                }
                            });
                            this.construirOrdenConRequerimiento(cabeceraRequerimiento, DataFiltradaDetalleRequerimientoList, 'ORDEN_COMPRA');
                        }
                        if (cantidadItemsTipoServiciosPorAtender > 0) { //* cargar para tipo de item servicio
                            detalleRequerimientoList.forEach(element => {
                                if (element.id_tipo_item == 2) {
                                    DataFiltradaDetalleRequerimientoList.push(element);
                                }
                            });
                            this.construirOrdenConRequerimiento(cabeceraRequerimiento, DataFiltradaDetalleRequerimientoList, 'ORDEN_SERVICIO');
                        }


                    }

                }
            }).catch((err) => {
                $("#modal-lista-requerimientos-pendientes .modal-content").LoadingOverlay("hide", true);
                console.log(err)
                Swal.fire(
                    '',
                    'Lo sentimos hubo un error en el servidor, por favor vuelva a intentarlo',
                    'error'
                );
            });

        } else {
            Swal.fire(
                '',
                'El requerimiento ya fue agregado',
                'warning'
            );
        }

    }

    makeId() {
        let ID = "";
        let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (var i = 0; i < 12; i++) {
            ID += characters.charAt(Math.floor(Math.random() * 36));
        }
        return ID;
    }

    construirOrdenConRequerimiento(cabeceraRequerimiento, detalleRequerimiento, TipoDocumento) {
        let idTipoOrden = "";
        let cabeceraRequerimientoObject = {};
        switch (TipoDocumento) {
            case 'ORDEN_COMPRA': // administracion.adm_tp_docum.id_tp_documento = 2
                idTipoOrden = 2;

                break;

            case 'ORDEN_SERVICIO':// administracion.adm_tp_docum.id_tp_documento = 3
                idTipoOrden = 3;

                break;

            case 'ORDEN_IMPORTACION':// administracion.adm_tp_docum.id_tp_documento = 12
                idTipoOrden = 12;

                break;

            default:
                break;
        }

        cabeceraRequerimientoObject = {
            'id_requerimiento': cabeceraRequerimiento.id_requerimiento,
            'codigo': cabeceraRequerimiento.codigo,
            'id_periodo': cabeceraRequerimiento.id_periodo,
            'descripcion_periodo': cabeceraRequerimiento.periodo.descripcion,
            'id_moneda': cabeceraRequerimiento.id_moneda,
            'simbolo_moneda': cabeceraRequerimiento.moneda.simbolo,
            'tipo_cambio': cabeceraRequerimiento.tipo_cambio > 0 ? cabeceraRequerimiento.tipo_cambio : cabeceraRequerimiento.tipo_cambio_venta,
            'id_empresa': cabeceraRequerimiento.id_empresa,
            'descripcion_empresa': cabeceraRequerimiento.empresa.contribuyente.razon_social,
            'id_sede': cabeceraRequerimiento.id_sede,
            'descripcion_sede': cabeceraRequerimiento.sede.codigo,
            'direccion_fiscal_empresa': cabeceraRequerimiento.empresa.contribuyente.direccion_fiscal,
            'id_ubigeo_empresa': cabeceraRequerimiento.empresa.contribuyente.ubigeo,
            'descripcion_ubigeo_empresa': cabeceraRequerimiento.empresa.contribuyente.ubigeo_completo,
            'observacion': cabeceraRequerimiento.observacion
        };

        let proveedorDetReq = [];
        // console.log(detalleRequerimiento);
        detalleRequerimiento.forEach(detReq => {
            let registradoEnProveedorDetReq = 0;
            if (detReq.proveedor_seleccionado_id > 0 || detReq.proveedor_seleccionado != null) {
                proveedorDetReq.forEach((provDetReqValue, ProvDetReqIndex) => {
                    if (provDetReqValue.proveedor_seleccionado_id == detReq.proveedor_seleccionado_id || (provDetReqValue.proveedor_seleccionado != null && provDetReqValue.proveedor_seleccionado == detReq.proveedor_seleccionado)) {
                        registradoEnProveedorDetReq++;
                    }
                });
                if (registradoEnProveedorDetReq == 0) {

                    proveedorDetReq.push(
                        {
                            'id_orden': 'ORDEN' + this.makeId(),
                            'detalle_requerimiento_list': [detReq],
                            'proveedor_seleccionado_id': detReq.proveedor_seleccionado_id, //mgc
                            'proveedor_seleccionado': detReq.proveedor_seleccionado, //mgc
                            'id_proveedor': detReq.proveedor != null ? detReq.proveedor.id_proveedor : '',
                            'id_contribuyente': detReq.proveedor != null ? detReq.proveedor.id_contribuyente : '',
                            'razon_social_proveedor': detReq.proveedor != null ? detReq.proveedor.razon_social : '',
                            'id_tipo_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.id_tipo_documento : '',
                            'descripcion_tipo_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.descripcion_tipo_documento : '',
                            'nro_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.nro_documento : '',
                            'direccion_fiscal_proveedor': detReq.proveedor != null ? detReq.proveedor.direccion_fiscal : '',
                            'id_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.id_cuenta_bancaria : '',
                            'id_moneda_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.id_moneda_cuenta_bancaria : '',
                            'simbolo_moneda_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.simbolo_moneda_cuenta_bancaria : '',
                            'numero_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.numero_cuenta_bacnaria : '',
                            'numero_cuenta_interbancaria_proveedor': detReq.proveedor != null ? detReq.proveedor.numero_cuenta_interbacnaria : '',
                            'id_concato_proveedor': detReq.proveedor != null ? detReq.proveedor.id_contacto : '',
                            'nombre_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.nombre_contacto : '',
                            'telefono_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.telefono_contacto : '',
                            'cargo_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.cargo_contacto : ''
                        }
                    );
                } else {
                    proveedorDetReq.forEach((provDetReqValue, ProvDetReqIndex) => {
                        if (provDetReqValue.proveedor_seleccionado_id == detReq.proveedor_seleccionado_id || provDetReqValue.proveedor_seleccionado == detReq.proveedor_seleccionado) {
                            proveedorDetReq[ProvDetReqIndex]['detalle_requerimiento_list'].push(detReq);

                            if (proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado_id'] == null && detReq.proveedor_seleccionado_id > 0) {
                                proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado_id'] = detReq.proveedor_seleccionado_id;
                            }
                            if (proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado'] == null && detReq.proveedor_seleccionado != null) {
                                proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado'] = detReq.proveedor_seleccionado;
                            }

                        }
                    });

                }
            } else {
                proveedorDetReq.push(
                    {
                        'id_orden': 'ORDEN' + this.makeId(),
                        'detalle_requerimiento_list': [detReq],
                        'proveedor_seleccionado_id': null,
                        'proveedor_seleccionado': null,
                        'id_proveedor': detReq.proveedor != null ? detReq.proveedor.id_proveedor : '',
                        'id_contribuyente': detReq.proveedor != null ? detReq.proveedor.id_contribuyente : '',
                        'razon_social_proveedor': detReq.proveedor != null ? detReq.proveedor.razon_social : '',
                        'id_tipo_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.id_tipo_documento : '',
                        'descripcion_tipo_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.descripcion_tipo_documento : '',
                        'nro_documento_proveedor': detReq.proveedor != null ? detReq.proveedor.nro_documento : '',
                        'direccion_fiscal_proveedor': detReq.proveedor != null ? detReq.proveedor.direccion_fiscal : '',
                        'id_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.id_cuenta_bancaria : '',
                        'id_moneda_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.id_moneda_cuenta_bancaria : '',
                        'simbolo_moneda_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.simbolo_moneda_cuenta_bancaria : '',
                        'numero_cuenta_proveedor': detReq.proveedor != null ? detReq.proveedor.numero_cuenta_bacnaria : '',
                        'numero_cuenta_interbancaria_proveedor': detReq.proveedor != null ? detReq.proveedor.numero_cuenta_interbacnaria : '',
                        'id_concato_proveedor': detReq.proveedor != null ? detReq.proveedor.id_contacto : '',
                        'nombre_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.nombre_contacto : '',
                        'telefono_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.telefono_contacto : '',
                        'cargo_contacto_proveedor': detReq.proveedor != null ? detReq.proveedor.cargo_contacto : ''
                    });
            }
        });

        // console.log(proveedorDetReq);
        // revisar si en this.ordenArray existe el proveedor y añadir en esa objeto de orden los item

        let proveedorDetReqNuevaOrden = [];
        let idProvDetReqAtendidosArray = [];
        if ((this.ordenArray).length > 0) {
            (proveedorDetReq).forEach(pdr => {
                (this.ordenArray).forEach((oa, k) => {
                    if ((oa.id_proveedor_mgc == pdr.proveedor_seleccionado_id && pdr.proveedor_seleccionado_id != null) || (oa.razon_social_proveedor_mgc == pdr.proveedor_seleccionado && pdr.proveedor_seleccionado != null)) { // incluir en orden
                        pdr.detalle_requerimiento_list.forEach(drl => {
                            this.ordenArray[k].detalle.push(drl)
                        });
                        idProvDetReqAtendidosArray.push(pdr.id_orden); // lista de atendidos que deben ser compara con proveedorDetReq para determinar los que falta agregar como nueva orden
                    }

                });
            });

            // agregado a nueva orden 

            (proveedorDetReq).forEach(pdr => {
                if (!idProvDetReqAtendidosArray.includes(pdr.id_orden)) {
                    proveedorDetReqNuevaOrden.push(pdr);
                }
            });


        } else {// si el this.ordenArray esta vacio, es el primero
            (proveedorDetReq).forEach(pdr => {
                proveedorDetReqNuevaOrden.push(pdr);
            });
        }



        // crear un array de objetos de ordenes ( el total por proveedor)
        // console.log(proveedorDetReqNuevaOrden);
        let montoNetoOrden =0;
        let montoIgvOrden =0;
        let montoIcbperOrden =0;
        let montoTotalOrden =0;

        proveedorDetReqNuevaOrden.forEach(provDetReqValue => {

            let detalleOrdenObject = [];
            provDetReqValue.detalle_requerimiento_list.forEach(element => {
                detalleOrdenObject.push({
                    'id_detalle_orden': 'ITEM' + this.makeId(),
                    'id_detalle_requerimiento': element.id_detalle_requerimiento ? element.id_detalle_requerimiento :'',
                    'id_tipo_item': element.id_tipo_item,
                    'id_producto': (element.id_producto ? element.id_producto : ''),
                    'codigo_producto': element.id_tipo_item == 1 ? (element.producto.codigo ? element.producto.codigo : '') : '<small>(No aplica)</small>',
                    'codigo_requerimiento': element.codigo_requerimiento ? element.codigo_requerimiento : '',
                    'codigo_softlink': element.producto.cod_softlink ? element.producto.cod_softlink : '',
                    'part_number': element.producto.part_number ? element.producto.part_number : '',
                    'descripcion': (element.producto.descripcion ? element.producto.descripcion : (element.descripcion != null ? element.descripcion : '')),
                    'descripcion_complementaria': (element.descripcion_complementaria ? element.descripcion_complementaria : ''),
                    'id_unidad_medida': (element.producto.id_unidad_medida ? element.producto.id_unidad_medida : element.id_unidad_medida),
                    'abreviatura_unidad_medida': (element.producto != null && element.producto.unidad_medida != null ? element.producto.unidad_medida.abreviatura : (element.unidad_medida != null ? element.unidad_medida : 'sin und.')),
                    'cantidad_solicitada': (element.cantidad > 0 ? element.cantidad : '<small>(no definido en el requerimiento)</small'),
                    'cantidad_pendiente_atender': (element.cantidad ? ((parseFloat(element.cantidad) - (parseFloat(element.cantidad_atendida_almacen) + parseFloat(element.cantidad_atendida_almacen)))) : 0),
                    'cantidad_atendida_almacen': (element.cantidad_atendida_almacen > 0 ? element.cantidad_atendida_almacen : '0'),
                    'cantidad_atendida_orden': (element.cantidad_atendida_orden > 0 ? element.cantidad_atendida_orden : '0'),
                    'precio_unitario': (element.precio_unitario ? element.precio_unitario : 0),
                    'subtotal': (element.precio_unitario ? element.precio_unitario : 0) * (element.cantidad ? ((parseFloat(element.cantidad) - (parseFloat(element.cantidad_atendida_almacen) + parseFloat(element.cantidad_atendida_almacen)))) : 0),
                    'id_moneda': cabeceraRequerimientoObject.simbolo_moneda,
                    'simbolo_moneda': cabeceraRequerimientoObject.simbolo_moneda,
                    'id_estado': 1
                });
                
                montoNetoOrden+=(element.precio_unitario ? element.precio_unitario : 0) * (element.cantidad ? ((parseFloat(element.cantidad) - (parseFloat(element.cantidad_atendida_almacen) + parseFloat(element.cantidad_atendida_almacen)))) : 0)
                
            });

            montoIgvOrden=montoNetoOrden*0.18;
            montoTotalOrden=montoNetoOrden+montoIgvOrden;



            let cabeceraOrdenObject = {
                'id_orden': provDetReqValue.id_orden,
                'id_tipo_orden': idTipoOrden,
                'descripcion_tipo_orden': idTipoOrden,
                'codigo_orden': '',
                'id_moneda': cabeceraRequerimientoObject.id_moneda,
                'simbolo_moneda': cabeceraRequerimientoObject.simbolo_moneda,
                'tipo_cambio': cabeceraRequerimientoObject.tipo_cambio,
                'id_softlink': '',
                'codigo_softlink': '',
                'id_periodo': cabeceraRequerimientoObject.id_periodo,
                'descripcion_periodo': cabeceraRequerimientoObject.descripcion_periodo,
                'fecha_emision': '', //moment().format("DD-MM-YYYY");
                'id_empresa': cabeceraRequerimientoObject.id_empresa,
                'descripcion_empresa': cabeceraRequerimientoObject.descripcion_empresa,
                'id_sede': cabeceraRequerimientoObject.id_sede,
                'descripcion_sede': cabeceraRequerimientoObject.descripcion_sede,
                'id_proveedor_mgc': provDetReqValue.proveedor_seleccionado_id > 0 ? provDetReqValue.proveedor_seleccionado_id : '',
                'id_proveedor': '',
                'razon_social_proveedor_mgc': provDetReqValue.proveedor_seleccionado != null ? provDetReqValue.proveedor_seleccionado : '',
                'razon_social_proveedor': provDetReqValue.razon_social_proveedor,
                'id_tipo_documento_proveedor': provDetReqValue.id_tipo_documento_proveedor,
                'descripcion_tipo_documento_proveedor': provDetReqValue.descripcion_tipo_documento_proveedor,
                'nro_documento_proveedor': provDetReqValue.nro_documento_proveedor,
                'direccion_fiscal_proveedor': provDetReqValue.direccion_fiscal_proveedor,
                'id_cuenta_proveedor': provDetReqValue.id_cuenta_proveedor,
                'numero_cuenta_proveedor': provDetReqValue.numero_cuenta_proveedor,
                'numero_cuenta_interbancaria_proveedor': provDetReqValue.numero_cuenta_interbancaria_proveedor,
                'simbolo_moneda_cuenta_proveedor': provDetReqValue.simbolo_moneda_cuenta_proveedor,
                'id_moneda_cuenta_proveedor': provDetReqValue.id_moneda_cuenta_proveedor,
                'id_contacto_proveedor': provDetReqValue.id_concato_proveedor,
                'nombre_contacto_proveedor': provDetReqValue.nombre_contacto_proveedor,
                'telefono_contacto_proveedor': provDetReqValue.telefono_contacto_proveedor,
                'cargo_contacto_proveedor': provDetReqValue.cargo_contacto_proveedor,
                'id_rubro_proveedor': '',
                'descripcion_rubro_proveedor': '',
                'id_condicion_compra': '',
                'descripcion_condicion_compra': '',
                'id_condicion_softlink': '',
                'descripcion_condicion_softlink': '',
                'plazo_entrega_dias': 1,
                'requerimiento_vinculado_list': [cabeceraRequerimientoObject],
                'id_tipo_documento': 2,
                'descripcion_tipo_documento': '01 - Factura',
                'direccion_entrega': cabeceraRequerimientoObject.direccion_fiscal_empresa,
                'id_ubigeo_entrega': cabeceraRequerimientoObject.id_ubigeo_empresa,
                'descripcion_ubigeo_entrega': cabeceraRequerimientoObject.descripcion_ubigeo_empresa,
                'id_personal_autorizado_1': '',
                'nombre_personal_autorizado_1': '',
                'id_personal_autorizado_2': '',
                'nombre_personal_autorizado_2': '',
                'es_compra_local': false,
                'observacion': cabeceraRequerimientoObject.observacion,
                'id_estado_orden': '',
                'descripcion_estado_orden': '',
                'monto_neto':montoNetoOrden,
                'monto_igv':montoIgvOrden,
                'monto_icbper':montoIcbperOrden,
                'monto_total':montoTotalOrden,
                'detalle': detalleOrdenObject

            };
            this.ordenArray.push(cabeceraOrdenObject);

        });

        Lobibox.notify('info', {
            title: false,
            size: 'mini',
            rounded: true,
            sound: false,
            delayIndicator: false,
            msg: `Seleccionó el requerimiento ${cabeceraRequerimientoObject.codigo}`
        });

        // console.log(this.ordenArray);
        this.construirPanelListaOrdenes(this.ordenArray);
        // construir panel de encabezado y panel detalle por defecto la primera orden de array
        this.autoSeleccionarOrdenParaMostrar(this.ordenArray);

    }

    construirPanelListaOrdenes(data) {
        // Panel lista ordenes
        document.querySelector("ul[id='contenedor_lista_ordenes']").innerHTML = '';
        data.forEach(element => {
            document.querySelector("ul[id='contenedor_lista_ordenes']").insertAdjacentHTML('beforeend', this.construirCardOrden(element))

        });


    }


    guardarOrdenes() {

    }


    migrarOrdenes() {

    }


    autoSeleccionarOrdenParaMostrar(data) {
        if (this.idOrdenSeleccionada == '' || this.idOrdenSeleccionada == null) {// si NO existe un id, selecciona le primero del array
            if (data.length > 0) {
                this.idOrdenSeleccionada = data[0]['id_orden'];
                this.seleccionarOrden(data[0]['id_orden']);
            }
        } else { // si existe un id, buscar y llamar funciones
            data.forEach(element => {
                if (element.id_orden == this.idOrdenSeleccionada) {
                    this.seleccionarOrden(this.idOrdenSeleccionada);

                }
            });
        }
    }


    construirPanelEncabezadoOrden(data) {

        document.querySelector("form[id='form-orden'] input[name='id_orden']").value = '';
        document.querySelector("form[id='form-orden'] input[name='tipo_cambio']").value = data.tipo_cambio ? data.tipo_cambio : '';
        document.querySelector("form[id='form-orden'] span[name='tipo_cambio']").textContent = data.tipo_cambio ? data.tipo_cambio : '';
        document.querySelector("form[id='form-orden'] select[name='id_tipo_orden']").value = data.id_tipo_orden ? data.id_tipo_orden : '';
        document.querySelector("form[id='form-orden'] select[name='id_moneda']").value = data.id_moneda ? data.id_moneda : '';
        const SelectorSede = document.querySelector("form[id='form-orden'] select[name='id_sede']");
        SelectorSede.value = data.id_sede ? data.id_sede : '';
        var id_empresa = SelectorSede.options[SelectorSede.selectedIndex].getAttribute('data-id-empresa');
        var id_ubigeo = SelectorSede.options[SelectorSede.selectedIndex].getAttribute('data-id-ubigeo');
        var ubigeo_descripcion = SelectorSede.options[SelectorSede.selectedIndex].getAttribute('data-ubigeo-descripcion');
        var direccion = SelectorSede.options[SelectorSede.selectedIndex].getAttribute('data-direccion');
        this.changeLogoEmprsa(id_empresa);
        this.llenarUbigeo(direccion, id_ubigeo, ubigeo_descripcion);

        if (data.id_proveedor > 0) {
            document.querySelector("form[id='form-orden'] p[name='direccion_proveedor']").textContent = data.direccion_fiscal_proveedor ? data.direccion_fiscal_proveedor : '';
            document.querySelector("form[id='form-orden'] select[name='id_proveedor']").value = data.id_proveedor ? data.id_proveedor : '';
            this.llenarDatosCabeceraSeccionProveedor(data.id_proveedor, data.id_cuenta_proveedor);
        }

        document.querySelector("form[id='form-orden'] p[name='requerimiento_vinculados']").textContent = data.requerimiento_vinculado_list.length > 0 ? data.requerimiento_vinculado_list.map(function (e) { return e.codigo }).join(", ") : '';

        $('.selectpicker').selectpicker('refresh')

    }

    construirPanelDetalleOrden(data) {
        let payload = [];
        let CantidadItemsPendientesPorMapear = 0;
        let cantidadItemsAgregados = 0;
        data.forEach(element => {
            if (element.id_tipo_item == 1 && (element.id_producto == null || element.id_producto == '')) {
                CantidadItemsPendientesPorMapear++;
            } else {

                cantidadItemsAgregados++;
                payload.push(element);
            }


        });

        if (cantidadItemsAgregados > 0) {
            Lobibox.notify('success', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: `Se agregó ${cantidadItemsAgregados} ítem(s)`
            });
        }
        if (CantidadItemsPendientesPorMapear > 0) {
            Lobibox.notify('warning', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: `Aun tiene ${CantidadItemsPendientesPorMapear} ítem(s) por mapear`
            });
        }
        this.agregarItemADetalleOrden(payload);
    }

    agregarItemADetalleOrden(payload) {
        payload.forEach(element => {
            document.querySelector("tbody[name='body_detalle_orden']").insertAdjacentHTML('beforeend', `<tr style="text-align:center;" class="${element.id_estado == 7 ? 'danger textRedStrikeHover' : ''}">
            <td class="text-center">${element.codigo_requerimiento} <input type="hidden"  name="id_detalle_orden[]" value="${element.id_detalle_orden}"> <input type="hidden"  class="idEstado" name="idEstado[]"> <input type="hidden"  name="idDetalleRequerimiento[]" value="${element.id_detalle_requerimiento ? element.id_detalle_requerimiento : ''}">  <input type="hidden"  name="idTipoItem[]" value="1"></td>
            <td class="text-center">${element.codigo_producto} </td>
            <td class="text-center">${element.codigo_softlink} </td>
            <td class="text-center">${element.part_number} <input type="hidden"  name="idProducto[]" value="${element.id_producto} "></td>
            <td class="text-left">${element.descripcion} 
                <textarea class="form-control handleChangeUpdateDescripcionServicio" placeholder="Descripción de servicio" style="display:${element.id_tipo_item == 1 ? 'none' : 'block'}; height: 5rem; overflow-y: scroll;"  name="descripcion[]">${element.descripcion}</textarea>
                <textarea class="form-control handleChangeUpdateDescripcionComplementaria" style="display:${element.id_tipo_item == 2 ? 'none' : 'block'}; height: 5rem; overflow-y: scroll;" name="descripcionComplementaria[]" placeholder="Descripción complementaria" style="width:100%;height: 60px;" >${element.descripcion_complementaria}</textarea>
            </td>
            <td><p name="unidad[]" class="form-control-static unidadMedida" data-valor="${element.id_unidad_medida}">${element.abreviatura_unidad_medida}</p>
            <input type="hidden"  name="unidad[]" value="${element.id_unidad_medida}">

            </td>
            <td>${element.cantidad_pendiente_atender}</td>
            <td>${element.cantidad_atendida_almacen}</td>
            <td>${element.cantidad_atendida_orden}</td>
            <td>
                <input class="form-control cantidad_a_comprar input-sm text-right handleBurUpdateSubtotal handleChangeUpdateCantidad"  data-id-tipo-item="1" type="number" min="0" name="cantidadAComprarRequerida[]"  placeholder="" value="${element.cantidad_pendiente_atender}" >
            </td>
            <td>
                <div class="input-group">
                    <div class="input-group-addon" style="background:lightgray;" name="simboloMoneda">${element.simbolo_moneda}</div>
                    <input class="form-control precio input-sm text-right handleBurUpdateSubtotal handleChangeUpdateCantidad" data-id-tipo-item="${element.id_tipo_item}" type="number" min="0" name="precioUnitario[]"  placeholder="" value="${$.number(element.precio_unitario,2,'.',',')}" >
                </div>
            </td>
            <td style="text-align:right;"><span class="moneda" name="simboloMoneda">${element.simbolo_moneda}</span><span class="subtotal" name="subtotal[]">${$.number(element.subtotal,2,'.',',')??'0.00'}</span></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm handleClickEliminarItemOrden" name="btnEliminarItemOrden" title="Eliminar Item">
                <i class="fas fa-trash fa-sm"></i>
                </button>
            </td>
        </tr>`);
        });


        this.calcularTotales();
    }

    calcularTotales(){
        let simboloMonedaOrden='';
        let montoNetoOrden = 0;
        let montoIgvOrden = 0;
        let montoIcbperOrden = 0;
        let montoTotalOrden = 0;
        (this.ordenArray).forEach((element)=>{
            if(element.id_orden==this.idOrdenSeleccionada){
                simboloMonedaOrden = element.simbolo_moneda;
                montoNetoOrden = element.monto_neto;
                montoIgvOrden= element.monto_igv;
                montoIcbperOrden= element.monto_icbper;
                montoTotalOrden= element.monto_total;
            }
        });

        const simboloMonedaSpan = document.querySelectorAll("table[name='listaDetalleOrden'] tfoot span[name='simboloMoneda']");
        simboloMonedaSpan.forEach(element => {
            element.textContent=simboloMonedaOrden;
        });
        document.querySelector("table[name='listaDetalleOrden'] tfoot label[name='montoNeto']").textContent=$.number(montoNetoOrden,2,'.',',');
        if(montoIgvOrden>0){
            document.querySelector("table[name='listaDetalleOrden'] tfoot input[name='incluye_igv']").checked =true;
        }else{
            document.querySelector("table[name='listaDetalleOrden'] tfoot input[name='incluye_igv']").checked =false;
        }
        if(montoIcbperOrden>0){
            document.querySelector("table[name='listaDetalleOrden'] tfoot input[name='incluye_icbper']").checked =true;
        }else{
            document.querySelector("table[name='listaDetalleOrden'] tfoot input[name='incluye_icbper']").checked =false;
        }
        document.querySelector("table[name='listaDetalleOrden'] tfoot label[name='igv']").textContent=$.number(montoIgvOrden,2,'.',',');
        document.querySelector("table[name='listaDetalleOrden'] tfoot label[name='icbper']").textContent=$.number(montoIcbperOrden,2,'.',',');
        document.querySelector("table[name='listaDetalleOrden'] tfoot label[name='montoTotal']").textContent=$.number(montoTotalOrden,2,'.',',');

    }

    eliminarItemOrden(obj) {
        Swal.fire({
            title: 'Esta seguro?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'cancelar',
            confirmButtonText: 'Si, eliminar'
    
        }).then((result) => {
            if (result.isConfirmed) {
                let tr = obj.closest("tr");
                const identificador =tr.querySelector("input[name='id_detalle_orden[]']").value;
                var regExp = /[a-zA-Z]/g; //expresión regular
                
                console.log(this.ordenArray);
                
                if(this.ordenArray.length ==0){
                    tr.remove();

                }else{
                    if (regExp.test(identificador) == true) {//si contiene el id letras es un autogenerado 
                        const cantidadItemEliminados=this.eliminarItemDeOrdenArray(identificador);
                        if(cantidadItemEliminados>0){
                            tr.remove();
                            // this.calcularMontosTotales(); //TODO: calcular monto totales despues de anular un item
                        }
                        console.log(this.ordenArray);
        
                    } else {
                        const cantidadItemEliminados= this.eliminarItemDeOrdenArray(identificador);
                        if(cantidadItemEliminados>0){
                            tr.remove();
                            
                        }
                        console.log(this.ordenArray);
                        // tr.querySelector("input[class~='idEstado']").value = 7;
                        // tr.classList.add("danger", "textRedStrikeHover");
                        // tr.querySelector("button[name='btnOpenModalEliminarItemOrden']").setAttribute("disabled", true);
                        // this.calcularMontosTotales(); // considere las filas anuladas
                    }
                }

    
                Lobibox.notify('success', {
                    title: false,
                    size: 'mini',
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: 'El item fue eliminado'
                });
            }
        })
    }

    eliminarItemDeOrdenArray(id){
        let tamOriginalArrayDetalle=0;
        let tamFiltradoArrayDetalle=0;
        this.ordenArray.forEach((ordValue,ordKey) => {
            if((ordValue.detalle).length>0){
                
                ordValue.detalle.forEach((detValue,DetKey) => {
                    if(detValue.id_detalle_orden==id){
                        tamOriginalArrayDetalle= ordValue.detalle.length;
                    }
                });
                this.ordenArray[ordKey]['detalle'] = ordValue.detalle.filter(item => item.id_detalle_orden != id);
                tamFiltradoArrayDetalle = this.ordenArray[ordKey]['detalle'].length;
            }
        });

    
        return (parseInt(tamOriginalArrayDetalle)-parseInt(tamFiltradoArrayDetalle));

    }
    

    abrirCatalogoProductos() {
        document.querySelector("div[id='modal-catalogo-items'] h3[class='modal-title']").textContent = "Lista de productos";
        $('#modal-catalogo-items').modal({
            show: true,
            backdrop: 'true',
            keyboard: true

        });
        this.limpiarTabla('listaItems');
        document.querySelector("div[id='modal-catalogo-items'] button[id='btn-crear-producto']").classList.add("oculto")
        this.listarCatalogoProductos();
    }

    listarCatalogoProductos() {
        $tablaListaCatalogoProductos = $('#listaCatalogoProductos').DataTable({
            'dom': 'frtip',
            'language': vardataTables[0],
            'order': [[5, 'asc']],
            'serverSide': true,
            'processing': false,
            'destroy': true,
            'ajax': {
                'url': 'mostrar-catalogo-productos',
                'type': 'POST',
                beforeSend: data => {

                    $("#listaCatalogoProductos").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                }

            },
            'columns': [
                { 'data': 'codigo', 'name': 'alm_prod.codigo' },
                { 'data': 'cod_softlink', 'name': 'alm_prod.cod_softlink' },
                { 'data': 'part_number', 'name': 'alm_prod.part_number' },
                { 'data': 'descripcion', 'name': 'alm_prod.descripcion' },
                { 'data': 'abreviatura_unidad_medida', 'name': 'alm_und_medida.abreviatura' },
                { 'data': 'id_producto', 'name': 'alm_prod.id_producto', "searchable": false }

            ],
            'initComplete': function () {
                //Boton de busqueda
                const $filter = $('#listaCatalogoProductos_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscar" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
                $input.off();
                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscar').trigger('click');
                    }
                });
                $('#btnBuscar').on('click', (e) => {
                    $tablaListaCatalogoProductos.search($input.val()).draw();
                })
                //Fin boton de busqueda
            },
            "drawCallback": function (settings) {
                //Botón de búsqueda
                $('#listaCatalogoProductos_filter input').prop('disabled', false);
                $('#btnBuscar').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#listaCatalogoProductos_filter input').trigger('focus');
                //fin botón búsqueda
                if ($tablaListaCatalogoProductos.rows().data().length == 0) {
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
                $('#listaCatalogoProductos_filter input').prop('disabled', false);
                $('#btnBuscar').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#listaCatalogoProductos_filter input').trigger('focus');
                //fin botón búsqueda
                $("#listaCatalogoProductos").LoadingOverlay("hide", true);
            },
            'columnDefs': [
                { 'aTargets': [0], 'className': "text-center", 'sWidth': '5%' },
                { 'aTargets': [1], 'className': "text-center", 'sWidth': '5%' },
                { 'aTargets': [2], 'className': "text-center", 'sWidth': '5%' },
                { 'aTargets': [3], 'className': "text-left", 'sWidth': '40%' },
                { 'aTargets': [4], 'className': "text-center", 'sWidth': '5%' },
                { 'aTargets': [5], 'className': "text-center", 'sWidth': '20%' },
                {
                    'render':
                        function (data, type, row) {

                            return `<button class="btn btn-success btn-xs handleClickSelectProducto"
                                data-id-producto="${row.id_producto}"
                                data-codigo="${row.codigo}"
                                data-codigo-softlink="${row.cod_softlink}"
                                data-part-number="${row.part_number}"
                                data-descripcion="${row.descripcion}"
                                data-unidad-medida="${row.abreviatura_unidad_medida}"
                                data-id-unidad-medida="${row.id_unidad_medida}"
                                >Agregar producto</button>`;

                        }, targets: 5, className: "text-center", sWidth: '8%'
                }
            ]

        });
    }

    selectProducto(obj) {

        this.agregarItemADetalleOrden([{
            'id_detalle_orden': 'ITEMPRO' + this.makeId(),
            'id_detalle_requerimiento':'',
            'id_tipo_item': 1,
            'id_producto': obj.dataset.idProducto,
            'codigo_producto': obj.dataset.codigo,
            'codigo_requerimiento': '<small>(Sin vínculo)</small>',
            'codigo_softlink': obj.dataset.codigoSoftlink,
            'part_number': obj.dataset.partNumber,
            'descripcion': obj.dataset.descripcion,
            'descripcion_complementaria': '',
            'id_unidad_medida': obj.dataset.idUnidadMedida,
            'abreviatura_unidad_medida': obj.dataset.unidadMedida,
            'cantidad_solicitada': '<small>(No aplica)</small>',
            'cantidad_pendiente_atender': 1,
            'cantidad_atendida_almacen': 0,
            'cantidad_atendida_orden': 0,
            'precio_unitario': 0,
            'subtotal': 0,
            'id_moneda': document.querySelector("select[name='id_moneda']").value,
            'simbolo_moneda': document.querySelector("select[name='id_moneda']").options[document.querySelector("select[name='id_moneda']").selectedIndex].dataset.simboloMoneda,
            'id_estado': 1

        }]);
        $('#modal-catalogo-items').modal('hide');

    }


    agregarServicio() {
        this.agregarItemADetalleOrden([
            {
                'id_detalle_orden': 'ITEMSER' + this.makeId(),
                'id_detalle_requerimiento':'',
                'id_tipo_item': 2,
                'id_producto': '',
                'codigo_producto': '<small>(No aplica)</small>',
                'codigo_requerimiento': '<small>(Sin vínculo)</small>',
                'codigo_softlink': '',
                'part_number': '',
                'descripcion': '',
                'descripcion_complementaria': '',
                'id_unidad_medida': 17,
                'abreviatura_unidad_medida': 'SERV',
                'cantidad_solicitada': '<small>(No aplica)</small>',
                'cantidad_pendiente_atender': 1,
                'cantidad_atendida_almacen': '<small>(No aplica)</small>',
                'cantidad_atendida_orden': 0,
                'precio_unitario': 0,
                'subtotal': 0,
                'id_moneda': document.querySelector("select[name='id_moneda']").value,
                'simbolo_moneda': document.querySelector("select[name='id_moneda']").options[document.querySelector("select[name='id_moneda']").selectedIndex].dataset.simboloMoneda,
                'id_estado': 1
            }
        ])
    }

    updateTipoOrden(obj){
        console.log(obj.value);
    }

    updatePeriodo(obj){
        console.log(obj.value);
    }

    updateMoneda(obj){
        console.log(obj.value);
    }

    updateFechaEmision(obj){
        console.log(obj.value);
    }
  
    updateSede(obj){
        var id_sede = obj.value;
        var id_empresa = obj.options[obj.selectedIndex].getAttribute('data-id-empresa');
        var id_ubigeo = obj.options[obj.selectedIndex].getAttribute('data-id-ubigeo');
        var ubigeo_descripcion = obj.options[obj.selectedIndex].getAttribute('data-ubigeo-descripcion');
        var direccion = obj.options[obj.selectedIndex].getAttribute('data-direccion');

        console.log(id_sede,id_empresa,id_ubigeo,ubigeo_descripcion,direccion);
    }

    updateProveedor(obj){
        var id_proveedor = obj.value;
        var id_contribuyente = obj.options[obj.selectedIndex].getAttribute('data-id-contribuyente');
        var razon_social = obj.options[obj.selectedIndex].getAttribute('data-razon-social');
        var numero_documento = obj.options[obj.selectedIndex].getAttribute('data-numero-documento');
        console.log(id_proveedor,id_contribuyente,razon_social,numero_documento);
    }

    updateCuentaBancariaProveedor(obj){
        console.log(obj.value);
    }
    
    updateContactoProveedor(obj){
        let id_contacto_proveedor= obj.value;
        let telefono_contacto_proveedor= obj.options[obj.selectedIndex].dataset.telefono;
        console.log(id_contacto_proveedor,telefono_contacto_proveedor);
    }

    updateRubroProveedor(obj){
        console.log(obj.value);
    }

    updateFormaPago(obj){
        let id_condicion_softlink = obj.value; // id condicion softlink
        let text_condicion_softlink = obj.options[obj.selectedIndex].text;
        let dias_condicion_softlink = obj.options[obj.selectedIndex].dataset.dias; // dias condicion softlink
        let id_condicion=1; // id condicion agile
        let plazo_dias=0; // plazo dias agile

        if (dias_condicion_softlink > 0) {
            id_condicion = 2; 
            plazo_dias = dias_condicion_softlink;
        } else {
            id_condicion = 1;
            plazo_dias = 0;
        }

        console.log(id_condicion_softlink,text_condicion_softlink,dias_condicion_softlink,id_condicion,plazo_dias);
    }

    updatePlazoEntrega(obj){
        console.log(obj.value);
    }

    updateTipoDocumento(obj){
        let id_tipo_documento= obj.value;
        let text_tipo_documento= obj.options[obj.selectedIndex].text;;
        console.log(id_tipo_documento,text_tipo_documento);
    }
    
    updateDireccionEntrega(obj){
        console.log(obj.value);
    }

    updateUbigeoEntrega(obj){
        console.log(obj.value);
    }

    updateCompraLocal(obj){
        console.log(obj.checked);
    }
    
    updatePersonalAutorizado1(obj){
        console.log(obj.value);
    }

    updatePersonalAutorizado2(obj){
        console.log(obj.value);
    }

    updateObservacion(obj){
        console.log(obj.value);
    }

    updateDescripcionComplementaria(obj){
        const tr = obj.closest("tr");
        const identificador =tr.querySelector("input[name='id_detalle_orden[]']").value;

        console.log(identificador, obj.value);
    }

    updateDescripcionServicio(obj){
        const tr = obj.closest("tr");
        const identificador =tr.querySelector("input[name='id_detalle_orden[]']").value;

        console.log(identificador, obj.value);
    }

    updateCantidad(obj){
        const tr = obj.closest("tr");
        const identificador =tr.querySelector("input[name='id_detalle_orden[]']").value;

        console.log(identificador, obj.value);
    }
    
    updatePrecio(obj){
        const tr = obj.closest("tr");
        const identificador =tr.querySelector("input[name='id_detalle_orden[]']").value;

        console.log(identificador, obj.value);
    }

    updateIncluyeIGV(obj){
        console.log(obj.checked);
    }

    updateIncluyeICBPER(obj){
        console.log(obj.checked);
    }
}