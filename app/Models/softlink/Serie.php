<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use SoftDeletes;
    protected $table = 'kardex.series';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mov_id', 'cod_prod', 'serie', 'id_ingreso', 'id_salida', 'flg_kar_i',
        'flg_kar_s', 'fecha_ing', 'fecha_sal', 'proceso', 'fechavcto', 
        'unicodet_i', 'unicodet_s', 'lote'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
