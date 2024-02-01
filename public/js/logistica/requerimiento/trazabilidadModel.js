
class TrazabilidadModel{
    constructor(token) {
        this.token = token;
    }

    obtenerDataTrazabilidadDeRequerimiento(idRequerimiento){

        return new Promise(function(resolve, reject) {
            $.ajax({
                type: 'GET',
                url:`mostrarDocumentosByRequerimiento/${idRequerimiento}`,
                dataType: 'JSON',
                
                beforeSend: function (data) { 
                    $('#drawflow').LoadingOverlay("show", {
                        imageAutoResize: true,
                        progress: true,
                        imageColor: "#3c8dbc"
                    });
                    },
                success(response) {
                    resolve(response);
                    $('#drawflow').LoadingOverlay("hide", true);

                },
                error: function(err) {
                    reject(err) 
                }
                });
            });
    }
}