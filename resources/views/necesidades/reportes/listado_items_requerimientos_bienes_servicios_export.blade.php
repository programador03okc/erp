<table>
    <thead>
        <tr>
            <th rowspan="2" style="background-color:#cccccc;">Id detalle requerimiento</th>
            <th rowspan="2" style="background-color:#cccccc;">Prioridad</th>
            <th rowspan="2" style="background-color:#cccccc;">Requerimiento</th>
            <th rowspan="2" style="background-color:#cccccc;">CDP</th>
            <th colspan="2" style="background-color:#cccccc;">Presupuesto anterior</th>
            <th colspan="3" style="background-color:#cccccc;">Partida (ppto. anterior) </th>
            <th colspan="2" style="background-color:#cccccc;">Presupuesto interno</th>
            <th colspan="3" style="background-color:#cccccc;">Partida (ppto, interno) </th>
            <th rowspan="2" style="background-color:#cccccc;">Cod. Padre Centro Costo</th>
            <th rowspan="2" style="background-color:#cccccc;">Des. Padre Centro Costo</th>
            <th rowspan="2" style="background-color:#cccccc;">Cod.Centro Costo</th>
            <th rowspan="2" style="background-color:#cccccc;">Des.Centro Costo</th>
            <th rowspan="2" style="background-color:#cccccc;">Proyecto</th>
            <th rowspan="2" style="background-color:#cccccc;" width="30">Motivo</th>
            <th rowspan="2" style="background-color:#cccccc;" width="80">Concepto</th>
            <th rowspan="2" style="background-color:#cccccc;">cod. Producto</th>
            <th rowspan="2" style="background-color:#cccccc;">Item</th>
            <th rowspan="2" style="background-color:#cccccc;">Tipo Requerimiento</th>
            <th rowspan="2" style="background-color:#cccccc;">Empresa</th>
            <th rowspan="2" style="background-color:#cccccc;">Sede</th>
            <th rowspan="2" style="background-color:#cccccc;">Grupo</th>
            <th rowspan="2" style="background-color:#cccccc;">División</th>
            <th colspan="4" style="background-color:#cccccc;">Totales Item Requerimiento</th>
            <th rowspan="2" style="background-color:#cccccc;">Tipo Cambio</th>
            <th rowspan="2" style="background-color:#cccccc;" width="80">Observación</th>
            <th rowspan="2" style="background-color:#cccccc;">Fecha Emisión Req.</th>
            <th rowspan="2" style="background-color:#cccccc;">Fecha Registro</th>
            <th rowspan="2" style="background-color:#cccccc;">Hora Registro</th>
            <th rowspan="2" style="background-color:#cccccc;">Estado Requerimiento</th>
            <th colspan="3" style="background-color:#cccccc;">Orden de compra</th>
        </tr>
        <tr>
            <th style="background-color:#cccccc;" width="10">Cod.Prespuesto</th>
            <th style="background-color:#cccccc;" width="30">Des.Prespuesto</th>
            <th style="background-color:#cccccc;" width="30">Partida</th>
            <th style="background-color:#cccccc;" width="10">Cod.sub Partida</th>
            <th style="background-color:#cccccc;" width="20">Des.sub Partida</th>
            <th style="background-color:#cccccc;" width="10">Cod.Prespuesto</th>
            <th style="background-color:#cccccc;" width="30">Des.Prespuesto</th>
            <th style="background-color:#cccccc;" width="20">Partida</th>
            <th style="background-color:#cccccc;" width="10">Cod.sub Partida</th>
            <th style="background-color:#cccccc;" width="20">Des.sub Partida</th>

            <th style="background-color:#cccccc;">Cantidad</th>
            <th style="background-color:#cccccc;">Precio Unitario (Sin IGV)</th>
            <th style="background-color:#cccccc;">Subtotal</th>
            <th style="background-color:#cccccc;">Moneda</th>

            <th style="background-color:#cccccc;">Cod. Orden</th>
            <th style="background-color:#cccccc;">Cantidad</th>
            <th style="background-color:#cccccc;">Precio Unitario (Sin IGV)</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
                <tr>
                    <td>{{ $item['id_detalle_requerimiento'] }}</td>
                    <td>{{ $item['prioridad'] }}</td>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['codigo_oportunidad'] }}</td>
                    <td>{{ $item['codigo_presupuesto_old'] }}</td>
                    <td>{{ $item['descripcion_presupuesto_old'] }}</td>
                    <td>{{ strtoupper($item['descripcion_partida_padre']) }}</td>
                    <td>{{ $item['partida'] }}</td>
                    <td>{{ $item['descripcion_partida'] }}</td>
                    <td>{{ $item['codigo_presupuesto_interno'] }}</td>
                    <td>{{ $item['descripcion_presupuesto_interno'] }}</td>
                    <td>{{ $item['descripcion_partida_presupuesto_interno'] }}</td>
                    <td>{{ $item['codigo_sub_partida_presupuesto_interno'] }}</td>
                    <td>{{ $item['descripcion_sub_partida_presupuesto_interno'] }}</td>
                    <td>{{ $item['padre_centro_costo'] }}</td>
                    <td>{{ $item['padre_descripcion_centro_costo'] }}</td>
                    <td>{{ $item['centro_costo'] }}</td>
                    <td>{{ $item['descripcion_centro_costo'] }}</td>
                    <td>{{ $item['descripcion_proyecto'] }}</td>
                    <td>{{ $item['motivo'] }}</td>
                    <td>{{ $item['concepto'] }}</td>
                    <td>{{ $item['codigo_producto'] }}</td>
                    <td>{{ $item['descripcion'] }}</td>
                    <td>{{ $item['tipo_requerimiento'] }}</td>
                    <td>{{ $item['empresa_razon_social'] }}</td>
                    <td>{{ $item['sede'] }}</td>
                    <td>{{ $item['grupo'] }}</td>
                    <td>{{ $item['division'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item['precio_unitario'] }}</td>
                    <td>{{ $item['subtotal'] }}</td>
                    <td>{{ $item['simbolo_moneda'] }}</td>
                    <td>{{ $item['tipo_cambio'] }}</td>
                    <td>{{ $item['observacion'] }}</td>
                    <td>{{ $item['fecha_requerimiento'] }}</td>
                    <td>{{ $item['fecha_registro'] }}</td>
                    <td>{{ $item['hora_registro'] }}</td>
                    <td>{{ $item['estado_requerimiento'] }}</td>
                    <td>{{ $item['codigo_orden'] }}</td>
                    <td>{{ $item['cantidad_orden'] }}</td>
                    <td>{{ $item['precio_orden'] }}</td>
                </tr>
        @endforeach
    </tbody>
</table>