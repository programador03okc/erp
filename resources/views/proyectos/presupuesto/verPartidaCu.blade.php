<div class="modal fade" tabindex="-1" role="dialog" id="modal-ver_partida_cu" style="overflow-y:scroll;">
    <div class="modal-dialog" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" 
                aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Ver Partidas</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" id="nombre_cu"></div>
                            <div class="panel-body">
                                <table class="mytable table table-condensed table-bordered table-okc-view" width="100%" 
                                    id="VerPartidaCu"  style="margin-top:10px;">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Código</th>
                                            <th width="40%">Descripción</th>
                                            <th>Rendimiento</th>
                                            {{-- <th>Und</th> --}}
                                            <th>Cantidad</th>
                                            <th>Unitario</th>
                                            <th>Total Partida</th>
                                            <th>Fecha Registro CU</th>
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
    </div>  
</div>
