@extends('themes.base')
@include('layouts.menu_tesoreria')
@section('cabecera')
    Dashboard Tesoreria
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('tesoreria.index')}}"><i class="fas fa-tachometer-alt"></i> Tesorería</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('cuerpo')

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fas fa-money-bill-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">TC. - COMPRA</span>
                <span class="info-box-number">{{ $tipo_cambio->compra }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Fecha: {{ date('d/m/Y', strtotime($tipo_cambio->fecha)) }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fas fa-money-bill-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">TC. - VENTA</span>
                <span class="info-box-number">{{ $tipo_cambio->venta }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Fecha: {{ date('d/m/Y', strtotime($tipo_cambio->fecha)) }}</span>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/chartjs/Chart.min.js') }}"></script>
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection


