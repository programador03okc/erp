<?php

namespace App\Models\administracion;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DashboardSeguimientoView extends Model
{

    protected $table = 'administracion.dashboard_seguimiento_view';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $appends = ['dias_para_entrega','comercial','compras','almacen','cas','despacho'];



    public function getDiasParaEntregaAttribute()
    {
        // obtener el tiempo de entrega
        $result = '';
        $fecha_entrega = Carbon::parse($this->attributes['fecha_entrega']);
        if ($fecha_entrega) {
            // $fecha_entrega_orden_date = Carbon::parse($fecha_entrega)->format('d-m-Y H:i:s');
            // $echa_hoy= Carbon::parse()->format('d-m-Y H:i:s');
            $fechaHoy = Carbon::now();
            $diferencia = $fecha_entrega->diff($fechaHoy);
            $diasParaEntrega = $diferencia->days;
            $horasParaEntrega = $diferencia->h;
            $minutosParaEntrega = $diferencia->i;

            if($fechaHoy > $fecha_entrega){
                $result = '<span class="text-danger">(Plazo Vencido)<span>';
            }else{
                
                $result = $diasParaEntrega . 'd ' . $horasParaEntrega . 'h ' . $minutosParaEntrega . 'm';
            }
        }

        return $result;
    }
    public function getComercialAttribute()
    {

        //
        if ($this->attributes['fecha_publicacion_orden'] != null) {
            $fechaPublicacionOrden = Carbon::parse($this->attributes['fecha_publicacion_orden'])->format('d-m-Y H:i:s');
        } else {
            $fechaPublicacionOrden = Carbon::make(null);
        }

        if ($this->attributes['fecha_creacion_cdp'] != null) {
            $fechaCreacionCdp = Carbon::parse($this->attributes['fecha_creacion_cdp'])->format('d-m-Y H:i:s');
        } else {
            $fechaCreacionCdp = Carbon::make(null);
        }

        if ($this->attributes['fecha_aprobacion_cdp'] != null) {
            $fechaAprobacionCdp = Carbon::parse($this->attributes['fecha_aprobacion_cdp'])->format('d-m-Y H:i:s');
        } else {
            $fechaAprobacionCdp = Carbon::make(null);
        }

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaPublicacionOrden, $fechaCreacionCdp, $fechaAprobacionCdp);

        // obtener tiempo global de area
        $fechas = [$fechaPublicacionOrden, $fechaCreacionCdp, $fechaAprobacionCdp];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i+1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;



        return '
        <div class="grid">
            <div class="tiempo-global-area">' .$diasGlobalArea.'d '. $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaPublicacionOrden != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">PUBLICACION</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaPublicacionOrden != null ? $fechaPublicacionOrden : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaCreacionCdp != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">GENERAR CDP</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaCreacionCdp != null ? $fechaCreacionCdp : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaAprobacionCdp != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">APROBACIÓN</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaAprobacionCdp != null ? $fechaAprobacionCdp : '') . '</div>
            
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>

            <div class="tiempo-ingreso-area">' . ($fechaAprobacionCdp != null ? $fechaAprobacionCdp : '') . '</div>
            <div class="flechas">
                <div style="display:flex; flex-direction: row; justify-content:space-between; flex-wrap:nowrap; text-align:center;">
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-left fa-lg azul" style="font-size:5em;"></i>
                    </div>
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-right fa-lg rojo" style="font-size:5em;"></i>
                    </div>
                </div>
            </div>
            <div class="tiempo-salida-area">' . $tiempoTotalSalida . '</div>

        </div>
        ';
    }

    public function getComprasAttribute()
    {

        if ($this->attributes['fecha_ultimo_mapeo_generado'] != null) {
            $fechaUltimoMapeo = Carbon::parse($this->attributes['fecha_ultimo_mapeo_generado'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimoMapeo = Carbon::make(null);
        }

        if ($this->attributes['fecha_ultimo_orden_generada'] != null) {
            $fechaUltimaOrdenGenerada = Carbon::parse($this->attributes['fecha_ultimo_orden_generada'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimaOrdenGenerada = Carbon::make(null);
        }



        if ($this->attributes['fecha_ultimo_envio_a_pago'] != null) {
            $fechaUltimoEnvioAPago = Carbon::parse($this->attributes['fecha_ultimo_envio_a_pago'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimoEnvioAPago = Carbon::make(null);
        }

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaUltimoMapeo, $fechaUltimaOrdenGenerada, $fechaUltimoEnvioAPago);
        // obtener tiempo global de area
        // if($fechaUltimoEnvioAPago==null){}
        $fechas = [$fechaUltimoMapeo, $fechaUltimaOrdenGenerada, $fechaUltimoEnvioAPago];
        
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i+1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;

 
        $html= '
        <div class="grid">
            <div class="tiempo-global-area">' .$diasGlobalArea.'d '. $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoMapeo != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">MAPEO PRODUCTOS</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoMapeo != null ? $fechaUltimoMapeo : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimaOrdenGenerada != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">GENERAR ORDEN</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimaOrdenGenerada != null ? $fechaUltimaOrdenGenerada : '') . '</div>
            ';
            $html.='
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoEnvioAPago != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">ENVIO A PAGO</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoEnvioAPago != null ? $fechaUltimoEnvioAPago : '') . '</div>

            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>

            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>

            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>

            <div class="tiempo-ingreso-area">' . ($fechaUltimoEnvioAPago != null ? $fechaUltimoEnvioAPago : '') . '</div>
            <div class="flechas">
                <div style="display:flex; flex-direction: row; justify-content:space-between; flex-wrap:nowrap; text-align:center;">
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-left fa-lg azul" style="font-size:5em;"></i>
                    </div>
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-right fa-lg rojo" style="font-size:5em;"></i>
                    </div>
                </div>
            </div>
            <div class="tiempo-salida-area">' . $tiempoTotalSalida . '</div>

        </div>
        ';

        return $html;
    }

    public function getAlmacenAttribute()
    {

        
        if ($this->attributes['fecha_ultimo_ingreso_almacen'] != null) {
            $fechaUltimoIngresoAlmacen = Carbon::parse($this->attributes['fecha_ultimo_ingreso_almacen'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimoIngresoAlmacen = Carbon::make(null);
        }

        if ($this->attributes['fecha_salida_almacen_odi'] != null) {
            $fechaSalidaAlmacenODI = Carbon::parse($this->attributes['fecha_salida_almacen_odi'])->format('d-m-Y H:i:s');
        } else {
            $fechaSalidaAlmacenODI = Carbon::make(null);
        }

        if ($this->attributes['fecha_salida_almacen_ode'] != null) {
            $fechaSalidaAlmacenODE = Carbon::parse($this->attributes['fecha_salida_almacen_ode'])->format('d-m-Y H:i:s');
        } else {
            $fechaSalidaAlmacenODE = Carbon::make(null);
        }

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaUltimoIngresoAlmacen, $fechaSalidaAlmacenODI, $fechaSalidaAlmacenODE);

        // obtener tiempo global de area
        $fechas = [$fechaUltimoIngresoAlmacen, $fechaSalidaAlmacenODI, $fechaSalidaAlmacenODE];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i+1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html= '
        <div class="grid">
        <div class="tiempo-global-area">' .$diasGlobalArea.'d '. $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
        <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoIngresoAlmacen != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">INGRESO PRODUCTO</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoIngresoAlmacen != null ? $fechaUltimoIngresoAlmacen : '') . '</div>
            ';
            if($this->attributes['tiene_transformacion']==true){

            $html.='<div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaAlmacenODI != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">SALIDA ODI</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSalidaAlmacenODI != null ? $fechaSalidaAlmacenODI : '') . '</div>
            ';
            }
            $html.='<div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaAlmacenODE != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">SALIDA ODE</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSalidaAlmacenODE != null ? $fechaSalidaAlmacenODE : '') . '</div>
            
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>


            <div class="tiempo-ingreso-area">' . ($fechaSalidaAlmacenODE != null ? $fechaSalidaAlmacenODE : '') . '</div>
            <div class="flechas">
                <div style="display:flex; flex-direction: row; justify-content:space-between; flex-wrap:nowrap; text-align:center;">
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-left fa-lg azul" style="font-size:5em;"></i>
                    </div>
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-right fa-lg rojo" style="font-size:5em;"></i>
                    </div>
                </div>
            </div>
            <div class="tiempo-salida-area">' . $tiempoTotalSalida . '</div>

        </div>
        ';

        return $html;
    }

    public function getCasAttribute()
    {

        
        if ($this->attributes['fecha_inicio_cas'] != null) {
            $fechaInicioCAS = Carbon::parse($this->attributes['fecha_inicio_cas'])->format('d-m-Y H:i:s');
        } else {
            $fechaInicioCAS = Carbon::make(null);
        }

        if ($this->attributes['fecha_transformacion_cas'] != null) {
            $fechaTransformacionCAS = Carbon::parse($this->attributes['fecha_transformacion_cas'])->format('d-m-Y H:i:s');
        } else {
            $fechaTransformacionCAS = Carbon::make(null);
        }


        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaInicioCAS, $fechaTransformacionCAS);

        // obtener tiempo global de area
        $fechas = [$fechaInicioCAS, $fechaTransformacionCAS];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i+1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html= '
        <div class="grid">
            <div class="tiempo-global-area">' .$diasGlobalArea.'d '. $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaInicioCAS != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">INICIO TRANSFORMACIÓN</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaInicioCAS != null ? $fechaInicioCAS : '') . '</div>
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaTransformacionCAS != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">FIN TRANSFORMACIÓN</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaTransformacionCAS != null ? $fechaTransformacionCAS : '') . '</div>
        
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            
            <div class="tiempo-ingreso-area">' . ($fechaTransformacionCAS != null ? $fechaTransformacionCAS : '') . '</div>
            <div class="flechas">
                <div style="display:flex; flex-direction: row; justify-content:space-between; flex-wrap:nowrap; text-align:center;">
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-left fa-lg azul" style="font-size:5em;"></i>
                    </div>
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-right fa-lg rojo" style="font-size:5em;"></i>
                    </div>
                </div>
            </div>
            <div class="tiempo-salida-area">' . $tiempoTotalSalida . '</div>

        </div>
        ';

        return $html;
    }

    
    public function getDespachoAttribute()
    {

        
        if ($this->attributes['fecha_programacion_odi'] != null) {
            $fechaProgramacionODI = Carbon::parse($this->attributes['fecha_programacion_odi'])->format('d-m-Y H:i:s');
        } else {
            $fechaProgramacionODI = Carbon::make(null);
        }

        if ($this->attributes['fecha_programacion_ode'] != null) {
            $fechaProgramacionODE = Carbon::parse($this->attributes['fecha_programacion_ode'])->format('d-m-Y H:i:s');
        } else {
            $fechaProgramacionODE = Carbon::make(null);
        }

        if ($this->attributes['fecha_salida_despacho'] != null) {
            $fechaSalidaDespacho = Carbon::parse($this->attributes['fecha_salida_despacho'])->format('d-m-Y H:i:s');
        } else {
            $fechaSalidaDespacho = Carbon::make(null);
        }

        if ($this->attributes['fecha_entregado_conforme'] != null) {
            $fechaEntregadoConforme = Carbon::parse($this->attributes['fecha_entregado_conforme'])->format('d-m-Y H:i:s');
        } else {
            $fechaEntregadoConforme = Carbon::make(null);
        }

        $fechaRetornioGuiaCellada='';

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaProgramacionODI,$fechaProgramacionODE, $fechaSalidaDespacho, $fechaEntregadoConforme);

        // obtener tiempo global de area
        $fechas = [$fechaProgramacionODI,$fechaProgramacionODE,$fechaSalidaDespacho, $fechaEntregadoConforme];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i+1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html= '
        <div class="grid">';
        if($this->attributes['tiene_transformacion']==true){
            $html.='<div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaProgramacionODI != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">ORDEN DESPACHO INTERNO</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaProgramacionODI != null ? $fechaProgramacionODI : '') . '</div>
            ';
        }
            $html.=' 
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaProgramacionODE != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">ORDEN DESPACHO EXTERNO</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaProgramacionODE != null ? $fechaProgramacionODE : '') . '</div>

            <div class="tiempo-global-area">' .$diasGlobalArea.'d '. $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaDespacho != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">SALIDA DESPACHO</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSalidaDespacho != null ? $fechaSalidaDespacho : '') . '</div>
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaEntregadoConforme != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">ENTREGADO CONFORME</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaEntregadoConforme != null ? $fechaEntregadoConforme : '') . '</div>
        
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaRetornioGuiaCellada != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">RETORNO DE GUIA CELLADA</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaRetornioGuiaCellada != null ? $fechaRetornioGuiaCellada : '') . '</div>
        
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>


            <div class="tiempo-ingreso-area">' . ($fechaEntregadoConforme != null ? $fechaEntregadoConforme : '') . '</div>
            <div class="flechas">
                <div style="display:flex; flex-direction: row; justify-content:space-between; flex-wrap:nowrap; text-align:center;">
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-left fa-lg azul" style="font-size:5em;"></i>
                    </div>
                    <div style="display:block; width: 70px;">
                        <i class="fas fa-long-arrow-alt-right fa-lg rojo" style="font-size:5em;"></i>
                    </div>
                </div>
            </div>
            <div class="tiempo-salida-area">' . $tiempoTotalSalida . '</div>

        </div>
        ';

        return $html;
    }
}
