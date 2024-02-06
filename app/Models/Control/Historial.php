<?php

namespace App\Models\Control;

use App\Models\Configuracion\Usuario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'control.historial';
    protected $fillable = ['id_control', 'descripcion', 'id_usuario'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['fecha_registro'];

    public function control()
    {
        return $this->belongsTo(GuiaAlmacen::class, 'id_control', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario')->withTrashed();
    }

    public function getFechaRegistroAttribute() {
        return date('d/m/Y H:i A', strtotime($this->created_at));
    }
}
