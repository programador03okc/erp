<?php

namespace App\Models\proyectos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyCdPartida extends Model
{
    protected $table = 'proyectos.proy_cd_partida';
    protected $primaryKey ='id_partida';
    public $timestamps=false;
}
