@section('sidebar')
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MÓDULO CAS</li>
    <li><a href="{{route('cas.index')}}"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
    {{-- menu1 --}}
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cubes"></i> <span>Transformaciones</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{route('cas.customizacion.tablero-transformaciones.index')}}"> Gestión de Transformaciones </a>
            </li>
            <li>
                <a href="{{route('cas.customizacion.gestion-customizaciones.index')}}"> Lista de Transformaciones </a>
            </li>
            <li>
                <a href="{{route('cas.customizacion.hoja-transformacion.index')}}"> Orden de Transformación </a>
            </li>
        </ul>
    </li>
    {{-- menu2 --}}
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cubes"></i> <span>Garantías</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{route('cas.garantias.incidencias.index')}}"> Registro de incidencia </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.devolucionCas.index')}}"> Devolución </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.fichas.index')}}"> Gestión de incidencias </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.marca.inicio')}}"> Marca </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.modelo.inicio')}}"> Model </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.producto.inicio')}}"> Producto </a>
            </li>
        </ul>
    </li>
</ul>
@endsection
