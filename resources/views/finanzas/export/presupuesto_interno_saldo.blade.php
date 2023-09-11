<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Reporte de Presupuesto de Interno </h2>
    <br>
    <br>
    <h2>Requerimiento de pago</h2>
    <table>
        <thead>
            <tr>
                <th style="background-color: #cccccc;" width="18"><b>Partida	</b></th>
                <th style="background-color: #cccccc;" width="30"><b>Descripción	</b></th>
                <th style="background-color: #cccccc;text-align: center;" width="18"><b>Total</b></th>
                <th style="background-color: #cccccc;text-align: center;" width="18"><b>Ejecutado</b></th>
                <th style="background-color: #cccccc;text-align: center;" width="18"><b>Saldo</b></th>

            </tr>
        </thead>
        <tbody>



            @foreach ($data as $key => $item)
                <tr>
                    <td style="text-align: left;">{{ $item->partida  }}</td>
                    <td style="text-align: left;">{{ $item->descripcion }}</td>
                    <td style="text-align: right;"> {{ $item->total}}</td>
                    <td style="text-align: right;"> {{ $item->ejecutado}}</td>
                    <td style="text-align: right;"> {{ ($item->total - $item->ejecutado ) }}</td>
                </tr>

            @endforeach
        </tbody>
    </table>
</body>
</html>
