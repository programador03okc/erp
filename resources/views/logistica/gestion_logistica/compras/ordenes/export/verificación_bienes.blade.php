<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                {{-- <th></th> --}}
                <th style="border: 2px solid #000000;">
                    {{-- <img src="{{ asset('/public/images/logo_okc.png') }}" alt="" width="" height="120px" style="text-align: center;" /> --}}
                    {{-- <img src="{{ asset('images/img-avatar.png') }}"> --}}
                    <div><img src="{{public_path().'/'.$logo_okc}}" width="150px" ></div>
                </th>
                <th style="border: 2px solid #000000;">GESTIÓN LOGÍSTICA</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">CÓDIGO</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">OKC-LOG-FOR-004</th>
            </tr>
            <tr>
                {{-- <th></th> --}}
                <th style="border: 2px solid #000000;"></th>
                <th style="border: 2px solid #000000;">FORMATO</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">FECHA DE APROBACIÓN</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">01/066/2024</th>
            </tr>
            <tr>
                {{-- <th></th> --}}
                <th style="border: 2px solid #000000;"></th>
                <th style="border: 2px solid #000000;" >LISTA DE VERIFICACIÓN DE BIENES</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">VERSIÓN</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">01</th>
            </tr>
            <tr>
                {{-- <th></th> --}}
                <th style="border: 2px solid #000000;"></th>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;"></th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">PÁGINA</th>
                <td style="border: 2px solid #000000;"></td>
                <td style="border: 2px solid #000000;"></td>
                <th style="border: 2px solid #000000;">1/1</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>DATOS DEL PRODUCTO</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>




            <tr>
                {{-- <td></td> --}}
                <td>Recepcionado por:</td>
                <td style="border-bottom: 2px solid #000000;">EFRAIN MEDINA CARDENAS</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td>Firma:</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Producto</td>
                <td style="border-bottom: 2px solid #000000;">{{$producto}}</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr style="text-align: left;">
                {{-- <td></td> --}}
                <td>Cantidad</td>
                <td style="border-bottom: 2px solid #000000;">{{"".$cantidad}}</td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
                <td style="border-bottom: 2px solid #000000;text-align: left;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Proveedor</td>
                <td style="border-bottom: 2px solid #000000;"><p>{{$proveedor}}</p></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>N° Factura / O.C.</td>
                <td style="border-bottom: 2px solid #000000;">{{$oc}}</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Fecha de Recepción</td>
                <td style="border-bottom: 2px solid #000000;">{{$fecha_recepcion}}</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            {{-- ----------------------------------- --}}
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>VERIFICACIÓN</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>OBSERVACIONES</td>
                <td></td>
            </tr>


            <tr>
                {{-- <td></td> --}}
                <td>Cantidad solicitada</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Envase/embalaje en buen estado</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Producto en buenas condiciones</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Ficha técnica</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;"></td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;">X</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Hoja MSDS</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;"></td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;">X</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Certificado de calidad</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;"></td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;">X</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>

            {{-- ------------------------------ --}}
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>CONDICIONES DE ALMACENAMIENTO</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>OTROS</td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Sobre:</td>
                <td>Racks</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>Parihuelas</td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Bajo techo:</td>
                <td>SI</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>NO</td>
                <td style="border: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Iluminación:</td>
                <td>Natural</td>
                <td style="border: 2px solid #000000;"></td>
                <td></td>
                <td>Artificial</td>
                <td style="border: 2px solid #000000;">X</td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>Indicaciones importantes de Seguridad y Almacenamiento:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{-- ------------------------------------------- --}}
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>OBSERVACIONES</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
                <td style="border-bottom: 2px solid #000000;"></td>
            </tr>
            {{-- ------------------------------------------- --}}
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td>EVALUACIÓN FINAL</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                {{-- <td></td> --}}
                <td></td>
                <td>ACEPTACIÓN</td>
                <td style="border: 2px solid #000000;">X</td>
                <td></td>
                <td>RECHAZO</td>
                <td style="border: 2px solid #000000;"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

