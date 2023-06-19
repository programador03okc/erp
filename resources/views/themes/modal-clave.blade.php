<div class="modal fade" tabindex="-1" role="dialog" id="actualizar-clave" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog modal-style">
        <div class="modal-content">
            <form id="form-clave" data-form="actualizar-clave" method="POST">
                <div class="modal-header">
                    <h3 class="modal-title" id="titulo">Actualizar contraseña</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nueva contraseña</label>
                                <input class="form-control contraseña-validar" type="password" placeholder="Escriba la nueva contraseña" id="clave" name="clave" minlength="8"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Repita su contraseña</label>
                                <input class="form-control contraseña-validar" type="password" placeholder="Repita la contraseña" name="repita_clave" minlength="8" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" style="background-color: #ff8576 !important" role="alert">
                                <p>Su nueva contraseña debe tener al menos 8 caracteres alfanuméricos.</p>
                                <p>- Mínimo una Mayúscula</p>
                                <p>- Mínimo una Minúscula</p>
                                <p>- Mínimo un número</p>
                                <p>- Mínimo un caracter especial ("@#_%")</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>