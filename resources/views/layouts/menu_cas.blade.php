@section('sidebar')
{{-- <ul class="sidebar-menu" data-widget="tree"> --}}
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
                <a href="{{route('cas.customizacion.tablero-transformaciones.index')}}"><i class="far fa-circle fa-xs"></i> Gestión de Transformaciones </a>
            </li>
            <li>
                <a href="{{route('cas.customizacion.gestion-customizaciones.index')}}"><i class="far fa-circle fa-xs"></i> Lista de Transformaciones </a>
            </li>
            <li>
                <a href="{{route('cas.customizacion.hoja-transformacion.index')}}"><i class="far fa-circle fa-xs"></i> Orden de Transformación </a>
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
                <a href="{{route('cas.garantias.incidencias.index')}}"><i class="far fa-circle fa-xs"></i> Registro de incidencia </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.devolucionCas.index')}}"><i class="far fa-circle fa-xs"></i> Devolución </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.fichas.index')}}"><i class="far fa-circle fa-xs"></i> Gestión de incidencias </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.marca.inicio')}}"><i class="far fa-circle fa-xs"></i> Marca </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.modelo.inicio')}}"><i class="far fa-circle fa-xs"></i> Model </a>
            </li>
            <li>
                <a href="{{route('cas.garantias.producto.inicio')}}"><i class="far fa-circle fa-xs"></i> Producto </a>
            </li>
        </ul>
    </li>
    {{-- menu2 --}}
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cubes"></i> <span>Servicios</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{route('cas.servicios.lista')}}"><i class="far fa-circle fa-xs"></i> Lista </a>
            </li>
        </ul>
    </li>
{{-- </ul> --}}
@endsection
