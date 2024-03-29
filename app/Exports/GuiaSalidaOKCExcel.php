<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;

class GuiaSalidaOKCExcel implements FromView, WithEvents
{
    public $guia;
    public $detalle;

    public function __construct($guia, $detalle)
    {
        $this->guia = $guia;
        $this->detalle = $detalle;
    }

    public function view(): View
    {

        return view('almacen.export.guia_salida_okc_export', [
            'guia' => $this->guia,
            'detalle' => $this->detalle,
        ]);
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:U29')->getFont()->setSize(10);
                $event->sheet->getDelegate()->getStyle('A1:U29')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(4);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(4);
                $event->sheet->getStyle('H16:H20')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('J7:J8')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('D17')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(5)->setRowHeight(11);

                $style = array(
                    'alignment' => array(
                        'wrap' => true,
                        'font-size' => 10
                    )
                );
                $event->sheet->getStyle("H16:H20")->applyFromArray($style);
                $event->sheet->getStyle("J7:J8")->applyFromArray($style);
                $event->sheet->getDelegate()->getStyle('E7')->getAlignment()->setVertical("TOP");
                $event->sheet->getDelegate()->getStyle('N8')->getAlignment()->setVertical("TOP");
            },



        ];
    }
}
