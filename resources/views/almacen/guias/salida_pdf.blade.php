<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>

    @page {
        margin-left: 25px;
        margin-right: 25px;
    }
    * {
        font-family: "DejaVu Sans";
        font-size: 10px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    div.seccion-hoja {
        border-bottom: 1px solid black;
        margin-bottom: 5px
    }

    div.seccion-hoja h4 {
        margin-bottom: 1px
    }

    table {
        width: 100%;
        /*page-break-after: always;
        page-break-before: always;*/
        
    }
    table tbody tr td {
        font-size: 10px;
    }
    div.space {
        page-break-inside: avoid;
    }

    div.producto-transformar {
        background-color: #bce8f1;
        padding-top: 5px;
        padding-bottom: 5px;
        font-weight: bold;
    }

    div.seccion-producto {
        background-color: #ededed;
        padding-top: 4px;
        padding-bottom: 4px;
        font-weight: bold;
    }

    span.rojo {
        color: red;
        font-weight: bold;
    }

    span.verde {
        color: green;
        font-weight: bold;
    }

    h4 {
        font-size: 20px;
    }

    table.bordered {
        border-spacing: 0px;
    }

    table.bordered th {
        border-top: 1px solid #cfcfcf;
        border-right: 1px solid #cfcfcf;
        border-bottom: 1px solid #cfcfcf;
    }

    table.bordered th:nth-child(1) {
        border-left: 1px solid #cfcfcf;
    }

    table.bordered td:nth-child(1) {
        border-left: 1px solid #cfcfcf;
    }

    table.bordered td {
        border-right: 1px solid #cfcfcf;
        border-bottom: 1px solid #cfcfcf;
    }

    h3.titulo {
        text-align: center;
        background-color: #acf2bf;
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 22px;
    }
</style>
<?php
use Carbon\Carbon;
?>
<body>
    <table width="100%" style="margin-bottom: 0px">
        <tr>
            <td>
                <img src="{{ $logo_empresa }}" height="50px">
            </td>
        </tr>
    </table>
    <h4 style="text-align: center;
        padding-top: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid black;
        font-size: 22px;margin:0px; padding:0px;">Salida de Almacén</h4>
    <h4 class="text-center" style="margin:0px; padding:0px;border-bottom: 1px solid black;background-color: #fce0a5;">
        {{($salida->id_guia_ven!==null?('Guía '.$salida->guia):
        ($salida->id_transformacion!==null?$salida->cod_transformacion:''))}}</h4>
    <h5 class="text-center" style="margin:0px; padding:0px;">{{$salida->cod_sunat}} - {{$salida->ope_descripcion}}</h5>
    <h5 class="text-center" style="margin:0px; padding:0px;">{{$salida->codigo}}</h5>

    <div class="seccion-hoja">
        <h4 style="font-size: 14px;">Datos Generales</h4>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 20%" class="text-right">Almacén:</th>
                <td style="width: 45%">{{$salida->des_almacen}}</td>
                <th style="width: 25%" class="text-right">Fecha de Salida:</th>
                <td style="width: 35%">{{$salida->fecha_emision}}</td>
            </tr>
            <tr>
                <th style="width: 20%" class="text-right">Cliente:</th>
                <td style="width: 45%">{{$salida->id_guia_ven!==null ? $salida->ruc_cliente : $salida->ruc_empresa}} - {{$salida->id_guia_ven!==null ? $salida->razon_social_cliente : $salida->empresa_razon_social}}</td>
                <th style="width: 25%" class="text-right">Fecha Emisión Guía:</th>
                <td style="width: 35%">{{$salida->fecha_guia}}</td>
            </tr>
            @if ($docs!=='')
            <tr>
                <th style="width: 20%" class="text-right">Doc(s) Venta:</th>
                <td style="width: 45%">{{$docs}}</td>
                <th style="width: 25%" class="text-right">Fecha(s) emisión Doc.:</th>
                <td style="width: 35%">{{$docs_fecha}}</td>
            </tr>
            @endif
            @if ($salida->id_transferencia!==null)
            <tr>
                <th style="width: 20%" class="text-right">Transferencia:</th>
                <td style="width: 45%">{{$salida->trans_codigo}}</td>
                <th style="width: 25%" class="text-right">Almacén Destino:</th>
                <td style="width: 35%">{{$salida->trans_almacen_destino}}</td>
            </tr>
            @endif
            @if ($salida->id_transformacion!==null)
            <tr>
                <th style="width: 20%" class="text-right">Transformación:</th>
                <td style="width: 45%">{{$salida->cod_transformacion}}</td>
                <th style="width: 25%" class="text-right">Fecha de proceso:</th>
                <td style="width: 35%">{{$salida->fecha_transformacion}}</td>
            </tr>
            @endif
            <tr>
                <th style="width: 20%" class="text-right">Responsable:</th>
                <td style="width: 45%">{{$salida->nombre_corto}}</td>
            </tr>
        </thead>
    </table>
    <div class="seccion-hoja">
        <h4 style="font-size: 14px;">Lista de productos</h4>
    </div>
    
    <table class="bordered" style="page-break-after:always;">
        <thead>
            <tr>
                <th class="text-center cabecera-producto" style="width: 7%">Código</th>
                <th class="text-center cabecera-producto" style="width: 15%">Part Number</th>
                <th class="text-center cabecera-producto">Descripción del producto</th>
                <th class="text-center cabecera-producto" style="width: 5%">Cant.</th>
                <th class="text-center cabecera-producto" style="width: 5%">Und.</th>
                <th class="text-center cabecera-producto" style="width: 5%">Mnd.</th>
                <th class="text-center cabecera-producto" style="width: 8%">Unit.</th>
                @if($salida->id_operacion == 27)
                <th class="text-center cabecera-producto" style="width: 8%">Unit.$</th>
                @endif
                <th class="text-center cabecera-producto" style="width: 8%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalle as $prod)
            <tr>
                <td class="text-center" @if ($prod['series']!=='') rowspan="2" @endif>{{$prod['codigo']}}</td>
                <td class="text-center"><div>{{$prod['part_number']}}</div></td>
                <td><div>{{$prod['descripcion']}}</div></td>
                <td class="text-center">{{$prod['cantidad']}}</td>
                <td class="text-center">{{$prod['abreviatura']}}</td>
                <td class="text-center">{{$prod['simbolo']}}</td>
                <td class="text-right">{{round($prod['costo_promedio'],2,PHP_ROUND_HALF_UP)}}</td>
                @if($salida->id_operacion == 27)
                    <td class="text-right">{{round($prod['valor_dolar'],2,PHP_ROUND_HALF_UP)}}</td>
                @endif
                <td class="text-right">{{ number_format(round($prod['valorizacion'],2,PHP_ROUND_HALF_UP), 2) }}</td>
            </tr>
            @if ($prod['series']!=='')
            <tr>
                <td @if ($salida->id_operacion == 27) colspan="8" @else colspan="7" @endif>
                    <div>{{$prod['series']}}</div>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    <br>

    <footer style="position:absolute;bottom:0px;right:0px;">
        <p style="text-align:right;font-size:10px;margin-bottom:0px;">
            {{'Registrado por: ' . $salida->nombre_corto }}
        </p>
        <p style="text-align:right;font-size:10px;margin-bottom:0px;margin-top:0px;">
            {{'Fecha registro: ' . $fecha_registro . ' ' . $hora_registro }}
        </p>
        <p style="text-align:right;font-size:10px;margin-bottom:0px;margin-top:0px;">
            {{'Fecha de impresión: ' . (new Carbon())->format('d-m-Y H:i:s') }}
            </p>
        <p style="text-align:right;font-size:10px;margin-top:0px;">
            <strong>{{config('global.nombreSistema') . ' '  . config('global.version')}}</strong>
        </p>
    </footer>
</body>
</html>