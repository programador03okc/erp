@include('layouts.head')
@include('layouts.menu_rrhh')
@include('layouts.body')
<div class="page-main" type="datos_afp">
    <fieldset>
        <legend><h2>Reporte de AFP</h2></legend>
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-sm btn-flat  btn-block bg-olive" onclick="reporteExcelAfp();">
                    <i class="fa fa-download"></i> Descargar Excel
                </button>
            </div>
        </div>
        <br>
    </fieldset>
</div>
@include('layouts.footer')
@include('layouts.scripts')
<script src="{{('/js/rrhh/reportes/reporte_rrhh.js')}}"></script>
@include('layouts.fin_html')