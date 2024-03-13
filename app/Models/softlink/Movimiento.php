<?php

namespace App\Models\softlink;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimiento extends Model
{
    use SoftDeletes;
    protected $table = 'kardex.movimien';
    protected $primaryKey = 'id';
    protected $fillable = [ 'mov_id', 'tipo', 'cod_suc', 'cod_alma', 'cod_docu', 'num_docu', 'fec_docu', 'fec_entre', 
    'fec_vcto', 'flg_sitpedido', 'cod_pedi', 'num_pedi', 'cod_auxi', 'cod_trans', 'cod_vend', 'tip_mone', 'impto1', 
    'impto2', 'mon_bruto', 'mon_impto1', 'mon_impto2', 'mon_gravado', 'mon_inafec', 'mon_exonera', 'mon_gratis', 
    'mon_total', 'sal_docu', 'tot_cargo', 'tot_percep', 'tip_codicion', 'txt_observa', 'flg_kardex', 'flg_anulado', 
    'flg_referen', 'flg_percep', 'cod_user', 'programa', 'txt_nota', 'tip_cambio', 'tdflags', 'numlet', 'impdcto',
    'impanticipos', 'registro', 'tipo_canje', 'numcanje', 'cobrobco', 'ctabco', 'flg_qcont', 'fec_anul', 'audit', 
    'origen', 'tip_cont', 'tip_fact', 'contrato', 'idcontrato', 'canje_fact', 'aceptado', 'reg_conta', 'mov_pago', 
    'ndocu1', 'ndocu2', 'ndocu3', 'flg_logis', 'cod_recep', 'flg_aprueba', 'fec_aprueba', 'flg_limite', 'fecpago', 
    'imp_comi', 'ptosbonus', 'canjepedtran', 'cod_clasi', 'doc_elec', 'cod_nota', 'hashcpe', 'flg_sunat_acep', 
    'flg_sunat_anul', 'flg_sunat_mail', 'flg_sunat_webs', 'flg_sunat_cpe', 'flg_sunat_whatsapp', 'mov_id_baja', 
    'mov_id_resu_bv', 'mov_id_resu_ci', 'nroticket', 'flg_guia_traslado', 'flg_anticipo_doc', 'flg_anticipo_reg', 
    'doc_anticipo_id', 'flg_emi_itinerante', 'placa', 'tipo_documento_id' ];
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
