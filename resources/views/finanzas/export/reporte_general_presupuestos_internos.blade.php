<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th style="border: 1 solid #000; background-color: #00b0f0;">PARTIDA</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">DESCRIPCIÓN</th>
            <th style="border: 1 solid #000; background-color: #00b0f0;">TOTAL</th>
            <td style="border: 1 solid #000; background-color: #00b0f0;">S/.</td>
            <td style="border: 1 solid #000; background-color: #00b0f0;">$</td>
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
                <td style="border: 1 solid #000; {{$value->registro==2?'background-color: #b8cce4;':''}} ">
                    @if ($value->registro==2)
                    <strong> {{ $value->partida }} </strong>
                    @else
                    {{ $value->partida }}
                    @endif
                </td>
                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}">
                    @if ($value->registro==2)
                    <strong> {{ $value->descripcion }} </strong>
                    @else
                    {{ $value->descripcion }}
                    @endif
                </td>
                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}">

                    @if ($value->registro==2)
                    <strong> {{ $value->total }} </strong>
                    @else
                    {{ $value->total }}
                    @endif
                </td>
                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}"></td>
                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}"></td>
                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}">
                    @if ($value->registro==2)
                    <strong> {{ $value->total_ejecutado }} </strong>
                    @else
                    {{ $value->total_ejecutado }}
                    @endif
                </td>

                <td style="border: 1 solid #000;{{$value->registro==2?'background-color: #b8cce4;':''}}">

                    @if ($value->registro==2)
                    <strong> {{ $saldo }} </strong>
                    @else
                    {{ $saldo }}
                    @endif
                </td>
            </tr>
            @if (sizeof($value->historial)>0)
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
            @endif
        @endforeach
    </tbody>
</table>
