var fleteObject={};

function openAgenciaTransporte(data) {
    $('[data-toggle="tooltip"]').tooltip()

    $("#modal-orden_despacho_transportista").modal({
        show: true
    });
    // console.log(data);
    $("[name=id_od]").val(data.id_od);
    $("[name=con_id_requerimiento]").val(data.id_requerimiento);
    $("[name=tr_id_transportista]").val(data.id_transportista !== null ? data.id_transportista : '');

    $("[name=tr_razon_social]").val(data.transportista_razon_social !== null ? data.transportista_razon_social : '');
    $("[name=serie]").val(data.serie_tra !== null ? data.serie_tra : '');
    $("[name=numero]").val(data.numero_tra !== null ? data.numero_tra : '');
    $("[name=serie_guia_venta]").val(data.serie_guia !== null ? data.serie_guia : '');
    $("[name=numero_guia_venta]").val(data.numero_guia !== null ? data.numero_guia : '');

    $("[name=fecha_transportista]").val(data.fecha_transportista !== null ? data.fecha_transportista : '');
    $("[name=fecha_despacho_real]").val(data.fecha_despacho_real !== null ? data.fecha_despacho_real : '');
    $("[name=importe_flete]").val(data.importe_flete !== null ? data.importe_flete : '');
    $("[name=fechaRegistroFlete]").prop('title', (data.fecha_registro_flete != null ? ('Fecha registro de flete: ' + (moment(data.fecha_registro_flete).format("DD-MM-YYYY h:m"))) : 'Flete Sin fecha registro'));
    $("[name=codigo_envio]").val(data.codigo_envio !== null ? data.codigo_envio : '');

    if (data.credito) {
        $('[name=credito]').prop('checked', true);
    } else {
        $('[name=credito]').prop('checked', false);
    }
    $("#submit_od_transportista").removeAttr("disabled");
}

$("#form-orden_despacho_transportista").on("submit", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    
    // console.log(data);
    let mensajeList=[];
    let continuar = true;
    let transportistaVal = $('[name=tr_id_transportista]').val();
    // let ConceptoVal = $('[name=concepto]').val();
    // let empresaVal = $('[name=empresa]').val();
    // let sedeVal = $('[name=sede]').val();
    // let divisionVal = $('[name=division]').val();
    // let grupoVal = $('[name=grupo]').val();
    // let proyectoVal = $('[name=proyecto]').val();
    // let cdpVal = $('[name=cdp]').val();
    // let partidaVal = $('[name=cdp]').val();
    // let centroCostoVal = $('[name=centro_costo]').val();

    if (transportistaVal == '') {
        mensajeList.push("transportista");
        continuar = false;
    }
    // if (debeGenerarRequerimiento) {
    //     if(ConceptoVal == ''){
    //         mensajeList.push("concepto");
    //         continuar = false;
    //     }
    //     if(empresaVal == ''){
    //         mensajeList.push("empresa");
    //         continuar = false;
    //     }
    //     if(sedeVal == ''){
    //         mensajeList.push("sede");
    //         continuar = false;
    //     }
    //     if(divisionVal == ''){
    //         mensajeList.push("división");
    //         continuar = false;
    //     }
    //     // if(grupoVal ==3){
    //     //     if(!proyectoVal >0){
    //     //         mensajeList.push("proyecto");
    //     //         continuar = false;
    //     //     }

    //     //     if(partidaVal == ''){
    //     //         mensajeList.push("partida");
    //     //         continuar = false;
    //     //     }
    //     // }
    //     if(cdpVal ==2){
    //         if(!cdpVal >0){
    //             mensajeList.push("CDP");
    //             continuar = false;
    //         }
    //     }

    //     if(centroCostoVal == ''){
    //         mensajeList.push("centro de costo");
    //         continuar = false;
    //     }
    // }

    if(continuar){
        despachoTransportista(data,$('[name=id_od]').val());
        calcularImportesDetalleRequerimientoTransportista($('input[name="importe_flete"]').val());

    }else{
        Swal.fire({
            title:  'Debe completar los campos: ('+mensajeList.toString()+')',
            icon: "warning",
        });
    }
    
});

function despachoTransportista(data, idOd=null) {
    $('#submit_od_transportista').attr('disabled', 'true');
    $.ajax({
        type: 'POST',
        url: 'despachoTransportista',
        data: data,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            if (response > 0) {
                $("#requerimientosEnProceso").DataTable().ajax.reload(null, false);
                $('#modal-orden_despacho_transportista').modal('hide');
                Lobibox.notify("success", {
                    title: false,
                    size: "mini",
                    rounded: true,
                    sound: false,
                    delayIndicator: false,
                    msg: 'Datos actualizados correctamente.'
                });

                consultaGuardarRequerimientoFlete(idOd); 

            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function cerrarDespachoTransportista() {
    $('#modal-orden_despacho_transportista').modal('hide');
}

function ceros_numero(numero) {
    if (numero == "numero") {
        var num = $("[name=numero]").val();
        if (num !== '') {
            $("[name=numero]").val(leftZero(7, num));
        }
    } else if (numero == "serie") {
        var num = $("[name=serie]").val();
        if (num !== '') {
            $("[name=serie]").val(leftZero(4, num));
        }
    } else if (numero == "numero_gv") {
        var num = $("[name=numero_guia_venta]").val();
        if (num !== '') {
            $("[name=numero_guia_venta]").val(leftZero(7, num));
        }
    } else if (numero == "serie_gv") {
        var num = $("[name=serie_guia_venta]").val();
        if (num !== '') {
            $("[name=serie_guia_venta]").val(leftZero(4, num));
        }
    }
}


function calcularImportesDetalleRequerimientoTransportista(importeFlete) {
    let importeUnitario = 0;
    let importeIGV = 0;
    let importeTotal = 0;
    if (parseFloat(importeFlete) > 0) {
        importeUnitario = (parseFloat(importeFlete) / 1.18);
        importeIGV = (parseFloat(importeFlete) * 0.18);
        importeTotal = (parseFloat(importeFlete));
    }

    fleteObject={
        'estado':'',
        'transportista':$("input[name='tr_razon_social']").val()??'',
        'fecha_entrega':$("input[name='fecha_transportista']").val()??'',
        'precio_unitario' : importeUnitario,
        'importe_igv' : importeIGV,
        'importe_total' :importeTotal
    }
}