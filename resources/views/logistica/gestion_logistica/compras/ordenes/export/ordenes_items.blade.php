
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
                    <td>{{ $item->concepto_requerimiento }}</td>
                    <td>{{ $item->razon_social_cliente }}</td>
                    <td>{{ $item->razon_social_proveedor }}</td>
                    <td>{{ $item->codigo_am }}</td>
                    <td>{{ $item->nombre_am }}</td>
                    <td>{{ $item->descripcion_subcategoria }}</td>
                    <td>{{ $item->descripcion_categoria }}</td>
                    <td>{{ $item->codigo_producto }}</td>
                    <td>{{ $item->part_number_producto }}</td>
                    <td>{{ $item->cod_softlink_producto }}</td>
                    <td>{{ $item->descripcion_producto }}</td>
                    <td>{{ $item->lugar_entrega_cdp }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->abreviatura_unidad_medida_producto }}</td>
                    <td>{{ $item->simbolo_moneda_orden }}</td>
                    <td>{{ $item->precio }}</td>
                    <td>{{ $item->cc_fila_precio }}</td>
                    <td>{{ $item->fecha_emision }}</td>
                    <td>{{ $item->fecha_llegada }}</td>
                    <td>{{ $item->fecha_ingreso_almacen }}</td>
                    <td>{{ $item->tiempo_atencion_proveedor }}</td>
                    <td>{{ $item->descripcion_sede_empresa }}</td>
                    <td>{{ $item->descripcion_estado }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

