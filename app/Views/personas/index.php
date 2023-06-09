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
    
    <script>
      $(document).ready(function() {
        var selectedRol = $('#rol').val();
        if (selectedRol === 'empleado') {
          $('#id_cargo').show();
          $('#cargo_label').show();
        } else {
          $('#id_cargo').hide();
          $('#cargo_label').hide();
        }

        $('#rol').change(function() {
          var selectedRol = $(this).val();
          if (selectedRol === 'empleado') {
            $('#id_cargo').show();
            $('#cargo_label').show();
          } else {
            $('#id_cargo').hide();
            $('#cargo_label').hide();
          }
        });

        $('#formulario').submit(function() {
          var selectedRol = $('#rol').val();
          if (selectedRol !== 'empleado') {
            $('#id_cargo').removeAttr('name');
          }
        });
      });
    </script>
    <div class="row">
      <div class="col-12" id="accordion">
        <div class="card card-primary card-outline">
          <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
            <div class="card-header">
              <h4 class="card-title w-100">
                Filtros de busqueda <i class="fas fa-search"></i>
              </h4>
            </div>
          </a>
          <div id="collapseTwo" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <form id="formulario" method="get" action="<?= base_url('personas') ?>" class="form-inline">
                <div class="form-group">
                  <label for="estado" class="mr-2">Estado:</label>
                  <select name="estado" id="estado" class="form-control mr-2">
                    <option value="" <?php echo ($estado === '') ? 'selected' : ''; ?>>Todos</option>
                    <option value="1" <?php echo ($estado === '1') ? 'selected' : ''; ?>>Activo</option>
                    <option value="0" <?php echo ($estado === '0') ? 'selected' : ''; ?>>Inactivo</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="rol" class="mr-2">Rol:</label>
                  <select name="rol" id="rol" class="form-control mr-2">
                    <option value="" <?php echo ($rol === '') ? 'selected' : ''; ?>>Todos</option>
                    <option value="empleado" <?php echo ($rol === 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    <option value="proveedor" <?php echo ($rol === 'proveedor') ? 'selected' : ''; ?>>Proveedor</option>
                    <option value="cliente" <?php echo ($rol === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="id_cargo" id="cargo_label" class="mr-2" style="display: none;">ID Cargo:</label>
                  <select name="cargo" id="id_cargo" class="form-control mr-2" style="display: none;">
                    <option value="">Todos</option>
                    <?php if($cargos):?>
                      <?php foreach($cargos as $cargoOption):?>
                        <option value="<?=$cargoOption['id']?>" <?php if($cargoOption['id'] == $cargo) echo 'selected'; ?>>
                          <?= $cargoOption['descripcion'] ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="nombre" class="mr-2">Nombre:</label>
                  <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" class="form-control mr-2">
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