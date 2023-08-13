<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cotizaciones</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
              <li class="breadcrumb-item active">Cotizaciones</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <a type="button" class="btn btn-block btn-success" href="cotizacionCrear"><i class="fas fa-plus"></i> Generar Cotización</a>
          </div>
          <div class="col2 ml-3">
            <a href="<?= base_url('exportarCotizacion/'. ($num_cot ? $num_cot : 'none') . '/' . ($clienteFiltro ? $clienteFiltro : 'none') . '/' . ($vendedorFiltro ? $vendedorFiltro : 'none') . '/' . ($fecha_inicio ? $fecha_inicio : 'none') . '/' . ($fecha_fin ? $fecha_fin : 'none') . '/' .  ($iva !== null && $iva !== '' ? ($iva != 0 ? $iva : 0) : 'none') . '/' . ($pagado ? $pagado : 'none') . '/' . ($descuento ? $descuento : 'none') . '/' .  ($estado !== null && $estado !== '' ? ($estado != 0 ? $estado : 0) : 'none') . '/' .  ($aprobado !== null && $aprobado !== '' ? ($aprobado != 0 ? $aprobado : 0) : 'none')) ?>" class="btn btn-block btn-primary">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
          </div>
        </div>
      </div><!-- /.container-fluid -->
      <div class="row container-flui">
        <div class="col-12" id="accordion">
          <div class="card card-primary card-outline">
            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
              <div class="card-header">
                <h4 class="card-title w-100">
                  Filtros de busqueda <i class="fas fa-search"></i>
                </h4>
              </div>
            </a>
            <div id="collapseTwo" class="collapse show" data-parent="#accordion">
              <div class="card-body">
                <form id="formulario" method="get" action="<?= base_url('Cotizaciones') ?>">
                  <div class="row g-3">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="num_cot" class="mr-2">Numero de Cotización:</label>
                        <input type="text" name="num_cot" id="num_cot" value="<?= $num_cot ?>" class="form-control mr-2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="clienteFiltro" class="mr-2">Cliente:</label>
                        <select id="clienteFiltro" name="clienteFiltro" class="selectpicker form-control" data-live-search="true">
                          <option value="">Todos</option>
                          <?php if($clientes):?>
                            <?php foreach($clientes as $cliente):?>
                              <option value="<?=$cliente['id']?>" <?php if($cliente['id'] == $clienteFiltro) echo 'selected'; ?>>
                                <?= $cliente['nombres'] ?>
                              </option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="vendedorFiltro" class="mr-2">Vendedor:</label>
                        <select id="vendedorFiltro" name="vendedorFiltro" class="selectpicker form-control" data-live-search="true">
                          <option value="">Todos</option>
                          <?php if($vendedores):?>
                            <?php foreach($vendedores as $vendedor):?>
                              <option value="<?=$vendedor['id']?>" <?php if($vendedor['id'] == $vendedorFiltro) echo 'selected'; ?>>
                                <?= $vendedor['nombres'] ?>
                              </option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" value="<?= $fecha_inicio ?>" name="fecha_inicio"/>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" value="<?= $fecha_fin ?>" name="fecha_fin" disabled/>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="iva" class="mr-2">% IVA:</label>
                        <select name="iva" id="iva" class="form-control mr-2">
                          <option value="" <?php echo ($iva === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="12" <?php echo ($iva == '12') ? 'selected' : ''; ?>>12%</option>
                          <option value="0" <?php echo ($iva == '0') ? 'selected' : ''; ?>>0%</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="aprobado" class="mr-2">Aprobado:</label>
                          <select name="aprobado" id="aprobado" class="form-control mr-2">
                            <option value="" <?php echo ($aprobado === '') ? 'selected' : ''; ?>>Todos</option>
                            <option value="1" <?php echo ($aprobado == '1') ? 'selected' : ''; ?>>Aprobado</option>
                            <option value="0" <?php echo ($aprobado == '0') ? 'selected' : ''; ?>>Pendiente</option>
                          </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="pagado" class="mr-2">Estado Pago:</label>
                        <select name="pagado" id="pagado" class="form-control mr-2">
                          <option value="" <?php echo ($pagado === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="Pendiente" <?php echo ($pagado == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                          <option value="Pagado" <?php echo ($pagado == 'Pagado') ? 'selected' : ''; ?>>Pagado</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="descuento" class="mr-2">Descuento:</label>
                        <select name="descuento" id="descuento" class="form-control mr-2">
                          <option value="" <?php echo ($descuento === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="Si" <?php echo ($descuento == 'Si') ? 'selected' : ''; ?>>Si</option>
                          <option value="No" <?php echo ($descuento == 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="estado" class="mr-2">Estado:</label>
                          <select name="estado" id="estado" class="form-control mr-2">
                            <option value="" <?php echo ($estado === '') ? 'selected' : ''; ?>>Todos</option>
                            <option value="1" <?php echo ($estado == '1') ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($estado == '0') ? 'selected' : ''; ?>>Anulado</option>
                          </select>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Administrar cotizaciones </h3>
              </div>
              <!-- /.card-header -->
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
                <table id="example2" class="table table-bordered table-hover  table-sm table-responsive-sm">
                  <thead>
                    <tr>
                      <th>Cliente</th>
                      <th>Vendedor</th>
                      <th>Documento</th>
                      <th>Fecha</th>
                      <th>Total</th>
                      <th>Items</th>
                      <th>Estado</th>
                      <th>Pagado</th>
                      <th>Aprobado</th>
                      <th class="col-1">Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($cotizaciones):?>
                      <?php foreach($cotizaciones as $cotizacion):?>
                          <tr> 
                            <td><?= $cotizacion['persona'];?></td>
                            <td><?= $cotizacion['vendedor'];?></td>
                            <td><a href="<?=base_url('consultarCotizacion/'.$cotizacion['id']);?>">Cotizacion #<?= $cotizacion['num_cot'];?></a></td>
                            <td><?= $cotizacion['fecha_doc'];?></td>
                            <td><?= $cotizacion['total'];?></td>
                            <td><?= $cotizacion['cantidad_registros'];?></td>
                            <td><?php echo $cotizacion['estado']==1 ? 'Activo' : 'Anulado'  ?></td>
                            <td><?php echo $cotizacion['pagado']==1 ? 'Pagado' : 'Pendiente'  ?></td>
                            <td><?php echo $cotizacion['aprobado']==1 ? 'Aprobado' : 'Pendiente'  ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" class="btn btn-warning">Acciones</button>
                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <li><a class="dropdown-item" href="<?=base_url('consultarCotizacion/'.$cotizacion['id']);?>"><i class="fas fa-eye"></i> Consultar</a></li>
                                  <li><a class="dropdown-item" href="<?=base_url('editarCotizacion/'.$cotizacion['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                  <?php 
                                    if($cotizacion['total'] != $cotizacion['valor_pagado']){
                                  ?>
                                    <li><a class="dropdown-item" href="<?=base_url('registraCobro/'.$cotizacion['id']);?>"><i class="fas fa-coins"></i> Registrar Cobro</a></li>
                                  <?php 
                                    }
                                  ?>
                                  <li><a class="dropdown-item"  data-toggle="modal" data-target="#modalDelete<?=$cotizacion['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                </div>
                              </div>
                              <div class="modal fade" id="modalDelete<?=$cotizacion['id'];?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Eliminar cotización <?=$cotizacion['num_cot'];?></h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Desea eliminar la factura de cotización <?=$cotizacion['num_cot'];?>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                      <a type="button" class="btn btn-danger" href="<?=base_url('eliminarCotizacion/'.$cotizacion['id']);?>">Eliminar Cotización</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
                <div>
                  <?php echo $paginador->links() ?>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
<script>
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            if($(this).val() == "") {
                $('#fecha_fin').prop('disabled', true);
            } else {
                $('#fecha_fin').prop('disabled', false);
            }
        });
    });
    
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            var fechaInicio = $(this).val();

            if(fechaInicio == "") {
                $('#fecha_fin').prop('disabled', true);
            } else {
                $('#fecha_fin').prop('disabled', false);
                $('#fecha_fin').prop('min', fechaInicio);
            }
        });
    });
    
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            var fechaInicio = $(this).val();

            if(fechaInicio == "") {
                $('#fecha_fin').prop('disabled', true);
                $('#fecha_fin').removeAttr('required');
            } else {
                $('#fecha_fin').prop('disabled', false);
                $('#fecha_fin').prop('min', fechaInicio);
                $('#fecha_fin').prop('required', true);
            }
        });
    });
</script>
<script>
    // Obtener el ancho de la ventana del navegador
    var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    // Verificar el ancho de la ventana y aplicar el estado colapsado o desplegado del acordeón
    if (windowWidth < 768) {
    // Pantallas pequeñas: colapsar el acordeón por defecto
    $("#collapseTwo").removeClass("show");
    } else {
    // Pantallas grandes: desplegar el acordeón por defecto
    $("#collapseTwo").addClass("show");
    }
</script>
<?= $this->endsection() ?>