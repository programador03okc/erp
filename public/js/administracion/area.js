$(function(){
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaArea').dataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        "processing": true,
        "bDestroy": true,
        'ajax': route('administracion.areas.listar_area'),
        'columns': [
            {'data': 'id_area'},
            {'data': 'empresa'},
            {'data': 'sede', className: 'text-center'},
            {'data': 'grupo'},
            {'data': 'descripcion'},
            {'data': 'codigo', className: 'text-center'}
        ],
        'order': [
            [1, 'asc'], [2, 'asc']
        ]
    });
    $('#listaArea').DataTable().on("draw", function(){
        resizeSide();
    })

    $('.group-table .mytable tbody').on('click', 'tr', function(){
        if ($(this).hasClass('eventClick')){
            $(this).removeClass('eventClick');
        } else {
            $('.dataTable').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        var id = $(this)[0].firstChild.innerHTML;
        clearForm(form);
        mostrar_area(id);
        changeStateButton('historial');
    });
    resizeSide();
});

function mostrar_area(id){
    baseUrl = route('administracion.areas.cargar_area', {id: id});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            console.log(response);
            $('[name=id_area]').val(response[0].id_area);
            $('[name=empresa]').val(response[0].id_empresa);
            buscarSede(response[0].id_empresa, response[0].id_sede);
            $('[name=sede]').text(response[0].id_sede);
            buscarGrupo(response[0].id_sede, response[0].id_grupo);
            $('[name=grupo]').text(response[0].id_grupo);
            $('[name=descripcion]').val(response[0].descripcion);
            $('[name=codigo]').val(response[0].codigo);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_area(data, action){
    var msj;
    if (action == 'register'){
        baseUrl = route('administracion.areas.guardar_area');
        msj = 'Area registrada con exito';
    }else if(action == 'edition'){
        baseUrl = route('administracion.areas.editar_area');
        msj = 'Area editada con exito';
    }
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        data: data,
        dataType: 'JSON',
        success: function(response){
            if (response == 'ok'){
                alert(msj);
                $('#listaArea').DataTable().ajax.reload();
                changeStateButton('guardar');
            }else if(response == 'exist'){
                alert('Error, Ya existe un area con esa descripción');
                changeStateButton('historial');
            }else if(response == 'null'){
                alert('Error, No se grabaron los datos, intente más tarde');
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function anular_area(ids){
    baseUrl = route('administracion.areas.anular_area', {id: ids});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            if (response > 0){
                alert('Area anulada con exito');
                $('#listaArea').DataTable().ajax.reload();
                changeStateButton('anular');
                clearForm('form-area');
            }
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function buscarSede(value, seleccion){
    $('[name=sede]').empty();
    baseUrl = route('administracion.grupos.combo_sede_empresa', {value:value});
    $.ajax({
        type: 'GET',
        url: baseUrl,
        dataType: 'JSON',
        success: function(response) {
            response.forEach(element => {
                if (element.id_sede == seleccion){
                    opt = '<option value="'+element.id_sede+'" selected>'+element.descripcion+'</option>';
                }else{
                    opt = '<option value="'+element.id_sede+'">'+element.descripcion+'</option>';
                }
            });
            $('[name=sede]').append(opt);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function buscarGrupo(value, seleccion){
    $('[name=grupo]').empty();
    baseUrl = route('administracion.areas.combo_grupo_sede', {value: value});
    $.ajax({
        type: 'GET',
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            response.forEach(element => {
                if (element.id_grupo == seleccion){
                    opt = '<option value="'+element.id_grupo+'" selected>'+element.descripcion+'</option>';
                }else{
                    opt = '<option value="'+element.id_grupo+'">'+element.descripcion+'</option>';
                }
            });
            $('[name=grupo]').append(opt);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}
