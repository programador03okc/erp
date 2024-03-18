<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'kardex.sopprod';
    protected $primaryKey = 'id';
    protected $fillable = ['cod_prod','cod_clasi','cod_cate','cod_subc','cod_prov','cod_espe','cod_sunat',
    'nom_prod','cod_unid','nom_unid','fac_unid','kardoc_costo','kardoc_stock','kardoc_ultingfec','kardoc_ultingcan',
    'kardoc_unico','fec_ingre','flg_descargo','tip_moneda','flg_serie','txt_observa','flg_afecto','flg_suspen',
    'apl_lista','foto','web','bi_c','impto1_c','impto2_c','impto3_c','dscto_c','bi_v','impto1_v','impto2_v','impto3_v',
    'dscto_v','cta_s_caja','cta_d_caja','cod_ubic','peso','flg_percep','por_percep','gasto','dsctocompra',
    'dsctocompra2','cod_promo','can_promo','ult_edicion','ptosbonus','bonus_moneda','bonus_importe','flg_detrac',
    'por_detrac','cod_detrac','mon_detrac','oferta','largo','ancho','area','aweb','id_product','width','height',
    'depth','weight','costo_adicional','bien_normalizado','partida_arancelaria'
    ];
    public $timestamps = false;
}
