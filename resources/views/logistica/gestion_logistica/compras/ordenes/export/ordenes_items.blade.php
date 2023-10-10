
    <table>

        <thead>
            <tr>
                <th> Factura</th>
                <th> Cod. orden</th>
                <th> Cod. req.</th>
                <th> Cod. orden softlink</th>
                <th> Nro. Orden MGC</th>
                <th> Concepto</th>
                <th> Cliente</th>
                <th> Proveedor</th>
                <th> Cod.AM</th>
                <th> Nombre AM</th>
                <th> Marca</th>
                <th> Categoría</th>
                <th> Cod. producto</th>
                <th> Part number</th>
                <th> Cod. softlink</th>
                <th> Descripción</th>
                <th> Lugar entrega MGC</th>
                <th> Cantidad</th>
                <th> Unitdad medida.</th>
                <th> Moneda</th>
                <th> Precio unit. Ord.</th>
                <th> Precio unit. CDP</th>
                <th> Fecha emisión orden</th>
                <th> Plazo entrega</th>
                <th> Fecha ingreso almacén</th>
                <th> Tiempo atención proveedor</th>
                <th> Empresa - sede</th>
                <th> Estado</th>
            </tr>
        </thead>


        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->numero_factura }}</td>
                    <td>{{ $item->codigo_orden }}</td>
                    <td>{{ $item->codigo_requerimiento }}</td>
                    <td>{{ $item->codigo_softlink }}</td>
                    <td>{{ $item->nro_orden_mgc }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

