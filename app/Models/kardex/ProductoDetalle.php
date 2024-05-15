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
        'id_salida', 'fecha_ing', 'fecha_sal','autogenerado','tipo_cambio','total'
    ];
    protected $appends = ['precio_unitario_al_tipo_cambio'];
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

    public function getPrecioUnitarioAlTipoCambioAttribute()
    {

        $precioUnitarioAlTipoCambio=0;

        $detalleProducto = ProductoDetalle::leftJoin('kardex.productos', 'productos.id', '=', 'producto_detalle.producto_id')
            ->where([['producto_detalle.id', $this->attributes['id']]])
            ->select(['productos.tipo_moneda', 'producto_detalle.precio_unitario','producto_detalle.tipo_cambio'])
            ->first();

        if($detalleProducto){
            if($detalleProducto->tipo_moneda==1 && floatval($detalleProducto->tipo_cambio) >0){
                $precioUnitarioAlTipoCambio = number_format(floatval($detalleProducto->precio_unitario) * floatval($detalleProducto->tipo_cambio),2);
            }
            if($detalleProducto->tipo_moneda==2 && floatval($detalleProducto->tipo_cambio) >0){
                $precioUnitarioAlTipoCambio =number_format(floatval($detalleProducto->precio_unitario) / floatval($detalleProducto->tipo_cambio),2);
            }
        }

        return $precioUnitarioAlTipoCambio;
    }
}
