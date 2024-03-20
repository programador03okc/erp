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
            $saldo = ($saldo>0 ? $saldo : 0);
        @endphp
            <tr>
                <td style="border: 1 solid #000;  ">
                    {{ $value->partida }}
                </td>
                <td style="border: 1 solid #000;">
                    {{ $value->descripcion }}
                </td>
                <td style="border: 1 solid #000;">

                    {{ $value->total }}
                </td>
                <td style="border: 1 solid #000;">
                    {{ $value->total_ejecutado }}
                </td>

                <td style="border: 1 solid #000;">

                    {{ $saldo }}
                </td>
            </tr>
            {{-- @if (sizeof($value->historial)>0)
                @foreach ($value->historial as $historia)
                    <tr>
                        <td style="border-bottom: 1 solid #000;border-left: 1 solid #000; background-color: #d9d9d9;"><i>{{ $value->partida }}</i></td>
                        <td style="border-bottom: 1 solid #000;background-color: #d9d9d9;"><i>{{ $historia['codigo'] }}</i></td>
                        <td style="border-bottom: 1 solid #000;background-color: #d9d9d9;"></td>

                        <td style="border-bottom: 1 solid #000; sbackground-color: #d9d9d9;"><i>{{ $historia['soles'] }}</i></td>
                        <td style="border-bottom: 1 solid #000; background-color: #d9d9d9;"><i>{{ $historia['dolares'] }}</i></td>

                        <td style="border-bottom: 1 solid #000;background-color: #d9d9d9;"><i>{{ $historia['monto'] }}</i></td>
                        <td style="border-bottom: 1 solid #000; border-right: 1 solid #000;background-color: #d9d9d9;"></td>


                    </tr>
                @endforeach
            @endif --}}
        @endforeach
    </tbody>
</table>
