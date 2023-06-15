@extends('themes.base_home')

@section('cabecera') Inicio @endsection

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
    <script>
        $(function () {
        });
    </script>
@endsection