
<table>
    <thead>
        <tr>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">Nro Orden</th>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">CODIGO</th>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">ESTADO</th>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">FECHA INCIO</th>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">FECHA FINAL</th>
            <th style="border: 1 solid #000; background-color: #b4c6e7;">Días</th>
        </tr>
        <tr>
            <th style="border: 1 solid #000; background-color: #a9d08e;"></th>
            <th style="border: 1 solid #000; background-color: #a9d08e;"></th>
            <th style="border: 1 solid #000; background-color: #a9d08e;"></th>
            <th style="border: 1 solid #000; background-color: #a9d08e;">{{ $fecha_menor }}</th>
            <th style="border: 1 solid #000; background-color: #a9d08e;">{{ $fecha_mayor }}</th>
            <th style="border: 1 solid #000; background-color: #a9d08e;">{{ $dias }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $value)
            <tr>
                <td style="border: 1 solid #000; background-color: #ffe699;">{{ $value->nro_orden }}</td>
                <td style="border: 1 solid #000; background-color: #ffe699;"></td>
                <td style="border: 1 solid #000; background-color: #ffe699;"></td>
                <td style="border: 1 solid #000; background-color: #ffe699;">{{ $value->fecha_inicio }}</td>
                <td style="border: 1 solid #000; background-color: #ffe699;">{{ $value->fecha_final }}</td>
                <td style="border: 1 solid #000; background-color: #ffe699;">{{ $value->diferencia_dias }}</td>
            </tr>
            @if (sizeof($value->incidencias)>0)
                @foreach ($value->incidencias as $incidente)

                    <tr>
                        <td>{{ $incidente->nro_orden }}</td>
                        <td>{{ $incidente->codigo }}</td>
                        <td>{{ $incidente->estado_incidencia }}</td>
                        <td>{{ $incidente->fecha_reporte }}</td>
                        <td>{{ $incidente->fecha_cierre }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach

    </tbody>
</table>
