<?php

namespace App\Models\mgcp\CuadroCosto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CcFilaMovimientoTransformacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mgcp_cuadro_costos.cc_fila_movimientos_transformacion';
    // public $timestamps = false;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function setSaleAttribute($value)
    {
        $this->attributes['sale'] = mb_strtoupper($value);
    }

    public function getSaleAttribute()
    {
        return $this->attributes['sale'] ?? '';
    }

    public function getComentarioAttribute()
    {
        return $this->attributes['comentario'] ?? '';
    }

    public function setComentarioAttribute($value)
    {
        $this->attributes['comentario'] = mb_strtoupper($value);
    }

    public function filaCuadro()
    {
        return $this->hasOne(CcAmFila::class, 'id', 'id_fila_ingresa');
    }
}
