<?php

namespace App\Models\Almacen;

use Illuminate\Database\Eloquent\Model;

class ProductoSap extends Model
{
    protected $table = 'almacen.producto_sap';
    protected $primaryKey = 'id_producto';
    protected $fillable = ['codigo_sap','subcategoria_id','codigo_softlink'];
    // public $timestamps = false;

    public function subcategoriaSap(){
        return $this->hasOne('App\Models\ClasificacionSap\SubCategoriaSap','id','subcategoria_id');
    }

}