@include('layouts.head')
@include('layouts.menu_rrhh')
@include('layouts.body')
<div class="page-main" type="bonificacion">
    <legend><h2>Bonificaciones al Personal</h2></legend>
    <form id="form-bonificacion" type="register" form="formulario">
        <input type="hidden" name="id_bonificacion" primary="ids">
        <div class="row">
            <div class="col-md-3">
                <h5>Buscar DNI</h5>
                <input type="hidden" class="form-control" name="id_trabajador">
                <div class="input-group-okc">
                    <input type="text" class="form-control" name="nro_documento" placeholder="Ingrese DNI" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text" id="basic-addon1" onClick="buscarPersona();">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h5>Nombre del trabajador</h5>
                <input type="text" class="form-control" name="datos_trabajador" disabled="true">
            </div>
            <div class="col-md-2">
                <h5>Fecha</h5>
                <input type="date" class="form-control activation" name="fecha" disabled="true">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <h5>Tipo Bonificacion</h5>
                <select class="form-control activation" name="id_variable_bonificacion" disabled="true">
                    <option value="0" selected disabled>Elija una opción</option>
                    @foreach ($bonif as $bonif)
                        <option value="{{$bonif->id_variable_bonificacion}}">{{$bonif->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <h5>Afecto</h5>
                <select class="form-control activation" name="afecto" disabled="true">
                    <option value="0" disabled>Elija una opción</option>
                    <option value="SI">Si</option>
                    <option value="NO">No</option>
                </select>
            </div>
            <div class="col-md-2">
                <h5>Importe</h5>
                <input type="number" class="form-control activation" name="importe" disabled="true">
            </div>
            <div class="col-md-3">
                <h5>Tipo Planilla</h5>
                <select class="form-control activation" name="id_tipo_pla" disabled="true"><option value="0" selected disabled>Elija una opción</option></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <h5>Motivo</h5>
                <textarea class="form-control activation" name="motivo" disabled="true"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <h5>Tabla de Resultados</h5>
                <table class="mytable table table-condensed table-bordered table-okc-view table-result-form" id="ListaBonificacionTrab" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>TIPO</th>
                            <th>AFECTO</th>
                            <th width="120">IMPORTE</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody id="trab-bonificacion">
                        <tr><td></td><td colspan="4"> No hay datos registrados</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

@include('layouts.footer')
@include('layouts.scripts')
<script src="{{('/js/rrhh/remuneraciones/bonificacion.js')}}"></script>
@include('layouts.fin_html')