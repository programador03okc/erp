@extends('themes.base')

@section('cabecera')
Dashboard Servicios CAS
@endsection
@include('layouts.menu_cas')

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('cuerpo')

<div class="row">
</div>

@endsection
@section('scripts')
    <script>

    </script>
@endsection


{{-- @extends('themes.base')
@include('layout.menu_cas')
@section('cabecera')
Dashboard Servicios CAS
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('cuerpo')

<div class="row">

</div>

@endsection --}}
