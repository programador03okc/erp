<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuiaDespacho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'control.control_logistica_salida';
    protected $fillable = [
        'control_almacen_id', 'transportista', 'guia_transportista', 'factura_transportista', 'fecha_guia_transportista', 'flete', 'adjunto_guia',
        'cargo_guia', 'adjunto_guia_sellada', 'envio_adjunto_guia', 'envio_adjunto_guia_sellada', 'id_usuario',
        'contribuyente_id','guia_transportista_serie','guia_transportista_numero','fecha_emision_guia','importe_flete','codigo_envio','credito','guia_venta_serie','guia_venta_numero','fecha_despacho_real',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
