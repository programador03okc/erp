var tempArchivoAdjuntoRequerimientoPagoCabeceraList = [];
// var tempIdArchivoAdjuntoRequerimientoPagoCabeceraToDeleteList = [];
var tempArchivoAdjuntoRequerimientoPagoDetalleList = [];
// var tempIdArchivoAdjuntoRequerimientoPagoDetalleToDeleteList = [];
var objBotonAdjuntoRequerimientoPagoDetalleSeleccionado = '';

let $tablaListaRequerimientoPago;
var iTableCounter = 1;
var oInnerTable;

var tempCentroCostoSelected;
var tempObjectBtnPartida;
var tempObjectBtnCentroCostos;

var $tablaListaCuadroPresupuesto;

var tempPartidasActivas = [];

var filtros_rq_pagos = {};
filtros_rq_pagos.elaborado = 'SIN_FILTRO';
filtros_rq_pagos.empresa = 'SIN_FILTRO';
filtros_rq_pagos.sede = 'SIN_FILTRO';
filtros_rq_pagos.grupo = 'SIN_FILTRO';
filtros_rq_pagos.division = 'SIN_FILTRO';
filtros_rq_pagos.fecha_inicio = 'SIN_FILTRO';
filtros_rq_pagos.fecha_final = 'SIN_FILTRO';
filtros_rq_pagos.estado = 'SIN_FILTRO';
class ListarRequerimientoPagoView {

    constructor(presupuestoInternoView) {
        this.presupuestoInternoView = presupuestoInternoView;
        this.ActualParametroAllOrMe = 'SIN_FILTRO';
        this.ActualParametroEmpresa = 'SIN_FILTRO';
        this.ActualParametroSede = 'SIN_FILTRO';
        this.ActualParametroGrupo = 'SIN_FILTRO';
        this.ActualParametroDivision = 'SIN_FILTRO';
        this.ActualParametroFechaDesde = 'SIN_FILTRO';
        this.ActualParametroFechaHasta = 'SIN_FILTRO';
        this.ActualParametroEstado = 'SIN_FILTRO';

    }

    limpiarTabla(idElement) {
        let nodeTbodyList = document.querySelectorAll("table[id='" + idElement + "'] tbody");
        nodeTbodyList.forEach(element => {
            if (element != null) {
                while (element.children.length > 0) {
                    element.removeChild(element.lastChild);
                }
            }

        });
    }
    initializeEventHandlerListaRequerimientoPago() {
        this.checkStatusBtnGuardar();
        $('#ListaRequerimientoPago tbody').on("click", "button.handleClickVerEnVistaRapidaRequerimientoPago", (e) => {
            this.verEnVistaRapidaRequerimientoPago(e.currentTarget);
            // console.log('dd');
        });

        document.onkeydown = function (evt) {
            if (document.querySelectorAll("div[class='modal fade in']").length == 1 && document.querySelectorAll("div[class='modal fade in']")[0].getAttribute("id") == 'modal-requerimiento-pago') {
                evt = evt || window.event;
                var isEscape = false;
                if ("key" in evt) {
                    isEscape = (evt.key === "Escape" || evt.key === "Esc");
                } else {
                    isEscape = (evt.keyCode === 27);
                }
                if (isEscape) {
                    if (document.querySelector("div[id='modal-requerimiento-pago']").classList.contains("in")) {
                        Swal.fire({
                            title: 'Esta seguro que desea cerrar el modal "Nuevo requerimiento de pago"?',
                            text: "Si acepta, se cerrará el modal",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'cancelar',
                            confirmButtonText: 'Si, cerrar'

                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modal-requerimiento-pago').modal('hide');

                            }
                        })
                    }

                }

            }
        };


        $('#modal-requerimiento-pago').on("change", "select.handleChangeOptEmpresa", (e) => {
            this.changeOptEmpresaSelect(e.currentTarget);
        });

        $('#modal-requerimiento-pago').on("change", "select.handleChangeOptGrupo", (e) => {
            this.changeOptGrupoSelect(e.currentTarget);
        });


        $('#modal-requerimiento-pago').on("click", "button.handleClickAgregarServicio", () => {
            this.agregarServicio();
            this.checkStatusBtnGuardar();
            // if ($("select[name='id_presupuesto_interno']").val() > 0) {
            //     this.presupuestoInternoView.ocultarOpcionCentroDeCosto();
            // }
        });

        $('#ListaDetalleRequerimientoPago tbody').on("click", "button.handleClickEliminarItem", (e) => {
            this.eliminarItem(e.currentTarget);
            this.checkStatusBtnGuardar();
        });

        $('#ListaDetalleRequerimientoPago tbody').on("click", "button.handleClickCargarModalPartidas", (e) => {
            document.querySelector("div[id='listaPartidas']").innerHTML = '';
            document.querySelector("div[id='listaPresupuesto']").innerHTML = '';
            this.cargarModalPartidas(e);
        });

        $('#modal-partidas').on("click", "h5.handleClickapertura", (e) => {
            this.apertura(e.currentTarget.dataset.idPresup);
            this.changeBtnIcon(e);
        });

        $('#modal-partidas').on("click", "button.handleClickSelectPartida", (e) => {
            this.selectPartida(e.currentTarget.dataset.idPartida);
        });

        $('#ListaDetalleRequerimientoPago tbody').on("click", "button.handleClickCargarModalCentroCostos", (e) => {
            this.cargarModalCentroCostos(e);
        });

        $('#modal-centro-costos').on("click", "h5.handleClickapertura", (e) => {
            this.apertura(e.currentTarget.dataset.idPresup);
            this.changeBtnIcon(e);
        });
        $('#modal-centro-costos').on("click", "button.handleClickSelectCentroCosto", (e) => {
            this.selectCentroCosto(e.currentTarget.dataset.idCentroCosto, e.currentTarget.dataset.codigo, e.currentTarget.dataset.descripcionCentroCosto);
        });

        $('#ListaDetalleRequerimientoPago tbody').on("blur", "input.handleBurUpdateSubtotal", (e) => {
            this.updateSubtotal(e.target);
        });

        $('#modal-requerimiento-pago').on("click", "button.handleClickGuardarRequerimientoPago", () => {
            this.guardarRequerimientoPago();
        });
        $('#modal-requerimiento-pago').on("click", "button.handleClickRequerimientoPago", () => {
            this.actualizarRequerimientoPago();
        });
        $('#modal-requerimiento-pago').on("change", "select.handleChangeUpdateMoneda", () => {
            this.changeMonedaSelect();
        });
        $('#modal-requerimiento-pago').on("change", "select.handleCheckStatusValue", (e) => {
            this.checkStatusValue(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("keyup", "input.handleCheckStatusValue", (e) => {
            this.checkStatusValue(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("keyup", "textarea.handleCheckStatusValue", (e) => {
            this.checkStatusValue(e.currentTarget);
        });
        // $('#modal-requerimiento-pago').on("click", "button.handleClickModalListaCuadroDePresupuesto", () => {
        //     this.modalListaCuadroDePresupuesto();
        // });
        $('#modal-requerimiento-pago').on("click", "button.handleClickAdjuntarArchivoCabecera", (e) => {
            let idRrequerimientoPAgo = e.currentTarget.dataset.idRequerimientoPago > 0 ? e.currentTarget.dataset.idRequerimientoPago : parseInt(document.querySelector("form[id='form-requerimiento-pago'] input[name='id_requerimiento_pago']").value);
            this.modalAdjuntarArchivosCabecera(idRrequerimientoPAgo);
        });
        $('#modal-requerimiento-pago').on("click", "button.handleClickAdjuntarArchivoDetalle", (e) => {
            this.modalAdjuntarArchivosDetalle(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("click", "button.handleClickInfoAdicionalCuentaSeleccionada", (e) => {
            this.mostrarInfoAdicionalCuentaSeleccionada(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("change", "select.handleChangeTipoDestinatario", (e) => {
            this.changeTipoDestinatario(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("blur", "input.handleBlurBuscarDestinatarioPorNumeroDocumento", (e) => {
            this.buscarDestinatarioPorNumeroDeDocumento(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("keyup", "input.handleKeyUpBuscarDestinatarioPorNombre", (e) => {
            this.buscarDestinatarioPorNombre(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("focusin", "input.handleFocusInputNombreDestinatario", (e) => {
            this.focusInputNombreDestinatario(e.currentTarget);
        });
        $('#modal-requerimiento-pago').on("focusout", "input.handleFocusOutInputNombreDestinatario", (e) => {
            this.focusOutInputNombreDestinatario(e.currentTarget);
        });

        $('#modal-requerimiento-pago').on("change", "select.updateDivision", (e) => {
            this.updateDivision(e.currentTarget);
        });

        $('#listaDestinatariosEncontrados').on("click", "tr.handleClickSeleccionarDestinatario", (e) => {
            this.seleccionarDestinatario(e.currentTarget);
        });

        $('#ListaRequerimientoPago tbody').on("click", "button.handleClickimprimirRequerimientoPagoEnPdf", (e) => {
            this.imprimirRequerimientoPagoEnPdf(e.currentTarget);
        });
        $('#modal-vista-rapida-requerimiento-pago').on("click", "a.handleClickAdjuntarArchivoCabecera", (e) => {
            this.modalVerAdjuntarArchivosCabecera(e.currentTarget.dataset.idRequerimientoPago);
        });
        $('#modal-vista-rapida-requerimiento-pago').on("click", "a.handleClickAdjuntarArchivoDetalle", (e) => {
            this.modalVerAdjuntarArchivosDetalle(e.currentTarget.dataset.id);
        });
        $('#ListaRequerimientoPago tbody').on("click", "button.handleClickEditarRequerimientoPago", (e) => {
            this.editarRequerimientoPago(e.currentTarget);
        });
        $('#ListaRequerimientoPago tbody').on("click", "button.handleClickAnularRequerimientoPago", (e) => {
            this.anularRequerimientoPago(e.currentTarget);
        });
        $('#listaCuadroPresupuesto').on("click", "button.handleClickSeleccionarCDP", (e) => {
            this.seleccionarCDP(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "input.handleChangeAgregarAdjuntoCabecera", (e) => {
            this.agregarAdjuntoRequerimientoPagoCabecera(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("click", "button.handleClickDescargarArchivoCabeceraRequerimientoPago", (e) => {
            this.descargarArchivoRequerimientoPagoCabecera(e.currentTarget);
        });
        $('#modal-ver-adjuntos-requerimiento-pago-cabecera').on("click", "button.handleClickDescargarArchivoCabeceraRequerimientoPago", (e) => {
            this.descargarArchivoRequerimientoPagoCabecera(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("click", "button.handleClickEliminarArchivoCabeceraRequerimientoPago", (e) => {
            this.eliminarArchivoRequerimientoPagoCabecera(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "select.handleChangeCategoriaAdjunto", (e) => {
            this.actualizarCategoriaDeAdjunto(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "input.handleChangeFechaEmision", (e) => {
            this.actualizarFechaEmisionAdjunto(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "input.handleChangeSerieComprobante", (e) => {
            this.actualizarSerieComprobanteDeAdjunto(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "input.handleChangeNumeroComprobante", (e) => {
            this.actualizarNumeroComprobanteDeAdjunto(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago').on("change", "input.handleChangeMontoTotalComprobante", (e) => {
            this.actualizarMontoTotalDeAdjunto(e.currentTarget);
        });
        // $('#modal-ver-agregar-adjuntos-requerimiento-pago').on("change", "input.handleChangeFechaEmision", (e) => {
        //     this.actualizarFechaEmisionAdjunto(e.currentTarget);
        // });

        $('#modal-adjuntar-archivos-requerimiento-pago-detalle').on("change", "input.handleChangeAgregarAdjuntoDetalle", (e) => {
            this.agregarAdjuntoRequerimientoPagoDetalle(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago-detalle').on("click", "button.handleClickDescargarArchivoRequerimientoPagoDetalle", (e) => {
            this.descargarArchivoRequerimientoPagoDetalle(e.currentTarget);
        });
        $('#modal-ver-adjuntos-requerimiento-pago-detalle').on("click", "button.handleClickDescargarArchivoRequerimientoPagoDetalle", (e) => {
            this.descargarArchivoRequerimientoPagoDetalle(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento-pago-detalle').on("click", "button.handleClickEliminarArchivoRequerimientoPagoDetalle", (e) => {
            this.eliminarArchivoRequerimientoPagoDetalle(e.currentTarget);
        });

        $('#modal-requerimiento-pago').on("change", "select.handleChangeProyecto", (e) => {
            this.deshabilitarOtrosTiposDePresupuesto('SELECCION_PROYECTOS', e.currentTarget.value); // deshabilitar el poder afectar otro presupuesto ejemplo: selector de proyectos, selctor de cdp
            this.obtenerCentroCostoDeProyecto();
            this.habilitarInputMesAfectacion(false);


        });
        $('#modal-requerimiento-pago').on("change", "select.handleChangePresupuestoInterno", (e) => {
            this.deshabilitarOtrosTiposDePresupuesto('SELECCION_PRESUPUESTO_INTERNO', e.currentTarget.value); // deshabilitar el poder afectar otro presupuesto ejemplo: selector de proyectos, selctor de cdp
            if(e.currentTarget.value>0){
                this.habilitarInputMesAfectacion(true);
            }else{
                this.habilitarInputMesAfectacion(false);
            }
        });
        $('#listaCuadroPresupuesto').on("click", "button.handleClickSeleccionarCDP", (e) => {
            // console.log(e.currentTarget.dataset.idCc);
            this.deshabilitarOtrosTiposDePresupuesto('SELECCION_CDP', e.currentTarget.dataset.idCc);
            this.habilitarInputMesAfectacion(false);

        });

        // $('#modal-requerimiento-pago').on("click", "button.handleClickLimpiarSeleccionCuadroDePresupuesto", (e) => {
        //     this.deshabilitarOtrosTiposDePresupuesto('SELECCION_CDP', 0);
        //     document.querySelector("input[name='id_cc']").value = ''; // TODO : actualizar todo los selectores con id_cc que se usan en el formulario
        //     document.querySelector("input[name='codigo_oportunidad']").value = '';
        // });

        $('#modal-requerimiento-pago').on("blur", "input.handleBlurCalcularPresupuestoUtilizadoYSaldoPorPartida", () => {
            this.calcularPresupuestoUtilizadoYSaldoPorPartida();
        });
        $('#modal-requerimiento-pago').on("change", "select.handleChangeCalcularPresupuestoUtilizadoYSaldoPorPartida", () => {
            this.calcularPresupuestoUtilizadoYSaldoPorPartida();
        });

    }

    calcularPresupuestoUtilizadoYSaldoPorPartida() {
        tempPartidasActivas = [];
        let partidaAgregadas = [];
        let subtotalItemList = [];

        let idMonedaPresupuestoUtilizado = '';
        let simboloMonedaPresupuestoUtilizado = '';

        let divPadre= '';
        let tbodyChildren ='';
        if (document.querySelector("div[id='modal-requerimiento-pago']").classList.contains("in")){
            divPadre="div[id='modal-requerimiento-pago'] ";
            idMonedaPresupuestoUtilizado = document.querySelector("select[name='moneda']").value;
            simboloMonedaPresupuestoUtilizado = document.querySelector("select[name='moneda']").options[document.querySelector("select[name='moneda']").selectedIndex].dataset.simbolo;
            tbodyChildren =  document.querySelector("tbody[id='body_detalle_requerimiento_pago']").children;

        }
        if(document.querySelector("div[id='modal-vista-rapida-requerimiento-pago']").classList.contains("in")){
            divPadre="div[id='modal-vista-rapida-requerimiento-pago'] ";
            idMonedaPresupuestoUtilizado = document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_moneda']").value
            simboloMonedaPresupuestoUtilizado = idMonedaPresupuestoUtilizado =='1'?'S/':(idMonedaPresupuestoUtilizado==2?'$':'') ;
            tbodyChildren =  document.querySelector("tbody[id='body_requerimiento_pago_detalle_vista']").children;
        }

        // let tbodyChildren = document.querySelector("tbody[id='body_detalle_requerimiento_pago']").childElementCount > 0 ? document.querySelector("tbody[id='body_detalle_requerimiento_pago']").children : document.querySelector("tbody[id='body_requerimiento_pago_detalle_vista']").children;

        let actualTipoCambioCompra = document.querySelector("span[id='tipo_cambio_compra']").textContent;



        for (let index = 0; index < tbodyChildren.length; index++) {
            if (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida > 0) {
                if (!partidaAgregadas.includes(tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida)) {
                    partidaAgregadas.push(tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida);
                    tempPartidasActivas.push({
                        'id_partida': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida,
                        'tipo_prespuesto': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.tipoPresupuesto,
                        'codigo': tbodyChildren[index].querySelector("p[class='descripcion-partida']").title,
                        'descripcion': tbodyChildren[index].querySelector("p[class='descripcion-partida']").textContent,
                        'presupuesto_mes': (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.presupuestoMes).replace(/,/gi, ''),
                        'total_saldo_mes_disponible': (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalSaldoMes)!=null ?(tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalSaldoMes).replace(/,/gi, ''):0,
                        'presupuesto_total': (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal).replace(/,/gi, ''),
                        'id_moneda_presupuesto_utilizado': idMonedaPresupuestoUtilizado,
                        'simbolo_moneda_presupuesto_utilizado': simboloMonedaPresupuestoUtilizado,
                        'presupuesto_utilizado_al_cambio': 0,
                        'presupuesto_utilizado': 0,
                        'total_por_consumido_con_igv_fase_aprobacion': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalPorConsumidoConIgvFaseAprobacion != null && tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalPorConsumidoConIgvFaseAprobacion != null ? ((tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalPorConsumidoConIgvFaseAprobacion).replace(/,/gi, '')) : 0,
                        'saldo_total': 0,
                        'saldo_mes': 0
                    });
                }

                let subtotal = (tbodyChildren[index].querySelector("input[class~='cantidad']").value > 0 ? tbodyChildren[index].querySelector("input[class~='cantidad']").value : 0) * (tbodyChildren[index].querySelector("input[class~='precio']").value > 0 ? tbodyChildren[index].querySelector("input[class~='precio']").value : 0);

                subtotalItemList.push({
                    'id_partida': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida,
                    'subtotal': subtotal
                });

            }
        }



        for (let p = 0; p < tempPartidasActivas.length; p++) {
            for (let i = 0; i < subtotalItemList.length; i++) {
                if (tempPartidasActivas[p].id_partida == subtotalItemList[i].id_partida) {
                    tempPartidasActivas[p].presupuesto_utilizado += subtotalItemList[i].subtotal;
                }
            }
        }

        for (let p = 0; p < tempPartidasActivas.length; p++) {
            if (tempPartidasActivas[p].id_moneda_presupuesto_utilizado == 2) { // moneda dolares
                let presupuesto_utilizado_alCambio = tempPartidasActivas[p].presupuesto_utilizado * actualTipoCambioCompra;
                tempPartidasActivas[p].presupuesto_utilizado_al_cambio = presupuesto_utilizado_alCambio;
                if (document.querySelector("input[name='id_requerimiento_pago']").value > 0) {

                    tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total)) - parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : (tempPartidasActivas[p].presupuesto_utilizado_al_cambio > 0 ? tempPartidasActivas[p].presupuesto_utilizado_al_cambio : 0));
                    tempPartidasActivas[p].saldo_mes = parseFloat((tempPartidasActivas[p].total_saldo_mes_disponible)) - parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : (tempPartidasActivas[p].presupuesto_utilizado_al_cambio > 0 ? tempPartidasActivas[p].presupuesto_utilizado_al_cambio : 0));
                } else {
                    tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total)) - parseFloat(presupuesto_utilizado_alCambio > 0 ? presupuesto_utilizado_alCambio : 0) - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion);
                    tempPartidasActivas[p].saldo_mes = parseFloat((tempPartidasActivas[p].total_saldo_mes_disponible)) - parseFloat(presupuesto_utilizado_alCambio > 0 ? presupuesto_utilizado_alCambio : 0) - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion);

                }
            } else { // moneda soles
                if (document.querySelector("input[name='id_requerimiento_pago']").value > 0) {
                    tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total)) - parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : 0) - (tempPartidasActivas[p].presupuesto_utilizado > 0 ? tempPartidasActivas[p].presupuesto_utilizado : 0);
                    tempPartidasActivas[p].saldo_mes=parseFloat(tempPartidasActivas[p].total_saldo_mes_disponible) - parseFloat(tempPartidasActivas[p].presupuesto_utilizado) -parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion);
                } else {
                    tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total)) - parseFloat(tempPartidasActivas[p].presupuesto_utilizado > 0 ? parseFloat(tempPartidasActivas[p].presupuesto_utilizado) : 0) - (parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion>0?parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion):0));
                    tempPartidasActivas[p].saldo_mes = parseFloat((tempPartidasActivas[p].total_saldo_mes_disponible)) - parseFloat(tempPartidasActivas[p].presupuesto_utilizado) - parseFloat(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion);
                }

            }
        }

        for (let p = 0; p < tempPartidasActivas.length; p++) {
            tempPartidasActivas[p].presupuesto_mes = Util.formatoNumero(tempPartidasActivas[p].presupuesto_mes,2,'.','');
            tempPartidasActivas[p].presupuesto_total= Util.formatoNumero(tempPartidasActivas[p].presupuesto_total,2,'.','');
            tempPartidasActivas[p].presupuesto_utilizado= Util.formatoNumero(tempPartidasActivas[p].presupuesto_utilizado,2,'.','');
            tempPartidasActivas[p].presupuesto_utilizado_al_cambio= Util.formatoNumero(tempPartidasActivas[p].presupuesto_utilizado_al_cambio,2,'.','');
            tempPartidasActivas[p].saldo_mes= Util.formatoNumero(tempPartidasActivas[p].saldo_mes,2,'.','');
            tempPartidasActivas[p].saldo_total= Util.formatoNumero(tempPartidasActivas[p].saldo_total,2,'.','');
            tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion= Util.formatoNumero(tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion,2,'.','');
            tempPartidasActivas[p].total_saldo_mes_disponible= Util.formatoNumero(tempPartidasActivas[p].total_saldo_mes_disponible,2,'.','');

        }


        this.construirTablaPresupuestoUtilizadoYSaldoPorPartida(tempPartidasActivas);
        console.log(tempPartidasActivas);
    }

    construirTablaPresupuestoUtilizadoYSaldoPorPartida(data) {
        // console.log(data);
        this.limpiarTabla('listaPartidasActivas');
        data.forEach(element => {

            let bodyPartidaActivasList = document.querySelectorAll("tbody[id='body_partidas_activas']");

            let displayMontoUtilizadoActual = "";
            if (document.querySelector("div[id='modal-requerimiento-pago']").classList.contains("in")){
                displayMontoUtilizadoActual = "";
            }
            if(document.querySelector("div[id='modal-vista-rapida-requerimiento-pago']").classList.contains("in")){
                displayMontoUtilizadoActual = "hidden";
            }

            bodyPartidaActivasList.forEach(bodyPartidaActiva => {
                // console.log(bodyPartidaActiva);
                bodyPartidaActiva.insertAdjacentHTML('beforeend', `<tr style="text-align:center">
                    <td>${element.codigo}</td>
                    <td>${element.descripcion}</td>
                    <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.presupuesto_total, 2)}</td>
                    <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.presupuesto_mes, 2)}</td>
                    <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.total_saldo_mes_disponible, 2)}</td>
                    <td style="text-align:right; background-color: #fbdddd;" ${displayMontoUtilizadoActual}><span class="simboloMoneda">${element.simbolo_moneda_presupuesto_utilizado}</span>${element.presupuesto_utilizado_al_cambio > 0 ? (Util.formatoNumero(element.presupuesto_utilizado, 2) + ' (S/' + Util.formatoNumero(element.presupuesto_utilizado_al_cambio, 2) + ')') : (Util.formatoNumero(element.presupuesto_utilizado, 2))}</td>
                    <td style="text-align:right; background-color: #fbdddd;">
                        <span>S/</span>${Util.formatoNumero(element.total_por_consumido_con_igv_fase_aprobacion, 2)}
                        <button type="button" class="btn btn-primary btn-xs" name="btnVerRequerimientosVinculados"
                        onClick="verRequerimientosVinculadosConPartida(${element.id_partida},'${element.codigo.trim()}','${element.descripcion.trim()}','${element.tipo_prespuesto}','${element.presupuesto_total}','${element.presupuesto_mes}');"title="Ver Requerimientos vinculados con partida">
                        <i class="fas fa-eye"></i>
                        </button>
                    </td>
                    <td style="display:none; text-align:right; color:${element.saldo_total >= 0 ? '#333' : '#dd4b39'}; background-color: #e5fbdd;"><span>S/</span>${Util.formatoNumero(element.saldo_total, 2)}</td>
                    <td style="text-align:right; color:${element.saldo_mes >= 0 ? '#333' : '#dd4b39'}; background-color: #e5fbdd;  font-weight: bold; "><span>S/</span>${Util.formatoNumero(element.saldo_mes, 2)}</td>
                </tr>`);

            });

        });

    }

    obtenerCentroCostoDeProyecto(){
        let idCentroCosto= document.querySelector("select[name='proyecto']").options[document.querySelector("select[name='proyecto']").selectedIndex].dataset.idCentroCosto;
        let codigoCentroCosto= document.querySelector("select[name='proyecto']").options[document.querySelector("select[name='proyecto']").selectedIndex].dataset.codigoCentroCosto;
        let descripcionCentroCosto= document.querySelector("select[name='proyecto']").options[document.querySelector("select[name='proyecto']").selectedIndex].dataset.descripcionCentroCosto;
        document.querySelector("input[name='id_centro_costo']").value= idCentroCosto!=undefined && idCentroCosto >0 ?idCentroCosto:'';
        document.querySelector("input[name='descripcion_centro_costo']").value=((codigoCentroCosto !=undefined?codigoCentroCosto:'')+' - '+(descripcionCentroCosto!=undefined?descripcionCentroCosto:''));

        tempCentroCostoSelected={
           'id':idCentroCosto!=undefined && idCentroCosto !="" ?idCentroCosto:'',
           'codigo':codigoCentroCosto !=undefined && codigoCentroCosto !="" ?codigoCentroCosto:'',
           'descripcion':descripcionCentroCosto !=undefined && descripcionCentroCosto !="" ?descripcionCentroCosto:'',
         }

         if(idCentroCosto>0){
            this.autoLlenarCentroCosto();
        }else{
            Swal.fire(
                '',
                'No se detectó un centro de costo para este proyecto, debería establecer manualmente el centro de costo',
                'info'
            );
        }
    }

    autoLlenarCentroCosto(){
        let tbodyChildren = document.querySelector("tbody[id='body_detalle_requerimiento_pago']").children;
        if (tbodyChildren.length > 0) {

            Swal.fire({
                title: 'Establecer el centro de costo seleccionado para todos los ítem ingresados?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Si'

            }).then((result) => {
                if (result.isConfirmed) {
                    for (let i = 0; i < tbodyChildren.length; i++) {
                        tbodyChildren[i].querySelector("input[class='centroCosto']").value = tempCentroCostoSelected.id;
                        tbodyChildren[i].querySelector("p[class='descripcion-centro-costo']").title = tempCentroCostoSelected.descripcion;
                        tbodyChildren[i].querySelector("p[class='descripcion-centro-costo']").textContent = tempCentroCostoSelected.codigo;

                    }
                }
            });
        }
    }

    habilitarInputMesAfectacion(esActivo){
        if(esActivo){
            document.querySelector("div[id='input-mes-afectacion']")?document.querySelector("div[id='input-mes-afectacion']").removeAttribute("hidden"):false;
        }else{
            document.querySelector("div[id='input-mes-afectacion']")? document.querySelector("div[id='input-mes-afectacion']").setAttribute("hidden",true):false;

        }
    }

    deshabilitarOtrosTiposDePresupuesto(origen, valor) {
        switch (origen) {
            case 'SELECCION_PRESUPUESTO_INTERNO':
                if (valor > 0) {
                    document.querySelector("select[name='proyecto']").setAttribute("disabled", true);
                    document.querySelector("select[name='proyecto']").value = '';
                    document.querySelector("button[name='btnSearchCDP']").setAttribute("disabled", true);
                    document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody").innerHTML = "";
                    cdpVinculadoConRequerimientoList = [];
                    limpiarTabla("tablaCuadroPresupuestoVinculados");


                } else {
                    document.querySelector("select[name='proyecto']").removeAttribute("disabled");
                    document.querySelector("button[name='btnSearchCDP']").removeAttribute("disabled");
                }
                break;
            case 'SELECCION_PROYECTOS':
                if (valor > 0) {
                    document.querySelector("select[name='id_presupuesto_interno']").setAttribute("disabled", true);
                    document.querySelector("select[name='id_presupuesto_interno']").value = '';
                    document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody").innerHTML = "";
                    document.querySelector("button[name='btnSearchCDP']").setAttribute("disabled", true);
                    cdpVinculadoConRequerimientoList = [];
                    limpiarTabla("tablaCuadroPresupuestoVinculados");


                } else {
                    document.querySelector("select[name='id_presupuesto_interno']").removeAttribute("disabled");
                    document.querySelector("button[name='btnSearchCDP']").removeAttribute("disabled");
                }
                break;
            case 'SELECCION_CDP':
                if (valor > 0) {
                    document.querySelector("select[name='id_presupuesto_interno']").setAttribute("disabled", true);
                    document.querySelector("select[name='id_presupuesto_interno']").value = '';
                    document.querySelector("select[name='proyecto']").setAttribute("disabled", true);
                    document.querySelector("select[name='proyecto']").value = '';
                } else {
                    document.querySelector("select[name='id_presupuesto_interno']").removeAttribute("disabled");
                    document.querySelector("select[name='proyecto']").removeAttribute("disabled");
                }
                break;

            default:
                break;
        }
    }

    changeBtnIcon(obj) {

        if (obj.currentTarget.children[0].className == 'fas fa-chevron-right') {

            obj.currentTarget.children[0].classList.replace('fa-chevron-right', 'fa-chevron-down')
        } else {
            obj.currentTarget.children[0].classList.replace('fa-chevron-down', 'fa-chevron-right')
        }
    }
    //para abrir el modal
    abrirModalFiltrosRequerimientosElaborados() {
        $('#modal-filtro-requerimientos-elaborados').modal({
            show: true,
            backdrop: 'static'
        });
    }
    /*
    initializeEventHandler() {
        // document.querySelector("button[class~='handleClickImprimirRequerimientoPdf']").addEventListener("click", this.imprimirRequerimientoPdf.bind(this), false);

        $('#modal-filtro-requerimientos-elaborados').on("change", "select.handleChangeUpdateValorFiltroRequerimientosElaborados", (e) => {
            this.updateValorFiltroRequerimientosElaborados();
        });

        $('#modal-filtro-requerimientos-elaborados').on("click", "input[type=checkbox]", (e) => {
            this.estadoCheckFiltroRequerimientosElaborados(e);
        });

        $('#modal-filtro-requerimientos-elaborados').on("change", "select.handleChangeFiltroEmpresa", (e) => {
            this.getDataSelectSede(e.currentTarget.value);
        });
        $('#modal-filtro-requerimientos-elaborados').on("change", "select.handleChangeFiltroGrupo", (e) => {
            this.getDataSelectDivision(e.currentTarget.value);
        });
        $('#ListaRequerimientosElaborados').on("click", "button.handleClickImprimirRequerimientoPdf", (e) => {
            this.imprimirRequerimientoPdf(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-requerimiento').on("click", "button.handleClickDescargarArchivoRequerimientoCabecera", (e) => {
            this.descargarArchivoRequerimiento(e.currentTarget);
        });
        $('#modal-adjuntar-archivos-detalle-requerimiento').on("click", "button.handleClickDescargarArchivoRequerimientoDetalle", (e) => {
            this.descargarArchivoItem(e.currentTarget);
        });



        $('#modal-filtro-requerimientos-elaborados').on('hidden.bs.modal', () => {
            this.updateValorFiltroRequerimientosElaborados();

            if (this.updateContadorFiltroRequerimientosElaborados() == 0) {
                this.mostrarListaRequerimientoPago('SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO');
            } else {

                this.mostrarListaRequerimientoPago(this.ActualParametroAllOrMe, this.ActualParametroEmpresa, this.ActualParametroSede, this.ActualParametroGrupo, this.ActualParametroDivision, this.ActualParametroFechaDesde, this.ActualParametroFechaHasta, this.ActualParametroEstado);

            }



        });

    }
*/

    estadoCheckFiltroRequerimientosElaborados(e) {
        const modalFiltrosRequerimientosElaborados = document.querySelector("div[id='modal-filtro-requerimientos-elaborados']");
        switch (e.currentTarget.getAttribute('name')) {
            case 'chkElaborado':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='elaborado']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='elaborado']").setAttribute("readOnly", true)
                }
                break;
            case 'chkEmpresa':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='empresa']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='empresa']").setAttribute("readOnly", true)
                }
                break;
            case 'chkSede':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='sede']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='sede']").setAttribute("readOnly", true)
                }
                break;
            case 'chkGrupo':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='grupo']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='grupo']").setAttribute("readOnly", true)
                }
                break;
            case 'chkDivision':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='division']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='division']").setAttribute("readOnly", true)
                }
                break;
            case 'chkFechaRegistro':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("input[name='fechaRegistroDesde']").removeAttribute("readOnly")
                    modalFiltrosRequerimientosElaborados.querySelector("input[name='fechaRegistroHasta']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("input[name='fechaRegistroDesde']").setAttribute("readOnly", true)
                    modalFiltrosRequerimientosElaborados.querySelector("input[name='fechaRegistroHasta']").setAttribute("readOnly", true)
                }
                break;
            case 'chkEstado':
                if (e.currentTarget.checked == true) {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='estado']").removeAttribute("readOnly")
                } else {
                    modalFiltrosRequerimientosElaborados.querySelector("select[name='estado']").setAttribute("readOnly", true)
                }
                break;
            default:
                break;
        }

    }

    getDataSelectSede(idEmpresa) {

        if (idEmpresa > 0) {
            this.requerimientoCtrl.obtenerSede(idEmpresa).then((res) => {
                this.llenarSelectFiltroSede(res);
            }).catch(function (err) {
                console.log(err)
            })
        } else {
            let selectElement = document.querySelector("div[id='modal-filtro-requerimientos-elaborados'] select[name='sede']");
            if (selectElement.options.length > 0) {
                let i, L = selectElement.options.length - 1;
                for (i = L; i >= 0; i--) {
                    selectElement.remove(i);
                }
                let option = document.createElement("option");

                option.value = 'SIN_FILTRO';
                option.text = '-----------------';
                selectElement.add(option);
            }
        }
        return false;
    }

    llenarSelectFiltroSede(array) {
        let selectElement = document.querySelector("div[id='modal-filtro-requerimientos-elaborados'] select[name='sede']");
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
            option.setAttribute('data-ubigeo', element.id_ubigeo);
            option.setAttribute('data-name-ubigeo', element.ubigeo_descripcion);
            if (element.codigo == 'LIMA' || element.codigo == 'Lima') { // default sede lima
                option.selected = true;

            }

            selectElement.add(option);
        });

    }

    getDataSelectDivision(idGrupo) {

        if (idGrupo > 0) {
            this.requerimientoCtrl.getListaDivisionesDeGrupo(idGrupo).then((res) => {
                this.llenarSelectFiltroDivision(res);
            }).catch(function (err) {
                console.log(err)
            })
        } else {
            let selectElement = document.querySelector("div[id='modal-filtro-requerimientos-elaborados'] select[name='division']");
            if (selectElement.options.length > 0) {
                let i, L = selectElement.options.length - 1;
                for (i = L; i >= 0; i--) {
                    selectElement.remove(i);
                }
                let option = document.createElement("option");

                option.value = 'SIN_FILTRO';
                option.text = '-----------------';
                selectElement.add(option);
            }
        }
        return false;
    }


    llenarSelectFiltroDivision(array) {
        // console.log(array);
        let selectElement = document.querySelector("div[id='modal-filtro-requerimientos-elaborados'] select[name='division']");
        if (selectElement.options.length > 0) {
            let i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }
        let optionDefault = document.createElement("option");
        optionDefault.text = "Seleccione una opción";
        optionDefault.value = "";
        selectElement.add(optionDefault);

        array.forEach(element => {
            let option = document.createElement("option");
            option.text = element.descripcion;
            option.value = element.id_division;
            selectElement.add(option);
        });
    }


    updateDivision(obj) {
        let currentIdGrupo = obj.options[obj.selectedIndex].dataset.idGrupo;
        // console.log(currentIdGrupo);
        // console.log(obj.value);
        this.presupuestoInternoView.llenarComboPresupuestoInterno(currentIdGrupo, obj.value);

    }


    updateValorFiltroRequerimientosElaborados() {
        const modalRequerimientosElaborados = document.querySelector("div[id='modal-filtro-requerimientos-elaborados']");
        if (modalRequerimientosElaborados.querySelector("select[name='elaborado']").getAttribute("readonly") == null) {
            this.ActualParametroAllOrMe = modalRequerimientosElaborados.querySelector("select[name='elaborado']").value;
        }
        if (modalRequerimientosElaborados.querySelector("select[name='empresa']").getAttribute("readonly") == null) {
            this.ActualParametroEmpresa = modalRequerimientosElaborados.querySelector("select[name='empresa']").value;
        }
        if (modalRequerimientosElaborados.querySelector("select[name='sede']").getAttribute("readonly") == null) {
            this.ActualParametroSede = modalRequerimientosElaborados.querySelector("select[name='sede']").value;
        }
        if (modalRequerimientosElaborados.querySelector("select[name='grupo']").getAttribute("readonly") == null) {
            this.ActualParametroGrupo = modalRequerimientosElaborados.querySelector("select[name='grupo']").value;
        }
        if (modalRequerimientosElaborados.querySelector("select[name='division']").getAttribute("readonly") == null) {
            this.ActualParametroDivision = modalRequerimientosElaborados.querySelector("select[name='division']").value;
        }
        if (modalRequerimientosElaborados.querySelector("input[name='fechaRegistroDesde']").getAttribute("readonly") == null) {
            this.ActualParametroFechaDesde = modalRequerimientosElaborados.querySelector("input[name='fechaRegistroDesde']").value.length > 0 ? modalRequerimientosElaborados.querySelector("input[name='fechaRegistroDesde']").value : 'SIN_FILTRO';
        }
        if (modalRequerimientosElaborados.querySelector("input[name='fechaRegistroHasta']").getAttribute("readonly") == null) {
            this.ActualParametroFechaHasta = modalRequerimientosElaborados.querySelector("input[name='fechaRegistroHasta']").value.length > 0 ? modalRequerimientosElaborados.querySelector("input[name='fechaRegistroHasta']").value : 'SIN_FILTRO';
        }
        if (modalRequerimientosElaborados.querySelector("select[name='estado']").getAttribute("readonly") == null) {
            this.ActualParametroEstado = modalRequerimientosElaborados.querySelector("select[name='estado']").value;
        }
    }

    updateContadorFiltroRequerimientosElaborados() {

        let contadorCheckActivo = 0;
        const allCheckBoxFiltroRequerimientosElaborados = document.querySelectorAll("div[id='modal-filtro-requerimientos-elaborados'] input[type='checkbox']");
        allCheckBoxFiltroRequerimientosElaborados.forEach(element => {
            if (element.checked == true) {
                contadorCheckActivo++;
            }
        });
        document.querySelector("button[id='btnFiltrosListaRequerimientosElaborados'] span").innerHTML = '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtros : ' + contadorCheckActivo
        return contadorCheckActivo;
    }
    imprimirRequerimientoPdf(obj) {
        if (obj.dataset.idRequerimiento > 0) {
            window.open('imprimir-requerimiento-pdf/' + obj.dataset.idRequerimiento + '/0');

        }

    }
    handleChangeFiltroListado() {
        this.mostrarListaRequerimientoPago(document.querySelector("select[name='mostrar_me_all']").value, document.querySelector("select[name='id_empresa_select']").value, document.querySelector("select[name='id_sede_select']").value, document.querySelector("select[name='id_grupo_select']").value, document.querySelector("select[name='division_select']").value, document.querySelector("select[name='id_prioridad_select']").value);

    }

    descargarListaCabeceraRequerimientoPagoElaboradosExcel() {
        // window.open(`listado-requerimientos-pagos-export-excel/${this.ActualParametroAllOrMe}/${this.ActualParametroEmpresa}/${this.ActualParametroSede}/${this.ActualParametroGrupo}/${this.ActualParametroDivision}/${this.ActualParametroFechaDesde}/${this.ActualParametroFechaHasta}/${this.ActualParametroEstado}`);

        // window.open(`listado-requerimientos-pagos-export-excel/${filtros_rq_pagos.elaborado}/${filtros_rq_pagos.empresa}/${filtros_rq_pagos.sede}/${filtros_rq_pagos.grupo}/${filtros_rq_pagos.division}/${filtros_rq_pagos.fecha_inicio}/${filtros_rq_pagos.fecha_final}/${filtros_rq_pagos.estado}`);
        let form = $('<form action="listado-requerimientos-pagos-export-excel" method="POST" target="_blank">' +
            '<input type="hidden" name="_token" value="' + token + '" >' +
            '<input type="hidden" name="elaborado" value="' + filtros_rq_pagos.elaborado + '" >' +
            '<input type="hidden" name="empresa" value="' + filtros_rq_pagos.empresa + '" >' +
            '<input type="hidden" name="sede" value="' + filtros_rq_pagos.sede + '" >' +
            '<input type="hidden" name="grupo" value="' + filtros_rq_pagos.grupo + '" >' +
            '<input type="hidden" name="division" value="' + filtros_rq_pagos.division + '" >' +
            '<input type="hidden" name="fecha_inicio" value="' + filtros_rq_pagos.fecha_inicio + '" >' +
            '<input type="hidden" name="fecha_final" value="' + filtros_rq_pagos.fecha_final + '" >' +
            '<input type="hidden" name="estado" value="' + filtros_rq_pagos.estado + '" >' +
            '</form>');
        $('body').append(form);
        form.submit();

    }
    descargarListaItemsRequerimientoPagoElaboradosExcel() {
        // window.open(`listado-items-requerimientos-pagos-export-excel/${this.ActualParametroAllOrMe}/${this.ActualParametroEmpresa}/${this.ActualParametroSede}/${this.ActualParametroGrupo}/${this.ActualParametroDivision}/${this.ActualParametroFechaDesde}/${this.ActualParametroFechaHasta}/${this.ActualParametroEstado}`);

        // window.open(`listado-items-requerimientos-pagos-export-excel/${filtros_rq_pagos.elaborado}/${filtros_rq_pagos.empresa}/${filtros_rq_pagos.sede}/${filtros_rq_pagos.grupo}/${filtros_rq_pagos.division}/${filtros_rq_pagos.fecha_inicio}/${filtros_rq_pagos.fecha_final}/${filtros_rq_pagos.estado}`);

        let form = $('<form action="listado-items-requerimientos-pagos-export-excel" method="POST" target="_blank">' +
            '<input type="hidden" name="_token" value="' + token + '" >' +
            '<input type="hidden" name="elaborado" value="' + filtros_rq_pagos.elaborado + '" >' +
            '<input type="hidden" name="empresa" value="' + filtros_rq_pagos.empresa + '" >' +
            '<input type="hidden" name="sede" value="' + filtros_rq_pagos.sede + '" >' +
            '<input type="hidden" name="grupo" value="' + filtros_rq_pagos.grupo + '" >' +
            '<input type="hidden" name="division" value="' + filtros_rq_pagos.division + '" >' +
            '<input type="hidden" name="fecha_inicio" value="' + filtros_rq_pagos.fecha_inicio + '" >' +
            '<input type="hidden" name="fecha_final" value="' + filtros_rq_pagos.fecha_final + '" >' +
            '<input type="hidden" name="estado" value="' + filtros_rq_pagos.estado + '" >' +
            '</form>');
        $('body').append(form);
        form.submit();
    }

    mostrarListaRequerimientoPago() {
        // console.log(meOrAll,idEmpresa,idSede,idGrupo,idDivision,fechaRegistroDesde,fechaRegistroHasta,idEstado);
        let that = this;
        vista_extendida();
        var vardataTables = funcDatatables();
        const button_crear_nuevo_requerimiento = (array_accesos.find(element => element === 20) ? {
            text: '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo',
            attr: {
                id: 'btnNuevoRequerimientoPago',
                title: 'Crear nuevo requerimiento de pago',
            },
            action: () => {
                this.nuevoRequerimientoPago();

            },
            className: 'btn-success btn-sm'
        } : []);
        const
            button_filtros = (array_accesos.find(element => element === 21) ? {
                text: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtros : 0',
                attr: {
                    id: 'btnFiltrosListaRequerimientosElaborados',
                    disabled: false
                },
                action: () => {
                    this.abrirModalFiltrosRequerimientosElaborados();

                },
                className: 'btn-default btn-sm'
            } : []),
            button_descargar_excel_cabecera = (array_accesos.find(element => element === 22) ? {
                text: '<span class="far fa-file-excel" aria-hidden="true"></span> Descargar a nivel cabecera',
                attr: {
                    id: 'btnDescargarListaRequerimientosElaboradosExcel'
                },
                action: () => {
                    this.descargarListaCabeceraRequerimientoPagoElaboradosExcel();

                },

                className: 'btn-default btn-sm'
            } : []),
            button_descargar_excel_items = (array_accesos.find(element => element === 22) ? {
                text: '<span class="far fa-file-excel" aria-hidden="true"></span> Descargar a nivel item',
                attr: {
                    id: 'btnDescargarListaRequerimientosElaboradosExcel'
                },
                action: () => {
                    this.descargarListaItemsRequerimientoPagoElaboradosExcel();

                },

                className: 'btn-default btn-sm'
            } : []);
        $tablaListaRequerimientoPago = $('#ListaRequerimientoPago').DataTable({
            'dom': vardataTables[1],
            'buttons': [
                // button_crear_nuevo_requerimiento
                // {
                //     text: '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo',
                //     attr: {
                //         id: 'btnNuevoRequerimientoPago',
                //         title: tieneAccionCrearRequerimientoPago > 0 ? 'Crear nuevo requerimiento de pago' : 'No tiene persmiso para crear un requerimiento de pago',
                //         disabled: tieneAccionCrearRequerimientoPago > 0 ? false : true
                //     },
                //     action: () => {
                //         this.nuevoRequerimientoPago();

                //     },
                //     className: 'btn-success btn-sm'
                // }
                 button_filtros, button_descargar_excel_cabecera, button_descargar_excel_items
            ],
            'language': vardataTables[0],
            'order': [[0, 'desc']],
            'bLengthChange': false,
            'serverSide': true,
            'destroy': true,
            'ajax': {
                'url': 'lista-requerimiento-pago',
                'type': 'POST',
                'data': filtros_rq_pagos,
                beforeSend: data => {

                    $("#ListaRequerimientoPago").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },

            },
            'columns': [
                { 'data': 'id_requerimiento_pago', 'name': 'requerimiento_pago.id_requerimiento_pago', 'visible': false },
                { 'data': 'prioridad', 'name': 'adm_prioridad.descripcion', 'className': 'text-center', 'visible': false },
                {
                    'data': 'codigo', 'name': 'codigo', 'className': 'text-center', 'render': function (data, type, row) {
                        // return `<label class="lbl-codigo handleClickAbrirRequerimiento" title="Abrir Requerimiento">${row.codigo}</label>`;
                        return `<div style="display:flex;">${row['termometro']} &nbsp; ${row.codigo} </div>`;
                    }
                },
                { 'data': 'concepto', 'name': 'concepto' },
                { 'data': 'descripcion_requerimiento_pago_tipo', 'name': 'requerimiento_pago_tipo.descripcion' },
                { 'data': 'fecha_registro', 'name': 'requerimiento_pago.fecha_registro', 'className': 'text-center' },
                { 'data': 'descripcion_empresa_sede', 'name': 'sis_sede.descripcion', 'className': 'text-center' },
                { 'data': 'grupo', 'name': 'sis_grupo.descripcion', 'className': 'text-center' },
                { 'data': 'division', 'name': 'division.descripcion', 'className': 'text-center' },
                { 'data': 'descripcion_proyecto', 'name': 'proy_proyecto.descripcion', 'className': 'text-center' },
                { 'data': 'descripcion_presupuesto_interno', 'name': 'presupuesto_interno.descripcion', 'className': 'text-center' },
                {
                    'data': 'monto_total', 'name': 'requerimiento_pago.monto_total', 'defaultContent': '', 'className': 'text-right', 'render': function (data, type, row) {
                        return row['simbolo_moneda'].concat(' ', $.number(row['monto_total'], 2));
                    }
                },
                { 'data': 'usuario_nombre_corto', 'name': 'sis_usua.nombre_corto' },
                {
                    'data': 'nombre_estado', 'name': 'adm_estado_doc.estado_doc', 'className': 'text-center', 'render': function (data, type, row) {
                        switch (row['id_estado']) {
                            case 1:
                                return '<span class="labelEstado label label-default">' + row['nombre_estado'] + '</span>';
                                break;
                            case 2:
                                return '<span class="labelEstado label label-success">' + row['nombre_estado'] + '</span>';
                                break;
                            case 3:
                                return '<span class="labelEstado label label-warning">' + row['nombre_estado'] + '</span>';
                                break;
                            case 5:
                                return '<span class="labelEstado label label-primary">' + row['nombre_estado'] + '</span>';
                                break;
                            case 7:
                                return '<span class="labelEstado label label-danger">' + row['nombre_estado'] + '</span>';
                                break;
                            default:
                                return '<span class="labelEstado label label-default">' + row['nombre_estado'] + '</span>';
                                break;

                        }
                    },
                },
                {
                    'data': 'id_requerimiento_pago', 'name': 'requerimiento_pago.id_requerimiento_pago', 'render': function (data, type, row) {

                        let containerOpenBrackets = '<center><div class="btn-group" role="group" style="margin-bottom: 5px;">';
                        let containerCloseBrackets = '</div></center>';
                        let btnVerEnModal = (array_accesos.find(element => element === 13) ? '<button type="button" class="btn btn-xs btn-primary  handleClickVerEnVistaRapidaRequerimientoPago" name="btnVerEnVistaRapidaRequerimientoPago" data-id-requerimiento-pago="' + row.id_requerimiento_pago + '" data-codigo-requerimiento-pago="' + row.codigo + '" title="Vista rápida"><i class="fas fa-eye fa-xs"></i></button>' : '');
                        let btnVerAdjuntosModal = (array_accesos.find(element => element === 31) ? '<button type="button" class="btn btn-xs btn-default  handleClickVerAgregarAdjuntosRequerimiento" name="btnVerAdjuntosRequerimientoPago" data-id-requerimiento-pago="' + row.id_requerimiento_pago + '" data-codigo-requerimiento-pago="' + row.codigo + '"  data-id-moneda="' + row.id_moneda + '" data-simbolo-moneda="' + row.simbolo_moneda + '" data-monto-a-pagar="' + row.monto_total + '" title="Ver archivos adjuntos" data-sustento="' + row['requerimiento_sustentado'] + '" ><i class="fas fa-paperclip fa-xs"></i></button>' : '');
                        let btnEditar = '<button type="button" class="btn btn-xs btn-warning  handleClickEditarRequerimientoPago" name="btnEditarRequerimientoPago" data-id-requerimiento-pago="' + row.id_requerimiento_pago + '" data-codigo-requerimiento-pago="' + row.codigo + '" title="Editar"><i class="fas fa-edit fa-xs"></i></button>';
                        let btnAnular = '<button type="button" class="btn btn-xs btn-danger  handleClickAnularRequerimientoPago" name="btnAnularRapidaRequerimientoPago" data-id-requerimiento-pago="' + row.id_requerimiento_pago + '" data-codigo-requerimiento-pago="' + row.codigo + '" title="Anular"><i class="fas fa-ban fa-xs"></i></button>';
                        let btnImprimirEnPdf = (array_accesos.find(element => element === 30) ? `<button type="button" class="btn btn-xs btn-default handleClickimprimirRequerimientoPagoEnPdf" name="btnImprimirRequerimientoPagoEnPdf" data-toggle="tooltip" data-placement="bottom" title="Imprimir en PDF" data-id-requerimiento-pago="${row.id_requerimiento_pago}">
                    <i class="fas fa-print"></i>
                    </button>`: '');

                        let botonera = containerOpenBrackets + btnVerEnModal + btnImprimirEnPdf;
                        if (row.id_usuario == auth_user.id_usuario && (row.id_estado == 1 || row.id_estado == 3)) {
                            botonera += btnEditar + btnAnular;
                        }
                        // if (row.cantidad_adjuntos_pago > 0) {
                        botonera += btnVerAdjuntosModal;
                        // }

                        botonera += containerCloseBrackets;

                        return botonera;


                    }
                }
            ],
            "createdRow": function (row, data, dataIndex) {

                let color = '#f2f2f2';
                if (data.requerimiento_sustentado == true) {
                    $(row).css('background-color', '#ffe270');
                }

            },
            'columnDefs': [
                // {
                //     'render': function (data, type, row) {
                //         // return `<label class="lbl-codigo handleClickAbrirRequerimiento" title="Abrir Requerimiento">${row.codigo}</label>`;
                //         let arreglo = ['F001-0010','F002-0012','F003-0013, F003-0014','Sin factura'];
                //         let facturaRandom= arreglo[Math.floor(Math.random() * arreglo.length)];
                //         return `<span class="label label-${facturaRandom=='Sin factura'?'default':'info'}" data-codigo="${row.codigo}">${facturaRandom}</span>`;
                //     }, targets: 13
                // },

            ],
            'initComplete': function () {
                // that.updateContadorFiltroRequerimientosElaborados();

                //Boton de busqueda
                const $filter = $('#ListaRequerimientoPago_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscar" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
                $input.off();
                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscar').trigger('click');
                    }
                });
                $('#btnBuscar').on('click', (e) => {
                    $tablaListaRequerimientoPago.search($input.val()).draw();
                })
                //Fin boton de busqueda

            },
            "drawCallback": function (settings) {
                if ($tablaListaRequerimientoPago.rows().data().length == 0) {
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
                $('#ListaRequerimientoPago_filter input').prop('disabled', false);
                $('#btnBuscar').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#ListaRequerimientoPago_filter input').trigger('focus');
                //fin botón búsqueda
                $("#ListaRequerimientoPago").LoadingOverlay("hide", true);
            }
        });
        //Desactiva el buscador del DataTable al realizar una busqueda
        $tablaListaRequerimientoPago.on('search.dt', function () {
            $('#tableDatos_filter input').prop('disabled', true);
            $('#btnBuscar').html('<span class="glyphicon glyphicon-time" aria-hidden="true"></span>').attr('disabled', true);
        });

        // $('#ListaRequerimientoPago').DataTable().on("draw", function () {
        //     resizeSide();
        // });
    }
    limpiarMesajesValidacion() {
        let allDivError = document.querySelectorAll("div[class='form-group has-error']");
        let allSpanDanger = document.querySelectorAll("span[class~='text-danger']");
        if (allDivError.length > 0) {
            allDivError.forEach(element => {
                element.classList.remove('has-error');
            });
        }
        if (allSpanDanger.length > 0) {
            allSpanDanger.forEach(element => {
                element.remove();
            });
        }

    }

    resetearFormularioRequerimientoPago() {
        this.limpiarMesajesValidacion();
        $('#form-requerimiento-pago')[0].reset();
        document.querySelector("div[id='modal-requerimiento-pago'] span[name='codigo']").textContent = '';
        document.querySelector("div[id='modal-requerimiento-pago'] span[name='fecha_registro']").textContent = '';

        tempArchivoAdjuntoRequerimientoPagoCabeceraList = [];
        tempArchivoAdjuntoRequerimientoPagoDetalleList = [];

        cdpVinculadoConRequerimientoList = [];
        document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody").innerHTML = "";


        // tempIdArchivoAdjuntoRequerimientoPagoCabeceraToDeleteList = [];
        // tempIdArchivoAdjuntoRequerimientoPagoDetalleToDeleteList = [];
        objBotonAdjuntoRequerimientoPagoDetalleSeleccionado = [];
        this.limpiarTabla('ListaDetalleRequerimientoPago');
        this.calcularSubtotal();
        this.calcularTotal();
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_requerimiento_pago']").value = '';
        this.updateContadorTotalAdjuntosRequerimientoPagoCabecera();
        this.limpiarTabla('listaArchivosRequerimientoPagoCabecera');
        this.limpiarTabla('listaArchivosRequerimientoPagoDetalle');
        this.limpiarTabla('listaPartidasActivas');
        this.limpiarTabla('listaHistorialRevision');
        $(":file").filestyle('clear');

        this.limpiarTabla('listaDestinatariosEncontrados');
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").innerHTML = "";
        let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
        if (selectCuenta != null) {
            while (selectCuenta.children.length > 0) {
                selectCuenta.removeChild(selectCuenta.lastChild);
            }
        }

        let allMesPpto=document.querySelectorAll("span[name='mes_ppto']");
        allMesPpto.forEach(element => {
                 element.textContent = "";
        });

    }
    nuevoRequerimientoPago() {
        this.resetearFormularioRequerimientoPago();
        $('#modal-requerimiento-pago').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
        document.querySelector("div[id='modal-requerimiento-pago'] form[id='form-requerimiento-pago']").setAttribute("type", 'register');
        document.querySelector("div[id='modal-requerimiento-pago'] span[id='titulo-modal']").textContent = "Nuevo requerimiento de pago";
        document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnActualizarRequerimientoPago']").classList.add("oculto");
        document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").classList.remove("oculto");
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='fecha_registro']").value = moment().format("YYYY-MM-DD");
        let allMesPpto=document.querySelectorAll("span[name='mes_ppto']");
        let mesAfectacion = document.querySelector("select[name='mes_afectacion']");

        if(mesAfectacion){
            allMesPpto.forEach(element => {
                element.textContent = moment(mesAfectacion.value, 'MM').format('MMMM');
            });

        }else{
            allMesPpto.forEach(element => {
                    element.textContent =  moment().format('MMMM');
            });
         }
    }


    changeOptEmpresaSelect(obj) {
        let idEmpresa = obj.value;
        if (idEmpresa > 0) {
            document.querySelector("div[id='modal-requerimiento-pago'] select[name='sede']").removeAttribute("disabled");
            document.querySelector("div[id='modal-requerimiento-pago'] select[name='grupo']").removeAttribute("disabled");

            this.construirOptSelectSede(idEmpresa);
        } else {

            document.querySelector("div[id='modal-requerimiento-pago'] select[name='sede']").setAttribute("disabled", true);
            document.querySelector("div[id='modal-requerimiento-pago'] select[name='grupo']").setAttribute("disabled", true);
            document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']").setAttribute("disabled", true);
        }


        return false;
    }

    construirOptSelectSede(idEmpresa, idSede = null) {
        this.obtenerSede(idEmpresa).then((res) => {
            this.llenarSelectSede(res, idSede);
        }).catch(function (err) {
            console.log(err)
        })
    }

    obtenerSede(idEmpresa) {
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

    llenarSelectSede(array, idSede = null) {

        let selectElement = document.querySelector("div[id='modal-requerimiento-pago'] select[name='sede']");
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
                option.selected = true;

            }
            option.setAttribute('data-ubigeo', element.id_ubigeo);
            option.setAttribute('data-name-ubigeo', element.ubigeo_descripcion);
            selectElement.add(option);
        });

        // if (array.length > 0) {
        //     this.updateSedeByPassingElement(selectElement);
        // }

    }


    changeOptGrupoSelect(obj) {
        let idGrupo = obj.value;
        let descripcionGrupo = obj.options[obj.selectedIndex].textContent;
        if (idGrupo > 0) {
            document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']").removeAttribute("disabled");

            this.construirOptSelectDivision(idGrupo, null);

            this.llenarComboProyectos(idGrupo);

            document.querySelector("select[name='id_presupuesto_interno']").removeAttribute("disabled");
            document.querySelector("select[name='proyecto']").removeAttribute("disabled");
            document.querySelector("button[name='btnSearchCDP']").removeAttribute("disabled");

            if (idGrupo == 3 || descripcionGrupo == 'Proyectos') {
                // document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_cc']").value = '';
                // document.querySelector("div[id='modal-requerimiento-pago'] input[name='codigo_oportunidad']").value = '';
                document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-cdp']").classList.add("oculto");
            } else {
                document.querySelector("div[id='modal-requerimiento-pago'] select[name='proyecto']").value = 0;

            }

            if (idGrupo == 2 || descripcionGrupo == 'Comercial') {
                document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-cdp']").classList.remove("oculto");

            } else {
                document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-cdp']").classList.add("oculto");
            }

        } else {

            document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']").setAttribute("disabled", true);
        }
        return false;
    }

    llenarComboProyectos(idGrupo, idProyecto = null) {
        this.obtenerListaProyectos(idGrupo).then((res) => {
            this.construirListaProyecto(res, idProyecto);
        }).catch(function (err) {
            console.log(err)
        })
    }

    obtenerListaProyectos(idGrupo) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `obtener-lista-proyectos/${idGrupo}`,
                dataType: 'JSON',
                beforeSend: function (data) {

                    $('select[name="id_proyecto"]').LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                success(response) {
                    $('select[name="id_proyecto"]').LoadingOverlay("hide", true);
                    resolve(response);

                },
                fail: function (jqXHR, textStatus, errorThrown) {
                    $('select[name="id_proyecto"]').LoadingOverlay("hide", true);
                    alert("Hubo un problema al cargar los proyectos. Por favor actualice la página e intente de nuevo");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    }


    construirListaProyecto(data, idProyecto = null) {

        let selectElement = document.querySelector("div[id='contenedor-proyecto'] select[name='proyecto']");
        selectElement.innerHTML = '';
        let option = document.createElement("option");
        option.text = "Seleccionar un proyecto";
        option.value = '';
        selectElement.add(option);

        data.forEach(element => {
            let option = document.createElement("option");
            option.text = element.codigo + ' - ' + element.descripcion;
            option.value = element.id_proyecto;
            option.setAttribute('data-codigo', element.codigo !=null ?element.codigo:'');
            option.setAttribute('data-id-centro-costo', element.id_centro_costo !=null ?element.id_centro_costo:'');
            option.setAttribute('data-codigo-centro-costo', element.codigo_centro_costo !=null ?element.codigo_centro_costo:'');
            option.setAttribute('data-descripcion-centro-costo', element.descripcion_centro_costo !=null ? element.descripcion_centro_costo:'');
            if (element.id_proyecto == idProyecto) {
                option.selected = true;
            }
            selectElement.add(option);
        });
    }

    construirOptSelectDivision(idGrupo, idDivision = null) {
        this.obtenerDivision(idGrupo).then((res) => {
            this.llenarSelectDivision(res, idDivision);
        }).catch(function (err) {
            console.log(err)
        })
    }
    obtenerDivision(idGrupo) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-division-por-grupo/${idGrupo}`,
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

    llenarSelectDivision(array, idDivision = null) {
        let selectElement = document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']");

        if (selectElement.options.length > 0) {
            let i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        let optionDefault = document.createElement("option");
        optionDefault.text = "Elija una opción";
        optionDefault.value = "";
        selectElement.add(optionDefault);

        array.forEach(element => {
            let option = document.createElement("option");
            option.text = element.descripcion;
            option.value = element.id_division;
            if (element.id_division == idDivision) {
                option.selected = true;
            }

            option.dataset.idGrupo = element.grupo_id;
            // option.setAttribute('data-id-grupo', element.grupo_id);
            selectElement.add(option);
        });
    }

    makeId() {
        let ID = "";
        let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (let i = 0; i < 12; i++) {
            ID += characters.charAt(Math.floor(Math.random() * 36));
        }
        return ID;
    }


    agregarServicio(data = null) {
        // console.log( data);
        let idFila = data != null && data.id_requerimiento_pago_detalle > 0 ? data.id_requerimiento_pago_detalle : (this.makeId());
        let cantidadAdjuntos = data != null && data.adjunto ? (data.adjunto).filter((element, i) => element.id_estado != 7).length : 0;

        let idPartida = '';
        let tipoPresupuesto = '';
        let codigoPartida = '';
        let descripcionPartida = '';
        let totalPartida = 0;
        let totalPartidaMes = 0;
        let totalSaldoMes = 0;
        let totalPorConsumidoConIgvFaseAprobacion = 0;

        // console.log(data);
        if (data != null) {

            if (data.id_partida > 0) {
                tipoPresupuesto = 'ANTIGUO';
                idPartida = data.id_partida;
                codigoPartida = data.partida != null ? data.partida.codigo : '';
                descripcionPartida = data.partida != null ? data.partida.descripcion : '';
                totalPartida = data.presupuesto_old_total_partida ?? 0;

            } else if (data.id_partida_pi > 0) {
                tipoPresupuesto = 'INTERNO';
                idPartida = data.id_partida_pi;
                codigoPartida = data.presupuesto_interno_detalle != null ? data.presupuesto_interno_detalle.partida : '';
                descripcionPartida = data.presupuesto_interno_detalle != null ? data.presupuesto_interno_detalle.descripcion : '';
                totalPartida = data.presupuesto_interno_total_partida != null ? data.presupuesto_interno_total_partida : 0;
                totalPartidaMes = data.presupuesto_interno_mes_partida != null ? data.presupuesto_interno_mes_partida : 0;
                totalSaldoMes = data.presupuesto_interno_saldo_mes_disponible_partida != null ? data.presupuesto_interno_saldo_mes_disponible_partida : 0;
                totalPorConsumidoConIgvFaseAprobacion = data.total_consumido_hasta_fase_aprobacion_con_igv !=null ? (data.total_consumido_hasta_fase_aprobacion_con_igv) : 0;

            }
        }


        console.log(data);
        document.querySelector("tbody[id='body_detalle_requerimiento_pago']").insertAdjacentHTML('beforeend', `<tr style="background-color:${data != null && data.id_estado == '7' ? '#f1d7d7' : ''}; text-align:center">
        <td>
            <input type="hidden"  class="idEstado" name="idEstado[]" value="${data != null && data.id_estado}">
            <p class="descripcion-partida"
            data-tipo-presupuesto="${tipoPresupuesto}"
            data-id-partida="${idPartida}"
            data-presupuesto-total="${totalPartida}"
            data-presupuesto-mes="${totalPartidaMes}"
            data-total-saldo-mes="${totalSaldoMes}"
            data-total-por-consumido-con-igv-fase-aprobacion="${totalPorConsumidoConIgvFaseAprobacion}"

            title="${(descripcionPartida != '' ? descripcionPartida : '(NO SELECCIONADO)')}">${(codigoPartida != '' ? codigoPartida : '(NO SELECCIONADO)')}</p>
            <button type="button" class="btn btn-xs btn-info handleClickCargarModalPartidas" name="partida">Seleccionar</button>
            <div class="form-group">
                <h5></h5>
                <input type="text" class="partida" name="idPartida[]" value="${idPartida}" hidden>
            </div>
        </td>
        <td>
            <p class="descripcion-centro-costo" title="${tempCentroCostoSelected != undefined ? tempCentroCostoSelected.descripcion : data != null && data.centro_costo != null ? data.centro_costo.descripcion : '(NO SELECCIONADO)'}">${tempCentroCostoSelected != undefined ? tempCentroCostoSelected.codigo : (data != null && data.centro_costo != null ? data.centro_costo.codigo : '(NO SELECCIONADO)')}</p>
            <button type="button" class="btn btn-xs btn-primary handleClickCargarModalCentroCostos" name="centroCostos"  title="${data != null && data.centro_costo != null ? data.centro_costo.codigo : ''}" >Seleccionar</button>
            <div class="form-group">
                <h5></h5>
                <input type="text" class="centroCosto" name="idCentroCosto[]" value="${tempCentroCostoSelected != undefined ? tempCentroCostoSelected.id : (data != null && data.centro_costo != null ? data.centro_costo.id_centro_costo : '')}" hidden>
            </div>
        </td>
        <td>
            <div class="form-group">
                <h5></h5>
                <textarea class="form-control input-sm descripcion handleCheckStatusValue" name="descripcion[]" placeholder="Descripción">${data != null && typeof data.descripcion === 'string' ? data.descripcion : ""}</textarea>
            </div>
        </td>
        <td><select name="unidad[]" class="form-control input-sm oculto" value="17" readOnly><option value="17">Servicio</option></select> Servicio</td>
        <td>
            <div class="form-group">
                <h5></h5>
                <input class="form-control input-sm cantidad text-right handleCheckStatusValue handleBurUpdateSubtotal handleBlurCalcularPresupuestoUtilizadoYSaldoPorPartida" type="number" min="1" name="cantidad[]"  placeholder="Cantidad" value="${data != null && typeof data.cantidad === 'string' ? data.cantidad : ""}">
            </div>
        </td>
        <td>
            <div class="form-group">
                <h5></h5>
                <input class="form-control input-sm precio text-right handleCheckStatusValue handleBurUpdateSubtotal handleBlurCalcularPresupuestoUtilizadoYSaldoPorPartida" type="number" min="0" name="precioUnitario[]"  placeholder="Precio U." value="${data != null && typeof data.precio_unitario === 'string' ? data.precio_unitario : ""}">
            </div>
        </td>

        <td style="text-align:right;"><span class="moneda" name="simboloMoneda">${document.querySelector("select[name='moneda']").options[document.querySelector("select[name='moneda']").selectedIndex].dataset.simbolo}</span><span class="subtotal" name="subtotal[]">0.00</span></td>
        <td>
            <div class="form-group">
            <h5></h5>
                <textarea class="form-control input-sm motivo handleCheckStatusValue" name="motivo[]" placeholder="Motivo">${data != null && typeof data.motivo === 'string' ? data.motivo : ""}</textarea>
            </div>
        </td>
        <td>
            <div class="btn-group" role="group">
                <input type="hidden" class="tipoItem" name="tipoItem[]" value="2">
                <input type="hidden" class="idRegister" name="idRegister[]" value="${idFila}">
                <button type="button" class="btn btn-warning btn-xs handleClickAdjuntarArchivoDetalle" data-id="${idFila}" name="btnAdjuntarArchivoItem" title="Adjuntos" >
                    <i class="fas fa-paperclip"></i>
                    <span class="badge" name="cantidadAdjuntosItem" style="position:absolute; top:-10px; left:-10px; border: solid 0.1px;">${cantidadAdjuntos}</span>
                </button>
                <button type="button" class="btn btn-danger btn-xs handleClickEliminarItem" name="btnEliminarItem[]" title="Eliminar" ><i class="fas fa-trash-alt"></i></button>
            </div>
        </td>
        </tr>`);

        if (data != null && data.id_requerimiento_pago_detalle > 0) {

            this.getAdjuntosRequerimientoPagoDetalle(data.id_requerimiento_pago_detalle).then((adjuntoList) => {
                (adjuntoList).forEach(element => {
                    if (element.id_estado != 7) { // omitir anulados

                        tempArchivoAdjuntoRequerimientoPagoDetalleList.push({
                            id: element.id_requerimiento_pago_detalle_adjunto,
                            id_requerimiento_pago_detalle: element.id_requerimiento_pago_detalle,
                            nameFile: element.archivo,
                            action: '',
                            file: []
                        });
                    }
                });
            }).catch(function (err) {
                console.log(err)
            })
        }
    }


    eliminarItem(obj) {

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
                var regExp = /[a-zA-Z]/g;

                if (regExp.test(tr.querySelector("input[name='idRegister[]']").value) == true) {
                    tr.remove();
                    this.calcularSubtotal();
                    this.calcularTotal();
                } else {
                    tr.querySelector("input[class~='idEstado']").value = 7;
                    tr.classList.add("danger", "textRedStrikeHover");
                    tr.querySelector("button[name='btnEliminarItem[]']").setAttribute("disabled", true);
                    this.calcularSubtotal();
                    this.calcularTotal();
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

    checkStatusBtnGuardar() {
        if (document.querySelector("tbody[id='body_detalle_requerimiento_pago']").children.length > 0) {
            document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").removeAttribute("disabled")
            document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").setAttribute("title", "Guardar");
        } else {
            document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").setAttribute("disabled", true);
            document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").setAttribute("title", "Debe ingresar un item");
        }
    }

    // modal partidas
    cargarModalPartidas(obj) {


        tempObjectBtnPartida = obj.target;
        let id_grupo = document.querySelector("form[id='form-requerimiento-pago'] select[name='grupo']").value;
        let id_proyecto = document.querySelector("form[id='form-requerimiento-pago'] select[name='proyecto']").value;
        let tipoPptoCDP = document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody") != null ? document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody").childElementCount : 0;
        let usuarioProyectos = false;
        // console.log(gruposUsuario);
        gruposUsuario.forEach(element => {
            if (element.id_grupo == 3) { // proyectos
                usuarioProyectos = true
            }
        });
        if (id_grupo > 0) {

            if (!$("select[name='id_presupuesto_interno']").val() > 0) { //* si presupuesto interno fue seleccionado, no cargar presupuesto antiguo.

                if (id_proyecto > 0) {
                    $('#modal-partidas').modal({
                        show: true,
                        backdrop: 'true'
                    });
                    this.listarPartidas(id_grupo, id_proyecto > 0 ? id_proyecto : null);
                } else if (tipoPptoCDP > 0) {
                    Swal.fire(
                        '',
                        'No es requerido completar la partida cuando selecciona un CDP. Puede omitir',
                        'info'
                    );
                } else {
                    Swal.fire(
                        '',
                        'Debe seleccione un tipo presupuesto o proyecto',
                        'warning'
                    );
                }
            } else {
                $('#modal-partidas').modal({
                    show: true,
                    backdrop: 'true'
                });
            }
        } else {
            Swal.fire(
                '',
                'Debe seleccione primero la división para cargar las opciones.',
                'warning'
            );
        }
    }


    listarPartidas(idGrupo, idProyecto) {
        this.limpiarTabla('listaPartidas');
        this.obtenerListaPartidas(idGrupo, idProyecto).then((res) => {
            this.construirListaPartidas(res);

        }).catch(function (err) {
            console.log(err)
        })
    }

    obtenerListaPartidas(idGrupo, idProyecto) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `mostrar-partidas/${idGrupo}/${idProyecto}`,
                dataType: 'JSON',
                beforeSend: function (data) {
                    var customElement = $("<div>", {
                        "css": {
                            "font-size": "24px",
                            "text-align": "center",
                            "padding": "0px",
                            "margin-top": "-400px"
                        },
                        "class": "your-custom-class"
                    });

                    $('#modal-partidas div.modal-body').LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        custom: customElement,
                        imageColor: "#3c8dbc"
                    });
                },
                success(response) {
                    resolve(response);
                },
                fail: function (jqXHR, textStatus, errorThrown) {
                    $('#modal-partidas div.modal-body').LoadingOverlay("hide", true);
                    alert("Hubo un problema al cargar las partidas. Por favor actualice la página e intente de nuevo");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    }

    construirListaPartidas(data) {
        // console.log(data);
        let html = '';
        let isVisible = '';
        data['presupuesto'].forEach(presupuesto => {
            html += `
            <div id='${presupuesto.codigo}' class="panel panel-primary" style="width:100%; overflow: auto;">
                <h5 class="panel-heading handleClickapertura" data-id-presup="${presupuesto.id_presup}" style="margin: 0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
                    &nbsp; ${presupuesto.descripcion}
                </h5>
                <div id="pres-${presupuesto.id_presup}" class="oculto" style="width:100%;">
                    <table class="table table-bordered table-condensed partidas" id="listaPartidas" width="100%" style="font-size:0.9em">
                        <tbody>
            `;

            data['titulos'].forEach(titulo => {
                if (titulo.id_presup == presupuesto.id_presup) {
                    html += `
                    <tr id="com-${titulo.id_titulo}">
                        <td><strong>${titulo.codigo}</strong></td>
                        <td><strong>${titulo.descripcion}</strong></td>
                        <td class="right ${isVisible}"><strong>S/${Util.formatoNumero(titulo.total, 2)}</strong></td>
                    </tr> `;

                    data['partidas'].forEach(partida => {
                        if (partida.id_presup == presupuesto.id_presup) {
                            if (titulo.codigo == partida.cod_padre) {
                                html += `<tr id="par-${partida.id_partida}">
                                    <td style="width:15%; text-align:left;" name="codigo">${partida.codigo}</td>
                                    <td style="width:75%; text-align:left;" name="descripcion">${partida.descripcion}</td>
                                    <td style="width:15%; text-align:right;" name="importe_total" class="right ${isVisible}" data-presupuesto-total="${partida.importe_total}" >S/${Util.formatoNumero(partida.importe_total, 2)}</td>
                                    <td style="width:5%; text-align:center;"><button class="btn btn-success btn-xs handleClickSelectPartida" data-id-partida="${partida.id_partida}">Seleccionar</button></td>
                                </tr>`;
                            }
                        }
                    });

                }


            });
            html += `
                    </tbody>
                </table>
            </div>
        </div>`;
        });
        document.querySelector("div[id='listaPartidas']").innerHTML = html;

        $('#modal-partidas div.modal-body').LoadingOverlay("hide", true);

    }

    apertura(idPresup) {
        // let idPresup = e.target.dataset.idPresup;
        if ($("#pres-" + idPresup + " ").hasClass('oculto')) {
            $("#pres-" + idPresup + " ").removeClass('oculto');
            $("#pres-" + idPresup + " ").addClass('visible');
        } else {
            $("#pres-" + idPresup + " ").removeClass('visible');
            $("#pres-" + idPresup + " ").addClass('oculto');
        }
    }

    selectPartida(idPartida) {
        let codigo = $("#par-" + idPartida + " ").find("td[name=codigo]")[0].innerHTML;
        let descripcion = $("#par-" + idPartida + " ").find("td[name=descripcion]")[0].innerHTML;
        // let presupuestoTotal = $("#par-" + idPartida + " ").find("td[name=importe_total]")[0].dataset.presupuestoTotal;
        let presupuestoTotal = $("#par-" + idPartida + " ").find("td[name=importe_total]")[0].dataset.presupuestoTotal;
        let presupuestoMes = ($("#par-" + idPartida + " ").find("td[name=importe_mes]")[0]) != null ? $("#par-" + idPartida + " ").find("td[name=importe_mes]")[0].dataset.presupuestoMes : 0;


        tempObjectBtnPartida.nextElementSibling.querySelector("input").value = idPartida;

        tempObjectBtnPartida.textContent = 'Cambiar';

        let tr = tempObjectBtnPartida.closest("tr");
        // tr.querySelector("p[class='descripcion-partida']").dataset.idPartida = idPartida;
        tr.querySelector("p[class='descripcion-partida']").textContent = codigo
        tr.querySelector("p[class='descripcion-partida']").dataset.idPartida = idPartida;
        tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal = presupuestoTotal;
        tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoMes = presupuestoMes;

        // tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal = presupuestoTotal;
        tr.querySelector("p[class='descripcion-partida']").setAttribute('title', descripcion);

        // this.checkStatusValue(tempObjectBtnPartida.nextElementSibling.querySelector("input"));
        $('#modal-partidas').modal('hide');

    }
    // end modal partidas

    // modal centro costo
    cargarModalCentroCostos(obj) {
        tempObjectBtnCentroCostos = obj.target;

        $('#modal-centro-costos').modal({
            show: true
        });
        this.listarCentroCostos();
    }

    listarCentroCostos() {
        this.limpiarTabla('listaCentroCosto');

        this.obtenerCentroCostos().then((res) => {
            this.construirCentroCostos(res);
        }).catch(function (err) {
            console.log(err)
        })
    }

    obtenerCentroCostos() {
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

    construirCentroCostos(data) {
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

    selectCentroCosto(idCentroCosto, codigo, descripcion) {
        // console.log(idCentroCosto);
        tempObjectBtnCentroCostos.nextElementSibling.querySelector("input").value = idCentroCosto;
        tempObjectBtnCentroCostos.textContent = 'Cambiar';

        let tr = tempObjectBtnCentroCostos.closest("tr");
        tr.querySelector("p[class='descripcion-centro-costo']").textContent = codigo
        tr.querySelector("p[class='descripcion-centro-costo']").setAttribute('title', descripcion);
        this.checkStatusValue(tempObjectBtnCentroCostos.nextElementSibling.querySelector("input"));
        $('#modal-centro-costos').modal('hide');
        tempObjectBtnCentroCostos = null;
        // componerTdItemDetalleRequerimiento();
    }
    // end modal centro costo

    changeMonedaSelect() {
        let simboloMonedaPresupuestoUtilizado = document.querySelector("select[name='moneda']").options[document.querySelector("select[name='moneda']").selectedIndex].dataset.simbolo
        let allSelectorSimboloMoneda = document.getElementsByName("simboloMoneda");
        if (allSelectorSimboloMoneda.length > 0) {
            allSelectorSimboloMoneda.forEach(element => {
                element.textContent = simboloMonedaPresupuestoUtilizado;
            });
        }
        // document.querySelector("div[name='montoMoneda']").textContent = simboloMonedaPresupuestoUtilizado;
        // this.calcularPresupuestoUtilizadoYSaldoPorPartida();

    }

    updateSubtotal(obj) {
        let tr = obj.closest("tr");
        let cantidad = parseFloat(tr.querySelector("input[class~='cantidad']").value);
        let precioUnitario = parseFloat(tr.querySelector("input[class~='precio']").value);
        let subtotal = (cantidad * precioUnitario);
        tr.querySelector("span[class='subtotal']").textContent = Util.formatoNumero(subtotal, 2);
        this.calcularTotal();
    }

    calcularSubtotal() {
        let TableTBody = document.querySelector("tbody[id='body_detalle_requerimiento_pago']");
        let childrenTableTbody = TableTBody.children;
        for (let index = 0; index < childrenTableTbody.length; index++) {
            let cantidad = parseFloat(childrenTableTbody[index].querySelector("input[class~='cantidad']").value ? childrenTableTbody[index].querySelector("input[class~='cantidad']").value : 0);
            let precioUnitario = parseFloat(childrenTableTbody[index].querySelector("input[class~='precio']").value ? childrenTableTbody[index].querySelector("input[class~='precio']").value : 0);
            childrenTableTbody[index].querySelector("span[class~='subtotal']").textContent = (cantidad * precioUnitario);

        }
    }


    calcularTotal() {
        let TableTBody = document.querySelector("tbody[id='body_detalle_requerimiento_pago']");
        let childrenTableTbody = TableTBody.children;
        let total = 0;
        for (let index = 0; index < childrenTableTbody.length; index++) {
            // console.log(childrenTableTbody[index]);
            if (parseInt(childrenTableTbody[index].querySelector("input[class='idEstado']").value) != 7) {
                let cantidad = parseFloat(childrenTableTbody[index].querySelector("input[class~='cantidad']").value ? childrenTableTbody[index].querySelector("input[class~='cantidad']").value : 0);
                let precioUnitario = parseFloat(childrenTableTbody[index].querySelector("input[class~='precio']").value ? childrenTableTbody[index].querySelector("input[class~='precio']").value : 0);
                total += (cantidad * precioUnitario);
            }
        }
        let allLabelTotal = document.querySelectorAll("div[id='modal-requerimiento-pago'] label[name='total']");
        allLabelTotal.forEach(element => {
            element.textContent = Util.formatoNumero(total, 2);
        });
        document.querySelector("input[name='monto_total']").value = total;
        document.querySelector("input[name='monto_total_read_only']").value = Util.formatoNumero(total, 2);
    }

    checkStatusValue(obj) {
        if (obj.value > 0 || obj.value.length > 0) {
            obj.closest('div').classList.remove("has-error");
            if (obj.closest("div").querySelector("span")) {
                obj.closest("div").querySelector("span").remove();
            }
        } else {
            obj.closest('div').classList.add("has-error");
        }
    }

    validarFormularioRequerimientoPago() {
        let continuar = true;

        let mensajePartidaActiva = '';

        if (document.querySelector("select[name='proyecto']") == null || !parseInt(document.querySelector("select[name='proyecto']").value) > 0) {
            for (let index = 0; index < tempPartidasActivas.length; index++) {
                if (parseFloat(tempPartidasActivas[index]['saldo_mes']) < 0) {
                    mensajePartidaActiva += tempPartidasActivas[index]['descripcion'] + ' con un saldo de ' + $.number(tempPartidasActivas[index]['saldo_mes'], 2, '.', ',') + '<br>';

                }
            }
            // console.log(mensajePartidaActiva);
            if (mensajePartidaActiva != '') {
                continuar = false;
                Swal.fire(
                    '',
                    'Se excedió el presupuesto de la partida: <br>' + mensajePartidaActiva,
                    'warning'
                );
            }

        }

        if (document.querySelector("tbody[id='body_detalle_requerimiento_pago']").childElementCount == 0) {
            Swal.fire(
                '',
                'Ingrese por lo menos un producto/servicio',
                'warning'
            );
            return false;
        }
        if (document.querySelector("input[name='concepto']").value == '') {
            continuar = false;
            if (document.querySelector("input[name='concepto']").closest('div').querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Ingrese un concepto/motivo)';
                document.querySelector("input[name='concepto']").closest('div').querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("input[name='concepto']").closest('div').classList.add('has-error');
            }
        }
        if (!(document.querySelector("select[name='empresa']").value > 0)) {
            continuar = false;
            if (document.querySelector("select[name='empresa']").closest('div').querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione una empresa)';
                document.querySelector("select[name='empresa']").closest('div').querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("select[name='empresa']").closest('div').classList.add('has-error');
            }
        }
        if (!(document.querySelector("select[name='sede']").value > 0)) {
            continuar = false;
            if (document.querySelector("select[name='sede']").closest('div').querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione una sede)';
                document.querySelector("select[name='sede']").closest('div').querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("select[name='sede']").closest('div').classList.add('has-error');
            }
        }
        if (!(document.querySelector("select[name='grupo']").value > 0)) {
            continuar = false;
            if (document.querySelector("select[name='grupo']").closest('div').querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione un grupo)';
                document.querySelector("select[name='grupo']").closest('div').querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("select[name='grupo']").closest('div').classList.add('has-error');
            }
        }
        if (!(document.querySelector("select[name='division']").value > 0)) {
            continuar = false;
            if (document.querySelector("select[name='division']").closest('div').querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione una división)';
                document.querySelector("select[name='division']").closest('div').querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("select[name='division']").closest('div').classList.add('has-error');
            }
        }

        if (document.querySelector("input[name='id_contribuyente']").value == '' && document.querySelector("input[name='id_persona']").value == '') {
            continuar = false;
            if (document.querySelector("input[name='nombre_destinatario']").closest('div').parentElement.querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione un destinatario)';
                document.querySelector("input[name='nombre_destinatario']").closest('div').parentElement.querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("input[name='nombre_destinatario']").closest('div').parentElement.classList.add('has-error');
            }
        }
        // console.log(document.querySelector("select[name='id_cuenta']").value);
        if ((document.querySelector("select[name='id_cuenta']").value == '')) {
            continuar = false;
            if (document.querySelector("select[name='id_cuenta']").closest('div').parentElement.querySelector("span") == null) {
                let newSpanInfo = document.createElement("span");
                newSpanInfo.classList.add('text-danger');
                newSpanInfo.textContent = '(Seleccione una cuenta bancaria)';
                document.querySelector("select[name='id_cuenta']").closest('div').parentElement.querySelector("h5").appendChild(newSpanInfo);
                document.querySelector("select[name='id_cuenta']").closest('div').parentElement.classList.add('has-error');
            }
        }


        let tbodyChildren = document.querySelector("tbody[id='body_detalle_requerimiento_pago']").children;
        for (let index = 0; index < tbodyChildren.length; index++) {
            if (tbodyChildren[index].querySelector("input[class~='idEstado']").value != 7) {

                if (!(tbodyChildren[index].querySelector("input[class~='centroCosto']").value > 0)) {
                    continuar = false;
                    if (tbodyChildren[index].querySelector("input[class~='centroCosto']").closest('td').querySelector("span") == null) {
                        let newSpanInfo = document.createElement("span");
                        newSpanInfo.classList.add('text-danger');
                        newSpanInfo.textContent = 'Ingrese un centro de costo';
                        tbodyChildren[index].querySelector("input[class~='centroCosto']").closest('td').querySelector("h5").appendChild(newSpanInfo);
                        tbodyChildren[index].querySelector("input[class~='centroCosto']").closest('td').querySelector("div[class~='form-group']").classList.add('has-error');
                    }
                }
                if ((document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody") != null ? document.querySelector("table[id='tablaCuadroPresupuestoVinculados'] tbody").childElementCount : 0) == 0) {

                    if (!(tbodyChildren[index].querySelector("input[class~='partida']").value > 0)) {
                        continuar = false;
                        if (tbodyChildren[index].querySelector("input[class~='partida']").closest('td').querySelector("span") == null) {
                            let newSpanInfo = document.createElement("span");
                            newSpanInfo.classList.add('text-danger');
                            newSpanInfo.textContent = 'Ingrese una partida';
                            tbodyChildren[index].querySelector("input[class~='partida']").closest('td').querySelector("h5").appendChild(newSpanInfo);
                            tbodyChildren[index].querySelector("input[class~='partida']").closest('td').querySelector("div[class~='form-group']").classList.add('has-error');
                        }
                    }
                }

                if (!(tbodyChildren[index].querySelector("input[class~='cantidad']").value > 0)) {
                    continuar = false;
                    if (tbodyChildren[index].querySelector("input[class~='cantidad']").closest('td').querySelector("span") == null) {
                        let newSpanInfo = document.createElement("span");
                        newSpanInfo.classList.add('text-danger');
                        newSpanInfo.textContent = 'Ingrese una cantidad';
                        tbodyChildren[index].querySelector("input[class~='cantidad']").closest('td').querySelector("h5").appendChild(newSpanInfo);
                        tbodyChildren[index].querySelector("input[class~='cantidad']").closest('td').querySelector("div[class~='form-group']").classList.add('has-error');
                    }

                }
                if (!(tbodyChildren[index].querySelector("input[class~='precio']").value > 0)) {
                    continuar = false;
                    if (tbodyChildren[index].querySelector("input[class~='precio']").closest('td').querySelector("span") == null) {
                        let newSpanInfo = document.createElement("span");
                        newSpanInfo.classList.add('text-danger');
                        newSpanInfo.textContent = 'Ingrese un precio';
                        tbodyChildren[index].querySelector("input[class~='precio']").closest('td').querySelector("h5").appendChild(newSpanInfo);
                        tbodyChildren[index].querySelector("input[class~='precio']").closest('td').querySelector("div[class~='form-group']").classList.add('has-error');
                    }

                }
                if (tbodyChildren[index].querySelector("textarea[class~='descripcion']")) {
                    if (tbodyChildren[index].querySelector("textarea[class~='descripcion']").value == '') {
                        continuar = false;
                        if (tbodyChildren[index].querySelector("textarea[class~='descripcion']").closest('td').querySelector("span") == null) {
                            let newSpanInfo = document.createElement("span");
                            newSpanInfo.classList.add('text-danger');
                            newSpanInfo.textContent = 'Ingrese una descripción';
                            tbodyChildren[index].querySelector("textarea[class~='descripcion']").closest('td').querySelector("h5").appendChild(newSpanInfo);
                            tbodyChildren[index].querySelector("textarea[class~='descripcion']").closest('td').querySelector("div[class~='form-group']").classList.add('has-error');
                        }
                    }


                }
            }
        }
        return continuar;
    }

    actualizarCategoriaDeAdjunto(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.closest('tr').id);
            tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].category = parseInt(obj.value) > 0 ? parseInt(obj.value) : 1;
            var regExp = /[a-zA-Z]/g; //expresión regular
            if (regExp.test(tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].id) == false) {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ACTUALIZAR';
            } else {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'GUARDAR';
            }
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar cambiar la categoría del adjunto, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }

        if (obj.value == 2) { // factura
            obj.closest('tr').querySelector("button[name='btnVincularFacturaRequerimientoPago']").removeAttribute('disabled');
        } else {
            obj.closest('tr').querySelector("button[name='btnVincularFacturaRequerimientoPago']").setAttribute('disabled', true);
        }
    }
    actualizarFechaEmisionAdjunto(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.closest('tr').id);
            tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].fecha_emision = obj.value;

            if (facturaList.length > 0) {
                let indiceFac = facturaList.findIndex(elemnt => elemnt.id_adjunto == obj.closest('tr').id);
                facturaList[indiceFac].fecha_emision = obj.value;
                // console.log(facturaList);
            }

            // console.log(tempArchivoAdjuntoRequerimientoPagoCabeceraList);
            var regExp = /[a-zA-Z]/g; //expresión regular
            if (regExp.test(tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].id) == false) {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ACTUALIZAR';
            } else {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'GUARDAR';
            }
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar cambiar la fecha emision del adjunto, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }
    }
    actualizarSerieComprobanteDeAdjunto(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.closest('tr').id);
            tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].serie = obj.value;

            if (facturaList.length > 0) {
                let indiceFac = facturaList.findIndex(elemnt => elemnt.id_adjunto == obj.closest('tr').id);
                facturaList[indiceFac].serie = obj.value;
                console.log(facturaList);
            }

            var regExp = /[a-zA-Z]/g; //expresión regular
            if (regExp.test(tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].id) == false) {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ACTUALIZAR';
            } else {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'GUARDAR';
            }
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar cambiar la serie del comprobante, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }
    }
    actualizarNumeroComprobanteDeAdjunto(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.closest('tr').id);
            tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].numero = obj.value;

            if (facturaList.length > 0) {
                let indiceFac = facturaList.findIndex(elemnt => elemnt.id_adjunto == obj.closest('tr').id);
                facturaList[indiceFac].numero = obj.value;
                // console.log(facturaList);
            }


            var regExp = /[a-zA-Z]/g; //expresión regular
            if (regExp.test(tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].id) == false) {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ACTUALIZAR';
            } else {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'GUARDAR';
            }
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar cambiar el número del comprobante, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }
    }
    actualizarMontoTotalDeAdjunto(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.closest('tr').id);
            tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].monto_total = obj.value;
            var regExp = /[a-zA-Z]/g; //expresión regular
            if (regExp.test(tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].id) == false) {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ACTUALIZAR';
            } else {
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'GUARDAR';
            }
        } else {
            Swal.fire(
                '',
                'Hubo un error inesperado al intentar cambiar el monto, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                'error'
            );
        }
    }

    guardarRequerimientoPago() {
        if (this.validarFormularioRequerimientoPago()) {
            let formData = new FormData($('#form-requerimiento-pago')[0]);

            if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
                formData.append(`archivoAdjuntoRequerimientoPagoObject`, JSON.stringify(tempArchivoAdjuntoRequerimientoPagoCabeceraList));

                tempArchivoAdjuntoRequerimientoPagoCabeceraList.forEach(element => {
                    formData.append(`archivo_adjunto_list[]`, element.file);
                });

                if (facturaList.length > 0) { // agregar a formdata si existe lista facturas
                    formData.append(`facturaObject`, JSON.stringify(facturaList));
                };
                // tempArchivoAdjuntoRequerimientoPagoCabeceraList.forEach(element => {
                //     formData.append(`archivoAdjuntoRequerimientoPagoCabeceraFile${element.category}[]`, element.file);
                //     formData.append(`id_adjunto[]`, element.id);
                //     formData.append(`fecha_emision_adjunto[]`, element.fecha_emision);
                //     formData.append(`categoria_adjunto[]`, element.category);
                //     formData.append(`archivo_adjunto[]`, element.file);
                //     // formData.append(`archivoAdjuntoRequerimientoCabeceraFileGuardar${element.category}[]`, element.file);
                //     formData.append(`nombre_real_adjunto[]`, element.nameFile);
                //     formData.append(`action[]`, 'GUARDAR');
                // });
            }
            if (tempArchivoAdjuntoRequerimientoPagoDetalleList.length > 0) {
                formData.append(`archivoAdjuntoRequerimientoPagoDetalleObject`, JSON.stringify(tempArchivoAdjuntoRequerimientoPagoDetalleList));

                tempArchivoAdjuntoRequerimientoPagoDetalleList.forEach(element => {
                    formData.append(`archivo_adjunto_detalle_list[]`, element.file);
                });
                // tempArchivoAdjuntoRequerimientoPagoDetalleList.forEach(element => {
                //     formData.append(`archivoAdjuntoRequerimientoPagoDetalle${element.id}[]`, element.file);
                // });
            }

            $.ajax({
                type: 'POST',
                url: 'guardar-requerimiento-pago',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {

                    var customElement = $("<div>", {
                        "css": {
                            "font-size": "24px",
                            "text-align": "center",
                            "padding": "0px",
                            "margin-top": "-400px"
                        },
                        "class": "your-custom-class",
                        "text": "Guardando requerimiento de pago..."
                    });

                    $('#wrapper-okc').LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        custom: customElement,
                        imageColor: "#3c8dbc"
                    });
                },
                success: (response) => {
                    // console.log(response);
                    if (response.id_requerimiento_pago > 0) {
                        $('#wrapper-okc').LoadingOverlay("hide", true);

                        Lobibox.notify('success', {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: response.mensaje
                        });
                        $('#modal-requerimiento-pago').modal('hide');
                        this.resetearFormularioRequerimientoPago();
                        $("#ListaRequerimientoPago").DataTable().ajax.reload(null, false);

                    } else {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        Swal.fire(
                            '',
                            response.mensaje,
                            'error'
                        );
                    }

                    if (response.memsaje_creacion_estado_trazabilidad != '') {

                        Lobibox.notify('info', {
                            title: false,
                            size: 'normal',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: response.memsaje_creacion_estado_trazabilidad
                        });
                    }
                },
                statusCode: {
                    404: function () {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        Swal.fire(
                            'Error 404',
                            'Lo sentimos hubo un problema con el servidor, la ruta a la que se quiere acceder para guardar no esta disponible, por favor vuelva a intentarlo más tarde.',
                            'error'
                        );
                    }
                },
                fail: (jqXHR, textStatus, errorThrown) => {
                    $('#wrapper-okc').LoadingOverlay("hide", true);
                    Swal.fire(
                        '',
                        'Lo sentimos hubo un error en el servidor al intentar guardar el requerimiento de pago, por favor vuelva a intentarlo',
                        'error'
                    );
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

        } else {

            Lobibox.notify('warning', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: 'Por favor ingrese los datos faltantes en el formulario'
            });

        }
    }

    existeVinculoConCDPConEstadoDeEnvio(){
        let cantidadDeEstadoEnvioAsignados=0;
        if(document.querySelector("tbody[id='body_cdp_vinculados']").childElementCount >0){
           let fila = document.querySelector("tbody[id='body_cdp_vinculados']").children;
            for (var i = 0; i < fila.length; i++) {
                if(parseInt(fila[i].querySelector("select[name='id_estado_envio[]']").value) >0){
                    cantidadDeEstadoEnvioAsignados++;
                }
            };
        }

        return (cantidadDeEstadoEnvioAsignados >0?true:false);
    }


    actualizarRequerimientoPago() {


        if (document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_requerimiento_pago']").value > 0) {

            if(this.existeVinculoConCDPConEstadoDeEnvio()){
                Swal.fire({
                    title: `Desea que los CDP vinculados se cree estados en trazabilidad?`,
                    text: "Si ignora esta accion, no se creara estados en trazabilidad en despachos externos",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Si, crear'

                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector("input[name='al_actualizar_crear_estados_trazabilidad']").value='SI';
                        this.accionActualizar();
                    }else{
                        document.querySelector("input[name='al_actualizar_crear_estados_trazabilidad']").value='NO';
                        this.accionActualizar();
                    }
                });
            }else{
                this.accionActualizar();
            }


        } else {
            Swal.fire(
                '',
                'No se encontro un requerimiento de pago seleccionado, vuelva a intentarlo seleccionado un requerimiento de pago editable de la lista',
                'warning'
            );
        }
    }

    accionActualizar(){
        if (this.validarFormularioRequerimientoPago()) {

            let formData = new FormData($('#form-requerimiento-pago')[0]);

            if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
                formData.append(`archivoAdjuntoRequerimientoPagoObject`, JSON.stringify(tempArchivoAdjuntoRequerimientoPagoCabeceraList));

                tempArchivoAdjuntoRequerimientoPagoCabeceraList.forEach(element => {
                    formData.append(`archivo_adjunto_list[]`, element.file);
                });
            }

            if (facturaList.length > 0) { // agregar a formdata si existe lista facturas
                formData.append(`facturaObject`, JSON.stringify(facturaList));
            };

            if (tempArchivoAdjuntoRequerimientoPagoDetalleList.length > 0) {
                formData.append(`archivoAdjuntoRequerimientoPagoDetalleObject`, JSON.stringify(tempArchivoAdjuntoRequerimientoPagoDetalleList));

                tempArchivoAdjuntoRequerimientoPagoDetalleList.forEach(element => {
                    formData.append(`archivo_adjunto_detalle_list[]`, element.file);
                });
            }

            $.ajax({
                type: 'POST',
                url: 'actualizar-requerimiento-pago',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {

                    var customElement = $("<div>", {
                        "css": {
                            "font-size": "24px",
                            "text-align": "center",
                            "padding": "0px",
                            "margin-top": "-400px"
                        },
                        "class": "your-custom-class",
                        "text": "Actualizando requerimiento de pago..."
                    });

                    $('#wrapper-okc').LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        custom: customElement,
                        imageColor: "#3c8dbc"
                    });
                },
                success: (response) => {
                    // console.log(response);
                    if (response.id_requerimiento_pago > 0) {
                        $('#wrapper-okc').LoadingOverlay("hide", true);

                        $('#modal-requerimiento-pago').modal('hide');

                        Lobibox.notify('success', {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: response.mensaje
                        });
                        $("#ListaRequerimientoPago").DataTable().ajax.reload(null, false);


                        if (response.memsaje_creacion_estado_trazabilidad != '') {

                            Lobibox.alert('info', {
                                title: 'Información',
                                size: 'normal',
                                rounded: true,
                                sound: false,
                                delayIndicator: false,
                                msg: response.memsaje_creacion_estado_trazabilidad
                            });
                        }

                    } else {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        Swal.fire(
                            '',
                            response.mensaje,
                            'error'
                        );
                    }
                },
                statusCode: {
                    404: function () {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        Swal.fire(
                            'Error 404',
                            'Lo sentimos hubo un problema con el servidor, la ruta a la que se quiere acceder para actualizar no esta disponible, por favor vuelva a intentarlo más tarde.',
                            'error'
                        );
                    }
                },
                fail: (jqXHR, textStatus, errorThrown) => {
                    $('#wrapper-okc').LoadingOverlay("hide", true);
                    Swal.fire(
                        '',
                        'Lo sentimos hubo un error en el servidor al intentar actualizar el requerimiento de pago, por favor vuelva a intentarlo',
                        'error'
                    );
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

        } else {
            Lobibox.notify('warning', {
                title: false,
                size: 'mini',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: 'Por favor ingrese los datos faltantes en el formulario'
            });
        }
    }

    // modalListaCuadroDePresupuesto() {
    //     $('#modal-lista-cuadro-presupuesto').modal({
    //         show: true
    //     });
    //     this.listarCuadroPresupuesto();

    // }

    // listarCuadroPresupuesto() {
    //     var vardataTables = funcDatatables();
    //     $tablaListaCuadroPresupuesto = $('#listaCuadroPresupuesto').DataTable({
    //         'dom': vardataTables[1],
    //         'buttons': [],
    //         'language': vardataTables[0],
    //         'order': [[7, 'desc']],
    //         'bLengthChange': false,
    //         'serverSide': true,
    //         'destroy': true,
    //         'ajax': {
    //             'url': 'lista-cuadro-presupuesto',
    //             'type': 'POST',
    //             beforeSend: data => {

    //                 $("#listaCuadroPresupuesto").LoadingOverlay("show", {
    //                     imageAutoResize: true,
    //                     progress: true,
    //                     imageColor: "#3c8dbc"
    //                 });
    //             },

    //         },
    //         'columns': [
    //             { 'data': 'codigo_oportunidad', 'name': 'cc_view.codigo_oportunidad', 'className': 'text-center' },
    //             { 'data': 'descripcion_oportunidad', 'name': 'cc_view.descripcion_oportunidad', 'className': 'text-left' },
    //             { 'data': 'fecha_creacion', 'name': 'cc_view.fecha_creacion', 'className': 'text-center' },
    //             { 'data': 'fecha_limite', 'name': 'cc_view.fecha_limite', 'className': 'text-center' },
    //             { 'data': 'nombre_entidad', 'name': 'cc_view.nombre_entidad', 'className': 'text-left' },
    //             { 'data': 'name', 'name': 'cc_view.name', 'className': 'text-center' },
    //             { 'data': 'estado_aprobacion', 'name': 'cc_view.estado_aprobacion', 'className': 'text-center' },
    //             { 'data': 'id', 'name': 'cc_view.id', }
    //         ],
    //         'columnDefs': [


    //             {
    //                 'render': function (data, type, row) {
    //                     let containerOpenBrackets = '<center><div class="btn-group" role="group" style="margin-bottom: 5px;">';
    //                     let containerCloseBrackets = '</div></center>';
    //                     let btnSeleccionar = `<button type="button" class="btn btn-xs btn-success handleClickSeleccionarCDP"  data-id-cc="${row.id}"  data-codigo-oportunidad="${row.codigo_oportunidad}" title="Seleccionar">Seleccionar</button>`;
    //                     return containerOpenBrackets + btnSeleccionar + containerCloseBrackets;
    //                 }, targets: 7
    //             },

    //         ],
    //         'initComplete': function () {
    //             // that.updateContadorFiltroRequerimientosElaborados();

    //             //Boton de busqueda
    //             const $filter = $('#listaCuadroPresupuesto_filter');
    //             const $input = $filter.find('input');
    //             $filter.append('<button id="btnBuscarCDP" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
    //             $input.off();
    //             $input.on('keyup', (e) => {
    //                 if (e.key == 'Enter') {
    //                     $('#btnBuscarCDP').trigger('click');
    //                 }
    //             });
    //             $('#btnBuscarCDP').on('click', (e) => {
    //                 $tablaListaCuadroPresupuesto.search($input.val()).draw();
    //             })
    //             //Fin boton de busqueda

    //         },
    //         "drawCallback": function (settings) {
    //             if ($tablaListaCuadroPresupuesto.rows().data().length == 0) {
    //                 Lobibox.notify('info', {
    //                     title: false,
    //                     size: 'mini',
    //                     rounded: true,
    //                     sound: false,
    //                     delayIndicator: false,
    //                     msg: `No se encontro data disponible para mostrar`
    //                 });
    //             }
    //             //Botón de búsqueda
    //             $('#listaCuadroPresupuesto_filter input').prop('disabled', false);
    //             $('#btnBuscarCDP').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
    //             $('#listaCuadroPresupuesto_filter input').trigger('focus');
    //             //fin botón búsqueda
    //             $("#listaCuadroPresupuesto").LoadingOverlay("hide", true);
    //         }
    //     });
    //     //Desactiva el buscador del DataTable al realizar una busqueda
    //     $tablaListaCuadroPresupuesto.on('search.dt', function () {
    //         $('#tableDatos_filter input').prop('disabled', true);
    //         $('#btnBuscarCDP').html('<span class="glyphicon glyphicon-time" aria-hidden="true"></span>').attr('disabled', true);
    //     });

    // }


    // seleccionarCDP(obj) {

    //     if (obj.dataset.idCc > 0) {
    //         document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_cc']").value = obj.dataset.idCc;
    //         document.querySelector("div[id='modal-requerimiento-pago'] input[name='codigo_oportunidad']").value = obj.dataset.codigoOportunidad;
    //     } else {
    //         Swal.fire(
    //             '',
    //             'Lo sentimos hubo un error al intentar obtener el id del cuadro de presupuesto, por favor vuelva a intentarlo',
    //             'error'
    //         );
    //     }
    //     $('#modal-lista-cuadro-presupuesto').modal('hide');
    // }

    cargarDataRequerimientoPago(idRequerimientoPago) {
        if (idRequerimientoPago > 0) {
            $('#modal-vista-rapida-requerimiento-pago .modal-content').LoadingOverlay("show", {
                imageAutoResize: true,
                progress: true,
                imageColor: "#3c8dbc"
            });
            this.obtenerRequerimientoPago(idRequerimientoPago).then((res) => {
                $('#modal-vista-rapida-requerimiento-pago .modal-content').LoadingOverlay("hide", true);

                this.mostrarDataEnVistaRapidaRequerimientoPago(res)

            }).catch(function (err) {
                $('#modal-vista-rapida-requerimiento-pago .modal-content').LoadingOverlay("hide", true);
                console.log(err)
                Swal.fire(
                    '',
                    'Hubo un error al tratar de obtener la data',
                    'error'
                );
            });
        } else {
            Swal.fire(
                '',
                'Lo sentimos no se encontro un ID valido para cargar el requerimiento de pago seleccionado, por favor vuelva a intentarlo',
                'error'
            );
        }
    }
    limpiarVistaRapidaRequerimientoPago() {
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_requerimiento_pago']").value = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_estado']").value = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_usuario']").value = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='codigo']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='concepto']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='razon_social_empresa']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='grupo_division']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='tipo_requerimiento']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='prioridad']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='fecha_registro']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='creado_por']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='solicitado_por']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='periodo']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='tipo_destinatario']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='destinatario']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='banco']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='tipo_cuenta']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='moneda']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='nro_cuenta']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='nro_cci']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='comentario']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] span[name='simboloMoneda']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='listaDetalleRequerimientoPago'] span[name='simbolo_moneda']").textContent = '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='listaDetalleRequerimientoPago'] label[name='total']").textContent = '';
        document.querySelector("td[id='adjuntosRequerimientoPago']").innerHTML = '';
        this.limpiarTabla('listaDetalleRequerimientoPago');
        this.limpiarTabla('listaHistorialRevision');
        let allMesPpto=document.querySelectorAll("span[name='mes_ppto']");
        allMesPpto.forEach(element => {
                 element.textContent = "";
        });

    }

    verEnVistaRapidaRequerimientoPago(obj) {
        $('#modal-vista-rapida-requerimiento-pago').modal({
            show: true
        });
        this.limpiarVistaRapidaRequerimientoPago();
        this.cargarDataRequerimientoPago(obj.dataset.idRequerimientoPago);
    }

    mostrarDataEnVistaRapidaRequerimientoPago(data) {
        // console.log(data);
        // ### ==================== cabecera ====================== ###
        var destinatario, nro_documento_destinatario, tipo_documento_destinatario, banco, tipo_cuenta, tipo_cuenta, moneda, nro_cuenta, nro_cci = '';
        if (data.id_tipo_destinatario == 1 || data.id_persona > 0) {
            destinatario = data.persona != null ? ((data.persona.nombres).concat(' ', data.persona.apellido_paterno).concat(' ', data.persona.apellido_materno)) : '';
            tipo_documento_destinatario = data.persona != null ? (data.persona.tipo_documento_identidad != null ? data.persona.tipo_documento_identidad.descripcion : '') : '';
            nro_documento_destinatario = data.persona != null ? data.persona.nro_documento : '';
            banco = data.cuenta_persona != null ? (data.cuenta_persona.banco != null && data.cuenta_persona.banco.contribuyente != null ? data.cuenta_persona.banco.contribuyente.razon_social : '') : '';
            tipo_cuenta = data.cuenta_persona != null ? (data.cuenta_persona.tipo_cuenta != null ? data.cuenta_persona.tipo_cuenta.descripcion : '') : '';
            moneda = data.cuenta_persona != null ? (data.cuenta_persona.moneda != null ? data.cuenta_persona.moneda.descripcion : '') : '';
            nro_cuenta = data.cuenta_persona != null ? data.cuenta_persona.nro_cuenta : '';
            nro_cci = data.cuenta_persona != null ? data.cuenta_persona.nro_cci : '';
        } else if (data.id_tipo_destinatario == 2 || data.id_contribuyente > 0) {
            destinatario = data.contribuyente != null ? data.contribuyente.razon_social : '';
            tipo_documento_destinatario = data.contribuyente != null ? (data.contribuyente.tipo_documento_identidad != null ? data.contribuyente.tipo_documento_identidad.descripcion : '') : '';
            nro_documento_destinatario = data.contribuyente != null ? data.contribuyente.nro_documento : '';
            banco = data.cuenta_contribuyente != null ? (data.cuenta_contribuyente.banco != null && data.cuenta_contribuyente.banco.contribuyente != null ? data.cuenta_contribuyente.banco.contribuyente.razon_social : '') : '';
            tipo_cuenta = data.cuenta_contribuyente != null ? (data.cuenta_contribuyente.tipo_cuenta != null ? data.cuenta_contribuyente.tipo_cuenta.descripcion : '') : '';
            moneda = data.cuenta_contribuyente != null ? (data.cuenta_contribuyente.moneda != null ? data.cuenta_contribuyente.moneda.descripcion : '') : '';;
            nro_cuenta = data.cuenta_contribuyente != null ? data.cuenta_contribuyente.nro_cuenta : '';
            nro_cci = data.cuenta_contribuyente != null ? data.cuenta_contribuyente.nro_cuenta_interbancaria : '';
        }
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_requerimiento_pago']").value = data.id_requerimiento_pago;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_estado']").value = data.id_estado;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_usuario']").value = data.id_usuario;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] input[name='id_moneda']").value = data.moneda != null && data.moneda.id_moneda != undefined ? data.moneda.id_moneda : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='codigo']").textContent = data.codigo;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='concepto']").textContent = data.concepto;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='razon_social_empresa']").textContent = data.sede != null ? data.sede.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='grupo_division']").textContent = (data.grupo != null && data.grupo.descripcion != undefined ? data.grupo.descripcion : '') + (data.grupo != null && data.division != null ? '/' : '') + (data.division != null && data.division.descripcion != undefined ? data.division.descripcion : '');
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='tipo_requerimiento']").textContent = data.tipo_requerimiento_pago != null && data.tipo_requerimiento_pago.descripcion != undefined ? data.tipo_requerimiento_pago.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='prioridad']").textContent = data.prioridad != null && data.prioridad.descripcion != undefined ? data.prioridad.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='fecha_registro']").textContent = data.fecha_registro;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='creado_por']").textContent = data.creado_por != null && data.creado_por.nombre_corto != undefined ? data.creado_por.nombre_corto : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='solicitado_por']").textContent = data.nombre_trabajador != null && data.nombre_trabajador != undefined ? data.nombre_trabajador : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='periodo']").textContent = data.periodo != null && data.periodo.descripcion != undefined ? data.periodo.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='comentario']").textContent = data.comentario;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='tipo_impuesto']").textContent = data.tipo_impuesto == 1 ? 'Detracción' : data.tipo_impuesto == 2 ? 'Renta' : 'No aplica';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='tipo_destinatario']").textContent = data.tipo_destinatario != null ? data.tipo_destinatario.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='destinatario']").textContent = destinatario;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='tipo_documento_destinatario']").textContent = tipo_documento_destinatario;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='nro_documento_destinatario']").textContent = nro_documento_destinatario;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='banco']").textContent = banco;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='tipo_cuenta']").textContent = tipo_cuenta;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='moneda']").textContent = data.moneda != null && data.moneda.descripcion != undefined ? data.moneda.descripcion : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='nro_cuenta']").textContent = nro_cuenta;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosDestinatario'] td[id='nro_cci']").textContent = nro_cci;
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] span[name='simboloMoneda']").textContent = data.moneda != null && data.moneda.simbolo != undefined ? data.moneda.simbolo : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='listaDetalleRequerimientoPago'] span[name='simbolo_moneda']").textContent = data.moneda != null && data.moneda.simbolo != undefined ? data.moneda.simbolo : '';
        document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='listaDetalleRequerimientoPago'] label[name='total']").textContent = $.number(data.monto_total, 2);

        let allMesPpto=document.querySelectorAll("span[name='mes_ppto']");

        if(data.mes_afectacion!=null){
            allMesPpto.forEach(element => {
                element.textContent = moment(data.mes_afectacion, 'MM').format('MMMM');
            });

        }else{
            allMesPpto.forEach(element => {
                if(data && data.fecha_registro !=null){
                    element.textContent = moment(data.fecha_registro, 'DD-MM-YYYY').format('MMMM');
                }else{
                    element.textContent =  moment().format('MMMM');

                }
            });

        }



        if (data.id_presupuesto_interno > 0) {
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='presupuesto_interno']").textContent = (data.presupuesto_interno != null ? data.presupuesto_interno.codigo : '') + ' - ' + (data.presupuesto_interno != null ? data.presupuesto_interno.descripcion : '');
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_interno']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_interno']").classList.add("oculto");

        }
        if (data.cdp_requerimiento.length > 0) {
            let codigosOportunidad = [];
            (data.cdp_requerimiento).forEach(element => {
                codigosOportunidad.push(element.codigo_oportunidad);
            });
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='codigo_cdp']").textContent = codigosOportunidad.toString() ?? '';
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_cdp']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_cdp']").classList.add("oculto");

        }
        if (data.id_proyecto > 0) {
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] td[id='proyecto_presupuesto']").textContent = data.proyecto.descripcion ?? '';
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_proyecto']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-vista-rapida-requerimiento-pago'] table[id='tablaDatosGenerales'] tr[id='contenedor_proyecto']").classList.add("oculto");

        }


        if (data.adjunto.length > 0) {
            document.querySelector("td[id='adjuntosRequerimientoPago']").innerHTML = `<a title="Ver archivos adjuntos de requerimiento pago" style="cursor:pointer;" data-tipo-modal="lectura" class="handleClickAdjuntarArchivoCabecera"  data-id-requerimiento-pago="${data.id_requerimiento_pago}">
            Ver (<span>${(data.adjunto).filter((element, i) => element.id_estado != 7).length}</span>)
            </a>`;
        }


        // ### ==================== Detalle ====================== ###

        this.limpiarTabla('listaDetalleRequerimientoPago');
        if (data.detalle.length > 0) {
            // console.log(data.detalle);
            for (let i = 0; i < data.detalle.length; i++) {
                let cantidadAdjuntosItem = 0;
                cantidadAdjuntosItem = (data.detalle[i].adjunto).filter((element, i) => element.id_estado != 7).length;
                // console.log(cantidadAdjuntosItem);
                // cantidadAdjuntosItem = data.detalle[i].adjunto.length;

                document.querySelector("tbody[id='body_requerimiento_pago_detalle_vista']").insertAdjacentHTML('beforeend', `<tr style="background-color:${data.detalle[i].id_estado == '7' ? '#f1d7d7' : ''}">
                <td>
                    <p class="descripcion-partida"
                        data-tipo-presupuesto="${data.detalle[i].id_partida_pi > 0 ? 'INTERNO' : (data.detalle[i].id_partida > 0 ? 'ANTIGUO' : '')}"
                        data-id-partida="${data.detalle[i].id_partida != null ? data.detalle[i].id_partida : data.detalle[i].id_partida_pi}"
                        data-presupuesto-total="${data.detalle[i].presupuesto_interno_total_partida}"
                        data-presupuesto-mes="${data.detalle[i].presupuesto_interno_mes_partida}"
                        data-total-saldo-mes="${data.detalle[i].presupuesto_interno_saldo_mes_disponible_partida}"
                        data-total-por-consumido-con-igv-fase-aprobacion="${data.detalle[i].total_consumido_hasta_fase_aprobacion_con_igv !=null? data.detalle[i].total_consumido_hasta_fase_aprobacion_con_igv : 0}"
                        title="${data.detalle[i].presupuesto_interno_detalle != null ? data.detalle[i].presupuesto_interno_detalle.partida : ''}";
                        style="display:none;"> ${data.detalle[i].presupuesto_interno_detalle != null ? data.detalle[i].presupuesto_interno_detalle.descripcion : ''}
                    </p>
                        ${i + 1}
                </td>
                <td title="${data.detalle[i].partida != null ? (data.detalle[i].partida.descripcion).toUpperCase() : (data.detalle[i].presupuesto_interno_detalle != null ? (data.detalle[i].presupuesto_interno_detalle.descripcion).toUpperCase() : '')}" >${data.detalle[i].partida != null ? data.detalle[i].partida.codigo : (data.detalle[i].presupuesto_interno_detalle != null ? data.detalle[i].presupuesto_interno_detalle.partida : '')}</td>
                <td title="${data.detalle[i].id_centro_costo > 0 ? (data.detalle[i].centro_costo.descripcion).toUpperCase() : ''}">${data.detalle[i].centro_costo != null ? data.detalle[i].centro_costo.codigo : ''}</td>
                <td name="descripcion_servicio">${data.detalle[i].descripcion != null ? data.detalle[i].descripcion : ''} </td>
                <td>${data.detalle[i].unidad_medida != null ? data.detalle[i].unidad_medida.descripcion : ''}</td>
                <td style="text-align:center;">
                    <input class="form-control input-sm cantidad text-right" type="number" min="1" name="cantidad[]" value="${data.detalle[i].cantidad >= 0 ? data.detalle[i].cantidad : 0}" style="display:none;">
                    ${data.detalle[i].cantidad >= 0 ? data.detalle[i].cantidad : ''}</td>
                <td style="text-align:right;">
                    <input class="form-control input-sm precio text-right" type="number" min="1" name="precio[]" value="${data.detalle[i].precio_unitario}" style="display:none;">
                    ${data.moneda != null && data.moneda.simbolo != undefined ? data.moneda.simbolo : ''}${Util.formatoNumero(data.detalle[i].precio_unitario, 2)}</td>
                <td style="text-align:right;">${data.moneda != null && data.moneda.simbolo != undefined ? data.moneda.simbolo : ''}${(data.detalle[i].subtotal ? Util.formatoNumero(data.detalle[i].subtotal, 2) : (Util.formatoNumero((data.detalle[i].cantidad * data.detalle[i].precio_unitario), 2)))}</td>
                <td style="text-align:left;">${data.detalle[i].motivo != null ? data.detalle[i].motivo : ''}</td>
                <td style="text-align:center;">${data.detalle[i].estado != null ? data.detalle[i].estado.estado_doc : ''}</td>
                <td style="text-align: center;">
                ${cantidadAdjuntosItem > 0 ? '<a title="Ver archivos adjuntos de item" style="cursor:pointer;" class="handleClickAdjuntarArchivoDetalle" data-tipo-modal="lectura" data-id="' + data.detalle[i].id_requerimiento_pago_detalle + '" >Ver (<span>' + cantidadAdjuntosItem + '</span>)</a>' : '-'}
                </td>
                </tr>`);



            }
            // ### ==================== Detalle ====================== ###

            // ### ==================== Historia aprobación ====================== ###
            this.limpiarTabla('listaHistorialRevision');
            data.aprobacion.forEach(element => {
                this.agregarHistorialAprobacion(element);
            });
            // ### ==================== /Historia aprobación ====================== ###

            // ### ==================== Partidas activas ====================== ###
            this.calcularPresupuestoUtilizadoYSaldoPorPartida();
            // ### ==================== /Partidas activas ====================== ###
            this.llenar_tabla_flujo_aprobacion(data.flujo_aprobacion ?? []);

        }

    }

    llenar_tabla_flujo_aprobacion(data) {
        this.limpiarTabla('listaFlujoAprobacion');
        let html = '';
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                html += `<tr>
                    <td style="text-align:center;">${data[i].orden ? data[i].orden : ''}</td>
                    <td style="text-align:center;">${data[i].rol ? data[i].rol.descripcion : ''}</td>
                    <td style="text-align:left;">${data[i].nombre_usuarios ? data[i].nombre_usuarios.toString() : ''}</td>
                    <td style="text-align:center;">${data[i].aprobar_sin_respetar_orden == 'true' ? 'SI' : 'NO'}</td>
                </tr>`;
            }
        }
        document.querySelector("tbody[id='body_flujo_aprobacion']").insertAdjacentHTML('beforeend', html)
    }

    editarRequerimientoPago(obj) {

        let idRequerimientoPago = obj.dataset.idRequerimientoPago;

        this.resetearFormularioRequerimientoPago();
        $('#modal-requerimiento-pago').modal({
            show: true
        });
        document.querySelector("div[id='modal-requerimiento-pago'] form[id='form-requerimiento-pago']").setAttribute("type", 'edition');
        document.querySelector("div[id='modal-requerimiento-pago'] span[id='titulo-modal']").textContent = "Editar requerimiento de pago";
        document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnGuardarRequerimientoPago']").classList.add("oculto");
        document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnActualizarRequerimientoPago']").classList.remove("oculto");
        document.querySelector("div[id='modal-requerimiento-pago'] button[id='btnActualizarRequerimientoPago']").removeAttribute("disabled");

        if (idRequerimientoPago > 0) {
            this.cargarRequerimientoPago(idRequerimientoPago);
        } else {
            Swal.fire(
                '',
                'Lo sentimos no se encontro un ID valido para cargar el requerimiento de pago seleccionado, por favor vuelva a intentarlo',
                'error'
            );
        }
    }

    anularRequerimientoPago(obj) {
        let idRequerimientoPago = obj.dataset.idRequerimientoPago;
        let codigoRequerimientoPago = obj.dataset.codigoRequerimientoPago;
        if (parseInt(idRequerimientoPago) > 0) {
            Swal.fire({
                title: `Esta seguro que desea anular el requerimiento ${codigoRequerimientoPago.length > 0 ? codigoRequerimientoPago : ''}?`,
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'cancelar',
                confirmButtonText: 'Si, anular'

            }).then((result) => {
                if (result.isConfirmed) {

                    this.realizarAnulacionRequerimientoPago(idRequerimientoPago).then((response) => {
                        $("#wrapper-okc").LoadingOverlay("hide", true);

                        if (response.status == 200) {
                            $('#modal-vista-rapida-requerimiento-pago').modal('hide');

                            Lobibox.notify('success', {
                                title: false,
                                size: 'mini',
                                rounded: true,
                                sound: false,
                                delayIndicator: false,
                                msg: response.mensaje
                            });

                            $("#ListaRequerimientoPago").DataTable().ajax.reload(null, false);

                        } else {
                            $("#wrapper-okc").LoadingOverlay("hide", true);

                            Swal.fire(
                                '',
                                response.mensaje,
                                response.tipo_estado
                            );

                        }
                    }).catch((err) => {
                        $("#wrapper-okc").LoadingOverlay("hide", true);
                        console.log(err)
                        Swal.fire(
                            '',
                            'Lo sentimos hubo un error en el servidor, por favor vuelva a intentarlo',
                            'error'
                        );
                    });
                }
            })
        } else {
            Swal.fire(
                '',
                'Lo sentimos no se encontro un ID valido para cargar el requerimiento de pago seleccionado, por favor vuelva a intentarlo',
                'error'
            );
        }
    }
    realizarAnulacionRequerimientoPago(id) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'POST',
                url: `anular-requerimiento-pago`,
                data: { 'idRequerimientoPago': id },
                dataType: 'JSON',
                beforeSend: data => {

                    $("#wrapper-okc").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                success(response) {
                    resolve(response);
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    cargarRequerimientoPago(idRequerimientoPago) {
        this.obtenerRequerimientoPago(idRequerimientoPago).then((res) => {
            this.mostrarRequerimientoPago(res);

        }).catch(function (err) {
            console.log(err)
        });
    }

    obtenerRequerimientoPago(id) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `mostrar-requerimiento-pago/${id}`,
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

    mostrarRequerimientoPago(data) {
        if (data.id_empresa > 0) {
            this.construirOptSelectSede(data.id_empresa, data.id_sede);
        }
        if (data.id_grupo > 0) {
            this.construirOptSelectDivision(data.id_grupo, data.id_division);
        }

        if (data.id_grupo == 2) {
            document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-cdp']").classList.remove("oculto");

        }

        document.querySelector("div[id='modal-requerimiento-pago'] select[name='sede']").removeAttribute("disabled");
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='grupo']").removeAttribute("disabled");
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']").removeAttribute("disabled");

        const fechaRegistro = moment(data.fecha_registro, 'DD-MM-YYYY').format('YYYY-MM-DD');
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_requerimiento_pago']").value = data.id_requerimiento_pago;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='tipo_requerimiento_pago']").value = data.id_requerimiento_pago_tipo;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_usuario']").value = data.id_usuario;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_estado']").value = data.id_estado;
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='codigo']").value = data.codigo;
        document.querySelector("div[id='modal-requerimiento-pago'] span[name='codigo']").textContent = data.codigo;

        document.querySelector("div[id='modal-requerimiento-pago'] input[name='concepto']").value = data.concepto;

        this.mostrarHistorialRevisionAprobacion(data.cdp_requerimiento);
        // if (data.id_proyecto > 0) {
        //     document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-proyecto']").classList.remove("oculto");
        // } else {
        //     document.querySelector("div[id='modal-requerimiento-pago'] div[id='contenedor-proyecto']").classList.add("oculto");

        // }

        $("select[name='id_presupuesto_interno']").val(data.id_presupuesto_interno);

        if(data.mes_afectacion!=null){
            this.habilitarInputMesAfectacion(true);
            $("select[name='mes_afectacion']").val(data.mes_afectacion);

        }else{
            this.habilitarInputMesAfectacion(false);
            $("select[name='mes_afectacion']").val('');
        }

        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_trabajador']").value = data.id_trabajador;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_trabajador']").value = data.nombre_trabajador;

        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='fecha_registro']").value = fechaRegistro;
        document.querySelector("div[id='modal-requerimiento-pago'] span[name='fecha_registro']").textContent = 'Fecha registro: ' + fechaRegistro;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = data.id_contribuyente != null || data.id_contribuyente != '' ? data.id_contribuyente : '';
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = data.id_persona != null || data.id_persona != '' ? data.id_persona : '';
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_tipo_destinatario']").value = data.id_tipo_destinatario;

        if (data.id_tipo_destinatario == 1 && data.id_persona > 0) {
            // console.log(data);
            document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = data.persona != null && data.persona.tipo_documento_identidad != null ? data.persona.tipo_documento_identidad.descripcion : '';
            document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_documento']").value = data.persona != null && data.persona.nro_documento != null ? data.persona.nro_documento : '';
            document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = data.persona != null && data.persona.nombres != null ? ((data.persona.nombres).concat(' ', data.persona.apellido_paterno).concat(' ', data.persona.apellido_materno)) : '';

            obtenerCuentasBancariasPersona(data.id_persona, (data.id_cuenta_persona != null ? data.id_cuenta_persona : null));

        }
        if (data.id_tipo_destinatario == 2 && data.id_contribuyente > 0) {

            document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = data.contribuyente != null && data.contribuyente.tipo_documento_identidad != null ? data.contribuyente.tipo_documento_identidad.descripcion : '';
            document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_documento']").value = data.contribuyente != null && data.contribuyente.nro_documento != null ? data.contribuyente.nro_documento : '';
            document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = data.contribuyente != null && data.contribuyente.razon_social != null ? data.contribuyente.razon_social : '';

            obtenerCuentasBancariasContribuyente(data.id_contribuyente, (data.id_cuenta_contribuyente != null ? data.id_cuenta_contribuyente : null),false);
        }

        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = data.proveedor != null ? data.proveedor.razon_social : '';
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_documento']").value = data.proveedor != null ? data.proveedor.nro_documento : '';
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = data.proveedor != null ? data.proveedor.documento_identidad : '';
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_cuenta_principal_proveedor']").value = data.proveedor != null && data.proveedor.cuenta_contribuyente.length > 0 ? data.proveedor.cuenta_contribuyente[0].id_cuenta_contribuyente : '';
        // document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_cuenta_principal_proveedor']").value = data.proveedor != null && data.proveedor.cuenta_contribuyente.length > 0 ? data.proveedor.cuenta_contribuyente[0].nro_cuenta : '';
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='periodo']").value = data.id_periodo;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='moneda']").value = data.id_moneda;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='prioridad']").value = data.id_prioridad;
        document.querySelector("div[id='modal-requerimiento-pago'] textarea[name='comentario']").value = data.comentario;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='tipo_impuesto']").value = data.tipo_impuesto != null ? data.tipo_impuesto : 0;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='empresa']").value = data.id_empresa;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='sede']").value = data.id_sede;
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='grupo']").value = data.id_grupo;
        // console.log(data.id_division);
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='division']").value = data.id_division;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='monto_total']").value = data.monto_total;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='monto_total_read_only']").value = $.number(data.monto_total, 2);
        document.querySelector("div[id='modal-requerimiento-pago'] table[id='ListaDetalleRequerimientoPago'] label[name='total']").textContent = $.number(data.monto_total, 2);

        // document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_condicion_softlink']").value = data.id_condicion_softlink;
        this.presupuestoInternoView.llenarComboPresupuestoInterno(data.id_grupo, data.id_division, data.id_presupuesto_interno);

        this.llenarComboProyectos(data.id_grupo, data.id_proyecto);
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='proyecto']").value = data.id_proyecto;

        document.querySelector("input[name='id_centro_costo']").value = data.proyecto!=null?data.proyecto.id_centro_costo: '';
        document.querySelector("input[name='descripcion_centro_costo']").value = data.proyecto !=null && data.proyecto.centro_costo !=null ? (data.proyecto.centro_costo.codigo +' - '+data.proyecto.centro_costo.descripcion) : '';


        this.limpiarTabla('ListaDetalleRequerimientoPago');
        this.limpiarTabla('listaPartidasActivas');

        data.detalle.forEach(element => {
            if (element.id_estado != 7) {
                this.agregarServicio(element);
                this.calcularSubtotal();
                this.calcularTotal();
            }
        });

        // agregar data adjuntos a variable temporal
        if (data.adjunto.length > 0) {
            (data.adjunto).forEach(element => {
                if (element.id_estado != 7) { // omitir anulados
                    tempArchivoAdjuntoRequerimientoPagoCabeceraList.push(
                        {
                            'id': element.id_requerimiento_pago_adjunto,
                            'id_doc_com': element.id_doc_com,
                            'serie': element.serie,
                            'numero': element.numero,
                            'id_moneda': element.id_moneda,
                            'monto_total': element.monto_total,
                            'nameFile': element.archivo,
                            'fecha_emision': element.fecha_emision,
                            'category': element.id_tp_doc,
                            'documento_compra': element.documento_compra,
                            'action': '',
                            'file': []
                        }
                    );
                }

            });
        }
        this.updateContadorTotalAdjuntosRequerimientoPagoCabecera();
        this.calcularPresupuestoUtilizadoYSaldoPorPartida();

    }


    mostrarHistorialRevisionAprobacion(array) {
        limpiarTabla("tablaCuadroPresupuestoVinculados");
        array.forEach(element => {
            agregarEnTablaCuadroPresupuestoVinculados(element);
        });
    }

    modalVerAdjuntarArchivosCabecera(idRequerimientoPago) {
        $('#modal-ver-adjuntos-requerimiento-pago-cabecera').modal({
            show: true
        });

        if (idRequerimientoPago > 0) {
            this.getcategoriaAdjunto().then((categoriaAdjuntoList) => {
                this.getAdjuntosRequerimientoPagoCabecera(idRequerimientoPago).then((adjuntoList) => {
                    tempArchivoAdjuntoRequerimientoPagoCabeceraList = [];
                    (adjuntoList).forEach(element => {
                        tempArchivoAdjuntoRequerimientoPagoCabeceraList.push({
                            id: element.id_requerimiento_pago_adjunto,
                            serie: element.serie,
                            numero: element.numero,
                            id_moneda: element.id_moneda,
                            monto_total: element.monto_total,
                            category: element.id_tp_doc,
                            fecha_emision: element.fecha_emision,
                            nameFile: element.archivo,
                            id_doc_com: element.id_doc_com,
                            documento_compra: element.documento_compra,
                            action: '',
                            file: []
                        });
                    });
                    this.construirTablaVerAdjuntosRequerimientoPagoCabecera(tempArchivoAdjuntoRequerimientoPagoCabeceraList, categoriaAdjuntoList);
                }).catch(function (err) {
                    console.log(err)
                })
            }).catch(function (err) {
                console.log(err)
            })
        }

    }

    construirTablaVerAdjuntosRequerimientoPagoCabecera(adjuntoList, categoriaAdjuntoList) {
        this.limpiarTabla('listaVerAdjuntosRequerimientoPagoCabecera');
        let html = '';
        adjuntoList.forEach(element => {
            html += `<tr id="${element.id}" style="text-align:center">
        <td style="text-align:left;">${element.nameFile}</td>
        <td style="text-align:left;">${element.fecha_emision}</td>`;

            html += `<td style="text-align:left;"> ${element.serie ?? ''}-${element.numero ?? ''}</td>`;

            html += `<td>
            <select class="form-control handleChangeCategoriaAdjunto" name="categoriaAdjunto" disabled>
        `;
            categoriaAdjuntoList.forEach(categoria => {
                if (element.category == categoria.id_tp_doc) {
                    html += `<option value="${categoria.id_tp_doc}" selected >${categoria.descripcion}</option>`

                } else {
                    html += `<option value="${categoria.id_tp_doc}">${categoria.descripcion}</option>`
                }
            });
            html += `</select>
        </td>
        <td style="text-align:center;">
            <div class="btn-group" role="group">`;
            if (Number.isInteger(element.id)) {
                html += `<button type="button" class="btn btn-info btn-xs handleClickDescargarArchivoCabeceraRequerimientoPago" name="btnDescargarArchivoCabeceraRequerimientoPago" title="Descargar" data-id="${element.id}" ><i class="fas fa-file-download"></i></button>`;
            }
            html += `</div>
        </td>
        </tr>`;
        });
        document.querySelector("tbody[id='body_ver_adjuntos_requerimiento_pago_cabecera']").insertAdjacentHTML('beforeend', html);

    }


    modalAdjuntarArchivosCabecera(idRequerimientoPago) { // TODO pasar al btn el id y no usar de un input para ambos casos de mostrar solo lectura y mostrar con carga
        $('#modal-adjuntar-archivos-requerimiento-pago').modal({
            show: true
        });

        if (idRequerimientoPago > 0) {
            var regExp = /[a-zA-Z]/g; //expresión regular

            if (regExp.test(idRequerimientoPago) == false) {

                this.getcategoriaAdjunto().then((categoriaAdjuntoList) => {
                    this.getAdjuntosRequerimientoPagoCabecera(idRequerimientoPago).then((adjuntoList) => {
                        // tempArchivoAdjuntoRequerimientoPagoCabeceraList = []; //? vaciar variable causa que al volver ingresar al modal de ajuntos se pierda lo cargado (lo agregado sin guardar)
                        // console.log(adjuntoList);
                        (adjuntoList).forEach(element => {
                            let agregadoAlArray = 0;
                            tempArchivoAdjuntoRequerimientoPagoCabeceraList.forEach(adj => {
                                if (adj.id == element.id_requerimiento_pago_adjunto) {
                                    agregadoAlArray++;
                                }
                            });
                            if (agregadoAlArray == 0) {
                                tempArchivoAdjuntoRequerimientoPagoCabeceraList.push({
                                    id: element.id_requerimiento_pago_adjunto,
                                    serie: element.serie,
                                    numero: element.numero,
                                    id_moneda: element.id_moneda,
                                    monto_total: element.monto_total,
                                    category: element.id_tp_doc,
                                    fecha_emision: element.fecha_emision,
                                    nameFile: element.archivo,
                                    id_doc_com: element.id_doc_com,
                                    documento_compra: element.documento_compra,
                                    action: '',
                                    confirmada: '',
                                    file: []
                                });
                            }
                            $('#listaArchivosRequerimientoPagoCabecera').LoadingOverlay("hide", true);


                        });
                        // console.log(tempArchivoAdjuntoRequerimientoPagoCabeceraList);
                    }).catch(function (err) {
                        console.log(err)
                    })
                }).catch(function (err) {
                    console.log(err)
                })
            }
            this.getcategoriaAdjunto().then((categoriaAdjuntoList) => {

                this.construirTablaAdjuntosRequerimientoPagoCabecera(tempArchivoAdjuntoRequerimientoPagoCabeceraList, categoriaAdjuntoList);
            });
        }
    }



    getAdjuntosRequerimientoPagoCabecera(idRequerimientoPago) {
        $('#listaArchivosRequerimientoPagoCabecera').LoadingOverlay("show", {
            imageAutoResize: true,
            progress: true,
            imageColor: "#3c8dbc"
        });
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-adjuntos-requerimiento-pago-cabecera/${idRequerimientoPago}`,
                dataType: 'JSON',
                success(response) {
                    resolve(response);
                    $('#listaArchivosRequerimientoPagoCabecera').LoadingOverlay("hide", true);

                },
                error: function (err) {
                    reject(err)
                    $('#listaArchivosRequerimientoPagoCabecera').LoadingOverlay("hide", true);

                }
            });
        });
    }
    getcategoriaAdjunto() {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-categoria-adjunto`,
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


    construirTablaAdjuntosRequerimientoPagoCabecera(adjuntoList, categoriaAdjuntoList, tipoModal) {
        // console.log(adjuntoList,categoriaAdjuntoList);
        this.limpiarTabla('listaArchivosRequerimientoPagoCabecera');

        let html = '';
        let hasHiddenBtnEliminarArchivo = '';
        let hasDisabledSelectTipoArchivo = '';
        let estadoActual = document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_estado']").value;

        if (estadoActual == 1 || estadoActual == 3 || estadoActual == '') {
            if (document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_usuario']").value == auth_user.id_usuario) { //usuario en sesion == usuario requerimiento
                hasHiddenBtnEliminarArchivo = '';
            } else {
                hasHiddenBtnEliminarArchivo = 'oculto';
                hasDisabledSelectTipoArchivo = 'disabled';
            }
        }
        // console.log(adjuntoList);
        adjuntoList.forEach(element => {
            html += `<tr id="${element.id}" style="text-align:center">
        <td style="text-align:left;">${element.nameFile}</td>
        <td style="text-align:left;">`;
            if (element.id > 0) {
                html += `${element.fecha_emision ?? ''}`
            } else {
                html += `<input type="date" class="form-control handleChangeFechaEmision" name="fecha_emision" value="${element.fecha_emision ?? ''}" />`;
            }
            html += `</td>`;
            // console.log(element);

            html += `
        <td style="text-align:left; display:flex;" data-id-doc-com="${element.id_doc_com ?? ''}">
        <input type="text" class="form-control handleChangeSerieComprobante" name="serie"  placeholder="Serie" value="${element.serie ?? ''}"  ${element.id_doc_com > 0 ? 'disabled' : ''}>
        <input type="text" class="form-control handleChangeNumeroComprobante" name="numero"  placeholder="Número" value="${element.numero ?? ''}" ${element.id_doc_com > 0 ? 'disabled' : ''}>
        </td>
        `;

            html += `<td>
            <select class="form-control handleChangeCategoriaAdjunto" name="categoriaAdjunto" ${element.id_doc_com > 0 || element.confirmada == true ? 'disabled' : ''} >
        `;
            categoriaAdjuntoList.forEach(categoria => {
                if (element.category == categoria.id_tp_doc) {
                    html += `<option value="${categoria.id_tp_doc}" selected >${categoria.descripcion}</option>`

                } else {
                    html += `<option value="${categoria.id_tp_doc}">${categoria.descripcion}</option>`
                }
            });
            html += `</select>
        </td>
        <td style="text-align:center;">
            <div class="btn-group" role="group">`;
            if (element.id_doc_com > 0) {
                html += `<button type="button" class="btn btn-primary btn-xs handleClickVerVinculoConFactura" name="btnVerVinculoConFactura" title="Ver vínculo con factura" data-id="${element.id}" data-id-doc-com="${element.id_doc_com}" ><i class="fas fa-receipt"></i></button>`;
            } else {
                html += `<button type="button" class="btn btn-success btn-xs handleClickVincularFacturaRequerimientoPago" name="btnVincularFacturaRequerimientoPago" title="Crear factura" data-id="${element.id}" ><i class="fas fa-receipt"></i></button>`;
            }
            if (Number.isInteger(element.id)) {
                html += `<button type="button" class="btn btn-info btn-xs handleClickDescargarArchivoCabeceraRequerimientoPago" name="btnDescargarArchivoCabeceraRequerimientoPago" title="Descargar" data-id="${element.id}" ><i class="fas fa-file-download"></i></button>`;
            }
            if (tipoModal != 'lectura') {
                html += `<button type="button" class="btn btn-danger btn-xs handleClickEliminarArchivoCabeceraRequerimientoPago ${hasHiddenBtnEliminarArchivo}" name="btnEliminarArchivoRequerimientoPago" title="Eliminar" data-id="${element.id}" ><i class="fas fa-trash-alt"></i></button>`;
            }
            html += `</div>
        </td>
        </tr>`;
        });
        document.querySelector("tbody[id='body_archivos_requerimiento_pago_cabecera']").insertAdjacentHTML('beforeend', html);

    }

    descargarArchivoRequerimientoPagoCabecera(obj) {
        if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
            tempArchivoAdjuntoRequerimientoPagoCabeceraList.forEach(element => {
                if (element.id == obj.dataset.id) {
                    window.open("/files/necesidades/requerimientos/pago/cabecera/" + element.nameFile);
                }
            });
        }
    }

    eliminarArchivoRequerimientoPagoCabecera(obj) {
        obj.closest("tr").remove();
        // tempIdArchivoAdjuntoRequerimientoPagoCabeceraToDeleteList.push(obj.dataset.id);
        // tempArchivoAdjuntoRequerimientoPagoCabeceraList = tempArchivoAdjuntoRequerimientoPagoCabeceraList.filter((element, i) => element.id != obj.dataset.id);
        var regExp = /[a-zA-Z]/g; //expresión regular
        if ((regExp.test(obj.dataset.id) == true)) {

            facturaList = facturaList.filter((element, i) => element.id_adjunto != obj.dataset.id);

            tempArchivoAdjuntoRequerimientoPagoCabeceraList = tempArchivoAdjuntoRequerimientoPagoCabeceraList.filter((element, i) => element.id != obj.dataset.id);
        } else {
            if (tempArchivoAdjuntoRequerimientoPagoCabeceraList.length > 0) {
                let indice = tempArchivoAdjuntoRequerimientoPagoCabeceraList.findIndex(elemnt => elemnt.id == obj.dataset.id);
                tempArchivoAdjuntoRequerimientoPagoCabeceraList[indice].action = 'ELIMINAR';
            } else {
                Swal.fire(
                    '',
                    'Hubo un error inesperado al intentar eliminar el adjunto, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                    'error'
                );
            }

        }
        this.updateContadorTotalAdjuntosRequerimientoPagoCabecera();

    }

    estaHabilitadoLaExtension(file) {
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

    agregarAdjuntoRequerimientoPagoCabecera(obj) {
        if (obj.files != undefined && obj.files.length > 0) {
            // console.log(obj.files);

            Array.prototype.forEach.call(obj.files, (file) => {

                if (this.estaHabilitadoLaExtension(file) == true) {
                    let payload = {
                        id: this.makeId(),
                        serie: '',
                        numero: '',
                        id_moneda: document.querySelector("div[id='modal-requerimiento-pago'] select[name='moneda']").value,
                        monto_total: document.querySelector("div[id='modal-requerimiento-pago'] input[name='monto_total']").value,
                        category: 2, //default: factura
                        fecha_emision: moment().format("YYYY-MM-DD"), //default: fecha hoy
                        nameFile: file.name,
                        action: 'GUARDAR',
                        file: file
                    };

                    this.addToTablaArchivosRequerimientoPagoCabecera(payload);

                    tempArchivoAdjuntoRequerimientoPagoCabeceraList.push(payload);
                } else {
                    Swal.fire(
                        'Este tipo de archivo no esta permitido adjuntar',
                        file.name,
                        'warning'
                    );
                }
            });

            this.updateContadorTotalAdjuntosRequerimientoPagoCabecera();


        }
        return false;
    }

    updateContadorTotalAdjuntosRequerimientoPagoCabecera() {
        document.querySelector("span[name='cantidadAdjuntosCabeceraRequerimientoPago']").textContent = tempArchivoAdjuntoRequerimientoPagoCabeceraList.length;
    }


    addToTablaArchivosRequerimientoPagoCabecera(payload) {
        this.getcategoriaAdjunto().then((categoriaAdjuntoList) => {
            this.agregarRegistroEnTablaAdjuntoRequerimientoPagoCabecera(payload, categoriaAdjuntoList);

        }).catch(function (err) {
            console.log(err)
        })
    }

    agregarRegistroEnTablaAdjuntoRequerimientoPagoCabecera(payload, categoriaAdjuntoList) {
        let html = '';
        html = `<tr id="${payload.id}" style="text-align:center">
        <td style="text-align:left;">${payload.nameFile}</td>
        <td>
            <input type="date" class="form-control handleChangeFechaEmision" name="fecha_emision" value="${moment().format("YYYY-MM-DD")}" />
        </td>
        <td style="text-align:left; display:flex;">
            <input type="text" class="form-control handleChangeSerieComprobante" name="serie"  placeholder="Serie">
            <input type="text" class="form-control handleChangeNumeroComprobante" name="numero"  placeholder="Número">
        </td>
        <td>
            <select class="form-control handleChangeCategoriaAdjunto select2" name="categoriaAdjunto">
        `;
        categoriaAdjuntoList.forEach(element => {
            if (element.id_tp_doc == payload.category) {
                html += `<option value="${element.id_tp_doc}" selected>${element.descripcion}</option>`
            } else {
                html += `<option value="${element.id_tp_doc}">${element.descripcion}</option>`

            }
        });

        let selectMoneda = document.querySelector("div[id='modal-requerimiento-pago'] select[name='moneda']");

        html += `</select>
        <td style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-xs handleClickVincularFacturaRequerimientoPago" name="btnVincularFacturaRequerimientoPago" title="Crear factura" data-id="${payload.id}" ><i class="fas fa-receipt"></i></button>
                <button type="button" class="btn btn-danger btn-xs handleClickEliminarArchivoCabeceraRequerimientoPago" name="btnEliminarArchivoRequerimientoPago" title="Eliminar" data-id="${payload.id}" ><i class="fas fa-trash-alt"></i></button>

            </div>
        </td>
        </tr>`;

        document.querySelector("tbody[id='body_archivos_requerimiento_pago_cabecera']").insertAdjacentHTML('beforeend', html);

        $('.select2').select2();
    }

    modalVerAdjuntarArchivosDetalle(idRequerimientoPagoDetalle) {
        $('#modal-ver-adjuntos-requerimiento-pago-detalle').modal({
            show: true
        });
        if (idRequerimientoPagoDetalle.length > 0) {

            var regExp = /[a-zA-Z]/g; //expresión regular

            if (regExp.test(idRequerimientoPagoDetalle) == false) {
                tempArchivoAdjuntoRequerimientoPagoDetalleList = [];
                this.getAdjuntosRequerimientoPagoDetalle(idRequerimientoPagoDetalle).then((adjuntoList) => {
                    (adjuntoList).forEach(element => {
                        if (element.id_estado != 7) { // omitir anulados

                            tempArchivoAdjuntoRequerimientoPagoDetalleList.push({
                                id: element.id_requerimiento_pago_detalle_adjunto,
                                id_requerimiento_pago_detalle: element.id_requerimiento_pago_detalle,
                                nameFile: element.archivo,
                                action: '',
                                file: []
                            });
                        }
                    });
                    this.construirTablaVerAdjuntosRequerimientoPagoDetalle(tempArchivoAdjuntoRequerimientoPagoDetalleList, idRequerimientoPagoDetalle);
                }).catch(function (err) {
                    console.log(err)
                })
            }
        }

    }

    construirTablaVerAdjuntosRequerimientoPagoDetalle(adjuntoList, idRequerimientoPagoDetalle) {
        this.limpiarTabla('listaVerAdjuntosRequerimientoPagodetalle');
        let html = '';
        let hasDisableBtnEliminarArchivo = '';
        let estadoActual = document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_estado']")!=null ? document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_estado']").value:'';

        if (estadoActual == 1 || estadoActual == 3 || estadoActual == '') {
            if (document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_usuario']")!=null && document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_usuario']").value == auth_user.id_usuario) { //usuario en sesion == usuario requerimiento
                hasDisableBtnEliminarArchivo = '';
            } else {
                hasDisableBtnEliminarArchivo = 'oculto';
            }
        }
        // console.log(idRequerimientoPagoDetalle);
        // console.log(adjuntoList);
        adjuntoList.forEach(element => {
            if (idRequerimientoPagoDetalle.length > 0 && idRequerimientoPagoDetalle == element.id_requerimiento_pago_detalle) {

                html += `<tr id="${element.id}" style="text-align:center">
        <td style="text-align:left;">${element.nameFile}</td>
        <td style="text-align:center;">
            <div class="btn-group" role="group">`;
                if (Number.isInteger(element.id)) {
                    html += `<button type="button" class="btn btn-info btn-xs handleClickDescargarArchivoRequerimientoPagoDetalle" name="btnDescargarArchivoRequerimientoPagoDetalle" title="Descargar" data-id="${element.id}" ><i class="fas fa-paperclip"></i></button>`;
                }



                html += `
            </div>
        </td>
        </tr>`;
            }
        });
        document.querySelector("tbody[id='body_ver_adjuntos_requerimiento_pago_detalle']").insertAdjacentHTML('beforeend', html);

    }

    modalAdjuntarArchivosDetalle(obj) {
        $('#modal-adjuntar-archivos-requerimiento-pago-detalle').modal({
            show: true
        });
        this.limpiarTabla('listaArchivosRequerimientoPagoDetalle');

        objBotonAdjuntoRequerimientoPagoDetalleSeleccionado = obj;
        let textoDescripcion = '';

        // if (obj.dataset.tipoModal == "lectura") {
        //     document.querySelector("div[id='modal-adjuntar-archivos-requerimiento-pago-detalle'] div[id='group-action-upload-file']").classList.add("oculto");

        //     textoDescripcion = (obj.closest('tr').querySelector("td[name='descripcion_servicio']")) ? ((obj.closest('tr').querySelector("td[name='descripcion_servicio']").textContent).length > 0 ? obj.closest('tr').querySelector("td[name='descripcion_servicio']").textContent : '') : '';
        // } else {
        // document.querySelector("div[id='modal-adjuntar-archivos-requerimiento-pago-detalle'] div[id='group-action-upload-file']").classList.remove("oculto");
        textoDescripcion = (obj.closest('tr').querySelector("textarea[name='descripcion[]']")) ? ((obj.closest('tr').querySelector("textarea[name='descripcion[]']").value).length > 0 ? obj.closest('tr').querySelector("textarea[name='descripcion[]']").value : '') : '';
        // }
        document.querySelector("div[id='modal-adjuntar-archivos-requerimiento-pago-detalle'] span[id='descripcion']").textContent = textoDescripcion.length > 0 ? textoDescripcion : '';
        this.listarArchivosAdjuntosDetalle(obj.dataset.id);
    }

    listarArchivosAdjuntosDetalle(idRequerimientoPagoDetalle) {

        if (idRequerimientoPagoDetalle.length > 0) {

            var regExp = /[a-zA-Z]/g; //expresión regular

            if (regExp.test(idRequerimientoPagoDetalle) == false) {
                tempArchivoAdjuntoRequerimientoPagoDetalleList = [];
                this.getAdjuntosRequerimientoPagoDetalle(idRequerimientoPagoDetalle).then((adjuntoList) => {
                    // console.log(adjuntoList);
                    (adjuntoList).forEach(element => {
                        if (element.id_estado != 7) { // omitir anulados

                            tempArchivoAdjuntoRequerimientoPagoDetalleList.push({
                                id: element.id_requerimiento_pago_detalle_adjunto,
                                id_requerimiento_pago_detalle: element.id_requerimiento_pago_detalle,
                                nameFile: element.archivo,
                                action: '',
                                file: []
                            });
                        }
                    });
                    this.construirTablaAdjuntosRequerimientoPagoDetalle(tempArchivoAdjuntoRequerimientoPagoDetalleList, idRequerimientoPagoDetalle);
                }).catch(function (err) {
                    console.log(err)
                })
            }


        }

    }

    getAdjuntosRequerimientoPagoDetalle(idRequerimientoPagoDetalle) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-adjuntos-requerimiento-pago-detalle/${idRequerimientoPagoDetalle}`,
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

    construirTablaAdjuntosRequerimientoPagoDetalle(adjuntoList, idRequerimientoPagoDetalle = null) {
        this.limpiarTabla('listaArchivosRequerimientoPagoDetalle');

        let html = '';
        let hasDisableBtnEliminarArchivo = '';
        let estadoActual = document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_estado']").value;

        if (estadoActual == 1 || estadoActual == 3 || estadoActual == '') {
            if (document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_usuario']").value == auth_user.id_usuario) { //usuario en sesion == usuario requerimiento
                hasDisableBtnEliminarArchivo = '';
            } else {
                hasDisableBtnEliminarArchivo = 'oculto';
            }
        }
        console.log(adjuntoList);
        adjuntoList.forEach(element => {
            if (idRequerimientoPagoDetalle.length > 0 && idRequerimientoPagoDetalle == element.id_requerimiento_pago_detalle) {

                html += `<tr id="${element.id}" style="text-align:center">
        <td style="text-align:left;">${element.nameFile}</td>
        <td style="text-align:center;">
            <div class="btn-group" role="group">`;
                if (Number.isInteger(element.id)) {
                    html += `<button type="button" class="btn btn-info btn-xs handleClickDescargarArchivoRequerimientoPagoDetalle" name="btnDescargarArchivoRequerimientoPagoDetalle" title="Descargar" data-id="${element.id}" ><i class="fas fa-paperclip"></i></button>`;
                }


                html += `<button type="button" class="btn btn-danger btn-xs handleClickEliminarArchivoRequerimientoPagoDetalle ${hasDisableBtnEliminarArchivo}" name="btnEliminarArchivoRequerimientoPagoDetalle" title="Eliminar" data-id="${element.id}"  ><i class="fas fa-trash-alt"></i></button>`;

                html += `
            </div>
        </td>
        </tr>`;
            }
        });
        document.querySelector("tbody[id='body_archivos_requerimiento_pago_detalle']").insertAdjacentHTML('beforeend', html);
    }

    descargarArchivoRequerimientoPagoDetalle(obj) {
        console.log(obj);
        if (tempArchivoAdjuntoRequerimientoPagoDetalleList.length > 0) {
            tempArchivoAdjuntoRequerimientoPagoDetalleList.forEach(element => {
                if (element.id == obj.dataset.id) {
                    window.open("/files/necesidades/requerimientos/pago/detalle/" + element.nameFile);
                    console.log("descargando");
                }
            });
        }
    }

    agregarAdjuntoRequerimientoPagoDetalle(obj) {
        if (obj.files != undefined && obj.files.length > 0) {
            // console.log(obj.files);
            Array.prototype.forEach.call(obj.files, (file) => {

                if (this.estaHabilitadoLaExtension(file) == true) {
                    let payload = {
                        id: objBotonAdjuntoRequerimientoPagoDetalleSeleccionado.dataset.id,
                        id_requerimiento_pago_detalle: objBotonAdjuntoRequerimientoPagoDetalleSeleccionado.dataset.id,
                        nameFile: file.name,
                        action: 'GUARDAR',
                        file: file
                    };
                    this.agregarRegistroEnTablaAdjuntoRequerimientoPagoDetalle(payload);

                    tempArchivoAdjuntoRequerimientoPagoDetalleList.push(payload);
                } else {
                    Swal.fire(
                        'Este tipo de archivo no esta permitido adjuntar',
                        file.name,
                        'warning'
                    );
                }
            });

            this.updateContadorTotalAdjuntosRequerimientoPagoDetalle();



        }
        return false;
    }

    updateContadorTotalAdjuntosRequerimientoPagoDetalle() {
        if (typeof objBotonAdjuntoRequerimientoPagoDetalleSeleccionado == 'object') {
            objBotonAdjuntoRequerimientoPagoDetalleSeleccionado.querySelector("span[name='cantidadAdjuntosItem']").textContent = tempArchivoAdjuntoRequerimientoPagoDetalleList.filter((element, i) => (element.id_requerimiento_pago_detalle == objBotonAdjuntoRequerimientoPagoDetalleSeleccionado.dataset.id && element.action != 'ELIMINAR')).length;
        }

    }


    agregarRegistroEnTablaAdjuntoRequerimientoPagoDetalle(payload) {

        let html = '';
        html = `<tr id="${payload.id}" style="text-align:center">
        <td style="text-align:left;">${payload.nameFile}</td>
        <td style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger btn-xs handleClickEliminarArchivoRequerimientoPagoDetalle" name="btnEliminarArchivoRequerimientoPagoDetalle" title="Eliminar" data-id="${payload.id}" ><i class="fas fa-trash-alt"></i></button>
            </div>
        </td>
        </tr>`;

        document.querySelector("tbody[id='body_archivos_requerimiento_pago_detalle']").insertAdjacentHTML('beforeend', html);
    }

    eliminarArchivoRequerimientoPagoDetalle(obj) {
        obj.closest("tr").remove();
        // tempIdArchivoAdjuntoRequerimientoPagoDetalleToDeleteList.push(obj.dataset.id);
        // tempArchivoAdjuntoRequerimientoPagoDetalleList = tempArchivoAdjuntoRequerimientoPagoDetalleList.filter((element, i) => element.id != obj.dataset.id);
        var regExp = /[a-zA-Z]/g; //expresión regular
        if ((regExp.test(obj.dataset.id) == true)) {

            tempArchivoAdjuntoRequerimientoPagoDetalleList = tempArchivoAdjuntoRequerimientoPagoDetalleList.filter((element, i) => element.id != obj.dataset.id);
        } else {
            if (tempArchivoAdjuntoRequerimientoPagoDetalleList.length > 0) {
                let indice = tempArchivoAdjuntoRequerimientoPagoDetalleList.findIndex(elemnt => elemnt.id == obj.dataset.id);
                tempArchivoAdjuntoRequerimientoPagoDetalleList[indice].action_adjunto = 'ELIMINAR';
            } else {
                Swal.fire(
                    '',
                    'Hubo un error inesperado al intentar eliminar el adjunto del item, puede que no el objecto este vacio, elimine adjuntos y vuelva a seleccionar',
                    'error'
                );
            }

        }
        this.updateContadorTotalAdjuntosRequerimientoPagoDetalle();

    }

    imprimirRequerimientoPagoEnPdf(obj) {

        let idRequerimientoPago = obj.dataset.idRequerimientoPago;


        if (idRequerimientoPago.length > 0) {

            var regExp = /[a-zA-Z]/g; //expresión regular

            if (regExp.test(idRequerimientoPago) == false) {
                window.open('imprimir-requerimiento-pago-pdf/' + idRequerimientoPago);
            } else {
                Swal.fire(
                    '',
                    'Hubo un error inesperado al intentar imprimir el requerimiento de pago, no se encontro un ID valido para continuar, intente refrescar la ventana de navegador',
                    'error'
                );
            }

        }

    }


    agregarHistorialAprobacion(data) {
        document.querySelector("tbody[id='body_requerimiento_pago_historial_revision']").insertAdjacentHTML('beforeend', `<tr style="text-align:center">
        <td>${data.usuario != null ? data.usuario.nombre_corto : ''}</td>
        <td>${data.vo_bo != null ? data.vo_bo.descripcion : ''}</td>
        <td>${data.detalle_observacion != null ? data.detalle_observacion : ''}</td>
        <td>${data.fecha_vobo != null ? data.fecha_vobo : ''}</td>
        </tr>`);
    }


    changeTipoDestinatario(obj) {
        if (obj.value > 0) {
            this.limpiarInputDestinatario();
        }
    }


    mostrarInfoAdicionalCuentaSeleccionada(obj) {
        document.querySelector("div[id='modal-info-adicional-cuenta-seleccionada'] div[class='modal-body']").innerHTML = '';
        let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
        if (selectCuenta.value > 0) {
            $('#modal-info-adicional-cuenta-seleccionada').modal({
                show: true
            });
            document.querySelector("div[id='modal-info-adicional-cuenta-seleccionada'] div[class='modal-body']").insertAdjacentHTML('beforeend', `<div>

            <dl>
                <dt>Banco</dt>
                <dd>${selectCuenta.options[(selectCuenta.selectedIndex)].dataset.banco}</dd>
                <dt>Tipo Cuenta</dt>
                <dd>${selectCuenta.options[(selectCuenta.selectedIndex)].dataset.tipoCuenta}</dd>
                <dt>Moneda</dt>
                <dd>${selectCuenta.options[(selectCuenta.selectedIndex)].dataset.moneda}</dd>
                <dt>Nro cuenta</dt>
                <dd>${selectCuenta.options[(selectCuenta.selectedIndex)].dataset.nroCuenta}</dd>
                <dt>Nro CCI</dt>
                <dd>${selectCuenta.options[(selectCuenta.selectedIndex)].dataset.nroCci}</dd>
            </dl>
            </div>`);
        } else {
            Swal.fire(
                'Información de cuenta',
                'Debe seleccionar una persona o contribuyente que cuente con información de cuenta bancaria',
                'info'
            );
        }


    }



    buscarDestinatarioPorNumeroDeDocumento(obj) {
        let idTipoDestinatario = parseInt(document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_tipo_destinatario']").value);
        let option = '';
        document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").innerHTML = "";
        let nroDocumento = (obj.value).trim();
        if (nroDocumento.length > 0 && idTipoDestinatario > 0) {
            $.ajax({
                type: 'POST',
                url: 'obtener-destinatario-por-nro-documento',
                data: { 'nroDocumento': nroDocumento, 'idTipoDestinatario': idTipoDestinatario },
                dataType: 'JSON',
                beforeSend: data => {

                    $("input[name='nombre_destinatario']").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                success: (response) => {
                    $("input[name='nombre_destinatario']").LoadingOverlay("hide", true);
                    let idCuentaPreSeleccionada = '';
                    if (response.tipo_estado == 'success') {
                        console.log(response.data);
                        if (response.data != null && response.data.length > 0) {
                            if (idTipoDestinatario == 1) { // persona
                                document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = response.data[0]['nombre_completo'];
                                document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = response.data[0]['id_persona'];
                                if (response.data[0]['tipo_documento_identidad'] != null) {
                                    document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = (response.data[0]['tipo_documento_identidad']['descripcion']) != null ? response.data[0]['tipo_documento_identidad']['descripcion'] : '';
                                }

                                // llenar cuenta bancaria
                                if (response.data[0]['cuenta_persona'] != null) {

                                    idCuentaPreSeleccionada = response.data[0]['cuenta_persona'][0]['id_cuenta_bancaria'];
                                }
                                let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
                                if (selectCuenta != null) {
                                    while (selectCuenta.children.length > 0) {
                                        selectCuenta.removeChild(selectCuenta.lastChild);
                                    }
                                }

                                let seleccionado = "";
                                let tieneCuentaPorDefecto = false;

                                (response.data[0].cuenta_persona).forEach(element => {
                                    if (element.por_defecto == true) {
                                        tieneCuentaPorDefecto = true;
                                    }
                                });
                                (response.data[0].cuenta_persona).forEach(element => {

                                    if (idCuentaPreSeleccionada > 0 && tieneCuentaPorDefecto == false) {
                                        if (element.id_cuenta_bancaria == idCuentaPreSeleccionada) {
                                            seleccionado = "selected";
                                        } else {
                                            seleccionado = "";

                                        }

                                    } else if (element.por_defecto == true && tieneCuentaPorDefecto == true) {
                                        seleccionado = "selected";
                                    } else {
                                        seleccionado = "";
                                    }

                                    option += `
                                    <option
                                        data-nro-cuenta="${element.nro_cuenta != null && element.nro_cuenta != "" ? element.nro_cuenta : ''}"
                                        data-nro-cci="${element.nro_cci != null && element.nro_cci != "" ? element.nro_cci : ''}"
                                        data-tipo-cuenta="${element.tipo_cuenta != null ? element.tipo_cuenta.descripcion : ''}"
                                        data-banco="${element.banco != null && element.banco.contribuyente != null ? element.banco.contribuyente.razon_social : ''}"
                                        data-moneda="${element.moneda != null ? element.moneda.descripcion : ''}"
                                        value="${element.id_cuenta_bancaria}" ${seleccionado}
                                        >
                                        ${element.nro_cuenta != null && element.nro_cuenta != "" ? element.nro_cuenta : (element.nro_cci != null && element.nro_cci != "" ? (element.nro_cci + " (CCI)") : "")}
                                    </option>`;
                                });
                                document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").insertAdjacentHTML('beforeend', option);


                            } else if (idTipoDestinatario == 2) { // contribuyente
                                document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = response.data[0]['razon_social'];
                                document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = response.data[0]['id_contribuyente'];
                                if (response.data[0]['tipo_documento_identidad'] != null) {
                                    document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = (response.data[0]['tipo_documento_identidad']['descripcion']) != null ? response.data[0]['tipo_documento_identidad']['descripcion'] : '';
                                }
                                // llenar cuenta bancaria
                                // idCuentaPreSeleccionada = response.data[0]['cuenta_contribuyente'][0]['id_cuenta_contribuyente'];
                                console.log(idCuentaPreSeleccionada);
                                document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_cuenta_contribuyente']").value = idCuentaPreSeleccionada;

                                let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
                                if (selectCuenta != null) {
                                    while (selectCuenta.children.length > 0) {
                                        selectCuenta.removeChild(selectCuenta.lastChild);
                                    }
                                }

                                let seleccionado = "";
                                let tieneCuentaPorDefecto = false;
                                console.log("as1ea");


                                (response.data[0].cuenta_contribuyente).forEach(element => {
                                    if (element.por_defecto == true) {
                                        tieneCuentaPorDefecto = true;
                                    }
                                });

                                (response.data[0].cuenta_contribuyente).forEach(element => {

                                    if (idCuentaPreSeleccionada > 0 && tieneCuentaPorDefecto == false) {
                                        if (element.id_cuenta_contribuyente == idCuentaPreSeleccionada) {
                                            seleccionado = "selected";
                                        } else {
                                            seleccionado = "";
                                        }

                                    } else if (element.por_defecto == true && tieneCuentaPorDefecto == true) {
                                        seleccionado = "selected";
                                    } else {
                                        seleccionado = "";
                                    }

                                    option += `
                                    <option
                                        data-nro-cuenta="${element.nro_cuenta != null && element.nro_cuenta != "" ? element.nro_cuenta : ''}"
                                        data-nro-cci="${element.nro_cuenta_interbancaria != null && element.nro_cuenta_interbancaria != "" ? element.nro_cuenta_interbancaria : ''}"
                                        data-tipo-cuenta="${element.tipo_cuenta != null ? element.tipo_cuenta.descripcion : ''}"
                                        data-banco="${element.banco != null && element.banco.contribuyente != null ? element.banco.contribuyente.razon_social : ''}"
                                        data-moneda="${element.moneda != null ? element.moneda.descripcion : ''}"
                                        value="${element.id_cuenta_contribuyente}" ${seleccionado}
                                        >
                                        ${element.nro_cuenta != null && element.nro_cuenta != "" ? element.nro_cuenta : (element.nro_cuenta_interbancaria != null && element.nro_cuenta_interbancaria != "" ? (element.nro_cuenta_interbancaria + " (CCI)") : "")}
                                    </option>`;
                                });
                                document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").insertAdjacentHTML('beforeend', option);
                            }
                            this.listarEnResultadoDestinatario(response.data, idTipoDestinatario);
                        } else {
                            document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = "";
                            document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = "";
                            document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = "";
                            document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = "";
                            document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").value = "";

                            let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
                            if (selectCuenta != null) {
                                while (selectCuenta.children.length > 0) {
                                    selectCuenta.removeChild(selectCuenta.lastChild);
                                }
                            }

                        }
                        Lobibox.notify(response.tipo_estado, {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: response.mensaje
                        });
                    } else {
                        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = "";
                        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = "";
                        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = "";
                        document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = "";
                        document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']").value = "";

                        let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
                        if (selectCuenta != null) {
                            while (selectCuenta.children.length > 0) {
                                selectCuenta.removeChild(selectCuenta.lastChild);
                            }
                        }
                    }

                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                $("input[name='nombre_destinatario']").LoadingOverlay("hide", true);

                Swal.fire(
                    '',
                    'Hubo un problema al intentar obtener la data, por favor vuelva a intentarlo.',
                    'error'
                );
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
        }

    }

    limpiarInputDestinatario() {
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = "";
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = "";
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = "";
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_documento']").value = "";
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = "";

        this.limpiarTabla("listaDestinatariosEncontrados");
        document.querySelector("div[id='modal-requerimiento-pago'] span[id='cantidadDestinatariosEncontrados']").textContent = 0;

        let selectCuenta = document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_cuenta']");
        if (selectCuenta != null) {
            while (selectCuenta.children.length > 0) {
                selectCuenta.removeChild(selectCuenta.lastChild);
            }
        }
    }

    listarEnResultadoDestinatario(data, idTipoDestinatario) {
        document.querySelector("div[id='modal-requerimiento-pago'] span[id='cantidadDestinatariosEncontrados']").textContent = data.length;
        document.querySelector("div[id='modal-requerimiento-pago'] table[id='listaDestinatariosEncontrados']").innerHTML = '';
        data.forEach(element => {
            if (idTipoDestinatario == 1) {
                document.querySelector("div[id='modal-requerimiento-pago'] table[id='listaDestinatariosEncontrados']").insertAdjacentHTML('beforeend', `
                <tr class="handleClickSeleccionarDestinatario" style="cursor:pointer;"
                data-id-persona="${element.id_persona != null ? element.id_persona : ''}"
                data-id-contribuyente="${element.id_contribuyente != null ? element.id_contribuyente : ''}"
                data-tipo-documento-identidad="${element.tipo_documento_identidad != null ? element.tipo_documento_identidad.descripcion : ''}"
                data-numero-documento="${element.nro_documento != null ? element.nro_documento : ''}"
                data-nombre-destinatario="${element.nombre_completo != null ? element.nombre_completo : ''}"
                data-cuenta="${JSON.stringify(element.cuenta_persona)}"
                >
                <td>${element.tipo_documento_identidad != null ? element.tipo_documento_identidad.descripcion : ''}</td>
                <td>${element.nro_documento != null ? element.nro_documento : ''}</td>
                <td>${element.nombre_completo != null ? element.nombre_completo : ''}</td>
                <td>${element.cuenta_persona.length > 0 ? '<span class="label label-success">Con cuenta</span>' : '<span class="label label-danger">Sin cuenta</span>'}</td>

                </tr>
                `);
            }
            if (idTipoDestinatario == 2) {
                document.querySelector("div[id='modal-requerimiento-pago'] table[id='listaDestinatariosEncontrados']").insertAdjacentHTML('beforeend', `
                <tr class="handleClickSeleccionarDestinatario" style="cursor:pointer;"
                data-id-persona="${element.id_persona != null ? element.id_persona : ''}"
                data-id-contribuyente="${element.id_contribuyente != null ? element.id_contribuyente : ''}"
                data-tipo-documento-identidad="${element.tipo_documento_identidad != null ? element.tipo_documento_identidad.descripcion : ''}"
                data-numero-documento="${element.nro_documento != null ? element.nro_documento : ''}"
                data-nombre-destinatario="${element.razon_social != null ? element.razon_social : ''}"
                data-cuenta="${JSON.stringify(element.cuenta_contribuyente)}"
                >
                <td>${element.tipo_documento_identidad != null ? element.tipo_documento_identidad.descripcion : ''}</td>
                <td>${element.nro_documento != null ? element.nro_documento : ''}</td>
                <td>${element.razon_social ? element.razon_social : ''}</td>
                <td>${element.cuenta_contribuyente.length > 0 ? '<span class="label label-success">Con cuenta</span>' : '<span class="label label-danger">Sin cuenta</span>'}</td>


                </tr>
                `);
            }
        });
    }

    buscarDestinatarioPorNombre(obj) {
        let nombreDestinatario = obj.value;
        let idTipoDestinatario = parseInt(document.querySelector("div[id='modal-requerimiento-pago'] select[name='id_tipo_destinatario']").value);

        if (!(nombreDestinatario).trim().length == 0) {
            document.querySelector("div[id='modal-requerimiento-pago'] div[id='resultadoDestinatario']").classList.remove("oculto");
            $.ajax({
                type: 'POST',
                url: 'obtener-destinatario-por-nombre',
                data: { 'nombreDestinatario': nombreDestinatario, 'idTipoDestinatario': idTipoDestinatario },
                dataType: 'JSON',
                beforeSend: data => {

                    $("input[name='nro_documento']").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                    $("div[id='resultadoDestinatario']").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                success: (response) => {
                    $("input[name='nro_documento']").LoadingOverlay("hide", true);
                    $("div[id='resultadoDestinatario']").LoadingOverlay("hide", true);


                    if (response.tipo_estado == 'success') {
                        if (response.data != null && response.data.length > 0) {
                            this.listarEnResultadoDestinatario(response.data, idTipoDestinatario);

                        }
                    }

                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                $("input[name='nro_documento']").LoadingOverlay("hide", true);
                $("div[id='resultadoDestinatario']").LoadingOverlay("hide", true);

                Swal.fire(
                    '',
                    'Hubo un problema al intentar obtener la data, por favor vuelva a intentarlo.',
                    'error'
                );
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

        }

        if ((nombreDestinatario).trim().length == 0 && (document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value > 0 || document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value > 0)) {
            this.limpiarInputDestinatario();
        }
    }

    focusInputNombreDestinatario(obj) {
        document.querySelector("div[id='modal-requerimiento-pago'] div[id='resultadoDestinatario']").classList.remove("oculto");

    }
    focusOutInputNombreDestinatario(obj) {
        setTimeout(() => {
            document.querySelector("div[id='modal-requerimiento-pago'] div[id='resultadoDestinatario']").classList.add("oculto");
        }, 500);
    }

    seleccionarDestinatario(obj) {

        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_persona']").value = obj.dataset.idPersona;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='id_contribuyente']").value = obj.dataset.idContribuyente;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nro_documento']").value = obj.dataset.numeroDocumento;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='nombre_destinatario']").value = obj.dataset.nombreDestinatario;
        document.querySelector("div[id='modal-requerimiento-pago'] input[name='tipo_documento_identidad']").value = obj.dataset.tipoDocumentoIdentidad;

        if (obj.dataset.idPersona > 0) {
            obtenerCuentasBancariasPersona(obj.dataset.idPersona,null,true);
        } else if (obj.dataset.idContribuyente > 0) {
            obtenerCuentasBancariasContribuyente(obj.dataset.idContribuyente,null,true);
        } else {

            Swal.fire(
                'Obtener cuenta bancaria',
                'Hubo un problema. no se encontró un id persona o id contribuyente valido para poder obtener las cuentas bancarias',
                'error'
            );

        }
    }

    filtrosRequerimientosPago() {
        let model = this;
        $('#filtrar-reuerimiento-pago').click(function (e) {
            e.preventDefault();
            filtros_rq_pagos._token = token;
            model.mostrarListaRequerimientoPago();
        });
        $('[data-action="checked"]').change(function (e) {
            e.preventDefault();
            let checked = $(e.currentTarget).prop('checked');
            let key = $(e.currentTarget).attr('data-name');
            let modal = $('#modal-filtro-requerimientos-elaborados');
            if (checked) {
                $(e.currentTarget).closest('.form-group').find('[data-action="disabled"]').removeAttr('disabled');
            } else {
                $(e.currentTarget).closest('.form-group').find('[data-action="disabled"]').attr('disabled', 'true');
            }
            seleccionarFiltros(key, checked, $(e.currentTarget));

        });
        $('[data-select="change"]').change(function (e) {
            e.preventDefault();
            let key = $(e.currentTarget).closest('.form-group').find('[data-action="checked"]').attr('data-name');
            let checked = $(e.currentTarget).closest('.form-group').find('[data-action="checked"]').prop('checked');
            let input_checked = $(e.currentTarget).closest('.form-group').find('[data-action="checked"]');

            seleccionarFiltros(key, checked, input_checked);
        });
        function seleccionarFiltros(key, checked, input_checked) {
            switch (key) {
                case 'elaborado':

                    if (checked) {
                        filtros_rq_pagos.elaborado = input_checked.closest('.form-group').find('[name="elaborado"]').val();
                    } else {
                        filtros_rq_pagos.elaborado = 'SIN_FILTRO';
                    }
                    break;

                case 'empresa':
                    if (checked) {
                        filtros_rq_pagos.empresa = input_checked.closest('.form-group').find('[name="empresa"]').val();
                        filtros_rq_pagos.sede = input_checked.closest('.form-group').find('[name="sede"]').val();
                    } else {
                        filtros_rq_pagos.empresa = 'SIN_FILTRO';
                        filtros_rq_pagos.sede = 'SIN_FILTRO';
                    }
                    break;


                case 'grupo':
                    if (checked) {
                        filtros_rq_pagos.grupo = input_checked.closest('.form-group').find('[name="grupo"]').val();
                        filtros_rq_pagos.division = input_checked.closest('.form-group').find('[name="division"]').val();
                    } else {
                        filtros_rq_pagos.grupo = 'SIN_FILTRO';
                        filtros_rq_pagos.division = 'SIN_FILTRO';
                    }
                    break;


                case 'fechas':
                    if (checked) {
                        filtros_rq_pagos.fecha_inicio = input_checked.closest('.form-group').find('[name="fechaRegistroDesde"]').val();
                        filtros_rq_pagos.fecha_final = input_checked.closest('.form-group').find('[name="fechaRegistroHasta"]').val();
                    } else {
                        filtros_rq_pagos.fecha_inicio = 'SIN_FILTRO';
                        filtros_rq_pagos.fecha_final = 'SIN_FILTRO';
                    }
                    break;

                case 'estado':
                    if (checked) {
                        filtros_rq_pagos.estado = input_checked.closest('.form-group').find('[name="estado"]').val();
                    } else {
                        filtros_rq_pagos.estado = 'SIN_FILTRO';
                    }
                    break;
            }
        }

        $('[name="empresa"]').change(function (e) {
            e.preventDefault();
            let value = $(e.currentTarget).val();
            var html = '';
            $.ajax({
                type: 'GET',
                // url: '/necesidades/requerimiento/elaboracion/listar-sedes-por-empresa/'+value,
                url: route("necesidades.requerimiento.elaboracion.listar-sedes-por-empresa", { id: value }),
                data: {},
                // processData: false,
                // contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {
                    // console.log(data);
                }
            }).done(function (response) {
                html = '<option value="">Sede...</option>';
                $.each(response, function (index, element) {
                    html += '<option value="' + element.id_sede + '">' + element.descripcion + '</option>';
                });
                $('[name="sede"]').html(html);
                // console.log(response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

            // console.log(value);
        });

        $('[name="grupo"]').change(function (e) { // ? evaluar si usar mejor la func llenarSelectDivision
            e.preventDefault();
            let value = $(e.currentTarget).val();
            var html = '';
            $.ajax({
                type: 'GET',
                // url: '/necesidades/requerimiento/elaboracion/listar-sedes-por-empresa/'+value,
                url: route("finanzas.presupuesto.presupuesto-interno.get-area"),
                data: { id_grupo: value },
                // processData: false,
                // contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {
                    // console.log(data);
                }
            }).done(function (response) {
                html = '<option value="" hidden>Division...</option>';
                $.each(response.data, function (index, element) {

                    html += '<option value="' + element.id_division + '" data-id-grupo="' + element.grupo_id + '">' + element.descripcion + '</option>';
                });
                $('[name="division"]').html(html);
                // console.log(response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

            console.log(value);
        });
    }
}
