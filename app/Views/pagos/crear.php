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
                                            <label for="id_proveedor">Seleccionar Proveedor *</label>
                                            <select id="id_proveedor" name="id_proveedor" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Escoja una persona</option>
                                                <?php if($personas):?>
                                                    <?php foreach($personas as $persona):?>
                                                        <option value="<?=$persona['id']?>" <?php if(old('id_proveedor') == $persona['id']) echo 'selected'; ?>><?= $persona['nombres']?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
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
                                        <div class="form-group" id="seccionCheque" style="display: none;">
                                            <label for="num_cheque">Numero de Cheque:</label>
                                            <input class="form-control" type="text" id="num_cheque" name="num_cheque" value="<?= old('num_cheque') ?>" autocomplete="off">
                                        </div>
                                        <div class="form-group" id="seccionTransferencia" style="display: none;">
                                            <label for="num_transferencia">Numero de Transferencia:</label>
                                            <input class="form-control" type="text" id="num_transferencia" name="num_transferencia" value="<?= old('num_transferencia') ?>" autocomplete="off">
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
                                        <th>ID</th>
                                        <th>Documentos</th>
                                        <th>Valor Seleccionado</th>
                                        <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Modal -->
                            <div id="modal" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Seleccionar opción</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <select name="compra_id" id="opciones" class="form-control">
                                                <option value="">Seleccionar compra</option>
                                                <?php foreach ($compras as $compra) {
                                                    if ($compra['id_per_prov'] === '1') {
                                                        ?>
                                                        <option value="<?= $compra['id'] ?>"><?= $compra['num_fact'] ?></option>
                                                        <?php
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="agregarBtn">Agregar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-5 card-body p-0">
                                <div class="">
                                    <button class="btn btn-danger delete" id="removeRows" type="button">Eliminar</button>
                                    <button class="btn btn-primary" id="agregarDocumentoBtn" type="button">Agregar Factura</button>
                                </div>
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
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valor_total">Valor Total:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control align-right" id="valor_total" value="<?= old('valor_total') ?>" name="valor_total" placeholder="Valor Total">
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
    // Obtener elementos del DOM
    const agregarDocumentoBtn = document.getElementById('agregarDocumentoBtn');
    const modal = document.getElementById('modal');
    const opcionesSelect = document.getElementById('opciones');
    const agregarBtn = document.getElementById('agregarBtn');
    const tabla = document.getElementById('tabla').getElementsByTagName('tbody')[0];
    let contador = 1;

    // Función para mostrar el modal
    agregarDocumentoBtn.addEventListener('click', () => {
      $('#modal').modal('show');
    });

    // Función para agregar una opción seleccionada a la tabla
    agregarBtn.addEventListener('click', () => {
      const opcionSeleccionada = opcionesSelect.value;
      const fila = tabla.insertRow();
      const idCelda = fila.insertCell();
      const celda = fila.insertCell();
      const valorSeleccionadoCelda = fila.insertCell();
      const accionesCelda = fila.insertCell();
      const input = document.createElement('input');
      const botonBorrar = document.createElement('button');
      input.type = 'text';
      input.value = opcionSeleccionada;
      input.disabled = true;
      botonBorrar.textContent = 'Borrar';
      botonBorrar.className = 'btn btn-danger';
      idCelda.innerHTML = contador;
      celda.innerHTML = opcionSeleccionada;
      valorSeleccionadoCelda.appendChild(input);
      accionesCelda.appendChild(botonBorrar);
      fila.id = "fila" + contador;

    botonBorrar.addEventListener('click', () => {
        const fila = botonBorrar.closest('tr');
        tabla.deleteRow(fila.rowIndex);
    });



      contador++;
      $('#modal').modal('hide');
    });
  </script>
<?= $this->endsection() ?>