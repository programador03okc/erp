@include('layouts.head')
@include('layouts.menu_contabilidad')
@include('layouts.body')
<div class="page-main" type="cta_contable">
    <legend class="mylegend">
        <h2>Plan de Cuentas Contables</h2>
    </legend>
    {{-- <input type="hidden" name="_token" value="{{csrf_token()}}" id="token"> --}}
    <div class="row">
        <div id="tree"></div>
    </form>
</div>
@include('layouts.footer')
@include('layouts.scripts')
<script src="{{('/js/contabilidad/cta_contable.js')}}"></script>
@include('layouts.fin_html')