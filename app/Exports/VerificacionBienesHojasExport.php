<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VerificacionBienesHojasExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    public function sheets(): array
    {
        $array= explode('-',$this->data);
        // dd($array);
        $sheets = [];
        foreach ($array as $key => $value) {
            $sheets[] = new VerificacionBienesExport((int)$value);
            // dd($value);
        }

        // for ($month = 1; $month <= 12; $month++) {
        //     $sheets[] = new VerificacionBienesExport($this->year, $month);
        // }

        return $sheets;
    }
}
