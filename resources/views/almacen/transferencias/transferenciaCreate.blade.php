<div class="modal fade" tabindex="-1" role="dialog" id="modal-ver_requerimiento" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <form id="form-ver_requerimiento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Nueva Transferencia del <label name="codigo_req"></label></h3>
                </div>
                <div class="modal-body">
                    <input type="text" class="oculto" name="id_requerimiento">
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Almacén Destino</h5>
                            <select class="form-control js-example-basic-single" name="id_almacen_destino_create" required>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <h5>Fecha de Emisión</h5>
                            <input type="date" class="form-control" name="fecha_emision_transferencia" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-2">
                            <h5>Sede Destino</h5>
                            <label name="sede_requerimiento"></label>
                        </div>
                        <div class="col-md-3">
                            <h5>Concepto</h5>
                            <label name="concepto"></label>
                        </div>
                        <div class="col-md-2">
                            <h5>Fecha de Requerimiento</h5>
                            <label name="fecha_requerimiento"></label>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" id="detalleRequerimiento" style="margin-top:10px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>O.C.</th>
                                        <th>Almacen Origen</th>
                                        <th>Código</th>
                                        <th>PartNumber</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Unid</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="submit_guia" class="btn btn-success" value="Generar Transferencia" />
                    <!-- <button class="btn btn-sm btn-success" onClick="generar_transferencia();">Generar Transferencia</button> -->
                </div>
            </form>
        </div>
    </div>
</div>