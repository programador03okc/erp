<?php

namespace App\Models\kardex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kardex.producto_detalle';
    protected $fillable = [
        'serie', 'fecha', 'precio', 'tipo_moneda','precio_unitario', 'producto_id', 'estado','disponible','id_ingreso',
        'id_salida', 'fecha_ing', 'fecha_sal','autogenerado','tipo_cambio'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    static function verificarSerie($serie, $generarSerie) {
        $autogenerado = false ;
        if ((trim($serie) == '' || $serie == null || trim($serie) == '-')) {
            if ($generarSerie == true) {
                $data = ProductoDetalle::count();
                $serie = 'SN-'. ($data+1);
                $autogenerado = true;
                return ["serie" => $serie, "autogenerado" => $autogenerado];
            } else {
                return ["serie" => null, "autogenerado" => $autogenerado];
            }
        } else {
            $serie = $serie;
            return ["serie" => $serie, "autogenerado"=> $autogenerado];
        }
    }
}
