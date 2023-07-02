<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<link rel="stylesheet" href="<?= base_url('public/plugins/toastr/toastr.min.css') ?>">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Registrar Cotización</h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Cotizaciones') ?>">Cotizaciones</a></li>
              <li class="breadcrumb-item active">Crear Cotización</li>
          </ol>
        </div>
      </div>
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Formulario de Registro de Cotización</h3>
                  </div>
                  <form action="<?= base_url('guardarCotizacion') ?>" method="POST">
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
                                            <label for="numero-factura">Num. Cotizacion:</label>
                                            <input  class="form-control" type="text" id="num_cot" name="num_cot" value="<?= $num_cot ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="id_cliente">Seleccionar Cliente *</label>
                                            <select id="id_cliente" name="id_cliente" class="selectpicker form-control" data-live-search="true">
                                                <option value="">seleccione una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if(old('id_cliente') == $persona['id']) echo 'selected'; ?>><?= $persona['nombres'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="fecha_doc">Fecha emision</label>
                                            <input type="date" class="form-control" id="fecha_doc" value="<?= date('Y-m-d') ?>" name="fecha_doc" onclick="mostrarCalendario()"/>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="numero-factura">Vendedor:</label>
                                        <input  class="form-control" type="text" value="<?= $vendedor['nombres']?>" disabled>
                                        <input  type="hidden" name="id_vendedor" value="<?= $vendedor['id'] ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles de la Cotización</h3>
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
                                                    <th>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="checkAll"/>
                                                        </div>
                                                    </th>
                                                    <th>Producto</th>
                                                    <th>Stock</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>% Descuento</th>
                                                    <th>IVA</th>
                                                    <th>Subtotal</th>
                                                    <th>Total</th>
                                                </tr>
                                                <!--Primera fila de la tabla con los campos del primer producto-->
                                                <tr>

                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            
                            

                            <div class="table-responsive mb-5 card-body p-0">
                                <div class="">
                                    <button class="btn btn-danger delete" id="removeRows" type="button">Eliminar</button>
                                    <button class="btn btn-primary" id="addRows" type="button">Agregar producto</button>
                                </div>
                            </div>

                            <div class="row">  
                                <div class="col-8">           
                                    <div class="form-group">
                                        <label for="textAreaRemark">Descripcion</label>
                                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4" placeholder="Añadir detalle adicional del documento" oninput="limitarCaracteres()"><?= old('descripcion') ?></textarea>
                                        <p id="contadorCaracteres">Caracteres restantes: 250/250</p>
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
                                            <input type="text" class="form-control align-right" id="subtotal" value="<?= old('subtotal_cotizacion') ?>" name="subtotal_cotizacion" placeholder="Subtotal" >
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
                                            <input type="text" class="form-control align-right" id="val_descuento" value="<?= old('val_descuento') ?>" name="val_descuento" placeholder="Valor Descuento" >
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
                                            <input type="text" class="form-control align-right" id="iva" value="<?= old('val_iva') ?>" name="val_iva" placeholder="Valor IVA" >
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
                                            <input type="text" class="form-control align-right" id="total" value="<?= old('total') ?>" name="total" placeholder="Valor Total" >
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button class="btn btn-primary btn-block col-lg-2"  id="submitButton" type="submit">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Guardar Documento
                            </button>
                        </div>
                        <!-- Agrega un modal con el id "selectionModal" -->
                        <div class="modal fade" id="selectionModal" tabindex="-1" role="dialog" aria-labelledby="selectionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="selectionModalLabel">Mensaje de advertencia</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Por favor, seleccione al menos un producto.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Agrega un modal con el id "customModal" -->
                        <div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="customModalLabel">Mensaje de advertencia</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Debe seleccionar un producto en la fila anterior antes de agregar una nueva fila.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                  </form>
              </div>
          </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script> 

    function updatePlaceholder(input) {
        var fileName = input.files[0].name;
        document.getElementById("doc_adjunto_label").innerText = fileName;
    }

    // $('#id_producto_' + count).selectpicker(); // Inicializar el elemento selectpicker;
    function limitarCaracteres() {
    var descripcion = document.getElementById("descripcion");
    var contadorCaracteres = document.getElementById("contadorCaracteres");
    var maxCaracteres = 250;

    if (descripcion.value.length > maxCaracteres) {
        descripcion.value = descripcion.value.substr(0, maxCaracteres);
    }

    contadorCaracteres.textContent = "Caracteres restantes: " + (maxCaracteres - descripcion.value.length) + "/250";
    }
   

    function deshabilitarSelect(selectElement) {
  $(selectElement).selectpicker('refresh'); // Actualiza el selectpicker
  $(selectElement).prop('disabled', true); // Deshabilita el select
  $(selectElement).selectpicker('refresh'); // Vuelve a actualizar el selectpicker
  
  var selectedValue = $(selectElement).val(); // Obtiene el valor seleccionado
  var hiddenInputId = $(selectElement).attr('id').replace('id_productoSelect_', 'id_producto_'); // Obtiene el ID del input oculto
  
  $('#' + hiddenInputId).val(selectedValue); // Asigna el valor seleccionado al input oculto
}


document.getElementById("submitButton").addEventListener("click", function(event) {
    var selectedProducts = document.querySelectorAll('select[name="id_producto[]"]');
    var hasSelectedProduct = false;

    selectedProducts.forEach(function(select) {
        if (select.value !== "") {
            hasSelectedProduct = true;
        }
    });

    if (!hasSelectedProduct) {
        event.preventDefault();
        // Mostrar el modal con el id "selectionModal"
        $('#selectionModal').modal('show');
    }
});


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
    var lastRow = $('#facturaItems tr:last');
    var selectProducto = lastRow.find('.selectpicker');
    var idProducto = selectProducto.val();
    var numElementos = lastRow.find(':input').length;
    if (numElementos > 0 && !idProducto) {
    // Mostrar mensaje de alerta con Toastr
    toastr.warning('Debe seleccionar un producto en la fila anterior antes de agregar una nueva fila.');
    return;
}
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};



    
    count++;
    var htmlRows = '';
    htmlRows += '<tr>';
    htmlRows += '<td><input class="itemRow" type="checkbox"></td>';               
    htmlRows += '<td>';
    htmlRows += '<select class="selectpicker form-control" data-live-search="true" name="id_producto[]" id="id_productoSelect_'+count+'" aria-label="Disabled select example" required onchange="deshabilitarSelect(this)">';
    htmlRows += '<option value="" selected>Seleccionar producto</option>' + optionsHtml;
    htmlRows += '</select>';
    htmlRows += '<input type="hidden" name="id_producto[]" id="id_producto_'+count+'" />';
    htmlRows += '</td>';
    htmlRows += '<td><input type="text" name="stock_producto[]" id="stock_producto_'+count+'" class="form-control stock_producto" autocomplete="off" disabled></td>';  
    htmlRows += '<td><input type="number" name="cantidad_venta[]" id="cantidad_venta_'+count+'" class="form-control cantidad_venta" autocomplete="off" required oninput="this.value = permitirNumerosDecimales(this)" step="1" min="1"></td>';

    htmlRows += '<td><input type="text" name="precio_venta[]" id="precio_venta_'+count+'" class="form-control precio_venta" autocomplete="off" oninput="this.value = permitirNumerosDecimales(this)" readonly></td>'; 
    htmlRows += '<td><input type="text" name="descuento_item[]" id="descuento_item_'+count+'" class="form-control descuento_item" autocomplete="off" value="0" oninput="this.value = porcentajedescuento(this)"></td>'; 
    htmlRows += '<td><input type="text" name="iva_producto[]" id="iva_producto_'+count+'" class="form-control iva_producto" autocomplete="off" value="0" oninput="this.value = permitirNumerosDecimales(this)" readonly></td>'; 
    htmlRows += '<td><input type="text" name="monto_subtotal_item[]" id="monto_subtotal_item_'+count+'" class="form-control monto_subtotal_item" autocomplete="off" readonly oninput="this.value = permitirNumerosDecimales(this)"></td>'; 
    htmlRows += '<td><input type="text" name="monto_total_item[]" id="monto_total_item_'+count+'" class="form-control monto_total_item" autocomplete="off" readonly oninput="this.value = permitirNumerosDecimales(this)"></td>'; 
    htmlRows += '</tr>';
    $('#facturaItems').append(htmlRows);
    $('#id_productoSelect_' + count).selectpicker(); // Inicializar el elemento selectpicker;

    // Obtener el elemento de selección de productos de la fila dinámica
    var productoSelect = document.getElementById("id_productoSelect_" + count);
    
    // Agregar un evento de cambio al elemento de selección de productos de la fila dinámica
    productoSelect.addEventListener("change", function() {
        
        // Obtener el valor seleccionado
        var productoId = this.value;
        
        // Obtener el precio del producto seleccionado
        var precio_venta = obtenerPrecioProducto(productoId);

        
        // Asignar el precio al campo de precio unitario
        var precioUnitario = document.getElementById("precio_venta_" + count);
        precioUnitario.value = precio_venta;
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

        
        //se declara variables para obtener stock del productos
        var stock = obtenerStockProducto(productoId);
        var stockProducto = document.getElementById("stock_producto_" + count);
        stockProducto.value = stock;
        console.log(stockProducto.value);
        // asignar maximo estock
        var maxInput = document.getElementById("cantidad_venta_" + count);
        maxInput.setAttribute('max', stock);
        /*
        var maxStock = stockProducto.value;
        console.log(maxStock);
*/


        var descuento = obtenerDescuentoProducto(productoId);
        var DescuentoProducto = document.getElementById("descuento_item_" + count);
        DescuentoProducto.value = descuento;
        console.log(DescuentoProducto.value);
    });
});
    // Función para obtener el precio de un producto por su ID
    function obtenerPrecioProducto(productoId) {
        // Recorrer la lista de productos y buscar el precio del producto con el ID especificado
        var productos = <?php echo json_encode($productos); ?>;
        for (var i = 0; i < productos.length; i++) {
            if (productos[i].id == productoId) {
                return productos[i].precio_venta;
            }
        }
        return 0; // Devolver cero si no se encontró el producto
    }
    // Función para obtener el stock de un producto por su ID
    function obtenerStockProducto(productoId) {
        // Recorrer la lista de productos y buscar el stock del producto con el ID especificado
        var productos = <?php echo json_encode($productos); ?>;
        for (var i = 0; i < productos.length; i++) {
            if (productos[i].id == productoId) {
                return productos[i].stock;
            }
        }
        return 0; // Devolver cero si no se encontró el producto
    }
    // Función para obtener el Descuento de un producto por su ID
    function obtenerDescuentoProducto(productoId) {
        // Recorrer la lista de productos y buscar el Descuento del producto con el ID especificado
        var productos = <?php echo json_encode($productos); ?>;
        for (var i = 0; i < productos.length; i++) {
            if (productos[i].id == productoId) {
                return productos[i].descuento;
            }
        }
        return 0; // Devolver cero si no se encontró el producto
    }

    // Función para calcular y asignar el monto subtotal del item
    function calcularSubTotalItem(row) {
        var precioUnitario = document.getElementById("precio_venta_" + row).value;
        var descuento = document.getElementById("descuento_item_" + row).value;
        var cantidad = document.getElementById("cantidad_venta_" + row).value;
        var montoSubtotalItem = document.getElementById("monto_subtotal_item_" + row);
        montoSubtotalItem.value = (precioUnitario * cantidad)+(((precioUnitario * cantidad)*descuento)/100);
        
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
    $(document).on('keyup blur', "[id^=id_producto_]", function(){
        calculateTotal();
    });		
    $(document).on('keyup blur', "[id^=cantidad_venta_]", function(){
        calculateTotal();
    });	
    $(document).on('keyup blur', "[id^=precio_venta_]", function(){
        calculateTotal();
    });	
    $(document).on('keyup blur', "[id^=iva_producto_]", function(){		
        calculateTotal();
    });	
    $(document).on('keyup blur', "[id^=descuento_item_]", function(){		
        calculateTotal();
    });	
	$(document).on('keyup blur', "#iva", function(){		
		calculateTotal();
	});	
});	


function calculateTotal(){
	var montoSubtotal = 0; 
	var valorIvaTotal = 0; 
	var montoTotal = 0; 
	var descuentoTotal = 0; 
    var valDescuentoItem = 0;
	$("[id^='precio_venta_']").each(function() {
		var id = $(this).attr('id');
		id = id.replace("precio_venta_",'');
		var priceU = $('#precio_venta_'+id).val();
		var cantidad  = $('#cantidad_venta_'+id).val();
		var ivaItem = $('#iva_producto_'+id).val();
		var descuento = $('#descuento_item_'+id).val();
		if(!cantidad) {
			cantidad = 0;
		}

        valItvaItem = (cantidad * ivaItem) /100;

		var subtotal = (priceU*cantidad)-(((priceU*cantidad)*descuento)/100);
		$('#monto_subtotal_item_'+id).val(parseFloat(subtotal));
        valDescuentoItem += parseFloat(((priceU*cantidad)*descuento)/100);

	
		var totalItem = subtotal + ((subtotal*ivaItem)/100);
		$('#monto_total_item_'+id).val(parseFloat(totalItem));
		montoSubtotal += subtotal;
        
        valorIvaTotal += (parseFloat(ivaItem)*subtotal)/100;
	});



	$('#subtotal').val(parseFloat(montoSubtotal));	
	$('#iva').val(parseFloat(valorIvaTotal));	
	$('#val_descuento').val(parseFloat(valDescuentoItem));	

	var subtotal = $('#subtotal').val();

	if(subtotal) {
		subtotal = parseFloat(subtotal)+parseFloat(valorIvaTotal);
		$('#total').val(subtotal);		
		var total = $('#total').val();	
	}
}
</script>



<script>
    
    function porcentajedescuento(input) {
  // Obtener el valor del input
  var valor = input.value;
  
  // Eliminar todos los caracteres que no sean números ni el símbolo de punto
  var numero = valor.replace(/[^0-9.]/g, "");
  
  // Verificar si hay más de un punto decimal y eliminar los extras
  var puntosDecimales = numero.match(/\./g);
  if (puntosDecimales && puntosDecimales.length > 1) {
    numero = numero.replace(/\./g, "");
  }
  
  // Convertir el número en un valor decimal
  var porcentaje = parseFloat(numero);
  
  // Asegurarse de que el número esté en el rango de 0 a 100
  if (isNaN(porcentaje) || porcentaje < 0) {
    porcentaje = 0;
  } else if (porcentaje > 100) {
    porcentaje = 100;
  }
  
  // Devolver el porcentaje resultante
  return porcentaje;
}


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

function permitirNumerosDecimales(input) {
  // Obtener el valor del input
  var valor = input.value;
  
  // Eliminar todos los caracteres que no sean números ni puntos decimales
  var numero = valor.replace(/[^0-9.]/g, "");
  
  // Verificar si hay más de un punto decimal y eliminar los extras
  var puntosDecimales = numero.match(/\./g);
  if (puntosDecimales && puntosDecimales.length > 1) {
    numero = numero.replace(/\./g, "");
  }
  
  // Devolver el número resultante
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

<script>
    $(document).ready(function() {
        $('form').submit(function() {
            // Bloquear el botón y cambiar el texto a "Guardando"
            var submitButton = $('button[type="submit"]');
            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

            // Continuar con el envío del formulario
            return true;
        });
    });
</script>

<script src="<?= base_url('public/plugins/toastr/toastr.min.js') ?>"></script>
<?= $this->endsection() ?>