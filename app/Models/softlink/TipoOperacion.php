<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Model;

class TipoOperacion extends Model
{
    protected $table = 'kardex.tablope';
    protected $primaryKey = 'id';
    protected $fillable = ['unico','codapl','codigo','descrip','indsubcta','moneda','detigv','caligv','verigv',
    'tipond','correl','ostock','astock','serie','series','kardex','docref','copiar','correc','anular','borrar',
    'formato','cola','ctacte','status','dc','docitem','impto1','impto2','apl_impsto','val_texto','prn_lin',
    'ask_kardex','doctrans','gen_asiento','cta_bi','cta_impto1','cta_impto2','cta_impto3','cta_dscto','bi_dh',
    'impto1_dh','impto2_dh','impto3_dh','dscto_dh','total_dh','opebco','modobco','fechvcto','ruc','di','interes',
    'ingbco','cta_s_caja','cta_d_caja','cta_s_bco','cta_d_bco','cta_c_let','cta_p_let','resu_doc','add_impto',
    'flg_serie','contrato','genaviso','multiref','comi','dni_monto','dni_monto2','dni_mone','ldireccion','auxi_exige',
    'doc1','doc2','doc3','flg_percep','aplica','tipo_aplica','refagrupa','cod_suc','flg_peding','flg_prof',
    'flg_pre_cero','flg_num_ant','flg_can_cero','flg_electronico','flg_placa','flg_contingencia','flg_separa'
    ];

    public $timestamps = false;



}
