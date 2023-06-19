$(function(){
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaGrupo').dataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language' : vardataTables[0],
        "processing": true,
        "bDestroy": true,
        'ajax': route('administracion.grupos.listar_grupo'),
        'columns': [
            {'data': 'id_grupo'},
            {'data': 'empresa'},
            {'data': 'sede'},
            {'data': 'descripcion'},
            {'data': 'cod_grupo', className: 'text-center'}
        ],
        'order': [
            [1, 'asc'], [2, 'asc']
        ]
    });
    $('#listaGrupo').DataTable().on("draw", function(){
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
        mostrar_grupo(id);
        changeStateButton('historial');
    });
    resizeSide();
});

function mostrar_grupo(id){
    baseUrl = route('administracion.grupos.cargar_grupo', {id: id});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('[name=id_grupo]').val(response[0].id_grupo);
            $('[name=empresa]').val(response[0].id_empresa);
            buscarSede(response[0].id_empresa, response[0].id_sede);
            $('[name=sede]').text(response[0].id_sede);
            $('[name=descripcion]').val(response[0].descripcion);
            $('[name=codigo]').val(response[0].cod_grupo);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function save_grupo(data, action){
    var msj;
    if (action == 'register'){
        baseUrl = route('administracion.grupos.guardar_grupo');
        msj = 'Grupo registrado con exito';
    }else if(action == 'edition'){
        baseUrl = route('administracion.grupos.editar_grupo');
        msj = 'Grupo editado con exito';
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
                $('#listaGrupo').DataTable().ajax.reload();
                changeStateButton('guardar');
            }else if(response == 'exist'){
                alert('Error, Ya existe un grupo con esa descripción');
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

function anular_grupo(ids){
    baseUrl = route('administracion.grupos.anular_grupo', {id: ids});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            if (response > 0){
                alert('Grupo anulado con exito');
                $('#listaGrupo').DataTable().ajax.reload();
                changeStateButton('anular');
                clearForm('form-grupo');
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