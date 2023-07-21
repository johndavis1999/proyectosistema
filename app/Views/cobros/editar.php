<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <script>
        $(document).ready(function() {
            $('#forma_pago').change(function() {
                if ($(this).val() === 'Cheque' || $(this).val() === 'Transferencia') {
                    $('#seccionBanco').show();
                    $('#seccionFechaMov').show();
                    $('#seccionMovimiento').show();
                    $('#validar').show();
                } else {
                    $('#seccionBanco').hide();
                    $('#seccionFechaMov').hide();
                    $('#seccionMovimiento').hide();
                    $('#validar').hide();
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
                <h1>Editar Cobro</h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Cobro') ?>">Cobro</a></li>
              <li class="breadcrumb-item active">Editar</li>
          </ol>
        </div>
      </div>
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Formulario de Edición de Cobro</h3>
                  </div>
                  
                  <form action="<?= base_url('actualizarCobro') ?>" method="POST" enctype="multipart/form-data">
                  
                    <input  type="hidden" name="id" value="<?= $cobro['id'] ?>">
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
                                        <?php 
                                                if(session('id_rol')=='1'){
                                                ?>
                                                    <div class="form-group">
                                                        <label for="numero-factura">Vendedor:</label>
                                                        <select id="id_vendedor" name="id_vendedor" class="selectpicker form-control" data-live-search="true">
                                                            <option value="">seleccione un vendedor</option>
                                                            <?php if($personas):?>
                                                                <?php foreach($personas as $persona):?>
                                                                    <option value="<?=$persona['id']?>" <?php if($persona['id'] == $cobro['id_vendedor']) echo 'selected'; ?>>
                                                                        <?= $persona['nombres'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                <?php 
                                                }else{
                                                ?>
                                                    <div class="form-group">
                                                        <label for="numero-factura">Vendedor:</label>
                                                        <select id="id_vendedor" name="id_vendedor" class="selectpicker form-control" data-live-search="true" disabled>
                                                            <option value="">seleccione un vendedor</option>
                                                            <?php if($personas):?>
                                                                <?php foreach($personas as $persona):?>
                                                                    <option value="<?=$persona['id']?>" <?php if($persona['id'] == $cobro['id_vendedor']) echo 'selected'; ?>>
                                                                        <?= $persona['nombres'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input  type="hidden" name="id_vendedor" value="<?= $cobro['id_vendedor'] ?>">
                                                    </div>
                                                <?php 
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="fecha_registro">Fecha de registro *</label>
                                            <input type="date" class="form-control" id="fecha_registro" value="<?= $cobro['fecha_registro'] ?>" name="fecha_registro" onclick="mostrarCalendario()"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Seleccionar Cliente *</label>
                                            <select id="" name="" class="selectpicker form-control" data-live-search="true" disabled>
                                                <option value="">Escoja una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if($cobro['id_cliente'] == $persona['id']) echo 'selected'; ?>><?= $persona['nombres']?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="forma_pago">Seleccionar Forma de Pago: *</label>
                                            <select id="forma_pago" id="formaPago" name="forma_pago" class="form-control" data-live-search="true">
                                                <option value="">Escoja una forma de pago</option>
                                                <option value="Efectivo" <?php if($cobro['forma_pago'] == 'Efectivo') echo 'selected'; ?>>Efectivo</option>
                                                <option value="Transferencia" <?php if($cobro['forma_pago'] == 'Transferencia') echo 'selected'; ?>>Transferencia</option>
                                                <option value="Cheque" <?php if($cobro['forma_pago'] == 'Cheque') echo 'selected'; ?>>Cheque</option>
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
                                                        <option value="<?=$banco['id']?>" <?php if($cobro['id_banco'] == $banco['id']) echo 'selected'; ?>><?= $banco['nombre'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group" id="seccionMovimiento" style="display: none;">
                                            <label for="num_movimiento">Numero de Comprobante:</label>
                                            <input class="form-control" type="text" id="num_movimiento" name="num_movimiento" value="<?= $cobro['num_movimiento'] ?>" oninput="this.value = permitirNumeros(this)" autocomplete="off">
                                        </div>
                                        <div class="form-group" id="seccionFechaMov" style="display: none;">
                                            <label for="fecha_movimiento">Fecha de movimiento *</label>
                                            <input type="date" class="form-control" id="fecha_movimiento" value="<?= $cobro['fecha_movimiento'] ?>" name="fecha_movimiento" onclick="mostrarCalendario()"/>
                                        </div>
                                        <div class="form-check" id="validar" style="display: none;">
                                            <input class="form-check-input" name="omitir_validar_mov" type="checkbox" value="1" <?= $cobro['omitir_validar_mov'] ? 'checked' : '' ?> id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                            Omitir validación de movimiento
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles del Cobro</h3>
                                </div>
                                <table id="tabla" class="table table-bordered table-hover  table-sm table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="col-2">Documento</th>
                                            <th class="col-2">Capital a Vencer</th>
                                            <th class="col-2">Pendiente</th>
                                            <th class="col-2">Valor a Pagar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente -->
                                        <tr>
                                            <td>Cotización #<?= $cobro['cotizacion'] ?></td>
                                            <td>$<?= $cobro['valor_cotizacion'] ?></td>
                                            <td>$
                                                <?php
                                                    $valor_vencer = $cobro['total_cotizacion'] - $cobro['pagado']; 
                                                    echo $valor_vencer;
                                                    $valor_vencer = $cobro['valor_cotizacion'] - $cobro['pagado'] + $cobro['valor_pagado'];
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
                                                    <input type="text" class="form-control align-right" id="valor_pagado" value="<?= $cobro['valor_pagado'] ?>" name="valor_pagado" placeholder="Valor Total" oninput="this.value = permitirNumerosDecimales(this); validarMaximo(this)" required max="<?= $cobro['total_cotizacion'] - $cobro['valor_pagado'] ?>">
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
                                        <div class="mt-3">
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
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-block col-lg-2"  id="submitButton" type="submit">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Guardar Documento
                            </button>
                        </div>
                  </form>
              </div>
          </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    function permitirNumeros(input) {
        // Eliminar todos los caracteres que no sean números
        var numero = input.value.replace(/\D/g, "");
        return numero;
    }

</script>
<?= $this->endsection() ?>