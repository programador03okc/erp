<?php


namespace App\Models\Logistica;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use Debugbar;

class AdjuntosLogisticos extends Model
{

    protected $table = 'logistica.adjuntos_logisticos';
    protected $primaryKey = 'id_adjunto';
    public $timestamps = false;

    public function getFechaRegistroAttribute()
    {
        $fecha = new Carbon($this->attributes['fecha_registro']);
        return $fecha->format('d-m-Y h:m');
    }
    public function getFechaEmisionAttribute()
    {
        $fecha = new Carbon($this->attributes['fecha_emision']);
        return $fecha->format('d-m-Y h:m');
    }
    
    public function categoriaAdjunto()
    {
        return $this->belongsTo('App\Models\Logistica\CategoriaAdjunto','categoria_adjunto_id','id_categoria_adjunto');
    }
    public function moneda()
    {
        return $this->belongsTo('App\Models\Configuracion\Moneda', 'id_moneda', 'id_moneda');
    }
}
