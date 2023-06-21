@section('sidebar')
<li class="header">PROYECTOS</li>

<li><a href="{{ route('proyectos.index') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-stream"></i> <span>Variables de Entorno</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.variables-entorno.tipos-insumo.index') }}"><i class="far fa-circle fa-xs"></i> Tipos de Insumo </a></li>
        <li><a href="{{ route('proyectos.variables-entorno.sistemas-contrato.index') }}"><i class="far fa-circle fa-xs"></i> Sistemas de Contrato </a></li>
        <li><a href="{{ route('proyectos.variables-entorno.iu.index') }}"><i class="far fa-circle fa-xs"></i> Indices Unificados </a></li>
        <li><a href="{{ route('proyectos.variables-entorno.categorias-insumo.index') }}"><i class="far fa-circle fa-xs"></i> Categoría de Insumos </a></li>
        <li><a href="{{ route('proyectos.variables-entorno.categorias-acu.index') }}"><i class="far fa-circle fa-xs"></i> Categoría de A.C.U. </a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-layer-group"></i> <span>Catálogos</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.catalogos.insumos.index') }}"><i class="far fa-circle fa-xs"></i> Insumos </a></li>
        <li><a href="{{ route('proyectos.catalogos.nombres-cu.index') }}"><i class="far fa-circle fa-xs"></i> Nombres de A.C.U. </a></li>
        <li><a href="{{ route('proyectos.catalogos.acus.index') }}"><i class="far fa-circle fa-xs"></i> Detalle de A.C.U. </a></li>
    </ul>
</li>
{{-- <li class="treeview">
    <a href="#">
        <i class="fab fa-opera"></i> <span>Opcion Comercial</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.opciones.opciones.index') }}"><i class="far fa-circle fa-xs"></i> Opción Comercial </a></li>
        <li><a href="{{ route('proyectos.opciones.presupuestos-internos.index') }}"><i class="far fa-circle fa-xs"></i> Presupuesto Interno </a></li>
        <li><a href="{{ route('proyectos.opciones.cronogramas-internos.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma Interno </a></li>
        <li><a href="{{ route('proyectos.opciones.cronogramas-valorizados-internos.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma Val. Interno </a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-copyright"></i> <span>Propuesta</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.propuestas.propuestas-cliente.index') }}"><i class="far fa-circle fa-xs"></i> Propuesta Cliente </a></li>
        <li><a href="{{ route('proyectos.propuestas.cronogramas-cliente.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma de Propuesta </a></li>
        <li><a href="{{ route('proyectos.propuestas.cronogramas-valorizados-cliente.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma Val. Propuesta </a></li>
        <li><a href="{{ route('proyectos.propuestas.valorizaciones.index') }}"><i class="far fa-circle fa-xs"></i> Valorización </a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-file-powerpoint"></i> <span>Ejecución</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.ejecucion.proyectos.index') }}"><i class="far fa-circle fa-xs"></i> Proyectos </a></li>
        <li><a href="{{ route('proyectos.ejecucion.residentes.index') }}"><i class="far fa-circle fa-xs"></i> Residentes </a></li>
        <li><a href="{{ route('proyectos.ejecucion.presupuestos-ejecucion.index') }}"><i class="far fa-circle fa-xs"></i> Presupuesto de Ejecución </a></li>
        <li><a href="{{ route('proyectos.ejecucion.cronogramas-ejecucion.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma de Ejecución </a></li>
        <li><a href="{{ route('proyectos.ejecucion.cronogramas-valorizados-ejecucion.index') }}"><i class="far fa-circle fa-xs"></i> Cronograma Val. Ejecución </a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-chart-bar"></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.reportes.curvas.index') }}"><i class="far fa-circle fa-xs"></i> Curvas S del Proyecto </a></li>
        <li><a href="{{ route('proyectos.reportes.saldos.index') }}"><i class="far fa-circle fa-xs"></i> Saldos por Presupuesto </a></li>
        <li><a href="{{ route('proyectos.reportes.opciones-relaciones.index') }}"><i class="far fa-circle fa-xs"></i> Opciones y sus Relaciones </a></li>
        <li><a href="{{ route('logistica.gestion-logistica.reportes.compras-locales') }}"><i class="far fa-circle fa-xs"></i> Compras locales</a></li>
        <li><a href="{{ route('proyectos.reportes.cuadro-gastos.index') }}"><i class="far fa-circle fa-xs"></i>Cuadro de gastos</a></li>

    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fas fa-cog"></i> <span>Configuraciones</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('proyectos.configuraciones.estructuras.index') }}"><i class="far fa-circle fa-xs"></i> Estructura Presupuesto </a></li>
    </ul>
</li> --}}
@endsection
