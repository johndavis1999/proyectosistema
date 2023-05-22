<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Administrar Caegorias</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Administrar Caegorias</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modal-default"><i class="fas fa-plus"></i> Crear Caegoria</button>
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
                <h3 class="card-title">Gestionar información de Caegorias</h3>
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
                      <th>Categoria</th>
                      <th>Estado</th>
                      <th class="col-1">Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($categorias):?>
                      <?php foreach($categorias as $categoria):?>
                          <tr> 
                            <td><?= $categoria['descripcion'];?></td>
                            <td><?php echo $categoria['estado']==1 ? 'Activo' : 'Inactivo'  ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" class="btn btn-warning">Acciones</button>
                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <li><a class="dropdown-item" data-toggle="modal" data-target="#modalEditar<?=$categoria['id'];?>"><i class="fas fa-edit"></i> Editar</a></li>
                                  <li><a class="dropdown-item" data-toggle="modal" data-target="#modalDelete<?=$categoria['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                </div>
                                <div class="modal fade" id="modalDelete<?=$categoria['id'];?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Eliminar Categoria <?=$categoria['id'];?></h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Desea eliminar la categoria <?=$categoria['descripcion'];?>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                <a type="button" class="btn btn-danger" href="<?=base_url('eliminarCategoria/'.$categoria['id']);?>">Eliminar Categoría</a>
                                            </div>
                                        </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                              </div>
                            </td>
                            
                            <div class="modal fade" id="modalEditar<?=$categoria['id'];?>">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Editar Categoria <?=$categoria['id'];?></h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form action="actualizarCategoria" method="post">
                                    <div class="modal-body">
                                      <input type="hidden" value="<?=$categoria['id'];?>" name="id">
                                      <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción de la categoría</label>
                                        <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?=$categoria['descripcion'];?>" placeholder="Categoria del producto">
                                      </div>
                                      <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                          <input type="checkbox" class="custom-control-input" value="1" <?= $categoria['estado'] == "1" ? 'checked' : '' ?> name="estado" id="estado<?=$categoria['id'];?>">
                                          <label class="custom-control-label" for="estado<?=$categoria['id'];?>">Activo</label>

                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
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
    
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Crear Nueva Categoria</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="guardarCategoria" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la categoría</label>
                        <input autofocus type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Categoria del producto">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Categoria</button>
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
<?= $this->endsection() ?>