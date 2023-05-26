<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content') ?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Registrar Compras a Proveedores</h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Compras') ?>">Compras Proveedores</a></li>
              <li class="breadcrumb-item active">Registrar Compra</li>
          </ol>
        </div>
      </div>
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Formulario de creacion de Producto</h3>
                  </div>
                  <form action="guardarCompra" method="POST">
                        <div class="card-body">
                            <?php if(session('mensaje')){?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo session('mensaje') ?>
                                </div>
                            <?php }  ?> 
                            <div>
                                <div class="form-group">
                                    <label for="id_per_prov">Seleccionar Proveedor *</label>
                                    <select id="id_per_prov" name="id_per_prov" class="selectpicker form-control" data-live-search="true">
                                        <option value="">Escoja una persona</option>
                                        <?php if($personas):?>
                                            <?php foreach($personas as $persona):?>
                                                <option value="<?=$persona['id']?>" <?php if(old('id_per_prov') == $persona['id']) echo 'selected'; ?>><?= $persona['nombres'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label for="numero-factura">Factura:</label>
                                    <input  class="form-control" type="text" id="num_fact" name="num_fact" value="<?= old('num_fact') ?>" placeholder="xxx-xxx-xxxxxxxxx" oninput="formatearNumero(this)">
                                </div>
                                
                                <div class="form-group">
                                    <label for="autorizacion-factura">Autorizacion:</label>
                                    <input class="form-control" type="text" id="autorizacion_fact" name="autorizacion_fact" value="<?= old('autorizacion_fact') ?>" oninput="formatoAutorizacion(this)">
                                </div>

                                <div class="form-group">
                                    <label for="fecha_doc">Fecha emision</label>
                                    <input type="date" class="form-control" id="fecha_doc" value="<?= old('fecha_doc') ?>" name="fecha_doc"/>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles de la factura</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <?php
                                        //Se recorre el arreglo de productos para construir las opciones del select
                                        $optionsHtml = '';
                                        foreach ($productos as $producto) {
                                            $optionsHtml .= '<option value="' . $producto['id'] . '">' . $producto['nombre'] . ' / Stock: ' . $producto['stock'] . '</option>';
                                        }
                                    ?>
                                    <table class="table table-sm" id="ingresoItem">
                                        <tr>
                                            <!--Checkbox para seleccionar todas las filas de la tabla-->
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input itemRow" type="checkbox" id="checkAll"/>
                                                </div>
                                            </th>
                                            <th>Producto</th>    
                                            <th>Cantidad</th>  
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>IVA</th>
                                            <th>Subtotal</th>
                                            <th>Total</th>
                                        </tr>
                                        <!--Primera fila de la tabla con los campos del primer producto-->
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input itemRow" type="checkbox" id="flexCheckDefault"/>
                                                </div>
                                            </td>
                                            <td>
                                                <!--Select para seleccionar el producto recorriendo array de productos-->
                                                <select name="id_producto[]" id="id_producto_1"  class="selectpicker form-control" data-live-search="true" required>
                                                    <option value="" selected>Seleccionar producto</option>
                                                    <?php foreach ($productos as $producto): ?>
                                                        <!-- Agrega una opcion por cada elemento devuelto -->
                                                        <option value="<?php echo $producto['id']; ?>"><?php echo ' ID:' . $producto['id'] . ' - '. $producto['nombre'] . ' - Stock:' . $producto['stock']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="cantidad_ingreso[]" id="cantidad_ingreso_1" class="form-control cantidad_ingreso" autocomplete="off" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="precio[]" id="precio_1" class="form-control precio" autocomplete="off" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="descuento[]" id="descuento_1" class="form-control descuento" autocomplete="off" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="iva[]" id="iva_1" class="form-control iva" autocomplete="off" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="subtotal[]" id="subtotal_1" class="form-control subtotal" autocomplete="off" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <!--Input para ingresar la cantidad del producto-->
                                                <input type="number" name="total[]" id="total_1" class="form-control total" autocomplete="off" min="1" step="1" required>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>

                            <div class="table-responsive mb-5 card-body p-0">
                                <div class="">
                                    <button class="btn btn-danger delete" id="removeRows" type="button">Eliminar</button>
                                    <button class="btn btn-primary" id="addRows" type="button">Agregar producto</button>
                                </div>
                            </div>

                            <div>             
                                <div class="form-group">
                                    <label for="textAreaRemark">Descripcion</label>
                                    <textarea class="form-control" name="descripcion" value="<?= old('descripcion') ?>" id="descripcion" rows="4" placeholder="Añadir detalle adicional del documento"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="doc_adjunto">Documento adjunto</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="doc_adjunto" name="doc_adjunto">
                                            <label class="custom-file-label" for="doc_adjunto">Seleccionar archivo</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="subttl_iva12">Subtotal IVA 12%*</label>
                                            <input type="text" class="form-control align-right" id="subttl_iva12" value="<?= old('subttl_iva12') ?>" name="subttl_iva12" placeholder="Subtotal IVA 12%">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="subttl_iva0">Subtotal IVA 0%*</label>
                                            <input type="text" class="form-control align-right" id="subttl_iva0" value="<?= old('subttl_iva0') ?>" name="subttl_iva0" placeholder="Subtotal IVA 0%">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="val_descuento">$ Descuento*</label>
                                            <input type="text" class="form-control align-right" id="val_descuento" value="<?= old('val_descuento') ?>" name="val_descuento" placeholder="Porcentaje Descuento">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="val_iva">Valor IVA</label>
                                            <input type="text" class="form-control align-right" id="val_iva" value="<?= old('val_iva') ?>" name="val_iva" placeholder="Valor IVA">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="total">Valor Total</label>
                                            <input type="text" class="form-control align-right" id="total" value="<?= old('total') ?>" name="total" placeholder="Valor Total">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <button class="btn btn-primary btn-block col-lg-2" type="submit">Guardar Documento</button>
                            
                        </div>
                  </form>
              </div>
          </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <script>
     $(document).ready(function(){
        // evento al hacer click en el botón "checkAll"
        $(document).on('click', '#checkAll', function() {          	
            $(".itemRow").prop("checked", this.checked);
        });	
        // evento al hacer click en cualquier casilla "itemRow"
        $(document).on('click', '.itemRow', function() {  	
            // si todas las casillas "itemRow" están seleccionadas, se marca la casilla "checkAll"
            if ($('.itemRow:checked').length == $('.itemRow').length) {
                $('#checkAll').prop('checked', true);
            } else {
                // de lo contrario, se desmarca la casilla "checkAll"
                $('#checkAll').prop('checked', false);
            }
        });  
        // se obtiene la cantidad de filas con clase "itemRow"
        var count = $(".itemRow").length;
        // se obtiene el HTML con las opciones de productos
        var optionsHtml = '<?php echo $optionsHtml; ?>';

        // evento al hacer click en el botón "addRows"
        //viejo select
        /*
        $(document).on('click', '#addRows', function() { 
            count++;
            // se crea un nuevo HTML para la fila a agregar
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input class="itemRow" type="checkbox"></td>';          
            htmlRows += '<td><select class="form-select" name="id_producto[]" id="id_producto_'+count+'" aria-label="Disabled select example" required>';
            htmlRows += '<option value="" selected>Seleccionar producto</option>' + optionsHtml;
            htmlRows += '</select></td>';            
            htmlRows += '<td><input type="number" name="cantidad_ingreso[]" id="cantidad_ingreso_'+count+'" class="form-control cantidad_ingreso" autocomplete="off" min="1" step="1" required></td>';           
            htmlRows += '</tr>';
            // se agrega el HTML al final de la tabla con id "ingresoItem"
            $('#ingresoItem').append(htmlRows);
        }); 
        */
        //nuevo con esto se habilita el select picker
        $(document).on('click', '#addRows', function() { 
            count++;
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input class="itemRow" type="checkbox"></td>';          
            htmlRows += '<td><select class="selectpicker form-control" data-live-search="true" name="id_producto[]" id="id_producto_'+count+'" aria-label="Disabled select example" required>';
            htmlRows += '<option value="" selected>Seleccionar producto</option>' + optionsHtml;
            htmlRows += '</select></td>';            
            htmlRows += '<td><input type="number" name="cantidad_ingreso[]" id="cantidad_ingreso_'+count+'" class="form-control cantidad_ingreso" autocomplete="off" min="1" step="1" required></td>';   
            htmlRows += '<td><input type="number" name="precio[]" id="precio_'+count+'" class="form-control precio" autocomplete="off" min="1" step="1" required></td>';            
            htmlRows += '<td><input type="number" name="descuento[]" id="descuento_'+count+'" class="form-control descuento" autocomplete="off" min="1" step="1" required></td>';   
            htmlRows += '<td><input type="number" name="iva[]" id="iva_'+count+'" class="form-control iva" autocomplete="off" min="1" step="1" required></td>';           
            htmlRows += '<td><input type="number" name="subtotal[]" id="subtotal_'+count+'" class="form-control subtotal" autocomplete="off" min="1" step="1" required></td>';    
            htmlRows += '<td><input type="number" name="total[]" id="total_'+count+'" class="form-control total" autocomplete="off" min="1" step="1" required></td>';           
            htmlRows += '</tr>';

            $('#ingresoItem').append(htmlRows);
            $('#id_producto_' + count).selectpicker(); // Inicializar el elemento selectpicker
        });
        // evento al hacer click en el botón "removeRows"
        $(document).on('click', '#removeRows', function(){
            // se eliminan todas las filas con la clase "itemRow" seleccionadas
            $(".itemRow:checked").each(function() {
                $(this).closest('tr').remove();
            });
            // se desmarca la casilla "checkAll"
            $('#checkAll').prop('checked', false);
        });
    });	
</script>




<script>
    
    /* para usar solo permitir numeros se be usar esto en el input oninput="this.value = permitirNumeros(this)" */
function formatoAutorizacion(input) {
    var numero = permitirNumeros(input);
    var maxLength = (numero.length >= 11) ? 50 : 11;
    input.value = numero.substring(0, maxLength);
    input.maxLength = maxLength;
}

function permitirNumeros(input) {
    // Eliminar todos los caracteres que no sean números
    var numero = input.value.replace(/\D/g, "");
    return numero;
}

function formatearNumero(input) {
    var numero = permitirNumeros(input);
    // Aplicar el formato xxx-xxx-xxxxxxxxx
    var formateado = "";
    if (numero.length > 0) {
        formateado += numero.substring(0, 3);
        if (numero.length >= 4) {
            formateado += "-" + numero.substring(3, 6);
        }
        if (numero.length >= 7) {
            formateado += "-" + numero.substring(6, 15);
        }
    }
    // Establecer el valor formateado en el campo de entrada
    input.value = formateado;
}

</script>

<?= $this->endsection() ?>