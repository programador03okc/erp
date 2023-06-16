@section('sidebar')
<li class="header">CONFIGURACION</li>

<li><a href="{{route('configuracion.dashboard')}}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-user-cog"></i> <span> Gestión de Accesos</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('configuracion.usuario.index') }}"><i class="far fa-circle fa-xs"></i> Usuarios</a></li>
        <li><a href="accesos"><i class="far fa-circle fa-xs"></i> Roles </a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#">
        <i class="fas fa-map-signs"></i> <span> Flujo de Aprobación</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="gestionar-flujos"><i class="far fa-circle fa-xs"></i> Gestionar Flujos</a></li>
        <li><a href="{{ route('configuracion.documentos.index') }}"><i class="far fa-circle fa-xs"></i> Documentos</a></li>
        <li><a href="{{ route('configuracion.historial-aprobaciones.index') }}"><i class="far fa-circle fa-xs"></i> Historial de Aprobaciones</a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#">
        <i class="fas fa-cog"></i> <span> Gestión del Sistema</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('configuracion.modulo.index') }}"><i class="far fa-circle fa-xs"></i> Módulos </a></li>
        <li><a href="{{ route('configuracion.aplicaciones.index') }}"><i class="far fa-circle fa-xs"></i> Aplicaciones</a></li>
        <li><a href="{{ route('configuracion.notas-lanzamiento.index') }}"><i class="far fa-circle fa-xs"></i> Notas de Lanzamiento</a></li>
        <li><a href="{{ route('configuracion.correos.index') }}"><i class="far fa-circle fa-xs"></i> Correo Corporativo</a></li>
    </ul>
</li>
@endsection
