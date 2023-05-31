<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content') ?>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
              <h1>Compras Proveedores</h1>
        </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
              <li class="breadcrumb-item active">Compras Proveedores</li>
          </ol>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col2">
          <button type="button" class="btn btn-block btn-success" onclick='window.location.href="comprasCrear"'><i class="fas fa-plus"></i> Registrar compra</button>
        </div>
        <div class="col2 ml-3">
          <button type="button" class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Exportar PDF</button>
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
              <h3 class="card-title">Gestionar facturas de compras</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <?php if(session('mensaje')){?>
                <div class="alert alert-danger" role="alert">
                    <?php echo session('mensaje') ?>
                </div>
            <?php }  ?> 
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Proveedor</th>
                    <th>Documento</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Items</th>
                    <th>Estado</th>
                    <th class="col-1">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($compras):?>
                    <?php foreach($compras as $compra):?>
                        <tr> 
                          <td><?= $compra['persona'];?></td>
                          <td><?= $compra['num_fact'];?></td>
                          <td><?= $compra['fecha_doc'];?></td>
                          <td><?= $compra['total'];?></td>
                          <td><?= $compra['cantidad_registros'];?></td>
                          <td><?php echo $compra['estado']==1 ? 'Activo' : 'Inactivo'  ?></td>
                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-warning">Acciones</button>
                              <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="<?=base_url('editarPersona/'.$compra['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                <li><a class="dropdown-item"  data-toggle="modal" data-target="#modalDelete<?=$compra['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                              </div>
                            </div>
                            <div class="modal fade" id="modalDelete<?=$compra['id'];?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">Eliminar Compra <?=$compra['num_fact'];?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    Desea eliminar la factura de compra <?=$compra['num_fact'];?>
                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <a type="button" class="btn btn-danger" href="<?=base_url('eliminarCompra/'.$compra['id']);?>">Eliminar Categoría</a>
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