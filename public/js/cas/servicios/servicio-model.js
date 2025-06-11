class ServicioModel {

    constructor(token) {
        this.token = token;
    }

    guardar = (data) =>{
        return $.ajax({
            url: route('cas.servicios.guardar'),
            type: 'POST',
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: data
        });
    }
    editar = (id) =>{
        return $.ajax({
            url: route('configuraciones.habitacion.editar',{id:id}),
            type: 'GET',
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: { _token: this.token }
        });
    }
    guardarFechaCierre = (data) =>{
        return $.ajax({
            url: route('cas.servicios.guardar-fecha-cierre'),
            type: 'POST',
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: data
        });
    }
    cancelar = (id) =>{
        return $.ajax({
            url: route('cas.servicios.cancelar',{id_servicio:id}),
            type: 'PUT',
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: { _token: this.token }
        });
    }
}
