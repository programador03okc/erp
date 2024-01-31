@extends('themes.base')
@include('layouts.menu_necesidades')

@section('option')
@endsection

@section('cabecera')
Trazabilidad de requerimiento logístico
@endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/drawflow/drawflow.min.css')}}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/drawflow/beautiful.css')}}">

@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('necesidades.index')}}"><i class="fas fa-tachometer-alt"></i> Necesidades</a></li>
    <li>Requerimientos logísticos</li>
    <li class="active">Trazabilidad de requerimiento logístico</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main">
    <div class="wrapper-drawflow">
        <div class="col-right">
            <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="bar-zoom">
                    <i class="fas fa-search-minus" onclick="editor.zoom_out()"></i>
                    <i class="fas fa-search" onclick="editor.zoom_reset()"></i>
                    <i class="fas fa-search-plus" onclick="editor.zoom_in()"></i>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@section('scripts')
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/logistica/requerimiento/trazabilidad.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/trazabilidad.js'))}}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/drawflow/drawflow.min.js') }}"></script>
<script src="{{ asset('js/logistica/requerimiento/trazabilidadView.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/trazabilidadView.js'))}}"></script>
<script src="{{ asset('js/logistica/requerimiento/trazabilidadModel.js')}}?v={{filemtime(public_path('js/logistica/requerimiento/trazabilidadModel.js'))}}"></script>

<script>
    var roles = JSON.parse('{!!$roles!!}');
    var grupos = JSON.parse('{!!$gruposUsuario!!}');
    var array_accesos = JSON.parse('{!!json_encode($array_accesos)!!}');

    
    let idRequerimientoByURL = parseInt(location.search.split('id=')[1]);
    
    if (idRequerimientoByURL > 0) {
        const trazabilidadView = new TrazabilidadView(new TrazabilidadModel('{{csrf_token()}}'));
        trazabilidadView.graficar(idRequerimientoByURL);
        vista_extendida();
    }


</script>

<script>
    var id = document.getElementById("drawflow");
    const editor = new Drawflow(id);
    editor.reroute = true;
    editor.editor_mode = 'view';
    editor.start();
</script>

@endsection