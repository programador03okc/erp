class SeguimientoModel {

    constructor(token) {
        this.token = token;
    }

    listar = (page,) => {
 
        return $.ajax({
            url: route("necesidades.dashboard.listar"),
            type: "GET",
            dataType: "JSON",
            data: {page:page,  _token: this.token,  },
        });
    }
   
}
