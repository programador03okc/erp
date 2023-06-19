@section('sidebar')
<li><a href="{{route('gerencial.index') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>

<li class="treeview">
    <a href="#">
        <i class="fas fa-book"></i> <span>Cobranzas</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{route('gerencial.cobranza.cliente') }}"><i class="far fa-circle fa-xs"></i> Clientes</a></li>
        <li><a href="{{route('gerencial.cobranza.index') }}"><i class="far fa-circle fa-xs"></i> Cobranza de Ventas</a></li>
        <li><a href="{{route('gerencial.fondos.index') }}"><i class="far fa-circle fa-xs"></i> Fondos y Auspicios</a></li>
        <li><a href="{{route('gerencial.devoluciones.index') }}"><i class="far fa-circle fa-xs"></i> Devol. de Penalidades</a></li>
    </ul>
</li>
@endsection