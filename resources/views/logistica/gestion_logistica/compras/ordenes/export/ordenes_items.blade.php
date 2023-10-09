<table>
    <thead>
        <tr>
            <th style="background-color:#787878;">Factura</th>
            <th style="background-color:#787878;">Cod. orden</th>
            <th style="background-color:#787878;">Cod. req.</th>
            <th style="background-color:#787878;">Cod. orden softlink</th>
            <th style="background-color:#787878;">Nro. Orden MGC</th>
            <th style="background-color:#787878;">Concepto</th>
            <th style="background-color:#787878;">Cliente</th>
            <th style="background-color:#787878;">Proveedor</th>
            <th style="background-color:#787878;">Cod.AM</th>
            <th style="background-color:#787878;">Nombre AM</th>
            <th style="background-color:#787878;">Marca</th>
            <th style="background-color:#787878;">Categoría</th>
            <th style="background-color:#787878;">Cod. producto</th>
            <th style="background-color:#787878;">Part number</th>
            <th style="background-color:#787878;">Cod. softlink</th>
            <th style="background-color:#787878;">Descripción</th>
            <th style="background-color:#787878;">Lugar entrega MGC</th>
            <th style="background-color:#787878;">Cantidad</th>
            <th style="background-color:#787878;">Unitdad medida.</th>
            <th style="background-color:#787878;">Moneda</th>
            <th style="background-color:#787878;">Precio unit. Ord.</th>
            <th style="background-color:#787878;">Precio unit. CDP</th>
            <th style="background-color:#787878;">Fecha emisión orden</th>
            <th style="background-color:#787878;">Plazo entrega</th>
            <th style="background-color:#787878;">Fecha ingreso almacén</th>
            <th style="background-color:#787878;">Tiempo atención proveedor</th>
            <th style="background-color:#787878;">Empresa - sede</th>
            <th style="background-color:#787878;">Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $item)
        {{-- {{dd($item['numero_factura'])}} --}}
                <tr>
                    <td>{{ $item['numero_factura'] }}</td>
                    <td>{{ $item['codigo_orden'] }}</td>
                    <td>{{ $item['codigo_requerimiento'] }}</td>
                    <td>{{ $item['codigo_softlink'] }}</td>
                    <td>{{ $item['nro_orden_mgc'] }}</td>
                    <td>{{ $item['concepto_requerimiento'] }}</td>
                    <td>{{ $item['razon_social_cliente'] }}</td>
                    <td>{{ $item['razon_social_proveedor'] }}</td>
                    <td>{{ $item['codigo_am'] }}</td>
                    <td>{{ $item['nombre_am'] }}</td>
                    <td>{{ $item['descripcion_subcategoria'] }}</td>
                    <td>{{ $item['descripcion_categoria'] }}</td>
                    <td>{{ $item['codigo_producto'] }}</td>
                    <td>{{ $item['part_number_producto'] }}</td>
                    <td>{{ $item['cod_softlink_producto'] }}</td>
                    <td>{{ $item['descripcion_producto'] }}</td>
                    <td>{{ $item['lugar_entrega_cdp'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item['abreviatura_unidad_medida_producto'] }}</td>
                    <td>{{ $item['simbolo_moneda_orden'] }}</td>
                    <td>{{ $item['precio'] }}</td>
                    <td>{{ $item['cc_fila_precio'] }}</td>
                    <td>{{ $item['fecha_emision'] }}</td>
                    <td>{{ $item['fecha_llegada'] }}</td>
                    <td>{{ $item['fecha_ingreso_almacen'] }}</td>
                    <td>{{ $item['tiempo_atencion_proveedor'] }}</td>
                    <td>{{ $item['descripcion_sede_empresa'] }}</td>
                    <td>{{ $item['descripcion_estado'] }}</td>
                </tr>
        @endforeach
    </tbody>
</table>
