<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">PRINCIPAL</li>
            <li><a href="#"><i class="fa fa-bell"></i> <span> Notificaciones</span></a></li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-chart-bar" aria-hidden="true"></i><span> Indicadores</span>
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
                            <li><a href="#"><i class="fa fa-circle"></i> Por Empresa</a></li>
                            <li><a href="#"><i class="fa fa-circle"></i> Por División</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        @yield('sidebar')
    </section>
</aside>
