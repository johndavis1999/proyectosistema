<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<link rel="stylesheet" href="<?= base_url('public/plugins/toastr/toastr.min.css') ?>">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Compras a Proveedores</h1>
          </div>
        
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('Compras') ?>">Compras Proveedores</a></li>
                    <li class="breadcrumb-item active">Consultar Compra</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4 mt-3">
          <div class="col2">
            <a class="btn btn-block btn-primary" href="<?=base_url('editarCompra/'.$compra['id']);?>"><i class="fas fa-edit"></i> Editar Documento</a>
          </div>
          <div class="col2 ml-3">
            <button type="button" class="btn btn-block btn-success" onclick='window.location.href="UsuariosCrear"'><i class="fas fa-plus"></i> Ver PDF Documento</button>
          </div>
          <?php 
            if($compra['total'] != $compra['valor_pagado']){
        ?>
            <div class="col2 ml-3">
            <a type="button" class="btn btn-block btn-success" href="<?=base_url('registraPago/'.$compra['id']);?>"><i class="fas fa-coins"></i> Registrar Pago</a>
            </div>
        <?php 
            }
          ?>
        </div>
      </div>
        <?php if(session('mensaje')){?>
            <div class="alert alert-danger" role="alert">
                <?php echo session('mensaje') ?>
            </div>
        <?php }  ?> 
        <?php if(session('exito')){?>
            <div class="alert alert-success" role="alert">
                <?php echo session('exito') ?>
            </div>
        <?php }  ?> 
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Compra N. <?= $compra['id'] ?> </h3>
                  </div>
                  <form>
                        <div class="card-body">
                            <?php if(session('mensaje')){?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo session('mensaje') ?>
                                </div>
                            <?php }  ?> 
                            <div>
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="id_per_prov">Proveedor:</label>
                                            <select id="id_per_prov" name="id_per_prov" class="selectpicker form-control" data-live-search="true" disabled>
                                                <option value="">Escoja una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if($persona['id'] == $compra['id_per_prov']) echo 'selected'; ?>>
                                                            <?= $persona['nombres'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="numero-factura">Factura:</label>
                                            <input  class="form-control" type="text" id="num_fact" name="num_fact" value="<?= $compra['num_fact'] ?>" placeholder="xxx-xxx-xxxxxxxxx" oninput="formatearNumero(this)" autocomplete="off" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="estado">Estado:</label>
                                            <select id="estado" name="estado" class="form-control" disabled>
                                                <option value="">Escoja un estado</option>
                                                <option value="1" <?php if($compra['estado'] == "1") echo 'selected'; ?>>
                                                    Activo
                                                </option>
                                                <option value="0" <?php if($compra['estado'] == "0") echo 'selected'; ?>>
                                                    Anulado
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="autorizacion-factura">Autorizacion:</label>
                                            <input class="form-control" type="text" id="autorizacion_fact" name="autorizacion_fact" value="<?= $compra['autorizacion_fact'] ?>" oninput="formatoAutorizacion(this)" autocomplete="off" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="fecha_doc">Fecha emision</label>
                                            <input type="date" class="form-control" id="fecha_doc" value="<?= $compra['fecha_doc'] ?>" name="fecha_doc" onclick="mostrarCalendario()" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-lg d-lg-none">
                                        <!-- Contenido del segundo col -->
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles de la factura</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <?php
                                    $optionsHtml = '';
                                    foreach ($productos as $producto) {
                                        $optionsHtml .= '<option value="' . $producto['id'] . '">' . $producto['nombre'] . '</option>';
                                    }
                                    ?>
                                    <div class="tablaForm">
                                        <div class="table-wrapper">
                                            <table class="table table-responsive-sm" id="facturaItems" style="width: 100%;">
                                                <tr>
                                                    <!--Checkbox para seleccionar todas las filas de la tabla-->
                                                    <!--
                                                    <th>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="checkAll"/>
                                                        </div>
                                                    </th>
                                                    -->
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>% Descuento</th>
                                                    <th>IVA</th>
                                                    <th>Subtotal</th>
                                                    <th>Total</th>
                                                </tr>
                                                <!--Primera fila de la tabla con los campos del primer producto-->
                                                <?php
                                                    // Iteramos a través de la lista de elementos de la orden
                                                    
                                                    $count = 0;
                                                    foreach ($detalles as $detalle) {
                                                        $count++;
                                                        $id_producto = $detalle["producto"];
                                                        $cantidad_compra = $detalle["cantidad"];
                                                        $precio_compra = $detalle["precio"];
                                                        $descuento_item = $detalle["descuento"];
                                                        $iva_producto = $detalle["iva"];
                                                        $monto_subtotal_item = $detalle["subtotal"];
                                                        $monto_total_item = $detalle["total"];
                                                ?>
                                                <tr>
                                                    <!--<td><input class="itemRow" type="checkbox"></td>-->
                                                    <td>
                                                        <!-- Creamos un menú desplegable para seleccionar el producto -->
                                                        <select class="selectpicker form-control" data-live-search="true" name="id_producto[]" id="id_productoSelect_'+count+'" aria-label="Disabled select example" required onchange="deshabilitarSelect(this)" disabled>
                                                            <option value="" selected>Seleccionar producto</option>
                                                            <!-- Iteramos a través de la lista de productos y creamos una opción para cada uno -->
                                                            <?php foreach ($productos as $producto): ?>
                                                            <option value="<?php echo $producto['id']; ?>" <?php if ($id_producto == $producto['id']) { echo 'selected'; } ?>><?php echo $producto['nombre']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" value="<?= $cantidad_compra ?>" name="cantidad_compra[]" id="cantidad_compra_<?php echo $count; ?>" class="form-control cantidad_compra" disabled></td>
                                                    <td><input type="text" value="<?= $precio_compra ?>" name="precio_compra[]" id="precio_compra_<?php echo $count; ?>" class="form-control precio_compra" disabled></td>
                                                    <td><input type="text" value="<?= $descuento_item ?>" name="descuento_item[]" id="descuento_item_<?php echo $count; ?>" class="form-control descuento_item" disabled></td>
                                                    <td><input type="text" value="<?= $iva_producto ?>" name="iva_producto[]" id="iva_producto_<?php echo $count; ?>" class="form-control iva_producto" disabled></td>
                                                    <td><input type="text" value="<?= $monto_subtotal_item ?>" name="monto_subtotal_item[]" id="monto_subtotal_item_<?php echo $count; ?>" class="form-control monto_subtotal_item" disabled></td>
                                                    <td><input type="text" value="<?= $monto_total_item ?>" name="monto_total_item[]" id="monto_total_item_<?php echo $count; ?>" class="form-control monto_total_item" disabled></td>
                                                </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            
                            
                            <!-- 
                            <div class="table-responsive mb-5 card-body p-0">
                                <div class="">
                                    <button class="btn btn-danger delete" id="removeRows" type="button">Eliminar</button>
                                    <button class="btn btn-primary" id="addRows" type="button">Agregar producto</button>
                                </div>
                            </div>
                            -->

                            <div class="row">  
                                <div class="col-8">           
                                    <div class="form-group">
                                        <label for="textAreaRemark">Descripcion</label>
                                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4" placeholder="Añadir detalle adicional del documento" oninput="limitarCaracteres()" disabled><?= $compra['descripcion'] ?></textarea>
                                        <!--<p id="contadorCaracteres">Caracteres restantes: 250/250</p>-->
                                    </div>
                                    <div class="form-group">
                                        <label for="doc_adjunto">Documento adjunto:</label>
                                        <div>
                                            <?php if (!empty($compra['doc_adjunto']) &&  file_exists($compra['doc_adjunto'])) {?>
                                                <a class="btn btn-primary" href="<?= base_url($compra['doc_adjunto']) ?>" download>
                                                    Descargar documento adjunto
                                                </a>
                                            <?php }else{?>
                                                <a class="btn btn-danger delete" href="#">
                                                    No existe documento adjunto
                                                </a>
                                            <?php }?>
                                        </div>

                                        <!-- 
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="doc_adjunto" name="doc_adjunto" onchange="updatePlaceholder(this)">
                                                <label class="custom-file-label" for="doc_adjunto" id="doc_adjunto_label">Seleccionar archivo</label>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="subtotal">Subtotal:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control align-right" id="subtotal" value="<?= $compra['subtotal_compra'] ?>" name="subtotal_compra" placeholder="Subtotal" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="val_descuento">Descuento:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control align-right" id="val_descuento" value="<?= $compra['val_descuento'] ?>" name="val_descuento" placeholder="Valor Descuento" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="val_iva">Valor IVA:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control align-right" id="iva" value="<?= $compra['val_iva'] ?>" name="val_iva" placeholder="Valor IVA" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total">Valor Total:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control align-right" id="total" value="<?= $compra['total'] ?>" name="total" placeholder="Valor Total" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- 
                            <button class="btn btn-primary btn-block col-lg-2"  id="submitButton" type="submit">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Guardar Documento
                            </button>
                            -->
                        </div>
                  </form>
              </div>
          </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<?= $this->endsection() ?>