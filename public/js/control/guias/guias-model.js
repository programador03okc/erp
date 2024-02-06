class GuiasModel {

    constructor(token) {
        this.token = token;
    }

    guardar = (data) => {
        return $.ajax({
            url: route("control.incidencias.guardar"),
            type: "POST",
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: data,
        });
    }
    eliminar = (id) => {
        return $.ajax({
            url: route("control.incidencias.eliminar", {id: id}),
            type: "PUT",
            dataType: "JSON",
            data: { _token: this.token },
        });
    }

    listaSedesCombo = (id) => {
        return $.ajax({
            url: route("sedes.lista-sedes-combo", {id: id}),
            type: "GET",
            dataType: "JSON",
            data: { _token: this.token },
        });
    }
    listaDivisionesCombo = (id) => {
        return $.ajax({
            url: route("sedes.lista-sedes-combo", {id: id}),
            type: "GET",
            dataType: "JSON",
            data: { _token: this.token },
        });
    }

}
