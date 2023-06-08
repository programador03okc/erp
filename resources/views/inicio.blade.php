@extends('themes.base')

@section('cabecera') Bienvenido @endsection

@section('estilos')
    
@endsection

@section('cuerpo')
<div class="box box-solid">
    <div class="box-body">
        <span id="spanMensajeBienvenida">Por favor utilice las opciones del lado izquierdo</span>
    </div>
</div>
@endsection

@section('scripts')
    {{--  <script src='{{ asset("mgcp/js/util.js?v=27") }}'></script>
    <script src='{{ asset("mgcp/js/moment.min.js?v=1") }}'></script>  --}}
    <script>
        $(function () {
        });
    </script>
@endsection