$(function () {
    vista_extendida();
    var vardataTables = funcDatatables();
    var form = $('.page-main form[type=register]').attr('id');

    $('#listaRequerimientosSinAtender').dataTable({
        'dom': vardataTables[1],
        'buttons': vardataTables[2],
        'language': vardataTables[0],
        "processing": true,
        "bDestroy": true,
        'ajax': route('configuracion.documentos.requerimientos-sin-atender.lista'),
        'columns': [
            { 'data': 'id' },
            { 'data': 'codigo' },
            { 'data': 'concepto' },
            { 'data': 'tipo_requerimiento' },
            { 'data': 'empresa' },
            { 'data': 'sede' },
            { 'data': 'grupo' },
            { 'data': 'division' },
            { 'data': 'fecha_emision' },
            {
                'data': 'monto_total', 'render': function (data, type, row) {
                    return row.simbolo_moneda + row.monto_total;
                }
            },
            { 'data': 'creado_por' },
            { 'data': 'estado' },
            {
                'render':
                    function (data, type, row, meta) {
                        return (`
                             
                            <div class="text-center" style="display:flex;">
                                <button type="button" class="btn btn-sm btn-warning btn-flat botonList" data-toggle="tooltip" data-placement="bottom" title="Retornar a elaborado" onClick="retornarDocumento('${row['id']}');"><i class="fas fa-undo"></i></button>
                                <button type="button" class="btn btn-sm btn-danger btn-flat botonList" data-toggle="tooltip" data-placement="bottom" title="Anular" onClick="anularDocumento('${row['id']}');"><i class="fas fa-ban"></i></button>
                            </div>
                                `);
                    }
            }
        ],
        'order': [
            [8, 'desc']
        ]
    });

    $('.group-table .mytable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('eventClick')) {
            $(this).removeClass('eventClick');
        } else {
            $('.dataTable').dataTable().$('tr.eventClick').removeClass('eventClick');
            $(this).addClass('eventClick');
        }
        // var id = $(this)[0].firstChild.innerHTML;
        changeStateButton('historial');
    });
    resizeSide();
});

function anularDocumento(id) {

    let sustentoAnulacion = '';
    Swal.fire({
        title: 'Sustente el motivo de la anulación',
        input: 'textarea',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Registrar',

        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            sustentoAnulacion = result.value;

            makeAnularDocumento(id, sustentoAnulacion).then((response) => {
                $("#wrapper-okc").LoadingOverlay("hide", true);

                console.log(response);
                if (response.status == 200) {
                    restablecerFormularioOrden();
                    Lobibox.notify(response.status == 200 ? 'success' : 'warning', {
                        title: false,
                        size: 'mini',
                        rounded: true,
                        sound: false,
                        delayIndicator: false,
                        msg: response.mensaje
                    });


                } else {
                    $("#wrapper-okc").LoadingOverlay("hide", true);

                        $('#listaRequerimientosSinAtender').DataTable().ajax.reload(null, false);

                    Swal.fire(
                        '',
                        response.mensaje,
                        response.tipo_estado
                    );
                    console.log(response);

                }
            }).catch((err) => {
                $("#wrapper-okc").LoadingOverlay("hide", true);
                console.log(err)
                Swal.fire(
                    '',
                    'Lo sentimos hubo un error en el servidor, por favor vuelva a intentarlo',
                    'error'
                );
            });

        }
    })
}

function makeAnularDocumento(id, sustento) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'POST',
            url: `anular`,
            data: { 'id_documento': id, 'sustento': sustento },
            dataType: 'JSON',
            beforeSend: data => {

                $("#wrapper-okc").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },
            success(response) {
                resolve(response);
            },
            error: function (err) {
                reject(err)
            }
        });
    });
}

function retornarDocumento(id) {

    let sustentoAnulacion = '';
    Swal.fire({
        title: 'Sustente el motivo de retornar a estado elaborado el requerimiento',
        input: 'textarea',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Registrar',

        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            sustentoAnulacion = result.value;

            makeRetornarDocumento(id, sustentoAnulacion).then((response) => {
                $("#wrapper-okc").LoadingOverlay("hide", true);

                console.log(response);
                if (response.status == 200) {
                    restablecerFormularioOrden();
                    Lobibox.notify(response.status == 200 ? 'success' : 'warning', {
                        title: false,
                        size: 'mini',
                        rounded: true,
                        sound: false,
                        delayIndicator: false,
                        msg: response.mensaje
                    });


                } else {
                    $("#wrapper-okc").LoadingOverlay("hide", true);

                        $('#listaRequerimientosSinAtender').DataTable().ajax.reload(null, false);

                    Swal.fire(
                        '',
                        response.mensaje,
                        response.tipo_estado
                    );
                    console.log(response);

                }
            }).catch((err) => {
                $("#wrapper-okc").LoadingOverlay("hide", true);
                console.log(err)
                Swal.fire(
                    '',
                    'Lo sentimos hubo un error en el servidor, por favor vuelva a intentarlo',
                    'error'
                );
            });

        }
    })
}

function makeRetornarDocumento(id,sustento) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'POST',
            url: `retornar`,
            data: { 'id_documento': id,'sustento':sustento },
            dataType: 'JSON',
            beforeSend: data => {

                $("#wrapper-okc").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            },
            success(response) {
                resolve(response);
            },
            error: function (err) {
                reject(err)
            }
        });
    });
}