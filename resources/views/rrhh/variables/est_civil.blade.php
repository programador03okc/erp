@include('layout.head')
@include('layout.menu_rrhh')
@include('layout.body')
<div class="page-main" type="est_civil">
    <legend><h2>Estado Civiles</h2></legend>
    <div class="row">
        <div class="col-md-7">
            <fieldset class="group-table">
                <table class="mytable table table-hover table-condensed table-bordered" id="listaEstCivil">
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
            <form id="form-est_civil" type="register" form="formulario">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <input type="hidden" name="id_estado_civil" primary="ids">
                <div class="row">
                    <div class="col-md-10">
                        <h5>Descripción</h5>
                        <input type="text" class="form-control activation" name="descripcion" disabled="true" placeholder="Descripcion del estado civil">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layout.footer')
@include('layout.scripts')
<script src="{{('/js/rrhh/variables/estado_civil.js')}}"></script>
@include('layout.fin_html')