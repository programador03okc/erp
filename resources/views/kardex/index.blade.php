
@extends('themes.base')

@section('cabecera') Dashboard Kardex @endsection
@include('layouts.menu_kardex')
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
    <li><a href="{{route('kardex.index')}}"><i class="fas fa-tachometer-alt"></i> Kardex</a></li>
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
