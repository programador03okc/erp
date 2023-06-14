@extends('themes.base_home')

@section('cabecera') Bienvenido @endsection

@section('estilos')
    
@endsection

@section('cuerpo')
<div class="box box-primary">
    <div class="box-header with-border"><h4>Lista de módulos</h4></div>
    <div class="box-body">
        <div class="row">{!! $modulos !!}</div>
    </div>
</div>
@endsection

@section('scripts')
    {{--  <script src='{{ asset("mgcp/js/util.js?v=27") }}'></script>  --}}
    <script>
        $(function () {
        });
    </script>
@endsection