<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th style="border: 1 solid #000; background-color: #00b0f0;">PARTIDA</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">DESCRIPCIÓN</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $value)
        @php
            $saldo = $value->total - $value->total_ejecutado;
        @endphp
            <tr>
                <td style="border: 1 solid #000;">{{ $value->partida }}</td>
                <td style="border: 1 solid #000;">{{ $value->descripcion }}</td>
                <td style="border: 1 solid #000;">{{ $value->total }}</td>
                <td style="border: 1 solid #000;">{{ $value->total_ejecutado }}</td>
                <td style="border: 1 solid #000;">{{ ($saldo>0?$saldo:0) }}</td>
            </tr>

        @endforeach
    </tbody>
</table>
