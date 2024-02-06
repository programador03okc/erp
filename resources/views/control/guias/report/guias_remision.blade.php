<!DOCTYPE html>
<html lang="es">
<head>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Fecha</th>
                <th>Recepción GCI</th>
                <th>N° GR</th>
                <th>Destino</th>
                <th>OCAM/OC</th>
                <th>CDP/REQ</th>
                <th>Descripción</th>
                <th>Transportista</th>
                <th>FACT/GR</th>
                <th>Responsable</th>
                <th >Estado</th>
                <th >GR Escaneada</th>
                <th >GR Cargo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{$item->empresa_razon}}</td>
                <td>{{$item->fecha_guia}}</td>
                <td>{{$item->recepcion_gci}}</td>
                <td>{{$item->codigo}}</td>
                <td>{{$item->destino}}</td>
                <td>{{$item->orden}}</td>
                <td>{{$item->documentos_agile}}</td>
                <td>{{$item->descripcion_guia}}</td>
                <td>{{$item->transportista}}</td>
                <td>{{$item->documentos_transportista}}</td>
                <td>{{$item->responsable}}</td>
                <td>{{$item->estado}}</td>

                <td>{{$item->adjunto_guia}}</td>
                <td>{{$item->adjunto_guia_sellada}}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</body>
</html>
