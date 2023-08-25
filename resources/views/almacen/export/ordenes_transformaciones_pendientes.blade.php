<table class="mytable table table-condensed table-bordered table-okc-view" id="listaTransformaciones" width="100%">
    <thead>
        <tr>
            {{-- <th hidden></th> --}}
            <th>Código</th>
            <th>Fecha Entrega</th>
            <th>OCAM</th>
            <th>CDP</th>
            <th>Cliente/Entidad</th>
            <th>Requerim.</th>
            <th>Fecha Despacho</th>
            <th>Fecha Inicio</th>
            <!-- <th>Fecha Procesado</th> -->
            <th>Almacén</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key=>$item)
            <tr>
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->fecha_entrega_req }}</td>
                <td>{{ $item->nro_orden }}</td>
                <td>{{ $item->codigo_oportunidad }}</td>
                <td>{{ $item->razon_social }}</td>
                <td>{{ $item->codigo_req }}</td>
                <td>{{  date("d/m/Y", strtotime($item->fecha_despacho)) }}</td>
                <td>{{ ($item->fecha_inicio? date("d/m/Y H:i:s", strtotime($item->fecha_inicio)):'-/-/-') }}</td>
                <td>{{ $item->descripcion }}</td>
                <td>{{ $item->estado_doc }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
