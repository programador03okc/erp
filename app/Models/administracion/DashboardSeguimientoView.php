<?php

namespace App\Models\administracion;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DashboardSeguimientoView extends Model
{

    protected $table = 'administracion.dashboard_seguimiento_view';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $appends = ['dias_para_entrega', 'comercial', 'compras', 'almacen', 'cas', 'despacho'];



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

            if ($fechaHoy > $fecha_entrega) {
                $result = '<span class="text-danger">(Plazo Vencido)<span>';
            } else {

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


        if ($this->attributes['fecha_solicitud_aprobacion_cdp'] != null) {
            $fechaSolicitudAprobacionCdp = Carbon::parse($this->attributes['fecha_solicitud_aprobacion_cdp'])->format('d-m-Y H:i:s');
        } else {
            $fechaSolicitudAprobacionCdp = Carbon::make(null);
        }
        if ($this->attributes['fecha_aprobacion_cdp'] != null) {
            $fechaUltimaAprobacionCdp = Carbon::parse($this->attributes['fecha_aprobacion_cdp'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimaAprobacionCdp = Carbon::make(null);
        }
        if ($this->attributes['fecha_aprobacion_cdp_anterior'] != null) {
            $fechaAprobacionAnteriorCdp = Carbon::parse($this->attributes['fecha_aprobacion_cdp_anterior'])->format('d-m-Y H:i:s');
        } else {
            $fechaAprobacionAnteriorCdp = Carbon::make(null);
        }

        $fechaAprobacion = null;
        $fechaUltimaReAprobacion = null;
        if ($fechaAprobacionAnteriorCdp != null) {
            $fechaAprobacion = $fechaAprobacionAnteriorCdp;
            $fechaUltimaReAprobacion = $fechaUltimaAprobacionCdp;
        } else {
            $fechaAprobacion = $fechaUltimaAprobacionCdp;
            // $fechaUltimaReAprobacion= $fechaAprobacionAnteriorCdp;


        }
        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaPublicacionOrden, $fechaSolicitudAprobacionCdp, $fechaAprobacion);

        // obtener tiempo global de area
        $fechas = [$fechaPublicacionOrden, $fechaSolicitudAprobacionCdp, $fechaAprobacion];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i + 1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;



        return '
        <div class="grid">
            <div class="tiempo-global-area">' . $diasGlobalArea . 'd ' . $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaPublicacionOrden != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Publicación de OC</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaPublicacionOrden != null ? $fechaPublicacionOrden : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSolicitudAprobacionCdp != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad"><span>Generación de CP <small>(en Pendiente de Aprobación)</small></span></div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSolicitudAprobacionCdp != null ? $fechaSolicitudAprobacionCdp : '') . '</div>
        
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaAprobacion != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Aprobación</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaAprobacion != null ? $fechaAprobacion : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimaReAprobacion != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad"><span> Re-aprobación <small>(casos de modificaciones)</small></span></div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimaReAprobacion != null ? $fechaUltimaReAprobacion : '') . '</div>
            
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>


            <div class="tiempo-ingreso-area">' . ($fechaUltimaAprobacionCdp != null ? $fechaUltimaAprobacionCdp : '') . '</div>
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

        if ($this->attributes['fecha_ultima_reserva_generada'] != null) {
            $fechaUltimaReserva = Carbon::parse($this->attributes['fecha_ultima_reserva_generada'])->format('d-m-Y H:i:s');
        } else {
            $fechaUltimaReserva = Carbon::make(null);
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
        $tiempoTotalSalida = max($fechaUltimoMapeo, $fechaUltimaReserva, $fechaUltimaOrdenGenerada, $fechaUltimoEnvioAPago);
        // obtener tiempo global de area
        // if($fechaUltimoEnvioAPago==null){}
        $fechas = [$fechaUltimoMapeo, $fechaUltimaReserva, $fechaUltimaOrdenGenerada, $fechaUltimoEnvioAPago];

        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i + 1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html = '
        <div class="grid">
            <div class="tiempo-global-area">' . $diasGlobalArea . 'd ' . $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoMapeo != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Mapeo items</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoMapeo != null ? $fechaUltimoMapeo : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimaReserva != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Reserva items</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimaReserva != null ? $fechaUltimaReserva : '') . '</div>

            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimaOrdenGenerada != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Generación de OC</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimaOrdenGenerada != null ? $fechaUltimaOrdenGenerada : '') . '</div>
            ';
        $html .= '
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoEnvioAPago != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Envío a pago</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoEnvioAPago != null ? $fechaUltimoEnvioAPago : '') . '</div>

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
        $tiempoTotalSalida = max($fechaUltimoIngresoAlmacen, $fechaSalidaAlmacenODI,$fechaSalidaAlmacenODE);

        // obtener tiempo global de area
        $fechas = [$fechaUltimoIngresoAlmacen, $fechaSalidaAlmacenODI, $fechaSalidaAlmacenODE];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i + 1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html = '
        <div class="grid">
        <div class="tiempo-global-area">' . $diasGlobalArea . 'd ' . $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
        <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaUltimoIngresoAlmacen != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Ingreso de mercadería</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaUltimoIngresoAlmacen != null ? $fechaUltimoIngresoAlmacen : '') . '</div>
            ';
        if ($this->attributes['tiene_transformacion'] == true) {

            $html .= '<div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaAlmacenODI != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Picking/ envío a CAS (ODI)</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSalidaAlmacenODI != null ? $fechaSalidaAlmacenODI : '') . '</div>
            ';
        }
        $html .= '<div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaAlmacenODE != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Generación documentos (Salida producto)</div>
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
        if ($this->attributes['fecha_devolucion'] != null) {
            $fechaDevolucionDeComponentes = Carbon::parse($this->attributes['fecha_devolucion'])->format('d-m-Y H:i:s');
        } else {
            $fechaDevolucionDeComponentes = Carbon::make(null);
        }
 

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaInicioCAS, $fechaTransformacionCAS,$fechaDevolucionDeComponentes);

        // obtener tiempo global de area
        $fechas = [$fechaInicioCAS, $fechaTransformacionCAS,$fechaDevolucionDeComponentes];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i + 1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html = '
        <div class="grid">
            <div class="tiempo-global-area">' . $diasGlobalArea . 'd ' . $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaInicioCAS != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Inicio customización</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaInicioCAS != null ? $fechaInicioCAS : '') . '</div>
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaTransformacionCAS != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Fin customización</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaTransformacionCAS != null ? $fechaTransformacionCAS : '') . '</div>
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaDevolucionDeComponentes != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Devolución de componentes sobrantes</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaDevolucionDeComponentes != null ? $fechaDevolucionDeComponentes : '') . '</div>


            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            
            <div class="tiempo-ingreso-area">' . ($fechaDevolucionDeComponentes != null ? $fechaDevolucionDeComponentes : '') . '</div>
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

        if ($this->attributes['fecha_retorno_guia_firmanda'] != null) {
            $fechaRetornioGuiaFirmada = Carbon::parse($this->attributes['fecha_retorno_guia_firmanda'])->format('d-m-Y H:i:s');
        } else {
            $fechaRetornioGuiaFirmada = Carbon::make(null);
        }

        if ($this->attributes['fecha_carga_guia_firmada_sistema'] != null) {
            $fechaCargaGuiaFirmadaASistema = Carbon::parse($this->attributes['fecha_carga_guia_firmada_sistema'])->format('d-m-Y H:i:s');
        } else {
            $fechaCargaGuiaFirmadaASistema = Carbon::make(null);
        }

       

        // obtener tiempo total salida
        $tiempoTotalSalida = max($fechaSalidaDespacho, $fechaEntregadoConforme,$fechaRetornioGuiaFirmada,$fechaCargaGuiaFirmadaASistema);

        // obtener tiempo global de area
        $fechas = [$fechaSalidaDespacho, $fechaEntregadoConforme,$fechaRetornioGuiaFirmada,$fechaCargaGuiaFirmadaASistema];
        for ($i = 0; $i < count($fechas) - 1; $i++) {
            $carbonFecha1 = Carbon::parse($fechas[$i]);
            $carbonFecha2 = Carbon::parse($fechas[$i + 1]);
            $diferencia = $carbonFecha1->diff($carbonFecha2);
        }
        $diasGlobalArea = $diferencia->d;
        $horasGlobalArea = $diferencia->h;
        $minutosGlobalArea = $diferencia->i;


        $html = '
        <div class="grid">';
        
            $html .= '            
   
            <div class="tiempo-global-area">' . $diasGlobalArea . 'd ' . $horasGlobalArea . 'h ' . $minutosGlobalArea . 'm </div>
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaSalidaDespacho != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Salida a despacho</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaSalidaDespacho != null ? $fechaSalidaDespacho : '') . '</div>
            
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaEntregadoConforme != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Entregado conforme a cliente</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaEntregadoConforme != null ? $fechaEntregadoConforme : '') . '</div>
        
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaCargaGuiaFirmadaASistema != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Carga GR firmada a sistema</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaCargaGuiaFirmadaASistema != null ? $fechaCargaGuiaFirmadaASistema : '') . '</div>
        
            <div class="indicador-semaforo"><i class="fas fa-circle ' . ($fechaRetornioGuiaFirmada != null ? 'verde' : 'rojo') . '"></i></div>
            <div class="actividad">Retorno de GR firmada</div>
            <div class="tiempo-finalizado-actividad">' . ($fechaRetornioGuiaFirmada != null ? $fechaRetornioGuiaFirmada : '') . '</div>
        
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>
            <div class="indicador-semaforo"></div>
            <div class="actividad">&nbsp;</div>
            <div class="tiempo-finalizado-actividad">&nbsp;</div>


            <div class="tiempo-ingreso-area">' . ($fechaRetornioGuiaFirmada != null ? $fechaRetornioGuiaFirmada : '') . '</div>
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
