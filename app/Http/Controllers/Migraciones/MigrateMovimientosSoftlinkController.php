<?php

namespace App\Http\Controllers\Migraciones;

use App\Http\Controllers\Controller;
use App\Models\Configuracion\LogActividad;
use App\Models\Logistica\Orden;
use App\Models\softlink\Movimiento;
use App\Models\softlink\TipoOperacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MigrateMovimientosSoftlinkController extends Controller
{
    // public static function obtenerMovimientosDeSoftlink()
    // {
    //     $data=[];
    //     $error='';
    //     $conexion='';
    //     $cantidadRegistrosMigrados=0;
    //     DB::beginTransaction();
    //     try {
    //         $softlinkMovimien = DB::connection('soft1')->table('movimien')->where('cod_docu','GR')->orderBy('fec_docu','desc')->limit(500)->get();
    //         $conexion='ok';
     
    //         if ($softlinkMovimien !== null) {

    //                 foreach ($softlinkMovimien as $value) {
    
    //                     $movimientosAGILE = Movimiento::where([
    //                         ['mov_id',$value->mov_id],
    //                         ['tipo',$value->tipo],
    //                         ['cod_suc',$value->cod_suc],
    //                         ['cod_alma',$value->cod_alma],
    //                         ['cod_docu',$value->cod_docu],
    //                         ['num_docu',$value->num_docu]
    //                     ])->orderBy('fec_docu','desc')->count();
                     
    //                     if($movimientosAGILE==0){
    //                         $nuevoMovimiento =  new Movimiento();  
    //                         $nuevoMovimiento->mov_id =str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->tipo =str_replace("'", "", str_replace("", "",htmlspecialchars($value->tipo, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_suc=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_suc, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_alma=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_alma, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_docu=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_docu, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->num_docu=str_replace("'", "", str_replace("", "",htmlspecialchars($value->num_docu, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->fec_docu=$value->fec_docu != '0000-00-00'?$value->fec_docu:null;
    //                         $nuevoMovimiento->fec_entre=$value->fec_entre != '0000-00-00'?$value->fec_entre:null;
    //                         $nuevoMovimiento->fec_vcto=$value->fec_vcto !='0000-00-00'?$value->fec_vcto:null;
    //                         $nuevoMovimiento->flg_sitpedido= str_replace("'", "", str_replace("", "",htmlspecialchars($value->flg_sitpedido, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_pedi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_pedi, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->num_pedi= $value->num_pedi;
    //                         $nuevoMovimiento->cod_auxi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_auxi, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_trans= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_trans, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_vend= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_vend, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->tip_mone= $value->tip_mone;
    //                         $nuevoMovimiento->impto1= $value->impto1;
    //                         $nuevoMovimiento->impto2= $value->impto2;
    //                         $nuevoMovimiento->mon_bruto= $value->mon_bruto;
    //                         $nuevoMovimiento->mon_impto1= $value->mon_impto1;
    //                         $nuevoMovimiento->mon_impto2= $value->mon_impto2;
    //                         $nuevoMovimiento->mon_gravado= $value->mon_gravado;
    //                         $nuevoMovimiento->mon_inafec= $value->mon_inafec;
    //                         $nuevoMovimiento->mon_exonera= $value->mon_exonera;
    //                         $nuevoMovimiento->mon_gratis= $value->mon_gratis;
    //                         $nuevoMovimiento->mon_total= $value->mon_total;
    //                         $nuevoMovimiento->sal_docu= $value->sal_docu;
    //                         $nuevoMovimiento->tot_cargo= $value->tot_cargo;
    //                         $nuevoMovimiento->tot_percep= $value->tot_percep;
    //                         $nuevoMovimiento->tip_codicion= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_codicion, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->txt_observa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->txt_observa, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_kardex= $value->flg_kardex;
    //                         $nuevoMovimiento->flg_anulado= $value->flg_anulado;
    //                         $nuevoMovimiento->flg_referen= $value->flg_referen;
    //                         $nuevoMovimiento->flg_percep= $value->flg_percep;
    //                         $nuevoMovimiento->cod_user= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_user, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->programa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->programa, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->txt_nota= str_replace("'", "", str_replace("", "",htmlspecialchars($value->txt_nota, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->tip_cambio= $value->tip_cambio;
    //                         $nuevoMovimiento->tdflags= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tdflags, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->numlet= str_replace("'", "", str_replace("", "",htmlspecialchars($value->numlet, ENT_NOQUOTES, "UTF-8"))); 
    //                         $nuevoMovimiento->impdcto= $value->impdcto;
    //                         $nuevoMovimiento->impanticipos= $value->impanticipos;
    //                         $nuevoMovimiento->registro= $value->registro;
    //                         $nuevoMovimiento->tipo_canje= $value->tipo_canje;
    //                         $nuevoMovimiento->numcanje= str_replace("'", "", str_replace("", "",htmlspecialchars($value->numcanje, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cobrobco= $value->cobrobco;
    //                         $nuevoMovimiento->ctabco= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ctabco, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_qcont= $value->flg_qcont;
    //                         $nuevoMovimiento->fec_anul= $value->fec_anul !='0000-00-00'?$value->fec_anul:null;
    //                         $nuevoMovimiento->audit= $value->audit;
    //                         $nuevoMovimiento->origen= str_replace("'", "", str_replace("", "",htmlspecialchars($value->origen, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->tip_cont= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_cont, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->tip_fact= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_fact, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->contrato= str_replace("'", "", str_replace("", "",htmlspecialchars($value->contrato, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->idcontrato= str_replace("'", "", str_replace("", "",htmlspecialchars($value->idcontrato, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->canje_fact= $value->canje_fact;
    //                         $nuevoMovimiento->aceptado= $value->aceptado;
    //                         $nuevoMovimiento->reg_conta= $value->reg_conta;
    //                         $nuevoMovimiento->mov_pago= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_pago, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->ndocu1= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu1, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->ndocu2= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu2, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->ndocu3= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu3, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_logis= $value->flg_logis;
    //                         $nuevoMovimiento->cod_recep= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_recep, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_aprueba= $value->flg_aprueba;
    //                         $nuevoMovimiento->fec_aprueba= $value->fec_aprueba !='0000-00-00 00:00:00'?$value->fec_aprueba:null;
    //                         $nuevoMovimiento->flg_limite= $value->flg_limite;
    //                         $nuevoMovimiento->fecpago= $value->fecpago !='0000-00-00'?$value->fecpago:null;
    //                         $nuevoMovimiento->imp_comi= $value->imp_comi;
    //                         $nuevoMovimiento->ptosbonus= $value->ptosbonus;
    //                         $nuevoMovimiento->canjepedtran= $value->canjepedtran;
    //                         $nuevoMovimiento->cod_clasi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_clasi, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->doc_elec= str_replace("'", "", str_replace("", "",htmlspecialchars($value->doc_elec, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->cod_nota= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_nota, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->hashcpe= str_replace("'", "", str_replace("", "",htmlspecialchars($value->hashcpe, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_sunat_acep= $value->flg_sunat_acep;
    //                         $nuevoMovimiento->flg_sunat_anul= $value->flg_sunat_anul;
    //                         $nuevoMovimiento->flg_sunat_mail= $value->flg_sunat_mail;
    //                         $nuevoMovimiento->flg_sunat_webs= $value->flg_sunat_webs;
    //                         $nuevoMovimiento->flg_sunat_cpe= str_replace("'", "", str_replace("", "",htmlspecialchars($value->flg_sunat_cpe, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_sunat_whatsapp= $value->flg_sunat_whatsapp;
    //                         $nuevoMovimiento->mov_id_baja= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_baja, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->mov_id_resu_bv= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_resu_bv, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->mov_id_resu_ci= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_resu_ci, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->nroticket= str_replace("'", "", str_replace("", "",htmlspecialchars($value->nroticket, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_guia_traslado= $value->flg_guia_traslado;
    //                         $nuevoMovimiento->flg_anticipo_doc= $value->flg_anticipo_doc;
    //                         $nuevoMovimiento->flg_anticipo_reg= $value->flg_anticipo_reg;
    //                         $nuevoMovimiento->doc_anticipo_id= str_replace("'", "", str_replace("", "",htmlspecialchars($value->doc_anticipo_id, ENT_NOQUOTES, "UTF-8")));
    //                         $nuevoMovimiento->flg_emi_itinerante= $value->flg_emi_itinerante;
    //                         $nuevoMovimiento->placa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->placa, ENT_NOQUOTES, "UTF-8")));
    //                         // $nuevoMovimiento->tipo_documento_id= MigrateMovimientosSoftlinkController::getTipoDocumentoId($value->tipo,$value->cod_docu);
    //                         $nuevoMovimiento->save();
    //                         $data[]=['movimiento'=>$nuevoMovimiento->id];
        
    //                         $cantidadRegistrosMigrados++;
    //                     }
    //                 }
                
    //         }else{
    //             $conexion='sin conexion';
    //         }
    //     DB::commit();
    //     } catch (Exception $e) {
    //         $error= $e;
    //         DB::rollBack();
    //     }

    //     return response()->json(['mensaje'=>'Se migró '.$cantidadRegistrosMigrados.' registros','data'=>$data,'error'=>$error,'conexion'=>$conexion],200);

    // }



    public static function getTipoDocumentoId($tipo,$cod_docu){
        
        
        $tipoDocumento = TipoOperacion::where([['codapl',$tipo],['codigo',$cod_docu]])->first();
        if($tipoDocumento->tipo_documento_id && $tipoDocumento->tipo_documento_id >0){
             $result = $tipoDocumento->tipo_documento_id;
        }
        return $result??null;
    }

 
}
