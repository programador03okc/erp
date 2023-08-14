@extends('themes.base')
@include('layouts.menu_config')

@section('cabecera') Gestión de Usuarios @endsection

@section('estilos')
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('configuracion.dashboard')}}"><i class="fas fa-tachometer-alt"></i> Configuraciones</a></li>
    <li>Usuarios</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="usuarios">
    <legend class="mylegend">
        <h2>Usuarios</h2>
        <ol class="breadcrumb">
            <li>

            </li>
        </ol>
    </legend>

    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Lista de usuarios</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="mytable table table-striped table-condensed table-bordered" id="listaUsuarios">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="12%">Nombre</th>
                                <th>Usuario</th>
                                <th>Clave</th>
                                <th>Email</th>
                                <th>Fecha Registro</th>
                                <th width="12%">Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-agregarUsuario">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <form class="formularioUsu" type="register" id="formPage">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Formulario de Usuarios</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>DATOS PERSONALES</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nro_documento">N° de documento</label>
                                <input type="number" class="form-control dni-unico" id="nro_documento" name="nro_documento" required>
                                <small id="emailHelp" class="form-text text-muted">N° de documento de identificación.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres">Nombres:</label>
                                <input type="text" class="form-control usuario-unico" id="nombres" name="nombres" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido_paterno">Apellido Paterno : </label>
                                <input type="text" class="form-control usuario-unico" id="apellido_paterno" name="apellido_paterno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido_materno">Apellido Materno : </label>
                                <input type="text" class="form-control usuario-unico" id="apellido_materno" name="apellido_materno" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de nacimiento : </label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="sexo">Sexo : </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo" id="inlineRadio1" value="M" required>
                                        <label class="form-check-label" for="inlineRadio1">Masculino</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo" id="inlineRadio2" value="F" required>
                                        <label class="form-check-label" for="inlineRadio2">Femenino</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Telefono : </label>
                                <input type="number" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Direccion : </label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pais">Pais : </label>
                                <select id="pais" class="form-control" name="pais" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($pais as $item)
                                        <option value="{{$item->id_pais}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ubigeo">Ubigeo : </label>
                                <input type="text" class="form-control" id="ubigeo" name="ubigeo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tipo_trabajador">Tipo trabajador : </label>
                                <select id="id_tipo_trabajador" class="form-control" name="id_tipo_trabajador" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($tipo_trabajador as $item)
                                        <option value="{{$item->id_tipo_trabajador}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_categoria_ocupacional">Categoria ocupacional : </label>
                                <select id="id_categoria_ocupacional" class="form-control" name="id_categoria_ocupacional" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($categoria_ocupacional as $item)
                                        <option value="{{$item->id_categoria_ocupacional}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tipo_planilla">Tipo de planilla : </label>
                                <select id="id_tipo_planilla" class="form-control" name="id_tipo_planilla" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($tipo_planilla as $item)
                                        <option value="{{$item->id_tipo_planilla}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hijos">Hijos : </label>
                                <input type="number" class="form-control" id="hijos" name="hijos" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>CREDENCIALES DE USUARIO</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="nombre_corto">Nombre corto : </label>
                                        <input type="text" class="form-control" id="nombre_corto" name="nombre_corto" required>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="nombre_largo">Nombre largo : </label>
                                        <input type="text" class="form-control" id="nombre_largo" name="nombre_largo" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usuario">Usuario : </label>
                                <input type="text" class="form-control usuario-unico" id="usuario" name="usuario" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_grupo">Grupo : </label>
                                <select id="id_grupo" class="selectpicker" name="id_grupo[]" data-live-search="true" data-width="100%" data-actions-box="true" multiple data-size="10" required>
                                    @foreach ($grupo as $item)
                                        <option value="{{$item->id_grupo}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_rol">Rol : </label>
                                <select id="id_rol" class="selectpicker" name="id_rol[]" data-live-search="true" data-width="100%" data-actions-box="true" multiple data-size="10" required>
                                    @foreach ($rol as $item)
                                        <option value="{{$item->id_rol}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Accesos -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-accesos">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <form id="formAccess">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Accesos por Usuario</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id_acceso">
                        <div class="col-md-12">
                            <input type="hidden" name="id_usuario">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Seleccione un rol</h5>
                                    <select class="form-control input-sm" name="role" id="role">
                                        <option value="0" selected disabled></option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <h5>Seleccione un módulo</h5>
                                    <select class="form-control input-sm" name="modulo" id="modulo" onchange="cargarAplicaciones(this.value);">
                                        <option value="0" selected disabled>Elija una opción</option>
                                        @foreach ($modulos as $modulos)
                                            <option value="{{$modulos->id_modulo}}">{{$modulos->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 text-right">
                                    <h5><i>Seleccionar todos</i></h5>
                                    <input type="checkbox" name="todos" id="todos">
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <div class="row" id="domAccess"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" onClick="guardarAcceso();">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_cambio_clave">
    <div class="modal-dialog modal-sm">
        <form action="" data-form="cambio-clave">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Cambio de clave</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id_usuario" />
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="nueva_clave">Nueva clave : </label>
                                <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="repetir_clave">Repetir clave : </label>
                                <input type="password" class="form-control" id="repetir_clave" name="repetir_clave" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-secundary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-sm btn-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_accesos">
    <div class="modal-dialog" style="width: 50%;">
        <form action="" data-form="cambio-clave">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Asignacion de accesos</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id_usuario" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Accesos</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                                    <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>

                                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="modulos">Modulos : </label>
                                                    <select id="modulos" class="form-control" name="modulos" data-select="modulos-select" required>
                                                        @foreach ($modulos_padre as $modulos)
                                                            <option value="{{$modulos->id_modulo}}">{{$modulos->descripcion}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="card" style="border: #b1aaaa 1px solid;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center" data-accesos="accesos">
                                                                Accesos
                                                            </div>
                                                        </div>

                                                    </div>
                                                  </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <div class="form-group">
                                                    <button><</button>
                                                    <button>></button>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card" style="border: #b1aaaa 1px solid;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center" data-accesos="select-accesos">
                                                                Accesos asignados.
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        The European languages are members of the same family. Their separate existence is a myth.
                                        For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                                        in their grammar, their pronunciation and their most common words. Everyone realizes why a
                                        new common language would be desirable: one could refuse to pay expensive translators. To
                                        achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                                        words. If several languages coalesce, the grammar of the resulting language is more simple
                                        and regular than that of the individual languages.
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                        Lorem Ipsum has been the industry standard dummy text ever since the 1500s,
                                        when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                        It has survived not only five centuries, but also the leap into electronic typesetting,
                                        remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                                        sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                                        like Aldus PageMaker including versions of Lorem Ipsum.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-secundary" type="button"  data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-sm btn-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-editar-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" data-form="actualizar-usuario">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="title-detalle_nota_lanzamiento">Editar Usuario</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control icd-okc" name="id_usuario" />
                    <div class="row">
                        <div class="col-md-12">
                            <h4>DATOS PERSONALES</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nro_documento_modificar">N° de documento</label>
                                <input type="number" class="form-control" id="nro_documento_modificar" name="nro_documento" required>
                                <small id="emailHelp" class="form-text text-muted">N° de documento de identificación.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres_modificar">Nombres:</label>
                                <input type="text" class="form-control" id="nombres_modificar" name="nombres" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido_paterno_modificar">Apellido Paterno : </label>
                                <input type="text" class="form-control" id="apellido_paterno_modificar" name="apellido_paterno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido_materno_modificar">Apellido Materno : </label>
                                <input type="text" class="form-control" id="apellido_materno_modificar" name="apellido_materno" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento_modificar">Fecha de nacimiento : </label>
                                <input type="date" class="form-control" id="fecha_nacimiento_modificar" name="fecha_nacimiento" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="sexo">Sexo : </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo" id="inlineRadio1_modificar" value="M" required>
                                        <label class="form-check-label" for="inlineRadio1_modificar">Masculino</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo" id="inlineRadio2_modificar" value="F" required>
                                        <label class="form-check-label" for="inlineRadio2_modificar">Femenino</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefon_modificaro">Telefono : </label>
                                <input type="number" class="form-control" id="telefono_modificar" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion_modificar">Direccion : </label>
                                <input type="text" class="form-control" id="direccion_modificar" name="direccion">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_modificar">Email : </label>
                                <input type="email" class="form-control" id="email_modificar" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pais_modificar">Pais : </label>
                                <select id="pais_modificar" class="form-control" name="pais" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($pais as $item)
                                        <option value="{{$item->id_pais}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ubigeo_modificar">Ubigeo : </label>
                                <input type="text" class="form-control" id="ubigeo_modificar" name="ubigeo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tipo_trabajador_modificar">Tipo trabajador : </label>
                                <select id="id_tipo_trabajador_modificar" class="form-control" name="id_tipo_trabajador" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($tipo_trabajador as $item)
                                        <option value="{{$item->id_tipo_trabajador}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_categoria_ocupaciona_modificar">Categoria ocupacional : </label>
                                <select id="id_categoria_ocupacional_modificar" class="form-control" name="id_categoria_ocupacional" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($categoria_ocupacional as $item)
                                        <option value="{{$item->id_categoria_ocupacional}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tipo_planilla_modificar">Tipo de planilla : </label>
                                <select id="id_tipo_planilla_modificar" class="form-control" name="id_tipo_planilla" required>
                                    <option value="" >Seleccione...</option>
                                    @foreach ($tipo_planilla as $item)
                                        <option value="{{$item->id_tipo_planilla}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hijos_modificar">Hijos : </label>
                                <input type="number" class="form-control" id="hijos_modificar" name="hijos">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>CREDENCIALES DE USUARIO</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="nombre_corto_modificar">Nombre corto : </label>
                                        <input type="text" class="form-control" id="nombre_corto_modificar" name="nombre_corto" required>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="nombre_largo_modificar">Nombre largo : </label>
                                        <input type="text" class="form-control" id="nombre_largo_modificar" name="nombre_largo" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usuario_modificar">Usuario : </label>
                                <input type="text" class="form-control" id="usuario_modificar" name="usuario" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_grupo_modificar">Grupo : </label>
                                <select id="id_grupo_modificar" class="selectpicker" name="id_grupo[]" data-live-search="true" data-width="100%" data-actions-box="true" multiple data-size="10" required>
                                    @foreach ($grupo as $item)
                                        <option value="{{$item->id_grupo}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_rol_modificar">Rol : </label>
                                <select id="id_rol_modificar" class="selectpicker" name="id_rol[]" data-live-search="true" data-width="100%" data-actions-box="true" multiple data-size="10" required>
                                    @foreach ($roles as $item)
                                        <option value="{{$item->id_rol}}" >{{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_division_modificar">Division : </label>
                                <select id="id_division_modificar" class="selectpicker" name="id_division[]" data-live-search="true" data-width="100%" data-actions-box="true" multiple data-size="10" required>
                                    @foreach ($divisiones as $item)
                                        <option value="{{$item->id_division}}" >{{$item->descripcion_grupo}} - {{$item->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" type="submit" >Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('proyectos.residentes.trabajadorModal')
{{-- @include('configuracion.modal_editar_usuario') --}}

@include('configuracion.modal_asignar_accesos')

@endsection
@section('scripts')
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}"></script>

    <script src="{{ asset('js/configuracion/usuario.js') }}"></script>
    <script src="{{ asset('js/configuracion/modal_asignar_accesos.js') }}"></script>
    <script src="{{ asset('js/proyectos/residentes/trabajadorModal.js') }}"></script>
@endsection
