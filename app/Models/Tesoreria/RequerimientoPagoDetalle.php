<?php

namespace App\Models\Tesoreria;

use App\Models\Finanzas\PresupuestoInternoDetalle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Empty_;

class RequerimientoPagoDetalle extends Model
{
    protected $table = 'tesoreria.requerimiento_pago_detalle';
    protected $primaryKey = 'id_requerimiento_pago_detalle';
    protected $appends = ['presupuesto_interno_total_partida', 'presupuesto_interno_mes_partida'];

    public $timestamps = false;

    public function getFechaRegistroAttribute()
    {
        $fecha = new Carbon($this->attributes['fecha_registro']);
        return $fecha->format('d-m-Y H:i');
    }
    public function getPresupuestoInternoTotalPartidaAttribute()
    {

        $presupuestoInternoTotalPartida = 0;
        if (isset($this->attributes['id_partida_pi'])) {
            $presupuestoInternoDetalle = PresupuestoInternoDetalle::where('id_presupuesto_interno_detalle', $this->attributes['id_partida_pi'])->first();
            if ($presupuestoInternoDetalle) {
                $presupuestoInternoTotalPartida = floatval(str_replace(",", "", $presupuestoInternoDetalle->enero)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->febrero)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->marzo)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->abril)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->mayo)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->junio)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->julio)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->agosto)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->setiembre)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->octubre)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->noviembre)) +
                    floatval(str_replace(",", "", $presupuestoInternoDetalle->diciembre));
            }
        }

        return $presupuestoInternoTotalPartida;
    }

    public function getPresupuestoInternoMesPartidaAttribute()
    {

        if (isset($this->attributes['id_partida_pi'])) {
            $presupuestoInternoDetalle = PresupuestoInternoDetalle::where('id_presupuesto_interno_detalle', $this->attributes['id_partida_pi'])->first();
            $requerimientopago = RequerimientoPago::find($this->attributes['id_requerimiento_pago']);
            $month = (new Carbon($requerimientopago->fecha_registro))->format('m');
            
            if ($presupuestoInternoDetalle) {
                switch ($month) {
                    case 1:
                        return $presupuestoInternoDetalle->enero;
                        break;

                    case 2:
                        return $presupuestoInternoDetalle->febrero;
                        break;

                    case 3:
                        return $presupuestoInternoDetalle->marzo;
                        break;

                    case 4:
                        return $presupuestoInternoDetalle->abril;
                        break;

                    case 5:
                        return $presupuestoInternoDetalle->mayo;
                        break;

                    case 6:
                        return $presupuestoInternoDetalle->junio;
                        break;

                    case 7:
                        return $presupuestoInternoDetalle->julio;
                        break;

                    case 8:
                        return $presupuestoInternoDetalle->agosto;
                        break;

                    case 9:
                        return $presupuestoInternoDetalle->setiembre;
                        break;

                    case 10:
                        return $presupuestoInternoDetalle->octubre;
                        break;

                    case 11:
                        return $presupuestoInternoDetalle->noviembre;
                        break;

                    case 12:
                        return $presupuestoInternoDetalle->diciembre;
                        break;

                    default:
                        return 0;
                        break;
                }
            } else {
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function adjunto()
    {
        return $this->hasMany('App\Models\Tesoreria\RequerimientoPagoAdjuntoDetalle', 'id_requerimiento_pago_detalle', 'id_requerimiento_pago_detalle');
    }
    public function presupuestoInternoDetalle()
    {
        return $this->hasone('App\Models\Finanzas\PresupuestoInternoDetalle', 'id_presupuesto_interno_detalle', 'id_partida_pi');
    }
    public function centroCosto()
    {
        return $this->hasone('App\Models\Presupuestos\CentroCosto', 'id_centro_costo', 'id_centro_costo');
    }
    public function partida()
    {
        return $this->hasone('App\Models\Presupuestos\Partida', 'id_partida', 'id_partida');
    }
    public function producto()
    {
        return $this->hasone('App\Models\Almacen\Producto', 'id_producto', 'id_producto');
    }
    public function unidadMedida()
    {
        return $this->hasone('App\Models\Almacen\UnidadMedida', 'id_unidad_medida', 'id_unidad_medida');
    }
    public function estado()
    {
        return $this->hasone('App\Models\Administracion\Estado', 'id_estado_doc', 'id_estado');
    }
}
