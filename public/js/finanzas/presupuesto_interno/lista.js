

$(document).ready(function () {
    lista();
    listarFinalizados();
});
function vistaCrear() {
    window.location.href = "crear";
}
function lista() {
    var vardataTables = funcDatatables();
    const button_nuevo = (array_accesos.find(
        element => element === 297)?
            {
            text: '<i class="fa fa-plus"></i> Nuevo presupuesto',
            attr: {
                id: 'btn-nuevo',
                href:'crear',
            },
            action: () => {
                // vistaCrear();
                window.location.href='crear';
                // window.open('crear');
            },
            init: function(api, node, config) {
                $(node).removeClass('btn-default')
            },
            className: 'btn-primary btn-sm'
        }:
        []
    );
    const button_cierrre_mes = (array_accesos.find(
        element => element === 314)?
            {
            text: '<i class="fa fa-calendar-times"></i> Cierre mensual',
            attr: {
                id: 'btn-cierre-mensual',
            },
            action: () => {
                // vistaCrear();
                cierreMesual();
                // window.open('crear');
            },
            init: function(api, node, config) {
                $(node).removeClass('btn-default')
            },
            className: 'btn-danger btn-sm'
        }:
        []
    );
    const button_reporte_total = (array_accesos.find(
        element => element === 302)?
            {
            text: '<i class="fa fa-project-diagram"></i> Reporte General',
            attr: {
                id: 'btn-reporte-general',
            },
            action: () => {
                // vistaCrear();
                window.open(route('finanzas.presupuesto.presupuesto-interno.reporte-anual', {year:2024}));
            },
            init: function(api, node, config) {
                $(node).removeClass('btn-default')
            },
            className: 'btn-default btn-sm'
        }:
        []
    );
    const button =[button_nuevo,button_reporte_total]
    var tableRequerimientos = $("#lista-presupuesto-interno").DataTable({
        language: vardataTables[0],
        destroy: true,
        pageLength: 10,
        serverSide: true,
        lengthChange: false,
        dom: vardataTables[1],
        buttons:button,
        ajax: {
            url: "lista-presupuesto-interno",
            type: "POST",
            data:{
                // filtros
                _token:token
            },
            beforeSend: data => {
                $("#lista-presupuesto-interno").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            }
        },
        columns: [
            {data: 'id_presupuesto_interno', name:"id_presupuesto_interno" },
            {data: 'codigo', name:"codigo" , class:"text-center"},
            {data: 'descripcion', name:"descripcion" , class:"text-center"},
            {data: 'fecha_registro', name:"fecha_registro" , class:"text-center"},
            {data: 'grupo', name:"grupo" , class:"text-center"},
            {data: 'estadopi', name:"estadopi" , class:"text-center"},
            {data: 'sede', name:"sede" , class:"text-center"},
            {data: 'total', name:"total" , class:"text-center"},
            {
                class:"text-center",
                render: function (data, type, row) {
                    return '<a href="#" data-action="exportar-ejecutado" data-id="'+row['id_presupuesto_interno']+'">'+row['total_ejecutado']+'</a>';
                }
            },
            {
                render: function (data, type, row) {
                    html='';
                        (array_accesos.find(element => element === 300)?
                        html+='<button type="button" class="btn text-black btn-flat botonList ver-presupuesto-interno" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Exportar Excel" data-original-title="Ver"><i class="fa fa-file-excel"></i></button>'
                        :'');
                        (array_accesos.find(element => element === 298)?
                        html+='<button type="button" class="btn btn-warning btn-flat botonList editar-registro" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Editar" data-original-title="Editar"><i class="fa fa-edit"></i></button>'
                        :'');
                        if (row['estado']==2) {
                            (array_accesos.find(element => element === 302)?
                            html+='<button type="button" class="btn btn-danger btn-flat botonList editar-registro-aprobado" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Editar Presupuesto Interno Aprobado" data-original-title="Editar"><i class="fa fa-pencil-alt"></i></button>'
                            :'');
                        }
                        // html+='<button type="button" class="btn btn-info btn-flat botonList editar-monto-partida" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Editar monto por partida" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>';

                        if (row['estado']==1) {
                            (array_accesos.find(element => element === 301)?
                            html+='<button type="button" class="btn btn-success btn-flat botonList aprobar-presupuesto" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Aprobar" data-original-title="Aprobar"><i class="fa fa-thumbs-up"></i></button>'
                            :'');

                            (array_accesos.find(element => element === 299)?
                            html+='<button type="button" class="btn btn-danger btn-flat botonList eliminar" data-id="'+row['id_presupuesto_interno']+'" title="Eliminar"><i class="fa fa-trash"></i></button>'
                            :'');
                        }

                        html+='<button type="button" class="btn btn-default text-black btn-flat botonList saldos-presupuesto" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Exportar Saldos" data-original-title="Exportar Saldos"><i class="fa fa-file-alt"></i></button>';

                        html +='<a href="#" data-action="exportar-ejecutado" data-id="'+row['id_presupuesto_interno']+'" class="btn btn-default text-black btn-flat botonList " title="Exportar Ejecutados" data-original-title="Exportar Ejecutados"><i class="fa fa-file"></i></a>';

                        html +='<a href="#" data-action="saldos-mensual" data-id="'+row['id_presupuesto_interno']+'" class="btn btn-default text-black btn-flat btn-sm" title="Saldos mensuales" data-original-title="Ver los saldos del mes"><i class="fa fa-balance-scale"></i></a>'
                        html +='<a href="#" data-action="movimeintos-mensual" data-id="'+row['id_presupuesto_interno']+'" class="btn btn-default text-black btn-flat btn-sm" title="Movimientos mensuales" data-original-title="Movimientos"><i class="fa fa-book"></i></a>';
                    html+='';
                    return html;
                },
                className: "text-center"
            }
        ],
        order: [[0, "desc"]],
        columnDefs: [{ aTargets: [0], sClass: "invisible" }],
        "drawCallback": function (settings) {

            $("#lista-presupuesto-interno").LoadingOverlay("hide", true);
        }
    });
}
$(document).on('click','.editar-registro',function () {
    var id = $(this).attr('data-id'),
        token = $('meta[name="csrf-token"]').attr('content'),
        form = $('<form action="'+route_editar+'" method="POST">'+
            '<input type="hidden" name="_token" value="'+token+'">'+
            '<input type="hidden" name="id" value="'+id+'">'+
        '</form>');
        $('body').append(form);
        form.submit();
});
$(document).on('click','.editar-registro-aprobado',function () {
    var id = $(this).attr('data-id'),
        token = $('meta[name="csrf-token"]').attr('content'),
        form = $('<form action="'+route_editar_presupuesto_aprobado+'" method="POST">'+
            '<input type="hidden" name="_token" value="'+token+'">'+
            '<input type="hidden" name="id" value="'+id+'">'+
        '</form>');
        $('body').append(form);
        form.submit();
});

$(document).on('click','.eliminar',function () {
    var id = $(this).attr('data-id');
    Swal.fire({
        title: 'Anular',
        text: "¿Está seguro de anular?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'no',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            return $.ajax({
                type: 'POST',
                url: 'eliminar',
                data: {id:id},
                // processData: false,
                // contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {

                }
            }).done(function(response) {
                return response
            }).fail( function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

        },
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.status===200) {
                $('#lista-presupuesto-interno').DataTable().ajax.reload();
                Swal.fire({
                    title: 'Éxito',
                    text: "Se guardo con éxito",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((resultado) => {
                    if (resultado.isConfirmed) {

                    }
                })
            }
        }
    });
});
$(document).on('click','.ver-presupuesto-interno',function () {
    var id = $(this).attr('data-id'),
        token = $('meta[name="csrf-token"]').attr('content'),
        form = $('<form action="get-presupuesto-interno" method="POST" target="_blank">'+
            '<input type="hidden" name="_token" value="'+token+'">'+
            '<input type="hidden" name="id" value="'+id+'">'+
        '</form>');
        $('body').append(form);
        form.submit();


});
$(document).on('click','.aprobar-presupuesto',function () {
    var id = $(this).attr('data-id');
    Swal.fire({
        title: 'Aprobar',
        text: "¿Está seguro de aprobar?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            return $.ajax({
                type: 'POST',
                url: 'aprobar',
                data: {id:id},
                // processData: false,
                // contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {

                }
            }).done(function(response) {
                return response
            }).fail( function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

        },
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.status===200) {
                $('#lista-presupuesto-interno').DataTable().ajax.reload();
                Swal.fire({
                    title: 'Éxito',
                    text: "Se aprobar con éxito",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((resultado) => {
                    if (resultado.isConfirmed) {

                    }
                })
            }
        }
    });
});
$(document).on('click','.editar-monto-partida',function () {
    var id = $(this).attr('data-id');
    $('[data-form="editar-monto-partida"]')[0].reset();
    $('[data-form="editar-monto-partida"]').find('input[name="id"]').val(id);
    $('#modal-editar-monto-partida').modal('show');
    $('.search-partidas').val(null).trigger('change');
});
$(document).on('submit','[data-form="editar-monto-partida"]',function (e) {
    e.preventDefault();
    var data = $(this).serialize(),
        this_button = $(this).find('[type="submit"]');

        this_button.attr('disabled','true');
    $.ajax({
        type: 'POST',
        url: 'editar-monto-partida',
        data: data,
        dataType: 'JSON',
        beforeSend: (data) => {

        }
    }).done(function(response) {
        if (response===true) {
            $('#modal-editar-monto-partida').modal('hide');
        }
        if (response===false) {
            Swal.fire(
                'Información',
                'El registo que ingreso no es posible modificarse',
                'warning'
            )
        }
        this_button.removeAttr('disabled');
    }).fail( function( jqXHR, textStatus, errorThrown ){
        this_button.removeAttr('disabled');
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
});

function cierreMesual() {
    Swal.fire({
        title: 'Cerrar el mes',
        text: "¿Está seguro de cerra el mes?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            return $.ajax({
                type: 'GET',
                url: 'cierre-mes',
                data: {},
                // processData: false,
                // contentType: false,
                dataType: 'JSON',
                beforeSend: (data) => {

                }
            }).done(function(response) {
                return response
            }).fail( function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });

        },
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.success===true) {
                Swal.fire(
                    'Éxito',
                    'Se cerror el mes con éxito',
                    'success'
                  )
            }
        }
    });

    // $.ajax({
    //     type: 'GET',
    //     url: 'cierre-mes',
    //     data: {},
    //     // processData: false,
    //     // contentType: false,
    //     dataType: 'JSON',
    //     beforeSend: (data) => {

    //     }
    // }).done(function(response) {
    //     console.log(response);
    // }).fail( function( jqXHR, textStatus, errorThrown ){
    //     console.log(jqXHR);
    //     console.log(textStatus);
    //     console.log(errorThrown);
    // });
}
$(document).on('click','[data-action="exportar-ejecutado"]',function (e) {
    e.preventDefault();
    let id = $(this).attr('data-id'),
        token = $('meta[name="csrf-token"]').attr('content'),
        form = $('<form action="presupuesto-ejecutado-excel" method="POST" target="_blank">'+
            '<input type="hidden" name="_token" value="'+token+'">'+
            '<input type="hidden" name="id" value="'+id+'">'+
        '</form>');
        $('body').append(form);
    form.submit();
});
$(document).on('click','.saldos-presupuesto',function (e) {
    e.preventDefault();
    console.log(token);
    let id = $(this).attr('data-id'),
        // token = $('meta[name="csrf-token"]').attr('content'),
        form = $('<form action="'+route("finanzas.presupuesto.presupuesto-interno.saldos-presupuesto")+'" method="POST" target="_blank">'+
            '<input type="hidden" name="_token" value="'+token+'">'+
            '<input type="hidden" name="id" value="'+id+'">'+
        '</form>');
        $('body').append(form);
    form.submit();

});


function listarFinalizados() {
    var vardataTables = funcDatatables();
    var tableRequerimientos = $("#data-table-finalizados").DataTable({
        language: vardataTables[0],
        destroy: true,
        pageLength: 10,
        serverSide: true,
        lengthChange: false,
        dom: vardataTables[1],
        buttons:[],
        ajax: {
            url: route("finanzas.presupuesto.presupuesto-interno.listar-finalizados"),
            type: "POST",
            data:{
                // filtros
                _token:token
            },
            beforeSend: data => {
                $("#lista-presupuesto-interno").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            }
        },
        columns: [
            {data: 'id_presupuesto_interno', name:"id_presupuesto_interno" },
            {data: 'codigo', name:"codigo" , class:"text-center"},
            {data: 'descripcion', name:"descripcion" , class:"text-center"},
            {data: 'fecha_registro', name:"fecha_registro" , class:"text-center"},
            {data: 'grupo', name:"grupo" , class:"text-center"},
            {data: 'estadopi', name:"estadopi" , class:"text-center"},
            {data: 'sede', name:"sede" , class:"text-center"},
            {data: 'total', name:"total" , class:"text-center"},
            {
                class:"text-center",
                render: function (data, type, row) {
                    return '<a href="#" data-action="exportar-ejecutado" data-id="'+row['id_presupuesto_interno']+'">'+row['total_ejecutado']+'</a>';
                }
            },
            {
                render: function (data, type, row) {
                    html='';
                        (array_accesos.find(element => element === 300)?
                        html+='<button type="button" class="btn text-black btn-flat botonList ver-presupuesto-interno" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Exportar Excel" data-original-title="Ver"><i class="fa fa-file-excel"></i></button>'
                        :'');
                        // html+='<button type="button" class="btn btn-info btn-flat botonList editar-monto-partida" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Editar monto por partida" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>';



                        html+='<button type="button" class="btn btn-default text-black btn-flat botonList saldos-presupuesto" data-id="'+row['id_presupuesto_interno']+'" data-toggle="tooltip" title="Exportar Saldos" data-original-title="Exportar Saldos"><i class="fa fa-file-alt"></i></button>';

                        html +='<a href="#" data-action="exportar-ejecutado" data-id="'+row['id_presupuesto_interno']+'" class="btn btn-default text-black btn-flat botonList " title="Exportar Ejecutados" data-original-title="Exportar Ejecutados"><i class="fa fa-file"></i></a>';
                    html+='';
                    return html;
                },
                className: "text-center"
            }
        ],
        order: [[0, "desc"]],
        columnDefs: [{ aTargets: [0], sClass: "invisible" }],
        "drawCallback": function (settings) {

            $("#lista-presupuesto-interno").LoadingOverlay("hide", true);
        }
    });
}
$(document).on('click','[data-action="saldos-mensual"]',function (e) {
    e.preventDefault();

    let id = $(this).attr('data-id');
    // console.log(id);
    window.open(route("finanzas.presupuesto.presupuesto-interno.saldos-mensual",{id:id}), '_blank');

});

$(document).on('click','[data-action="movimeintos-mensual"]',function (e) {
    e.preventDefault();

    let id = $(this).attr('data-id');
    console.log(id);
    window.open(route("finanzas.presupuesto.presupuesto-interno.saldo-movimiento-mensual",{id:id}));
    // $.ajax({
    //     type: 'GET',
    //     url: route("finanzas.presupuesto.presupuesto-interno.saldo-movimiento-mensual",{id:id}),
    //     // data: {id:id},
    //     dataType: 'JSON',
    //     beforeSend: (data) => {

    //     }
    // }).done(function(data) {
    //     console.log(data);
    // }).fail( function( jqXHR, textStatus, errorThrown ){
    //     console.log(jqXHR);
    //     console.log(textStatus);
    //     console.log(errorThrown);
    // });
});
