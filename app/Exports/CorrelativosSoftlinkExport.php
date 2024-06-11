<?php

namespace App\Exports;

use App\Http\Controllers\Migraciones\MigrateOrdenSoftLinkController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CorrelativosSoftlinkExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {
       
    }
    
    public function collection()
    {
        $dataAgrupada=[];
        // $data =Orden::reporteListaOrdenes();
        $data =(new MigrateOrdenSoftLinkController)->getCorrelativoDocumentoSoftlink();
        foreach ($data['orden_compra'] as $key => $value) {
            $dataAgrupada[]=[
                'tipo_documento'=> 'Orden compra',
                'id'=> $value['id'],
                'nombre'=> $value['nombre'],
                'cod_docu'=> $value['cod_docu'],
                'ultimo_correlativo_soft1'=> intval( $value['ultimo_correlativo_soft1']) >0 ?$value['ultimo_correlativo_soft1']:'0',
                'next_num_docu_soft1'=> $value['next_num_docu_soft1']

            ];
        }
        foreach ($data['orden_servicio'] as $key => $value) {
            $dataAgrupada[]=[
                'tipo_documento'=> 'Orden servicio',
                'id'=> $value['id'],
                'nombre'=> $value['nombre'],
                'cod_docu'=> $value['cod_docu'],
                'ultimo_correlativo_soft1'=> intval( $value['ultimo_correlativo_soft1']) >0 ?$value['ultimo_correlativo_soft1']:'0',
                'next_num_docu_soft1'=> $value['next_num_docu_soft1']

            ];
        }
        foreach ($data['orden_importacion'] as $key => $value) {
            $dataAgrupada[]=[
                'tipo_documento'=> 'Orden importación',
                'id'=> $value['id'],
                'nombre'=> $value['nombre'],
                'cod_docu'=> $value['cod_docu'],
                'ultimo_correlativo_soft1'=> intval( $value['ultimo_correlativo_soft1']) >0 ?$value['ultimo_correlativo_soft1']:'0',
                'next_num_docu_soft1'=> $value['next_num_docu_soft1']

            ];
        }
        return collect($dataAgrupada);


    }
    public function headings(): array
    {
        return [
            "Tipo documento", 
            "Id empresa", 
            "Código empresa", 
            "Código documento", 
            "Nro Correlativo actual",
            "codigo de documento siguiente de generar"
        ];
    }
}
