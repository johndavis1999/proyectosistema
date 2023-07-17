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
                <h1>Consultar Cobro</h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Cobro') ?>">Cobros</a></li>
              <li class="breadcrumb-item active">Consultar</li>
          </ol>
        </div>
      </div>
      <div class="" style="justify-content: center; align-items: center;">
          <div class="">
              <div class="card card-primary">
                  <form>
                    <input  type="hidden" name="id" value="<?= $cobro['id'] ?>">
                        <div class="card-body">
                            
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

                            <div>
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                        <?php 
                                                if(session('id_rol')=='1'){
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
                                            <input type="date" class="form-control" id="fecha_registro" value="<?= $cobro['fecha_registro'] ?>" name="fecha_registro" onclick="mostrarCalendario()" disabled/>
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
                                            <select id="forma_pago" id="formaPago" name="forma_pago" class="form-control" data-live-search="true" disabled>
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
                                            <select id="banco" name="id_banco" class="selectpicker form-control" data-live-search="true" disabled>
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
                                            <input class="form-control" type="text" id="num_movimiento" name="num_movimiento" value="<?= $cobro['num_movimiento'] ?>" autocomplete="off" disabled>
                                        </div>
                                        <div class="form-group" id="seccionFechaMov" style="display: none;">
                                            <label for="fecha_movimiento">Fecha de movimiento *</label>
                                            <input type="date" class="form-control" id="fecha_movimiento" value="<?= $cobro['fecha_movimiento'] ?>" name="fecha_movimiento" onclick="mostrarCalendario()" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles del Cobro</h3>
                                </div>
                                <table id="tabla" class="table">
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
                                                    <input type="text" class="form-control align-right" id="valor_pagado" value="<?= $cobro['valor_pagado'] ?>" name="valor_pagado" placeholder="Valor Total" oninput="this.value = permitirNumerosDecimales(this); validarMaximo(this)" required max="<?= $cobro['total_cotizacion'] - $cobro['valor_pagado'] ?>" disabled>
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