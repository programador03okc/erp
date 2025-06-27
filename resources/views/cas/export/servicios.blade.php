<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th style="border: 1 solid #000; background-color: #00b0f0;">REGION</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">CASE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">WO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TECNICO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">FECHA ACEPTACION</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">FECHA DE CIERRE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">CLIENTE</th>
            {{-- <th style="border: 1 solid #000; background-color: #00b0f0;">ACCION</th> --}}
            <th style="border: 1 solid #000; background-color: #00b0f0;">TIPO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">INCIDENCIA</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">NUMERO SERIE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">ESTADO WO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $value)
            <tr>
                <td>{{$value->region}}</td>
                <td>{{$value->numero_caso}}</td>
                <td>{{$value->nro_orden_trabajo}}</td>
                <td>{{$value->responsable}}</td>
                <td>{{$value->fecha_aceptacion}}</td>
                <td>{{$value->fecha_cierre}}</td>
                <td>{{$value->cliente}}</td>
                {{-- <td>{{$value->falla_reportada}}</td> --}}
                <td>ONSITE-FRU</td>
                <td>{{$value->incidencia}}</td>
                <td>{{$value->serie}}</td>
                <td>{{$value->estado_wo}}</td>
            </tr>

        @endforeach
    </tbody>
</table>
