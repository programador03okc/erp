var modalPage = '';

function ubigeoModal() {
    $('#modal-ubigeo').modal({
        show: true
    });
    listarUbigeos();
}

function listarUbigeos() {
    var vardataTables = funcDatatables();
    $('#listaUbigeos').dataTable({
        'dom': vardataTables[1],
        'buttons': [],
        'language': vardataTables[0],
        'bDestroy': true,
        'ajax': route('cas.garantias.incidencias.listar-ubigeos'),
        'columns': [
            { 'data': 'id_dis' },
            { 'data': 'codigo' },
            {
                'render':
                    function (data, type, row) {
                        return (row['descripcion'] + ' - ' + row['provincia'] + ' - ' + row['departamento']);
                    }
            },
            {
                'render':
                    function (data, type, row) {
                        let action = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-success btn-sm" name="btnSeleccionarUbigeo" title="Seleccionar ubigeo"
                        data-ubigeo-descripcion="${row.descripcion + ' - ' + row.provincia + ' - ' + row.departamento}"
                        data-id-ubigeo="${row.id_dis}"
                        onclick="selectUbigeo(this);">
                        <i class="fa fa-check"></i>
                        </button>
                    </div>
                    `;

                        return action;
                    }
            }
        ],
        'columnDefs': [{ 'aTargets': [0], 'sClass': 'invisible' }],
    });
}

function selectUbigeo(obj) {
    let idUbigeo = obj.dataset.idUbigeo;
    let ubigeoDescripcion = obj.dataset.ubigeoDescripcion;

    let page = document.getElementsByClassName('page-main')[0].getAttribute('type');
    console.log('idUbigeo' + idUbigeo);
    console.log('ubigeoDescripcion' + ubigeoDescripcion);

    if (page == 'crear-orden-requerimiento') {
        if (modalPage == 'modal-proveedor') {
            $('[name=ubigeo]').val(idUbigeo);
            $('[name=name_ubigeo]').val(ubigeoDescripcion);
        } else {
            $('[name=id_ubigeo_destino]').val(idUbigeo);
            $('[name=ubigeo_destino]').val(ubigeoDescripcion);
        }

    } else if (modalPage == 'modal-seleccionar_crear_proveedor') {
        $('[name=ubigeo_prov]').val(idUbigeo);
        $('[name=name_ubigeo_prov]').val(ubigeoDescripcion);
    } else if (modalPage == 'modal-proveedor') {
        $('[name=ubigeoProveedor]').val(idUbigeo);
        $('[name=descripcionUbigeoProveedor]').val(ubigeoDescripcion);
    } else if (modalPage == 'modal-contacto-proveedor') {
        $('[name=ubigeoContactoProveedor]').val(idUbigeo);
        $('[name=descripcionUbigeoContactoProveedor]').val(ubigeoDescripcion);
    } else if (modalPage == 'modal-establecimiento-proveedor') {
        $('[name=ubigeoEstablecimiento]').val(idUbigeo);
        $('[name=descripcionUbigeoEstablecimiento]').val(ubigeoDescripcion);
    } else if (page == 'incidencia') {
        console.log(ubigeoOrigen);
        if (ubigeoOrigen == 'incidencia') {
            $('[name=id_ubigeo_contacto]').val(idUbigeo);
            $('[name=ubigeo_contacto]').val(ubigeoDescripcion);
            ubigeoOrigen = '';
        } else {
            $('[name=ubigeo]').val(idUbigeo);
            $('[name=name_ubigeo]').val(ubigeoDescripcion);
        }
    } else {
        $('[name=ubigeo]').val(idUbigeo);
        $('[name=name_ubigeo]').val(ubigeoDescripcion);
    }

    modalPage = '';
    $('#modal-ubigeo').modal('hide');
}

