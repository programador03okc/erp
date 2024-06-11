<?php

namespace App\Models\Almacen\Catalogo;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    protected $table='almacen.alm_subcat';
    public $timestamps=false;
    protected $primaryKey='id_subcategoria';
    

}
