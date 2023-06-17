$(function() {
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaRol').dataTable({
        'dom': vardataTables[1],
        'pageLength': 15,
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        "processing": true,
        "bDestroy": true,
        'ajax': route('configuracion.roles.listar'),
        'columns': [
            {'data': 'id_rol'},
            {'data': 'descripcion'}
        ],
        'order': [
            [1, 'asc']
        ]
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
        mostrar_roles(id);
        changeStateButton('historial');
    });
});

function mostrar_roles(id){
    baseUrl = route('configuracion.roles.cargar-roles', {id: id});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('[name=id_rol]').val(response[0][0].id_rol);
            $('[name=descripcion]').val(response[0][0].descripcion);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_rol(data, action){
    var msj;
    if (action == 'register'){
        baseUrl = route('configuracion.roles.guardar_rol');
        msj = 'Rol registrado con exito';
    }else if(action == 'edition'){
        baseUrl = route('configuracion.roles.editar_rol');
        msj = 'Rol editado con exito';
    }
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function(response){
            if (response > 0){
                alert(msj);
                $('#listaRol').DataTable().ajax.reload();
                changeStateButton('guardar');
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function anular_rol(ids){
    baseUrl = route('configuracion.roles.anular_rol', {id: ids});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            if (response > 0){
                alert('Rol anulado con exito');
                $('#listaRol').DataTable().ajax.reload();
                changeStateButton('anular');
                clearForm('form-rol');
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}