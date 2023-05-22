<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Administrar Personas</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Administrar Personas</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <button type="button" class="btn btn-block btn-success" onclick='window.location.href="crearPersona"'><i class="fas fa-plus"></i> Crear Persona</button>
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
                <h3 class="card-title">Gestionar información de personas </h3>
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
                      <th>Persona</th>
                      <th>Estado</th>
                      <th class="col-1">Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($personas):?>
                      <?php foreach($personas as $persona):?>
                          <tr> 
                            <td><?= $persona['nombres'];?></td>
                            <td><?php echo $persona['estado']==1 ? 'Activo' : 'Inactivo'  ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" class="btn btn-warning">Acciones</button>
                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <li><a class="dropdown-item" href="<?=base_url('editarPersona/'.$persona['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                  <li><a class="dropdown-item" href="<?=base_url('eliminarPersona/'.$persona['id']);?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
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