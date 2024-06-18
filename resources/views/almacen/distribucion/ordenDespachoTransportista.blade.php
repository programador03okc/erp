<div class="modal fade" tabindex="-1" role="dialog" id="modal-orden_despacho_transportista" style="overflow-y:scroll;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-orden_despacho_transportista">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Datos del Transportista</h3>
                </div>
                <div class="modal-body">
                    <input type="text" class="oculto" name="id_od" />
                    <input type="text" class="oculto" name="con_id_requerimiento">
                    <input type="text" class="oculto" name="id_proyecto">
                    <input type="text" class="oculto" name="unidad">
                    <input type="text" class="oculto" name="id_moneda" value="1">
                    {{-- <input type="text" class="oculto" name="id_od_grupo_detalle"> --}}
                    <fieldset class="group-table" id="fieldsetTransportista">
                        <div class="row">
                            <!-- <div class="col-md-2">
                                <h5>Propia</h5>
                                <input type="checkbox" name="transporte_propio" 
                                        style="margin-right: 10px; margin-left: 7px;"/> 
                            </div> -->
                            <div id="agencia" class="col-md-12">
                                <h5>Agencia de transporte *</h5>
                                <div style="display:flex;">
                                    <input class="oculto" name="tr_id_transportista" />
                                    <input type="text" class="form-control" name="tr_razon_social" placeholder="Seleccione un transportista..." disabled="true">
                                    <button type="button" class="input-group-text activation" onClick="openTransportistaModal();">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    {{-- <button type="button" class="btn-primary activation" title="Agregar Proveedor" onClick="addProveedorModal();">
                                        <i class="fas fa-plus"></i></button> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Guía del transportista</h5>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="serie" onBlur="ceros_numero('serie');" placeholder="Serie">
                                    <span class="input-group-addon">-</span>
                                    <input type="text" class="form-control" name="numero" onBlur="ceros_numero('numero');" placeholder="Número">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>Fecha de emisión de guía</h5>
                                <input type="date" name="fecha_transportista" class="form-control" />
                            </div>

                            <!-- <div class="col-md-2">
                                <h5>incluye IGV</h5>
                                <input type="checkbox" name="incluye_igv" id="incluye_igv" style="margin-top: 13px;" />
                            </div>-->

                            <div class="col-md-4">
                                <h5>Monto flete <small>(Sin IGV)</small></h5>
                                <div class="input-group">
                                    <span class="input-group-addon" disabled>S/</span>
                                    <input type="number" class="form-control handleUpdateImporteFleteSinIGV" name="importe_flete_sin_igv" step="any" placeholder="(sin IGV)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>Monto flete <small>(Con IGV)</small></h5>
                                <div class="input-group">
                                    <span class="input-group-addon" disabled>S/</span>
                                    <input type="number" class="form-control handleUpdateImporteFleteConIGV" name="importe_flete" step="any" placeholder="(con IGV)" readonly>
                                </div>
                            </div> 
                        </div>
                        <div class="row">

 
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="credito" id="credito"> Credito
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="hadleChangeAplicaIGV" name="aplica_igv" id="aplica_igv"> Aplica IGV
                                    </label>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                    <br />
                    <fieldset class="group-table" id="fieldsetGuiaVenta">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Guía de venta</h5>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="serie_guia_venta" onBlur="ceros_numero('serie_gv');" placeholder="Serie">
                                    <span class="input-group-addon">-</span>
                                    <input type="text" class="form-control" name="numero_guia_venta" onBlur="ceros_numero('numero_gv');" placeholder="Número">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5>Fecha despacho real</h5>
                                <input type="date" name="fecha_despacho_real" class="form-control" />
                            </div>
                        </div>
                    </fieldset>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 style="font-size: 14px;">* Campos obligatorios</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" id="btn_cerrar_transportista" class="btn btn-default" onClick="cerrarDespachoTransportista();" value="Cerrar" />
                    <input type="submit" id="submit_od_transportista" class="btn btn-success" value="Guardar" />
                </div>
            </form>
        </div>
    </div>
</div>