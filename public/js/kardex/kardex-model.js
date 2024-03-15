class KardexModel {

    constructor(token) {
        this.token = token;
    }
    cargaInicialKardex = (data) => {
        return $.ajax({
            url: route("kardex.productos.carga-inicial"),
            type: "POST",
            dataType: "JSON",
            processData: false,
            contentType: false,
            data: data,
        });
    }
    verSeries = (id) => {
        return $.ajax({
            url: route("kardex.productos.ver-series"),
            type: "POST",
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: { _token: this.token, id:id },
        });
    }

    actualizarKardex = (id) => {
        return $.ajax({
            url: route("kardex.productos.actualizar-productos"),
            type: "GET",
            dataType: "JSON",
            // processData: false,
            // contentType: false,
            data: { _token: this.token },
        });
    }
}
