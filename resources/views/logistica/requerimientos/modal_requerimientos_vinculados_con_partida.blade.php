<!-- Modal Centro costos -->
<div class="modal fade" role="dialog" id="modal-requerimientos-vinculados-con-partida" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal-requerimientos-vinculados-con-partida" onClick="$('#modal-requerimientos-vinculados-con-partida').modal('hide');"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Requerimientos vinculados con partida <span class="label label-default" title="" id="partida"></span> </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="alertaRequerimientoVinculadosConPartida"></div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table table-condensed table-bordered" id="tablaResumenRequerimientoVinculadosConPartida">
                            <thead>
                                <tr>
                                    <th>Presupuesto partida anual</th>
                                    <th>Presupuesto partida mes <small><span>(actual)</span></small></th>
                                    <th>Total de partidas de requerimientos <small>(incl. IGV)</small></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center;">
                                    <td><span name="ppto_partida_anual">...</span> <input type="text" class="oculto" name="ppto_partida_anual"></td>
                                    <td><span name="ppto_partida_mes">...</span><input type="text" class="oculto" name="ppto_partida_mes"></td>
                                    <td><span name="total_partida_de_requerimientos_incluido_igv">...</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-widget">
                            <div class="box-body">
                                <table class="table table-condensed table-bordered table-okc-view" id="tablaRequerimientosVinculadosConPartida" style="width:100%;">
                                <caption>En esta taba de requerimientos vinculados solo se muestra únicamente los items que coinciden con la partida seleccionada.</caption>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">Prio.</th>
                                            <th style="text-align:center;">Empresa - Sede</th>
                                            <th style="text-align:center; width:10%;">Código</th>
                                            <th style="text-align:center;">Fecha creación</th>
                                            <th style="text-align:center;">Concepto</th>
                                            <th style="text-align:center;">Tipo Req.</th>
                                            <th style="text-align:center;">Grupo</th>
                                            <th style="text-align:center;">División</th>
                                            <th style="text-align:center;">Solicitado por</th>
                                            <th style="text-align:center;">Req. creado por</th>
                                            <th style="text-align:center;">Observación</th>
                                            <th style="text-align:center;">Importe</th>
                                            <th style="text-align:center;">Estado</th>
                                            <th style="text-align:center;width:7%;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" class="close" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
    <label id="indice_item" style="display: none;"></label>
</div>