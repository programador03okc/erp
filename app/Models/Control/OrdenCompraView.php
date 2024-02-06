<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompraView extends Model
{
    use HasFactory;

    protected $table = 'mgcp_ordenes_compra.oc_propias_view';
    public $timestamps = false;
}
