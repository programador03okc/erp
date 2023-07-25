<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de cotización</title>
</head>
<body>
    <h3>{{ $requerimiento['requerimiento'][0]['razon_social_empresa'] != null ?  $requerimiento['requerimiento'][0]['razon_social_empresa'] :''}}</h3>
    <h3>{{ $requerimiento['requerimiento'][0]['nro_documento_empresa'] != null ?  $requerimiento['requerimiento'][0]['nro_documento_empresa'] :''}}</h3>
    <br>
    <table>
        <tbody>
            <tr>
                <td align="center" colspan="9" style="font-size: 30px; font-weight: bold;">SOLICITUD DE COTIZACION</td>
            </tr>
            <tr>
                <td>Proveedor:</td>
                <td colspan="3"></td>
                <td>Contacto:</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td>N° RUC:</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <th>Fecha:</th>
                <td colspan="3"></td>
                <th>Teléfono:</th>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="12">Item</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="45">Descripción del bien o servicio</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="10">Unidad</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="10">Cantidad</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="15">Precio unit.</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="15">Subtotal</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="15">Plazo entrega</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="15">Garantía</th>
                <th align="center" style="background-color:#b6b6b6; color:#000000; font-weight: bold;" width="15">Ficha técnica</th>
            </tr>
        </thead>
        <tbody>
            @foreach(($requerimiento['det_req']) as $clave => $item)
            <tr>
                <td align="center">{{ $clave + 1 }}</td>
                <td>{{ $item['descripcion'] }}</td>
                <td align="center">{{ $item['unidad_medida'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
             </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>