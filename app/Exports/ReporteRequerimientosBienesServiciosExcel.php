<?php

namespace App\Exports;

use App\Http\Controllers\Logistica\RequerimientoController;
use App\Models\Almacen\DetalleRequerimiento;
use App\Models\Logistica\Orden;
use App\Models\Logistica\OrdenCompraDetalle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Carbon\Carbon;

class reporteRequerimientosBienesServiciosExcel implements FromView,ShouldAutoSize
{


    public function __construct(string $meOrAll, string $idEmpresa,string $idSede,string $idGrupo,string $idDivision, string $fechaRegistroDesde, string $fechaRegistroHasta, string $idEstado)
    {
        $this->meOrAll = $meOrAll;
        $this->idEmpresa = $idEmpresa;
        $this->idSede = $idSede;
        $this->idGrupo = $idGrupo;
        $this->idDivision = $idDivision;
        $this->fechaRegistroDesde = $fechaRegistroDesde;
        $this->fechaRegistroHasta = $fechaRegistroHasta;
        $this->idEstado = $idEstado;
    }

    public function view(): View{
        $meOrAll= $this->meOrAll;
        $idEmpresa= $this->idEmpresa;
        $idSede = $this->idSede;
        $idGrupo = $this->idGrupo;
        $idDivision = $this->idDivision;
        $fechaRegistroDesde = $this->fechaRegistroDesde;
        $fechaRegistroHasta = $this->fechaRegistroHasta;
        $idEstado = $this->idEstado;
        $requerimientos = (new RequerimientoController)->obtenerRequerimientosElaborados($meOrAll,$idEmpresa,$idSede,$idGrupo,$idDivision,$fechaRegistroDesde,$fechaRegistroHasta,$idEstado)->orderBy('fecha_registro','desc')->get();

        $data=[];
        foreach($requerimientos as $element){

            $estados_jefatura = [1,3,7,12];

            $estados_etapas = '-';
            if(!in_array($element->estado,$estados_jefatura)){
                $estados_etapas = 'Jefatura: aprobado';
            }

            $detalle = DetalleRequerimiento::where('alm_det_req.id_requerimiento',$element->id_requerimiento)
            ->join('logistica.log_det_ord_compra','log_det_ord_compra.id_detalle_requerimiento','=','alm_det_req.id_detalle_requerimiento')
            ->get();
            if(sizeof($detalle)>0){
                $estados_etapas = 'Logística: con orden';
            }

            if(sizeof($element->detalle)>0){
                // dd($element);
                foreach($element->detalle as $key=>$value){
                    // dd($value);
                    $orden_detalle = OrdenCompraDetalle::where('id_detalle_requerimiento',$value->id_detalle_requerimiento)->first();

                    if($orden_detalle){

                        $orden = Orden::find($orden_detalle->id_orden_compra);

                        $contador_estado = 0;
                        if(in_array($orden->estado_pago,[5, 6, 8, 9, 10])){
                            $contador_estado++;
                        }
                        if($contador_estado>0){
                            $estados_etapas = 'Logística: enviado a pago';
                        }

                        if($orden->estado_pago == 5 || (in_array($orden->estado_pago,[6,9,10]))){
                            $estados_etapas = 'Tesorería: pago autorizado';
                        }

                        if(in_array($orden->estado_pago,[9,10])){
                            $estados_etapas = 'Tesorería: pagado / pagado con saldo';
                        }

                        if($orden->estado_pago == 6){
                            $estados_etapas = 'Tesorería: pagado';
                        }

                    }
                }
            }


            // echo ([$element->estado,$element->detalle]);exit;
            // echo $element->estado;exit;
            $data[]=[
                'priori'                => str_replace("'", "", str_replace("", "", $element->priori)),
                'codigo'                => str_replace("'", "", str_replace("", "", $element->codigo)),
                'codigo_oportunidad'    => str_replace("'", "", str_replace("", "", $element->codigo_oportunidad)),
                'concepto'              => str_replace("'", "", str_replace("", "", $element->concepto)),
                'fecha_registro'        => str_replace("'", "", str_replace("", "", $element->fecha_registro)),
                'fecha_entrega'         => str_replace("'", "", str_replace("", "", $element->fecha_entrega)),
                'tipo_requerimiento'    => str_replace("'", "", str_replace("", "", $element->tipo_requerimiento)),
                'razon_social'          => str_replace("'", "", str_replace("", "", $element->razon_social)),
                'sede'                  => str_replace("'", "", str_replace("", "", $element->sede)),
                'grupo'                 => str_replace("'", "", str_replace("", "", $element->grupo)),
                'division'              => str_replace("'", "", str_replace("", "", $element->division)),
                'descripcion_proyecto'  => str_replace("'", "", str_replace("", "", ($element->descripcion_proyecto?$element->descripcion_proyecto:'-'))),
                'simbolo_moneda'        => str_replace("'", "", str_replace("", "", $element->simbolo_moneda)),
                'monto_total'           => str_replace("'", "", str_replace("", "", number_format($element->monto_total,2))),
                'observacion'           => str_replace("'", "", str_replace("", "", ($element->observacion?$element->observacion:'-'))),
                'nombre_usuario'        => str_replace("'", "", str_replace("", "", $element->nombre_usuario)),
                'nombre_solicitado_por' => str_replace("'", "", str_replace("", "", $element->nombre_solicitado_por)),
                'ultimo_aprobador'      => str_replace("'", "", str_replace("", "", ($element->ultimo_aprobador?$element->ultimo_aprobador:'-'))),
                'observacion'           => str_replace("'", "", str_replace("", "", $element->observacion)),
                'estado_doc'            => str_replace("'", "", str_replace("", "", $element->nombre_estado)),
                "etapas"=>$estados_etapas

            ];
        }
        return view('necesidades.reportes.view_requerimientos_bienes_servicios_export', [
            'requerimientos' => $data
        ]);
    }

}
