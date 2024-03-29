<?php

namespace App\Exports;

use App\Http\Controllers\Logistica\RequerimientoController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class reporteItemsRequerimientosBienesServiciosExcel implements FromView, WithColumnFormatting, WithStyles
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
        $requerimientos = (new RequerimientoController)->listaDetalleRequerimiento($meOrAll,$idEmpresa,$idSede,$idGrupo,$idDivision,$fechaRegistroDesde,$fechaRegistroHasta,$idEstado);
        $data=[];
        foreach($requerimientos as $element){

            $data[]=[
                'id_detalle_requerimiento'=> $element->id_detalle_requerimiento,
                'prioridad'=> $element->prioridad,
                'codigo'=> $element->codigo,
                'codigo_oportunidad'=> $element->codigo_oportunidad,
                'codigo_presupuesto_old'=> $element->codigo_presupuesto_old,
                'descripcion_presupuesto_old'=> $element->descripcion_presupuesto_old,
                'codigo_presupuesto_interno'=> $element->codigo_presupuesto_interno,
                'descripcion_presupuesto_interno'=> $element->descripcion_presupuesto_interno,
                'padre_centro_costo'=> $element->padre_centro_costo,
                'padre_descripcion_centro_costo'=> $element->padre_descripcion_centro_costo,
                'centro_costo'=> $element->centro_costo,
                'descripcion_centro_costo'=> $element->descripcion_centro_costo,
                'descripcion_partida_padre'=> $element->descripcion_partida_padre,
                'partida'=> $element->partida,
                'descripcion_partida'=> $element->descripcion_partida,
                'descripcion_partida_presupuesto_interno'=>$element->descripcion_partida_presupuesto_interno,
                'codigo_sub_partida_presupuesto_interno'=>$element->codigo_sub_partida_presupuesto_interno,
                'descripcion_sub_partida_presupuesto_interno'=>$element->descripcion_sub_partida_presupuesto_interno,
                'concepto'=> str_replace("'", "", str_replace("", "", $element->concepto)),
                'codigo_producto'=> $element->codigo_producto,
                'descripcion'=> $element->descripcion_producto != null? str_replace("'", "", str_replace("", "" ,$element->descripcion_producto)): str_replace("'", "", str_replace("", "" ,$element->descripcion_detalle_requerimiento)),
                'cantidad'=> $element->cantidad,
                'precio_unitario'=> $element->precio_unitario,
                'subtotal'=> $element->subtotal,
                'simbolo_moneda'=> $element->simbolo_moneda,
                'fecha_requerimiento'=> date('d/m/Y', strtotime($element->fecha_requerimiento)),
                'tipo_cambio'=> $element->tipo_cambio,
                'tipo_requerimiento'=> $element->tipo_requerimiento,
                'empresa_razon_social'=> $element->empresa_razon_social,
                'sede'=> $element->sede,
                'grupo'=> $element->grupo,
                'division'=> $element->division,
                'descripcion_proyecto'=> $element->descripcion_proyecto,
                'observacion'=> $element->observacion,
                'motivo'=> $element->motivo,
                'fecha_registro'=> date('d/m/Y', strtotime($element->fecha_registro)),
                'hora_registro'=> date('H:i:s', strtotime($element->fecha_registro)),
                'estado_requerimiento'=> $element->estado_requerimiento,
                'codigo_orden'=> $element->codigo_orden =!null ?$element->codigo_orden :'',
                'cantidad_orden'=> $element->cantidad_orden =!null ?$element->cantidad_orden :'',
                'precio_orden'=> $element->precio_orden =!null ?$element->precio_orden :'',


            ];
        }
        return view('necesidades.reportes.listado_items_requerimientos_bienes_servicios_export', [
            'items' => $data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('Q3:Q'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('AE3:AE'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:AF')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        return [
            1    => ['font' => ['bold' => true] ],
            2    => ['font' => ['bold' => true] ],
            'A:AF'  => ['font' => ['size' => 10]]
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'AA' => NumberFormat::FORMAT_NUMBER,
            'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }

}
