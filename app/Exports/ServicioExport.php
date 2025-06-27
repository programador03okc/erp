<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;

class ServicioExport implements FromView
{
    public $data;
    public function __construct(string $data)
    {
        $this->data = json_decode($data);
    }

    public function view(): View{
        // dd($this->data);
        return view('cas.export.servicios', ['data' => $this->data]);
    }
}
