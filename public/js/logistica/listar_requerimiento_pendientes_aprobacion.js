var rutaListaPendienteAprobacion,
rutaListaAprobarDocumento,
rutaObservarDocumento,
rutaListaAnularDocumento;


function inicializarRutasPendienteAprobacion(_rutaListaPendienteAprobacion,_rutaListaAprobarDocumento,_rutaObservarDocumento,_rutaListaAnularDocumento) {

    rutaListaPendienteAprobacion = _rutaListaPendienteAprobacion;
    rutaListaAprobarDocumento = _rutaListaAprobarDocumento;
    rutaObservarDocumento = _rutaObservarDocumento;
    rutaListaAnularDocumento = _rutaListaAnularDocumento;
    listar_requerimientos_pendientes_aprobar();
}


function listar_requerimientos_pendientes_aprobar(){
    var vardataTables = funcDatatables();
    $('#ListaReqPendienteAprobacion').DataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        // 'processing': true,
        'serverSide': true,
        'destroy': true,
        "order": [[ 4, "desc" ]],
        'ajax': {
            url:rutaListaPendienteAprobacion,
            type:'GET',
            data: {_token:token}
        },
        'columns':[
            {'render':
                function (data, type, row, meta){
                    return meta.row +1;
                }
            },
            {'render': function (data, type, row){
                let prioridad ='';
                let thermometerNormal = '<center><i class="fas fa-thermometer-empty green fa-lg"  data-toggle="tooltip" data-placement="right" title="Prioridad Normal" ></i></center>';
                let thermometerAlta = '<center> <i class="fas fa-thermometer-half orange fa-lg"  data-toggle="tooltip" data-placement="right" title="Prioridad Alta"  ></i></center>';
                let thermometerCritica = '<center> <i class="fas fa-thermometer-full red fa-lg"  data-toggle="tooltip" data-placement="right" title="Prioridad Crítico"  ></i></center>';
                    if(row.id_prioridad==1){
                        prioridad = thermometerNormal
                    }else if(row.id_prioridad ==2){
                        prioridad = thermometerAlta
                    }else if(row.id_prioridad ==3){
                        prioridad = thermometerCritica
                    }
                return prioridad;
                }
            },
            {'data':'codigo', 'name':'codigo'},
            {'data':'concepto', 'name':'concepto'},
            {'data':'fecha_requerimiento', 'name':'fecha_requerimiento'},
            {'data':'tipo_requerimiento', 'name':'tipo_requerimiento'},
            {'data':'razon_social_empresa', 'name':'razon_social_empresa'},
            {'render': function (data, type, row){
                return row['descripcion_op_com']?row['descripcion_op_com']:row['descripcion_grupo'];
                }
            },
            {'data':'usuario', 'name':'usuario'},
            {'data':'estado_doc', 'name':'estado_doc'},
            {'data':'cantidad_aprobados_total_flujo', 'name':'cantidad_aprobados_total_flujo'},
            {'render': function (data, type, row){
                var list_id_rol_aprob =[];
                var hasAprobacion =0;
                var cantidadObservaciones =0;
                var hasObservacionSustentadas =0;



                if(row.aprobaciones.length>0){
                    row.aprobaciones.forEach(element => {
                        list_id_rol_aprob.push(element.id_rol)
                    });

                    roles.forEach(element => {
                        if(list_id_rol_aprob.includes(element.id_rol)==true){
                            hasAprobacion+=1;
                        }

                    });
                }
                if(row.observaciones.length>0){
                    row.observaciones.forEach(element => {
                        cantidadObservaciones+=1;
                        if(element.id_sustentacion >0 ){
                            hasObservacionSustentadas+=1;
                        }
                    });
                }


                if(hasAprobacion==0){
                    disabledBtn= '';
                } else if(hasAprobacion >0 ){
                    disabledBtn= 'disabled';
                }
                if(hasObservacionSustentadas != cantidadObservaciones ){
                    disabledBtn= 'disabled';
                }

                if(row.estado == 7 ){
                    disabledBtn= 'disabled';
                }
                let first_aprob={};
                // console.log(row.pendiente_aprobacion);
                if(row.pendiente_aprobacion.length > 0){
                        first_aprob = row.pendiente_aprobacion.reduce(function(prev, curr) {
                        return prev.orden < curr.orden ? prev : curr;
                    });

                }
                // buscar si la primera aprobación su numero de orden se repite en otro pendiente_aprobacion
                let aprobRolList=[];
                // console.log(row.pendiente_aprobacion);
                let pendAprob = row.pendiente_aprobacion;
                pendAprob.forEach(element => {
                    if(element.orden == first_aprob.orden){
                        aprobRolList.push(element.id_rol);
                    }
                });

                // si el usuario actual su rol le corresponde aprobar
                // console.log(row.rol_aprobante_id);
                // console.log(aprobRolList);

                // si existe varios con mismo orden
                    if(aprobRolList.length >1){

                        // si existe un rol aprobante ya definido en el requerimiento
                        if(row.rol_aprobante_id >0){
                            roles.forEach(element => {
                                if(row.rol_aprobante_id==element.id_rol){
                                // if(aprobRolList.includes(element.id_rol)){
                                    disabledBtn='';
                                }else{
                                    disabledBtn= 'disabled';

                                }

                            });
                        }else{
                            roles.forEach(element => {
                                if(aprobRolList.includes(element.id_rol)){
                                    disabledBtn='';
                                }else{
                                    disabledBtn= 'disabled';

                                }

                            });
                        }

                    }else{
                        roles.forEach(element => {
                            if(first_aprob.id_rol==element.id_rol){
                                disabledBtn='';
                            }else{
                                disabledBtn= 'disabled';

                            }

                        });
                    }





                let containerOpenBrackets='<center><div class="btn-group" role="group" style="margin-bottom: 5px;">';
                let containerCloseBrackets='</div></center>';
                let btnDetalleRapido='<button type="button" class="btn btn-xs btn-info" title="Ver detalle" onClick="viewFlujo(' +row['id_requerimiento']+ ', ' +row['id_doc_aprob']+ ');"><i class="fas fa-eye fa-xs"></i></button>';
                let btnTracking='<button type="button" class="btn btn-xs bg-primary" title="Explorar Requerimiento" onClick="tracking_requerimiento(' +row['id_requerimiento']+ ');"><i class="fas fa-globe fa-xs"></i></button>';
                let btnAprobar='<button type="button" class="btn btn-xs btn-success" title="Aprobar Requerimiento" onClick="aprobarRequerimiento(' +row['id_doc_aprob']+ ');" '+disabledBtn+'><i class="fas fa-check fa-xs"></i></button>';
                let btnObservar='<button type="button" class="btn btn-xs btn-warning" title="Observar Requerimiento" onClick="observarRequerimiento('+row['id_doc_aprob']+ ');" '+disabledBtn+'><i class="fas fa-exclamation-triangle fa-xs"></i></button>';
                let btnAnular='<button type="button" class="btn btn-xs bg-maroon" title="Anular Requerimiento" onClick="anularRequerimiento(' +row['id_doc_aprob']+ ');" '+disabledBtn+'><i class="fas fa-ban fa-xs"></i></button>';
                return containerOpenBrackets+btnDetalleRapido+btnTracking+btnAprobar+btnObservar+btnAnular+containerCloseBrackets;
                }
            },
        ],
        "createdRow": function( row, data, dataIndex){
            if( data.estado == 2  ){
                $(row).css('color', '#4fa75b');
             }
            if( data.estado == 3  ){
                $(row).css('color', '#ee9b1f');
             }
             if( data.estado == 7  ){
                $(row).css('color', '#d92b60');
             }


        }
    });
    let tablelistaitem = document.getElementById(
        'ListaReqPendienteAprobacion_wrapper'
    )
    tablelistaitem.childNodes[0].childNodes[0].hidden = true;
}


function openModalAnular(id_doc_aprob){
    $('#modal-anular-req').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
    document.querySelector("form[id='form-anular-requerimiento'] input[name='id_doc_aprob']").value =id_doc_aprob;
}
function openModalObservar(id_doc_aprob){
    $('#modal-obs-req').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
    document.querySelector("form[id='form-obs-requerimiento'] input[name='id_doc_aprob']").value =id_doc_aprob;
}
function openModalAprob(id_doc_aprob){
    $('#modal-aprobacion-docs').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
    document.querySelector("form[id='form-aprobacion'] input[name='id_doc_aprob']").value =id_doc_aprob;
}
function GrabarAnular(){
    let id_doc_aprob = document.querySelector("form[id='form-anular-requerimiento'] input[name='id_doc_aprob']").value;
    let id_rol_usuario = document.querySelector("form[id='form-anular-requerimiento'] select[name='rol_usuario']").value;
    let motivo = document.querySelector("form[id='form-anular-requerimiento'] textarea[name='motivo_req']").value;
    $.ajax({
        type: 'POST',
        url: rutaListaAnularDocumento,
        data:{'id_doc_aprob':id_doc_aprob,'motivo':motivo,'id_rol':id_rol_usuario},
        dataType: 'JSON',
        success: function(response){
            if(response.status ==200){
                $('#modal-anular-req').modal('hide');
                listar_requerimientos_pendientes_aprobar();
                alert("El requerimiento cambio su estado a denegado");
            }else{
                alert("Hubo un problema, no se puedo denegar el requerimiento");
                console.log(response);
            }

        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });

}
function GrabarAprobacion(){
    let id_doc_aprob = document.querySelector("form[id='form-aprobacion'] input[name='id_doc_aprob']").value;
    let id_rol_usuario = document.querySelector("form[id='form-aprobacion'] select[name='rol_usuario']").value;
    let detalle_observacion = document.querySelector("form[id='form-aprobacion'] textarea[name='detalle_observacion']").value;

    $.ajax({
        type: 'POST',
        url: rutaListaAprobarDocumento,
        data:{'id_doc_aprob':id_doc_aprob,'detalle_observacion':detalle_observacion,'id_rol':id_rol_usuario},
        dataType: 'JSON',
        success: function(response){
            if(response.status ==200){
                $('#modal-aprobacion-docs').modal('hide');
                listar_requerimientos_pendientes_aprobar();
                alert("Requerimiento Aprobado");
            }else{
                alert("Hubo un problema, no se puedo aprobar el requerimiento");
                console.log(response);
            }

        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });

}

function GrabarObservacion(){
    let id_doc_aprob = document.querySelector("form[id='form-obs-requerimiento'] input[name='id_doc_aprob']").value;
    let id_rol_usuario = document.querySelector("form[id='form-obs-requerimiento'] select[name='rol_usuario']").value;
    let detalle_observacion = document.querySelector("form[id='form-obs-requerimiento'] textarea[name='motivo_req']").value;

    // console.log(id_doc_aprob);
    // console.log(id_rol_usuario);
    // console.log(detalle_observacion);
    $.ajax({
        type: 'POST',
        url: rutaObservarDocumento,
        data:{'id_doc_aprob':id_doc_aprob,'detalle_observacion':detalle_observacion,'id_rol':id_rol_usuario},
        dataType: 'JSON',
        success: function(response){
            if(response.status ==200){
                $('#modal-obs-req').modal('hide');
                listar_requerimientos_pendientes_aprobar();
                alert("Requerimiento Observado");
            }else{
                alert("Hubo un problema, no se puedo observar el requerimiento");
                console.log(response);
            }

        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function aprobarRequerimiento(id_doc_aprob){
    openModalAprob(id_doc_aprob);
}

function observarRequerimiento(id_doc_aprob){
    openModalObservar(id_doc_aprob);

}
function anularRequerimiento(id_doc_aprob){
    openModalAnular(id_doc_aprob);
}
