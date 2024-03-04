@extends('themes.base_home')

@section('cabecera') Dashboard Seguimiento @endsection

@section('estilos')
<style>
    .table th,
    td {
        border: 2px solid #ddd !important;


    }

    thead{
        background-color: lightgray;
    }

    .rojo {
        color: #e66363;
    }

    .verde {
        color: #63E6BE;
    }

    .azul {
        color: #74C0FC;
    }

    .grid {
        display: grid;
        height: 100%;
        grid-gap: 2px;



        grid-template:
            /* filas */
            [inicio] "header header header" 1fr
            [contenido-start] "izquierda1 contenido1 derecha1" 1fr
            "izquierda2 contenido2 derecha2" 1fr [fin] /
            /* columnas */
            [inicio] 1fr [contenido-start] 2fr 1fr [fin];
    }

    .grid>* {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.1rem;
        /* background-color: white; */
        /* outline: 3px #f2f2f2 solid; */


    }




    /* nombrar los grid area */
    .tiempo-global-area {
        grid-area: header;
        color: #337ab7;
        font-weight: bold;
        font-size: 2rem;
    }

    .indicador-semaforo:first-of-type {
        grid-area: izquierda1;
    }

    .actividad:first-of-type {
        grid-area: contenido1;
    }

    .tiempo-finalizado-actividad:first-of-type {
        grid-area: derecha1;
    }

    .tiempo-ingreso-area:first-of-type {
        grid-area: izquierda2;
    }

    .flechas:first-of-type {
        grid-area: contenido2;
    }

    .tiempo-salida-area:first-of-type {
        grid-area: derecha2;
    }
</style>
@endsection

@section('cuerpo')
<div class="contenedor">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-widget">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered" id="tablaSeguimiento" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Requerimiento</th>
                                    <th style="width: 5%">Orden</th>
                                    <th style="width: 5%">Días de entrega</th>
                                    <th style="width: 10%">Comercial</th>
                                    <th style="width: 10%">Compras</th>
                                    <th style="width: 10%">Almacén</th>
                                    <th style="width: 10%">CAS</th>
                                    <th style="width: 10%">Despacho</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation" class="text-center">
                            <ul class="pagination" id="paginadorSeguimiento">
      
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{('/js/necesidades/dashboard/SeguimientoView.js')}}?v={{filemtime(public_path('/js/necesidades/dashboard/SeguimientoView.js'))}}"></script>
    <script src="{{('/js/necesidades/dashboard/SeguimientoModel.js')}}?v={{filemtime(public_path('/js/necesidades/dashboard/SeguimientoModel.js'))}}"></script>

    <script>
        $(document).ready(function() {

            const seguimientoView = new SeguimientoView(new SeguimientoModel(token));
            seguimientoView.eventos();
            seguimientoView.listar();


            setInterval(function() {
                seguimientoView.listar();
            }, 180000); //tiempo en milisegundos  (cada 3minutos se llama a la función listar)



            

        });




    </script>
    @endsection