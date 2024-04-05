<?php

namespace App\Models\Gerencial;

use Illuminate\Database\Eloquent\Model;

class AreaContacto extends Model
{
    protected $table = 'cobranza.area_contacto';
    protected $fillable = ['nombre','estado'];
    protected $primaryKey = 'id';
}
