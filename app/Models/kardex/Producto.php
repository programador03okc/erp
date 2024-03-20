<?php

namespace App\Models\kardex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kardex.productos';
    protected $fillable = [
        'codigo_agil', 'codigo_softlink', 'descripcion', 'part_number', 'almacen', 'empresa', 'clasificacion', 'estado_kardex', 'ubicacion', 'responsable', 'fecha_registro', 'anual', 'estado', 'habilitado',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
