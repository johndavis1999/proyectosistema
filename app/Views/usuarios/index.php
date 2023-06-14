<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Administrar usuarios</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Administrar usuarios</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <button type="button" class="btn btn-block btn-success" onclick='window.location.href="UsuariosCrear"'><i class="fas fa-plus"></i> Crear usuario</button>
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
                <h3 class="card-title">Gestionar información de los usuarios creados </h3>
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
                <table id="example2" class="table table-bordered table-hover table-sm table-responsive-sm">
                  <thead>
                    <tr>
                      <th>Persona</th>
                      <th>Usuario</th>
                      <th>Rol</th>
                      <th>Estado</th>
                      <th>Imagen</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($usuarios):?>
                      <?php foreach($usuarios as $user):
                        ?>
                          <tr> 
                            <td><?= $user['persona'];?></td>
                            <td><?= $user['usuario'];?></td>
                            <td><?= $user['rol_usuario'];?></td>
                            <td><?php echo $user['estado']==1 ? 'Activo' : 'Inactivo'  ?></td>
                            <td>
                              <?php if (!empty($user['imagen']) &&  file_exists('public/users/img/'.$user['imagen'])) {?>
                                <a href="<?= base_url('public/users/img/'.$user['imagen'])?>">Ver</a>
                              <?php }else{?>
                                <a href="#">Ver</a>
                              <?php }?>
                            </td>
                            <td>
                              <div class="btn-group">
                                <button type="button" class="btn btn-warning">Acciones</button>
                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <li><a class="dropdown-item" href="<?=base_url('editarUsuario/'.$user['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                  <li><a class="dropdown-item" href="#"  data-toggle="modal" data-target="#modalReset<?=$user['id'];?>"><i class="fas fa-key"></i> Reestablecer contraseña</a></li>
                                  <li><a class="dropdown-item" href="<?=base_url('eliminarUsuario/'.$user['id']);?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                </div>
                              </div>
                              <div class="modal fade" id="modalReset<?=$user['id'];?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Reestablecer Contraseña de <?=$user['usuario'];?></h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form action="resetearPwd" method="post">
                                      <div class="modal-body">
                                        <input type="hidden" value="<?=$user['id'];?>" name="id">
                                        <div class="mb-3">
                                          <label for="password" class="form-label">Nueva contraseña</label>
                                          <input type="password" class="form-control" id="password" name="password" placeholder="Escribir contraseña nueva">
                                        </div>
                                        <div class="mb-3">
                                          <label for="repassword" class="form-label">Confirmar contraseña</label>
                                          <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Volver a escribir la nueva contraseña">
                                        </div>
                                      </div>
                                      <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-warning">
                                          <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                          Cambiar Contraseña
                                        </button>
                                      </div>
                                    </form>
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
        $('form').submit(function() {
            // Bloquear el botón y cambiar el texto a "Guardando"
            var submitButton = $('button[type="submit"]');
            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

            // Continuar con el envío del formulario
            return true;
        });
    });
</script>
<?= $this->endsection() ?>