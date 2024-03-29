@include('layouts.head')
@include('layouts.menu_rrhh')
@include('layouts.body')
<div class="page-main" type="merito">
    <legend><h2>Méritos del Personal</h2></legend>
    <form id="form-merito" type="register" form="formulario">
        <input type="hidden" name="id_merito" primary="ids">
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
                <input type="date" class="form-control activation" name="fecha_merito" disabled="true">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <h5>Tipo mérito</h5>
                <select class="form-control activation" name="id_variable_merito" disabled="true">
                    <option value="0" selected disabled>Elija una opción</option>
                    @foreach ($meri as $meri)
                        <option value="{{$meri->id_variable_merito}}">{{$meri->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7">
                <h5>Concepto</h5>
                <input type="text" class="form-control activation" name="concepto" disabled="true">
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <h5>Motivo</h5>
                <textarea class="form-control activation" name="motivo" disabled="true"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <h5>Tabla de Resultados</h5>
                <table class="mytable table table-condensed table-bordered table-okc-view table-result-form" id="ListaMeritoTrab" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>TIPO MERITO</th>
                            <th>CONCEPTO</th>
                            <th width="90">FECHA</th>
                        </tr>
                    </thead>
                    <tbody id="trab-merito">
                        <tr><td></td><td colspan="3"> No hay datos registrados</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

@include('layouts.footer')
@include('layouts.scripts')
<script src="{{('/js/rrhh/escalafon/merito.js')}}"></script>
@include('layouts.fin_html')