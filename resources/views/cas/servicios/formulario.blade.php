@extends('themes.base')

@section('cabecera') Registro de incidencia @endsection
@include('layouts.menu_cas')
@section('estilos')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">
    <style>
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
    <li><a href="{{route('cas.index')}}"><i class="fa fa-tachometer"></i> Servicios CAS</a></li>
    <li>Garantías</li>
    <li class="active">@yield('cabecera')</li>
</ol>
@endsection

@section('cuerpo')

    <div class="page-main" type="incidencia">

        <form id="form-incidencia">
            <input type="hidden" name="id_servicio" value="{{($servicio?$servicio->id:'0')}}">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <label style="font-weight: bold;">Seleccione los datos del negocio:</label>
                    </div>
                </div>


                <fieldset class="group-table" id="fieldsetDatosNegocio">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Empresa</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_empresa" required>
                                            <option value="">Elija una opción</option>
                                            @foreach ($empresas as $empresa)
                                            <option value="{{$empresa->id_empresa}}"
                                                {{ ($servicio ? ($empresa->id_empresa == $servicio->id_empresa?'selected':'') :'') }}
                                            >{{$empresa->razon_social}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control  limpiarIncidencia" name="cliente_razon_social" value="{{($servicio?$servicio->cliente:'')}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Sede cliente</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="sede_cliente" value="{{($servicio?$servicio->sede_cliente:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Fecha aceptacion</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control  limpiarIncidencia" name="fecha_aceptacion"
                                            value="{{($servicio?$servicio->fecha_aceptacion:'')}}"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label">Nro Orden</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control  limpiarIncidencia" name="nro_orden" value="{{($servicio?$servicio->nro_orden:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Responsable</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_responsable" required>
                                            <option value="">Elija una opción</option>
                                            @foreach ($usuarios as $usuario)
                                            <option value="{{$usuario->id_usuario}}"
                                                {{ ($servicio ? ($usuario->id_usuario == $servicio->id_responsable?'selected':'') :'') }}
                                            >{{$usuario->nombre_corto}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Factura venta</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="factura" value="{{($servicio?$servicio->factura:'')}}" placeholder="000-0000"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label">Cód. CDP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control  limpiarIncidencia" id="codigo_oportunidad_vista" name="cdp" value="{{($servicio?$servicio->cdp:'')}}" />
                                        <div class="form-control-static limpiarTexto codigo_oportunidad d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Fecha reporte</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control  limpiarIncidencia" name="fecha_reporte" value="{{($servicio?$servicio->fecha_reporte:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Fecha registro</label>
                                    <div class="col-sm-8">
                                        <div class="form-control-static limpiarTexto fecha_registro">{{($servicio?$servicio->fecha_registro:'')}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br/>
                <div class="row" style="margin-bottom:0px">
                    <div class="col-md-12">
                        <label style="font-weight: bold;">Seleccione los datos del contacto:</label>
                    </div>
                </div>

                <fieldset class="group-table" id="fieldsetDatosContacto">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-4 control-label">Quien reporta</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="usuario_final" value="{{($servicio?$servicio->usuario_final:'')}}"  required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">

                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">Dirección</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control  limpiarIncidencia" name="direccion_contacto" value="{{($servicio?$servicio->direccion_contacto:'')}}" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label">Horario</label>
                                    <div class="col-sm-9">
                                        <div class="form-control-static limpiarTexto horario_contacto d-none"></div>
                                        <input class="form-control  limpiarIncidencia" type="text" name="horario_contacto" value="{{($servicio?$servicio->horario_contacto:'')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" style="">
                                    <label class="col-sm-4 control-label">Contacto </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="nombre_contacto" value="{{($servicio?$servicio->nombre_contacto:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">Ubigeo</label>
                                    <div class="col-sm-10" style="display:flex;">
                                        <input class="oculto" name="id_ubigeo_contacto" value="{{($servicio?$servicio->id_ubigeo_contacto:'')}}"/>
                                        <input type="text" class="form-control" name="ubigeo_contacto" value="{{$ubigeo}}" readOnly>
                                        <button type="button" class="input-group-text btn-primary " id="basic-addon1"
                                            onClick="abrirUbigeoModal('incidencia');">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label">Correo</label>
                                    <div class="col-sm-9">
                                        <div class="form-control-static limpiarTexto email_contacto d-none"><label class=""></label></div>
                                        <input class="form-control  limpiarIncidencia" type="text" name="email_contacto" value="{{($servicio?$servicio->email_contacto:'')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Cargo / Área</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="cargo_contacto" value="{{($servicio?$servicio->cargo_contacto:'')}}"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">Teléfono</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control  limpiarIncidencia" name="telefono_contacto" value="{{($servicio?$servicio->telefono_contacto:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Dias de atención</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="dias_atencion" value="{{($servicio?$servicio->dias_atencion:'')}}"  disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Region</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="region" value="{{($servicio?$servicio->region:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">Acción</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control  limpiarIncidencia" name="descripcion_accion" value="{{($servicio?$servicio->descripcion_accion:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-horizontal">
                                <div class="form-group ">
                                    {{-- <label class="col-sm-4 control-label">Ingrese la falla reportada</label> --}}
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="descripcion_accion"
                                        placeholder="Ingrese las acciones a realizar" maxlength="254">{{($servicio?$servicio->descripcion_accion:'')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <br/>
                <div class="row" style="margin-bottom:0px">
                    <div class="col-md-12">
                        <label style="font-weight: bold;">Ingrese los datos del producto:</label>
                    </div>
                </div>
                <fieldset class="group-table" id="fieldsetProductos">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group" style=";">
                                    <label class="col-sm-4 control-label">Serie </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="serie" value="{{($servicio?$servicio->serie:'')}}" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-4 control-label">Marca</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia d-none" name="marca"/>
                                        <select class="form-control select2" name="marca" required>
                                            <option value="" disabled>Elija una opción</option>
                                            @foreach ($cas_marca as $item)
                                            <option value="{{ $item->descripcion }}" {{ ($servicio ? ($item->descripcion == $servicio->marca?'selected':'') :'') }}>{{ $item->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-2 control-label">Producto</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control  limpiarIncidencia d-none" name="producto" />
                                        <select class="form-control  limpiarIncidencia select2" name="producto" required>
                                            <option value="">Elija una opción</option>
                                            @foreach ($cas_producto as $item)
                                            <option value="{{ $item->descripcion }}" {{ ($servicio ? ($item->descripcion == $servicio->producto?'selected':'') :'') }}>{{ $item->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <label class="col-sm-2 control-label">Part Number </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control  limpiarIncidencia" name="part_number" value="{{($servicio?$servicio->part_number:'')}}" required/>
                                    </div>
                                </div>
                                {{-- <div class="form-group" >
                                    <label class="col-sm-2 control-label">Tipo</label>
                                    <div class="col-sm-10">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia" name="id_tipo" required>
                                            <option value="">Elija una opción</option>
                                            @foreach ($tiposProducto as $tp)
                                            <option value="{{$tp->id_tipo}}" {{ ($servicio ? ($tp->id_tipo == $servicio->id_tipo?'selected':'') :'') }}>{{$tp->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group" >
                                    <label class="col-sm-3 control-label">Modelo</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control  limpiarIncidencia d-none" name="modelo"/>
                                        <select class="form-control  limpiarIncidencia select2" name="modelo" required>
                                            <option value="">Elija una opción</option>
                                            @foreach ($cas_modelo as $item)
                                            <option value="{{ $item->descripcion }}" {{ ($servicio ? ($item->descripcion == $servicio->modelo?'selected':'') :'') }}>{{ $item->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <br/>
                <div class="row" style="margin-bottom:0px">
                    <div class="col-md-12">
                        <label style="font-weight: bold;">Ingrese los datos de la avería:</label>
                    </div>
                </div>
                <fieldset class="group-table" id="fieldsetFallaReportada">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Tipo de falla</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_tipo_falla">
                                            <option value="">Elija una opción</option>
                                            @foreach ($tipoFallas as $falla)
                                            <option value="{{$falla->id_tipo_falla}}" {{ ($servicio ? ($falla->id_tipo_falla == $servicio->id_tipo_falla?'selected':'') :'') }}>{{$falla->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Tipo de servicio</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_tipo_servicio">
                                            <option value="">Elija una opción</option>
                                            @foreach ($tipoServicios as $tpservicio)
                                            <option value="{{$tpservicio->id_tipo_servicio}}" {{ ($servicio ? ($tpservicio->id_tipo_servicio == $servicio->id_tipo_servicio?'selected':'') :'') }}
                                                >{{$tpservicio->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-4 control-label">Equipo operativo</label>
                                    <div class="col-sm-8">
                                        <label>
                                            <input type="checkbox" name="equipo_operativo" class="flat-red" {{ ($servicio ? (true == $servicio->equipo_operativo?'checked':'') :'') }}  >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Modo</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_modo">
                                            <option value="">Elija una opción</option>
                                            @foreach ($modos as $modo)
                                            <option value="{{$modo->id_modo}}" {{ ($servicio ? ($modo->id_modo == $servicio->id_modo?'selected':'') :'') }}
                                                >{{$modo->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Medio reporte</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_medio">
                                            <option value="">Elija una opción</option>
                                            @foreach ($medios as $medio)
                                            <option value="{{$medio->id_medio}}"{{ ($servicio ? ($medio->id_medio == $servicio->id_medio?'selected':'') :'') }}
                                                >{{$medio->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Conformidad</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="conformidad">
                                            <option value="">Elija una opción</option>
                                            <option value="PRE" {{ ($servicio ? ('PRE' == $servicio->conformidad?'selected':'') :'') }}>PRE</option>
                                            <option value="POST" {{ ($servicio ? ('POST' == $servicio->conformidad?'selected':'') :'') }}>POST</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">

                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Tipo garantía</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_tipo_garantia">
                                            <option value="">Elija una opción</option>
                                            @foreach ($tiposGarantia as $tipo)
                                            <option value="{{$tipo->id_tipo_garantia}}"
                                                {{ ($servicio ? ($tipo->id_tipo_garantia == $servicio->id_tipo_garantia?'selected':'') :'') }}
                                                >{{$tipo->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Atiende</label>
                                    <div class="col-sm-8">
                                        <select class="form-control js-example-basic-single  limpiarIncidencia"
                                            name="id_atiende">
                                            <option value="">Elija una opción</option>
                                            @foreach ($atiende as $at)
                                            <option value="{{$at->id_atiende}}"
                                                {{ ($servicio ? ($at->id_atiende == $servicio->id_atiende?'selected':'') :'') }}
                                            >{{$at->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Nro. de caso</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control  limpiarIncidencia" name="numero_caso" value="{{($servicio?$servicio->numero_caso:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Versión BIOS / Firmware actual</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="bios_actual" value="{{($servicio?$servicio->bios_actual:'')}}" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Versión BIOS / Firmware actualizada</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="bios_actualizada" value="{{($servicio?$servicio->bios_actualizada:'')}}" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-4 control-label">Estado de Servicio</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="estado_servicio" value="{{($servicio?$servicio->estado_servicio:'')}}" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label">¿Daños físicos detectados?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="fisico_detectado" class="flat-green" {{ ($servicio ? (true == $servicio->fisico_detectado?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label">¿Tornillos, tapas, jefes, accesorios completos?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="accesorios_completos" class="flat-green" {{ ($servicio ? (true == $servicio->accesorios_completos?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label">¿Se encuentra correctamente ensamblado?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="ensamblado" class="flat-green" {{ ($servicio ? (true == $servicio->ensamblado?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label"> ¿Presenta signos de desgaste?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="desgaste" class="flat-green" {{ ($servicio ? (true == $servicio->desgaste?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label"> ¿Presenta signos de golpes?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="golpes" class="flat-green" {{ ($servicio ? (true == $servicio->golpes?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label">¿El equipo se encuentra limpio?</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="equipo_limpo" class="flat-green" {{ ($servicio ? (true == $servicio->equipo_limpo?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-8 control-label">¿El equipo presenta indicios de manipulación o daños, ya sean internos o externos, que puedan ser considerados como causa de exclusión de garantía?</label>
                                    <div class="col-sm-4">
                                        <label>
                                            <input type="checkbox" name="manipulacion_danos" class="flat-green" {{ ($servicio ? (true == $servicio->manipulacion_danos?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-horizontal">
                                <div class="form-group " style="">
                                    <label class="col-sm-6 control-label">Verifica Boletines / Tips</label>
                                    <div class="col-sm-6">
                                        <label>
                                            <input type="checkbox" name="boletines" class="flat-green" {{ ($servicio ? (true == $servicio->boletines?'checked':'') :'') }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label class=" control-label">Hora de llegada</label>
                                <div class="">
                                    <input type="time" class="form-control" name="hora_llegada" value="{{($servicio?$servicio->hora_llegada:'')}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label class=" control-label">Hora de Inicio</label>
                                <div class="">
                                    <input type="time" class="form-control" name="hora_inicio" value="{{($servicio?$servicio->hora_inicio:'')}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label class=" control-label">Hora de Fin</label>
                                <div class="">
                                    <input type="time" class="form-control" name="hora_fin" value="{{($servicio?$servicio->hora_fin:'')}}" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-horizontal">
                                <div class="form-group " style="margin-bottom:0px;">
                                    {{-- <label class="col-sm-4 control-label">Ingrese la falla reportada</label> --}}
                                    <div class="col-sm-12">
                                        <textarea class="form-control  limpiarIncidencia" name="falla_reportada"
                                        placeholder="Ingrese la falla reportada" required>{{($servicio?$servicio->falla_reportada:'')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br/>
                <div class="row" style="margin-bottom:0px">
                    <div class="col-md-12">
                        <label style="font-weight: bold;">Cierre de la incidencia:</label>
                    </div>
                </div>
                <fieldset class="group-table" id="fieldsetComentariosCierre">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-6 control-label">Costo del servicio contratado (S/)</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control  limpiarIncidencia" name="importe_gastado" value="{{($servicio?$servicio->importe_gastado:'70')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-horizontal">
                                <div class="form-group " style=";">
                                    <label class="col-sm-6 control-label">Parte reemplazada</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control  limpiarReporte" name="parte_reemplazada" value="{{($servicio?$servicio->parte_reemplazada:'')}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-horizontal">
                                <div class="form-group " style="margin-bottom:0px;">
                                    {{-- <label class="col-sm-4 control-label">Ingrese la falla reportada</label> --}}
                                    <div class="col-sm-12">
                                        <textarea class="form-control  limpiarIncidencia" name="comentarios_cierre"
                                        placeholder="Ingrese los comentarios del cierre" required>{{($servicio?$servicio->comentarios_cierre:'')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    @include('publico.ubigeoModal')
    <!-- modal -->
@endsection

@section('scripts')

    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>


    <script src="{{ asset('js/publico/ubigeoModal.js?')}}?v={{filemtime(public_path('js/publico/ubigeoModal.js'))}}"></script>
    <script src="{{ asset('js/cas/servicios/servicio-model.js')}}"></script>
    <script src="{{ asset('js/cas/servicios/servicio-view.js')}}"></script>
    <script>
        function abrirUbigeoModal(origen) {
                ubigeoOrigen = origen;
                console.log(ubigeoOrigen);
                ubigeoModal();
            }
        $(document).ready(function() {

            $(".select2").select2();
            const view = new ServicioView(new ServicioModel(token));
            view.eventos();
        });
    </script>
@endsection

