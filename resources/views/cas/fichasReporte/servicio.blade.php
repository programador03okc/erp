<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    * {
        font-family: "DejaVu Sans";
        font-size: 12px;
    }
    table {
        width: 100%;
    }
    table.bordered {
        border-spacing: 0px;
    }

</style>
<body>
    <table class="bordered" >
        <tbody>
            <tr>
                <td>
                    <img src="{{ $logo_empresa }}" height="70px">
                </td>
                <td colspan="3" align="center">
                     RST - Reporte de Servicio Técnico
                </td>
                <td colspan="2" align="center">
                    N° {{$servicio->correlativo}}
                </td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">Nro. de caso:</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">{{$servicio->numero_caso}}</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">Nro orden trabajo (WO):</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">{{$servicio->nro_orden_trabajo}}</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">Fecha de Servicio:</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">{{$servicio->fecha_reporte}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Cliente/Usuario:</td>
                <td colspan="5" style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">{{$servicio->nombre_contacto}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Dirección:</td>
                <td colspan="5"style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">{{$servicio->direccion_contacto}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Equipo:</td>
                <td colspan="5"style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">{{$servicio->marca .', '. $servicio->modelo . ' - ' . $servicio->producto}} </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Modelo (P/N):</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="2">{{$servicio->part_number}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;"> Número de Serie:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2">{{$servicio->serie}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Falla Reportada:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="5">{{$servicio->falla_reportada}}</td>
            </tr>
            {{-- ------- checks --}}
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6" align="center"> <strong>Revisión Externa | "Si / No"</strong></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">¿Daños físicos detectados?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;"align="center"> {{$servicio->fisico_detectado ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">¿Tornillos, tapas, jefes, accesorios completos?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->accesorios_completos ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3"> ¿Se encuentra correctamente ensamblado?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->ensamblado ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">¿Presenta signos de desgaste?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->desgaste ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3"> ¿Presenta signos de golpes?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->golpes ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">¿El equipo se encuentra limpio?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->equipo_limpo ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">¿El equipo presenta indicios de manipulación o daños, ya sean internos o externos, que puedan ser considerados como causa de exclusión de garantía?</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->manipulacion_danos ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>


            {{-- ----------- espacio en blanco  --}}
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6">
                    <br>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6" align="center"><strong>Revisión de Información</strong></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">Verifica Boletines / Tips</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" align="center"> {{$servicio->boletines ? 'Si' : 'No'}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="2"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6" align="center"> <strong>Revisión del BIOS</strong></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="2">Versión BIOS / Firmware actual:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" > {{$servicio->bios_actual}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="2">Versión BIOS / Firmware actualizada:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;"> {{$servicio->bios_actualizada}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6"> Labor realizada / Recomendaciones:</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6">
                    {{-- {{$servicio->comentarios_cierre}} --}}
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" >Estado de Servicio:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="5">{{$servicio->estado_servicio}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Hora de llegada:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">{{$servicio->hora_llegada}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Hora de Inicio:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">{{$servicio->hora_inicio}}</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Hora de Fin:</td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">{{$servicio->hora_fin}}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;"colspan="3">
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;" colspan="3">Firma del cliente: </td>
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="3">Técnico asignado: {{$usuarios->nombre_corto}}</td>
            </tr>
            <tr>
                {{-- <td style="border-bottom: 1px solid #000; border-left: 1px solid #000;">Nivel de satisfacción: 1-2-3-4-5 </td> --}}
                <td style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" colspan="6" align="center">OKC GROUP S.A.C. </td>
            </tr>
            <tr>
                <td  colspan="3">
                    <img src="{{ $caritas }}" height="100px">
                </td>
                <td align="center" colspan="3">
                    <img src="{{ $lenovo }}" height="40px">
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
