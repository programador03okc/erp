<table class="mytable table table-condensed table-bordered table-okc-view" id="listaTransformaciones" width="100%">
    <thead>
        <tr>
            {{-- <th hidden></th> --}}
            <th>Código</th>
            <th>Fecha Entrega</th>
            <th>OCAM</th>
            <!-- <th>Cuadro Costo</th>
            <th>Oportunidad</th> -->
            <th>Entidad</th>
            <th>Requerim.</th>
            <th>Fecha registro</th>
            <th>Fecha inicio</th>
            <th>Fecha fin</th>
            <th>Almacén</th>
            <th>Responsable</th>
            <th>Obs.</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key=>$item)
            <tr>
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->fecha_entrega_req }}</td>
                <td>{{ $item->orden_am }}</td>
                <td>{{ $item->nombre }}</td>
                <td>{{ $item->codigo_req }}</td>
                <td>{{ ($item->fecha_registro? date("d/m/Y H:i:s", strtotime($item->fecha_registro)):'-/-/-') }}</td>
                <td>{{ ($item->fecha_inicio? date("d/m/Y H:i:s", strtotime($item->fecha_inicio)):'-/-/-') }}</td>
                <td>{{ ($item->fecha_transformacion? date("d/m/Y H:i:s", strtotime($item->fecha_transformacion)):'-/-/-') }}</td>
                <td>{{ $item->descripcion }}</td>
                <td>{{ $item->nombre_responsable }}</td>
                <td>{{ $item->observacion }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
