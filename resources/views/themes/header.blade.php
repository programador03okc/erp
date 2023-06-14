<header class="main-header">
    <a href="{{ route('inicio') }}" class="logo">
        <span class="logo-mini">AGILE</span>
        <span class="logo-lg">AGILE</span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ route('configuracion.dashboard') }}">Configuración</a>
                </li>
                <li>
                    <a href="#">Sobre Agile</a>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fa fa-bell"></i>
                        <span id="spanNotificaciones" class="label label-default">0</span>
                    </a>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">@if (!is_null(Auth::user())) {{ Auth::user()->nombre_corto }} @endif</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Cambiar contraseña</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('cerrar-sesion') }}" class="btn btn-default btn-flat">Cerrar sesión</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
