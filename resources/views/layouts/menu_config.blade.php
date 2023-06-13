@section('sidebar')
<li class="header">CONFIGURACION</li>

<li><a href="{{route('configuracion.index')}}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-user-cog"></i> <span> Gestión de Accesos</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{route('configuracion.listarUsuarios')}}"> Usuarios</a></li>
        <li><a href="accesos"> Roles </a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#">
        <i class="fas fa-map-signs"></i> <span> Flujo de Aprobación</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="gestionar-flujos"> Gestionar Flujos</a></li>
        <li><a href="documentos"> Documentos</a></li>
        <li><a href="historial-aprobaciones"> Historial de Aprobaciones</a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#">
        <i class="fas fa-cog"></i> <span> Gestión del Sistema</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="modulo"> Módulos </a></li>
        <li><a href="aplicaciones"> Aplicaciones</a></li>
        <li><a href="notas_lanzamiento"> Notas de Lanzamiento</a></li>
        <li><a href="correo_coorporativo"> Correo Corporativo</a></li>
        <li><a href="configuracion_socket">Socket</a></li>
    </ul>
</li>
@endsection
