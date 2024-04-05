<?php

namespace App\Models\Gerencial;

use App\Models\Configuracion\SisUsua;
use Illuminate\Database\Eloquent\Model;

class Observaciones extends Model
{
    //
    protected $table = 'cobranza.cobranzas_observaciones';
    protected $fillable = ['cobranza_id', 'usuario_id', 'descripcion', 'oc_id', 'estado', 'created_at','telefono_contacto','nombre_contacto','area_contacto_id'];
    protected $primaryKey = 'id';
    // protected $hidden = ['updated_at', 'deleted_at'];
    // public $timestamps = false;

    public function estadoDocumento()
    {
        return $this->belongsTo(EstadoDocumento::class, 'estado', 'id_estado_doc');
    }
    
    public function usuario()
    {
        return $this->belongsTo(SisUsua::class, 'usuario_id', 'id_usuario');
    }
    
    public function adjunto()
    {
        return $this->hasMany(CobranzaAdjuntoObservacion::class, 'observacion_id', 'id');
    }
    public function areaContacto()
    {
        return $this->belongsTo(AreaContacto::class, 'area_contacto_id', 'id');
    }
}
