<?php

namespace App\Models\Gerencial;

use Illuminate\Database\Eloquent\Model;

class CobranzaAdjuntoObservacion extends Model
{
    protected $table = 'cobranza.cobranza_adjunto_observacion';
    protected $fillable = ['cobranza_id','archivo', 'usuario_id', 'estado', 'created_at'];
    protected $primaryKey = 'id';
}
