<?php

namespace App\Models\Tesoreria;

use App\Models\Finanzas\PresupuestoInternoDetalle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RequerimientoPagoDetalle extends Model
{
    protected $table = 'tesoreria.requerimiento_pago_detalle';
    protected $primaryKey = 'id_requerimiento_pago_detalle';
    protected $appends = ['presupuesto_interno_total_partida','presupuesto_interno_mes_partida'];

    public $timestamps = false;
 
    public function getFechaRegistroAttribute()
    {
        $fecha = new Carbon($this->attributes['fecha_registro']);
        return $fecha->format('d-m-Y H:i');
    }
    public function getPresupuestoInternoTotalPartidaAttribute()
    {

            $presupuestoInternoTotalPartida=0;
            $presupuestoInternoDetalle =PresupuestoInternoDetalle::where('id_presupuesto_interno_detalle',$this->attributes['id_partida_pi'])->first();
            if($presupuestoInternoDetalle){
                $presupuestoInternoTotalPartida = floatval($presupuestoInternoDetalle->enero)+
                floatval($presupuestoInternoDetalle->febrero)+
                floatval($presupuestoInternoDetalle->abril)+
                floatval($presupuestoInternoDetalle->mayo)+
                floatval($presupuestoInternoDetalle->junio)+
                floatval($presupuestoInternoDetalle->julio)+
                floatval($presupuestoInternoDetalle->agosto)+
                floatval($presupuestoInternoDetalle->setiembre)+
                floatval($presupuestoInternoDetalle->octubre)+
                floatval($presupuestoInternoDetalle->noviembre)+
                floatval($presupuestoInternoDetalle->diciembre);

            }

        return $presupuestoInternoTotalPartida;
    }

    public function getPresupuestoInternoMesPartidaAttribute(){

        $presupuestoInternoDetalle =PresupuestoInternoDetalle::where('id_presupuesto_interno_detalle',$this->attributes['id_partida_pi'])->first();
        $month = date('m');

        if($presupuestoInternoDetalle){
            switch ($month) {
                case 1:
                    return $presupuestoInternoDetalle->enero_aux;
                    break;
                
                case 2:
                    return $presupuestoInternoDetalle->febrero_aux;
                    break;
                
                case 3:
                    return $presupuestoInternoDetalle->marzo_aux;
                    break;
                
                case 4:
                    return $presupuestoInternoDetalle->abril_aux;
                    break;
                
                case 5:
                    return $presupuestoInternoDetalle->mayo_aux;
                    break;
                
                case 6:
                    return $presupuestoInternoDetalle->junio_aux;
                    break;
                
                case 7:
                    return $presupuestoInternoDetalle->julio_aux;
                    break;
                
                case 8:
                    return $presupuestoInternoDetalle->agosto_aux;
                    break;
                
                case 9:
                    return $presupuestoInternoDetalle->setiembre_aux;
                    break;
                
                case 10:
                    return $presupuestoInternoDetalle->octubre_aux;
                    break;
                
                case 11:
                    return $presupuestoInternoDetalle->noviembre_aux;
                    break;
                
                case 12:
                    return $presupuestoInternoDetalle->diciembre_aux;
                    break;
                
                default:
                    return 0;
                    break;
            }

        }else{
            return 0;
        }
    }

    public function adjunto(){
        return $this->hasMany('App\Models\Tesoreria\RequerimientoPagoAdjuntoDetalle','id_requerimiento_pago_detalle','id_requerimiento_pago_detalle');
    }
    public function presupuestoInternoDetalle(){
        return $this->hasone('App\Models\Finanzas\PresupuestoInternoDetalle','id_presupuesto_interno_detalle','id_partida_pi');
    }
    public function centroCosto(){
        return $this->hasone('App\Models\Presupuestos\CentroCosto','id_centro_costo','id_centro_costo');
    }
    public function partida(){
        return $this->hasone('App\Models\Presupuestos\Partida','id_partida','id_partida');
    }
    public function producto(){
        return $this->hasone('App\Models\Almacen\Producto','id_producto','id_producto');
    }
    public function unidadMedida(){
        return $this->hasone('App\Models\Almacen\UnidadMedida','id_unidad_medida','id_unidad_medida');
    }
    public function estado(){
        return $this->hasone('App\Models\Administracion\Estado','id_estado_doc','id_estado');
    }
}
