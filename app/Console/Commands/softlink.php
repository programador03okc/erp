<?php

namespace App\Console\Commands;

use App\Http\Controllers\Migraciones\MigrateMovimientosSoftlinkController;
use App\Models\Configuracion\Usuario;
use App\Models\kardex\Producto as KardexProducto;
use App\Models\kardex\ProductoDetalle;
use App\Models\softlink\Movimiento;
use App\Models\softlink\MovimientoDetalle;
use App\Models\softlink\Producto;
use App\Models\softlink\Serie;
use App\Models\softlink\TipoCambio;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\StringHelper;
use App\Http\Controllers\Migraciones\MigrateProductoSoftlinkController;

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

            case 'migrar_tipo_cambio':
                $data  = DB::connection('softtest')->table('tcambio')->where('flg_migracion', 0)->orderBy('dfecha', 'asc')->get();
                $cantidadMigrados = 0;
                $bar = $this->output->createProgressBar(count($data));
                $bar->start();
                foreach ($data as $value) {
                    if ($value->dfecha != '0000-00-00' && $value->dfecha != null) {
                        $tipoCambio = new TipoCambio();
                        $tipoCambio->dfecha = $value->dfecha;
                        $tipoCambio->cambio = $value->cambio;
                        $tipoCambio->cambio2 = $value->cambio2;
                        $tipoCambio->cambio3 = $value->cambio3;
                        $tipoCambio->save();
                        $cantidadMigrados++;
                    }

                    DB::connection('softtest')
                        ->table('tcambio')
                        ->where('dfecha', $value->dfecha)
                        ->update(
                            ['flg_migracion' => 1]
                        );

                    $bar->advance();
                }
                $bar->finish();
                $this->info("\nSe migró $cantidadMigrados registros de tipo de cambio de softlink a la tabla local kardex.tcambio");

                break;
            case 'migrar_cabecera_movimientos':

                $data  = DB::connection('softtest')->table('movimien')->whereIN('cod_docu', ['GR', 'G1', 'G2', 'G4', 'G5', 'G6'])->where('flg_migracion', 0)->orderBy('fec_docu', 'asc')->get();
                $cantidadMigrados = 0;
                $bar = $this->output->createProgressBar(count($data));
                $bar->start();

                foreach ($data as $value) {
                    $movimientosAGILE = Movimiento::where([
                        ['mov_id', $value->mov_id],
                        ['tipo', $value->tipo],
                        ['cod_suc', $value->cod_suc],
                        ['cod_alma', $value->cod_alma],
                        ['cod_docu', $value->cod_docu],
                        ['num_docu', $value->num_docu]
                    ])->orderBy('fec_docu', 'asc')->count();

                    if ($movimientosAGILE == 0) {
                        $nuevoMovimiento =  new Movimiento();
                        $nuevoMovimiento->mov_id = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tipo = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tipo, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_suc = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_suc, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_alma = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_alma, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_docu = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_docu, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->num_docu = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->num_docu, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->fec_docu = $value->fec_docu != '0000-00-00' ? $value->fec_docu : null;
                        $nuevoMovimiento->fec_entre = $value->fec_entre != '0000-00-00' ? $value->fec_entre : null;
                        $nuevoMovimiento->fec_vcto = $value->fec_vcto != '0000-00-00' ? $value->fec_vcto : null;
                        $nuevoMovimiento->flg_sitpedido = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->flg_sitpedido, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_pedi = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_pedi, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->num_pedi = $value->num_pedi;
                        $nuevoMovimiento->cod_auxi = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_auxi, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_trans = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_trans, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_vend = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_vend, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tip_mone = $value->tip_mone;
                        $nuevoMovimiento->impto1 = $value->impto1;
                        $nuevoMovimiento->impto2 = $value->impto2;
                        $nuevoMovimiento->mon_bruto = $value->mon_bruto;
                        $nuevoMovimiento->mon_impto1 = $value->mon_impto1;
                        $nuevoMovimiento->mon_impto2 = $value->mon_impto2;
                        $nuevoMovimiento->mon_gravado = $value->mon_gravado;
                        $nuevoMovimiento->mon_inafec = $value->mon_inafec;
                        $nuevoMovimiento->mon_exonera = $value->mon_exonera;
                        $nuevoMovimiento->mon_gratis = $value->mon_gratis;
                        $nuevoMovimiento->mon_total = $value->mon_total;
                        $nuevoMovimiento->sal_docu = $value->sal_docu;
                        $nuevoMovimiento->tot_cargo = $value->tot_cargo;
                        $nuevoMovimiento->tot_percep = $value->tot_percep;
                        $nuevoMovimiento->tip_codicion = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tip_codicion, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->txt_observa = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->txt_observa, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_kardex = $value->flg_kardex;
                        $nuevoMovimiento->flg_anulado = $value->flg_anulado;
                        $nuevoMovimiento->flg_referen = $value->flg_referen;
                        $nuevoMovimiento->flg_percep = $value->flg_percep;
                        $nuevoMovimiento->cod_user = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_user, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->programa = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->programa, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->txt_nota = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->txt_nota, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tip_cambio = $value->tip_cambio;
                        $nuevoMovimiento->tdflags = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tdflags, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->numlet = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->numlet, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->impdcto = $value->impdcto;
                        $nuevoMovimiento->impanticipos = $value->impanticipos;
                        $nuevoMovimiento->registro = $value->registro;
                        $nuevoMovimiento->tipo_canje = $value->tipo_canje;
                        $nuevoMovimiento->numcanje = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->numcanje, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cobrobco = $value->cobrobco;
                        $nuevoMovimiento->ctabco = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->ctabco, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_qcont = $value->flg_qcont;
                        $nuevoMovimiento->fec_anul = $value->fec_anul != '0000-00-00' ? $value->fec_anul : null;
                        $nuevoMovimiento->audit = $value->audit;
                        $nuevoMovimiento->origen = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->origen, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tip_cont = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tip_cont, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tip_fact = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tip_fact, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->contrato = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->contrato, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->idcontrato = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->idcontrato, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->canje_fact = $value->canje_fact;
                        $nuevoMovimiento->aceptado = $value->aceptado;
                        $nuevoMovimiento->reg_conta = $value->reg_conta;
                        $nuevoMovimiento->mov_pago = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_pago, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->ndocu1 = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->ndocu1, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->ndocu2 = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->ndocu2, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->ndocu3 = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->ndocu3, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_logis = $value->flg_logis;
                        $nuevoMovimiento->cod_recep = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_recep, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_aprueba = $value->flg_aprueba;
                        $nuevoMovimiento->fec_aprueba = $value->fec_aprueba != '0000-00-00 00:00:00' ? $value->fec_aprueba : null;
                        $nuevoMovimiento->flg_limite = $value->flg_limite;
                        $nuevoMovimiento->fecpago = $value->fecpago != '0000-00-00' ? $value->fecpago : null;
                        $nuevoMovimiento->imp_comi = $value->imp_comi;
                        $nuevoMovimiento->ptosbonus = $value->ptosbonus;
                        $nuevoMovimiento->canjepedtran = $value->canjepedtran;
                        $nuevoMovimiento->cod_clasi = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_clasi, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->doc_elec = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->doc_elec, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->cod_nota = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_nota, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->hashcpe = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->hashcpe, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_sunat_acep = $value->flg_sunat_acep;
                        $nuevoMovimiento->flg_sunat_anul = $value->flg_sunat_anul;
                        $nuevoMovimiento->flg_sunat_mail = $value->flg_sunat_mail;
                        $nuevoMovimiento->flg_sunat_webs = $value->flg_sunat_webs;
                        $nuevoMovimiento->flg_sunat_cpe = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->flg_sunat_cpe, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_sunat_whatsapp = $value->flg_sunat_whatsapp;
                        $nuevoMovimiento->mov_id_baja = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id_baja, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->mov_id_resu_bv = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id_resu_bv, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->mov_id_resu_ci = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id_resu_ci, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->nroticket = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->nroticket, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_guia_traslado = $value->flg_guia_traslado;
                        $nuevoMovimiento->flg_anticipo_doc = $value->flg_anticipo_doc;
                        $nuevoMovimiento->flg_anticipo_reg = $value->flg_anticipo_reg;
                        $nuevoMovimiento->doc_anticipo_id = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->doc_anticipo_id, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->flg_emi_itinerante = $value->flg_emi_itinerante;
                        $nuevoMovimiento->placa = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->placa, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimiento->tipo_documento_id = MigrateMovimientosSoftlinkController::getTipoDocumentoId($value->tipo, $value->cod_docu);
                        $nuevoMovimiento->estado_migracion = 1;
                        $nuevoMovimiento->save();
                        $cantidadMigrados++;

                        DB::connection('softtest')
                            ->table('movimien')
                            ->where('mov_id', $value->mov_id)
                            ->update(
                                ['flg_migracion' => 1]
                            );
                    }
                    $bar->advance();
                }
                $bar->finish();
                $this->info("\nSe migró $cantidadMigrados registros cabecera de movimiento de softlink a la tabla local kardex.movimien");

                break;


            case 'migrar_detalle_movimientos':

                $cantidadMigrados = 0;
                $aux = DB::connection('softtest')->table('movimien')->whereIN('cod_docu', ['GR', 'G1', 'G2', 'G4', 'G5', 'G6'])->where('flg_migracion', 1)->orderBy('fec_docu', 'asc')->get();
                $cantidadAux = count($aux);
                $bar = $this->output->createProgressBar($cantidadAux);
                $bar->start();
                foreach ($aux as $key => $value) {
                    $data  = DB::connection('softtest')->table('detmov')->where('mov_id', $value->mov_id)->orderBy('fec_pedi', 'asc')->get();

                    foreach ($data as $value) {
                        // $movimientosDetalleAGILE = MovimientoDetalle::where([
                        //     ['mov_id',$value->mov_id],
                        //     ['tipo',$value->tipo],
                        //     ['cod_docu',$value->cod_docu],
                        //     ['num_docu',$value->num_docu]
                        // ])->orderBy('fec_pedi','asc')->count();

                        // if($movimientosDetalleAGILE==0){
                        $nuevoMovimientoDetalle =  new MovimientoDetalle();
                        $nuevoMovimientoDetalle->unico = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->unico, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->mov_id = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->tipo = $value->tipo;
                        $nuevoMovimientoDetalle->cod_docu = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_docu, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->num_docu = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->num_docu, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->fec_pedi = $value->fec_pedi != '0000-00-00' ? $value->fec_pedi : null;
                        $nuevoMovimientoDetalle->cod_auxi = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_auxi, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->cod_prod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_prod, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->nom_prod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->nom_prod, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->can_pedi = $value->can_pedi;
                        $nuevoMovimientoDetalle->sal_pedi = $value->sal_pedi;
                        $nuevoMovimientoDetalle->can_devo = $value->can_devo;
                        $nuevoMovimientoDetalle->pre_prod = $value->pre_prod;
                        $nuevoMovimientoDetalle->dscto_condi = $value->dscto_condi;
                        $nuevoMovimientoDetalle->dscto_categ = $value->dscto_categ;
                        $nuevoMovimientoDetalle->pre_neto = $value->pre_neto;
                        $nuevoMovimientoDetalle->igv_inclu = $value->igv_inclu;
                        $nuevoMovimientoDetalle->cod_igv = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_igv, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->impto1 = $value->impto1;
                        $nuevoMovimientoDetalle->impto2 = $value->impto2;
                        $nuevoMovimientoDetalle->imp_item = $value->imp_item;
                        $nuevoMovimientoDetalle->pre_gratis = $value->pre_gratis;
                        $nuevoMovimientoDetalle->descargo = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->descargo, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->trecord = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->trecord, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->cod_model = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_model, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->flg_serie = $value->flg_serie;
                        $nuevoMovimientoDetalle->series = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->series, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->entrega = $value->entrega;
                        $nuevoMovimientoDetalle->notas = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->notas, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->flg_percep = $value->flg_percep;
                        $nuevoMovimientoDetalle->por_percep = $value->por_percep;
                        $nuevoMovimientoDetalle->mon_percep = $value->mon_percep;
                        $nuevoMovimientoDetalle->ok_stk = $value->ok_stk;
                        $nuevoMovimientoDetalle->ok_serie = $value->ok_serie;
                        $nuevoMovimientoDetalle->lstock = $value->lStock;
                        $nuevoMovimientoDetalle->no_calc = $value->no_calc;
                        $nuevoMovimientoDetalle->promo = $value->promo;
                        $nuevoMovimientoDetalle->seriesprod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->seriesprod, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->pre_anexa = $value->pre_anexa;
                        $nuevoMovimientoDetalle->dsctocompra = $value->dsctocompra;
                        $nuevoMovimientoDetalle->cod_prov = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_prov, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->costo_unit = $value->costo_unit;
                        $nuevoMovimientoDetalle->peso = $value->peso;
                        $nuevoMovimientoDetalle->gasto1 = $value->gasto1;
                        $nuevoMovimientoDetalle->gasto2 = $value->gasto2;
                        $nuevoMovimientoDetalle->flg_detrac = $value->flg_detrac;
                        $nuevoMovimientoDetalle->por_detrac = $value->por_detrac;
                        $nuevoMovimientoDetalle->cod_detrac = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_detrac, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->mon_detrac = $value->mon_detrac;
                        $nuevoMovimientoDetalle->tipoprecio = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->tipoprecio, ENT_NOQUOTES, "UTF-8"))));
                        $nuevoMovimientoDetalle->estado_migracion = 1;
                        $nuevoMovimientoDetalle->save();
                        $cantidadMigrados++;

                        DB::connection('softtest')
                            ->table('detmov')
                            ->where('unico', $value->unico)
                            ->update(
                                ['flg_migracion' => 1]
                            );

                        // }
                    }
                    $bar->advance();
                }

                $bar->finish();

                $this->info("\nSe migró $cantidadMigrados registros de detalle de movimiento de softlink a la tabla local kardex.detmov");


                break;

            case 'migrar_series':

                $data  = DB::connection('softtest')->table('series')->where('flg_migracion', 0)->orderBy('fecha_ing', 'asc')->get();
                $cantidadMigrados = 0;
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
                    $nuevaSerie->mov_id = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->mov_id, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->cod_prod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_prod, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->serie = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->serie, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->id_ingreso = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->id_ingreso, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->id_salida = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->id_salida, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->flg_kar_i = $value->flg_kar_i;
                    $nuevaSerie->flg_kar_s = $value->flg_kar_s;
                    $nuevaSerie->fecha_ing = $value->fecha_ing != '0000-00-00' ? $value->fecha_ing : null;
                    $nuevaSerie->fecha_sal = $value->fecha_sal != '0000-00-00' ? $value->fecha_sal : null;
                    $nuevaSerie->proceso = $value->proceso;
                    $nuevaSerie->fechavcto =  $value->fechavcto != '0000-00-00' ? $value->fechavcto : null;
                    $nuevaSerie->unicodet_i = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->unicodet_i, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->unicodet_s = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->unicodet_s, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->lote = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->lote, ENT_NOQUOTES, "UTF-8"))));
                    $nuevaSerie->estado_migracion = 1;
                    $nuevaSerie->save();
                    $cantidadMigrados++;


                    DB::connection('softtest')
                        ->table('series')
                        ->where('mov_id', $value->mov_id)
                        ->update(
                            ['flg_migracion' => 1]
                        );

                    // }
                    $bar->advance();
                }

                $bar->finish();

                $this->info("\nSe migró $cantidadMigrados registros de series de softlink a la tabla local kardex.series");

                break;

            case 'migrar_productos':

                $data  = DB::connection('softtest')->table('sopprod')->orderBy('fec_ingre', 'asc')->get();
                $cantidadMigrados = 0;
                $bar = $this->output->createProgressBar(count($data));
                $bar->start();

                foreach ($data as $value) {

                    $nuevoProducto =  new Producto();
                    $nuevoProducto->cod_prod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_prod, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_clasi = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_clasi, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_cate = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_cate, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_subc = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_subc, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_prov = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_prov, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_espe = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_espe, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_sunat = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_sunat, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->nom_prod = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->nom_prod, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_unid = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_unid, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->nom_unid = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->nom_unid, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->fac_unid = $value->fac_unid;
                    $nuevoProducto->kardoc_costo = $value->kardoc_costo;
                    $nuevoProducto->kardoc_stock = $value->kardoc_stock;
                    $nuevoProducto->kardoc_ultingfec = $value->kardoc_ultingfec != '0000-00-00' ? $value->kardoc_ultingfec : null;
                    $nuevoProducto->kardoc_ultingcan = $value->kardoc_ultingcan;
                    $nuevoProducto->kardoc_unico = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->kardoc_unico, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->fec_ingre = $value->fec_ingre != '0000-00-00' ? $value->fec_ingre : null;
                    $nuevoProducto->flg_descargo = $value->flg_descargo;
                    $nuevoProducto->tip_moneda = $value->tip_moneda;
                    $nuevoProducto->flg_serie = $value->flg_serie;
                    $nuevoProducto->txt_observa = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->txt_observa, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->flg_afecto = $value->flg_afecto;
                    $nuevoProducto->flg_suspen = $value->flg_suspen;
                    $nuevoProducto->apl_lista = $value->apl_lista;
                    $nuevoProducto->foto = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->foto, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->web = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->web, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->bi_c = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->bi_c, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto1_c = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto1_c, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto2_c = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto2_c, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto3_c = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto3_c, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->dscto_c = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->dscto_c, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->bi_v = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->bi_v, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto1_v = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto1_v, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto2_v = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto2_v, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->impto3_v = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->impto3_v, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->dscto_v = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->dscto_v, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cta_s_caja = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cta_s_caja, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cta_d_caja = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cta_d_caja, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->cod_ubic = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_ubic, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->peso = $value->peso;
                    $nuevoProducto->flg_percep = $value->flg_percep;
                    $nuevoProducto->por_percep = $value->por_percep;
                    $nuevoProducto->gasto = $value->gasto;
                    $nuevoProducto->dsctocompra = $value->dsctocompra;
                    $nuevoProducto->dsctocompra2 = $value->dsctocompra2;
                    $nuevoProducto->cod_promo = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_promo, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->can_promo = $value->can_promo;
                    $nuevoProducto->ptosbonus = $value->ptosbonus;
                    $nuevoProducto->bonus_moneda = $value->bonus_moneda;
                    $nuevoProducto->bonus_importe = $value->bonus_importe;
                    $nuevoProducto->flg_detrac = $value->flg_detrac;
                    $nuevoProducto->por_detrac = $value->por_detrac;
                    $nuevoProducto->cod_detrac = trim(str_replace("'", "", str_replace("", "", htmlspecialchars($value->cod_detrac, ENT_NOQUOTES, "UTF-8"))));
                    $nuevoProducto->mon_detrac = $value->mon_detrac;
                    $nuevoProducto->oferta = $value->oferta;
                    $nuevoProducto->largo = $value->largo;
                    $nuevoProducto->ancho = $value->ancho;
                    $nuevoProducto->area = $value->area;
                    $nuevoProducto->aweb = $value->aweb;
                    $nuevoProducto->id_product = $value->id_product;
                    $nuevoProducto->width = $value->width;
                    $nuevoProducto->height = $value->height;
                    $nuevoProducto->depth = $value->depth;
                    $nuevoProducto->weight = $value->weight;
                    $nuevoProducto->costo_adicional = $value->costo_adicional;
                    $nuevoProducto->bien_normalizado = $value->bien_normalizado;
                    $nuevoProducto->partida_arancelaria = $value->partida_arancelaria;
                    $nuevoProducto->save();
                    $cantidadMigrados++;

                    $bar->advance();
                }

                $bar->finish();

                $this->info("\nSe migró $cantidadMigrados registros de productos de softlink a la tabla local kardex.sopprod");

                break;
            case 'carga_productos_y_detalle':
                $listaCodProdAgregado = [];
                $cantidadProductosAgregados = 0;
                $cantidadDetalleProductosAgregados = 0;


                $to = Carbon::parse();
                $from = Carbon::parse('2023-01-01')->toDateTimeString();
                $movimiento = Movimiento::whereIN('cod_docu', ['GR', 'G1', 'G2', 'G4', 'G5', 'G6'])->where('estado_migracion', 1)->whereBetween('fec_docu', [$from, $to])->orderBy('fec_docu', 'asc')->get();

                $cantidadAux = count($movimiento);
                $bar = $this->output->createProgressBar($cantidadAux);
                $bar->start();

                foreach ($movimiento as $key => $movValue) {
                    $movimientoDetalle = MovimientoDetalle::select('detmov.mov_id', 'detmov.unico', 'detmov.cod_prod', 'detmov.nom_prod', 'sopprod.cod_espe', 'sopprod.nom_unid', 'sopprod.tip_moneda')
                        ->leftJoin('kardex.sopprod', 'sopprod.cod_prod', '=', 'detmov.cod_prod')
                        ->where([['detmov.mov_id', $movValue->mov_id], ['estado_migracion', 1]])->orderBy('fec_pedi', 'asc')->get();

                    foreach ($movimientoDetalle as $key => $movDetValue) {
                        if (!in_array($movDetValue->unico, $listaCodProdAgregado)) {

                            $listaCodProdAgregado[] = $movDetValue->unico;

                            $nuevoProducto = new KardexProducto();
                            $nuevoProducto->codigo_softlink = trim($movDetValue->cod_prod);
                            $nuevoProducto->descripcion = trim($movDetValue->nom_prod);
                            $nuevoProducto->part_number = $movDetValue->cod_espe ? trim($movDetValue->cod_espe) : null;
                            $nuevoProducto->unidad_medida = $movDetValue->nom_unid ? trim($movDetValue->nom_unid) : null;
                            $nuevoProducto->tipo_moneda = $movDetValue->tip_moneda ? trim($movDetValue->tip_moneda) : null;
                            $nuevoProducto->save();
                            $cantidadProductosAgregados++;

                            $actualiarDetalleMovimiento = MovimientoDetalle::where('unico', $movDetValue->unico)->first();
                            if ($actualiarDetalleMovimiento != null) {
                                $actualiarDetalleMovimiento->estado_migracion = 2; // procesado
                                $actualiarDetalleMovimiento->save();
                            }

                            $series = Serie::where('cod_prod', trim($movDetValue->cod_prod))->get();

                            foreach ($series as $serie) {
                                if ($series && intval($serie->id) > 0) {
                                    // $nuevoProductoDetalle = new ProductoDetalle();
                                    $nuevoProductoDetalle = ProductoDetalle::firstOrNew(['serie' => trim($serie->serie)]);
                                    if ($movValue->tipo == 2) {
                                        if ($serie->fecha_sal == null) {
                                            $estado = 1;
                                        } else {
                                            $estado = 0;
                                        }
                                    } else {
                                        $estado = 1;
                                    }
                                    $verificarSerie = ProductoDetalle::verificarSerie(trim($serie->serie), null);
                                    $nuevoProductoDetalle->serie = trim($verificarSerie['serie']);
                                    $nuevoProductoDetalle->fecha = $serie->fechavcto;
                                    $nuevoProductoDetalle->producto_id = $nuevoProducto->id;
                                    $nuevoProductoDetalle->id_ingreso =  trim($serie->id_ingreso) == "" ? null : trim($serie->id_ingreso);
                                    $nuevoProductoDetalle->fecha_ing =  trim($serie->fecha_ing) == "" ? null : trim($serie->fecha_ing);
                                    $nuevoProductoDetalle->id_salida = trim($serie->id_salida) == "" ? null : trim($serie->id_salida);
                                    $nuevoProductoDetalle->fecha_sal = trim($serie->fecha_sal) == "" ? null : trim($serie->fecha_sal);
                                    $nuevoProductoDetalle->estado = 1;
                                    $nuevoProductoDetalle->disponible = $estado;
                                    $nuevoProductoDetalle->autogenerado = $verificarSerie['autogenerado'];
                                    $nuevoProductoDetalle->save();
                                    $cantidadDetalleProductosAgregados++;
                                }
                            }
                        }
                    }

                    $actualiarMovimiento = Movimiento::where('mov_id', $movValue->mov_id)->first();
                    $actualiarMovimiento->estado_migracion = 2; // procesado
                    $actualiarMovimiento->save();

                    $bar->advance();
                }

                $bar->finish();

                $this->info("\nSe insertó $cantidadProductosAgregados registros en la tabla local kardex.productos y $cantidadDetalleProductosAgregados registros en la tabla kardex.producto_detalle");


                break;

            case 'migra_productos_con_nueva_categoria':
              
                $productos = DB::table('almacen.alm_prod')
                ->select(
                    'alm_prod.*',
                    'alm_und_medida.abreviatura',
                    'alm_clasif.descripcion as clasificacion',
                    'alm_tp_prod.descripcion as categoria',
                    'alm_subcat.descripcion as subcategoria'
                    // 'alm_tp_prod.id_clasificacion'
                )
                ->leftjoin('almacen.alm_clasif', 'alm_clasif.id_clasificacion', '=', 'alm_prod.id_clasif')
                ->leftjoin('almacen.alm_tp_prod', 'alm_tp_prod.id_tipo_producto', '=', 'alm_prod.id_categoria')
                // ->leftjoin('almacen.alm_cat_prod', 'alm_cat_prod.id_categoria', '=', 'alm_prod.id_categoria')
                ->leftjoin('almacen.alm_subcat', 'alm_subcat.id_subcategoria', '=', 'alm_prod.id_subcategoria')
                ->leftjoin('almacen.alm_und_medida', 'alm_und_medida.id_unidad_medida', '=', 'alm_prod.id_unidad_medida')
                ->whereNotNull('alm_prod.id_grupo_sap')
                ->get();

                $cantidadAux = count($productos);
                $bar = $this->output->createProgressBar($cantidadAux);
                $bar->start();
               

            //Verifica si esxiste el producto
            $cantidadProductosAgregados=0;
            foreach ($productos as $producto) {
                $prod = null;
                if (!empty(trim($producto->part_number))) { //if ($producto->part_number !== null && $producto->part_number !== '') {
                    // return [$producto];exit;
                    $prod = DB::connection('softtest')->table('sopprod')
                        ->select('cod_prod')
                        ->join('sopsub2', 'sopsub2.cod_sub2', '=', 'sopprod.cod_subc')
                        ->where([
                            ['sopprod.cod_espe', '=', trim($producto->part_number)],
                            ['sopsub2.nom_sub2', '=', $producto->subcategoria]
                        ])
                        ->first();

                } else if ($producto->descripcion !== null && $producto->descripcion !== '') {
                    $prod = DB::connection('softtest')->table('sopprod')
                        ->select('cod_prod')
                        ->join('sopsub2', 'sopsub2.cod_sub2', '=', 'sopprod.cod_subc')
                        ->where([
                            ['nom_prod', '=', trim($producto->descripcion)],
                            ['sopsub2.nom_sub2', '=', $producto->subcategoria]
                        ])
                        ->first();
                }

                $cod_prod = null;
                //Si existe copia el cod_prod
                if ($prod !== null) {
                    $cod_prod = $prod->cod_prod;
                    $cod_clasi = (new MigrateProductoSoftlinkController)->obtenerClasificacion($producto->clasificacion);
                    $cod_cate = (new MigrateProductoSoftlinkController)->obtenerCategoria($producto->categoria, $producto->id_categoria);
                    $cod_subc = (new MigrateProductoSoftlinkController)->obtenerSubCategoria($producto->subcategoria, $producto->id_subcategoria);

                    $cod_unid = (new MigrateProductoSoftlinkController)->obtenerUnidadMedida($producto->abreviatura);
                    // return $cod_cate;exit;
                    DB::connection('softtest')
                    ->table('sopprod')
                    ->where('cod_prod',$cod_prod)
                    ->update(
                        [
                            'cod_prod' => $cod_prod,
                            'cod_clasi' => $cod_clasi,
                            'cod_cate' => $cod_cate,
                            'cod_subc' => $cod_subc,
                            'cod_espe' => trim($producto->part_number),
                            'cod_sunat' => '',
                            'nom_prod' => trim($producto->descripcion),
                            'cod_unid' => $cod_unid,
                            'nom_unid' => trim($producto->abreviatura),
                            'ult_edicion' => date('Y-m-d H:i:s'),
                            'tip_moneda' => $producto->id_moneda,
                            'flg_serie' => ($producto->series ? 1 : 0), //Revisar
                            'txt_observa' => ($producto->notas !== null ? $producto->notas : '')
                        ]
                    );
                } //Si no existe, genera el producto
                else {
                    //obtiene el sgte codigo
                    $ultimo = DB::connection('softtest')->table('sopprod')
                        ->select('cod_prod')
                        ->where([['cod_prod', '!=', 'TEXTO']])
                        ->orderBy('cod_prod', 'desc')
                        ->first();

                    $cod_prod = StringHelper::leftZero(6, (intval($ultimo->cod_prod) + 1));

                    $cod_clasi = (new MigrateProductoSoftlinkController)->obtenerClasificacion($producto->clasificacion);

                    $cod_cate = (new MigrateProductoSoftlinkController)->obtenerCategoria($producto->categoria, $producto->id_categoria);

                    $cod_subc = (new MigrateProductoSoftlinkController)->obtenerSubCategoria($producto->subcategoria, $producto->id_subcategoria);

                    $cod_unid = (new MigrateProductoSoftlinkController)->obtenerUnidadMedida($producto->abreviatura);

                    DB::connection('softtest')->table('sopprod')->insert(
                        [
                            'cod_prod' => $cod_prod,
                            'cod_clasi' => $cod_clasi,
                            'cod_cate' => $cod_cate,
                            'cod_subc' => $cod_subc,
                            'cod_prov' => '',
                            'cod_espe' => (trim($producto->part_number) !='') ?trim($producto->part_number):'',
                            'cod_sunat' => '',
                            'nom_prod' => trim($producto->descripcion),
                            'cod_unid' => $cod_unid,
                            'nom_unid' => trim($producto->abreviatura),
                            'fac_unid' => 1,
                            'kardoc_costo' => 0,
                            'kardoc_stock' => 0,
                            'kardoc_ultingfec' => '0000-00-00',
                            'kardoc_ultingcan' => 0,
                            'kardoc_unico' => '',
                            'fec_ingre' => date('Y-m-d'),
                            'flg_descargo' => 1,
                            'tip_moneda' => $producto->id_moneda,
                            'flg_serie' => ($producto->series ? 1 : 0), //Revisar
                            'txt_observa' => ($producto->notas !== null ? $producto->notas : ''),
                            'flg_afecto' => 1,
                            'flg_suspen' => 0,
                            'apl_lista' => 3,
                            'foto' => '',
                            'aweb' => '',
                            'bi_c' => '',
                            'impto1_c' => '',
                            'impto2_c' => '',
                            'impto3_c' => '',
                            'dscto_c' => '',
                            'bi_v' => '',
                            'impto1_v' => '',
                            'impto2_v' => '',
                            'impto3_v' => '',
                            'dscto_v' => '',
                            'cta_s_caja' => 0,
                            'cta_d_caja' => '',
                            'cod_ubic' => '',
                            'peso' => 0,
                            'flg_percep' => 0,
                            'por_percep' => 0,
                            'gasto' => 0,
                            'dsctocompra' => 0,
                            'dsctocompra2' => 0,
                            'cod_promo' => '',
                            'can_promo' => 0,
                            'ult_edicion' => date('Y-m-d H:i:s'),
                            'ptosbonus' => 0,
                            'bonus_moneda' => 0,
                            'bonus_importe' => 0,
                            'flg_detrac' => 0,
                            'por_detrac' => 0,
                            'cod_detrac' => '',
                            'mon_detrac' => 0,
                            'largo' => 0,
                            'ancho' => 0,
                            'area' => 0,
                            'aweb' => 0,
                            'id_product' => 0,
                            'width' => 0,
                            'height' => 0,
                            'depth' => 0,
                            'weight' => 0,
                            'costo_adicional' => 0
                        ]
                    );

                    $sucursales = DB::connection('softtest')->table('sucursal')->get();

                    foreach ($sucursales as $suc) {
                        $prod = DB::connection('softtest')->table('precios')
                            ->where([['cod_prod', '=', $cod_prod], ['cod_suc', '=', $suc->cod_suc]])
                            ->first();

                        if ($prod == null) {
                            DB::connection('softtest')->table('precios')->insert(
                                [
                                    'cod_prod' => $cod_prod,
                                    'cod_suc' => $suc->cod_suc,
                                    'en_lista' => 1,
                                    'lsupendido' => 0,
                                    'fecha_susp' => '0000-00-00',
                                    'precio_venta' => 0,
                                    'precio_mayor' => 0,
                                    'precio_tres' => 0,
                                    'precio_cuatro' => 0,
                                    'precio_cinco' => 0,
                                    'precio_seis' => 0,
                                    'precio_costo' => 0,
                                    'precio_inver' => 0,
                                    'precio_refer' => 0,
                                    'porct_1' => 0,
                                    'porct_2' => 0,
                                    'porct_3' => 0,
                                    'porct_4' => 0,
                                    'porct_5' => 0,
                                    'porct_6' => 0,
                                    'costo_ultimo' => 0
                                ]
                            );
                        }
                    }

                    $almacenes = DB::connection('softtest')->table('almacen')->get();

                    foreach ($almacenes as $alm) {
                        $stock = DB::connection('softtest')->table('stocks')
                            ->where([['cod_suc', '=', $alm->cod_suc], ['cod_alma', '=', $alm->cod_alma], ['cod_prod', '=', $cod_prod]])
                            ->first();

                        if ($stock == null) {
                            DB::connection('softtest')->table('stocks')->insert(
                                [
                                    'cod_suc' => $alm->cod_suc,
                                    'cod_alma' => $alm->cod_alma,
                                    'cod_prod' => $cod_prod,
                                    'stock_act' => 0,
                                    'stock_ing' => 0,
                                    'stock_ped' => 0,
                                    'stock_min' => 0,
                                    'stock_max' => 0,
                                    'cod_ubic' => '',
                                ]
                            );
                        }
                    }
                }
                DB::table('almacen.alm_prod')
                ->where('id_producto', $producto->id_producto)
                ->update(['cod_softlink' => $cod_prod]);
                
                $bar->advance();

                $cantidadProductosAgregados++;
            
            }

            $bar->finish();

            $this->info("\nSe insertó $cantidadProductosAgregados registros");


            break;

            default:
                $this->info("No existe opción para ejecutar");
                break;
        }



        // return 0;
        return Command::SUCCESS;
    }
}
