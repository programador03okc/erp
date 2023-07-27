function cargarDep(){
    limpiar();
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: route('cargar_departamento'),
        dataType: 'JSON',
        success: function(response){
            Object.keys(response).forEach(function(key){
                var opt = '<option value="'+response[key].id_dpto+'">'+response[key].descripcion+'</option>';
                $('#depart').append(opt);
            });
        }
    }).fail( function( jqXHR, textStatus, errorThrown ){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function cargarProv(value){
    baseUrl = route('cargar_provincia', {id:value});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('#provin').html('<option value="0" selected disabled>Elija una opción</option>' + response);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function cargarDist(value){
    baseUrl = route('cargar_distrito', {id:value});
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: baseUrl,
        dataType: 'JSON',
        success: function(response){
            $('#distri').html('<option value="0" selected disabled>Elija una opción</option>' + response);
        }
    }).fail( function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function limpiar(){
    $('#depart').val(0);
    $('#depart').empty();
    $('#provin').empty();
    $('#distri').empty();
}
