<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <script>
        $(document).ready(function() {
            $('#forma_pago').change(function() {
                if ($(this).val() === 'Cheque' || $(this).val() === 'Transferencia') {
                    $('#seccionBanco').show();
                    $('#seccionFechaMov').show();
                    if($(this).val() === 'Cheque'){
                        $('#seccionCheque').show();
                        $('#seccionTransferencia').hide();
                    }
                    else if($(this).val() === 'Transferencia'){
                        $('#seccionTransferencia').show();
                        $('#seccionCheque').hide();
                    }
                } else {
                    $('#seccionBanco').hide();
                    $('#seccionFechaMov').hide();
                    $('#seccionTransferencia').hide();
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
                <h1>Consultar Pago #<?= $pago['id'];?></h1>
          </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('Pagos') ?>">Pagos</a></li>
              <li class="breadcrumb-item active">Consultar</li>
          </ol>
        </div>
      </div>
      <div class="row mb-4 mt-3">
          <div class="col2">
            <a class="btn btn-block btn-primary" href="<?=base_url('editarPago/'.$pago['id']);?>"><i class="fas fa-edit"></i> Editar Pago</a>
          </div>
          <div class="col2 ml-3">
            <button type="button" class="btn btn-block btn-success" onclick='window.location.href="#"'><i class="fas fa-file-pdf"></i> Generar PDF</button>
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
                  <form>
                        <div class="card-body">
                            <div>
                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group">
                                            <label for="fecha_registro">Fecha de registro *</label>
                                            <input type="date" class="form-control" id="fecha_registro" value="<?= $pago['fecha_registro'] ?>" name="fecha_registro" onclick="mostrarCalendario()" disabled />
                                        </div>
                                        <div class="form-group">
                                            <label for="id_proveedor">Seleccionar Proveedor *</label>
                                            <select id="id_proveedor" name="id_proveedor" class="selectpicker form-control" data-live-search="true" disabled>
                                                <option value="">Escoja una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if($pago['id_proveedor'] == $persona['id']) echo 'selected'; ?>><?= $persona['nombres']?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="forma_pago">Seleccionar Forma de Pago: *</label>
                                            <select id="forma_pago" id="formaPago" name="forma_pago" class="form-control" data-live-search="true" disabled>
                                                <option value="">Escoja una forma de pago</option>
                                                <option value="Efectivo" <?php if($pago['forma_pago'] == 'Efectivo') echo 'selected'; ?>>Efectivo</option>
                                                <option value="Transferencia" <?php if($pago['forma_pago'] == 'Transferencia') echo 'selected'; ?>>Transferencia</option>
                                                <option value="Cheque" <?php if($pago['forma_pago'] == 'Cheque') echo 'selected'; ?>>Cheque</option>
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
                                                        <option value="<?=$banco['id']?>" <?php if($pago['id_banco'] == $banco['id']) echo 'selected'; ?>><?= $banco['nombre'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group" id="seccionCheque" style="display: none;">
                                            <label for="num_cheque">Numero de Cheque:</label>
                                            <input class="form-control" type="text" id="num_cheque" name="num_cheque" value="<?= $pago['num_cheque'] ?>" autocomplete="off" disabled>
                                        </div>
                                        <div class="form-group" id="seccionTransferencia" style="display: none;">
                                            <label for="num_transferencia">Numero de Transferencia:</label>
                                            <input class="form-control" type="text" id="num_transferencia" name="num_transferencia" value="<?= $pago['num_transferencia'] ?>" autocomplete="off" disabled>
                                        </div>
                                        <div class="form-group" id="seccionFechaMov" style="display: none;">
                                            <label for="fecha_movimiento">Fecha de movimiento *</label>
                                            <input type="date" class="form-control" id="fecha_movimiento" value="<?= $pago['fecha_movimiento'] ?>" name="fecha_movimiento" onclick="mostrarCalendario()" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles del Pago</h3>
                                </div>
                                <table id="tabla" class="table table-bordered table-hover  table-sm table-responsive-sm">
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
                                            <td><?= $pago['compra'] ?></td>
                                            <td>$<?= $pago['valor_compra'] ?></td>
                                            <td>$
                                                <?php
                                                    $valor_saldo = $pago['total_compra'] - $pago['pagado']; 
                                                    echo $valor_saldo;
                                                    $valor_vencer = $pago['total_compra'] - $pago['pagado'] + $pago['valor_pagado']
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
                                                    <input type="text" class="form-control align-right" id="valor_pagado" value="<?= $pago['valor_pagado'] ?>" name="valor_pagado" placeholder="Valor Total" oninput="this.value = permitirNumerosDecimales(this); validarMaximo(this)" required disabled>
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
                                        <div>
                                            <?php if (!empty($pago['doc_adjunto']) &&  file_exists($pago['doc_adjunto'])) {?>
                                                <a class="btn btn-primary" href="<?= base_url($pago['doc_adjunto']) ?>" download>
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
<?= $this->endsection() ?>