
@section('sidebar')
    <li><a href="{{route('tesoreria.index')}}"><i class="fas fa-tachometer-alt"></i> <span>Tesorería</span></a></li>
    @if(Auth::user()->tieneAplicacion(133))
    <li>
        <a href="{{route('tesoreria.pagos.procesar-pago.index')}}"><i class="fas fa-file-invoice-dollar"></i>
            <span>Registro de Pagos </span>
        </a>

    </li>
    @endif
    {{-- <li class="treeview">
        <a href="#">
            <i class="fas fa-file-invoice-dollar"></i> <span> Pagos </span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{route('tesoreria.pagos.procesar-pago.index')}}"><i class="far fa-circle fa-xs"></i> Registro de Pagos </a></li>
        </ul>
    </li> --}}

    @if(Auth::user()->tieneSubModulo(44))
    <li class="treeview">
        <a href="#">
            <i class="fas fa-receipt"></i> <span>Comprobantes</span><i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu" >
            <li><a href="{{route('tesoreria.facturacion.index')}}"><i class="far fa-circle fa-xs"></i> Facturación Venta</a></li>
            <li><a href="{{route('almacen.comprobantes.lista_comprobante_compra')}}"><i class="far fa-circle fa-xs"></i> Comprobantes Compra</a></li>
            {{-- <li><a href="{{route('tesoreria.documento-compra.index')}}"><i class="far fa-circle fa-xs"></i> Crear Comp. Compra</a></li> --}}
        </ul>
    </li>
    @endif
    <li><a href="{{ route('tesoreria.tipo-cambio.index') }}"><i class="fas fa-money-bill-alt"></i> <span>Tipo de Cambio</span></a></li>
    <li><a href="{{ route('tesoreria.cierre-apertura.index') }}"><i class="fas fa-money-bill-alt"></i> <span>Cierre / Apertura</span></a></li>

@endsection
