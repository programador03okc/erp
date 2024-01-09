
let $graficaBarras;
$('[name="presupuesto_id"]#presupuesto_id').change(function (e) {
    e.preventDefault();
    let presupuesto_id = $(this).val();
    if ($graficaBarras) {
        $graficaBarras.destroy();
    }
    if (presupuesto_id) {

        $.ajax({
            type: 'POST',
            url: route('finanzas.presupuesto.presupuesto-interno.grafica', {id:presupuesto_id}),
            data: {_token:token},
            dataType: 'JSON',
            beforeSend: (data) => {

            }
        }).done(function(response) {
            console.log(response);
            graficaBarrs(response.data);
        }).fail( function( jqXHR, textStatus, errorThrown ){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }

});
function graficaBarrs(data) {

    $graficaBarras = new Chart(
        document.getElementById('myChart'),
        {
            type: 'bar',
            data: {
                labels: data.map(row => row.x),
                // labels: label,
                datasets: [
                    {
                        label: 'Presupuesto planificado',
                        data: data,
                        parsing: {
                            yAxisKey: 'planificado'
                        }
                    }, {
                        label: 'Presupuesto ejecutado',
                        data: data,
                        parsing: {
                            yAxisKey: 'ejecutado'
                        }
                    }
                ]
            },
        }
    );
    $graficaBarras.reset();
}
