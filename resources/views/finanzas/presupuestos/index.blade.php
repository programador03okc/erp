
@extends('themes.base')

@section('cabecera') Lista de Presupuestos @endsection
@include('layouts.menu_finanzas')
@section('estilos')
{{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script> --}}

    <style>
        .lbl-codigo:hover{
            color:#007bff !important;
            cursor:pointer;
        }
        .invisible{
            display: none;
        }
	.d-none{
	    display: none;
    	}
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('finanzas.index')}}"><i class="fa fa-usd"></i> Finanzas</a></li>
    <li class="active"> @yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Datos Generales</h3>
            <div class="box-tools pull-right">
                {{-- <button type="button" data-toggle="modal" data-target="actualizarPartidas"
                    title="Actualizar descripcion de partidas" class="btn btn-box-tool btn-sm btn-info"
                    onClick="actualizarPartidas();">
                    <i class="fas fa-sync-alt"></i>
                </button> --}}
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="mytable table table-condensed table-bordered table-okc-view"
                        id="listaPresupuestos">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th scope="col">Código</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Fecha Emisión</th>
                                <th scope="col">Activo</th>
                                <th scope="col">Empresa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presupuestos as $presup)
                            <tr>
                                <td hidden>{{ $presup->id_presup }}</td>
                                <td><label class="lbl-codigo" title="Abrir Presupuesto" onClick="abrirPresupuesto('{{ $presup->id_presup }}')">{{ $presup->codigo }}</label></td>
                                <td>{{ $presup->descripcion }}</td>
                                <td>{{ $presup->fecha_emision }}</td>
                                <td> <span class="pull-right badge bg-{{ ($presup->activo=='t'?'green':'red') }}">{{ ($presup->activo=='t'?'activo':'inactivo') }}</span></td>
                                <td>{{ $presup->empresa->contribuyente->razon_social }}</td>
                            </tr>
                            @empty
                                <tr><td colSpan="6">No hay registros para mostrar</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.checkboxes.min.js') }}"></script> --}}
    <script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>


    <script>
        $(document).ready(function () {



            var vardataTables = funcDatatables();

            $('#listaPresupuestos').DataTable({
                'dom': vardataTables[1],
                'buttons': vardataTables[2],
                'language' : vardataTables[0],
                'destroy' : true,
            });
        });

        function abrirPresupuesto(id){
            console.log('abrirPresupuesto()');
            localStorage.setItem("id_presup",id);
            location.assign("/finanzas/presupuesto/create");
        }

        function actualizarPartidas()
        {
            $.ajax({
                type: 'GET',
                url: 'actualizarPartidas',
                dataType: 'JSON',
                success: function(response){
                    console.log(response);
                    alert("Se actualizaron correctamente las descripciones");
                }
            }).fail( function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            });
        }

    </script>

@endsection
