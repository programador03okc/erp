<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    protected $table = 'kardex.tcambio';
    protected $primaryKey = 'id';
    public $timestamps = false;

    
}
