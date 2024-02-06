
@extends('themes.base')

@section('cabecera') Dashboard Control @endsection
@include('layouts.menu_control')
@section('estilos')
    <style>
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('almacen.index')}}"><i class="fas fa-tachometer-alt"></i> Control de Guias</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="row">

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

    });
</script>
@endsection
{{-- --------------------- --}}
