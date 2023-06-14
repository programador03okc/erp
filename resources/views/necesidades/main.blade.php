@extends('themes.base')
<!-- @include('layouts.menu_necesidades') -->

@section('cabecera')
    Dashboard Necesidades
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('necesidades.index')}}"><i class="fas fa-tachometer-alt"></i> Necesidades</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('content')

<div class="row">
</div>

@include('almacen.verRequerimientoEstado')

@endsection
@section('scripts')
{{-- <script src="{{ asset('template/adminlte2-4/plugins/chartjs/Chart.min.js') }}"></script> --}}
    <script>

    </script>
@endsection
