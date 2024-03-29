<?php

namespace App\Models\Contabilidad;

use App\Models\Configuracion\Distrito;
use Illuminate\Database\Eloquent\Model;

class Contribuyente extends Model
{
        protected $table = 'contabilidad.adm_contri';
        protected $fillable = [
            'id_tipo_contribuyente',
            'id_doc_identidad',
            'nro_documento',
            'razon_social',
            'telefono',
            'celular',
            'direccion_fisca',
            'ubigeo',
            'id_pais',
            'estado',
            'fecha_registro',
            'email',
            'transportista',
            'id_rubro',
            'id_cliente_gerencial_old',
            'id_empresa_gerencial_old'
        ];
        protected $primaryKey = 'id_contribuyente';
        protected $appends = ['ubigeo_completo'];

        public $timestamps = false;

        public function getUbigeoCompletoAttribute(){
            $dis= $this->attributes['ubigeo'];
            if($dis>0){
                $ubigeo=Distrito::with('provincia.departamento')->where('id_dis',$dis)->first();
                $dist= $ubigeo->descripcion;
                $prov= $ubigeo->provincia->descripcion;
                $dpto= $ubigeo->provincia->departamento->descripcion;
                return ($dist.' - '.$prov.' - '.$dpto);
            }else{
                return '';
            }

        }

        public function tipoDocumentoIdentidad(){
            return $this->hasOne('App\Models\Contabilidad\TipoDocumentoIdentidad','id_doc_identidad','id_doc_identidad')->withDefault([
                'id_doc_identidad' => null,
                'descripcion' => null,
                'longitud' => null,
                'estado' => null
            ]);
        }
        public function tipoContribuyente()
        {
            return $this->hasOne('App\Models\Contabilidad\TipoContribuyente','id_tipo_contribuyente','id_tipo_contribuyente')->withDefault([
                'id_tipo_contribuyente' => null,
                'descripcion' => null,
                'estado' => null,
                'cod_sunat' => null
            ]);
        }
        public function pais()
        {
            return $this->hasOne('App\Models\Configuracion\Pais','id_pais','id_pais')->withDefault([
                'id_pais' => null,
                'descripcion' => null,
                'abreviatura' => null,
                'estado' => null
            ]);
        }
        public function distrito()
        {
            return $this->hasOne('App\Models\Configuracion\Distrito','id_dis','ubigeo')->withDefault([
                'id_dis' => null,
                'descripcion' => null,
                'estado' => null
            ]);
        }
        public function proveedor(){
            return $this->hasOne('App\Models\Logistica\Proveedor','id_contribuyente','id_contribuyente');
        }
        public function cuentaContribuyente(){
            return $this->hasMany('App\Models\Contabilidad\CuentaContribuyente','id_contribuyente','id_contribuyente');
        }
        public function contactoContribuyente(){
            return $this->hasMany('App\Models\Contabilidad\ContactoContribuyente','id_contribuyente','id_contribuyente');
        }
}
