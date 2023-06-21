@section('sidebar')
    <li><a href="{{route('power-bi.ventas.index')}}"><i class="fa fa-file-invoice-dollar"></i> <span>Ventas</span></a></li>
    <li><a href="{{route('power-bi.cobranzas.index')}}"><i class="fa fa-receipt"></i> <span>Cobranzas</span></a></li>
    <li><a href="{{route('power-bi.inventario.index')}}"><i class="fa fa-cubes"></i> <span>Inventario</span></a></li>
@endsection
