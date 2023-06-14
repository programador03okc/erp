@extends('themes.base')

@section('cabecera') Dashboard Finanzas @endsection
@include('layouts.menu_finanzas')
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
    <li><a href="{{route('finanzas.index')}}"><i class="fas fa-tachometer-alt"></i> Finanzas</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="row">

</div>
@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/chartjs/Chart.min.js') }}"></script>
<script>
    $(document).ready(function(){
        Util.seleccionarMenu(window.location);
    });
</script>
@endsection


{{-- ---- --}}
