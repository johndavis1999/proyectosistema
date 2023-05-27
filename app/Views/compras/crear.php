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
                                        $optionsHtml = '';
                                        foreach ($productos as $producto) {
                                            $optionsHtml .= '<option value="' . $producto['id'] . '">' . $producto['nombre'] . '</option>';
                                        }
                                    ?>
                                    <table class="table table-sm" id="facturaItems">
                                        <tr>
                                            <!--Checkbox para seleccionar todas las filas de la tabla-->
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll"/>
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
                                                <input type="number" name="precio_compra[]" id="precio_compra_1" class="form-control precio_compra" autocomplete="off" min="1" step="1" required>
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
    // $('#id_producto_' + count).selectpicker(); // Inicializar el elemento selectpicker;
</script>
  
<script>
    $(document).ready(function(){
	$(document).on('click', '#checkAll', function() {          	
		$(".itemRow").prop("checked", this.checked);
	});	
	$(document).on('click', '.itemRow', function() {  	
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});  
	var count = $(".itemRow").length;
    var optionsHtml = '<?php echo $optionsHtml; ?>';

    $(document).on('click', '#addRows', function() { 
    count++;
    var htmlRows = '';
    htmlRows += '<tr>';
    htmlRows += '<td><input class="itemRow" type="checkbox"></td>';               
    htmlRows += '<td><select class="selectpicker form-control" data-live-search="true" name="id_producto[]" id="id_producto_'+count+'" aria-label="Disabled select example" required>';
    htmlRows += '<option value="" selected>Seleccionar producto</option>' + optionsHtml;
    htmlRows += '</select></td>';             
    htmlRows += '<td><input type="number" name="factura_producto_cantidad[]" id="factura_producto_cantidad_'+count+'" class="form-control factura_producto_cantidad" autocomplete="off" required step="1" min="1"></td>';           
    htmlRows += '<td><input type="number" name="precio_compra[]" id="precio_compra_'+count+'" class="form-control precio_compra" autocomplete="off"></td>'; 
    htmlRows += '<td><input type="number" name="descuento_item[]" id="descuento_item_'+count+'" class="form-control descuento_item" autocomplete="off"></td>'; 
    htmlRows += '<td><input type="number" name="iva_producto[]" id="iva_producto_'+count+'" class="form-control iva_producto" autocomplete="off"></td>'; 
    htmlRows += '<td><input type="number" name="monto_subtotal_item[]" id="monto_subtotal_item_'+count+'" class="form-control monto_subtotal_item" autocomplete="off" readonly></td>'; 
    htmlRows += '<td><input type="number" name="monto_total_item[]" id="monto_total_item_'+count+'" class="form-control monto_total_item" autocomplete="off" readonly></td>'; 
    htmlRows += '</tr>';
    $('#facturaItems').append(htmlRows)
    $('#id_producto_' + count).selectpicker(); // Inicializar el elemento selectpicker;
    
    // Obtener el elemento de selección de productos de la fila dinámica
    var productoSelect = document.getElementById("id_producto_" + count);
    
    // Agregar un evento de cambio al elemento de selección de productos de la fila dinámica
    productoSelect.addEventListener("change", function() {
        
        // Obtener el valor seleccionado
        var productoId = this.value;
        
        // Obtener el precio del producto seleccionado
        var precio_compra = obtenerPrecioProducto(productoId);

        
        // Asignar el precio al campo de precio unitario
        var precioUnitario = document.getElementById("precio_compra_" + count);
        precioUnitario.value = precio_compra;
        subtotalProducto = precioUnitario.value;
        // Calcular y asignar el monto subtotal del item
        calcularSubTotalItem(count);
        calcularTotalItem(count);
        //get IVA
        
        // Obtener el iva del producto seleccionado
        var porcentaje_iva = obtenerIvaProducto(productoId);

        var ivaProducto = document.getElementById("iva_producto_" + count);
        ivaProducto.value = porcentaje_iva;
        console.log(ivaProducto.value);
        valIvaProducto = ivaProducto.value;
        asignarIvaItem(count);

       
    });
    
});

    // Función para obtener el precio de un producto por su ID
    function obtenerPrecioProducto(productoId) {
        // Recorrer la lista de productos y buscar el precio del producto con el ID especificado
        var productos = <?php echo json_encode($productos); ?>;
        for (var i = 0; i < productos.length; i++) {
            if (productos[i].id == productoId) {
                return productos[i].precio_compra;
            }
        }
        return 0; // Devolver cero si no se encontró el producto
    }
    // Función para calcular y asignar el monto subtotal del item
    function calcularSubTotalItem(row) {
        var precioUnitario = document.getElementById("precio_compra_" + row).value;
        var cantidad = document.getElementById("factura_producto_cantidad_" + row).value;
        var montoSubtotalItem = document.getElementById("monto_subtotal_item_" + row);
        montoSubtotalItem.value = precioUnitario * cantidad;
        
    }
    // Función para calcular y asignar el monto subtotal del item
    function calcularTotalItem(row) {
        var montoSubtotalItem = document.getElementById("monto_subtotal_item_" + row).value;
        var valorIva = document.getElementById("iva_producto_" + row).value;
        var montoTotalItem = document.getElementById("monto_total_item_" + row);
        montoTotalItem.value = montoSubtotalItem * valorIva;
        console.log(montoTotalItem.value)
    }



    // Función para obtener el iva de un producto por su ID
    function obtenerIvaProducto(productoId) {
        // Recorrer la lista de productos y buscar el iva del producto con el ID especificado
        var productos = <?php echo json_encode($productos); ?>;
        for (var i = 0; i < productos.length; i++) {
            if (productos[i].id == productoId) {
                return productos[i].porcentaje_iva;
            }
        }
        return 0; // Devolver cero si no se encontró el producto
    }

    // Función para  asignar iva
    function asignarIvaItem(row) {
        var ivaProducto = document.getElementById("iva_producto_" + row);
        var valorIva = document.getElementById("iva_producto_" + row).value;
        ivaProducto.value = valorIva;
    }










    $(document).on('click', '#removeRows', function(){
        $(".itemRow:checked").each(function() {
            $(this).closest('tr').remove();
        });
        $('#checkAll').prop('checked', false);
        calculateTotal();
    });		
    $(document).on('blur', "[id^=factura_producto_cantidad_]", function(){
        calculateTotal();
    });	
    $(document).on('blur', "[id^=precio_compra_]", function(){
        calculateTotal();
    });	
    $(document).on('blur', "#iva_producto_", function(){		
        calculateTotal();
    });	
});	


function calculateTotal(){
	var montoTotal = 0; 
	$("[id^='precio_compra_']").each(function() {
		var id = $(this).attr('id');
		id = id.replace("precio_compra_",'');
		var priceU = $('#precio_compra_'+id).val();
		var cantidad  = $('#factura_producto_cantidad_'+id).val();
		if(!cantidad) {
			cantidad = 1;
		}
		var total = priceU*cantidad;
		$('#monto_subtotal_item_'+id).val(parseFloat(total));
		montoTotal += total;			
	});
	$('#subtotal').val(parseFloat(montoTotal));	
	var iva = $("#iva").val();
	var subtotal = $('#subtotal').val();	
	if(subtotal) {
		var montoIva = subtotal*iva/100;
		subtotal = parseFloat(subtotal)+parseFloat(montoIva);
		$('#total').val(subtotal);		
		var amountPaid = $('#amountPaid').val();
		var total = $('#total').val();	
		if(amountPaid && total) {
			total = total-amountPaid;			
			$('#amountDue').val(total);
		} else {		
			$('#amountDue').val(subtotal);
		}
	}
}
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