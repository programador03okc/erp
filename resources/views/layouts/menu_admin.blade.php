@section('sidebar')
<li class="header">ADMINISTRACIÓN</li>
<li><a href="{{route('administracion.index')}}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-briefcase"></i> <span>Gestión Administrativa</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('administracion.empresas.index') }}"><i class="far fa-circle fa-xs"></i> Empresas</a></li>
        <li><a href="{{ route('administracion.sedes.index') }}"><i class="far fa-circle fa-xs"></i> Sedes </a></li>
        <li><a href="{{ route('administracion.grupos.index') }}"><i class="far fa-circle fa-xs"></i> Grupos</a></li>
        <li><a href="{{ route('administracion.areas.index') }}"><i class="far fa-circle fa-xs"></i> Áreas </a></li>
    </ul>
</li>
@endsection
