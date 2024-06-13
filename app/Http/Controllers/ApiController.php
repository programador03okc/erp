<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tesoreria\TipoCambio;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\DB;

ini_set('max_execution_time', 0);
class ApiController extends Controller
{
    public function tipoCambioMasivo($desde, $hasta)
    {

        $fini = new DateTime($desde);
        $ffin = new DateTime($hasta);
        $compra = 0;
        $venta = 0;
        $promedio = 0;
        $data = [];

        for ($i = $fini; $i <= $ffin; $i->modify('+1 day')) {
            $fecha = $i->format('Y-m-d');
            $query = DB::table('contabilidad.cont_tp_cambio')->where('fecha', $fecha);

            if ($query->count() == 0) {
                $url = 'https://api.apis.net.pe/v2/sbs/tipo-cambio?date='.$fecha;
                $apiQ = json_decode($this->consultaSunat($url));
                if ($apiQ) {
                    $compra = (float) $apiQ->compra;
                    $venta = (float) $apiQ->venta;
                    $promedio = ($compra + $venta) / 2;
                    // $ventaMGC = $venta + ($venta * 0.01); // CALCULO ANTERIOR
                    $ventaMGC = $venta + ($venta * 0.02);
                }

                DB::table('contabilidad.cont_tp_cambio')->insertGetId([
                    'fecha'     => $fecha,
                    'moneda'    => 2,
                    'compra'    => $compra,
                    'venta'     => $venta,
                    'estado'    => 1,
                    'promedio'  => $promedio
                ], 'id_tp_cambio');

                DB::table('mgcp_cuadro_costos.tipo_cambio')->where('id', 1)->update([
                    'tipo_cambio'   => $ventaMGC,
                    'fecha'         => date('Y-m-d H:i:s')
                ]);

                $data[] = ['fecha' => $fecha, 'compra' => $compra, 'venta' => $venta, 'mgc' => $ventaMGC];
            }
        }
        return response()->json($data, 200);
    }

    public function tipoCambioActual()
    {
        $fecha = date('Y-m-d');
        $compra = 0;
        $venta = 0;
        $data = [];
        $query = DB::table('contabilidad.cont_tp_cambio')->where('fecha', $fecha);

        if ($query->count() > 0) {
            $rpta = 'exist';
            $compra = $query->orderBy('id_tp_cambio', 'desc')->first()->compra;
            $venta = $query->orderBy('id_tp_cambio', 'desc')->first()->venta;
            $promedio = $query->orderBy('id_tp_cambio', 'desc')->first()->promedio;
            $ventaMGC = $venta + ($venta * 0.01);
        } else {
            $rpta = 'null';
            $apiQ = json_decode($this->consultaSunat('https://api.apis.net.pe/v1/tipo-cambio-sunat'));
            $compra = (float) $apiQ->compra;
            $venta = (float) $apiQ->venta;
            $promedio = ($compra + $venta) / 2;
            $ventaMGC = $venta + ($venta * 0.01);

            DB::table('contabilidad.cont_tp_cambio')->insertGetId([
                'fecha'     => $fecha,
                'moneda'    => 2,
                'compra'    => $compra,
                'venta'     => $venta,
                'estado'    => 1,
                'promedio'  => $promedio
            ], 'id_tp_cambio');

        }

        DB::table('mgcp_cuadro_costos.tipo_cambio')->where('id', 1)->update([
            'tipo_cambio'   => $ventaMGC,
            'fecha'         => date('Y-m-d H:i:s')
        ]);
        // $data[] = ['fecha' => $fecha, 'compra' => $compra, 'venta' => $venta];
        // return response()->json(array('response' => $rpta, 'data' => $data), 200);

        die('***FIN DEL PROCESO. TIPO DE CAMBIO ACTUAL: ' . $fecha . '***');
    }

    public function consultaSunat($url)
    {
        $token = 'apis-token-9009.I7acqpEHhJrPnI6KtejmCdk0gfaoKoo6';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $token),
        ));
        return curl_exec($curl);
    }
}
