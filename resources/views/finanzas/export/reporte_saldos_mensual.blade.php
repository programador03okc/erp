<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th style="border: 1 solid #000; background-color: #00b0f0;">PARTIDA</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">DESCRIPCIÓN</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">ENERO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">FEBRERO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">MARZO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">ABRIL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">MAYO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">JUNIO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">JULIO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">AGOSTO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SETIEMBRE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">OCTUBRE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">NOVIEMBRE</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">DICIEMBRE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($saldos as $value)
            <tr>
                <td>{{$value->partida}}</td>
                <td>{{$value->descripcion}}</td>
                <td>{{$value->enero_aux}}</td>
                <td>{{$value->febrero_aux}}</td>
                <td>{{$value->marzo_aux}}</td>
                <td>{{$value->abril_aux}}</td>
                <td>{{$value->mayo_aux}}</td>
                <td>{{$value->junio_aux}}</td>
                <td>{{$value->julio_aux}}</td>
                <td>{{$value->agosto_aux}}</td>
                <td>{{$value->setiembre_aux}}</td>
                <td>{{$value->octubre_aux}}</td>
                <td>{{$value->noviembre_aux}}</td>
                <td>{{$value->diciembre_aux}}</td>
            </tr>

        @endforeach
    </tbody>
</table>
