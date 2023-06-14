@extends('themes.base')

@section('titulo')
Dashboard Servicios CAS
@endsection
@include('layouts.menu_cas')

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('content')

<div class="row">
</div>

@endsection
@section('scripts')
    <script>

    </script>
@endsection


{{-- @extends('layout.main')
@include('layout.menu_cas')
@section('titulo')
Dashboard Servicios CAS
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('cas.index')}}"><i class="fas fa-tachometer-alt"></i> Servicios CAS</a></li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection
@section('content')

<div class="row">

</div>

@endsection --}}
