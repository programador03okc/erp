<?php

namespace App\Models\Control;

use App\Models\Configuracion\Usuario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observaciones extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'control.observaciones';
    protected $fillable = ['control_almacen_id', 'control_logistica_salida_id', 'observacion', 'id_usuario'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['fecha_registro'];

    public function control_almacen()
    {
        return $this->belongsTo(GuiaAlmacen::class);
    }

    public function control_logistica_salida()
    {
        return $this->belongsTo(GuiaDespacho::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario')->withTrashed();
    }

    public function getFechaRegistroAttribute() {
        return date('d/m/Y H:i A', strtotime($this->created_at));
    }
}
