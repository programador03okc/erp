<?php

namespace App\Exports;

use App\Models\Administracion\Empresas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;

class GuiasRemisionExport implements FromView, WithColumnFormatting
{
    public $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);


    }
    public function view(): View
    {
        return view('control.guias.report.guias_remision', ['data' => $this->data]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
