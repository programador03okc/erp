<?php

namespace App\Exports;

use App\Http\Controllers\Logistica\ProveedoresController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteListaProveedoresExcel implements FromView, WithColumnFormatting, WithStyles
{


    public function __construct()
    {
    }

    public function view(): View
    {

        $proveedor =  (new ProveedoresController)->getDataListadoProveedores();
        $data = [];

        foreach ($proveedor as $element) {

            $data[] = [
                'tipo_documento' => $element->contribuyente->id_doc_identidad == 1 ? 'DNI' : ($element->contribuyente->id_doc_identidad == 2 ? 'RUC' : ''),
                'nro_documento' => $element->contribuyente->nro_documento ?? '',
                'razon_social' => str_replace("'", "", str_replace("", "", $element->contribuyente->razon_social)),
                'direccion' => $element->contribuyente->direccion_fiscal ?? '',
                'ubigeo' => $element->contribuyente->ubigeo_completo ?? '',
                'pais' => $element->contribuyente->pais->descripcion ?? '',
                'telefono' => $element->contribuyente->telefono,
                'fecha_registro' => date('d/m/Y', strtotime($element->fecha_registro)),
                'estado' => $element->contribuyente->estado == 1 ? 'Habilitado' : 'Anulado'

            ];
        }

        return view('logistica.gestion_logistica.proveedores.reportes.listado_provedores_export', [
            'data' => $data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('Q3:Q' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('AE3:AE' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:AF')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
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
