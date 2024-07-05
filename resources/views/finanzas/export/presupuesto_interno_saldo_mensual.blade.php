<table class="table table-light">
    <thead class="thead-light">

        <tr>
            <th style="border: 1 solid #000; background-color: #00b0f0;" rowspan="2">PARTIDA</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" rowspan="2">DESCRIPCIÓN</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Enero</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Febrero</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Marzo</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Abril</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Mayo</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Junio</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Julio</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Agosto</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Setiembre</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Octubre</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Noviembre</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;" colspan="3">Diciembre</th>
        </tr>
        <tr>
            {{-- <th style="border: 1 solid #000; background-color: #00b0f0;"></th>
            <th style="border: 1 solid #000; background-color: #00b0f0;"></th> --}}
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>

            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL EJECUTADO</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">SALDO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $value)
        @php
            $enero_saldo = $value->enero_total - $value->enero_ejecutado;
            $enero_saldo = ($enero_saldo>0 ? $enero_saldo : 0);

            $febrero_saldo = $value->febrero_total - $value->febrero_ejecutado;
            $febrero_saldo = ($febrero_saldo>0 ? $febrero_saldo : 0);

            $marzo_saldo = $value->marzo_total - $value->marzo_ejecutado;
            $marzo_saldo = ($marzo_saldo>0 ? $marzo_saldo : 0);

            $abril_saldo = $value->abril_total - $value->abril_ejecutado;
            $abril_saldo = ($abril_saldo>0 ? $abril_saldo : 0);

            $mayo_saldo = $value->mayo_total - $value->mayo_ejecutado;
            $mayo_saldo = ($mayo_saldo>0 ? $mayo_saldo : 0);

            $junio_saldo = $value->junio_total - $value->junio_ejecutado;
            $junio_saldo = ($junio_saldo>0 ? $junio_saldo : 0);

            $julio_saldo = $value->julio_total - $value->julio_ejecutado;
            $julio_saldo = ($julio_saldo>0 ? $julio_saldo : 0);

            $agosto_saldo = $value->agosto_total - $value->agosto_ejecutado;
            $agosto_saldo = ($agosto_saldo>0 ? $agosto_saldo : 0);

            $setiembre_saldo = $value->setiembre_total - $value->setiembre_ejecutado;
            $setiembre_saldo = ($setiembre_saldo>0 ? $setiembre_saldo : 0);

            $octubre_saldo = $value->octubre_total - $value->octubre_ejecutado;
            $octubre_saldo = ($octubre_saldo>0 ? $octubre_saldo : 0);

            $noviembre_saldo = $value->noviembre_total - $value->noviembre_ejecutado;
            $noviembre_saldo = ($noviembre_saldo>0 ? $noviembre_saldo : 0);

            $diciembre_saldo = $value->diciembre_total - $value->diciembre_ejecutado;
            $diciembre_saldo = ($diciembre_saldo>0 ? $diciembre_saldo : 0);
        @endphp
            <tr>
                <td style="border: 1 solid #000;"> {{ $value->partida }} </td>
                <td style="border: 1 solid #000;"> {{ $value->descripcion }} </td>

                <td style="border: 1 solid #000;"> {{ $value->enero_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->enero_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $enero_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->febrero_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->febrero_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $febrero_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->marzo_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->marzo_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $marzo_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->abril_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->abril_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $abril_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->mayo_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->mayo_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $mayo_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->junio_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->junio_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $junio_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->julio_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->julio_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $julio_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->agosto_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->agosto_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $agosto_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->setiembre_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->setiembre_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $setiembre_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->octubre_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->octubre_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $octubre_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->noviembre_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->noviembre_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $noviembre_saldo }} </td>

                <td style="border: 1 solid #000;"> {{ $value->diciembre_total }} </td>
                <td style="border: 1 solid #000;"> {{ $value->diciembre_ejecutado }} </td>
                <td style="border: 1 solid #000;"> {{ $diciembre_saldo }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
