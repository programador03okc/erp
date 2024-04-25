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

        $('#form-orden').on("click", "button.crearNuevaOrden", (e) => {
            this.crearNuevaOrden();
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
    }



    construirCardOrden() {
        const cardOrden = `
            <li>
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="display:flex; flex-direction:row; gap:0.5rem;">
                        <h5>Cód. orden: <span class="label label-default" title="Código de orden"><span name="tituloDocumentoCodigoOrden[]">OC-240240</span></span></h5>
                        <h5>Cód. Softlink: <span class="label label-default" title="Código de Softlink"><span name="tituloDocumentoCodigoSoftlink[]">00100189</span></span></h5>
                    </div>
                    <div class="panel-body">
                        <ul class="list-inline">
                            <li>
                                <dl>
                                    <dt>Empresa:</dt>
                                    <dd>OK COMPUTER EIRL</dd>
                                    <dt>Sede:</dt>
                                    <dd>Lima</dd>
                                    <dt>Proveedor:</dt>
                                    <dd>MAXIMA EIRL</dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>Fecha emsión:</dt>
                                    <dd>##/##/####</dd>
                                    <dt>Importe:</dt>
                                    <dd>S/.1000.00</dd>
                                    <dt>Cta Proveedor:</dt>
                                    <dd>55234242-2432-10</dd>
                            </li>
                            <li>

                            </li>
                        </ul>
                        <div class="text-left">
                            <button type="button" class="btn btn-xs btn-success" id="btnSeleccionarOrden" title="Seleccionar"><i class="fas fa-check"></i></button>
                            <button type="button" class="btn btn-xs btn-default" id="btnImprimirOrden" title="Imprimir"><i class="fas fa-print"></i></button>
                            <button type="button" class="btn btn-xs btn-default" id="btnEditarOrden" title="Editar"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-xs btn-default" id="btnAnularOrden" title="Anular"><i class="fas fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-default" id="btnMigrarOrden" title="Migrar a Softlink"><i class="fas fa-file-export"></i></button>
                        </div>
                    </div>
                </div>
            </li>
        `;
        return cardOrden;
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

                // 

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

                document.querySelector("ul[id='contenedor_lista_ordenes']").insertAdjacentHTML('beforeend', this.construirCardOrden())
                // $('select[name="id_sede[]"]').selectpicker();

            }
        });

    }


    llenarDatosCabeceraSeccionProveedor(idProveedor) {
        this.ordenCtrl.obtenerDataProveedor(idProveedor).then((res) => {
            document.querySelector("p[name='direccion_proveedor[]']").textContent = res.contribuyente != null ? res.contribuyente.direccion_fiscal : '';
            this.llenarDatosCabeceraCuentaBancariaProveedor(res.cuenta_contribuyente);
            this.llenarDatosCabeceraConcactoProveedor(res.contacto_contribuyente);
            // document.querySelector("select[name='contacto_proveedor']").textContent = '';
            // document.querySelector("p[name='telefono_contacto']").textContent = '';
            // document.querySelector("select[name='rubro_proveedor']").textContent = '';

        });

    }

    llenarDatosCabeceraCuentaBancariaProveedor(data, idCuentaSelected = null) {
        let selectElement = document.querySelector("select[name='cuenta_bancaria_proveedor[]']");

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
        let selectElement = document.querySelector("select[name='id_contacto_proveedor[]']");

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
        if (document.querySelector("select[name='id_contacto_proveedor[]']").length > 0) {
            document.querySelector("p[name='telefono_contacto[]']").textContent = selectElement.options[selectElement.selectedIndex].dataset.telefono;
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
        let selectFormaPago = document.querySelector("select[name='forma_pago']");
        let dias_condicion_softlink = selectFormaPago.options[selectFormaPago.selectedIndex].dataset.dias;

        if (dias_condicion_softlink > 0) {
            document.getElementsByName('id_condicion')[0].value = 2;
            document.getElementsByName('plazo_dias')[0].value = dias_condicion_softlink;
        } else {
            document.getElementsByName('id_condicion')[0].value = 1;
            document.getElementsByName('plazo_dias')[0].value = 0;
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
        document.querySelector("input[name='direccion_entrega[]']").value = direccion;
        document.querySelector("select[name='id_ubigeo_destino[]']").value = id_ubigeo;
        $("select[name='id_ubigeo_destino[]']").trigger("change");
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

        let continuar=true;
        this.ordenArray.forEach(arr => {
            (arr.requerimiento_vinculado_list).forEach(req => {
                if(req.id_requerimiento==idRequerimiento){
                    continuar=false;
                }
            });
        });

        if(continuar){
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

        }else{
            Swal.fire(
                '',
                'El requerimiento ya fue agregado',
                'warning'
            ); 
        }

    }


    construirOrdenConRequerimiento(cabeceraRequerimiento, detalleRequerimiento, TipoDocumento) {
        let idTipoOrden = "";
        let cabeceraRequerimientoObject={};
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

        cabeceraRequerimientoObject=[{
            'id_requerimiento':cabeceraRequerimiento.id_requerimiento,
            'codigo':cabeceraRequerimiento.codigo,
            'id_periodo':cabeceraRequerimiento.id_periodo,
            'descripcion_periodo':cabeceraRequerimiento.periodo.descripcion,
            'id_moneda':cabeceraRequerimiento.id_moneda,
            'simbolo_moneda':cabeceraRequerimiento.moneda.simbolo,
            'tipo_cambio':cabeceraRequerimiento.tipo_cambio,
            'id_empresa':cabeceraRequerimiento.id_empresa,
            'descripcion_empresa':cabeceraRequerimiento.empresa.contribuyente.razon_social,
            'id_sede':cabeceraRequerimiento.id_sede,
            'descripcion_sede':cabeceraRequerimiento.sede.codigo,
            'direccion_fiscal_empresa':cabeceraRequerimiento.empresa.contribuyente.direccion_fiscal,
            'id_ubigeo_empresa':cabeceraRequerimiento.empresa.contribuyente.ubigeo,
            'descripcion_ubigeo_empresa':cabeceraRequerimiento.empresa.contribuyente.ubigeo_completo,
            'observacion':cabeceraRequerimiento.observacion
        }];

        let proveedorDetReq=[];
        detalleRequerimiento.forEach(detReq => {
            let registradoEnProveedorDetReq=0;
            if(detReq.proveedor_seleccionado_id >0 || detReq.proveedor_seleccionado !=null){
                proveedorDetReq.forEach((provDetReqValue,ProvDetReqIndex) => {
                   if(provDetReqValue.proveedor_seleccionado_id ==detReq.proveedor_seleccionado_id || (provDetReqValue.proveedor_seleccionado !=null && provDetReqValue.proveedor_seleccionado ==detReq.proveedor_seleccionado)){
                        registradoEnProveedorDetReq++;
                    } 
                });
                if(registradoEnProveedorDetReq==0){
                    
                    proveedorDetReq.push(
                        {
                            'detalle_requerimiento_id_list':[detReq.id_detalle_requerimiento],
                            'proveedor_seleccionado_id':detReq.proveedor_seleccionado_id, //mgc
                            'proveedor_seleccionado':detReq.proveedor_seleccionado, //mgc
                            'proveedor_agile_seleccionado_direccion_fiscal':detReq.proveedor_agile_seleccionado_direccion_fiscal,
                            'proveedor_agile_seleccionado_id_contribuyente':detReq.proveedor_agile_seleccionado_id_contribuyente,
                            'proveedor_agile_seleccionado_id_proveedor':detReq.proveedor_agile_seleccionado_id_proveedor,
                            'proveedor_agile_seleccionado_razon_social':detReq.proveedor_agile_seleccionado_razon_social,
                            'proveedor_agile_seleccionado_tipo_documento':detReq.proveedor_agile_seleccionado_tipo_documento,
                            'proveedor_agile_seleccionado_numero_documento':detReq.proveedor_agile_seleccionado_numero_documento,
                            'proveedor_agile_seleccionado_id_cuenta_bancaria':detReq.proveedor_agile_seleccionado_id_cuenta_bancaria,
                            'proveedor_agile_seleccionado_numero_cuenta_bancaria':detReq.proveedor_agile_seleccionado_numero_cuenta_bancaria,
                            'proveedor_agile_seleccionado_numero_cuenta_interbancaria':detReq.proveedor_agile_seleccionado_numero_cuenta_interbancaria,
                            'proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria':detReq.proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria,
                        }
                    );
                }else{
                    proveedorDetReq.forEach((provDetReqValue,ProvDetReqIndex) => {
                       if(provDetReqValue.proveedor_seleccionado_id ==detReq.proveedor_seleccionado_id || provDetReqValue.proveedor_seleccionado == detReq.proveedor_seleccionado ){
                            proveedorDetReq[ProvDetReqIndex]['detalle_requerimiento_id_list'].push(detReq.id_detalle_requerimiento);

                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado_id']==null && detReq.proveedor_seleccionado_id>0){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado_id'] =detReq.proveedor_seleccionado_id;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado']==null && detReq.proveedor_seleccionado !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_seleccionado'] =detReq.proveedor_seleccionado;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_direccion_fiscal']==null && detReq.proveedor_agile_seleccionado_direccion_fiscal !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_direccion_fiscal'] =detReq.proveedor_agile_seleccionado_direccion_fiscal;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_contribuyente']==null && detReq.proveedor_agile_seleccionado_id_contribuyente !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_contribuyente'] =detReq.proveedor_agile_seleccionado_id_contribuyente;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_proveedor']==null && detReq.proveedor_agile_seleccionado_id_proveedor !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_proveedor'] =detReq.proveedor_agile_seleccionado_id_proveedor;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_razon_social']==null && detReq.proveedor_agile_seleccionado_razon_social !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_razon_social'] =detReq.proveedor_agile_seleccionado_razon_social;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_tipo_documento']==null && detReq.proveedor_agile_seleccionado_tipo_documento !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_tipo_documento'] =detReq.proveedor_agile_seleccionado_tipo_documento;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_documento']==null && detReq.proveedor_agile_seleccionado_numero_documento !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_documento'] =detReq.proveedor_agile_seleccionado_numero_documento;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_cuenta_bancaria']==null && detReq.proveedor_agile_seleccionado_id_cuenta_bancaria !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_cuenta_bancaria'] =detReq.proveedor_agile_seleccionado_id_cuenta_bancaria;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_cuenta_bancaria']==null && detReq.proveedor_agile_seleccionado_numero_cuenta_bancaria !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_cuenta_bancaria'] =detReq.proveedor_agile_seleccionado_numero_cuenta_bancaria;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_cuenta_interbancaria']==null && detReq.proveedor_agile_seleccionado_numero_cuenta_interbancaria !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_numero_cuenta_interbancaria'] =detReq.proveedor_agile_seleccionado_numero_cuenta_interbancaria;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_moneda_cuenta_bancaria']==null && detReq.proveedor_agile_seleccionado_id_moneda_cuenta_bancaria !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_id_moneda_cuenta_bancaria'] =detReq.proveedor_agile_seleccionado_id_moneda_cuenta_bancaria;
                            }
                            if(proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria']==null && detReq.proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria !=null){
                                proveedorDetReq[ProvDetReqIndex]['proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria'] =detReq.proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria;
                            }
                       } 
                    });

                }
            }else{
                proveedorDetReq.push(
                    {'detalle_requerimiento_id_list':[detReq.id_detalle_requerimiento],
                    'proveedor_seleccionado_id':null,
                    'proveedor_seleccionado':null
                    });
             }
        });
        
        console.log(proveedorDetReq);
        
        // crear un array de objetos de ordenes ( el total por proveedor)
        proveedorDetReq.forEach(provDetReqValue => {
        
           let cabeceraOrdenObject = {
                'id_tipo_orden': idTipoOrden,
                'descripcion_tipo_orden': idTipoOrden,
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
                'id_proveedor': provDetReqValue.proveedor_agile_seleccionado_id >0 ?provDetReqValue.proveedor_agile_seleccionado_id:'',
                'ruc_proveedor': provDetReqValue.proveedor_agile_seleccionado_numero_documento !=null ?provDetReqValue.proveedor_agile_seleccionado_numero_documento:'',
                'razon_social_proveedor': provDetReqValue.proveedor_agile_seleccionado_razon_social !=null ?provDetReqValue.proveedor_agile_seleccionado_razon_social:(provDetReqValue.proveedor_seleccionado=!null?provDetReqValue.proveedor_seleccionado:'') ,
                'direccion_fiscal_proveedor': provDetReqValue.proveedor_agile_seleccionado_direccion_fiscal !=null?provDetReqValue.proveedor_agile_seleccionado_direccion_fiscal !=null:'',
                'id_cuenta_proveedor': provDetReqValue.proveedor_agile_seleccionado_id_cuenta_bancaria !=null?provDetReqValue.proveedor_agile_seleccionado_id_cuenta_bancaria !=null:'',
                'numero_cuenta_proveedor': provDetReqValue.proveedor_agile_seleccionado_numero_cuenta_bancaria !=null?provDetReqValue.proveedor_agile_seleccionado_numero_cuenta_bancaria !=null:'',
                'simbolo_moneda_cuenta_proveedor': provDetReqValue.proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria !=null?provDetReqValue.proveedor_agile_seleccionado_simbolo_moneda_cuenta_bancaria !=null:'',
                'id_moneda_cuenta_proveedor': provDetReqValue.proveedor_agile_seleccionado_id_moneda_cuenta_bancaria !=null?provDetReqValue.proveedor_agile_seleccionado_id_moneda_cuenta_bancaria !=null:'',
                'id_concato_proveedor': '',
                'nombre_concato_proveedor': '',
                'telefono_concato_proveedor': '',
                'id_rubro_proveedor': '',
                'descripcion_rubro_proveedor': '',

                'id_condicion_compra': '',
                'descripcion_condicion_compra': '',
                'id_condicion_softlink': '',
                'descripcion_condicion_softlink': '',
                'plazo_entrega_dias': '',
                'requerimiento_vinculado_list': cabeceraRequerimientoObject,
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
    
            };

            this.ordenArray.push(cabeceraOrdenObject);
        });
        
         console.log(this.ordenArray);

     
        this.construirPanelListaDeOrdenes(cabeceraRequerimiento);
        this.construirPanelEncabezadoOrden(cabeceraRequerimiento);
        this.construirPanelDetalleOrden(detalleRequerimiento);



    }


    construirPanelListaDeOrdenes(data) {
        // console.log(data);
    }
    construirPanelEncabezadoOrden(data) {
        // console.log(data);
        
    }
    construirPanelDetalleOrden(data) {
        // console.log(data);

    }

    // obtenerRequerimiento(reqTrueList, tipoOrden) { // used
    //     this.limpiarTabla('listaDetalleOrden');
    //     let idTipoItem = 0;
    //     let idTipoOrden = 0;
    //     let ambosTipos=false;
    //     if (tipoOrden == 'COMPRA') {
    //         idTipoItem = 1; // producto
    //         idTipoOrden = 2; // compra
    //     } else if (tipoOrden == 'SERVICIO') {
    //         idTipoItem = 2; // servicio
    //         idTipoOrden = 3; // servicio
    //     }else if(tipoOrden == 'COMPRA_SERVICIO'){
    //         ambosTipos=true;
    //     }

    //     detalleOrdenList = [];
    //     $.ajax({
    //         type: 'POST',
    //         url: 'requerimiento-detallado',
    //         data: { 'requerimientoList': reqTrueList },
    //         dataType: 'JSON',
    //         success: (response) => {
    //             // console.log(response);
    //             response.forEach(req => {
    //                 req.detalle.forEach(det => {
    //                     if ((![28, 5, 7].includes(det.estado)) && (det.id_tipo_item == idTipoItem || ambosTipos==true )) {
    //                         let cantidad_atendido_almacen = 0;
    //                         if (det.reserva.length > 0) {
    //                             (det.reserva).forEach(reserva => {
    //                                 if (reserva.estado == 1) {
    //                                     cantidad_atendido_almacen += parseFloat(reserva.stock_comprometido);
    //                                 }
    //                             });
    //                         }
    //                         let cantidad_atendido_orden = 0;
    //                         if (det.ordenes_compra.length > 0) {
    //                             (det.ordenes_compra).forEach(orden => {
    //                                 cantidad_atendido_orden += parseFloat(orden.cantidad);
    //                             });
    //                         }
    //                         let cantidadAAtender = (parseFloat(det.cantidad) - cantidad_atendido_almacen - cantidad_atendido_orden);
    //                         if (det.tiene_transformacion == false) {
    //                             detalleOrdenList.push(
    //                                 {
    //                                     'id': det.id,
    //                                     'id_detalle_requerimiento': det.id_detalle_requerimiento,
    //                                     'id_producto': det.id_producto,
    //                                     'id_tipo_item': det.id_tipo_item,
    //                                     'id_requerimiento': det.id_requerimiento,
    //                                     'codigo_requerimiento': req.codigo,
    //                                     'id_moneda': req.id_moneda,
    //                                     'cantidad': det.cantidad,
    //                                     'cantidad_a_comprar': !(cantidadAAtender >= 0) ? '' : cantidadAAtender,
    //                                     'cantidad_atendido_almacen': cantidad_atendido_almacen,
    //                                     'cantidad_atendido_orden': cantidad_atendido_orden,
    //                                     'descripcion_producto': det.producto != null ? det.producto.descripcion : '',
    //                                     'codigo_producto': det.producto != null ? det.producto.codigo : '',
    //                                     'part_number': det.producto != null ? det.producto.part_number : '',
    //                                     'codigo_softlink': det.producto != null ? det.producto.cod_softlink : '',
    //                                     'descripcion': det.descripcion,
    //                                     'estado': det.estado.id_estado_doc,
    //                                     'fecha_registro': det.fecha_registro,
    //                                     'id_unidad_medida': det.producto != null ? det.producto.id_unidad_medida : det.id_unidad_medida,
    //                                     'lugar_entrega': det.lugar_entrega,
    //                                     'observacion': det.observacion,
    //                                     'precio_unitario': det.precio_unitario,
    //                                     'stock_comprometido': cantidad_atendido_almacen,
    //                                     'subtotal': det.subtotal,
    //                                     'unidad_medida': det.producto!=null && det.producto.unidad_medida !=null ?det.producto.unidad_medida.abreviatura:det.unidad_medida
    //                                 }
    //                             );
    //                         }

    //                     }
    //                 });
    //             });
    //             // console.log(detalleOrdenList);
    //             if (detalleOrdenList.length == 0) {
    //                 Swal.fire(
    //                     '',
    //                     'No se encuentras items para atender',
    //                     'info'
    //                 );

    //             } else {

    //                 this.componerCabeceraOrden(response, idTipoOrden);
    //                 // this.listarDetalleOrdeRequerimiento(detalleOrdenList);
    //                 // this.setStatusPage();


    //             }
    //         }
    //     }).fail((jqXHR, textStatus, errorThrown) => {
    //         console.log(jqXHR);
    //         console.log(textStatus);
    //         console.log(errorThrown);
    //     });

    //     // sessionStorage.removeItem('reqCheckedList');
    //     // sessionStorage.removeItem('tipoOrden');
    // }

    // componerCabeceraOrden(data, idTipoOrden) {
    //     let codigoRequerimientoList =[];
    //     let idCcRequerimientoList =[];
    //     let observacionRequerimientoList =[];
    //     data.forEach(element => {
    //         let foundCodigoRequerimiento = codigoRequerimientoList.find(item => item == element.codigo);
    //         if (foundCodigoRequerimiento == undefined) {
    //             codigoRequerimientoList.push(element.codigo);
    //         }
    //         let foundIdCdpRequerimiento = codigoRequerimientoList.find(item => item == element.id_cc);
    //         if (foundIdCdpRequerimiento == undefined) {
    //             idCcRequerimientoList.push(element.id_cc);
    //         }
    //         let foundObservacionRequerimiento = codigoRequerimientoList.find(item => item == element.observacion);
    //         if (foundObservacionRequerimiento == undefined) {
    //             observacionRequerimientoList.push(element.observacion);
    //         }
    //     });

    //     this.cabeceraOrdenObject ={
    //         'id_tipo_orden':idTipoOrden??null,
    //         'descripcion_tipo_orden':idTipoOrden==1?'Compra':(idTipoOrden==2?'Servicio':'Orden & Servicio'),
    //         'codigo_requerimiento_vinculados':codigoRequerimientoList.toString(),
    //         'logo_empresa':Util.isEmpty(data[0].empresa.logo_empresa) ==false ?data[0].empresa.logo_empresa:null,
    //         'direccion_destino':data[0].sede && util.isEmpty(data[0].sede.direccion)==false ? data[0].sede.direccion : null,
    //         'id_ubigeo_destuno':data[0].sede && util.isEmpty(data[0].sede.id_ubigeo)==false ? data[0].sede.id_ubigeo : null,
    //         'id_empresa':data[0].id_empresa ? data[0].id_empresa : null,
    //         'id_sede':data[0].id_sede ? data[0].id_sede : null,
    //         'id_moneda':data[0].id_moneda ? data[0].id_moneda : null,
    //         'observacion':observacionRequerimientoList.toString(),
    //         'id_cc':idCcRequerimientoList
    //     };

    //     this.llenarCabeceraOrden(cabeceraOrdenObject);
    // }

    // llenarCabeceraOrden(cabeceraOrdenObject) {
    //     if (idTipoOrden == 3) { // orden de servicio
    //         this.ocultarBtnCrearProducto();
    //     }
    //     // let codigoRequerimiento = [];
    //     data.forEach(element => {
    //         let foundRequerimiento = this.codigoRequerimientoList.find(item => item == element.codigo);
    //         if (foundRequerimiento == undefined) {

    //             this.codigoRequerimientoList.push(element.codigo);

    //         }
    //     });

    //     document.querySelector("select[name='id_tp_documento']").value = idTipoOrden;
    //     document.querySelector("img[id='logo_empresa']").setAttribute("src", data[0].empresa.logo_empresa);
    //     document.querySelector("input[name='cdc_req']").value = this.codigoRequerimientoList.length > 0 ? this.codigoRequerimientoList : '';
    //     document.querySelector("input[name='ejecutivo_responsable']").value = '';
    //     document.querySelector("input[name='direccion_destino']").value = data[0].sede ? data[0].sede.direccion : '';
    //     document.querySelector("input[name='id_ubigeo_destino']").value = data[0].sede ? data[0].sede.id_ubigeo : '';
    //     document.querySelector("input[name='ubigeo_destino']").value = data[0].sede ? data[0].sede.ubigeo_completo : '';
    //     document.querySelector("select[name='id_sede']").value = data[0].id_sede ? data[0].id_sede : '';
    //     document.querySelector("select[name='id_moneda']").value = data[0].id_moneda ? data[0].id_moneda : 1;
    //     document.querySelector("input[name='id_cc']").value = data[0].id_cc ? data[0].id_cc : '';
    //     document.querySelector("textarea[name='observacion']").value = '';

    //     this.updateAllSimboloMoneda();

    // }




















}