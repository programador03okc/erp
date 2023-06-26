function mostrarCentroCostos() {
    $.ajax({
        type: 'GET',
        // headers: {'X-CSRF-TOKEN': token},
        url: 'mostrar-centro-costos',
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            var html = '';
            response.forEach(element => {
                html += `<tr ${element.id_padre == null ? 'style="background: LightCyan;"' : ''}>
                <td width="15%">${element.codigo}</td>
                <td>${element.descripcion}</td>
                <td>${element.grupo_descripcion !== null ? element.grupo_descripcion : ''}</td>
                <td>${element.periodo}</td>
                <td width="15%" style="padding:0px;">
                    <button type="button" class="btn btn-xs btn-info editar" data-toggle="tooltip" data-placement="bottom"
                        title="Editar" data-id="${element.id_centro_costo}" data-codigo="${element.codigo}" data-descripcion="${element.descripcion}"
                        data-grupo="${element.id_grupo}" data-periodo="${element.periodo}" data-codpadre="" data-despadre="">
                        <i class="glyphicon glyphicon-pencil" aria-hidden="true"></i></button>

                    <button type="button" class="btn btn-xs btn-danger anular" data-toggle="tooltip" data-placement="bottom"
                        title="Anular" data-id="${element.id_centro_costo}">
                        <i class="glyphicon glyphicon-remove" aria-hidden="true"></i></button>
                </td>`;
            });
            $('#listaCentroCostos tbody').html(html);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

$("#form-centro-costos").on("submit", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    var id = $('[name=id_centro_costo]').val();
    var url = '';
    $('#submit-cc').attr('disabled', 'true');

    if (id == '') {
        url = 'guardarCentroCosto';
    } else {
        url = 'actualizar-centro-costo';
    }
    console.log(data);
    guardar_cc(data, url);

});

function guardar_cc(data, url) {
    $.ajax({
        type: 'POST',
        // headers: {'X-CSRF-TOKEN': token},
        url: url,
        data: data,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            $('[name=id_centro_costo]').val('');
            $('[name=codigo]').val('');
            $('[name=descripcion]').val('');

            if (url == 'guardarCentroCosto') {
                alert('Se guardó exitosamente.');
            } else {
                alert('Se actualizó exitosamente.');
            }
            $('#submit-cc').removeAttr('disabled');
            mostrarCentroCostos();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

$("#listaCentroCostos tbody").on('click', ".editar", function () {
    var id = $(this).data('id');
    var cod = $(this).data('codigo');
    var des = $(this).data('descripcion');
    var grupo = $(this).data('grupo');
    var per = $(this).data('periodo');
    console.log(grupo);
    $('#submit-cc').removeAttr('disabled');

    $('[name=id_centro_costo]').val(id);
    $('[name=codigo]').val(cod);
    $('[name=descripcion]').val(des);
    $('[name=id_grupo]').val(grupo);
    $('[name=periodo]').val(per);

});

$("#listaCentroCostos tbody").on('click', ".anular", function () {
    var id = $(this).data('id');
    var rspta = confirm('¿Está seguro que desea anular?');
    if (rspta) {
        anular_cc(id);
    }
});

function anular_cc(id) {
    $.ajax({
        type: 'GET',
        // headers: {'X-CSRF-TOKEN': token},
        url: "anular-centro-costo/" + id,
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            mostrarCentroCostos();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    });
}
function listarCentroCostos() {
    var vardataTables = funcDatatables();
    var listarCentroCostos = $("#listaCentroCostos").DataTable({
        language: vardataTables[0],
        destroy: true,
        pageLength: 10,
        serverSide: true,
        lengthChange: false,
        dom: vardataTables[1],
        buttons:[],
        ajax: {
            url: route("finanzas.centro-costos.listar-centro-costos"),
            type: "GET",
            data:{
                // filtros
                _token:token
            },
            beforeSend: data => {
                $("#listaCentroCostos").LoadingOverlay("show", {
                    imageAutoResize: true,
                    progress: true,
                    imageColor: "#3c8dbc"
                });
            }
        },
        columns: [
            // {data: 'codigo' },
            {data: 'codigo' },
            {data: 'descripcion', class:"text-center"},
            {data: 'grupo_descripcion' , class:"text-center"},
            {data: 'periodo' , class:"text-center"},
            {
                render: function (data, type, row) {
                    html='';
                        html+='<button type="button" class="btn btn-xs btn-info editar" data-toggle="tooltip" data-placement="bottom" title="Editar" data-id="'+row['id_centro_costo']+'" data-codigo="'+row['codigo']+'" data-descripcion="'+row['descripcion']+'" data-grupo="'+row['id_grupo']+'" data-periodo="'+row['periodo']+'" data-codpadre="" data-despadre=""><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i></button>';

                        html+='<button type="button" class="btn btn-xs btn-danger anular" data-toggle="tooltip" data-placement="bottom" title="Anular" data-id="'+row['id_centro_costo']+'"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></button>';


                    html+='';
                    return html;
                },
                className: "text-center"
            }
        ],
        createdRow: function (row, data, dataIndex) {
            if (data.id_padre == null) {
                $(row).css('background', 'LightCyan');
            }
        },

        order: [[0, "asc"]],
        // columnDefs: [{ aTargets: [0], sClass: "invisible" }],
        "drawCallback": function (settings) {

            $("#listaCentroCostos").LoadingOverlay("hide", true);
        },

    });
}
