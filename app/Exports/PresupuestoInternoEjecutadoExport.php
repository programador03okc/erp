<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStyles;
class PresupuestoInternoEjecutadoExport implements FromView, WithColumnFormatting
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        foreach ($this->data['requerimiento_saldo'] as $key => $value) {
            $value->fecha_registro_req = ($value->fecha_registro_req!=null? date("d/m/Y", strtotime($value->fecha_registro_req)):'') ;
            $value->fecha_registro = ($value->fecha_registro!=null ? date("d/m/Y", strtotime($value->fecha_registro)) : '') ;
        }
        foreach ($this->data['orden_logistico'] as $key => $value) {
            $value->fecha_registro = ($value->fecha_registro!=null ?date("d/m/Y", strtotime($value->fecha_registro)):'') ;
            $value->fecha_autorizacion =($value->fecha_autorizacion!=null? date("d/m/Y", strtotime($value->fecha_autorizacion)):'' );
        }

        return view('finanzas.export.presupuesto_interno_monto_ejecutado',['data' => $this->data,]);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,

            // 'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }

}
