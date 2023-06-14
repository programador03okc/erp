
@extends('themes.base')
@include('layouts.menu_config')
@section('titulo') Dashboard de configuración @endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('configuracion.dashboard')}}"><i class="fas fa-tachometer-alt"></i> Configuración</a></li>
    <li class="active">Dashboard</li>
</ol>
@endsection

@section('content')
@endsection