<link rel="stylesheet" href="{{ asset('css/stepper.css')}}">

<div class="modal fade" tabindex="-1" role="dialog" id="modal-trazabilidad-requerimiento" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Trazabilidad Requerimiento</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-unstyled" id="head_requerimiento">
                            <li> <span style="font-weight: bold;">Código:</span>  <span id="codigo_requerimiento"></span></li>
                            <li><span style="font-weight: bold;">Creado Por:</span> <span id="requerimiento_creado_por"></span></li>
                            <li><span style="font-weight: bold;">Fecha registro:</span> <span id="fecha_registro_requerimiento"></span></li>
                            <li><span style="font-weight: bold;">Estado actual:</span> <span id="estado_actual_requerimiento"></span></li>
                        </ul>
                        <fieldset class="group-importes">
                            <div class="stepper-wrapper">
                            </div>
                        </fieldset>

                        <fieldset class="group-importes">
                            <table class="mytable table table-condensed table-bordered table-okc-view" id="listaTrazabilidadDetalleRequerimiento" width="100%">
                                <thead>
                                    <tr>
                                        <th>Código producto</th>
                                        <th>Part number</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th>Orden</th>
                                        <th>Guías de ingreso</th>
                                        <th>Factura</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="body_lista_trazabilidad_requerimiento">
                                    <tr id="default_tr">
                                        <td colspan="5"> No hay datos registrados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" class="close" data-dismiss="modal" >Cerrar</button>

            </div>
        </div>
    </div>
</div>