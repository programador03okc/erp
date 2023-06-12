<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">PRINCIPAL</li>
            <li><a href="#"><i class="fa fa-bell-o"></i> <span> Notificaciones</span></a></li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i><span> Indicadores</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Reporte
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Por Empresa</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> Por División</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MÓDULO NECESIDADES</li>
            <li><a href="{{route('necesidades.index')}}"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
            {{-- @if(Auth::user()->tieneSubModulo(51)) --}}
            <li class=" treeview ">
                <a href="#">
                    <i class="fa fa-cubes"></i> <span>Requerimiento logístico</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{-- @if(Auth::user()->tieneAplicacion(102)) --}}
                    <li><a href="{{route('necesidades.requerimiento.elaboracion.index')}}"><i class="far fa-circle fa-xs"></i> Crear / editar</a></li>
                    {{-- @endif
            @if(Auth::user()->tieneAplicacion(103)) --}}
                    <li><a href="{{route('necesidades.requerimiento.listado.index')}}"><i class="far fa-circle fa-xs"></i> Listado</a></li>
                    {{-- @endif --}}
                </ul>
            </li>
            {{-- @endif --}}
            {{-- @if(Auth::user()->tieneSubModulo(52)) --}}
            {{-- @if(Auth::user()->tieneAplicacion(132)) --}}
            <li><a href="{{route('necesidades.pago.listado.index')}}"><i class="fa fa-money"></i> <span>Requerimiento de pago</span></a></li>
            {{-- @endif --}}

            {{-- @endif --}}
            {{-- @if(Auth::user()->tieneAplicacion(134)) --}}
            <li><a href="{{route('necesidades.revisar-aprobar.listado.index')}}"><i class="fa fa-check-square-o"></i> <span>Revisar / aprobar</span></a></li>
            {{-- @endif --}}
            {{-- <li><a href="{{route('necesidades.ecommerce.index')}}"><i class="fas fa-shopping-cart"></i> <span>ECOMMERCE</span></a></li> --}}


        </ul>
    </section>
</aside>