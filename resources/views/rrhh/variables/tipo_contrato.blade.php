@include('layouts.head')
@include('layouts.menu_rrhh')
@include('layouts.body')
<div class="page-main" type="tipo_contrato">
    <legend><h2>Tipo de Contrato</h2></legend>
    <div class="row">
        <div class="col-md-7">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered" id="listaTipoContra">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="40">N°</th>
                            <th>Descripción</th>
                            <th width="70">Estado</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </fieldset>
        </div>
        <div class="col-md-5">
            <form id="form-tipo_contrato" type="register" form="formulario">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <input type="hidden" name="id_tipo_contrato" primary="ids">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Descripción</h5>
                        <input type="text" class="form-control activation" name="descripcion" disabled="true" placeholder="Descripcion del tipo de contrato">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.scripts')
<script src="{{('/js/rrhh/variables/tipo_contrato.js')}}"></script>
@include('layouts.fin_html')