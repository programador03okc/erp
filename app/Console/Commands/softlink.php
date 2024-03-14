<?php

namespace App\Console\Commands;

use App\Http\Controllers\Migraciones\MigrateMovimientosSoftlinkController;
use App\Models\Configuracion\Usuario;
use App\Models\softlink\Movimiento;
use App\Models\softlink\MovimientoDetalle;
use App\Models\softlink\Serie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class softlink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'softlink {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $text = $this->argument("param");
        // $this->info("Parametro ingresado: $text");
        // $this->withProgressBar([1,2,3],function(){
        //     sleep(1);
        // });
        switch ($text) {
            // case 'auto':
            //         MigrateMovimientosSoftlinkController::obtenerMovimientosDeSoftlink();
            //     break;
            
            case 'migrar_cabecera_movimientos':
               
                $data  = DB::connection('soft')->table('movimien')->whereIN('cod_docu',['GR','G1','G2','G4','G5','G6'])->orderBy('fec_docu','asc')->get();
                $cantidadMigrados=0;
                $bar = $this->output->createProgressBar(count($data));
                $bar->start();
                
                foreach ($data as $value) {
                    $movimientosAGILE = Movimiento::where([
                        ['mov_id',$value->mov_id],
                        ['tipo',$value->tipo],
                        ['cod_suc',$value->cod_suc],
                        ['cod_alma',$value->cod_alma],
                        ['cod_docu',$value->cod_docu],
                        ['num_docu',$value->num_docu]
                    ])->orderBy('fec_docu','asc')->count();
                 
                    if($movimientosAGILE==0){
                        $nuevoMovimiento =  new Movimiento();  
                        $nuevoMovimiento->mov_id =str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tipo =str_replace("'", "", str_replace("", "",htmlspecialchars($value->tipo, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_suc=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_suc, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_alma=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_alma, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_docu=str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_docu, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->num_docu=str_replace("'", "", str_replace("", "",htmlspecialchars($value->num_docu, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->fec_docu=$value->fec_docu != '0000-00-00'?$value->fec_docu:null;
                        $nuevoMovimiento->fec_entre=$value->fec_entre != '0000-00-00'?$value->fec_entre:null;
                        $nuevoMovimiento->fec_vcto=$value->fec_vcto !='0000-00-00'?$value->fec_vcto:null;
                        $nuevoMovimiento->flg_sitpedido= str_replace("'", "", str_replace("", "",htmlspecialchars($value->flg_sitpedido, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_pedi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_pedi, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->num_pedi= $value->num_pedi;
                        $nuevoMovimiento->cod_auxi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_auxi, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_trans= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_trans, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_vend= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_vend, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tip_mone= $value->tip_mone;
                        $nuevoMovimiento->impto1= $value->impto1;
                        $nuevoMovimiento->impto2= $value->impto2;
                        $nuevoMovimiento->mon_bruto= $value->mon_bruto;
                        $nuevoMovimiento->mon_impto1= $value->mon_impto1;
                        $nuevoMovimiento->mon_impto2= $value->mon_impto2;
                        $nuevoMovimiento->mon_gravado= $value->mon_gravado;
                        $nuevoMovimiento->mon_inafec= $value->mon_inafec;
                        $nuevoMovimiento->mon_exonera= $value->mon_exonera;
                        $nuevoMovimiento->mon_gratis= $value->mon_gratis;
                        $nuevoMovimiento->mon_total= $value->mon_total;
                        $nuevoMovimiento->sal_docu= $value->sal_docu;
                        $nuevoMovimiento->tot_cargo= $value->tot_cargo;
                        $nuevoMovimiento->tot_percep= $value->tot_percep;
                        $nuevoMovimiento->tip_codicion= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_codicion, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->txt_observa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->txt_observa, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_kardex= $value->flg_kardex;
                        $nuevoMovimiento->flg_anulado= $value->flg_anulado;
                        $nuevoMovimiento->flg_referen= $value->flg_referen;
                        $nuevoMovimiento->flg_percep= $value->flg_percep;
                        $nuevoMovimiento->cod_user= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_user, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->programa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->programa, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->txt_nota= str_replace("'", "", str_replace("", "",htmlspecialchars($value->txt_nota, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tip_cambio= $value->tip_cambio;
                        $nuevoMovimiento->tdflags= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tdflags, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->numlet= str_replace("'", "", str_replace("", "",htmlspecialchars($value->numlet, ENT_NOQUOTES, "UTF-8"))); 
                        $nuevoMovimiento->impdcto= $value->impdcto;
                        $nuevoMovimiento->impanticipos= $value->impanticipos;
                        $nuevoMovimiento->registro= $value->registro;
                        $nuevoMovimiento->tipo_canje= $value->tipo_canje;
                        $nuevoMovimiento->numcanje= str_replace("'", "", str_replace("", "",htmlspecialchars($value->numcanje, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cobrobco= $value->cobrobco;
                        $nuevoMovimiento->ctabco= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ctabco, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_qcont= $value->flg_qcont;
                        $nuevoMovimiento->fec_anul= $value->fec_anul !='0000-00-00'?$value->fec_anul:null;
                        $nuevoMovimiento->audit= $value->audit;
                        $nuevoMovimiento->origen= str_replace("'", "", str_replace("", "",htmlspecialchars($value->origen, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tip_cont= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_cont, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tip_fact= str_replace("'", "", str_replace("", "",htmlspecialchars($value->tip_fact, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->contrato= str_replace("'", "", str_replace("", "",htmlspecialchars($value->contrato, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->idcontrato= str_replace("'", "", str_replace("", "",htmlspecialchars($value->idcontrato, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->canje_fact= $value->canje_fact;
                        $nuevoMovimiento->aceptado= $value->aceptado;
                        $nuevoMovimiento->reg_conta= $value->reg_conta;
                        $nuevoMovimiento->mov_pago= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_pago, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->ndocu1= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu1, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->ndocu2= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu2, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->ndocu3= str_replace("'", "", str_replace("", "",htmlspecialchars($value->ndocu3, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_logis= $value->flg_logis;
                        $nuevoMovimiento->cod_recep= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_recep, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_aprueba= $value->flg_aprueba;
                        $nuevoMovimiento->fec_aprueba= $value->fec_aprueba !='0000-00-00 00:00:00'?$value->fec_aprueba:null;
                        $nuevoMovimiento->flg_limite= $value->flg_limite;
                        $nuevoMovimiento->fecpago= $value->fecpago !='0000-00-00'?$value->fecpago:null;
                        $nuevoMovimiento->imp_comi= $value->imp_comi;
                        $nuevoMovimiento->ptosbonus= $value->ptosbonus;
                        $nuevoMovimiento->canjepedtran= $value->canjepedtran;
                        $nuevoMovimiento->cod_clasi= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_clasi, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->doc_elec= str_replace("'", "", str_replace("", "",htmlspecialchars($value->doc_elec, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->cod_nota= str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_nota, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->hashcpe= str_replace("'", "", str_replace("", "",htmlspecialchars($value->hashcpe, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_sunat_acep= $value->flg_sunat_acep;
                        $nuevoMovimiento->flg_sunat_anul= $value->flg_sunat_anul;
                        $nuevoMovimiento->flg_sunat_mail= $value->flg_sunat_mail;
                        $nuevoMovimiento->flg_sunat_webs= $value->flg_sunat_webs;
                        $nuevoMovimiento->flg_sunat_cpe= str_replace("'", "", str_replace("", "",htmlspecialchars($value->flg_sunat_cpe, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_sunat_whatsapp= $value->flg_sunat_whatsapp;
                        $nuevoMovimiento->mov_id_baja= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_baja, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->mov_id_resu_bv= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_resu_bv, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->mov_id_resu_ci= str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id_resu_ci, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->nroticket= str_replace("'", "", str_replace("", "",htmlspecialchars($value->nroticket, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_guia_traslado= $value->flg_guia_traslado;
                        $nuevoMovimiento->flg_anticipo_doc= $value->flg_anticipo_doc;
                        $nuevoMovimiento->flg_anticipo_reg= $value->flg_anticipo_reg;
                        $nuevoMovimiento->doc_anticipo_id= str_replace("'", "", str_replace("", "",htmlspecialchars($value->doc_anticipo_id, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->flg_emi_itinerante= $value->flg_emi_itinerante;
                        $nuevoMovimiento->placa= str_replace("'", "", str_replace("", "",htmlspecialchars($value->placa, ENT_NOQUOTES, "UTF-8")));
                        $nuevoMovimiento->tipo_documento_id= MigrateMovimientosSoftlinkController::getTipoDocumentoId($value->tipo,$value->cod_docu);
                        $nuevoMovimiento->save();
                        $cantidadMigrados++;
    
                    }
                    $bar->advance();
                }
                $bar->finish();
                $this->info("\nCantidad de registros de movimiento migrados: $cantidadMigrados");
                
                break;


            case 'migrar_detalle_movimientos':
                
                $cantidadMigrados=0;
                $aux = DB::connection('soft')->table('movimien')->whereIN('cod_docu',['GR','G1','G2','G4','G5','G6'])->orderBy('fec_docu','asc')->get();
                $cantidadAux =count($aux);
                $bar = $this->output->createProgressBar($cantidadAux);
                $bar->start();
                foreach ($aux as $key => $value) {
                    $data  = DB::connection('soft')->table('detmov')->where('mov_id',$value->mov_id)->orderBy('fec_pedi','asc')->get();
                    
                    foreach ($data as $value) {
                        // $movimientosDetalleAGILE = MovimientoDetalle::where([
                        //     ['mov_id',$value->mov_id],
                        //     ['tipo',$value->tipo],
                        //     ['cod_docu',$value->cod_docu],
                        //     ['num_docu',$value->num_docu]
                        // ])->orderBy('fec_pedi','asc')->count();
                     
                        // if($movimientosDetalleAGILE==0){
                            $nuevoMovimientoDetalle =  new MovimientoDetalle(); 
                            $nuevoMovimientoDetalle->unico = str_replace("'", "", str_replace("", "",htmlspecialchars($value->unico, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->mov_id = str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->tipo = $value->tipo;
                            $nuevoMovimientoDetalle->cod_docu = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_docu, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->num_docu =  str_replace("'", "", str_replace("", "",htmlspecialchars($value->num_docu, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->fec_pedi = $value->fec_pedi !='0000-00-00'?$value->fec_pedi:null;
                            $nuevoMovimientoDetalle->cod_auxi = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_auxi, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->cod_prod = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_prod, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->nom_prod =  str_replace("'", "", str_replace("", "",htmlspecialchars($value->nom_prod, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->can_pedi = $value->can_pedi;
                            $nuevoMovimientoDetalle->sal_pedi = $value->sal_pedi;
                            $nuevoMovimientoDetalle->can_devo = $value->can_devo;
                            $nuevoMovimientoDetalle->pre_prod = $value->pre_prod;
                            $nuevoMovimientoDetalle->dscto_condi = $value->dscto_condi;
                            $nuevoMovimientoDetalle->dscto_categ = $value->dscto_categ;
                            $nuevoMovimientoDetalle->pre_neto = $value->pre_neto;
                            $nuevoMovimientoDetalle->igv_inclu = $value->igv_inclu;
                            $nuevoMovimientoDetalle->cod_igv = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_igv, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->impto1 = $value->impto1;
                            $nuevoMovimientoDetalle->impto2 = $value->impto2;
                            $nuevoMovimientoDetalle->imp_item = $value->imp_item;
                            $nuevoMovimientoDetalle->pre_gratis = $value->pre_gratis;
                            $nuevoMovimientoDetalle->descargo = str_replace("'", "", str_replace("", "",htmlspecialchars($value->descargo, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->trecord = str_replace("'", "", str_replace("", "",htmlspecialchars($value->trecord, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->cod_model = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_model, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->flg_serie = $value->flg_serie;
                            $nuevoMovimientoDetalle->series = str_replace("'", "", str_replace("", "",htmlspecialchars($value->series, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->entrega = $value->entrega;
                            $nuevoMovimientoDetalle->notas = str_replace("'", "", str_replace("", "",htmlspecialchars($value->notas, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->flg_percep = $value->flg_percep;
                            $nuevoMovimientoDetalle->por_percep = $value->por_percep;
                            $nuevoMovimientoDetalle->mon_percep = $value->mon_percep;
                            $nuevoMovimientoDetalle->ok_stk = $value->ok_stk;
                            $nuevoMovimientoDetalle->ok_serie = $value->ok_serie;
                            $nuevoMovimientoDetalle->lstock = $value->lStock;
                            $nuevoMovimientoDetalle->no_calc = $value->no_calc;
                            $nuevoMovimientoDetalle->promo = $value->promo;
                            $nuevoMovimientoDetalle->seriesprod =str_replace("'", "", str_replace("", "",htmlspecialchars($value->seriesprod, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->pre_anexa = $value->pre_anexa;
                            $nuevoMovimientoDetalle->dsctocompra = $value->dsctocompra;
                            $nuevoMovimientoDetalle->cod_prov = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_prov, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->costo_unit = $value->costo_unit;
                            $nuevoMovimientoDetalle->peso = $value->peso;
                            $nuevoMovimientoDetalle->gasto1 = $value->gasto1;
                            $nuevoMovimientoDetalle->gasto2 = $value->gasto2;
                            $nuevoMovimientoDetalle->flg_detrac = $value->flg_detrac;
                            $nuevoMovimientoDetalle->por_detrac = $value->por_detrac;
                            $nuevoMovimientoDetalle->cod_detrac =str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_detrac, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->mon_detrac = $value->mon_detrac;
                            $nuevoMovimientoDetalle->tipoprecio = str_replace("'", "", str_replace("", "",htmlspecialchars($value->tipoprecio, ENT_NOQUOTES, "UTF-8")));
                            $nuevoMovimientoDetalle->save();
                            $cantidadMigrados++;
        
                        // }
                    }
                    $bar->advance();
                }

                $bar->finish();
                
                $this->info("\nLa cabecera tiene un total de $cantidadAux registros y se agrego al detalle de movimiento $cantidadMigrados registros");

                break;

            case 'migrar_series':
                
                $data  = DB::connection('soft')->table('series')->orderBy('fecha_ing','asc')->get();
                $cantidadMigrados=0;
                $bar = $this->output->createProgressBar(count($data));
                $bar->start();
                
                foreach ($data as $value) {
                    // $seriesAGILE = Serie::where([
                    //     ['mov_id',$value->mov_id],
                    //     ['cod_prod',$value->cod_prod],
                    //     ['serie',$value->serie]
                    // ])->orderBy('fecha_ing','asc')->count();
                 
                    // if($seriesAGILE==0){
                        $nuevaSerie =  new Serie(); 
                        $nuevaSerie->mov_id = str_replace("'", "", str_replace("", "",htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->cod_prod = str_replace("'", "", str_replace("", "",htmlspecialchars($value->cod_prod, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->serie = str_replace("'", "", str_replace("", "",htmlspecialchars($value->serie, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->id_ingreso = str_replace("'", "", str_replace("", "",htmlspecialchars($value->id_ingreso, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->id_salida = str_replace("'", "", str_replace("", "",htmlspecialchars($value->id_salida, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->flg_kar_i = $value->flg_kar_i;
                        $nuevaSerie->flg_kar_s = $value->flg_kar_s;
                        $nuevaSerie->fecha_ing = $value->fecha_ing !='0000-00-00'?$value->fecha_ing:null;
                        $nuevaSerie->fecha_sal = $value->fecha_sal !='0000-00-00'?$value->fecha_sal:null;
                        $nuevaSerie->proceso = $value->proceso;
                        $nuevaSerie->fechavcto =  $value->fechavcto !='0000-00-00'?$value->fechavcto:null;
                        $nuevaSerie->unicodet_i = str_replace("'", "", str_replace("", "",htmlspecialchars($value->unicodet_i, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->unicodet_s = str_replace("'", "", str_replace("", "",htmlspecialchars($value->unicodet_s, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->lote = str_replace("'", "", str_replace("", "",htmlspecialchars($value->lote, ENT_NOQUOTES, "UTF-8")));
                        $nuevaSerie->save();
                        $cantidadMigrados++;
    
                    // }
                    $bar->advance();
                }

                $bar->finish();
                
                $this->info("\nCantidad de registros de series migrados: $cantidadMigrados");

                break;


            default:
                $this->info("No existe opción para ejecutar");
                break;
        }

  

        // return 0;
        return Command::SUCCESS;
    }
}
