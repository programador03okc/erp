<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\Chart as ChartChart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Worksheet\Chart;

class PresupuestoInternoSaldoExport implements FromView, WithStyles, WithColumnFormatting, WithCharts
{
    public $data;
    public $cantidad;
    public $presupuesto;

    public function __construct($data, $presupuesto)
    {
        $this->data = $data;
        $this->cantidad = sizeof($this->data) + 1;
        $this->presupuesto = json_decode($presupuesto);
    }
    public function view(): View
    {
        // dd($this->presupuesto);exit;
        foreach ($this->data as $key => $value) {
            // dd($value);exit;
        }

        return view('finanzas.export.presupuesto_interno_saldo',['data' => $this->data,]);
    }
    public function styles(Worksheet $sheet)
    {

        // $sheet->getDefaultColumnDimension()->setWidth('1',20);
        return [
            'A' => [
                'font' => [
                    'size' => 9,
                    'width'=>15
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],

            'A1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],
            ],

            // -------------------------
            'B' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'B1' => [
                'alignment' => [
                    'horizontal' => Alignment::VERTICAL_CENTER,

                ],
            ],

            'C' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'C1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'D' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'D1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'E' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'E1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'F' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'F1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            'G' => [
                'font' => [
                    'size' => 9
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'G1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function charts()
    {

        // leyenda del grafico
        $label      = [
            new DataSeriesValues('String', 'Worksheet!$C$1', null, 1),
            new DataSeriesValues('String', 'Worksheet!$D$1', null, 1),
            new DataSeriesValues('String', 'Worksheet!$E$1', null, 1)
        ];
        // eje x del grafico
        $categories = [
            // new DataSeriesValues('String', 'Worksheet!$C$1', null, 1),
            // new DataSeriesValues('String', 'Worksheet!$D$1', null, 1)
        ];
        // valores del grafico que es el eje y
        $values     = [
            new DataSeriesValues('Number', 'Worksheet!$C$2', null, 4),
            new DataSeriesValues('Number', 'Worksheet!$D$2', null, 4),
            new DataSeriesValues('Number', 'Worksheet!$E$2', null, 4)
        ];

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_STANDARD,
            range(0, \count($values) - 1), $label, $categories, $values);
        $plot   = new PlotArea(null, [$series]);

        $legend = new Legend();
        $chart  = new ChartChart('Grafica del '.$this->presupuesto->descripcion, new Title('Grafica del '.$this->presupuesto->descripcion), $legend, $plot);

        $chart->setTopLeftPosition('G2');
        $chart->setBottomRightPosition('N18');

        return $chart;
    }

}
