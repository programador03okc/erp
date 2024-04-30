$(function () {
    $("#form-clave").on('submit',function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var clave = $('.contraseña-validar[name="clave"]').val(),
            repita_clave = $('.contraseña-validar[name="repita_clave"]').val(),
            regularExpression = /^(?=^.{8,}$)((.)(?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/;
            success=false;

        if (clave === repita_clave) {
            if (regularExpression.test(clave)) {
                success=true;
            } else {
                success=false;
                Swal.fire('Información', 'Su nueva contraseña debe tener al menos 8 caracteres alfanuméricos. Ejemplos: Inicio01., Inicio01.@, @"+*}-+', 'warning');
            }
        }else{
            Swal.fire('Información', 'Su clave no coincide, ingrese correctamente en ambos campos su clave', 'warning');
        }

        if (success) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                url: route('actualizar-clave'),
                data: data,
                dataType: 'JSON',
                success: (data) => {
                    setTimeout(() => { window.location.reload(); }, 2000);
                }
            }).done(function(response) {
                if (response.success===true) {
                    $('#actualizar-clave').modal('hide');
                    Swal.fire('Éxito', 'Se actualizo con éxito', 'success')
                } else {
                    Swal.fire('Información', 'Ingrese de nuevo su clave', 'warning' )
                }
            }).fail( function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
        }

    });
});

function validarClave() {
    if (auth_user.renovar == true) {
        $.ajax({
            url: route('validar-clave'),
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                if (data.success === true) {
                    // $('#actualizar-contraseña').modal('show');
                    $('#actualizar-clave').modal('show');
                }
            }
        });
    }
}
function cambiarClave() {
    $("#actualizar-clave").modal({
        show: true,
        backdrop: "static"
    });
    $("#actualizar-clave").on("shown.bs.modal", function () {
        $("[name=clave]").focus();
    });
}

function notificacionesNoLeidas() {
    const $spanNotificaciones = $('#spanNotificaciones');
    $.ajax({
        url: route("notificaciones.cantidad-no-leidas"),
        data: {_token: token},
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            $spanNotificaciones.html(data.mensaje);
            if (data.mensaje > 0) {
                $spanNotificaciones.removeClass('label-default');
                $spanNotificaciones.addClass('label-warning');
            } else {
                $spanNotificaciones.removeClass('label-warning');
                $spanNotificaciones.addClass('label-default');
            }
        }
    });
}
