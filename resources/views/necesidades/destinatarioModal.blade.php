<div class="modal fade" tabindex="-1" role="dialog" id="modal-destinatario" style="overflow-y:scroll;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-destinatario" type="register">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Destinatario: <span name="tipo_destinatario"></span></h3>
                </div>
                <div class="modal-body">
                    <input type="text" class="oculto" name="id" />
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Destinatario</h5>
                            <fieldset class="group-table">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-widget">
                                            <div class="box-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-condensed table-bordered" id="ListaDestinatario" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align:center; width: 10%;">Número documento</th>
                                                                <th style="text-align:center;">Nombre <span name="tipo_destinatario">destinatario</span></th>
                                                                <th style="text-align:center; width: 2%;">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_destinatario">
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" class="close" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>