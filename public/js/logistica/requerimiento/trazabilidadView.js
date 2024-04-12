class TrazabilidadView {
    constructor(model) {
        this.model = model;
    }

    eventos = () => {
    }

    graficar(idrequerimiento) {


        this.model.obtenerDataTrazabilidadDeRequerimiento(idrequerimiento).then((res) => {
            this.construirGraficaTrazabilidad(res);

        }).catch(function (err) {
            console.log(err)
        })
    }

    construirGraficaTrazabilidad(data) {
        console.log(data[0]);

        let boxHtml = '';

        let posx = 20;
        let posy = 50;

        let nombrePlantillaDeNodoAnterior = "";
        let nombreGrupoNodoAnterior = "";
        // let cantidadNodos= data[0].length;
        // creando nodos
        console.log(data[0].length);
        data[0].forEach((nodo, key) => {
            if (nodo.id_nodo == key + 1) {
                let plantillaHTML = this.obtenerPlantillaHTML(nodo.plantilla, nodo)


                if ( nombreGrupoNodoAnterior != nodo.grupo) {
                    if (nombreGrupoNodoAnterior == "") {

                    } else {
                        posx = posx + 260;
                        posy = 50;
                    }
                } else {
                    posy = posy + 260;
                }

                if (['flujo_aprobacion'].includes(nombrePlantillaDeNodoAnterior)) {
                    posx = posx + 160;

                }
                console.log(posx, posy);
                console.log(nombrePlantillaDeNodoAnterior, nodo.plantilla);

                editor.addNode(nodo.plantilla, 1, 1, posx, posy, nodo.plantilla, nodo, plantillaHTML);

                nombrePlantillaDeNodoAnterior = nodo.plantilla;
                nombreGrupoNodoAnterior = nodo.grupo;
            }

        });


        // conexión de nodos      
        data[0].forEach((nodo, key) => {
            if (nodo.id_nodo == key + 1) {

                if(nodo.output){
                    nodo.output.forEach(op => {
                        
                        editor.addConnection(key + 1, op, "output_1", "input_1");
                    });

                }
            }
        });


        // editor.addNode('requerimiento', 0, 1, 20, 50, 'requerimiento', data[0][0], boxHtml);


        // var htmlFlujoAprobacion = `<div class=\"title-box\"> FLUJO DE APROBACIÓN</div>
        // <div class="box">
        //         <table class="table table-bordered">
        //             <thead>
        //                 <tr>
        //                     <th>Revisado por</th>
        //                     <th>Acción</th>
        //                     <th>Comentario</th>
        //                     <th>Fecha revisión</th>
        //                 </tr>
        //             </thead>
        //             <tbody id="body_historial_revision"> `;
        // data.flujo_aprobacion.forEach(element => {
        //     htmlFlujoAprobacion += `<tr>
        //                     <td style="text-align:center;">${element.nombre_usuario}</td>
        //                     <td style="text-align:center;">${element.descripcion_vobo}</td>
        //                     <td style="text-align:left;">${element.detalle_observacion ?? ''}</td>
        //                     <td style="text-align:center;">${element.fecha_vobo}</td>
        //                 </tr>`;
        // });

        // htmlFlujoAprobacion += `</tbody>
        //         </table>
        // </div>
        // `;

        //     editor.addNode('flujo_aprobacion', 1, 1, 250, 50, 'flujo_aprobacion', data.flujo_aprobacion, htmlFlujoAprobacion);
        //     editor.addConnection(1, 2, "output_1", "input_1");


        //     var incrementoBloqueOrden = 0;
        //     data.ordenes.forEach((elementOrden, keyOrden) => {
        //         var htmlOrden = `<div class=\"title-box\"> Orden de compra</div>
        //         <div class="box">
        //             <dl>
        //             <dt>Código</dt>
        //             <dd>${elementOrden.codigo}</dd>
        //             <dt>Fecha registro</dt>
        //             <dd>${elementOrden.fecha_registro}</dd>
        //             <dt>Estado</dt>
        //             <dd>${elementOrden.estado_descripcion}</dd>
        //             </dl>
        //         </div>
        //     `;

        //         editor.addNode('orden' + keyOrden, 1, 1, 650, 50 + incrementoBloqueOrden, 'orden' + keyOrden, data.ordenes, htmlOrden);
        //         incrementoBloqueOrden =+230;

        //         let idInput=3+keyOrden;
        //         editor.addConnection(2, idInput, "output_1", "input_1");


        //         data.flujo_envio_pago.forEach((elementFlujo, keyFlujo) => {
        //             if(elementFlujo.id_orden_compra == elementOrden.id_orden){
        //                 data.flujo_envio_pago[keyFlujo]['id_input']= idInput;
        //             }
        //         });
        //     });


        //     var incrementoBloqueFlujoPago = 0;
        //     data.flujo_envio_pago.forEach((elementFlujo, keyFlujo) => {
        //     var htmlFlujoPago = `<div class=\"title-box\"> FLUJO PARA PAGO</div>
        //         <div class="box">
        //         <dl>
        //                 <dt>Fecha de envio a pago</dt>
        //                 <dd>${elementFlujo.fecha_solicitud_pago}</dd>
        //                 <dt>Fecha autorización de pago</dt>
        //                 <dd>${elementFlujo.fecha_autorizacion}</dd>
        //                 <dt>Responsable de autorización</dt>
        //                 <dd>${elementFlujo.usuario_autoriza_pago}</dd>
        //                 <dt>Estado de pago</dt>
        //                 <dd>${elementFlujo.descripcion_estado_pago}</dd>
        //                 </dl>
        //         </div>
        //     `;
        //     editor.addNode('flujo_pago' + keyFlujo, 1, 1, 900, (50 + incrementoBloqueFlujoPago), 'flujo_pago' + keyFlujo, data.flujo_envio_pago, htmlFlujoPago);
        //     incrementoBloqueFlujoPago =+260;

        //     // editor.addConnection(3, 5, "output_1", "input_1");
        //     // editor.addConnection(4, 6, "output_1", "input_1");

        // });
        //     console.log(editor.getNodesFromName('requerimiento'));


    }

    obtenerPlantillaHTML(nombrePlantilla, nodo) {
        // console.log(nodo);
        switch (nombrePlantilla) {
            case 'requerimiento':
                return this.plantillaRequerimiento(
                    nodo.data.id_requerimiento,
                    nodo.data.codigo,
                    nodo.data.fecha_registro,
                    nodo.data.estado_descripcion
                )
                break;

            case 'flujo_aprobacion':
                return this.plantillaFlujoAprobacion(nodo.data)
                break;

            case 'reserva':
                return this.plantillaReserva(
                    nodo.data.codigo,
                    nodo.data.fecha_registro
                )
                break;
            case 'orden':
                return this.plantillaOrden(
                    nodo.data.id_orden_compra,
                    nodo.data.codigo,
                    nodo.data.fecha_registro,
                    nodo.data.estado_descripcion,
                    nodo.data.estado_pago

                )
                break;
            case 'flujo_pago':
                return this.plantillaFlujoPago(
                    nodo.data.fecha_solicitud_pago,
                    nodo.data.fecha_autorizacion,
                    nodo.data.usuario_autoriza_pago,
                    nodo.data.descripcion_estado_pago)
                break;
            case 'registro_pago':
                return this.plantillaRegistroPago(
                    nodo.data.fecha_pago,
                    nodo.data.fecha_registro,
                    nodo.data.total_pago,
                    nodo.data.observacion)
                break;
            case 'ingreso':
                return this.plantillaIngreso(
                    nodo.data.fecha_emision,
                    nodo.data.fecha_registro,
                    nodo.data.codigo_ingreso)
                break;
            case 'despacho':
                return this.plantillaDespacho(
                    nodo.data.fecha_despacho,
                    nodo.data.codigo)
                break;
            default:
                break;
        }
    }


    plantillaRequerimiento(id, codigo, fecha_registro, estado) {
        var plantillaHTML = `<div class=\"title-box\"> REQUERIMIENTO</div>
        <div class="box">
            <dl>
            <dt>Código</dt>
            <dd><a href="/necesidades/requerimiento/listado/imprimir-requerimiento-pdf/${id}/0" target="_blank">${codigo}</a></dd>
            <dt>Fecha registro</dt>
            <dd>${fecha_registro}</dd>
            <dt>Estado</dt>
            <dd>${estado}</dd>
            </dl>
        </div>
        `;

        return plantillaHTML;
    }

    plantillaFlujoAprobacion(data) {
        var plantillaHTML = `<div class=\"title-box\"> FLUJO DE APROBACIÓN</div>
        <div class="box">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Revisado por</th>
                            <th>Acción</th>
                            <th>Comentario</th>
                            <th>Fecha revisión</th>
                        </tr>
                    </thead>
                    <tbody id="body_historial_revision"> `;
        data.forEach(element => {
            plantillaHTML += `<tr>
                                <td style="text-align:center;">${element.nombre_usuario??''}</td>
                                <td style="text-align:center;">${element.descripcion_vobo??''}</td>
                                <td style="text-align:left;">${element.detalle_observacion ?? ''}</td>
                                <td style="text-align:center;">${element.fecha_vobo??''}</td>
                            </tr>`;
        });

        plantillaHTML += `</tbody>
                </table>
        </div>
        `;

        return plantillaHTML;
    }


    plantillaReserva(codigo, fecha_registro) {
        var plantillaHTML = `<div class=\"title-box\"> RESERVA</div>
        <div class="box">
            <dl>
            <dt>Código</dt>
            <dd>${codigo}</dd>
            <dt>Fecha registro</dt>
            <dd>${fecha_registro}</dd>
            </dl>
        </div>
    `;
        return plantillaHTML;
    }
    plantillaOrden(id, codigo, fecha_registro, estado, estado_pago) {
        var plantillaHTML = `<div class=\"title-box\"> Orden de compra</div>
        <div class="box">
            <dl>
            <dt>Código</dt>
            <dd><a href="/logistica/gestion-logistica/compras/ordenes/elaborar/generar-orden-pdf/${id}" target="_blank">${codigo}</a></dd>
            <dt>Fecha registro</dt>
            <dd>${fecha_registro}</dd>
            <dt>Estado</dt>
            <dd>${estado}</dd>
            <dt>Envío a pago</dt>
            <dd>${(estado_pago>1?'SI':'NO')}</dd>
            </dl>
        </div>
    `;
        return plantillaHTML;
    }


    plantillaFlujoPago(fecha_solicitud_pago, fecha_autorizacion, usuario_autoriza_pago, descripcion_estado_pago) {
        var plantillaHTML = `<div class=\"title-box\"> FLUJO PARA PAGO</div>
        <div class="box">
        <dl>
            <dt>Fecha de envio a pago</dt>
            <dd>${fecha_solicitud_pago}</dd>
            <dt>Fecha autorización de pago</dt>
            <dd>${fecha_autorizacion}</dd>
            <dt>Responsable de autorización</dt>
            <dd>${usuario_autoriza_pago}</dd>
            <dt>Estado de pago</dt>
            <dd>${descripcion_estado_pago}</dd>
            </dl>
        </div>
        `;
        return plantillaHTML;
    }
    plantillaRegistroPago(fecha_pago, fecha_registro, total_pago, observacion) {
        var plantillaHTML = `<div class=\"title-box\"> REGISTRO DE PAGO</div>
        <div class="box">
        <dl>
            <dt>Fecha pago</dt>
            <dd>${fecha_pago}</dd>
            <dt>Fecha registro</dt>
            <dd>${fecha_registro}</dd>
            <dt>Total pago</dt>
            <dd>S/${total_pago}</dd>
            <dt>Observación</dt>
            <dd>${observacion}</dd>
            </dl>
        </div>
        `;
        return plantillaHTML;
    }
    plantillaIngreso(codigo, fecha_emision, fecha_registro) {
        var plantillaHTML = `<div class=\"title-box\"> INGRESO</div>
        <div class="box">
        <dl>
            <dt>Fecha emisión</dt>
            <dd>${fecha_emision}</dd>
            <dt>Fecha registro</dt>
            <dd>${fecha_registro}</dd>
            <dt>Código</dt>
            <dd>${codigo}</dd>
            </dl>
        </div>
        `;
        return plantillaHTML;
    }
    plantillaDespacho(codigo, fecha_despacho) {
        var plantillaHTML = `<div class=\"title-box\"> DESPACHO</div>
        <div class="box">
        <dl>
            <dt>Fecha despacho</dt>
            <dd>${fecha_despacho}</dd>
            <dt>Código</dt>
            <dd>${codigo}</dd>
            </dl>
        </div>
        `;
        return plantillaHTML;
    }
}