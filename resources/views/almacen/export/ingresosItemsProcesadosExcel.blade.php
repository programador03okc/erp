<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Reporte de Ingresos Nivel de Items Procesados</h2>
    <br>
    <table>
        <thead>
            <tr>
                <th style="background-color: #cccccc;" width="18"><b>Serie</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Código</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Part number</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Descripción</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Cantidad</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Fecha Ingreso</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Ingreso</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Guía Compra</b></th>
                <th style="background-color: #cccccc;" width="30"><b>Proveedor</b></th>
                <th style="background-color: #cccccc;" width="30"><b>Operación</b></th>
                <th style="background-color: #cccccc;" width="30"><b>Almacén</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Responsable</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Ordenes</b></th>
                <th style="background-color: #cccccc;" width="18"><b>Requerimientos</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{$d->serie_producto}}</td>
                <td>{{$d->codigo_producto}}</td>
                <td>{{$d->part_number_producto}}</td>
                <td>{{$d->descripcion_producto}}</td>
                <td>{{$d->cantidad}}</td>
                <td>{{date('d-m-Y H:i', strtotime($d->fecha_emision))}}</td>
                <td>{{$d->codigo}}</td>
                <td>{{$d->serie}} - {{$d->numero}}</td>
                <td>{{$d->razon_social}}</td>
                <td>{{$d->operacion_descripcion}}</td>
                <td>{{$d->almacen_descripcion}}</td>
                <td>{{$d->nombre_corto}}</td>
                <td>{{$d->movimiento->ordenes_compra}}</td>
                <td>{{$d->movimiento->requerimientos}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>