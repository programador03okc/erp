


class PresupuestoInternoView{
    constructor(model) {
        this.model = model;
    }

    eventos = ()=>{

        // $('body').on("change", "select.handleChangePresupuestoInterno", (e) => {
        //     this.seleccionarPresupuestoInterno(e.currentTarget);
        // });

        $('tbody').on("click", "button.handleClickCargarModalPartidas", (e) => {
            document.querySelector("div[id='listaPartidas']").innerHTML='';
            document.querySelector("div[id='listaPresupuesto']").innerHTML='';
            let id_presupuesto_interno = document.querySelector("select[name='id_presupuesto_interno']").value;
            if(id_presupuesto_interno>0){
                this.cargarPresupuestoDetalle(id_presupuesto_interno);
            }else{
                document.querySelector("div[id='listaPartidas']").innerHTML='';
                document.querySelector("div[id='listaPresupuesto']").innerHTML='';
                // Swal.fire(
                //     '',
                //     'No se puedo seleccionar el id de presupuesto para obtener su detalle, vuelva a intentar seleccionar un presupuesto interno.',
                //     'warning'
                // );
            }
        });

        $('#modal-partidas').on("click", "h5.handleClickaperturaPresupuesto", (e) => {
            this.apertura(e.currentTarget.dataset.idPresupuestoInterno);
            this.changeBtnIcon(e);
        });
        $('#modal-partidas').on("click", "button.handleClickSelectDetallePresupuesto", (e) => {
            this.selectPresupuestoInternoDetalle(e.currentTarget);

        });

        $('#modal-partidas').on("click", "tr.handleClickaperturaDetalle", (e) => {
            this.aperturaDetalle(e.currentTarget.dataset.idPadre);
            this.changeBtnIcon(e);
        });

    }

    selectPresupuestoInternoDetalle(obj) {
        tempObjectBtnPartida.nextElementSibling.querySelector("input[class='partida']").value = obj.dataset.idPresupuestoInternoDetalle;
        tempObjectBtnPartida.textContent = 'Cambiar';

        let tr = tempObjectBtnPartida.closest("tr");
        tr.querySelector("p[class='descripcion-partida']").dataset.idPartida = obj.dataset.idPresupuestoInternoDetalle;
        tr.querySelector("p[class='descripcion-partida']").textContent = obj.dataset.partida;
        tr.querySelector("p[class='descripcion-partida']").dataset.tipoPresupuesto = 'INTERNO';
        tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoTotal = obj.dataset.totalPresupuestoAño;
        tr.querySelector("p[class='descripcion-partida']").dataset.presupuestoMes = obj.dataset.totalPresupuestoMes;
        tr.querySelector("p[class='descripcion-partida']").dataset.totalSaldoMes = obj.dataset.totalSaldoMes;
        tr.querySelector("p[class='descripcion-partida']").dataset.totalPorConsumidoConIgvFaseAprobacion = obj.dataset.totalPorConsumidoConIgvFaseAprobacion;
        tr.querySelector("p[class='descripcion-partida']").setAttribute('title', obj.dataset.descripcion);

        this.updatePartidaItem(tempObjectBtnPartida.nextElementSibling.querySelector("input[class='partida']"));
        $('#modal-partidas').modal('hide');

        // llamar al hacer clic en partida el calculo de detalle partida 
        if(document.querySelector("div[class='page-main']").getAttribute("type")=='requerimiento'){
            const requerimientoModel = new RequerimientoModel();
            const requerimientoController = new RequerimientoCtrl(requerimientoModel);
            const requerimientoView = new RequerimientoView(requerimientoController);
           requerimientoView.calcularPresupuestoUtilizadoYSaldoPorPartida();
        }else if(document.querySelector("div[class='page-main']").getAttribute("type")=='lista_requerimiento_pago'){
            const presupuestoInternoView = new PresupuestoInternoView(new PresupuestoInternoModel('{{csrf_token()}}'));
            const listarRequerimientoPagoView = new ListarRequerimientoPagoView(presupuestoInternoView);
            listarRequerimientoPagoView.calcularPresupuestoUtilizadoYSaldoPorPartida();
        }


        
    }

    updatePartidaItem(obj) {
        let text = obj.value;
        if (text.length > 0) {
            obj.closest("div").classList.remove('has-error');
            if (obj.closest("td").querySelector("span")) {
                obj.closest("td").querySelector("span").remove();
            }
        } else {
            obj.closest("div").classList.add('has-error');
        }
    }

    cargarPresupuestoDetalle(idPresupuestoIterno){
        $("#listaPresupuesto").LoadingOverlay("show", {
            imageAutoResize: true,
            progress: true,
            imageColor: "#3c8dbc"
        });

        this.model.obtenerListaDetallePrespuestoInterno(idPresupuestoIterno).then((res) => {
            this.construirListaDetallePrespuestoInterno(res);
            $('#listaPresupuesto').LoadingOverlay("hide", true);

        }).catch(function (err) {
            console.log(err)
            $('#listaPresupuesto').LoadingOverlay("hide", true);

        })
    }

    construirListaDetallePrespuestoInterno(data){
        // console.log(data);

        let html='';
        let maximoNivel=0;
        let idDivision = document.querySelector("select[name='division']").value;
        let partidaRemuneraciones = '';
        const selectMesAfectacion = document.querySelector("select[name='mes_afectacion']");
        const mesAfectacionText = selectMesAfectacion?selectMesAfectacion.options[selectMesAfectacion.selectedIndex].textContent:'mes';
        data.forEach(presupuesto => {
            html += `
            <div id='${presupuesto.codigo}' class="panel panel-primary" style="width:100%; overflow: auto;">
                <h5 class="panel-heading handleClickaperturaPresupuesto" data-id-presupuesto-interno="${presupuesto.id_presupuesto_interno}" style="margin: 0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
                    &nbsp; ${presupuesto.descripcion}
                </h5>
                <div id="presupuesto-interno-${presupuesto.id_presupuesto_interno}" class="oculto" style="width:100%;">
                    <table class="table table-bordered table-condensed table-hover partidas" id="listaPartidas" width="100%" style="font-size:0.9em">
                        <tbody>
            `;

            
            html += `
            <tr>
            <td><strong>PARTIDA</strong></td>
            <td><strong>DESCRIPCIÓN</strong></td>
            <td style="background-color: #ddeafb;"><strong>Total ppto (anual)</strong></td>
            <td style="background-color: #ddeafb;"><strong>Total ppto (${mesAfectacionText})</strong></td>
            <td style="background-color: #fbdddd;"><strong>Consumido (${mesAfectacionText})</strong></td>
            <td style="background-color: #e5fbdd;"><strong>Saldo (${mesAfectacionText})</strong></td>
            <td style="background-color: #e5fbdd;"><strong>Saldo (anual)</strong></td>
            </tr> `;
            let totalPresupuestoAño = 0; 
            let totalPresupuestoMes = 0; 
            let totalConsumidoMes = 0; 
            let totalSaldoMes = 0; 
            let totalSaldoAño = 0; 
            let totalPorConsumidoConIgvFaseAprobacion = 0; 

            presupuesto['detalle'].forEach(detalle => { 
                

                if(detalle.descripcion =='REMUNERACIONES' || detalle.descripcion =='remuneraciones'){
                    partidaRemuneraciones = detalle.partida;
                }
                
                // * obtener el maximo nivel de partida solo si todo los presup tiene un nivel regular
                let tam = detalle.partida.split('.').length;
                if(tam >0){
                    let aux=tam;
                    if(tam >= aux){
                        maximoNivel=tam;
                    }
                }
            });

            
            
            
            
            presupuesto['detalle'].forEach(detalle => {
                // if (detalle.id_presupuesto_interno == presupuesto.id_presupuesto_interno) {
                    totalPresupuestoAño=$.number((parseFloat(detalle.total_presupuesto_año)),2,".",",");
                    totalPresupuestoMes=$.number((parseFloat(detalle.total_presupuesto_mes)),2,".",",");
                    totalConsumidoMes=$.number((parseFloat(detalle.total_consumido_mes)),2,".",",");
                    totalSaldoMes=$.number((parseFloat(detalle.total_saldo_mes)),2,".",",");
                    totalSaldoAño=$.number((parseFloat(detalle.total_saldo_año)),2,".",",");
                    totalPorConsumidoConIgvFaseAprobacion=$.number((parseFloat(detalle.total_consumido_hasta_fase_aprobacion_con_igv)),2,".",",");
                    
                    //* buscar la divsión si es RRHH la familia de remuneraciones debe estar habilitada de lo contrario quedara bloqueada
                    let hasOpacity='';
                    let hasCursor='';
                    let btnStatus='';
                    
                    // if(idDivision !=10 && detalle.partida.startsWith(partidaRemuneraciones)==true){
                    //     hasOpacity= '0.4';
                    //     hasCursor= 'not-allowed;';
                    //     btnStatus = 'disabled';
                    // }else{
                        hasOpacity='1';
                        hasCursor='pointer';
                        btnStatus = '';
                        
                    // }

                    if(detalle.registro==1){
                        html += `
                        <tr id="com-${detalle.id_presupuesto_interno_detalle}" class="handleClickaperturaDetalle" data-id-padre="${detalle.id_hijo}" style="margin: 0; cursor: ${hasCursor}; opacity:${hasOpacity};">
                        <td>${detalle.partida.split('.').length == (maximoNivel-1) ?'<i class="fas fa-plus-square" style="color: #3F51B5;padding: 4px;font-size: 14px;"></i>&nbsp;':''} <strong></i>${detalle.partida}</strong></td>
                        <td><strong>${detalle.descripcion}</strong></td>
                        <td class="right" style="text-align:right; background-color: #ddeafb;" ><strong>S/${totalPresupuestoAño}</strong></td>
                        <td class="right" style="text-align:right; background-color: #ddeafb;" ><strong>S/${totalPresupuestoMes}</strong></td>
                        <td class="right" style="text-align:right; background-color: #fbdddd;" ><strong>S/${totalConsumidoMes}</strong></td>
                        <td class="right" style="text-align:right; background-color: #e5fbdd;" ><strong>S/${totalSaldoMes}</strong></td>
                        <td class="right" style="text-align:right; background-color: #e5fbdd;" ><strong>S/${totalSaldoAño}</strong></td>
                        </tr> `;
                    }else{
                        html += `<tr id="hijo-${detalle.id_padre}" class="oculto" style="width:100%; cursor: ${hasCursor}; opacity:${hasOpacity};"">
                        <td style="width:15%; text-align:left;" name="partida">${detalle.partida}</td>
                        <td style="width:75%; text-align:left;" name="descripcion">${detalle.descripcion}</td>
                        <td style="width:15%; text-align:right; background-color: #ddeafb;" name="total_presupuesto_año" class="right" >S/${totalPresupuestoAño}</td>
                        <td style="width:15%; text-align:right; background-color: #ddeafb;" name="total_presupuesto_mes" class="right" >S/${totalPresupuestoMes}</td>
                        <td style="width:15%; text-align:right; background-color: #fbdddd;" name="total_consumido_mes" class="right" >S/${totalConsumidoMes}</td>
                        <td style="width:15%; text-align:right; background-color: #e5fbdd;" name="total_saldo_mes" class="right" >S/${totalSaldoMes}</td>
                        <td style="width:15%; text-align:right; background-color: #e5fbdd;" name="total_saldo_año" class="right" >S/${totalSaldoAño}</td>
                        <td style="width:5%; text-align:center;">`;
                        
                        if(parseFloat(totalPresupuestoMes)>0){
                            html+=`<button class="btn btn-success btn-xs handleClickSelectDetallePresupuesto" ${btnStatus}
                            data-id-presupuesto-interno-detalle="${detalle.id_presupuesto_interno_detalle}"
                            data-partida="${detalle.partida}"
                            data-descripcion="${detalle.descripcion}"
                            data-total-presupuesto-año="${totalPresupuestoAño}"
                            data-total-presupuesto-mes="${totalPresupuestoMes}"
                            data-total-consumido-mes="${totalConsumidoMes}"
                            data-total-saldo-mes="${totalSaldoMes}"
                            data-total-saldo-año="${totalSaldoAño}"
                            data-total-por-consumido-con-igv-fase-aprobacion="${totalPorConsumidoConIgvFaseAprobacion}"
                            title="Seleccionar partida">
                            <i class="fas fa-check"></button>`;
                        }else{

                            html+=`<i class="fas fa-info-circle"  style="color: #FF9800; cursor:help" title="El presupuesto mensual es insuficiente" >`;
                        }
                        
                            html +=`</td>
                    </tr>`;

                    }

                // }


            });
            html += `
                    </tbody>
                </table>
            </div>
        </div>`;
        });
        document.querySelector("div[id='listaPresupuesto']").innerHTML = html;


    }

    
    apertura(id) {
        if ($("#presupuesto-interno-" + id + " ").hasClass('oculto')) {
            $("#presupuesto-interno-" + id + " ").removeClass('oculto');
            $("#presupuesto-interno-" + id + " ").addClass('visible');
        } else {
            $("#presupuesto-interno-" + id + " ").removeClass('visible');
            $("#presupuesto-interno-" + id + " ").addClass('oculto');
        }
    }
    aperturaDetalle(id) {
        if ($("#hijo-" + id + " ").hasClass('oculto')) {
            $("#hijo-" + id + " ").removeClass('oculto');
            $("#hijo-" + id + " ").addClass('');
        } else {
            $("#hijo-" + id + " ").removeClass('');
            $("#hijo-" + id + " ").addClass('oculto');
        }
    }

    changeBtnIcon(obj) {

        if (obj.currentTarget.children[0].className == 'fas fa-chevron-right') {

            obj.currentTarget.children[0].classList.replace('fa-chevron-right', 'fa-chevron-down')
        } else {
            obj.currentTarget.children[0].classList.replace('fa-chevron-down', 'fa-chevron-right')
        }
    }



    llenarComboPresupuestoInterno(idGrupo,idArea, idPresupuestoInterno=null){
        let selectElement = document.querySelector("select[name='id_presupuesto_interno']");
        selectElement.innerHTML='';
        let option = document.createElement("option");
        option.text = "Seleccionar un presupuesto interno";
        option.value = '';
        selectElement.add(option);

        this.model.comboPresupuestoInterno(idGrupo, idArea).then((res) => {
            // console.log(res);


            if (selectElement.options.length > 0) {
                let i, L = selectElement.options.length - 1;
                for (i = L; i >= 0; i--) {
                    selectElement.remove(i);
                }
            }
            
            let optionDefault = document.createElement("option");
            optionDefault.text = "selecciona un presupuesto interno";
            
            optionDefault.value = "";
            optionDefault.setAttribute('data-codigo', "");
            optionDefault.setAttribute('data-id-grupo', "");
            optionDefault.setAttribute('data-id-area', "");
            selectElement.add(optionDefault);

            res.forEach(element => {                
                let option = document.createElement("option");
                option.text = element.codigo+' - '+element.descripcion+(element.estado !=2?'(NO APROBADO)':'');
                option.value = element.id_presupuesto_interno;
                option.setAttribute('data-codigo', element.codigo);
                option.setAttribute('data-id-grupo', element.id_grupo);
                option.setAttribute('data-id-area', element.id_area);
                if (element.id_presupuesto_interno == idPresupuestoInterno) {
                    option.selected = true;
                }
                selectElement.add(option);
            });

        }).catch(function (err) {
            console.log(err)
        });
    }

    limpiarTabla(idElement) {
        let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
        if (nodeTbody != null) {
            while (nodeTbody.children.length > 0) {
                nodeTbody.removeChild(nodeTbody.lastChild);
            }

        }
    }
    // seleccionarPresupuestoInterno(obj){
    //     if(obj.value >0){
    //         const codigoPresupuestoInterno=  obj.options[obj.selectedIndex].dataset.codigo;
    //         $("input[name='codigo_presupuesto_interno']").val(codigoPresupuestoInterno);
    //         if( document.querySelector("select[name='division").options[document.querySelector("select[name='division").selectedIndex].dataset.idGrupo == 3){
    //             this.ocultarOpcionCentroDeCosto();
    //         }else{
    //             this.mostrarOpcionCentroDeCosto();
    //         }
    //     }else{
    //         this.mostrarOpcionCentroDeCosto();

    //     }

    // }

    // ocultarOpcionCentroDeCosto(){
    //     $("button[name=centroCostos]").addClass("oculto");
    //     $("p[class=descripcion-centro-costo]").attr("hidden",true);
    // }
    // mostrarOpcionCentroDeCosto(){
    //     $("button[name=centroCostos]").removeClass("oculto");
    //     $("p[class=descripcion-centro-costo]").removeAttr("hidden");
    // }
}