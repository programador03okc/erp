<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('cabecera') - AGILE</title>
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" />
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/lobibox/dist/css/lobibox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @yield('estilos')
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include("themes/header")
        @include("themes/aside")

        <div class="content-wrapper" id="wrapper-okc">
            @yield('option')
            <section class="content-header">
                <h1>@yield('cabecera')</h1>
                @yield('breadcrumb')
            </section>
            <section class="content">
                @yield('cuerpo')
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs"></div>Copyright &copy; 2023 Sistema AGILE
        </footer>

        <div class="control-sidebar-bg"></div>
    </div>

    @include('themes.modal-clave')

    <script src="{{ asset('template/adminlte2-4/plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/sweetalert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/lobibox/dist/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    <script src='{{ asset("template/adminlte2-4/plugins/moment/moment.min.js?v=1") }}'></script>
	<script src="{{ asset('template/adminlte2-4/plugins/jquery-number/jquery.number.min.js') }}"></script>
    <script src="{{ asset('js/ini.js') }}?v={{ filemtime(public_path('js/ini.js')) }}"></script>
    <script src="{{ asset('js/function.js') }}?v={{ filemtime(public_path('js/function.js')) }}"></script>
    <script src="{{ asset('js/myjava.js') }}?v={{ filemtime(public_path('js/myjava.js')) }}"></script>
    <script src="{{ asset('js/util.js') }}?v={{ filemtime(public_path('js/util.js')) }}"></script>
    <script>
        const token = '{{ csrf_token() }}';
        let auth_user = JSON.parse('{!!$auth_user!!}');
    </script>
    @routes
    <script>
        $(document).ready(function() {
            Util.seleccionarMenu(window.location);
            validarClave();
			notificacionesNoLeidas();
        });
    </script>
    @yield('scripts')
</body>
</html>
