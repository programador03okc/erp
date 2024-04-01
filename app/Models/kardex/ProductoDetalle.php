<?php

namespace App\Models\kardex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kardex.producto_detalle';
    protected $fillable = [
        'serie', 'fecha', 'precio', 'tipo_moneda','precio_unitario', 'producto_id', 'estado','disponible','id_ingreso',
        'id_salida', 'fecha_ing', 'fecha_sal'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
