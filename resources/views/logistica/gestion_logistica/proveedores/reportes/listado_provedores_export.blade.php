<table>
    <thead>
        <tr>
            <th style="background-color:#cccccc;">Tipo documento</th>
            <th style="background-color:#cccccc;">Nro documento</th>
            <th style="background-color:#cccccc;" width="80">Razon social</th>
            <th style="background-color:#cccccc;">Dirección</th>
            <th style="background-color:#cccccc;">Ubigeo</th>
            <th style="background-color:#cccccc;">Pais</th>
            <th style="background-color:#cccccc;">Teléfono</th>
            <th style="background-color:#cccccc;">Fecha registro</th>
            <th style="background-color:#cccccc;">Estado</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($data as $element)
        <tr>
            <td>{{ $element['tipo_documento'] }}</td>
            <td>{{ $element['nro_documento'] }}</td>
            <td>{{ $element['razon_social'] }}</td>
            <td>{{ $element['direccion'] }}</td>
            <td>{{ $element['ubigeo'] }}</td>
            <td>{{ $element['pais'] }}</td>
            <td>{{ $element['telefono'] }}</td>
            <td>{{ $element['fecha_registro'] }}</td>
            <td>{{ $element['estado'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>