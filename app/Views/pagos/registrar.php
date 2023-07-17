<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <script>
        $(document).ready(function() {
            $('#forma_pago').change(function() {
                if ($(this).val() === 'Cheque' || $(this).val() === 'Transferencia') {
                    $('#seccionBanco').show();
                    $('#seccionFechaMov').show();
                    $('#seccionMovimiento').show();
                } else {
                    $('#seccionBanco').hide();
                    $('#seccionFechaMov').hide();
                    $('#seccionMovimiento').hide();
                    $('#seccionCheque').hide();
                }
            });

            // Disparar el evento change al cargar la página
            $('#forma_pago').trigger('change');
        });
    </script>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Registrar Pago</h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Pagos') ?>">Pagos</a></li>
              <li class="breadcrumb-item active">Registrar Pago</li>
          </ol>
        </div>
      </div>
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Formulario de registro de Pago</h3>
                  </div>
                  
                  <form action="<?= base_url('guardarPago') ?>" method="POST" enctype="multipart/form-data">
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
                                            <label for="fecha_registro">Fecha de registro *</label>
                                            <input type="date" class="form-control" id="fecha_registro" value="<?= old('fecha_registro') ?>" name="fecha_registro" onclick="mostrarCalendario()"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Seleccionar Proveedor *</label>
                                            <select id="" name="" class="selectpicker form-control" data-live-search="true" disabled>
                                                <option value="">Escoja una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if($persona['id'] == $compras['id_per_prov']) echo 'selected'; ?>>
                                                            <?= $persona['nombres'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="id_proveedor" value="<?= $compras['id_per_prov']; ?>">
                                        <div class="form-group">
                                            <label for="forma_pago">Seleccionar Forma de Pago: *</label>
                                            <select id="forma_pago" id="formaPago" name="forma_pago" class="form-control" data-live-search="true">
                                                <option value="">Escoja una forma de pago</option>
                                                <option value="Efectivo" <?php if(old('forma_pago') == 'Efectivo') echo 'selected'; ?>>Efectivo</option>
                                                <option value="Transferencia" <?php if(old('forma_pago') == 'Transferencia') echo 'selected'; ?>>Transferencia</option>
                                                <option value="Cheque" <?php if(old('forma_pago') == 'Cheque') echo 'selected'; ?>>Cheque</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group" id="seccionBanco" style="display: none;">
                                            <label for="forma_pago">Seleccionar Banco: *</label>
                                            <select id="banco" name="id_banco" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Escoja un banco</option>
                                                <?php if($bancos):?>
                                                    <?php foreach($bancos as $banco):?>
                                                        <option value="<?=$banco['id']?>" <?php if(old('id_banco') == $banco['id']) echo 'selected'; ?>><?= $banco['nombre'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group" id="seccionMovimiento" style="display: none;">
                                            <label for="num_movimiento">Numero de Comprobante:</label>
                                            <input class="form-control" type="text" id="num_movimiento" name="num_movimiento" value="<?= old('num_movimiento') ?>" oninput="this.value = permitirNumeros(this)" autocomplete="off">
                                        </div>
                                        <div class="form-group" id="seccionFechaMov" style="display: none;">
                                            <label for="fecha_movimiento">Fecha de movimiento *</label>
                                            <input type="date" class="form-control" id="fecha_movimiento" value="<?= old('fecha_movimiento') ?>" name="fecha_movimiento" onclick="mostrarCalendario()"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles del Pago</h3>
                                </div>
                                <table id="tabla" class="table">
                                    <thead>
                                        <tr>
                                            <th class="col-2">Compra</th>
                                            <th class="col-2">Capital a Vencer</th>
                                            <th class="col-2">Pendiente</th>
                                            <th class="col-2">Valor a Pagar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente -->
                                        <tr>
                                            <td><?= $compras['num_fact'] ?><input type="hidden" name="id_compra" value="<?= $compras['id'] ?>"></td>
                                            <td>$<?= $compras['total'] ?><input type="hidden" name="valor_compra" value="<?= $compras['total'] ?>"></td>
                                            <td>$
                                                <?php
                                                    $valor_vencer = $compras['total'] - $compras['valor_pagado']; 
                                                    echo $valor_vencer;
                                                ?>
                                                <input type="hidden" name="valor_vencer" value="<?= $valor_vencer ?>">
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control align-right" id="valor_pagado" value="<?= old('valor_pagado') ?>" name="valor_pagado" placeholder="Valor Total" oninput="this.value = permitirNumerosDecimales(this); validarMaximo(this)" required max="<?= $compras['total'] - $compras['valor_pagado'] ?>">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">  
                                <div class="col-8">       
                                    <div class="form-group">
                                        <label for="doc_adjunto">Documento adjunto</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="doc_adjunto" name="doc_adjunto" onchange="updatePlaceholder(this)">
                                                <label class="custom-file-label" for="doc_adjunto" id="doc_adjunto_label">Seleccionar archivo</label>
                                            </div>
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
<script>
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

    function validarMaximo(input) {
        var maximo = <?= $valor_vencer ?>;
        var valorIngresado = parseFloat(input.value);
        
        if (valorIngresado <= 0) {
            input.setCustomValidity('El valor ingresado debe ser mayor a 0');
        } else if (valorIngresado > maximo) {
            input.setCustomValidity('El valor ingresado no puede ser mayor que ' + maximo);
        } else {
            input.setCustomValidity('');
        }
    }

</script>
<?= $this->endsection() ?>