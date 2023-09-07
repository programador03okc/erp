<div class="modal fade" tabindex="-1" role="dialog" id="modal-requerimiento_flete" style="overflow-y:scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-requerimiento_flete" type="register" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Requerimiento de Flete</h3>
                </div>
                <div class="modal-body">
                    <input type="text" class="oculto" name="id_od" />
                    <input type="text" class="oculto" name="id_moneda" value="1">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Cabecera Requerimiento</h5>
                            <fieldset class="group-table" id="fieldsetRequerimiento">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h5>Motivo/Concepto</h5>
                                            <input type="text" name="concepto" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Prioridad</h5>
                                            <select id="prioridad" class="form-control" name="prioridad">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($prioridades as $prioridad)
                                                @if($prioridad->id_prioridad == 1)
                                                <option value="{{$prioridad->id_prioridad}}" selected>{{ $prioridad->descripcion}}</option>
                                                @else
                                                <option value="{{$prioridad->id_prioridad}}">{{ $prioridad->descripcion}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Periodo</h5>
                                            <select id="periodo" class="form-control" name="periodo">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($periodos as $periodo)
                                                @if($periodo->id_periodo == $idPeriodoMayor)
                                                <option value="{{$periodo->id_periodo}}" selected>{{ $periodo->descripcion}}</option>
                                                @else
                                                <option value="{{$periodo->id_periodo}}">{{ $periodo->descripcion}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Solicitado por</h5>
                                            <select id="solicitado_por" class="selectpicker" name="solicitado_por" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($trabajadores as $trabajador)
                                                @if($trabajador->id_trabajador == $idTrabajadorSesion)
                                                <option value="{{$trabajador->id_trabajador}}" selected>{{ $trabajador->nombre_trabajador}}</option>
                                                @else
                                                <option value="{{$trabajador->id_trabajador}}">{{ $trabajador->nombre_trabajador}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Empresa</h5>
                                            <select id="empresa" class="selectpicker handleChangeEmpresa" name="empresa" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($empresas as $empresa)
                                                <option value="{{$empresa->id_empresa}}">{{ $empresa->razon_social}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Sede</h5>
                                            <input type="text" class="oculto" name="almacen">
                                            <select id="sede" name="sede" class="form-control handleChangeSede">
                                                <option value="0">Elija una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>División</h5>
                                            <input type="text" class="oculto" name="grupo">
                                            <select id="division" class="selectpicker handleChangeDivision" name="division" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($divisiones as $division)
                                                @if(in_array($division->grupo_id,[2]))
                                                <option data-id-grupo="{{$division->grupo_id}}" value="{{$division->id_division}}">{{$division->descripcion_grupo}} - {{$division->descripcion}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Fecha Entrega</h5>
                                            <input type="date" class="form-control activation" name="fecha_entrega" />
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <h5>observación</h5>
                                            <textarea class="form-control activation" name="observacion" cols="100" rows="100" style="height:50px;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h5>CDP</h5>
                                            <select id="cdp" class="selectpicker" name="cdp" data-live-search="true" data-width="100%" data-actions-box="true" data-size="10">
                                                <option value="0">Elija una opción</option>
                                                @foreach ($cdps as $cdp)
                                                <option value="{{$cdp->id}}"> {{ trim($cdp->codigo_oportunidad)}} - {{ trim($cdp->descripcion_oportunidad)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <h5>Item's de requerimiento</h5>
                            <fieldset class="group-table" id="fieldsetRequerimientoDetalle">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-widget">
                                            <div class="box-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-condensed table-bordered" id="ListaDetalleRequerimiento" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 2%">#</th>
                                                                <!-- <th style="width: 10%">Partida</th> -->
                                                                <th style="width: 10%">C.Costo</th>
                                                                <th>Descripción de item</th>
                                                                <th style="width: 10%">Unidad</th>
                                                                <th style="width: 10%">Cantidad</th>
                                                                <th style="width: 10%">Precio Unit.</th>
                                                                <th style="width: 10%">IGV</th>
                                                                <th style="width: 10%">Total</th>
                                                                <th style="width: 10%">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_detalle_requerimiento">
                                                            <tr>
                                                                <td>1<input type="text" class="oculto" name="id_item" value="1"></td>
                                                                <!-- <td>
                                                                    <p class="descripcion-partida">(NO SELECCIONADO)</p><button type="button" class="btn btn-xs btn-info handleClickCargarModalPartidas" name="partida">Seleccionar</button>
                                                                    <div class="form-group">
                                                                        <input type="text" class="partida" name="partida" hidden="">
                                                                    </div>
                                                                </td> -->
                                                                <td>
                                                                    <p class="descripcion-centro-costo" title="(NO SELECCIONADO)">(NO SELECCIONADO)</p><button type="button" class="btn btn-xs btn-primary handleClickCargarModalCentroCostos" name="centroCostos" title="">Seleccionar</button>
                                                                    <div class="form-group">
                                                                        <input type="text" class="centroCosto" name="centro_costo" value="" hidden="">
                                                                    </div>
                                                                </td>
                                                                <td class="text-center"><input type="text" class="form-control" name="descripcion_item" value="FLETE"></td>
                                                                <td class="text-center">(Servicio)</td>
                                                                <td class="text-center">1</td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span> <span id="precio_unitario"></span><input type="text" class="oculto" name="precio_unitario"></td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span> <span id="importe_igv"></span><input type="text" class="oculto" name="importe_igv"></td>
                                                                <td class="text-right"><span name="simboloMoneda">S/</span> <span id="importe_total"></span><input type="text" class="oculto" name="importe_total"></td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-warning btn-xs handleClickAdjuntarArchivoItem" name="btnAdjuntarArchivoItem[]" data-id="1" title="Adjuntos">
                                                                        <i class="fas fa-paperclip"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <br />


                    <div class="row">
                        <div class="col-md-12">
                            <h5 style="font-size: 14px;">* Campos obligatorios</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" class="close" data-dismiss="modal">Cerrar</button>
                    <input type="submit" id="submit_od_requerimiento_flete" class="btn btn-success" value="Guardar" />
                </div>
            </form>
        </div>
    </div>
</div>