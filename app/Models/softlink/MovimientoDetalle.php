<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimientoDetalle extends Model
{
    use SoftDeletes;
    protected $table = 'kardex.detmov';
    protected $primaryKey = 'id';
    protected $fillable = [
    'unico', 'mov_id', 'tipo', 'cod_docu', 'num_docu', 'fec_pedi', 'cod_auxi', 'cod_prod', 'nom_prod', 'can_pedi',
    'sal_pedi', 'can_devo', 'pre_prod', 'dscto_condi', 'dscto_categ', 'pre_neto', 'igv_inclu', 'cod_igv', 'impto1', 
    'impto2', 'imp_item', 'pre_gratis', 'descargo', 'trecord', 'cod_model', 'flg_serie', 'series', 'entrega', 'notas',
    'flg_percep', 'por_percep', 'mon_percep', 'ok_stk', 'ok_serie', 'lstock', 'no_calc', 'promo', 'seriesprod',
    'pre_anexa', 'dsctocompra', 'cod_prov', 'costo_unit', 'peso', 'gasto1', 'gasto2', 'flg_detrac', 'por_detrac', 
    'cod_detrac', 'mon_detrac', 'tipoprecio'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
