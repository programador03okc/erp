
// ============== View =========================
var vardataTables = funcDatatables();
var simboloMoneda = '';
var tablaListaRequerimientosParaVincular;
var $tablaHistorialOrdenesElaboradas;
var $tablaListaCatalogoProductos;
var detalleOrdenList = [];
var iTableCounter = 1;
var oInnerTable;
var actionPage = null;
class OrdenView {
    constructor(ordenCtrl) {
        this.ordenCtrl = ordenCtrl;
        this.cabeceraOrdenObject = {};
    }

    getTipoCambioCompra() {

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        let fechaHoy = now.toISOString().slice(0, 10)

        this.ordenCtrl.getTipoCambioCompra(fechaHoy).then(function (tipoCambioCompra) {
            document.querySelector("input[name='tipo_cambio_compra']").value = tipoCambioCompra;
        }).catch(function (err) {
            console.log(err)
        })
    }

    limpiarTabla(idElement) {
        let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
        if (nodeTbody != null) {
            while (nodeTbody.children.length > 0) {
                nodeTbody.removeChild(nodeTbody.lastChild);
            }

        }
    }

    init() {


        // variable session storage: reqCheckedList -> continene un array de los id de requerimiento seleccionados en lista pendiente 
        // variable session storage: tipoOrden -> puede tener los valor: COMPRA , SERVICIO
        // variable session storage: action -> puede tener los valor: register, edition, historial (para mostrar una orden) 

        this.cargarContenedorOrden();


        var reqTrueList = JSON.parse(sessionStorage.getItem('reqCheckedList'));

        var tipoOrden = sessionStorage.getItem('tipoOrden');
        if (reqTrueList != undefined && reqTrueList != null && (reqTrueList.length > 0)) {
            this.obtenerRequerimiento(reqTrueList, tipoOrden);
            sessionStorage.removeItem('reqCheckedList');
            sessionStorage.removeItem('tipoOrden');
        }
        var idOrden = sessionStorage.getItem('idOrden');
        actionPage = sessionStorage.getItem('action');
        // sessionStorage.removeItem('action');

        if (idOrden > 0) {
            this.mostrarOrden(idOrden);
            sessionStorage.removeItem('idOrden');
            sessionStorage.removeItem('action');
        }

        $('#form-orden').on("click", "button.crearNuevaOrden", (e) => {
            this.crearNuevaOrden();
        });
        $('#form-orden').on("change", "select.onChangeSeleccionarProveedor", (e) => {
            this.llenarDatosCabeceraSeccionProveedor(e.currentTarget.value)
        });

        $('#form-orden').on("change", "select.seleccionarDatoCabeceraConcatoProveedor", (e) => {
            document.querySelector("p[name='telefono_contacto']").textContent = e.currentTarget.options[e.currentTarget.selectedIndex].dataset.telefono
        });
        $('#form-orden').on("click", "button.agregarCuentaProveedor", () => {
            this.agregarCuentaProveedor();
        });

        $("#form-agregar-cuenta-bancaria-proveedor").on("submit", (e) => {
            e.preventDefault();
            document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").setAttribute("disabled", true);
            this.guardarCuentaBancariaProveedor();
        });
        $('#form-orden').on("change", "select.actualizarFormaPago", () => {
            this.actualizarFormaPago();
        });
        $('#form-orden').on("change", "select.handleChangeSede", (e) => {
            this.changeSede(e.currentTarget);
        });
    }

    cargarContenedorOrden() {

        let contenidoHTML = `

        <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                Ordenes generadas
            </div>
        </div>
        <div class="panel-body" style="overflow:auto; white-space:nowrap; padding-bottom:0px;">
            <ul class="list-inline">
                <li>
                    <div class="panel panel-default">
                        <div class="panel-heading text-center" style="display:flex; flex-direction:row; gap:0.5rem;">
                            <h5>Cód. orden: <span class="label label-default" title="Código de orden"><span
                                        name="tituloDocumentoCodigoOrden[]">OC-240240</span></span></h5>
                            <h5>Cód. Softlink: <span class="label label-default" title="Código de Softlink"><span
                                        name="tituloDocumentoCodigoSoftlink[]">00100189</span></span></h5>
                        </div>
                        <div class="panel-body">
                            <ul class="list-inline">
                                <li>
                                    <dl>
                                    <dt>Empresa:</dt>
                                    <dd>OK COMPUTER EIRL</dd>
                                    <dt>Sede:</dt>
                                    <dd>Lima</dd>
                                    <dt>Proveedor:</dt>
                                    <dd>MAXIMA EIRL</dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl>
                                    <dt>Fecha emsión:</dt>
                                    <dd>##/##/####</dd>
                                    <dt>Importe:</dt>
                                    <dd>S/.1000.00</dd>
                                    <dt>Cta Proveedor:</dt>
                                    <dd>55234242-2432-10</dd>
                                </li>
                            </ul>

                            <div class="text-left">
                            <button type="button" class="btn btn-xs btn-success" id="btnSeleccionarOrden" title="Seleccionar"><i class="fas fa-check"></i></button>
                            <button type="button" class="btn btn-xs btn-info" id="btnSeleccionarOrden" title="Imprimir"><i class="fas fa-print"></i></button>
                            <button type="button" class="btn btn-xs btn-warning" id="btnSeleccionarOrden" title="Editar"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-xs btn-danger" id="btnSeleccionarOrden" title="Anular"><i class="fas fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-primary" id="btnSeleccionarOrden" title="Migrar a Softlink"><i class="fas fa-file-export"></i></button>
                            </div>
    
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
        <br>
        

        <div class="panel panel-info" style="flex:auto;">
        <div class="panel-heading">
            <div class="panel-title">
                Cabecera Orden
            </div>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <li role="presentation" class="active"><a href="#seccionDetalle" aria-controls="seccionDetalle" role="tab" data-toggle="tab">Detalle documento</a></li>
                        <li role="presentation"><a href="#seccionProveedor" aria-controls="seccionProveedor" role="tab" data-toggle="tab">Proveedor</a></li>
                        <li role="presentation"><a href="#seccionCondicionCompra" aria-controls="seccionCondicionCompra" role="tab" data-toggle="tab">Condicion de compra</a></li>
                        <li role="presentation"><a href="#seccionDespacho" aria-controls="seccionDespacho" role="tab" data-toggle="tab">Despacho</a></li>
                    </ul>
                </div>

                <div class="col-md-10">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="seccionDetalle">
                            <fieldset class="group-table">
                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Tipo Orden</dt>
                                            <dd>
                                            <select class="form-control input-xs" name="id_tipo_orden_compra[]">
                                                @foreach ($tp_documento as $tp)
                                                @if($tp->descripcion == 'Orden de Compra')
                                                <option value="{{$tp->id_tp_documento}}" selected>{{$tp->descripcion}}</option>
                                                @else
                                                @if((!in_array(Auth::user()->id_usuario,[17,27,3,1,77]) && $tp->id_tp_documento == 13))
                                                @else
                                                <option value="{{$tp->id_tp_documento}}">{{$tp->descripcion}}</option>
                                                @endif
                                                @endif
                                                @endforeach

                                            </select>
                                            </dd>
                                            <dt>Periodo</dt>
                                            <dd>
                                            <select class="form-control input-xs" name="id_periodo[]">
                                                @foreach ($periodos as $periodo)
                                                <option value="{{$periodo->id_periodo}}">{{$periodo->descripcion}}</option>
                                                @endforeach
                                            </select>
                                            </dd>

                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Código</dt>
                                            <dd>
                                                <p class="form-control-static" name="codigo_orden_compra[]">(Debe crear o abrir una orden)</p>
                                            </dd>
                                            <dt>Cod.Softlink</dt>
                                            <dd>
                                                <p class="form-control-static" name="codigo_softlink[]">(Debe migrar la OC/OS)</p>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Moneda</dt>
                                            <dd>
                                            <select class="form-control input-xs" name="id_moneda">
                                                @foreach ($tp_moneda as $tpm)
                                                <option value="{{$tpm->id_moneda}}" data-simbolo-moneda="{{$tpm->simbolo}}">{{$tpm->descripcion}} ( {{$tpm->simbolo}} )</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                            <dt>Fecha Emisión</dt>
                                            <dd>
                                                <input class="form-control input-xs" name="fecha_emision[]" type="datetime-local" value="2024-03-19T11:23">
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Empresa / Sede</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs handleChangeSede " name="id_sede[]" title="Seleccionar empresa - sede" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($sedes as $sede)
                                                <option value="{{$sede->id_sede}}" data-id-empresa="{{$sede->id_empresa}}" data-direccion="{{$sede->direccion}}" data-id-ubigeo="{{$sede->id_ubigeo}}" data-ubigeo-descripcion="{{$sede->ubigeo_descripcion}}">{{$sede->descripcion}}</option>
                                                @endforeach
                                            </select>                                                           
                                            </dd>
                                            <dt>
                                                <dd><img id="logo_empresa" src="/images/img-wide.png" alt="" style="height:80px !important; width:100% !important;"></dd>
                                            </dt>
                                        </dl>
                                    </div>
                                </div>


                            </fieldset>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="seccionProveedor">
                            <fieldset class="group-table">
                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>RUC - Razón social</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs onChangeSeleccionarProveedor" name="id_proveedor[]" title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($proveedores as $proveedor)
                                                <option value="{{$proveedor->id_proveedor}}" data-id-contribuyente="{{$proveedor->id_contribuyente}}" data-razon-social="{{$proveedor->contribuyente->razon_social}}" data-numero-documento="{{$proveedor->contribuyente->nro_documento}}">{{$proveedor->contribuyente->nro_documento!=null?$proveedor->contribuyente->nro_documento.' - ':''}} {{$proveedor->contribuyente->razon_social}}</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                            <dt>Contacto</dt>
                                            <dd>
                                            <select class="form-control input-xs seleccionarDatoCabeceraConcatoProveedor" name="id_contacto_proveedor[]">
                                                <option value="" disabled>Elija una opción</option>
                                            </select>
                                            </dd>


                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                        <dt>Dirección</dt>
                                            <dd>
                                            <p class="form-control-static" name="direccion_proveedor[]">(seleccione un proveedor)</p>
                                            </dd>
                                            <dt>Telefono contacto</dt>
                                            <dd>
                                                <p class="form-control-static" name="telefono_contacto[]">(seleccione un concacto)</p>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Cuenta Bancaria</dt>
                                            <dd>
                                            <div style="display:flex;">
                                                <select class="form-control input-xs" name="id_cuenta_bancaria_proveedor[]">
                                                    <option value="" disabled>Elija una opción</option>
                                                </select>
                                                <button type="button" class="btn-primary agregarCuentaProveedor" title="Agregar cuenta bancaria"><i class="fas fa-plus"></i></button>

                                            </div>
                                            </dd>
                                            <dt>Rubro</dt>
                                            <dd>
                                            <select class="selectpicker" title="Elija una opción" data-width="100%" data-container="body" data-live-search="true" name="id_rubro_proveedor[]">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($rubros as $rubro)
                                                <option value="{{$rubro->id_rubro}}">{{$rubro->descripcion}}</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                        </dl>
                                    </div>

                                </div>
                            </fieldset>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="seccionCondicionCompra">
                            <fieldset class="group-table">
                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Forma de pago</dt>
                                            <dd>
                                            <select class="form-control input-xs actualizarFormaPago" name="forma_pago[]">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($condiciones_softlink as $cond)
                                                <option value="{{$cond->id_condicion_softlink}}" data-dias="{{$cond->dias}}">{{$cond->descripcion}}</option>
                                                @endforeach
                                            </select>
                                            <div style="display:none;">
                                                <select class="form-control group-elemento activation" name="id_condicion[]" style="width:100%; text-align:center;">
                                                    @foreach ($condiciones as $cond)
                                                    <option value="{{$cond->id_condicion_pago}}">{{$cond->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Plazo entrega</dt>
                                            <dd>
                                            <div style="display:flex;">
                                                <input type="number" name="plazo_entrega[]" min="0" class="form-control input-xs" style="text-align:right;">
                                                <input type="text" value="días" class="form-control group-elemento input-xs" style="text-align:center;" readonly="">
                                            </div>
                                             </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Requerimiento</dt>
                                            <dd>
                                            <p class="form-control-static" name="requerimiento_vinculados[]">(Sin vinculo con requerimiento)</p>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Tipo Documento</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs" name="id_tipo_documento[]"  title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($tp_doc as $tp)
                                                @if($tp->descripcion == 'Factura')
                                                <option value="{{$tp->id_tp_doc}}" selected>{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                                                @else
                                                <option value="{{$tp->id_tp_doc}}">{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="seccionDespacho">
                            <fieldset class="group-table">
                                <div class="row" style="position: absolute;right: 2rem;z-index: 999;">

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Direccion de Entrega</dt>
                                            <dd>
                                            <input class="form-control input-xs" name="direccion_entrega[]" type="text">
                                            </dd>
                                            <dt>Compra locales</dt>
                                            <dd>
                                            <input type="checkbox" name="compra_local[]"> Compras locales
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Ubigeo entrega</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs" name="id_ubigeo_destino[]"  title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($ubigeos as $ubigeo)
                                                <option value="{{$ubigeo->id_dis}}">{{$ubigeo->codigo}} - {{$ubigeo->descripcion}} - {{$ubigeo->provincia}} - {{$ubigeo->departamento}}</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                            <dt>Observación</dt>
                                            <dd>
                                            <textarea class="form-control input-xs" name="observacion[]" cols="100" rows="100" style="height:50px;"></textarea>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Personal autorizado #1</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs" name="id_trabajador_persona_autorizado_1[]"  title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($trabajadores as $trabajador)
                                                <option value="{{$trabajador->id_trabajador}}">{{$trabajador->nombre_trabajador}}</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl class="">
                                            <dt>Personal autorizado #2</dt>
                                            <dd>
                                            <select class="form-control selectpicker input-xs" name="id_trabajador_persona_autorizado_2[]"  title="Elija una opción" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="" disabled>Elija una opción</option>
                                                @foreach ($trabajadores as $trabajador)
                                                <option value="{{$trabajador->id_trabajador}}">{{$trabajador->nombre_trabajador}}</option>
                                                @endforeach
                                            </select>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
            </div>
  
        </div>
    </div>

        <div class="panel panel-info" style="flex:auto;">
        <div class="panel-heading">
            <div class="panel-title">
                Item's Orden
            </div>
        </div>
            <div class="panel-body">
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-xs btn-success activation handleClickCatalogoProductosModal"
                                id="btnAgregarProducto" data-toggle="tooltip" data-placement="bottom" title="Agregar producto"><i
                                    class="fas fa-plus"></i> Productos</button>
                            <button type="button" class="btn btn-xs btn-info activation handleClickCatalogoProductosObsequioModal"
                                id="btnAgregarProductoObsequio" data-toggle="tooltip" data-placement="bottom"
                                title="Agregar producto para obsequio"><i class="fas fa-plus"></i> Productos para obsequio</button>
                            <button type="button" class="btn btn-xs btn-primary activation handleClickAgregarServicio"
                                id="btnAgregarServicio" data-toggle="tooltip" data-placement="bottom" title="Agregar servicio"><i
                                    class="fas fa-plus"></i> Servicio</button>
                            <button type="button"
                                class="btn btn-xs btn-default activation handleClickVincularRequerimientoAOrdenModalOLD"
                                onClick="openVincularRequerimientoConOrden();" id="btnAgregarVinculoRequerimiento" data-toggle="tooltip"
                                data-placement="bottom" title="Agregar items de otro requerimiento" disabled><i class="fas fa-plus"></i>
                                Vincular otro requerimiento
                            </button>
                        </div>
                        <div class="box box-widget">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table
                                        class="mytable table table-hover table-condensed table-bordered table-okc-view dataTable no-footer"
                                        name="listaDetalleOrden[]" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Req.</th>
                                                <th style="width: 5%">Cod. producto</th>
                                                <th style="width: 5%">Cod. softlink</th>
                                                <th style="width: 5%">Part number</th>
                                                <th>Descripción del producto/servicio</th>
                                                <th style="width: 8%">Unid. Med.</th>
                                                <th style="width: 5%">Cantidad solicitada</th>
                                                <th style="width: 5%">Cantidad Reservada</th>
                                                <th style="width: 5%">Cantidad atendida por orden</th>
                                                <th style="width: 8%">Cantidad a comprar</th>
                                                <th style="width: 10%">Precio Unitario</th>
                                                <th style="width: 6%">Total</th>
                                                <th style="width: 5%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody name="body_detalle_orden[]"></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="11" class="text-right"><strong>Monto neto:</strong></td>
                                                <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="montoNeto[]">
                                                        0.00</label></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="11" class="text-right">
                                                    <input class="activation handleClickIncluyeIGV" type="checkbox" name="incluye_igv[]"
                                                        checked> <strong>Incluye IGV</strong>
                                                </td>
                                                <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="igv[]">
                                                        0.00</label></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="11" class="text-right">
                                                    <input class="activation handleClickIncluyeICBPER" type="checkbox"
                                                        name="incluye_icbper[]"> <strong>Incluye ICBPER</strong>
                                                </td>
                                                <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="icbper[]">
                                                        0.00</label></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="11" class="text-right"><strong>Monto total:</strong></td>
                                                <td class="text-right"><span name="simboloMoneda[]">S/</span><label name="montoTotal[]">
                                                        0.00</label></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>



</div>
        `;

        document.querySelector("div[id='contenedor_orden']").insertAdjacentHTML('beforeend', contenidoHTML)
        $('select[name="id_sede[]"]').selectpicker();



    }


    crearNuevaOrden(){

        Swal.fire({
            title: "Desea desde un requerimiento pendiente o genera una orden libre?",
            width: 500,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Mostrar lista de requerimientos",
            denyButtonText: `Crear en orden libre`
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) { // mostrar lista de requerimientos pendientes
              Swal.fire("TO-DO: mostrar modal de lista de requerimientos pendientes", "", "info");
            } else if (result.isDenied) { // limpiar todo para genera orden libre
                Swal.fire("TO-DO: limpiar y habiltar todo los input", "", "info");
            }
          });

        }


    llenarDatosCabeceraSeccionProveedor(idProveedor) {
        this.ordenCtrl.obtenerDataProveedor(idProveedor).then((res) => {
            document.querySelector("p[name='direccion_proveedor[]']").textContent = res.contribuyente != null ? res.contribuyente.direccion_fiscal : '';
            this.llenarDatosCabeceraCuentaBancariaProveedor(res.cuenta_contribuyente);
            this.llenarDatosCabeceraConcactoProveedor(res.contacto_contribuyente);
            // document.querySelector("select[name='contacto_proveedor']").textContent = '';
            // document.querySelector("p[name='telefono_contacto']").textContent = '';
            // document.querySelector("select[name='rubro_proveedor']").textContent = '';

        });

    }

    llenarDatosCabeceraCuentaBancariaProveedor(data, idCuentaSelected = null) {
        let selectElement = document.querySelector("select[name='cuenta_bancaria_proveedor[]']");

        if (selectElement.options.length > 0) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        data.forEach(element => {
            let option = document.createElement("option");

            if (idCuentaSelected != null) {
                if (element.id_cuenta_contribuyente == idCuentaSelected) {
                    option.setAttribute('selected', true);
                }
            } else if (element.por_defecto == true) {
                option.setAttribute('selected', true);
            }

            option.text = element.nro_cuenta != null ? element.nro_cuenta : (element.nro_cuenta_interbancaria != null ? element.nro_cuenta_interbancaria : '');
            option.value = element.id_cuenta_contribuyente;
            selectElement.add(option);
        });
    }

    llenarDatosCabeceraConcactoProveedor(data) {
        let selectElement = document.querySelector("select[name='id_contacto_proveedor[]']");

        if (selectElement.options.length > 0) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        data.forEach(element => {
            let option = document.createElement("option");

            option.text = element.nombre;
            option.dataset.telefono = element.telefono;
            option.value = element.id_datos_contacto;
            selectElement.add(option);
        });

        // cargar telefono de contacto seleccionado
        if (document.querySelector("select[name='id_contacto_proveedor[]']").length > 0) {
            document.querySelector("p[name='telefono_contacto[]']").textContent = selectElement.options[selectElement.selectedIndex].dataset.telefono;
        }
    }



    //### proveedor

    limpiarFormularioCuentaBancaria() {
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta']").value = '' : false;
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta_interbancaria']").value = '' : false;
        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor']") ? document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='swift']").value = '' : false;
        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");
    }
    agregarCuentaProveedor() {

        document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] strong[id='nombre_contexto']").textContent = "Proveedores";

        const selectProveedor = document.querySelector("select[name='id_proveedor']");
        let razonSocialProveedor = selectProveedor.options[selectProveedor.selectedIndex].dataset.razonSocial;
        let id = selectProveedor.value;

        if (id > 0) {
            $('#modal-agregar-cuenta-bancaria-proveedor').modal({
                show: true
            });
            this.limpiarFormularioCuentaBancaria();

            document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] span[id='razon_social_proveedor']").textContent = razonSocialProveedor;
            document.querySelector("div[id='modal-agregar-cuenta-bancaria-proveedor'] input[name='id_proveedor']").value = id;

        } else {
            Swal.fire(
                '',
                'Debe seleccionar un proveedor',
                'warning'
            );
        }

    }

    guardarCuentaBancariaProveedor() {
        let idProveedor = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='id_proveedor']").value;
        let banco = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='banco']").value;
        let idMoneda = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='moneda']").value;
        let tipoCuenta = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] select[name='tipo_cuenta_banco']").value;
        let nroCuenta = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta']").value;
        let nroCuentaInter = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='nro_cuenta_interbancaria']").value;
        let swift = document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[name='swift']").value;
        let mensajeValidación = '';

        if (nroCuenta == '' || nroCuenta == null) {
            mensajeValidación += "Debe escribir un número de cuenta";
        }

        if (mensajeValidación.length > 0) {
            Lobibox.notify('warning', {
                title: false,
                size: 'normal',
                rounded: true,
                sound: false,
                delayIndicator: false,
                msg: mensajeValidación
            });
            document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

        } else {
            $.ajax({
                type: 'POST',
                url: 'guardar-cuenta-bancaria-proveedor',
                data: {
                    'id_proveedor': idProveedor,
                    'id_banco': banco,
                    'id_moneda': idMoneda,
                    'id_tipo_cuenta': tipoCuenta,
                    'nro_cuenta': nroCuenta,
                    'nro_cuenta_interbancaria': nroCuentaInter,
                    'swift': swift
                },
                cache: false,
                dataType: 'JSON',
                success: (response) => {
                    console.log(response);
                    if (response.status == '200') {
                        $('#modal-agregar-cuenta-bancaria-proveedor').modal('hide');
                        Lobibox.notify('success', {
                            title: false,
                            size: 'mini',
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: 'Cuenta bancaria registrado con éxito'
                        });

                        this.actualizarSelectCuentasBancariasProveedor(idProveedor, response.id_cuenta_contribuyente);
                        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

                    } else {
                        Swal.fire(
                            '',
                            'Hubo un error al intentar guardar la cuenta bancaria del proveedor, por favor intente nuevamente',
                            'error'
                        );
                        document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

                    }



                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    '',
                    'Hubo un error al intentar guardar la cuenta bancaria del proveedor. ' + errorThrown,
                    'error'
                );
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                document.querySelector("form[id='form-agregar-cuenta-bancaria-proveedor'] input[class~='boton']").removeAttribute("disabled");

            });
        }
    }

    getCuentasBancarias(idProveedor) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'GET',
                url: `listar-cuentas-bancarias-proveedor/${idProveedor}`,
                dataType: 'JSON',
                success(response) {
                    resolve(response);
                },
                error: function (err) {
                    reject(err)
                }
            });
        });

    }

    actualizarSelectCuentasBancariasProveedor(idProveedor, idCuentaBancaria = null) {
        this.getCuentasBancarias(idProveedor).then((res) => {
            if (res[0].cuenta_contribuyente) {
                this.llenarDatosCabeceraCuentaBancariaProveedor(res[0].cuenta_contribuyente, idCuentaBancaria);
            }
        }).catch((err) => {
            Swal.fire(
                '',
                'Hubo un problema al intentar obtener la lista de cuentas bancarias, por favor vuelva a intentarlo',
                'error'
            );
            console.log(err)
        })
    }



    //###

    actualizarFormaPago() {
        let selectFormaPago = document.querySelector("select[name='forma_pago']");
        let dias_condicion_softlink = selectFormaPago.options[selectFormaPago.selectedIndex].dataset.dias;

        if (dias_condicion_softlink > 0) {
            document.getElementsByName('id_condicion')[0].value = 2;
            document.getElementsByName('plazo_dias')[0].value = dias_condicion_softlink;
        } else {
            document.getElementsByName('id_condicion')[0].value = 1;
            document.getElementsByName('plazo_dias')[0].value = 0;
        }
    }


    changeSede(obj) {
        var id_empresa = obj.options[obj.selectedIndex].getAttribute('data-id-empresa');
        var id_ubigeo = obj.options[obj.selectedIndex].getAttribute('data-id-ubigeo');
        var ubigeo_descripcion = obj.options[obj.selectedIndex].getAttribute('data-ubigeo-descripcion');
        var direccion = obj.options[obj.selectedIndex].getAttribute('data-direccion');
        this.changeLogoEmprsa(id_empresa);
        this.llenarUbigeo(direccion, id_ubigeo, ubigeo_descripcion);

    }

    llenarUbigeo(direccion, id_ubigeo, ubigeo_descripcion) {
        document.querySelector("input[name='direccion_entrega[]']").value = direccion;
        document.querySelector("select[name='id_ubigeo_destino[]']").value = id_ubigeo;
        $("select[name='id_ubigeo_destino[]']").trigger("change");
    }


    changeLogoEmprsa(id_empresa) {
        switch (id_empresa) {
            case '1':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_okc.png');
                break;
            case '2':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_proyectec.png');
                break;
            case '3':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/logo_smart.png');
                break;
            case '4':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/jedeza_logo.png');
                break;
            case '5':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/rbdb_logo.png');
                break;
            case '6':
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/protecnologia_logo.png');
                break;
            default:
                document.querySelector("img[id='logo_empresa']").setAttribute('src', '/images/img-wide.png');
                break;
        }
    }

    // obtenerRequerimiento(reqTrueList, tipoOrden) { // used
    //     this.limpiarTabla('listaDetalleOrden');
    //     let idTipoItem = 0;
    //     let idTipoOrden = 0;
    //     let ambosTipos=false;
    //     if (tipoOrden == 'COMPRA') {
    //         idTipoItem = 1; // producto
    //         idTipoOrden = 2; // compra
    //     } else if (tipoOrden == 'SERVICIO') {
    //         idTipoItem = 2; // servicio
    //         idTipoOrden = 3; // servicio
    //     }else if(tipoOrden == 'COMPRA_SERVICIO'){
    //         ambosTipos=true;
    //     }

    //     detalleOrdenList = [];
    //     $.ajax({
    //         type: 'POST',
    //         url: 'requerimiento-detallado',
    //         data: { 'requerimientoList': reqTrueList },
    //         dataType: 'JSON',
    //         success: (response) => {
    //             // console.log(response);
    //             response.forEach(req => {
    //                 req.detalle.forEach(det => {
    //                     if ((![28, 5, 7].includes(det.estado)) && (det.id_tipo_item == idTipoItem || ambosTipos==true )) {
    //                         let cantidad_atendido_almacen = 0;
    //                         if (det.reserva.length > 0) {
    //                             (det.reserva).forEach(reserva => {
    //                                 if (reserva.estado == 1) {
    //                                     cantidad_atendido_almacen += parseFloat(reserva.stock_comprometido);
    //                                 }
    //                             });
    //                         }
    //                         let cantidad_atendido_orden = 0;
    //                         if (det.ordenes_compra.length > 0) {
    //                             (det.ordenes_compra).forEach(orden => {
    //                                 cantidad_atendido_orden += parseFloat(orden.cantidad);
    //                             });
    //                         }
    //                         let cantidadAAtender = (parseFloat(det.cantidad) - cantidad_atendido_almacen - cantidad_atendido_orden);
    //                         if (det.tiene_transformacion == false) {
    //                             detalleOrdenList.push(
    //                                 {
    //                                     'id': det.id,
    //                                     'id_detalle_requerimiento': det.id_detalle_requerimiento,
    //                                     'id_producto': det.id_producto,
    //                                     'id_tipo_item': det.id_tipo_item,
    //                                     'id_requerimiento': det.id_requerimiento,
    //                                     'codigo_requerimiento': req.codigo,
    //                                     'id_moneda': req.id_moneda,
    //                                     'cantidad': det.cantidad,
    //                                     'cantidad_a_comprar': !(cantidadAAtender >= 0) ? '' : cantidadAAtender,
    //                                     'cantidad_atendido_almacen': cantidad_atendido_almacen,
    //                                     'cantidad_atendido_orden': cantidad_atendido_orden,
    //                                     'descripcion_producto': det.producto != null ? det.producto.descripcion : '',
    //                                     'codigo_producto': det.producto != null ? det.producto.codigo : '',
    //                                     'part_number': det.producto != null ? det.producto.part_number : '',
    //                                     'codigo_softlink': det.producto != null ? det.producto.cod_softlink : '',
    //                                     'descripcion': det.descripcion,
    //                                     'estado': det.estado.id_estado_doc,
    //                                     'fecha_registro': det.fecha_registro,
    //                                     'id_unidad_medida': det.producto != null ? det.producto.id_unidad_medida : det.id_unidad_medida,
    //                                     'lugar_entrega': det.lugar_entrega,
    //                                     'observacion': det.observacion,
    //                                     'precio_unitario': det.precio_unitario,
    //                                     'stock_comprometido': cantidad_atendido_almacen,
    //                                     'subtotal': det.subtotal,
    //                                     'unidad_medida': det.producto!=null && det.producto.unidad_medida !=null ?det.producto.unidad_medida.abreviatura:det.unidad_medida
    //                                 }
    //                             );
    //                         }

    //                     }
    //                 });
    //             });
    //             // console.log(detalleOrdenList);
    //             if (detalleOrdenList.length == 0) {
    //                 Swal.fire(
    //                     '',
    //                     'No se encuentras items para atender',
    //                     'info'
    //                 );

    //             } else {

    //                 this.componerCabeceraOrden(response, idTipoOrden);
    //                 // this.listarDetalleOrdeRequerimiento(detalleOrdenList);
    //                 // this.setStatusPage();


    //             }
    //         }
    //     }).fail((jqXHR, textStatus, errorThrown) => {
    //         console.log(jqXHR);
    //         console.log(textStatus);
    //         console.log(errorThrown);
    //     });

    //     // sessionStorage.removeItem('reqCheckedList');
    //     // sessionStorage.removeItem('tipoOrden');
    // }

    // componerCabeceraOrden(data, idTipoOrden) {
    //     let codigoRequerimientoList =[];
    //     let idCcRequerimientoList =[];
    //     let observacionRequerimientoList =[];
    //     data.forEach(element => {
    //         let foundCodigoRequerimiento = codigoRequerimientoList.find(item => item == element.codigo);
    //         if (foundCodigoRequerimiento == undefined) {
    //             codigoRequerimientoList.push(element.codigo);
    //         }
    //         let foundIdCdpRequerimiento = codigoRequerimientoList.find(item => item == element.id_cc);
    //         if (foundIdCdpRequerimiento == undefined) {
    //             idCcRequerimientoList.push(element.id_cc);
    //         }
    //         let foundObservacionRequerimiento = codigoRequerimientoList.find(item => item == element.observacion);
    //         if (foundObservacionRequerimiento == undefined) {
    //             observacionRequerimientoList.push(element.observacion);
    //         }
    //     });

    //     this.cabeceraOrdenObject ={
    //         'id_tipo_orden':idTipoOrden??null,
    //         'descripcion_tipo_orden':idTipoOrden==1?'Compra':(idTipoOrden==2?'Servicio':'Orden & Servicio'),
    //         'codigo_requerimiento_vinculados':codigoRequerimientoList.toString(),
    //         'logo_empresa':Util.isEmpty(data[0].empresa.logo_empresa) ==false ?data[0].empresa.logo_empresa:null,
    //         'direccion_destino':data[0].sede && util.isEmpty(data[0].sede.direccion)==false ? data[0].sede.direccion : null,
    //         'id_ubigeo_destuno':data[0].sede && util.isEmpty(data[0].sede.id_ubigeo)==false ? data[0].sede.id_ubigeo : null,
    //         'id_empresa':data[0].id_empresa ? data[0].id_empresa : null,
    //         'id_sede':data[0].id_sede ? data[0].id_sede : null,
    //         'id_moneda':data[0].id_moneda ? data[0].id_moneda : null,
    //         'observacion':observacionRequerimientoList.toString(),
    //         'id_cc':idCcRequerimientoList
    //     };

    //     this.llenarCabeceraOrden(cabeceraOrdenObject);
    // }

    // llenarCabeceraOrden(cabeceraOrdenObject) {
    //     if (idTipoOrden == 3) { // orden de servicio
    //         this.ocultarBtnCrearProducto();
    //     }
    //     // let codigoRequerimiento = [];
    //     data.forEach(element => {
    //         let foundRequerimiento = this.codigoRequerimientoList.find(item => item == element.codigo);
    //         if (foundRequerimiento == undefined) {

    //             this.codigoRequerimientoList.push(element.codigo);

    //         }
    //     });

    //     document.querySelector("select[name='id_tp_documento']").value = idTipoOrden;
    //     document.querySelector("img[id='logo_empresa']").setAttribute("src", data[0].empresa.logo_empresa);
    //     document.querySelector("input[name='cdc_req']").value = this.codigoRequerimientoList.length > 0 ? this.codigoRequerimientoList : '';
    //     document.querySelector("input[name='ejecutivo_responsable']").value = '';
    //     document.querySelector("input[name='direccion_destino']").value = data[0].sede ? data[0].sede.direccion : '';
    //     document.querySelector("input[name='id_ubigeo_destino']").value = data[0].sede ? data[0].sede.id_ubigeo : '';
    //     document.querySelector("input[name='ubigeo_destino']").value = data[0].sede ? data[0].sede.ubigeo_completo : '';
    //     document.querySelector("select[name='id_sede']").value = data[0].id_sede ? data[0].id_sede : '';
    //     document.querySelector("select[name='id_moneda']").value = data[0].id_moneda ? data[0].id_moneda : 1;
    //     document.querySelector("input[name='id_cc']").value = data[0].id_cc ? data[0].id_cc : '';
    //     document.querySelector("textarea[name='observacion']").value = '';

    //     this.updateAllSimboloMoneda();

    // }




















}