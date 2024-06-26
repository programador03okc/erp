var tempArchivoAdjuntoRequerimientoList = [];
var tempArchivoAdjuntoRequerimientoToDeleteList = [];
var tempArchivoAdjuntoItemList = [];

let $tablaListaRequerimientosElaborados;
var iTableCounter = 1;
var oInnerTable;
class ListarRequerimientoView {

    constructor(requerimientoCtrl) {
        this.requerimientoCtrl = requerimientoCtrl;
        // this.trazabilidadRequerimiento = new TrazabilidadRequerimiento(requerimientoCtrl);
        this.ActualParametroAllOrMe = 'SIN_FILTRO';
        this.ActualParametroEmpresa = 'SIN_FILTRO';
        this.ActualParametroSede = 'SIN_FILTRO';
        this.ActualParametroGrupo = 'SIN_FILTRO';
        this.ActualParametroDivision = 'SIN_FILTRO';
        this.ActualParametroFechaDesde = 'SIN_FILTRO';
        this.ActualParametroFechaHasta = 'SIN_FILTRO';
        this.ActualParametroEstado = 'SIN_FILTRO';

    }

    // mostrar(meOrAll, idEmpresa=null, idSede=null, idGrupo=null, division=null, idPrioridad=null) {
    //     this.requerimientoCtrl.getListadoElaborados(meOrAll, idEmpresa, idSede, idGrupo, division, idPrioridad).then( (res) =>{
    //         this.construirTablaListadoRequerimientosElaborados(res['data']);
    //     }).catch(function (err) {
    //         console.log(err)
    //         // SWEETALERT
    //     })

    // }
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
                this.mostrar('SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO', 'SIN_FILTRO');
            } else {

                this.mostrar(this.ActualParametroAllOrMe, this.ActualParametroEmpresa, this.ActualParametroSede, this.ActualParametroGrupo, this.ActualParametroDivision, this.ActualParametroFechaDesde, this.ActualParametroFechaHasta, this.ActualParametroEstado);

            }



        });

    }


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
        array.forEach(element => {
            let option = document.createElement("option");
            option.text = element.descripcion;
            option.value = element.id_division;
            selectElement.add(option);
        });
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



    abrirModalFiltrosRequerimientosElaborados() {
        $('#modal-filtro-requerimientos-elaborados').modal({
            show: true,
            backdrop: 'static'
        });
    }

    descargarListaRequerimientosElaboradosExcel() {
        window.open(`reporte-requerimientos-bienes-servicios-excel/${this.ActualParametroAllOrMe}/${this.ActualParametroEmpresa}/${this.ActualParametroSede}/${this.ActualParametroGrupo}/${this.ActualParametroDivision}/${this.ActualParametroFechaDesde}/${this.ActualParametroFechaHasta}/${this.ActualParametroEstado}`);

    }
    descargarListaItemsRequerimientosElaboradosExcel() {
        window.open(`reporte-items-requerimientos-bienes-servicios-excel/${this.ActualParametroAllOrMe}/${this.ActualParametroEmpresa}/${this.ActualParametroSede}/${this.ActualParametroGrupo}/${this.ActualParametroDivision}/${this.ActualParametroFechaDesde}/${this.ActualParametroFechaHasta}/${this.ActualParametroEstado}`);

    }

    mostrar(meOrAll = 'SIN_FILTRO', idEmpresa = 'SIN_FILTRO', idSede = 'SIN_FILTRO', idGrupo = 'SIN_FILTRO', idDivision = 'SIN_FILTRO', fechaRegistroDesde = 'SIN_FILTRO', fechaRegistroHasta = 'SIN_FILTRO', idEstado = 'SIN_FILTRO') {
        // console.log(meOrAll,idEmpresa,idSede,idGrupo,idDivision,fechaRegistroDesde,fechaRegistroHasta,idEstado);
        let that = this;
        vista_extendida();
        var vardataTables = funcDatatables();
        const button_filtro = (array_accesos.find(element => element === 18)?{
                text: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtros : 0',
                attr: {
                    id: 'btnFiltrosListaRequerimientosElaborados'
                },
                action: () => {
                    this.abrirModalFiltrosRequerimientosElaborados();

                },
                className: 'btn-default btn-sm'
            }:[]),
            button_descargar_excel_cabecera = (array_accesos.find(element => element === 19)?{
                text: '<span class="far fa-file-excel" aria-hidden="true"></span> Descargar a nivel cabecera',
                attr: {
                    id: 'btnDescargarListaRequerimientosElaboradosExcel'
                },
                action: () => {
                    this.descargarListaRequerimientosElaboradosExcel();

                },
                className: 'btn-default btn-sm'
            }:[]),
            button_descargar_excel_items = (array_accesos.find(element => element === 19)?{
                text: '<span class="far fa-file-excel" aria-hidden="true"></span> Descargar a nivel items',
                attr: {
                    id: 'btnDescargarListaItemsRequerimientosElaboradosExcel'
                },
                action: () => {
                    this.descargarListaItemsRequerimientosElaboradosExcel();

                },
                className: 'btn-default btn-sm'
            }:[]);
        $tablaListaRequerimientosElaborados = $('#ListaRequerimientosElaborados').DataTable({
            'dom': vardataTables[1],
            'buttons': [button_filtro,button_descargar_excel_cabecera,button_descargar_excel_items],
            'language': vardataTables[0],
            'order': [[0, 'desc']],
            'bLengthChange': false,
            'serverSide': true,
            'destroy': true,
            'ajax': {
                'url': route('necesidades.requerimiento.elaboracion.elaborados'),
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': token},
                'data': { 'meOrAll': meOrAll, 'idEmpresa': idEmpresa, 'idSede': idSede, 'idGrupo': idGrupo, 'idDivision': idDivision, 'fechaRegistroDesde': fechaRegistroDesde, 'fechaRegistroHasta': fechaRegistroHasta, 'idEstado': idEstado },
                beforeSend: data => {

                    $("#ListaRequerimientosElaborados").LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                },
                // data: function (params) {
                //     return Object.assign(params, Util.objectifyForm($('#form-requerimientosElaborados').serializeArray()))
                // }

            },
            'columns': [
                { 'data': 'id_requerimiento', 'name': 'alm_req.id_requerimiento', 'visible': false },
                { 'data': 'priori', 'name': 'adm_prioridad.descripcion', 'className': 'text-center', 'visible': false, 'render': function (data, type, row) {
                    // return `<label class="lbl-codigo handleClickAbrirRequerimiento" title="Abrir Requerimiento">${row.codigo}</label>`;
                    return `<div style="display:flex;">${row['termometro']} &nbsp;<a href="/necesidades/requerimiento/elaboracion/index?id=${row.id_requerimiento}" target="_blank" title="Abrir Requerimiento">${row.codigo}</a> ${row.tiene_transformacion == true ? '<i class="fas fa-random text-danger" title="Con transformación"></i>' : ''} </div>`;
                }},
                { 'data': 'codigo', 'name': 'codigo', 'className': 'text-center' },
                { 'data': 'concepto', 'name': 'concepto' },
                { 'data': 'fecha_registro', 'name': 'alm_req.fecha_registro', 'className': 'text-center' },
                { 'data': 'fecha_entrega', 'name': 'alm_req.fecha_entrega', 'className': 'text-center' },
                { 'data': 'tipo_requerimiento', 'name': 'alm_tp_req.descripcion', 'className': 'text-center' },
                { 'data': 'descripcion_empresa_sede', 'name': 'sis_sede.descripcion', 'className': 'text-center' },
                { 'data': 'grupo', 'name': 'sis_grupo.descripcion', 'className': 'text-center' },
                { 'data': 'division', 'name': 'division.descripcion', 'className': 'text-center' },
                { 'data': 'descripcion_proyecto', 'name': 'proy_proyecto.descripcion', 'className': 'text-center' },
                { 'data': 'descripcion_presupuesto_interno', 'name': 'presupuesto_interno.descripcion', 'className': 'text-center' },
                { 'data': 'monto_subtotal', 'name': 'monto_subtotal', 'defaultContent': '', 'className': 'text-right','render': function (data, type, row) {
                    return (row['simbolo_moneda']) + ($.number(row.monto_subtotal, 2));
                } },
                { 'data': 'monto_total', 'name': 'monto_total', 'defaultContent': '', 'className': 'text-right','render': function (data, type, row) {
                    return (row['simbolo_moneda']) + ($.number(row.monto_total, 2,'.',','));
                }},
                { 'data': 'nombre_solicitado_por', 'name': 'nombre_solicitado_por' },
                { 'data': 'nombre_usuario', 'name': 'nombre_usuario' },
                { 'data': 'estado_doc', 'name': 'adm_estado_doc.estado_doc','render': function (data, type, row) {

                    let textoTrazabilidad='';
                    if(row['ordenes_compra']){
                        (row['ordenes_compra']).forEach(element => {
                            if(element.estado_pago==1){
                                textoTrazabilidad='Logística: Orden Creada';
                            }else if(element.estado_pago==8){
                                textoTrazabilidad='Logística: Solicitud de pago enviado';
                            }else if(element.estado_pago==5){
                                textoTrazabilidad='Contabilidad: Autorizado para pago';
                            }else if(element.estado_pago==6){
                                textoTrazabilidad='Tesorería: Orden pagada';
                            }else if(element.estado_pago==10){
                                textoTrazabilidad='Tesorería: Orden pagada con saldo';
                            }
                            
                        });
                    }
                    switch (row['estado']) {
                        case 1:
                            return '<span class="labelEstado label label-default">' + row['estado_doc'] +'</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;
                        case 2:
                            return '<span class="labelEstado label label-success">' + row['estado_doc'] + '</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;
                        case 3:
                            return '<span class="labelEstado label label-warning">' + row['estado_doc'] + '</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;
                        case 5:
                            return '<span class="labelEstado label label-primary">' + row['estado_doc'] + '</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;
                        case 7:
                            return '<span class="labelEstado label label-danger">' + row['estado_doc'] + '</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;
                        default:
                            return '<span class="labelEstado label label-default">' + row['estado_doc'] + '</span>' + '<br><small>'+textoTrazabilidad+'</small>';
                            break;

                    }
                } },
                { 'data': 'id_requerimiento',  'render': function (data, type, row) {
                    let containerOpenBrackets = '<div class="btn-group" role="group" style="margin-bottom: 5px;">';
                    let containerCloseBrackets = '</div>';
                    let btnEditar = '';
                    let btnAnular = '';
                    // let btnMandarAPago = '';
                    let btnVerAdjuntosModal = (array_accesos.find(element => element === 34)?'<button type="button" class="btn btn-xs btn-default  handleClickVerAgregarAdjuntosRequerimiento" name="btnVerAgregarAdjuntosRequerimiento" data-id-requerimiento="' + row['id_requerimiento'] + '" data-codigo-requerimiento="' + row['codigo'] + '" title="Ver archivos adjuntos" data-sustento="'+row['requerimiento_sustentado']+'"><i class="fas fa-paperclip fa-xs"></i> <span class="badge" title="Cantidad de adjunto del requerimiento" name="cantidadAdjuntosRequerimiento" style="background-color:dimgray; position:absolute;border: solid 0.1px;z-index: 9;top: -9px;left: 0px;font-size: 0.9rem;">' + (parseInt(row['cantidad_adjuntos_activos']['cabecera'])+ parseInt(row['cantidad_adjuntos_activos']['detalle'])) + '</span></button>':'');
                    let btnDetalleRapido = (array_accesos.find(element => element === 33)?'<button type="button" class="btn btn-xs btn-primary btnVerDetalle handleClickVerDetalleRequerimientoSoloLectura" data-id-requerimiento="' + row['id_requerimiento'] + '" title="Ver detalle" ><i class="fas fa-eye fa-xs"></i></button>':'');
                    let btnImprimirEnPdf = (array_accesos.find(element => element === 36)?'<button type="button" class="btn btn-xs btn-default handleClickImprimirRequerimientoPdf" data-id-requerimiento="' + row['id_requerimiento'] + '" title="Imprimir en PDF" ><i class="fas fa-print fa-xs"></i></button>':'');
                    let btnTrazabilidad = (array_accesos.find(element => element === 35)?'<button type="button" class="btn btn-xs btn-default btnVerTrazabilidad handleClickVerTrazabilidadRequerimiento" title="Trazabilidad"><i class="fas fa-route fa-xs"></i></button>':'');
                    // if(row.estado ==2){
                    //         btnMandarAPago = '<button type="button" class="btn btn-xs btn-success" title="Mandar a pago" onClick="listarRequerimientoView.requerimientoAPago(' + row['id_requerimiento'] + ');"><i class="fas fa-hand-holding-usd fa-xs"></i></button>';
                    //     }
                    if (row.id_usuario == auth_user.id_usuario && (row.estado == 1 || row.estado == 3)) {
                        btnEditar = '<button type="button" class="btn btn-xs btn-warning btnEditarRequerimiento handleClickAbrirRequerimiento" title="Editar" ><i class="fas fa-edit fa-xs"></i></button>';
                        btnAnular = '<button type="button" class="btn btn-xs btn-danger btnAnularRequerimiento handleClickAnularRequerimiento" title="Anular" ><i class="fas fa-times fa-xs"></i></button>';
                    }
                    // let btnVerDetalle= `<button type="button" class="btn btn-xs btn-default desplegar-detalle handleClickDesplegarDetalleRequerimiento" data-toggle="tooltip" data-placement="bottom" title="Ver Detalle" data-id="${row.id_requerimiento}">
                    // <i class="fas fa-chevron-down"></i>
                    // </button>`;


                    // return containerOpenBrackets + btnDetalleRapido + btnVerAdjuntosModal +btnTrazabilidad + btnEditar + btnAnular +btnImprimirEnPdf+ containerCloseBrackets;
                    let botoneraPrimaria = containerOpenBrackets
                        .concat(btnDetalleRapido)
                        .concat(btnVerAdjuntosModal)
                        .concat(btnTrazabilidad)
                        .concat(btnImprimirEnPdf)
                        .concat(containerCloseBrackets);

                    let botoneraSecundaria = containerOpenBrackets
                        .concat(btnEditar)
                        .concat(btnAnular)
                        .concat(containerCloseBrackets);

                    return botoneraPrimaria + botoneraSecundaria
                } }
            ],
            'columnDefs': [
                // {
                //     'render': function (data, type, row) {
                //         let labelOrdenes = '';
                //         (row['ordenes_compra']).forEach(element => {
                //             labelOrdenes += `<label class="lbl-codigo handleClickAbrirOrdenPDF" data-id-orden-compra=${element.id_orden_compra} title="Abrir orden">${element.codigo}</label>`;
                //         });
                //         return labelOrdenes;
                //     }, targets: 15, className: 'text-center'
                // },

            ],
            "createdRow": function (row, data, dataIndex) {

                let color = '#f2f2f2';
                if (data.requerimiento_sustentado == true) {
                    $(row).css('background-color', '#ffe270');
                }

            },
            'initComplete': function () {
                that.updateContadorFiltroRequerimientosElaborados();

                //Boton de busqueda
                const $filter = $('#ListaRequerimientosElaborados_filter');
                const $input = $filter.find('input');
                $filter.append('<button id="btnBuscar" class="btn btn-default btn-sm pull-right" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>');
                $input.off();
                $input.on('keyup', (e) => {
                    if (e.key == 'Enter') {
                        $('#btnBuscar').trigger('click');
                    }
                });
                $('#btnBuscar').on('click', (e) => {
                    $tablaListaRequerimientosElaborados.search($input.val()).draw();
                })
                //Fin boton de busqueda

                $('#ListaRequerimientosElaborados tbody').on("click", "label.handleClickAbrirOrdenPDF", function (e) {
                    that.abrirOrdenPDF(e.currentTarget.dataset.idOrdenCompra);
                });
                $('#ListaRequerimientosElaborados tbody').on("click", ".handleClickAbrirRequerimiento", function () {
                    let data = $('#ListaRequerimientosElaborados').DataTable().row($(this).parents("tr")).data();
                    that.abrirRequerimiento(data.id_requerimiento);
                });
                $('#ListaRequerimientosElaborados tbody').on("click", "button.handleClickAnularRequerimiento", function () {
                    let data = $('#ListaRequerimientosElaborados').DataTable().row($(this).parents("tr")).data();
                    that.anularRequerimiento(this, data.id_requerimiento, data.codigo);
                });

                $('#ListaRequerimientosElaborados tbody').on("click", "button.handleClickVerTrazabilidadRequerimiento", function () {
                    let data = $('#ListaRequerimientosElaborados').DataTable().row($(this).parents("tr")).data();
                    let url = "/necesidades/requerimiento/listado/trazabilidad?id="+data.id_requerimiento;
                    var win = window.open(url, '_blank');
                    win.focus();
                });

                $('#ListaRequerimientosElaborados tbody').on("click", "button.handleClickVerDetalleRequerimientoSoloLectura", function () {
                    let data = $('#ListaRequerimientosElaborados').DataTable().row($(this).parents("tr")).data();
                    that.verDetalleRequerimientoSoloLectura(data, that);
                });

                // $('#ListaRequerimientosElaborados tbody').on("click", "button.handleClickDesplegarDetalleRequerimiento", function(e) {
                //     that.desplegarDetalleRequerimiento(e.currentTarget);
                // });

            },
            "drawCallback": function (settings) {
                if ($tablaListaRequerimientosElaborados.rows().data().length == 0) {
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
                $('#ListaRequerimientosElaborados_filter input').prop('disabled', false);
                $('#btnBuscar').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>').prop('disabled', false);
                $('#ListaRequerimientosElaborados_filter input').trigger('focus');
                //fin botón búsqueda
                $("#ListaRequerimientosElaborados").LoadingOverlay("hide", true);
            },

        });
        //Desactiva el buscador del DataTable al realizar una busqueda
        $tablaListaRequerimientosElaborados.on('search.dt', function () {
            $('#tableDatos_filter input').prop('disabled', true);
            $('#btnBuscar').html('<span class="glyphicon glyphicon-time" aria-hidden="true"></span>').attr('disabled', true);
        });

        $('#ListaReq').DataTable().on("draw", function () {
            resizeSide();
        });
    }

    limpiarVistaRapidaRequerimientoBienesServicios() {
        document.querySelector("div[id='modal-requerimiento'] input[name='id_requerimiento']").value = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='codigo']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='concepto']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='razon_social_empresa']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='division']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='tipo_requerimiento']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='prioridad']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='fecha_entrega']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='solicitado_por']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='periodo']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='creado_por']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='observacion']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='incidencia']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='tipo_impuesto']").textContent = '';

        document.querySelector("div[id='modal-requerimiento'] td[id='adjuntosRequerimiento']").innerHTML = '';
        document.querySelector("div[id='modal-requerimiento'] span[name='simboloMoneda']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] span[name='simbolo_moneda']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_subtotal']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_igv']").textContent = '';
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_total']").textContent = '';
        this.limpiarTabla('listaDetalleRequerimientoModal');
        this.limpiarTabla('listaHistorialRevision');
        this.limpiarTabla('listaPartidasActivas');

        let allMesPpto=document.querySelectorAll("span[name='mes_ppto']");
        allMesPpto.forEach(element => {
                 element.textContent = "";
        });
    }


    verDetalleRequerimientoSoloLectura(data, that) {
        let idRequerimiento = data.id_requerimiento;
        $('#modal-requerimiento').modal({
            show: true,
            backdrop: 'true'
        });
        this.limpiarVistaRapidaRequerimientoBienesServicios();

        document.querySelector("div[id='modal-requerimiento'] fieldset[id='group-acciones']").classList.add("oculto");
        document.querySelector("div[id='modal-requerimiento'] button[id='btnRegistrarRespuesta']").classList.add("oculto");

        if (idRequerimiento > 0) {

            $('#modal-requerimiento .modal-content').LoadingOverlay("show", {
                imageAutoResize: true,
                progress: true,
                imageColor: "#3c8dbc"
            });

            that.requerimientoCtrl.getRequerimiento(idRequerimiento).then((res) => {
                $('#modal-requerimiento .modal-content').LoadingOverlay("hide", true);

                that.construirSeccionDatosGenerales(res['requerimiento'][0], res['cdp_requerimiento']);
                that.construirSeccionItemsDeRequerimiento(res['det_req'], res['requerimiento'][0]['simbolo_moneda'],res['requerimiento'][0]['id_presupuesto_interno']);
                that.construirSeccionHistorialAprobacion(res['historial_aprobacion']);
                that.construirSeccionFlujoAprobacion(res['flujo_aprobacion']);

                // calcular monto neto, monto igv y monto total cuando no existe valor en los montos de la cabecera del req
                if(! parseFloat(res['requerimiento'][0]['monto_total']) >0){
                    let montoNeto=0;
                    let montoIGV=0;
                    let montoTotal=0;
                    res['det_req'].forEach(item => {
                        montoNeto += (parseFloat(item.precio_unitario) * parseFloat(item.cantidad));
                    });

                    if(res['requerimiento']['incluye_igv']){
                        montoIGV = parseFloat(montoNeto)*0.18;
                    }

                    montoTotal = parseFloat(montoNeto) + parseFloat(montoIGV);

                    document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_subtotal']").textContent = $.number(montoNeto, 2);
                    document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_igv']").textContent = $.number(montoIGV, 2);
                    document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_total']").textContent = $.number(montoTotal, 2);
                }
                // 

                $('#modal-requerimiento div.modal-body').LoadingOverlay("hide", true);
                setTimeout(function () {
                        that.calcularPresupuestoUtilizadoYSaldoPorPartida();
                }, 2000);

            }).catch(function (err) {
                $('#modal-requerimiento .modal-content').LoadingOverlay("hide", true);

                console.log(err)
            })
        }
    }

    construirSeccionDatosGenerales(data,cdpRequerimiento) {
        // console.log(data);
        document.querySelector("div[id='modal-requerimiento'] input[name='id_requerimiento']").value = data.id_requerimiento;
        document.querySelector("div[id='modal-requerimiento'] input[name='id_moneda']").value = data.id_moneda;
        document.querySelector("div[id='modal-requerimiento'] input[name='incluye_igv']").value = data.incluye_igv;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='codigo']").textContent = data.codigo;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='concepto']").textContent = data.concepto;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='razon_social_empresa']").textContent = data.razon_social_empresa + ' (' + data.codigo_sede_empresa + ')';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='division']").textContent = data.division;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='tipo_requerimiento']").textContent = data.tipo_requerimiento;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='prioridad']").textContent = data.prioridad;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='fecha_entrega']").textContent = data.fecha_entrega;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='solicitado_por']").textContent = (data.para_stock_almacen == true ? 'Para stock almacén' : (data.nombre_trabajador ? data.nombre_trabajador : '-'));
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='periodo']").textContent = data.periodo;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='creado_por']").textContent = data.persona;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='observacion']").textContent = data.observacion;
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='tipo_impuesto']").textContent = data.tipo_impuesto==1?'Detracción':data.tipo_impuesto ==2?'Renta':'No aplica';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='tipo_cambio']").textContent = data.tipo_cambio??'';
        document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='simbolo_moneda']").textContent = data.simbolo_moneda;
        document.querySelector("div[id='modal-requerimiento'] span[name='simboloMoneda']").textContent = data.simbolo_moneda;
        
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


        if (data.id_incidencia > 0) {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='incidencia']").textContent = data.codigo_incidencia;
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_incidencia']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_incidencia']").classList.add("oculto");

        }

        let selectorSpanSimboloMoneda = document.querySelectorAll("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] span[name='simbolo_moneda']")
        selectorSpanSimboloMoneda.forEach(element => {
            element.textContent = data.simbolo_moneda;
        });
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_subtotal']").textContent = $.number(data.monto_subtotal, 2);
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_igv']").textContent = $.number(data.monto_igv, 2);
        document.querySelector("div[id='modal-requerimiento'] table[id='listaDetalleRequerimientoModal'] label[name='monto_total']").textContent = $.number(data.monto_total, 2);

        if (data.id_presupuesto_interno > 0) {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='presupuesto_interno']").textContent = (data.codigo_presupuesto_interno ?? '')+' - '+(data.descripcion_presupuesto_interno ??'');
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_interno']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_interno']").classList.add("oculto");

        }
        if (data.id_presupuesto > 0) {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='presupuesto_old']").textContent = (data.codigo_presupuesto_old ?? '')+' - '+(data.descripcion_presupuesto_old ??'');
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_old']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_presupuesto_old']").classList.add("oculto");

        }
        if (cdpRequerimiento.length > 0) {
            let codigosOportunidad= [];
            (cdpRequerimiento).forEach(element => {
                codigosOportunidad.push(element.codigo_oportunidad);
            });
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='codigo_cdp']").textContent = codigosOportunidad.toString() ?? '';
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_cdp']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_cdp']").classList.add("oculto");

        }
        if (data.id_proyecto > 0) {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] td[id='proyecto_presupuesto']").textContent = data.descripcion_proyecto ?? '';
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_proyecto']").classList.remove("oculto");
        } else {
            document.querySelector("div[id='modal-requerimiento'] table[id='tablaDatosGenerales'] tr[id='contenedor_proyecto']").classList.add("oculto");

        }

        tempArchivoAdjuntoRequerimientoList = [];
        if (data.adjuntos.length > 0) {
            document.querySelector("td[id='adjuntosRequerimiento']").innerHTML = `<a title="Ver archivos adjuntos de requerimiento" style="cursor:pointer;"  class="handleClickVerAdjuntosRequerimiento" >
            Ver (<span name="cantidadAdjuntosRequerimiento">${data.adjuntos.length}</span>)
            </a>`;
            (data.adjuntos).forEach(element => {
                tempArchivoAdjuntoRequerimientoList.push({
                    'id': element.id_adjunto,
                    'id_requerimiento': element.id_requerimiento,
                    'archivo': element.archivo,
                    'nameFile': element.archivo,
                    'categoria_adjunto_id': element.categoria_adjunto_id,
                    'categoria_adjunto': element.categoria_adjunto,
                    'fecha_registro': element.fecha_registro,
                    'estado': element.estado
                });

            });

            document.querySelector("a[class~='handleClickVerAdjuntosRequerimiento']") ? (document.querySelector("a[class~='handleClickVerAdjuntosRequerimiento']").addEventListener("click", this.verAdjuntosRequerimiento.bind(this), false)) : false;

        }

        let tamañoSelectAccion = document.querySelector("div[id='modal-requerimiento'] select[id='accion']").length;
        if (data.estado == 3) {
            for (let i = 0; i < tamañoSelectAccion; i++) {
                if (document.querySelector("div[id='modal-requerimiento'] select[id='accion']").options[i].value == 1) {
                    document.querySelector("div[id='modal-requerimiento'] select[id='accion']").options[i].setAttribute('disabled', true)
                }
            }
        } else {
            for (let i = 0; i < tamañoSelectAccion; i++) {
                if (document.querySelector("div[id='modal-requerimiento'] select[id='accion']").options[i].value == 1) {
                    document.querySelector("div[id='modal-requerimiento'] select[id='accion']").options[i].removeAttribute('disabled')
                }
            }
        }

        

    }

    calcularPresupuestoUtilizadoYSaldoPorPartida() {
        tempPartidasActivas = [];
        let partidaAgregadas = [];
        let subtotalItemList = [];
        let tbodyChildren = document.querySelector("tbody[id='body_item_requerimiento']").children;

        let idMonedaPresupuestoUtilizado = document.querySelector("input[name='id_moneda']").value;
        let simboloMonedaPresupuestoUtilizado = document.querySelector("td[id='simbolo_moneda']").textContent;;
        let actualTipoCambioCompra = document.querySelector("td[id='tipo_cambio']").textContent;

        
        
        for (let index = 0; index < tbodyChildren.length; index++) {
            if (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida > 0) {
                if (!partidaAgregadas.includes(tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida)) {
                    partidaAgregadas.push(tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida);
                    tempPartidasActivas.push({
                        'id_partida': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.idPartida,
                        'tipo_prespuesto': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.tipoPresupuesto,
                        'codigo': tbodyChildren[index].querySelector("p[class='descripcion-partida']").title,
                        'descripcion': tbodyChildren[index].querySelector("p[class='descripcion-partida']").textContent,
                        'presupuesto_mes': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.presupuestoMes,
                        'total_saldo_mes_disponible': (tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalSaldoMes).replace(/,/gi, ''),
                        'presupuesto_total': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal,
                        'id_moneda_presupuesto_utilizado': idMonedaPresupuestoUtilizado,
                        'simbolo_moneda_presupuesto_utilizado': simboloMonedaPresupuestoUtilizado,
                        'total_por_consumido_con_igv_fase_aprobacion': tbodyChildren[index].querySelector("p[class='descripcion-partida']").dataset.totalPorConsumidoConIgvFaseAprobacion,
                        'presupuesto_utilizado_al_cambio': 0,
                        'presupuesto_utilizado': 0,
                        'saldo_total': 0,
                        'saldo_mes': 0
                    });
                }

                let subtotal = (tbodyChildren[index].querySelector("input[class~='cantidad']").value > 0 ? tbodyChildren[index].querySelector("input[class~='cantidad']").value : 0) * (tbodyChildren[index].querySelector("input[class~='precio']").value > 0 ? tbodyChildren[index].querySelector("input[class~='precio']").value : 0);
                
                if (document.querySelector("input[name='incluye_igv']") == true || document.querySelector("input[name='incluye_igv']").value == 'true') {
                    subtotal = (subtotal * 0.18) + subtotal;
                }

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
                tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total).replace(/,/gi, '')) - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : (tempPartidasActivas[p].presupuesto_utilizado_al_cambio > 0 ? tempPartidasActivas[p].presupuesto_utilizado_al_cambio : 0));
                tempPartidasActivas[p].saldo_mes = parseFloat((tempPartidasActivas[p].total_saldo_mes_disponible).replace(/,/gi, '')) - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : 0) - (tempPartidasActivas[p].presupuesto_utilizado_al_cambio > 0 ? tempPartidasActivas[p].presupuesto_utilizado_al_cambio : 0);
            } else {
                tempPartidasActivas[p].saldo_total = parseFloat((tempPartidasActivas[p].presupuesto_total).replace(/,/gi, '')) - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : (tempPartidasActivas[p].presupuesto_utilizado > 0 ? tempPartidasActivas[p].presupuesto_utilizado : 0));
                tempPartidasActivas[p].saldo_mes = parseFloat((tempPartidasActivas[p].total_saldo_mes_disponible).replace(/,/gi, ''))  - (tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion > 0 ? tempPartidasActivas[p].total_por_consumido_con_igv_fase_aprobacion : 0)- (tempPartidasActivas[p].presupuesto_utilizado > 0 ? tempPartidasActivas[p].presupuesto_utilizado : 0);

            }
        }


        this.construirTablaPresupuestoUtilizadoYSaldoPorPartida(tempPartidasActivas);
        // console.log(tempPartidasActivas);
    }

    construirTablaPresupuestoUtilizadoYSaldoPorPartida(data) {
        this.limpiarTabla('listaPartidasActivas');
        // console.log(data);
        data.forEach(element => {

        //     <td style="text-align:right; background-color: #fbdddd;">
        //     <span>S/</span>${Util.formatoNumero(element.total_por_consumido_con_igv_fase_aprobacion, 2)} 
        //     <button type="button" class="btn btn-primary btn-xs" name="btnVerRequerimientosVinculados" onClick="verRequerimientosVinculadosConPartida(${element.id_partida},'${element.codigo.trim()}','${element.descripcion.trim()}','${element.tipo_prespuesto}');"title="Ver Requerimientos vinculados con partida">
        //     <i class="fas fa-eye"></i>
        //     </button>
        // </td>
            document.querySelector("tbody[id='body_partidas_activas']").insertAdjacentHTML('beforeend', `<tr style="text-align:center">
                <td>${element.codigo}</td>
                <td>${element.descripcion}</td>
                <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.presupuesto_total, 2)}</td>
                <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.presupuesto_mes, 2)}</td>
                <td style="text-align:right; background-color: #ddeafb;"><span>S/</span>${Util.formatoNumero(element.total_saldo_mes_disponible, 2)}</td>
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

    }


    verAdjuntosRequerimiento() {

        this.limpiarTabla('listaArchivosRequerimiento');
        $('#modal-adjuntar-archivos-requerimiento').modal({
            show: true
        });

        document.querySelector("div[id='modal-adjuntar-archivos-requerimiento'] div[id='group-action-upload-file']").classList.add('oculto');

        let html = '';
        if (tempArchivoAdjuntoRequerimientoList.length > 0) {
            tempArchivoAdjuntoRequerimientoList.forEach(element => {
                if (element.estado == 1) {
                    html += `<tr>
                    <td style="text-align:left;">${element.archivo}</td>
                    <td style="text-align:left;">${element.categoria_adjunto}</td>
                    <td style="text-align:center;">
                        <div class="btn-group" role="group">`;
                    html += `<button type="button" class="btn btn-info btn-md handleClickDescargarArchivoRequerimientoCabecera" name="btnDescargarArchivoCabecera" title="Descargar" data-id="${element.id}" ><i class="fas fa-paperclip"></i></button>`;
                    html += `</div>
                    </td>
                    </tr>`;

                }
            });
        }
        document.querySelector("tbody[id='body_archivos_requerimiento']").insertAdjacentHTML('beforeend', html)

    }

    descargarArchivoRequerimiento(obj) {
        if (obj.dataset.id > 0) {
            if (tempArchivoAdjuntoRequerimientoList.length > 0) {
                tempArchivoAdjuntoRequerimientoList.forEach(element => {
                    if (element.id == obj.dataset.id) {
                        window.open("/files/necesidades/requerimientos/bienes_servicios/cabecera/" + element.nameFile);
                    }
                });
            }
        }
    }

    descargarArchivoItem(obj) {
        console.log(obj);
        if (obj.dataset.id > 0) {
            if (tempArchivoAdjuntoItemList.length > 0) {
                tempArchivoAdjuntoItemList.forEach(element => {
                    if (element.id == obj.dataset.id) {
                        console.log(element);
                        window.open("/files/necesidades/requerimientos/bienes_servicios/detalle/" + element.nameFile);
                    }
                });
            }
        }
    }

    construirSeccionItemsDeRequerimiento(data, simboloMoneda,idPresupuestoInterno) {
        console.log(data);
        this.limpiarTabla('listaDetalleRequerimientoModal');
        tempArchivoAdjuntoItemList = [];
        let html = '';
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                if (data[i].estado != 7) {
                    let cantidadAdjuntosItem = 0;
                    cantidadAdjuntosItem = data[i].adjuntos.length;
                    if (cantidadAdjuntosItem > 0) {
                        (data[i].adjuntos).forEach(element => {
                            if (element.estado == 1) {
                                tempArchivoAdjuntoItemList.push(
                                    {
                                        id: element.id_adjunto,
                                        idRegister: element.id_detalle_requerimiento,
                                        nameFile: element.archivo,
                                        dateFile: element.fecha_registro,
                                        estado: element.estado
                                    }
                                );
                            }

                        });
                    }
                    document.querySelector("tbody[id='body_item_requerimiento']").insertAdjacentHTML('beforeend', `<tr>


                <td>
                    ${i + 1}
                    <p class="descripcion-partida" 
                        data-tipo-presupuesto="${data[i].id_partida_pi>0?'INTERNO':'ANTIGUO'}"
                        data-id-partida="${data[i].id_partida !=null ? data[i].id_partida : data[i].id_partida_pi}" 
                        data-presupuesto-total="${data[i].presupuesto_interno_total_partida != null ?data[i].presupuesto_interno_total_partida: data[i].presupuesto_old_total_partida}" 
                        data-presupuesto-mes="${data[i].presupuesto_interno_mes_partida}" 
                        data-total-saldo-mes="${data[i].presupuesto_interno_saldo_mes_disponible_partida}" 
                        data-total-por-consumido-con-igv-fase-aprobacion="${data[i].total_consumido_hasta_fase_aprobacion_con_igv}"
                        title="${data[i].id_partida !=null ? data[i].descripcion_partida.toUpperCase() :(data[i].id_partida_pi >0?data[i].descripcion_partida_presupuesto_interno.toUpperCase() : '') }";
                        style="display:none;"> ${data[i].id_partida >0 ?data[i].codigo_partida :(data[i].id_partida_pi >0?data[i].codigo_partida_presupuesto_interno : '')}
                    </p>
                </td>
                <td title="${data[i].id_partida >0 ?data[i].descripcion_partida.toUpperCase() :(data[i].id_partida_pi >0?data[i].descripcion_partida_presupuesto_interno.toUpperCase() : '')}" >${data[i].id_partida >0 ?data[i].codigo_partida :(data[i].id_partida_pi >0?data[i].codigo_partida_presupuesto_interno : '')}</td>
                <td title="${data[i].id_centro_costo>0?data[i].descripcion_centro_costo.toUpperCase():''}">${data[i].codigo_centro_costo ? data[i].codigo_centro_costo : ''}</td>
                <td>${data[i].id_tipo_item == 1 ? (data[i].producto_part_number ? data[i].producto_part_number : data[i].part_number) : '(Servicio)'}${data[i].tiene_transformacion == true ? '<br><span class="label label-default">Transformado</span>' : ''} </td>
                <td>${data[i].producto_descripcion != null ? data[i].producto_descripcion : (data[i].descripcion ? data[i].descripcion : '')} </td>
                <td>${data[i].unidad_medida != null ? data[i].unidad_medida : ''}</td>
                <td style="text-align:center;">
                    <input class="form-control input-sm cantidad text-right" type="number" min="1" name="cantidad[]" value="${data[i].cantidad >= 0 ? data[i].cantidad : 0}" style="display:none;">
                    ${data[i].cantidad >= 0 ? data[i].cantidad : ''}
                </td>
                <td style="text-align:right;">
                    <input class="form-control input-sm precio text-right" type="number" min="1" name="precio[]" value="${data[i].precio_unitario}" style="display:none;">
                    ${simboloMoneda}${Util.formatoNumero(data[i].precio_unitario, 2)}
                </td>
                <td style="text-align:right;">${simboloMoneda}${(data[i].subtotal ? Util.formatoNumero(data[i].subtotal, 2) : (Util.formatoNumero((data[i].cantidad * data[i].precio_unitario), 2)))}</td>
                <td>${data[i].motivo != null ? data[i].motivo : ''}</td>
                <td>${data[i].estado_doc != null ? data[i].estado_doc : ''}</td>
                <td style="text-align: center;">
                    ${cantidadAdjuntosItem > 0 ? '<a title="Ver archivos adjuntos de item" style="cursor:pointer;" class="handleClickVerAdjuntosItem' + i + '" >Ver (<span name="cantidadAdjuntosItem">' + cantidadAdjuntosItem + '</span>)</a>' : '-'}
                </td>
            </tr>`);

                    document.querySelector("a[class='handleClickVerAdjuntosItem" + i + "']") ? document.querySelector("a[class~='handleClickVerAdjuntosItem" + i + "']").addEventListener("click", this.verAdjuntosItem.bind(this, data[i].id_detalle_requerimiento), false) : false;
                }

            }


        }

    }

    verAdjuntosItem(idDetalleRequerimiento) {
        $('#modal-adjuntar-archivos-detalle-requerimiento').modal({
            show: true,
            backdrop: 'true'
        });
        this.limpiarTabla('listaArchivos');
        document.querySelector("div[id='modal-adjuntar-archivos-detalle-requerimiento'] div[id='group-action-upload-file']").classList.add('oculto');
        let html = '';
        tempArchivoAdjuntoItemList.forEach(element => {
            if (element.idRegister == idDetalleRequerimiento) {
                html += `<tr>
                <td style="text-align:left;">${element.nameFile}</td>
                <td style="text-align:center;">
                    <div class="btn-group" role="group">`;
                if (Number.isInteger(element.id)) {
                    html += `<button type="button" class="btn btn-info btn-md handleClickDescargarArchivoRequerimientoDetalle" name="btnDescargarArchivoDetalle" title="Descargar" data-id="${element.id}" ><i class="fas fa-paperclip"></i></button>`;
                }
                html += `</div>
                </td>
                </tr>`;
            }
        });
        document.querySelector("tbody[id='body_archivos_item']").insertAdjacentHTML('beforeend', html);


    }

    construirSeccionHistorialAprobacion(data) {
        this.limpiarTabla('listaHistorialRevision');
        let html = '';
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                html += `<tr>
                    <td style="text-align:center;">${data[i].nombre_corto ? data[i].nombre_corto : ''}</td>
                    <td style="text-align:center;">${data[i].accion ? data[i].accion : ''}</td>
                    <td style="text-align:left;">${data[i].detalle_observacion ? data[i].detalle_observacion : ''}</td>
                    <td style="text-align:center;">${data[i].fecha_vobo ? data[i].fecha_vobo : ''}</td>
                </tr>`;
            }
        }
        document.querySelector("tbody[id='body_historial_revision']").insertAdjacentHTML('beforeend', html)

    }
    construirSeccionFlujoAprobacion(data) {
        // console.log(data);
        this.limpiarTabla('listaFlujoAprobacion');
        let html = '';
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                html += `<tr>
                    <td style="text-align:center;">${data[i].orden ? data[i].orden : ''}</td>
                    <td style="text-align:center;">${data[i].rol ? data[i].rol.descripcion : ''}</td>
                    <td style="text-align:left;">${data[i].nombre_usuarios ? data[i].nombre_usuarios.toString() : ''}</td>
                    <td style="text-align:center;">${data[i].aprobar_sin_respetar_orden =='true' ? 'SI' : 'NO'}</td>
                </tr>`;
            }
        }
        document.querySelector("tbody[id='body_flujo_aprobacion']").insertAdjacentHTML('beforeend', html)

    }
    // requerimientoAPago(idRequerimiento){
    //     requerimientoCtrl.enviarRequerimientoAPago(idRequerimiento).then(function (res) {
    //         if(res >0){
    //             alert('Se envió correctamente a Pago');
    //             listarRequerimientoView.mostrar('ALL');

    //         }
    //     }).catch(function (err) {
    //         console.log(err)
    //     })
    // }
    abrirOrdenPDF(idOrden) {
        console.log(idOrden);
        let url = route('logistica.gestion-logistica.compras.ordenes.listado.generar-orden-pdf',idOrden);
        // let url = `/logistica/gestion-logistica/compras/ordenes/listado/generar-orden-pdf/${idOrden}`;
        var win = window.open(url, "_blank");
        win.focus();
    }

    abrirRequerimiento(idRequerimiento) {
        localStorage.setItem('idRequerimiento', idRequerimiento);
        let url = "/necesidades/requerimiento/elaboracion/index";
        var win = window.open(url, "_self");
        win.focus();
    }


    anularRequerimiento(obj, idRequerimiento, codigo) {
        Swal.fire({
            title: 'Esta seguro que desea anular el requerimiento ' + codigo + '?',
            text: "No podrás revertir esto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, anular'

        }).then((result) => {
            if (result.isConfirmed) {


                this.requerimientoCtrl.anularRequerimiento(idRequerimiento).then(function (res) {
                    if (res.estado == 7) {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        obj.closest('tr').querySelector("span[class~='labelEstado']").setAttribute('class', 'labelEstado label label-danger');
                        obj.closest('tr').querySelector("span[class~='labelEstado']").textContent = 'Anulado';
                        obj.closest('tr').querySelector("span[class~='labelEstado']").setAttribute('class', 'labelEstado label label-danger');
                        obj.closest('tr').querySelector("button[class~='btnEditarRequerimiento']").remove();
                        obj.closest('tr').querySelector("button[class~='btnAnularRequerimiento']").remove();
                        Swal.fire(
                            'Anulado',
                            res.mensaje,
                            'success'
                        );
                    } else {
                        $('#wrapper-okc').LoadingOverlay("hide", true);
                        Swal.fire(
                            'Hubo un problema',
                            res.mensaje,
                            'error'
                        );
                    }
                }).catch(function (err) {
                    console.log(err)
                })


            }
        })


    }



    handleChangeFilterEmpresaListReqByEmpresa(event) {
        this.handleChangeFiltroListado();
        this.requerimientoCtrl.getSedesPorEmpresa(event.target.value).then(function (res) {
            listarRequerimientoView.construirSelectSede(res);
        }).catch(function (err) {
            console.log(err)
        })
    }

    construirSelectSede(data) {
        let selectSede = document.querySelector('div[type="lista_requerimiento"] select[name="id_sede_select"]');
        let html = '<option value="0">Todas</option>';
        data.forEach(element => {
            html += '<option value="' + element.id_sede + '">' + element.codigo + '</option>'
        });

        selectSede.innerHTML = html;
        document.querySelector('div[type="lista_requerimiento"] select[name="id_sede_select"]').removeAttribute('disabled');

    }

    handleChangeFiltroListado() {
        this.mostrar(document.querySelector("select[name='mostrar_me_all']").value, document.querySelector("select[name='id_empresa_select']").value, document.querySelector("select[name='id_sede_select']").value, document.querySelector("select[name='id_grupo_select']").value, document.querySelector("select[name='division_select']").value, document.querySelector("select[name='id_prioridad_select']").value);

    }

    handleChangeGrupo(event) {
        this.requerimientoCtrl.getListaDivisionesDeGrupo(event.target.value).then(function (res) {
            listarRequerimientoView.construirSelectDivision(res);
        }).catch(function (err) {
            console.log(err)
        })
    }
    construirSelectDivision(data) {
        let selectSede = document.querySelector('div[type="lista_requerimiento"] select[name="division_select"]');
        let html = '<option value="0">Todas</option>';
        data.forEach(element => {
            html += '<option value="' + element.id_division + '">' + element.descripcion + '</option>'
        });

        selectSede.innerHTML = html;
        document.querySelector('div[type="lista_requerimiento"] select[name="division_select"]').removeAttribute('disabled');

    }


    // desplegarDetalleRequerimiento(obj){
    //     let tr = obj.closest('tr');
    //     var row = $tablaListaRequerimientosElaborados.row(tr);
    //     var id = obj.dataset.id;
    //     if (row.child.isShown()) {
    //         //  This row is already open - close it
    //         row.child.hide();
    //         tr.classList.remove('shown');
    //     }
    //     else {
    //         // Open this row
    //         //    row.child( format(iTableCounter, id) ).show();
    //         this.buildFormat(obj, iTableCounter, id, row);
    //         tr.classList.add('shown');
    //         // try datatable stuff
    //         oInnerTable = $('#ListaRequerimientosElaborados_' + iTableCounter).dataTable({
    //             //    data: sections,
    //             autoWidth: true,
    //             deferRender: true,
    //             info: false,
    //             lengthChange: false,
    //             ordering: false,
    //             paging: false,
    //             scrollX: false,
    //             scrollY: false,
    //             searching: false,
    //             columns: [
    //             ]
    //         });
    //         iTableCounter = iTableCounter + 1;
    //     }
    // }

    // buildFormat(obj, table_id, id, row) {
    //     obj.setAttribute('disabled', true);
    //     this.requerimientoCtrl.obtenerDetalleRequerimientos(id).then((res) => {
    //         // console.log(res);
    //         obj.removeAttribute('disabled');
    //         this.construirDesplegableDetalleRequerimientosElaboradas(table_id, row, res);
    //     }).catch((err) => {
    //         console.log(err)
    //     })
    // }

    // construirDesplegableDetalleRequerimientosElaboradas(table_id, row, response){
    //     var html = '';
    //     // console.log(response);
    //     if (response.length > 0) {
    //         response.forEach(function (element) {
    //             // if(element.tiene_transformacion==false){
    //             let stock_comprometido = 0;
    //             (element.reserva).forEach(reserva => {
    //                 if(reserva.estado ==1){
    //                     stock_comprometido+= parseFloat(reserva.stock_comprometido);
    //                 }
    //             });

    //                 html += `<tr>
    //                     <td style="border: none; text-align:center;" data-part-number="${element.part_number}" data-producto-part-number="${element.producto_part_number}">${(element.producto_part_number != null ? element.producto_part_number :(element.part_number !=null ?element.part_number:''))} ${element.tiene_transformacion ==true?'<span class="label label-default">Transformado</span>':''}</td>
    //                     <td style="border: none; text-align:left;">${element.producto_descripcion != null ? element.producto_descripcion : (element.descripcion?element.descripcion:'')}</td>
    //                     <td style="border: none; text-align:center;">${element.abreviatura != null ? element.abreviatura : ''}</td>
    //                     <td style="border: none; text-align:center;">${element.cantidad >0 ? element.cantidad : ''}</td>
    //                     <td style="border: none; text-align:center;">${(element.precio_unitario >0 ? ((element.moneda_simbolo?element.moneda_simbolo:((element.moneda_simbolo?element.moneda_simbolo:'')+'0.00')) + $.number(element.precio_unitario,2)) : (element.moneda_simbolo?element.moneda_simbolo:'')+'0.00')}</td>
    //                     <td style="border: none; text-align:center;">${(parseFloat(element.subtotal) > 0 ? ((element.moneda_simbolo?element.moneda_simbolo:'') + $.number(element.subtotal,2)) :((element.moneda_simbolo?element.moneda_simbolo:'')+$.number((element.cantidad * element.precio_unitario),2)))}</td>
    //                     <td style="border: none; text-align:center;">${element.motivo != null ? element.motivo : ''}</td>
    //                     <td style="border: none; text-align:center;">${stock_comprometido != null ? stock_comprometido : ''}</td>
    //                     <td style="border: none; text-align:center;">${element.estado_doc != null && element.tiene_transformacion ==false ? element.estado_doc : ''}</td>
    //                     </tr>`;
    //                 // }
    //             });
    //             var tabla = `<table class="table table-condensed table-bordered"
    //             id="detalle_${table_id}">
    //             <thead style="color: black;background-color: #c7cacc;">
    //                 <tr>
    //                     <th style="border: none; text-align:center;">Part number</th>
    //                     <th style="border: none; text-align:center;">Descripcion</th>
    //                     <th style="border: none; text-align:center;">Unidad medida</th>
    //                     <th style="border: none; text-align:center;">Cantidad</th>
    //                     <th style="border: none; text-align:center;">Precio unitario</th>
    //                     <th style="border: none; text-align:center;">Subtotal</th>
    //                     <th style="border: none; text-align:center;">Motivo</th>
    //                     <th style="border: none; text-align:center;">Reserva almacén</th>
    //                     <th style="border: none; text-align:center;">Estado</th>
    //                 </tr>
    //             </thead>
    //             <tbody style="background: #e7e8ea;">${html}</tbody>
    //             </table>`;
    //     }else{
    //         var tabla = `<table class="table table-sm" style="border: none;"
    //             id="detalle_${table_id}">
    //             <tbody>
    //                 <tr><td>No hay registros para mostrar</td></tr>
    //             </tbody>
    //             </table>`;
    //         }
    //         row.child(tabla).show();
    // }
}
