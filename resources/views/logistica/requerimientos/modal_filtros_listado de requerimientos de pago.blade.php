<div class="modal fade" tabindex="-1" role="dialog" id="modal-filtro-requerimientos-elaborados">
    <div class="modal-dialog" style="width: 38%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="font-weight:bold;">Filtros</h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" id="formFiltroListaRequerimientosElaborados">

                    <div class="form-group">
                        <div class="col-md-12">
                            <small>Seleccione los filtros que desee aplicar y cierre este cuadro para continuar</small>
                        </div>
                    </div>

                    <div class="container-filter">
                        <fieldset class="group-table">
                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Elaborados por">
                                            <input type="checkbox" name="chkElaborado" data-action="checked" data-name="elaborado"> Elaborados por
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleChangeUpdateValorFiltroRequerimientosElaborados change" name="elaborado" data-action="disabled" data-select="change" disabled>
                                        <option value="">Seleccione...</option>
                                        <option value="0">Todos</option>
                                        <option value="{{Auth::user()->id_usuario}}">Por mi</option>
                                        <option value="REVISADO_APROBADO">Con revisados / aprobados por mi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Empresa">
                                            <input type="checkbox" name="chkEmpresa" data-action="checked" data-name="empresa"> Empresa - Sede
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm handleChangeFiltroEmpresa handleChangeUpdateValorFiltroRequerimientosElaborados" name="empresa" data-action="disabled" data-select="change" disabled>
                                        <option value="">Empresa...</option>
                                        @foreach ($empresas as $emp)
                                        <option value="{{$emp->id_empresa}}">{{$emp->razon_social}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control input-sm" data-select="change" name="sede" data-action="disabled" disabled>
                                        <option value="">Sedes...</option>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Sede">
                                            <input type="checkbox" name="chkSede" data-action="checked" data-name="sede"> Sede
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleChangeUpdateValorFiltroRequerimientosElaborados" data-select="change" name="sede" data-action="disabled" disabled>
                                        <option value="">Sedes...</option>
                                    </select>
                                </div>
                            </div> --}}


                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Grupo">
                                            <input type="checkbox" name="chkGrupo" data-action="checked" data-name="grupo"> Grupo - division
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm handleChangeFiltroGrupo handleChangeUpdateValorFiltroRequerimientosElaborados" name="grupo" data-action="disabled" data-select="change" disabled>
                                        @foreach ($grupos as $grupo)
                                        <option value="{{$grupo->id_grupo}}">{{$grupo->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm handleChangeUpdateValorFiltroRequerimientosElaborados" name="division" data-action="disabled" data-select="change" disabled>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="División">
                                            <input type="checkbox" name="chkDivision" data-action="checked" data-name="division"> División
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleChangeUpdateValorFiltroRequerimientosElaborados" name="division" data-action="disabled" data-select="change" disabled>
                                    </select>
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Fecha de creación">
                                            <input type="checkbox" name="chkFechaRegistro" data-action="checked" data-name="fechas"> Fecha creación
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-4">
                                    <input type="date" name="fechaRegistroDesde" class="form-control handleChangeUpdateValorFiltroRequerimientosElaborados" data-action="disabled" data-select="change" disabled>
                                    <small class="help-block">Desde (dd-mm-aaaa)</small>
                                </div>
                                <div class="col-sm-4">
                                    <input type="date" name="fechaRegistroHasta" class="form-control handleChangeUpdateValorFiltroRequerimientosElaborados" data-action="disabled" data-select="change" disabled>
                                    <small class="help-block">Hasta (dd-mm-aaaa)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">
                                    <div class="checkbox">
                                        <label title="Estado">
                                            <input type="checkbox" name="chkEstado" data-action="checked" data-name="estado"> Estado
                                        </label>
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm handleChangeUpdateValorFiltroRequerimientosElaborados" data-select="change" name="estado" data-action="disabled" disabled>
                                        @foreach ($estados as $estado)
                                        @if ($estado->id_requerimiento_pago_estado!==8)
                                        <option value="{{$estado->id_requerimiento_pago_estado}}">{{$estado->descripcion}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" class="close" data-dismiss="modal" id="filtrar-reuerimiento-pago">Aceptar</button>
            </div>
        </div>
    </div>
</div>

