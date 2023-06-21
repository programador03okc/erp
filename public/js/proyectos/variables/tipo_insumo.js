$(function(){
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaTipoInsumo').dataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        'ajax': route('proyectos.variables-entorno.tipos-insumo.listar_tipo_insumos'),
        'columns': [
            {'data': 'id_tp_insumo'},
            {'data': 'codigo'},
            {'data': 'descripcion'},
            {'render':
                function (data, type, row){
                    return ((row['estado'] == 1) ? 'Activo' : 'Inactivo');
                }
            },
            {'render':
                function (data, type, row){
                    return (formatDate(row['fecha_registro']));
                }
            }
        ],
        'columnDefs': [{ 'aTargets': [0], 'sClass': 'invisible'}],
    });

    $('.group-table .mytable tbody').on('click', 'tr', function(){
        if ($(this).hasClass('eventClick')){
            $(this).removeClass('eventClick');
        } else {
            $('.dataTable').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        var id = $(this)[0].firstChild.innerHTML;
        clearForm(form);
        mostrar_tipo_insumo(id);
        changeStateButton('historial');
    });

    
});

function mostrar_tipo_insumo(id){
    baseUrl = route('proyectos.variables-entorno.tipos-insumo.mostrar_tipo_insumo', {id: id});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('[name=id_tp_insumo]').val(response[0].id_tp_insumo);
            $('[name=descripcion]').val(response[0].descripcion);
            $('[name=codigo]').val(response[0].codigo);
            $('[name=estado]').val(response[0].estado);
            $('[id=fecha_registro] label').text('');
            $('[id=fecha_registro] label').append(formatDateHour(response[0].fecha_registro));
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_tipo_insumo(data, action){
    if (action == 'register'){
        baseUrl = route('proyectos.variables-entorno.tipos-insumo.guardar_tipo_insumo');
        mensaje = "Tipo de Insumo registrado con exito";
    } else if (action == 'edition'){
        baseUrl = route('proyectos.variables-entorno.tipos-insumo.actualizar_tipo_insumo');
        mensaje = "Tipo de Insumo actualizado con exito";
    }
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function(response){
            alert(mensaje);
            if (response > 0){
                $('#listaTipoInsumo').DataTable().ajax.reload();
                changeStateButton('guardar');
            }
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function anular_tipo_insumo(ids){
    baseUrl = route('proyectos.variables-entorno.tipos-insumo.anular_tipo_insumo', {id: ids});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': token},
        url: route('proyectos.variables-entorno.tipos-insumo.revisar_tipo_insumo', {id: ids}),
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            if (response >= 1){
                alert('No es posible anular. \nEl tipo_insumo seleccionado está relacionado con '
                +response+' insumo(s).');
            }
            else {
                $.ajax({
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': token},
                    url: baseUrl,
                    dataType: 'JSON',
                    success: function(response){
                        console.log(response);
                        if (response > 0){
                            alert('Tipo de Insumo anulado con exito');
                            $('#listaTipoInsumo').DataTable().ajax.reload();
                            changeStateButton('anular');
                            clearForm('form-tipo_insumo');
                        }
                    }
                }).fail( function( jqXHR, textStatus, errorThrown ){
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
    
}