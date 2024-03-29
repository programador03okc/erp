<div class="modal fade" tabindex="-1" role="dialog" id="modal-crear-nuevo-producto" style="overflow-y:scroll;">
    <div class="modal-dialog"  style="width: 880px;">
        <div class="modal-content">
            <form id="form-crear-nuevo-producto">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal-crear-nuevo-producto" onClick="$('#modal-crear-nuevo-producto').modal('hide');"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Crear Producto</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Categoría</h5>
                            <select class="form-control activation js-example-basic-single" name="id_categoria" required>
                                <!-- <option value="0">Elija una opción</option> -->
                                @foreach ($categorias as $cat)
                                    <option value="{{$cat->id_categoria}}">{{$cat->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <h5>Marca</h5>
                            <div style="display:flex;">
                                <select class="form-control activation js-example-basic-single" name="id_subcategoria" required>
                                    <!-- <option value="0">Elija una opción</option> -->
                                    @foreach ($subcategorias as $subcat)
                                        <option value="{{$subcat->id_subcategoria}}">{{$subcat->descripcion}}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-success" title="Agregar Marca" name="btnAddMarca" onclick="agregar_nueva_marca();"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5>Clasificación</h5>
                            <select class="form-control activation js-example-basic-single" name="id_clasif" required>
                                <!-- <option value="0">Elija una opción</option> -->
                                @foreach ($clasificaciones as $clasif)
                                    @if($clasif->descripcion == "NUEVO" || $clasif->id_clasificacion == 5)
                                        <option value="{{$clasif->id_clasificacion}}" selected="selected">{{$clasif->descripcion}}</option>
                                    @else
                                        <option value="{{$clasif->id_clasificacion}}">{{$clasif->descripcion}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Part Number</h5>
                            <input type="text" class="form-control activation" name="part_number" >
                        </div>
                        <div class="col-md-4">
                            <h5>Unidad Medida</h5>
                            <select class="form-control activation " name="id_unidad_medida" required>
                                <!-- <option value="0">Elija una opción</option> -->
                                @foreach ($unidades as $unid)
                                    @if($unid->descripcion == "Caja" || $unid->id_unidad_medida ==30 )
                                        <option value="{{$unid->id_unidad_medida}}" selected>{{$unid->descripcion}}</option>
                                    @else
                                        <option value="{{$unid->id_unidad_medida}}">{{$unid->descripcion}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Descripción</h5>
                            <textarea name="descripcion"  class="form-control activation"  cols="30" rows="10" wrap="soft" onkeyup="mayus(this);" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="submit_newProductoCreate" class="btn btn-success" value="Guardar"/>
                </div>
            </form>
        </div>
    </div>
</div>