$(function(){
    var vardataTables = funcDatatables();
    $('#listaLogActividad').dataTable({
        'dom': vardataTables[1],
        'buttons':  [
            {
                extend: 'excel',
                text: '<i class="far fa-file-excel"></i> Exportar',
            }
        ],
        'language' : vardataTables[0],
        "processing": true,
        "bDestroy": true,
        'ajax': route('configuracion.reportes.log-actividad.listar'),
        'columns': [
            {'data': 'id'},
            {'data': 'fecha'},
            {'data': 'nombre_usuario', 'name':'sis_usua.nombre_corto'},
            {'data': 'descripcion_tipo_accion', 'name':'log_tipo_acciones.descripcion'},
            {'data': 'modulo'},
            {'data': 'formulario'},
            {'data': 'tabla'},
            {'data': 'valor_anterior'},
            {'data': 'nuevo_valor'},
            {'data': 'comentarios'},
            {'data': 'created_at'}
        ],
        'order': [
            [0, 'desc']
        ]
    });
    resizeSide();

});

