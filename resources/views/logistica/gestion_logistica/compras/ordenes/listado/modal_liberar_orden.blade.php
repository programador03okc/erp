<div class="modal fade" tabindex="-1" role="dialog" id="modal-liberar-orden" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-liberar-orden" enctype="multipart/form-data">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Liberar item de Orden <span id="codigo_orden"></span></h3>
                <small>Seleccione los items que desea liberar/desvincular de la orden para ser nuevamente atendido los item seleccionados. A liberar algún item, el requerimiento pasara a lista de pendientes. Esta acción de liberar item no realiza actualiaciones sobre el documento en softlink</small>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Motivo</h5>
                        <textarea name="motivo_liberar_orden" class="form-control" rows="3" cols="30" required></textarea>
                    </div>
                    <div class="col-md-12">
                        <h5>&nbsp;</h5>
                       <input type="checkbox" name="anular_item_liberado_de_orden">  Anular item liberado de orden
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-widget">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="mytable table table-responsive table-bordered table-okc-view" id="tablaItemsDisponiblesParaLiberar">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:10%">Cod.Agile</th>
                                                <th style="width:10%">Cod.Softlink</th>
                                                <th style="width:10%">Part-number</th>
                                                <th style="width:30%">Descripción</th>
                                                <th style="width:10%">Cantidad</th>
                                                <th style="width:10%">Precio</th>
                                                <th style="width:5%">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" class="close" data-dismiss="modal">Cancelar</button>
                <input type="submit" id="submit_liberar" class="btn btn-success" value="Liberar"/>
            </div>
            </form>
        </div>
    </div>
</div>