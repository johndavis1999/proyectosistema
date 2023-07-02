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
            <button type="button" class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Generar PDF</button>
          </div>
        </div>
      </div><!-- /.container-fluid -->
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
                      <th>Cotización</th>
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
                            <td><?= $cotizacion['num_cot'];?></td>
                            <td><?= $cotizacion['fecha_doc'];?></td>
                            <td><?= $cotizacion['total'];?></td>
                            <td><?= $cotizacion['cantidad_registros'];?></td>
                            <td><?php echo $cotizacion['estado']==1 ? 'Activo' : 'Anulado'  ?></td>
                            <td><?php echo $cotizacion['pagado']==1 ? 'Pagado' : 'Pendiente'  ?></td>
                            <td><?php echo $cotizacion['aprobado']==1 ? 'Aprobado' : 'Rechazado'  ?></td>
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
                                    <li><a class="dropdown-item" href="<?=base_url('registraPago/'.$cotizacion['id']);?>"><i class="fas fa-coins"></i> Registrar Pago</a></li>
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
<?= $this->endsection() ?>