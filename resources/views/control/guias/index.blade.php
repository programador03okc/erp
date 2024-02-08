@extends('themes.base')
@include('layouts.menu_control')

@section('option')
@endsection

@section('cabecera')
Control de Guías de Remisión
@endsection

@section('estilos')
<link rel="stylesheet" href="{{ asset('css/usuario-accesos.css') }}">
<link rel="stylesheet" href="{{ asset('template/adminlte2-4/plugins/select2/css/select2.css') }}">

<style>
    .table .dropdown-menu {
        overflow-y: scroll;
    }

    .subrayado {
        text-decoration: underline
    }
</style>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{route('necesidades.index')}}"><i class="fas fa-tachometer-alt"></i> Control</a></li>
    <li class="active">Guías de Remisión</li>
</ol>
@endsection

@section('cuerpo')
<div class="page-main" type="lista_requerimiento_pago">

    <div class="row">
        <div class="col-md-12">
            <fieldset class="group-table">
                <div class="box box-widget">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-inbox mb-0 table-responsive" width="100%" id="tabla">
                                {{-- <thead class="table-primary"> --}}
                                <thead class="">
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Fecha</th>
                                        <th>Recepción GCI</th>
                                        <th>N° GR</th>
                                        <th>Destino</th>
                                        <th>OCAM/OC</th>
                                        <th>CDP/REQ</th>
                                        <th>Descripción</th>
                                        <th>Transportista</th>
                                        <th>FACT/GR</th>
                                        <th>GR Escaneada</th>
                                        <th>GR Cargo</th>
                                        <th width="80">Responsable</th>
                                        <th width="30">Estado</th>
                                        <th width="30">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalRegistro">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formulario" autocomplete="off">
                @csrf
                <input type="hidden" name="id" class="form-control" value="0">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Registrar nueva guía de remisión</h4>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Fecha GR:</label>
                                <input type="date" name="fecha" class="form-control text-center"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Empresas:</label>
                                        <select name="empresa_id" id="empresa_id" class="form-control form-select ">
                                            <option value="">Seleccione...</option>
                                            @foreach ($empresas as $item)
                                            <option value="{{$item->id_empresa}}">{{$item->contribuyente->razon_social}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Sede:</label>
                                        <select name="sede" class="form-control form-select select2 select-sede" required>
                                            <option value="ILO">ILO</option>
                                            <option value="LIMA">LIMA</option>
                                            <option value="MOQUEGUA">MOQUEGUA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Código GR:</label>
                                        <input type="text" name="codigo" class="form-control" placeholder="Ingrese GR" value="" required>
                                        <span class="text-red" id="span-codigo"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Tipo de movimiento:</label>
                                <select name="tipo_movimiento_id"
                                    class="form-control form-select select2 select-tipo-movimiento" required>
                                    <option value=""></option>
                                    @foreach ($tipoMovimientos as $movimiento)
                                        <option value="{{ $movimiento->id }}">{{ $movimiento->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Destino:</label>
                                <input type="text" name="destino" class="form-control"
                                    placeholder="Destino de OK COMPUTER" value="" data-change="script">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Código CDP:</label>
                                        {{-- <div class="input-group">
                                            <input type="text" name="codigo_cdp" class="form-control"
                                                placeholder="Ingrese CDP" value="">
                                            <button type="button" class="btn btn-light" id="btnBuscarCDP"><i
                                                    class="fa fa-search"></i></button>
                                        </div> --}}

                                        <div class="input-group">
                                            <input type="text" name="codigo_cdp" class="form-control"
                                                placeholder="Ingrese CDP" value="">
                                                <span class="input-group-btn">
                                                  <button type="button" class="btn btn-default" id="btnBuscarCDP"><i
                                                    class="fa fa-search"></i></button>
                                                </span>
                                          </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">OCAM/O. Virtual:</label>
                                        <input type="text" name="orden" class="form-control"
                                            placeholder="Ingrese la Orden" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">O. Física:</label>
                                        <input type="text" name="orden_virtual" class="form-control"
                                            placeholder="Ingrese la O. física" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Código REQ.:</label>
                                <input type="text" name="codigo_requerimiento" class="form-control"
                                    placeholder="Ingrese el REQ." value="">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Factura/Boleta:</label>
                                        <input type="text" name="documento" class="form-control"
                                            placeholder="Ingrese documentos" value="">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Entidad:</label>
                                        <input type="text" name="entidad" class="form-control"
                                            placeholder="Nombre de la entidad" value="" data-change="script">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Estado GR:</label>
                                <select name="estado_gr" class="form-control form-select select2 select-estado"
                                    required>
                                    <option value="NORMAL">NORMAL</option>
                                    <option value="EN BLANCO">EN BLANCO</option>
                                    <option value="ANULADA">ANULADA</option>
                                    <option value="DOC. INTERNO">DOC. INTERNO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Ingreso SoftLink:</label>
                                        <input type="date" name="fecha_ingreso" class="form-control text-center"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Procesado en SoftLink:</label>
                                    <div class="text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="procesado_softlink"
                                                name="procesado_softlink">
                                            <label class="form-check-label" for="procesado_softlink">Si</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Procesado en AGILE:</label>
                                    <div class="text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="procesado_agile"
                                                name="procesado_agile">
                                            <label class="form-check-label" for="procesado_agile">Si</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Responsable:</label>
                                <select name="id_responsable"
                                    class="form-control form-select select2 select-responsable" required>
                                    <option value=""></option>
                                    @foreach ($responsables as $responsable)
                                        <option value="{{ $responsable->id_usuario }}">{{ $responsable->nombre_corto }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Marca:</label>
                                <input type="text" name="marca" class="form-control" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Descripción de la GR:</label>
                                <textarea name="descripcion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Observaciones:</label>
                                <textarea name="observacion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalTransportista" style="overflow: scroll;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formulario-transportista" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="id_despacho" class="form-control" value="0">
                <input type="hidden" name="id_control" class="form-control" value="0">

                <input type="hidden" name="requerimiento_id" class="form-control" value="0">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar datos del Transportista</h4>
                </div>
                <div class="modal-body">
                    {{-- <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Transportista:</label>
                                <input type="text" name="transportista" class="form-control"
                                    placeholder="Nombre del transportista" value="" required>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contribuyente_id">Agencia de transporte <span
                                        class="text-red">*</span></label>
                                <select class="form-control select2-transporte select2-show-search form-select"  name="contribuyente_id" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">GR transportista:</label>
                                <input type="text" name="guia_t" class="form-control"
                                    placeholder="Ingrese la GR" value="" required>
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contribuyente_id">Guía del transportista</label>
                                <div class="input-group multiple-input-group">
                                    <input type="text" name="guia_transportista_serie" class="form-control" placeholder="Serie">
                                    <span class="input-group-text bg-default">-</span>
                                    <input type="text" name="guia_transportista_numero" class="form-control" placeholder="Número">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="">Factura transportista:</label>
                                <input type="text" name="factura_transportista" class="form-control"
                                    placeholder="Ingrese la factura" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Fecha de emisión de la guía </label>
                                <input type="date" name="fecha_emision_guia" class="form-control"  placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Fecha:</label>
                                <input type="date" name="fecha_guia_transportista"
                                    class="form-control text-center" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Flete:</label>
                                <input type="text" name="flete" class="form-control numero text-center"
                                    value="0.00">
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="importe_flete">Monto flete</label>
                                <div class="input-group multiple-input-group">
                                    <span class="input-group-text bg-default">S/.</span>
                                    <input type="number" class="form-control" id="monto_flete" name="importe_flete" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo_envio">Código de envío</label>
                                <input type="text" class="form-control" name="codigo_envio" placeholder="Código de envío">
                            </div>
                        </div>
                    </div>
                    {{-- -----------nuevos --}}
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="credito" value="true">
                                <span class="custom-control-label">Crédito</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serie_guia_venta">Guía de venta </label>
                                <div class="input-group multiple-input-group">
                                    <input type="text" name="guia_venta_serie" class="form-control"
                                         placeholder="Serie">
                                    <span class="input-group-text bg-default">-</span>
                                    <input type="text" name="guia_venta_numero" class="form-control"
                                         placeholder="Número">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha despacho real</label>
                                <input type="date" name="fecha_despacho_real" class="form-control"  placeholder="Username">
                            </div>
                        </div>
                    </div>
                    {{-- ----------------------------- --}}
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Retorno cargo GR:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cargo_guia"
                                        name="cargo_guia">
                                    <label class="form-check-label" for="cargo_guia">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Envío GR Transp.:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="envio_adjunto_guia"
                                        name="envio_adjunto_guia">
                                    <label class="form-check-label" for="envio_adjunto_guia">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Envío GR remitente:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="envio_adjunto_guia_sellada"
                                        name="envio_adjunto_guia_sellada">
                                    <label class="form-check-label" for="envio_adjunto_guia_sellada">Si</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">GR escaneada:</label>
                                <input type="file" name="adjunto_guia" class="form-control text-primary" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">GR remitente sellada:</label>
                                <input type="file" name="adjunto_guia_sellada" class="form-control text-primary">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Observaciones:</label>
                                <textarea name="observaciones" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="contribuyente_id">Vincular con código orden despacho</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="orden_depacho" class="form-control" placeholder="Ingresar Código de orde de despacho para vincular" >
                                    <button class="btn btn-light buscar-codigo-od" type="button" id="button-addon2">Buscar</button>
                                </div>
                                {{-- <select class="form-control select2-orden-despacho select2-show-search form-select"  name="od_id">
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    {{-- ------------- --}}

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalTransportistaActualizacion">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="formulario-transportista-actualizacion" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="id_despacho_act" class="form-control" value="0">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Actualización de datos del Transportista</h4>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Retorno cargo GR:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cargo_guia_act"
                                        name="cargo_guia_act">
                                    <label class="form-check-label" for="cargo_guia_act">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Envío GR Transp.:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="envio_adjunto_guia_act"
                                        name="envio_adjunto_guia_act">
                                    <label class="form-check-label" for="envio_adjunto_guia_act">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Envío GR remitente:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="envio_adjunto_guia_sellada_act" name="envio_adjunto_guia_sellada_act">
                                    <label class="form-check-label" for="envio_adjunto_guia_sellada_act">Si</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">GR remitente sellada:</label>
                                <input type="file" name="adjunto_guia_sellada_act"
                                    class="form-control text-primary" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Observaciones:</label>
                                <textarea name="observaciones" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                {{-- ----------------- --}}

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalArchivador">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formulario-archivador" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_arch" class="form-control" value="0">
                <input type="hidden" name="id_control_arch" class="form-control" value="0">
                <input type="hidden" name="id_despacho_arch" class="form-control" value="0">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Archivar Guía de Remisión</h4>

                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Estado GR física:</label>
                                <input type="text" name="estado_gr" class="form-control"
                                    placeholder="Ingrese estado físico" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Libro archivado:</label>
                                <input type="text" name="libro" class="form-control"
                                    placeholder="Ingrese nro libro archivado" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Enviado GR Control Admin:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enviado_cargo_guia"
                                        name="enviado_cargo_guia">
                                    <label class="form-check-label" for="enviado_cargo_guia">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enviado GR remitente:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enviado_guia_sellada"
                                        name="enviado_guia_sellada">
                                    <label class="form-check-label" for="enviado_guia_sellada">Si</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Enviado GR Sunat:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enviado_guia_sunat"
                                        name="enviado_guia_sunat">
                                    <label class="form-check-label" for="enviado_guia_sunat">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enviado GR destinatario:</label>
                            <div class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enviado_guia_destinatario"
                                        name="enviado_guia_destinatario">
                                    <label class="form-check-label" for="enviado_guia_destinatario">Si</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Observaciones:</label>
                                <textarea name="observaciones_arch" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalObservacion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formulario-observacion">
                @csrf
                <input type="hidden" name="id_control_obs" class="form-control" value="0">
                <input type="hidden" name="id_control_logistica" class="form-control" value="0">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar observacion</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Observaciones:</label>
                                <textarea name="comentario" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalHistorial">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tituloHistorial">Información de la GR</h4>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12" id="resultado"></div>
                </div>
                <div class="row mb-3">
                    <h6 class="fw-bolder">- Historial de la GR</h6>
                    <div class="col-md-12">
                        <table class="table table">
                            <thead class="table-primary">
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Detalle de la acción</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody id="resultado-historial"></tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <h6 class="fw-bolder">- Observaciones de la GR</h6>
                    <div class="col-md-12">
                        <table class="table table">
                            <thead class="table-primary">
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Detalle de la acción</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody id="resultado-observaciones"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modalRegistroMasivo">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="formulario-masivo" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Guía de remisión modo masivo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="form-group">
                                <label class="form-label">Código GR:</label>
                                <div class="input-group multiple-input-group ">
                                    <input type="text" name="codigo_gr" class="form-control text-center" placeholder="GR01" maxlength="4" required>
                                    <span class="input-group-text">-</span>
                                    <input type="text" name="serie_gr" id="serie_gr" class="form-control text-center" placeholder="1" maxlength="8"  required>
                                    <span class="input-group-text rounded-0">Hasta</span>
                                    <input type="number" name="hasta" class="form-control text-center" placeholder="100" required>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group margin">
                                <input type="text" name="codigo_gr" class="form-control text-center" placeholder="GR01" maxlength="4" required>
                                <span class="input-group-btn">
                                    <a class="btn btn-link">-</a>
                                </span>
                                <input type="text" name="serie_gr" id="serie_gr" class="form-control text-center" placeholder="1" maxlength="8"  required>
                                <span class="input-group-btn">
                                    <a class="btn btn-link">Hasta</a>
                                </span>
                                <input type="number" name="hasta" class="form-control text-center" placeholder="100" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade effect-flip-vertical" id="modal-despachos-externos">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Vista de despacho externo</h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" data-alert="mensaje">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-inbox mb-0 dataTable no-footer dtr-inline collapsed table-responsive" id="despachos-externos">
                            <thead>
                                <tr>
                                    <th>Cod.Req</th>
                                    <th>Tipo Requerimiento</th>
                                    <th>Fecha Fin Entrega</th>
                                    <th>Nro O/C</th>
                                    <th>Monto Total</th>
                                    <th>OC.fís/SIAF</th>
                                    <th>OCC</th>
                                    <th>Cod.CDP</th>
                                    <th>Cliente/Entidad</th>
                                    <th>Generado por</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-primary">Seleccionar</button> --}}
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade effect-flip-vertical" id="modal-filtros">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Filtros de Guias</h4>
            </div>
            {{-- <div class="modal-header">

                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div> --}}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- <label class="form-label">Empresas:</label> --}}
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="filtro-checkbox" value="empresa">
                                <span class="custom-control-label">Empresas:</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group select2-sm">
                            <select name="empresa_id" id="empresa_id" class="form-control form-select select2" data-bs-placeholder="Select Country" data-select="change" data-seccion="empresa" disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($empresas as $item)
                                <option value="{{$item->id_empresa}}">{{$item->contribuyente->razon_social}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="filtro-checkbox" value="estado">
                                <span class="custom-control-label">Estado:</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group select2-sm">
                            <select name="estado" class="form-control form-select select2" data-bs-placeholder="Select Country" data-select="change" data-seccion="estado" disabled>
                                <option value="">Seleccione...</option>
                                <option value="ALMACEN">ALMACEN</option>
                                <option value="ARCHIVADO">ARCHIVADO</option>
                                <option value="LOG. DE SALIDA">LOG. DE SALIDA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="filtro-checkbox" value="fecha">
                                <span class="custom-control-label">Fecha:</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group text-center">
                            <div class="input-group input-group-sm">
                                <input class="form-control datepicker-date text-center"  placeholder="Fecha desde" type="date" name="fecha_inicio" data-select="change" data-seccion="fecha_inicio" disabled>
                            </div>
                            <small id="helpId" class="text-muted">Fecha desde</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group text-center">
                            <div class="input-group input-group-sm">
                                <input class="form-control datepicker-date text-center"  placeholder="Fecha Hasta" type="date" name="fecha_final" data-select="change" data-seccion="fecha_final" disabled>

                            </div>
                            <small id="helpId" class="text-muted">Fecha Hasta</small>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-primary">Seleccionar</button> --}}
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="aplicar-filtros-guias">Aceptar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')


<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/bootstrap_filestyle/bootstrap-filestyle.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/datatables/extensions/Buttons/js/jszip.min.js') }}"></script>

<script src="{{ asset('template/adminlte2-4/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('template/adminlte2-4/plugins/loadingoverlay/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('js/control/guias/guias.js') }}"></script>

    <script src="{{ asset('js/control/guias/guias-model.js') }}"></script>
    <script src="{{ asset('js/control/guias/guias-view.js') }}"></script>
    <script>


        $(document).ready(function () {
            // BOOTSTRAP DATEPICKER
            // $('.datepicker-date').bootstrapdatepicker({
            //     format: "dd/mm/yyyy",
            //     viewMode: "date",
            //     // startDate: '-3d',
            //     multidate: false,
            //     // multidateSeparator: "-",
            //     language:"es"

            // })

            $(document).ready(function() {
                const view = new GuiasView(new GuiasModel(token));
                // view.listar();
                view.eventos();
            });
        });
</script>
@endsection




