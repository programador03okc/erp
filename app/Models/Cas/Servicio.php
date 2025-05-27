<?php

namespace App\Models\Cas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\StringHelper;
use App\Models\Administracion\Empresa;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cas.servicios';
    protected $fillable = [
        'codigo','fecha_reporte','id_responsable','id_salida','sede_cliente','id_contribuyente','id_contacto','usuario_final','id_tipo_falla','id_tipo_servicio','equipo_operativo','falla_reportada','conformidad','id_empresa','anio','id_medio','id_modo','id_atiende','id_tipo_garantia','numero_caso','factura','estado','fecha_registro','id_requerimiento','importe_gastado','comentarios_cierre','comentarios_cancelacion','fecha_cierre','parte_reemplazada','fecha_cancelacion','cliente','nro_orden','nombre_contacto','cargo_contacto','id_ubigeo_contacto','telefono_contacto','direccion_contacto','id_producto','id_prod_serie','serie','producto','marca','modelo','id_tipo','horario_contacto','email_contacto','cdp','fecha_documento'

    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static function nuevoCodigo($id_empresa, $fecha)
    {
        $yy = date('y');
        $yyyy = date('Y');

        $empresa = Empresa::find($id_empresa);

        $ultimoId = Servicio::orderBy('id', 'desc')
        ->value('id');

        $correlativo = StringHelper::leftZero(4, ($ultimoId + 1));

        return 'SRV-' . $empresa->codigo . '-' . $yy . $correlativo;
    }
    public static function nuevoCorrelativo($id_empresa, $fecha)
    {
        $yy = date('y');
        $yyyy = date('Y');

        $empresa = Empresa::find($id_empresa);

        $ultimoId = Servicio::orderBy('id', 'desc')
        ->value('id');

        $correlativo = StringHelper::leftZero(4, ($ultimoId + 1));

        return $yy . $correlativo;
    }
}
