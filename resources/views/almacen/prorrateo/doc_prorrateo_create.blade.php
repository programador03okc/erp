<div class="modal fade" tabindex="-1" role="dialog" id="modal-doc_prorrateo">
    <div class="modal-dialog" style="width:900px;">
        <div class="modal-content">
            <!-- <form id="form-doc_prorrateo"> -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Nuevo Documento de Prorrateo</h3>
                </div>
                <div class="modal-body">
                    <input class="oculto" name="id_prorrateo_doc">
                    <input class="oculto" name="id_prorrateo">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Tipo</h5>
                            <div style="display:flex;">
                                <select class="form-control" name="id_tp_prorrateo" required>
                                    <option value="0" disabled>Elija una opción</option>
                                    @foreach ($tp_prorrateo as $tp)
                                        <option value="{{$tp->id_tp_prorrateo}}">{{$tp->descripcion}}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-success" title="Agregar Tipo" onClick="agregar_tipo();">
                                <strong>+</strong></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Proveedor</h5>
                            <div style="display:flex;">
                                <input class="oculto" name="doc_id_proveedor" />
                                <input class="oculto" name="doc_id_contrib"/>
                                <input type="text" class="form-control" name="doc_razon_social" placeholder="Seleccione un proveedor..." 
                                    disabled="true" aria-describedby="basic-addon1" required>
                                <button type="button" class="input-group-text" id="basic-addon1" onClick="proveedorModal();">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn-primary" title="Agregar Proveedor" onClick="agregarProveedor();">
                                    <i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5>Fecha Emisión</h5>
                            <input type="date" name="doc_fecha_emision" class="form-control" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Tipo de Documento</h5>
                            <select class="form-control activation" name="id_tp_documento" required>
                                <option value="0" disabled>Elija una opción</option>
                                @foreach ($tp_doc as $tp)
                                    <option value="{{$tp->id_tp_doc}}">{{$tp->cod_sunat}} - {{$tp->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <h5>Serie-Número</h5>
                            <div style="display:flex;">
                                <input type="text" class="form-control" name="pro_serie" required
                                    placeholder="000">
                                <span class="input-group-addon">-</span>
                                <input type="text" class="form-control" name="pro_numero" required
                                    placeholder="000000" onChange="ceros_numero('pro_numero');">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5>Moneda</h5>
                            <select class="form-control" name="doc_id_moneda" required>
                                <option value="0" disabled>Elija una opción</option>
                                @foreach ($monedas as $tp)
                                    <option value="{{$tp->id_moneda}}">{{$tp->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <h5>TpCambio</h5>
                            <input type="number" name="tipo_cambio" class="form-control right" step="0.001"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Sub Total</h5>
                            <div style="display:flex;">
                                <input type="number" name="sub_total" class="form-control" step="0.0001"
                                    required/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 id="valor">Importe</h5>
                            <input type="number" name="importe" class="form-control" step="0.0001" readOnly required/>
                        </div>
                        <div class="col-md-3">
                            <h5>Importe Aplicado al Prorrateo <label id="abreviatura"></label></h5>
                            <input type="number" name="importe_aplicado" class="form-control activation" step="0.0001" required/>
                        </div>
                        <div class="col-md-3">
                            <br/>
                            <div class="borde-group-rojo">
                                <h5 style="margin:0;">Tipo de Prorrateo a Aplicar</h5>
                                <table width="100%">
                                    <tr>
                                        <td width="130">
                                            <select class="form-control" name="id_tipo_prorrateo" required>
                                                <option value="0" selected disabled>Elija una opción</option>
                                                @foreach ($tipos_prorrateo as $mn)
                                                    <option value="{{$mn->id_tipo_prorrateo}}">{{$mn->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-success" data-toggle="tooltip" 
                        data-placement="bottom" title="Guardar Documento" value="Guardar"/> -->
                    <button type="button" class="btn btn-success btn-xs boton " data-toggle="tooltip" 
                        data-placement="bottom" title="Guardar Documento" onClick="guardar_doc_prorrateo();">
                        Guardar </button>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>