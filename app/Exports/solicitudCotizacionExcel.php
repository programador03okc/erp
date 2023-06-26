<?php

namespace App\Exports;

use App\Http\Controllers\Logistica\RequerimientoController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class solicitudCotizacionExcel implements FromView, WithStyles, WithColumnFormatting
{

    public $idRequerimiento;

    public function __construct($idRequerimiento)
    {
        $this->idRequerimiento = $idRequerimiento;
    }

    public function view(): View{
        $requerimiento = (new RequerimientoController)->mostrarRequerimiento($this->idRequerimiento, 0);

        return view('necesidades.reportes.view_solicitud_cotizacion_export', [
            'requerimiento' => $requerimiento
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B6:B'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:H')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:P')->getFont()->setSize(10);
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(18);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

}
