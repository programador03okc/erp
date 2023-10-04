<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrdenCompraServicioNivelItemExport implements FromView, WithColumnFormatting, WithStyles
{
    public $data;
    public function __construct(string $data)
    {
        $this->data = $data;
    }
    public function view(): View{
        $requerimientos = json_decode($this->data);
        // va
        // dd(json_decode($this->data));exit;
        $data=[];
        foreach($requerimientos as $element){
            $data[]=[
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
                'presupuesto_interno_total_partida'=>$element->presupuesto_interno_total_partida,
                'presupuesto_interno_mes_partida'=>$element->presupuesto_interno_mes_partida,
                'concepto'=> str_replace("'", "", str_replace("", "", $element->concepto)),
                'descripcion'=> $element->descripcion_producto != null? str_replace("'", "", str_replace("", "" ,$element->descripcion_producto)): str_replace("'", "", str_replace("", "" ,$element->descripcion_detalle_requerimiento)),
                'cantidad'=> $element->cantidad,
                'precio_unitario'=> $element->precio_unitario,
                'subtotal'=> $element->subtotal,
                'simbolo_moneda'=> $element->simbolo_moneda,
                'nro_orden'=> $element->nro_orden,
                'estado_orden'=> $element->estado_orden,
                'estado_pago'=> $element->estado_pago,
                'codigo_producto'=> $element->codigo_producto,
                'cantidad_orden'=> $element->cantidad_orden,
                'precio_orden'=> $element->precio_orden,
                'subtotal_orden'=> $element->subtotal_orden,
                'simbolo_moneda_orden'=> $element->simbolo_moneda_orden,
                'subtotal_orden_considera_igv'=> $element->subtotal_orden_considera_igv,
                'fecha_requerimiento'=> $element->fecha_requerimiento !=null ? date('d/m/Y', strtotime($element->fecha_requerimiento)):'',
                'tipo_cambio'=> $element->tipo_cambio,
                'tipo_requerimiento'=> $element->tipo_requerimiento,
                'empresa_razon_social'=> $element->empresa_razon_social,
                'sede'=> $element->sede,
                'grupo'=> $element->grupo,
                'division'=> $element->division,
                'descripcion_proyecto'=> $element->descripcion_proyecto,
                'observacion'=> $element->observacion,
                'motivo'=> $element->motivo,
                'fecha_registro'=> $element->fecha_registro !=null ?date('d/m/Y', strtotime($element->fecha_registro)):'',
                'hora_registro'=> $element->fecha_registro !=null ? date('H:i:s', strtotime($element->fecha_registro)):'',
                'estado_requerimiento'=> $element->estado_requerimiento
            ];
        }
        return view('tesoreria.Pagos.export.ordenes_compra_servicio_nivel_item', [
            "data"=>$data
        ]);
    }

    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D2:D'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:R')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        return [
            1    => ['font' => ['bold' => true] ],
            'A:R'  => ['font' => ['size' => 10]]
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'Q' => NumberFormat::FORMAT_TEXT,
            'R' => NumberFormat::FORMAT_TEXT
        ];
    }
}
