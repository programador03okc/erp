<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class VerificacionBienesExport implements FromView, WithStyles, WithColumnWidths
{
    public $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }
    public function view(): View
    {
        // dd($this->data->id_detalle_orden);
        // dd($this->data);
        // $imagen = asset('./images/logo_okc.png');
        $imagen = 'images/logo_okc.png';
        return view('logistica.gestion_logistica.compras.ordenes.export.verificación_bienes',[
            'data'              => $this->data,
            'producto'          => $this->data->descripcion_adicional,
            'cantidad'          => (string)$this->data->cantidad,
            'proveedor'         => $this->data->proveedor,
            'oc'                => $this->data->codigo,
            'fecha_recepcion'   => $this->data->fecha_emision,
            'fecha_autorizar' => date("d/m/Y", strtotime($this->data->fecha_autorizar)) ,
            "logo_okc"=>$imagen
        ]);
    }
    public function styles(Worksheet $sheet)
    {
        // cabecera
        $sheet->mergeCells('A1:A4');
        $sheet->mergeCells('B1:E1');
        $sheet->mergeCells('B2:E2');
        $sheet->mergeCells('B3:E4');
        $sheet->mergeCells('F1:H1');
        $sheet->mergeCells('F2:H2');
        $sheet->mergeCells('F3:H3');
        $sheet->mergeCells('F4:H4');

        // DATOS DEL PRODUCTO
        $sheet->mergeCells('A6:I6');
        $sheet->mergeCells('B8:F8');
        $sheet->mergeCells('H8:I8');//FIRMA
        $sheet->mergeCells('B9:I9');
        $sheet->mergeCells('B10:I10');
        $sheet->mergeCells('B11:I11');
        $sheet->mergeCells('B12:I12');
        $sheet->mergeCells('B13:I13');
        // VERIFICACION
        $sheet->mergeCells('A15:I15');
        $sheet->mergeCells('G16:I16');
        $sheet->mergeCells('G17:I17');
        $sheet->mergeCells('G18:I18');
        $sheet->mergeCells('G19:I19');
        $sheet->mergeCells('G20:I20');
        $sheet->mergeCells('G21:I21');
        $sheet->mergeCells('G22:I22');
        // CONDICIONES DE ALMACENAMIENTO
        $sheet->mergeCells('A24:I24');
        $sheet->mergeCells('G26:I26');
        $sheet->mergeCells('G27:I27');
        $sheet->mergeCells('G25:I25');
        $sheet->mergeCells('G28:I28');
        $sheet->mergeCells('A30:D30');
        $sheet->mergeCells('E30:I30');
        $sheet->mergeCells('A31:I31');
        // OBSERVACIONES
        $sheet->mergeCells('A33:I33');
        $sheet->mergeCells('A34:I34');
        $sheet->mergeCells('A35:I35');
        $sheet->mergeCells('A36:I36');
        $sheet->mergeCells('A37:I37');
        // EVALUACION
        $sheet->mergeCells('A39:I39');
        return [
            //cabecera
            'B1:E4' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F1:G4' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'I1:I4' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            // DATOS DE PRODUCTO
            'A6:I6' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'A8:A13' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B8:E8' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'G8' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B9:I9' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B10:I10' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B11:I11' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B12:I12' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B13:I13' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            // VERIFICACION
            'A15:I15' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'A17:A22' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B17:B22' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C17:C22' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E17:E22' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F17:F22' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'G16:I16' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            // CONDICIONES DE ALMACENAMIENTO
            'A24:I24' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'A26:A28' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B26:B28' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C26:C28' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E26:E28' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F26:F28' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'G25:I25' => [
                'font' => [
                    // 'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'A30:D30' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            // OBSERVACIONES
            'A33:I33' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            // EVALUACION
            'A39:I39' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B41' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C41' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E41' => [
                'font' => [
                    'bold' => true,
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    // 'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F41' => [
                'font' => [
                    'size' => 9,
                    'width'=>10
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
        ];
    }
    public function columnWidths(): array
    {

        return [
            'C' => 3,
            'F' => 3,
            'B' => 10,
            // 'A' => 0.01,
            // 'C' => 30,
            // 'D' => 25,
            // 'E' => 30,
        ];
    }
    // public function columnFormats(): array
    // {
    //     return [
    //         'E' => String::FORMAT_NUMBER_COMMA_SEPARATED1,
    //     ];
    // }

}
