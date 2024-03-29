function get_data_requerimiento(){
    let tipo_req = document.querySelector("select[name='tipo_requerimiento']").value;
    let requerimiento = {};
    // console.log(tipo_req);
    tipo_requerimiento = tipo_req;
    fecha_creacion_cc = document.querySelector("form[id='form-requerimiento'] input[name='fecha_creacion_cc']").value;
    id_cc = document.querySelector("form[id='form-requerimiento'] input[name='id_cc']").value;
    tipo_cuadro = document.querySelector("form[id='form-requerimiento'] input[name='tipo_cuadro']").value;
    confirmacion_pago = document.querySelector("form[id='form-requerimiento'] input[name='confirmacion_pago']").value;

    id_requerimiento = document.querySelector("form[id='form-requerimiento'] input[name='id_requerimiento']").value;
    concepto = document.querySelector("form[id='form-requerimiento'] input[name='concepto']").value;
    fecha_requerimiento = document.querySelector("form[id='form-requerimiento'] input[name='fecha_requerimiento']").value;
    id_prioridad = document.querySelector("form[id='form-requerimiento'] select[name='prioridad']").value;
    id_empresa = document.querySelector("form[id='form-requerimiento'] select[name='empresa']").value;
    id_sede = document.querySelector("form[id='form-requerimiento'] select[name='sede']").value;
    id_grupo = document.querySelector("form[id='form-requerimiento'] input[name='id_grupo']").value;
    // id_area = document.querySelector("form[id='form-requerimiento'] input[name='id_area']").value;
    // nombre_area = document.querySelector("form[id='form-requerimiento'] input[name='nombre_area']").value;
    id_moneda = document.querySelector("form[id='form-requerimiento'] select[name='moneda']").value;
    id_periodo = document.querySelector("form[id='form-requerimiento'] select[name='periodo']").value;
    id_proyecto = document.querySelector("form[id='form-requerimiento'] select[name='id_proyecto']").value;
    id_rol = document.querySelector("form[id='form-requerimiento'] select[name='rol_usuario']").value;
    codigo_occ = document.querySelector("form[id='form-requerimiento'] input[name='codigo_occ']").value;
    id_sede = document.querySelector("form[id='form-requerimiento'] select[name='sede']").value;
    tipo_cliente = document.querySelector("form[id='form-requerimiento'] select[name='tipo_cliente']").value;
    id_cliente = document.querySelector("form[id='form-requerimiento'] input[name='id_cliente']").value;
    id_persona = document.querySelector("form[id='form-requerimiento'] input[name='id_persona']").value;
    direccion_entrega = document.querySelector("form[id='form-requerimiento'] input[name='direccion_entrega']").value;
    id_cuenta = document.querySelector("form[id='form-requerimiento'] input[name='id_cuenta']").value;
    nro_cuenta = document.querySelector("form[id='form-requerimiento'] input[name='nro_cuenta']").value;
    cci = document.querySelector("form[id='form-requerimiento'] input[name='cci']").value;
    telefono = document.querySelector("form[id='form-requerimiento'] input[name='telefono_cliente']").value;
    email = document.querySelector("form[id='form-requerimiento'] input[name='email_cliente']").value;
    ubigeo = document.querySelector("form[id='form-requerimiento'] input[name='ubigeo']").value;
    id_almacen = document.querySelector("form[id='form-requerimiento'] select[name='id_almacen']").value;
    almacen_id_sede =document.querySelector("select[name='id_almacen']").options[document.querySelector("select[name='id_almacen']").selectedIndex].dataset.idSede;
    almacen_id_empresa =document.querySelector("select[name='id_almacen']").options[document.querySelector("select[name='id_almacen']").selectedIndex].dataset.idEmpresa;
    observacion = document.querySelector("form[id='form-requerimiento'] textarea[name='observacion']").value;
    monto = document.querySelector("form[id='form-requerimiento'] input[name='monto']").value;
    fecha_entrega = document.querySelector("form[id='form-requerimiento'] input[name='fecha_entrega']").value;
    tiene_transformacion = document.querySelector("form[id='form-requerimiento'] input[name='tiene_transformacion']").value;
    justificacion_generar_requerimiento = document.querySelector("form[id='form-requerimiento'] input[name='justificacion_generar_requerimiento']").value;
    estado = document.querySelector("form[id='form-requerimiento'] input[name='estado']").value;
    fuente = document.querySelector("form[id='form-requerimiento'] select[name='fuente_id']").value;
    fuente_det = document.querySelector("form[id='form-requerimiento'] select[name='fuente_det_id']").value;
    // para_stock_almacen = document.querySelector("form[id='form-requerimiento'] input[name='para_stock_almacen']").checked;
    rol_aprobante = document.querySelector("form[id='form-requerimiento'] select[name='rol_aprobante']").value;
    id_trabajador = document.querySelector("form[id='form-requerimiento'] input[name='id_trabajador']").value;

    requerimiento = {
        id_requerimiento,
        fecha_creacion_cc,
        id_cc,
        tipo_cuadro,
        tipo_requerimiento,
        confirmacion_pago,
        // codigo,
        concepto,
        fecha_requerimiento,
        id_prioridad,
        id_empresa,
        id_sede,
        id_grupo,
        // id_area,
        // nombre_area,
        id_moneda,
        id_periodo,
        id_proyecto,
        id_rol,
        codigo_occ,
        tipo_cliente,
        id_cliente,
        id_persona,
        direccion_entrega,
        id_cuenta,
        nro_cuenta,
        cci,
        telefono,
        email,
        ubigeo,
        id_almacen,
        almacen_id_sede,
        almacen_id_empresa,
        observacion,
        monto,
        fecha_entrega,
        tiene_transformacion,
        justificacion_generar_requerimiento,
        estado,
        fuente,
        fuente_det,
        // para_stock_almacen,
        rol_aprobante,
        id_trabajador
        
    };
return requerimiento;
}

function get_data_detalle_requerimiento(){

    let id_cc_am_filas = null;
    let id_cc_venta_filas=null;
    if( tempDetalleItemCCSelect.hasOwnProperty('id_cc_am_filas')){
        id_cc_am_filas = tempDetalleItemCCSelect.id_cc_am_filas;
    }else if(tempDetalleItemCCSelect.hasOwnProperty('id_cc_venta_filas')){
        id_cc_venta_filas = tempDetalleItemCCSelect.id_cc_venta_filas;
    }
 
    var id_item = $('[name=id_item]').val();
    var id_tipo_item = $('[name=id_tipo_item]').val();
    var id_producto = $('[name=id_producto]').val();
    var id_servicio = $('[name=id_servicio]').val();
    var id_equipo = $('[name=id_equipo]').val();
    var id_detalle_requerimiento = $('[name=id_detalle_requerimiento]').val();
    var cod_item = $('[name=codigo_item]').val();
    var part_number = $('[name=part_number]').val();
    var des_item = $('[name=descripcion_item]').val();
    // var id_unidad_medida = $('[name=unidad_medida_item]').val() !=="" ?$('[name=unidad_medida_item]').val():0;
    var id_unidad_medida = $('[name=unidad_medida_item]').val();
    // var und = document.getElementsByName("unidad_medida_item")[0];
    // var und_text = und.options[und.selectedIndex].text;   
    var und_text = $('[name=unidad_medida_item]').find('option:selected').text();
    var cantidad = $('[name=cantidad_item]').val();
    var precio_referencial = $('[name=precio_ref_item]').val();
    var id_tipo_moneda = $('[name=tipo_moneda]').val();
    var tipo_moneda = $('[name=tipo_moneda] option:selected').text()
    var categoria = $('[name=categoria]').val();
    var subcategoria = $('[name=subcategoria]').val();
    var fecha_entrega = $('[name=fecha_entrega_item]').val();
    var lugar_entrega = $('[name=lugar_entrega_item]').val();
    var id_partida = $('[name=id_partida]').val();
    var cod_partida = $('[name=cod_partida]').val();
    var des_partida = $('[name=des_partida]').val();
    var id_almacen_reserva = $('[name=id_almacen_reserva]').val();
    var almacen_descripcion = $('[name=almacen_descripcion]').val();
    if($('[name=estado]').val() === ""){
        var estado = 1;
    }else{
        var estado = $('[name=estado]').val();
        
    }
    
    let tiene_transformacion = document.querySelector("form[id='form-requerimiento'] input[name='tiene_transformacion']").value;


    let item = {
        'id_item':parseInt(id_item),
        'id_tipo_item':parseInt(id_tipo_item),
        'id_producto':parseInt(id_producto),
        'id_servicio':parseInt(id_servicio),
        'id_equipo':parseInt(id_equipo),
        'id_detalle_requerimiento':parseInt(id_detalle_requerimiento),
        'cod_item':cod_item,
        'part_number':part_number,
        'des_item':des_item,
        'id_unidad_medida':parseInt(id_unidad_medida),
        'unidad':und_text,
        'cantidad':parseFloat(cantidad),
        'precio_referencial':parseFloat(precio_referencial)?parseFloat(precio_referencial):null,
        'id_tipo_moneda':id_tipo_moneda,
        'tipo_moneda':tipo_moneda,
        'categoria':categoria,
        'subcategoria':subcategoria,
        'fecha_entrega':fecha_entrega,
        'lugar_entrega':lugar_entrega,
        'id_partida':parseInt(id_partida),
        'cod_partida':cod_partida,
        'des_partida':des_partida,
        'estado':parseInt(estado),
        'id_almacen_reserva':parseInt(id_almacen_reserva),
        'almacen_descripcion':almacen_descripcion,
        'id_cc_am_filas':id_cc_am_filas,
        'id_cc_venta_filas': id_cc_venta_filas,
        'tiene_transformacion':tiene_transformacion
        };
        return item;
}

function validaRequerimiento(){
    var tipo_requerimiento = $('[name=tipo_requerimiento]').val();
    var tipo_cliente = $('[name=tipo_cliente]').val();
    var concepto = $('[name=concepto]').val();
    var empresa = $('[name=empresa]').val();
    var sede = $('[name=sede]').val();
    var id_persona = $('[name=id_persona]').val();
    var id_cliente = $('[name=id_cliente]').val();
    var ubigeo = $('[name=name_ubigeo]').val();
    // var id_almacen = $('[name=id_almacen]').val();
    var telefono_cliente = $('[name=telefono_cliente]').val();
    var fecha_entrega = $('[name=fecha_entrega]').val();
    var direccion_entrega = $('[name=direccion_entrega]').val();
    var email_cliente = $('[name=email_cliente]').val();
    var id_proyecto = $('[name=id_proyecto]').val();

    var msj = '';
    if((tipo_requerimiento == 1 || tipo_requerimiento == 2) && (tipo_cliente == 1 || tipo_cliente ==2)){ //compra || venta directa - pers.natural o pers.juridica

        if (data_item.length <= 0){
            msj+='\n Es necesario que agregue mínimo un Ítem';
        }
        if (concepto.length <= 0){
            msj+='\n Es necesario que ingrese un Concepto';
        }
        if (empresa == ''){
            msj+='\n Es necesario que seleccione una Empresa';
        }
        if (sede == ''){
            msj+='\n Es necesario que seleccione una Sede';
        }

        grupos.forEach(element => {
            if(element.id_grupo ==3){ // proyectos
                if (id_proyecto == '' && id_proyecto == '' ){
                    msj+='\n Es necesario que seleccione un Proyecto';
                }
                if (fecha_entrega == '' && fecha_entrega == '' ){
                    msj+='\n Es necesario que seleccione una fecha entrega';
                }
            }else if(element.id_grupo ==2){// comercial
                if (id_persona == '' && id_cliente == '' ){
                    msj+='\n Es necesario que seleccione un Cliente';
                }
                if (ubigeo == ''){
                    msj+='\n Es necesario que seleccione un Ubigeo';
                }
                // if (id_almacen == '0' || id_almacen == null){
                //     msj+='\n Es necesario que seleccione un Almacén';
                // }
                if (telefono_cliente == ''){
                    msj+='\n Es necesario que seleccione un Teléfono';
                }
                if (email_cliente == ''){
                    msj+='\n Es necesario que ingrese un Email';
                }
                if (direccion_entrega == ''){
                    msj+='\n Es necesario que seleccione una Dirección';
                }

            }else if(element.id_grupo ==1){
                if (fecha_entrega == '' && fecha_entrega == '' ){
                    msj+='\n Es necesario que seleccione una fecha entrega';
                }
            }
        });

    }else if((tipo_requerimiento == 1) && (tipo_cliente == 3)){  // compra - uso almacen 
        if (concepto.length <= 0 || concepto == ''){
            msj+='\n Es necesario que ingrese un Concepto';
        }
        if (data_item.length <= 0){
            msj+='\n Es necesario que agregue mínimo un Ítem';
        }
        if (concepto.length <= 0){
            msj+='\n Es necesario que ingrese un Concepto';
        }
        if (id_almacen == '0' || id_almacen == null){
            msj+='\n Es necesario que seleccione un Almacén';
        }
    }else if((tipo_requerimiento ==0)){  // compra - uso almacen
        msj+='\n Es necesario que seleccione un Tipo de Requerimiento';

    }

    return msj;

}

function cuentaRegresivaModalLoader(totalTime) {
    document.querySelector("div[id='modal-loader'] div[name='loader-cuentra-regresiva']").innerHTML = totalTime;
    
    if(totalTime==0){
            cerrarModalLoader();
        }else{
            totalTime-=1;
            setTimeout("cuentaRegresivaModalLoader("+totalTime+")",1000);
        }
    }

function cerrarModalLoader(){
    $('#modal-loader').modal('hide');
}
function resetModalLoader(){
    document.querySelector("div[id='modal-loader'] div[name='loader']").classList.remove("oculto");
    document.querySelector("div[id='modal-loader'] div[name='loader-status']").innerHTML= `<p>Guardando... <br><br><small>Puede tomar unos segundos</small></p>`;
    document.querySelector("div[id='modal-loader'] div[name='loader-info']").textContent= '';
    document.querySelector("div[id='modal-loader'] button[name='btnCerrarModalLoader']").classList.add("oculto");
    document.querySelector("div[id='modal-loader'] div[name='loader-status']").style.color= "#000";
    document.querySelector("div[id='modal-loader'] div[name='loader-status']").style.fontSize= "1em";
    document.querySelector("div[id='modal-loader'] div[name='loader-status']").style.fontWeight= "normal";

}

function save_requerimiento(action){
    action_requerimiento= action;
    // let actual_id_usuario = userSession.id_usuario;
    let requerimiento = get_data_requerimiento();

    if(requerimiento.estado == 3){
        openSustento();
    }else{
        requerimientoView.actionGuardarEditarRequerimiento();
    }
    
}

function GrabarSustentoRequerimiento(){
    // guardar requerimiento con sustento
    let hascheckedTrue=0;
    if(sustentoObj.length >0){
        sustentoObj.forEach(element => {
            if(element.checked == true){
                hascheckedTrue=+1;
            }
        });
    }

    if(hascheckedTrue>0){
        actionGuardarEditarRequerimiento();
        $('#modal-sustento').modal('hide');
        let requerimiento = get_data_requerimiento();
        mostrar_requerimiento(requerimiento.id_requerimiento);


    }else{
        alert("Debe seleccionar alguna de las observaciones");
    }

}

function openSustento(){ 
    $('#modal-sustento').modal({show: true, backdrop: 'true'});
    fillTablaListaObservacionesPorSustentar(data.observacion_requerimiento)
}

function updateCheckSustento(obj){
    let idSelected =obj.dataset.idAprobacion;
    sustentoObj.forEach((element, index) => {
        if (element.id_aprobacion == idSelected) {
            sustentoObj[index].checked = obj.checked;

        }
    });
}

function updateTexareaSustento(event){
    let idSelected = event.target.dataset.idAprobacion;
    let textValor = event.target.value;
    sustentoObj.forEach((element, index) => {
            if (element.id_aprobacion == idSelected) {
                sustentoObj[index].sustento = textValor;

            }
        });

 
}
function fillTablaListaObservacionesPorSustentar(data){
    sustentoObj=[];
    data.forEach(element => {
        sustentoObj.push(
            {   
                'id_aprobacion':element.id_aprobacion,
                'checked':true,
                'sustento':null
            }
        )
        
    });
    var vardataTables = funcDatatables();
    $('#tablaListaObservacionesPorSustentar').dataTable({
        bDestroy: true,
        order: [[0, 'asc']],
        info:     true,
        iDisplayLength:2,
        paging:   true,
        searching: false,
        language: vardataTables[0],
        processing: true,
        bDestroy: true,
        data:data ,
        columns: [
            {'render':
                function (data, type, row, meta){
                    return `<input type="checkbox" data-id-aprobacion="${row.id_aprobacion}" onchange="updateCheckSustento(this);" checked />`;
                }
            },
            { data: 'nombre_completo' },
            { data: 'descripcion' },
            {'render':
                function (data, type, row, meta){
                    
                return `<textarea class="form-control" name="sustentacion" data-id-aprobacion="${row.id_aprobacion}" cols="100" rows="100" style="height:50px;" onkeyup ="updateTexareaSustento(event);" ></textarea>`;
                }
            }
        ],

    })

    let tablaListaObservacionesPorSustentar = document.getElementById('tablaListaObservacionesPorSustentar_wrapper');
    tablaListaObservacionesPorSustentar.childNodes[0].childNodes[0].hidden = true;
}