<?php

namespace App\Models\Finanzas;

use App\Helpers\ConfiguracionHelper;
use Illuminate\Database\Eloquent\Model;

class PresupuestoInternoDetalle extends Model
{
    //
    protected $table = 'finanzas.presupuesto_interno_detalle';
    protected $primaryKey = 'id_presupuesto_interno_detalle';
    public $timestamps = false;

    //1
    public function getFloatEneroAttribute()
    {
      return floatval(str_replace(",", "", $this->enero));
    }
    //2
    public function getFloatFebreroAttribute()
    {
      return floatval(str_replace(",", "", $this->febrero));
    }
    //3
    public function getFloatMarzoAttribute()
    {
      return floatval(str_replace(",", "", $this->marzo));
    }
    //4
    public function getFloatAbrilAttribute()
    {
      return floatval(str_replace(",", "", $this->abril));
    }
    //5
    public function getFloatMayoAttribute()
    {
      return floatval(str_replace(",", "", $this->mayo));
    }
    //6
    public function getFloatJunioAttribute()
    {
      return floatval(str_replace(",", "", $this->junio));
    }
    //7
    public function getFloatJulioAttribute()
    {
      return floatval(str_replace(",", "", $this->julio));
    }
    //8
    public function getFloatAgostoAttribute()
    {
      return floatval(str_replace(",", "", $this->agosto));
    }
    //9
    public function getFloatSetiembreAttribute()
    {
      return floatval(str_replace(",", "", $this->setiembre));
    }
    //10
    public function getFloatOctubreAttribute()
    {
      return floatval(str_replace(",", "", $this->octubre));
    }
    //11
    public function getFloatNoviembreAttribute()
    {
      return floatval(str_replace(",", "", $this->noviembre));
    }
    //12
    public function getFloatDiciembreAttribute()
    {
      return floatval(str_replace(",", "", $this->diciembre));
    }

    // -----aux----
    //1
    public function getFloatEneroAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->enero_aux));
    }
    //2
    public function getFloatFebreroAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->febrero_aux));
    }
    //3
    public function getFloatMarzoAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->marzo_aux));
    }
    //4
    public function getFloatAbrilAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->abril_aux));
    }
    //5
    public function getFloatMayoAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->mayo_aux));
    }
    //6
    public function getFloatJunioAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->junio_aux));
    }
    //7
    public function getFloatJulioAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->julio_aux));
    }
    //8
    public function getFloatAgostoAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->agosto_aux));
    }
    //9
    public function getFloatSetiembreAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->setiembre_aux));
    }
    //10
    public function getFloatOctubreAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->octubre_aux));
    }
    //11
    public function getFloatNoviembreAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->noviembre_aux));
    }
    //12
    public function getFloatDiciembreAuxAttribute()
    {
      return floatval(str_replace(",", "", $this->diciembre_aux));
    }
    public static function cierreMensual(){
        $mes_actual = date("m", strtotime('-1 month'));
        $mes_siguiente = date('m');
        // $mes_actual = "01";
        // $mes_siguiente = "02";
        $año_actua = date('Y');

        // return [$mes_actual, $mes_siguiente, $año_actua];
        $presupuesto_interno = PresupuestoInterno::where('estado',2)
        ->whereYear('fecha_registro',$año_actua)
        // ->where('id_presupuesto_interno',37)39
        ->whereIn('id_presupuesto_interno',[37,39])
        ->get();
        // return $presupuesto_interno;
        $nombre_mes = ConfiguracionHelper::mesNumero($mes_actual);
        $nombre_mes_siguiente = ConfiguracionHelper::mesNumero($mes_siguiente);
        // return $nombre_mes_siguiente;
        $mes_aux = $nombre_mes.'_aux';
        $mes_siguiente =  $nombre_mes_siguiente.'_aux';
        // return [$mes_aux, $mes_siguiente];exit;
        // return $presupuesto_interno;
        foreach ($presupuesto_interno as $key => $value) {
            $historial = PresupuestoInternoDetalle::where('id_presupuesto_interno',$value->id_presupuesto_interno)
            // ->whereMonth('fecha_registro',$numero_mes)
            ->where('estado','!=',7)
            ->where('id_tipo_presupuesto',3)
            ->orderBy('partida', 'asc')
            ->get();

            foreach ($historial as $k_h => $v_h) {
                if (
                    $v_h->registro == '2'
                    // || ($v_h->partida!= '03.01.01.01' && $v_h->id_presupuesto_interno != 39)
                    ) {

                    // if ($v_h->partida!= '03.01.01.01' && $v_h->id_presupuesto_interno != 39){

                    // }
                    $saldo_mes_actual = floatval(str_replace(",", "", $v_h->$mes_aux)); // saldo del mes actual (febrero)
                    $saldo_mes_actual = ($saldo_mes_actual>0?$saldo_mes_actual:0); // valido que el saldo no sea negativo (febrero)

                    $inicio_mes_siguiente = ($nombre_mes!=='diciembre'?floatval(str_replace(",", "", $v_h->$nombre_mes_siguiente)):0) ; // mes de (marzo)
                    // return [$inicio_mes_siguiente,$saldo_mes_actual];
                    $saldo_mes_siguiente =  $saldo_mes_actual + $inicio_mes_siguiente; // campo de mes el que visualiza el usuario



                    $mes_siguiente = ($nombre_mes!=='diciembre'?$mes_siguiente:'saldo_anual'); //
                    // return [
                    //     $mes_siguiente,
                    //     number_format($saldo_mes_siguiente, 2, '.', ','),
                    //     $mes_siguiente
                    // ];
                    // modifica el saldo del mes siguiente
                    $presupuesto_interno_detalle= PresupuestoInternoDetalle::find($v_h->id_presupuesto_interno_detalle);
                    // $presupuesto_interno_detalle->$nombre_mes_siguiente = number_format($saldo_mes_siguiente, 2, '.', ',');
                    $presupuesto_interno_detalle->$mes_siguiente = number_format($saldo_mes_siguiente, 2, '.', ',');
                    $presupuesto_interno_detalle->save();


                    #historial de saldo
                    // $historial = HistorialPresupuestoInternoSaldo::where('id_presupuesto_interno',$presupuesto_interno_detalle->id_presupuesto_interno)
                    // ->where('id_partida',$presupuesto_interno_detalle->id_presupuesto_interno_detalle)
                    // ->where('descripcion','saldo del mes anterior')->first();
                    // if (!$historial) {
                    //     $historial = new HistorialPresupuestoInternoSaldo();
                    // }
                    //     $historial->id_presupuesto_interno = $presupuesto_interno_detalle->id_presupuesto_interno;
                    //     $historial->id_partida = $presupuesto_interno_detalle->id_presupuesto_interno_detalle;
                    //     $historial->tipo = 'INGRESO';
                    //     $historial->importe = floatval(str_replace(",", "", $saldo_mes_actual));
                    //     $historial->mes = $mes_siguiente;
                    //     $historial->fecha_registro = date('Y-m-d H:i:s');
                    //     $historial->estado = 3;
                    //     $historial->descripcion = 'saldo del mes anterior';
                    //     $historial->operacion = 'S';
                    // $historial->save();
                    #-----
                    PresupuestoInterno::calcularColumnaAuxMensual(
                        $presupuesto_interno_detalle->id_presupuesto_interno,
                        $presupuesto_interno_detalle->id_tipo_presupuesto,
                        $presupuesto_interno_detalle->id_presupuesto_interno_detalle,
                        $nombre_mes_siguiente
                    );
                }
            }

            foreach ($historial as $k_h => $v_h) {
                PresupuestoInternoDetalle::replicaAux($v_h->id_presupuesto_interno_detalle, $nombre_mes_siguiente, $mes_siguiente);
            }
        }

        return ["presupuesto_interno"=>$presupuesto_interno];
    }
    public static function replicaAux($id_presupuesto_interno_detalle, $mes, $mes_siguiente)
    {
        $presupuesto_interno_destalle= PresupuestoInternoDetalle::find($id_presupuesto_interno_detalle);
        $presupuesto_interno_destalle->$mes = $presupuesto_interno_destalle->$mes_siguiente;
        // $presupuesto_interno_destalle->febrero                  = $presupuesto_interno_destalle->febrero_aux;
        // $presupuesto_interno_destalle->marzo                    = $presupuesto_interno_destalle->marzo_aux;
        // $presupuesto_interno_destalle->abril                    = $presupuesto_interno_destalle->abril_aux;
        // $presupuesto_interno_destalle->mayo                     = $presupuesto_interno_destalle->mayo_aux;
        // $presupuesto_interno_destalle->junio                    = $presupuesto_interno_destalle->junio_aux;
        // $presupuesto_interno_destalle->julio                    = $presupuesto_interno_destalle->julio_aux;
        // $presupuesto_interno_destalle->agosto                   = $presupuesto_interno_destalle->agosto_aux;
        // $presupuesto_interno_destalle->setiembre                = $presupuesto_interno_destalle->setiembre_aux;
        // $presupuesto_interno_destalle->octubre                  = $presupuesto_interno_destalle->octubre_aux;
        // $presupuesto_interno_destalle->noviembre                = $presupuesto_interno_destalle->noviembre_aux;
        // $presupuesto_interno_destalle->diciembre                = $presupuesto_interno_destalle->diciembre_aux;
        $presupuesto_interno_destalle->save();

        return $presupuesto_interno_destalle;
    }
}
