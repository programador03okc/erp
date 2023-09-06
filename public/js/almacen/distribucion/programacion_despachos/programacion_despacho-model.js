class ProgramacionDespachoModel {

    constructor(token) {
        this.token = token;
    }

    listarODI = (page) => {
        return $.ajax({
            url: route("logistica.distribucion.programacion-despachos.listar-odi"),
            type: "GET",
            dataType: "JSON",
            data: {page:page,  _token: this.token,  },
        });
    }
    listarODE = (page) => {
        return $.ajax({
            url: route("logistica.distribucion.programacion-despachos.listar-ode"),
            type: "GET",
            dataType: "JSON",
            data: { page:page, _token: this.token,  },
        });
    }

    guardar = (data) => {
        return $.ajax({
            url: route("logistica.distribucion.programacion-despachos.guardar"),
            type: "POST",
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: data,
        });
    }
    editar = (id) => {
        return $.ajax({
            url: route("logistica.distribucion.programacion-despachos.editar", {id: id}),
            type: "GET",
            dataType: "JSON",
            data: { _token: this.token },
        });
    }
    eliminar = (id) => {
        return $.ajax({
            url: route("logistica.distribucion.programacion-despachos.eliminar", {id: id}),
            type: "PUT",
            dataType: "JSON",
            data: { _token: this.token },
        });
    }

}
