<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Iniciar sesión - AGILE</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" />
        <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/fontawesome/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/adminlte2-4/dist/css/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/iCheck/square/blue.css') }}">
        <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-box-body">
                <div class="login-box-logo">
                    <img src="{{ asset('images/logo_okc.png') }}" alt="">
                </div>
                <p class="login-box-msg">¡Bienvenido al Sistema AGILE! <br> Ingrese sus credenciales!</p>

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <h5>Usuario</h5>
                    <div class="form-group has-feedback">
                        <input type="text" name="usuario" class="form-control {{ $errors->has('usuario') ? ' is-invalid' : '' }}" 
                            value="{{ old('usuario') }}" placeholder="Ingrese su usuario">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('usuario'))
                            <span class="form-group has-error">
                                <span class="help-block">{{ $errors->first('usuario') }}</span>
                            </span>
                        @endif
                    </div>
                    <h5>Contraseña</h5>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Ingrese su contraseña">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                            <span class="form-group has-error">
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            </span>
                        @endif
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-xs-6">
                            <div class="checkbox icheck">
                                <label><input type="checkbox" {{ old('remember') ? 'checked' : '' }}> Recordarme</label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-danger btn-block btn-flat">Iniciar sesión</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br>
        <div class="row text-center">
            <p class="text-muted"><span>{{config('global.version')}}</span></p>
        </div>

        <script src="{{ asset('template/adminlte2-4/plugins/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('template/adminlte2-4/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%'
                });
            });
        </script>
    </body>
</html>
