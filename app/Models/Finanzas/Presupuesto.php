<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;
    protected $table = 'finanzas.presup';
    protected $primaryKey = 'id_presup';

    public $timestamps = false;
}
