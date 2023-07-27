$(function(){
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaIu').dataTable({
        'dom': vardataTables[1],
        'pageLength': 20,
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        'ajax': route('proyectos.variables-entorno.iu.listar_ius'),
        'columns': [
            {'data': 'id_iu'},
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
            $('#listaIu').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        var id = $(this)[0].firstChild.innerHTML;
        clearForm(form);
        mostrar_iu(id);
        changeStateButton('historial');
    });

});

function mostrar_iu(id){
    baseUrl = route('proyectos.variables-entorno.iu.mostrar_iu', {id: id});
    $.ajax({
        type: 'GET',
        // headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('[name=id_iu]').val(response[0].id_iu);
            $('[name=descripcion]').val(response[0].descripcion);
            $('[name=codigo]').val(response[0].codigo);
            // $('[name=estado]').val(response[0].estado);
            $('[id=fecha_registro] label').text('');
            $('[id=fecha_registro] label').append(formatDateHour(response[0].fecha_registro));
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_iu(data, action){
    if (action == 'register'){
        baseUrl = route('proyectos.variables-entorno.iu.guardar_iu');
        mensaje = "Indice Unificado registrado con exito";
    } else if (action == 'edition'){
        baseUrl = route('proyectos.variables-entorno.iu.actualizar_iu');
        mensaje = "Indice Unificado actualizado con exito";
    }
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            if (response > 0){
                alert('Indice Unificado registrado con exito');
                $('#listaIu').DataTable().ajax.reload();
                changeStateButton('guardar');
            }
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function anular_iu(ids){
    baseUrl = route('proyectos.variables-entorno.iu.anular_iu', {id: ids});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': token},
        url: route('proyectos.variables-entorno.iu.revisar_iu', {id: ids}),
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            if (response >= 1){
                alert('No es posible anular. \nEl iu seleccionado está relacionado con '
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
                            alert('Indice Unificado anulado con exito');
                            $('#listaIu').DataTable().ajax.reload();
                            changeStateButton('anular');
                            clearForm('form-iu');
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
