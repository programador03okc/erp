$(function(){
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaCategoriaInsumo').dataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        'ajax': route('proyectos.variables-entorno.categorias-insumo.listar_cat_insumos'),
        'columns': [
            {'data': 'id_categoria'},
            {'data': 'descripcion'},
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
        mostrar_cat_insumo(id);
        changeStateButton('historial');
    });


});

function mostrar_cat_insumo(id){
    baseUrl = route('proyectos.variables-entorno.categorias-insumo.mostrar_cat_insumo', {id: id});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('[name=id_categoria]').val(response[0].id_categoria);
            $('[name=descripcion]').val(response[0].descripcion);
            $('[id=fecha_registro] label').text('');
            $('[id=fecha_registro] label').append(formatDateHour(response[0].fecha_registro));
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_cat_insumo(data, action){
    console.log(data);
    console.log(action);
    if (action == 'register'){
        baseUrl = route('proyectos.variables-entorno.categorias-insumo.guardar_cat_insumo');
        mensaje = 'Categoría de insumo registrada con éxito';
    } else if (action == 'edition'){
        baseUrl = route('proyectos.variables-entorno.categorias-insumo.update_cat_insumo');
        mensaje = 'Categoría de insumo actualizada con éxito';
    }
    $.ajax({
        type: 'POST',
        // headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            if (response > 0){
                alert('Categoría registrada con éxito');
                $('#listaCategoriaInsumo').DataTable().ajax.reload();
                changeStateButton('guardar');
            }
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function anular_cat_insumo(ids){
    baseUrl = route('proyectos.variables-entorno.categorias-insumo.anular_cat_insumo', {id: ids});
    $.ajax({
        type: 'GET',
        // headers: {'X-CSRF-TOKEN': token},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            if (response > 0){
                alert('Categoría anulada con éxito');
                $('#listaCategoriaInsumo').DataTable().ajax.reload();
                changeStateButton('anular');
                clearForm('form-cat_insumo');
            }
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });


}
