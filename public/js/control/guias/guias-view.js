class GuiasView {

    constructor(model) {
        this.model = model;

    }

    /**
     * Eventos de guias
    */
    eventos = () => {

        // CUANDO LE DA ACEPTAR AL BOTON DEL MODAL
        $('#aplicar-filtros-guias').click(function (e) {
            listar();
        });
        //CUANDO GENERA UN CAMBIO EN UNO DE LOS INPUTS/SELECTS SE REALIZA EL CAMBIO DEL FILTRO
        $('[data-select="change"]').change(function (e) {
            e.preventDefault();
            let val = $(e.currentTarget).closest('.row').find('[name="filtro-checkbox"]').val();
            let seccion = $(e.currentTarget).attr('data-seccion');
            let curren = $(e.currentTarget).closest('.row').find('[name="filtro-checkbox"]');
            let checked = $(e.currentTarget).closest('.row').find('[name="filtro-checkbox"]').prop('checked');
            changeFiltros(val, curren, checked);
        });

        //PARA DESBLOQUEAR LOS FILTROS
        $('input[name="filtro-checkbox"]').change(function (e) {
            e.preventDefault();
            let val = $(e.currentTarget).val();
            let checked = $(e.currentTarget).prop('checked');
            let curren = $(e.currentTarget);

            if (checked) {
                curren.closest('.row').find('[data-select="change"]').removeAttr('disabled');
            } else {
                curren.closest('.row').find('[data-select="change"]').attr('disabled','true');
            }
            changeFiltros(val, curren, checked);

        });
        function changeFiltros(val, curren, checked) {
            switch (val) {
                case 'empresa':

                    if (checked) {
                        data_filtros.empresa_id  = curren.closest('.row').find('[data-select="change"]').val();
                    } else {
                        data_filtros.empresa_id  = '';
                    }
                break;

                case 'estado':
                    if (checked) {
                        data_filtros.estado  = curren.closest('.row').find('[data-select="change"]').val();
                    } else {
                        data_filtros.estado  = '';
                    }
                break;
                case 'fecha':
                    if (checked) {
                        data_filtros.fecha_final  = curren.closest('.row').find('[name="fecha_final"][data-select="change"]').val();
                        data_filtros.fecha_inicio  = curren.closest('.row').find('[name="fecha_inicio"][data-select="change"]').val();
                    } else {
                        data_filtros.fecha_final  = '';
                        data_filtros.fecha_inicio  = '';
                    }
                break;
            }
         }
         $('[data-change="script"]').change((e) => {
            e.preventDefault();
            let destino = $(e.currentTarget).closest('#formulario').find('[name="destino"]').val();
            let entidad = $(e.currentTarget).closest('#formulario').find('[name="entidad"]').val();

            let text_1 = (destino?destino:entidad);
            let text_2 = (entidad?entidad:destino);

            $(e.currentTarget).closest('#formulario').find('[name="destino"]').val(text_1);
            $(e.currentTarget).closest('#formulario').find('[name="entidad"]').val(text_2);
         });

        // EJEMPLOS
        $('#nuevo').click(function (e) {
            e.preventDefault();
            let tipo ="Nueva",
                form = $('<form action="'+route('control.incidencias.formulario')+'" method="POST">'+
                    '<input type="hidden" name="_token" value="'+token+'" >'+
                    '<input type="hidden" name="id" value="0" >'+
                    '<input type="hidden" name="tipo" value="'+tipo+'" >'+
                '</form>');
            $('body').append(form);
            form.submit();

        });


        $('#tabla-data').on("click", 'button.editar', (e) => {
            let id =$(e.currentTarget).attr('data-id'),
                tipo ="Editar",
                form = $('<form action="'+route('control.incidencias.formulario')+'" method="POST">'+
                    '<input type="hidden" name="_token" value="'+token+'" >'+
                    '<input type="hidden" name="id" value="'+id+'" >'+
                    '<input type="hidden" name="tipo" value="'+tipo+'" >'+
                '</form>');
            $('body').append(form);
            form.submit();
        });

    }
}



