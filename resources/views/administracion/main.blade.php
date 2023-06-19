@extends('themes.base')
@include('layouts.menu_admin')

@section('cabecera')
    Módulo de administración
@endsection

@section('cuerpo')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Empresas activas</span>
                <span class="info-box-number">{{ $totalEmpresa }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sedes activas</span>
                <span class="info-box-number">{{ $totalSede }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Grupos activos</span>
                <span class="info-box-number">{{ $totalGrupo }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-navy"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Áreas activas</span>
                <span class="info-box-number">{{ $totalArea }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
