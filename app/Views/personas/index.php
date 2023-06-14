<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    
    <script>
      $(document).ready(function() {
        var selectedRol = $('#rol').val();
        if (selectedRol === 'empleado') {
          $('#id_cargo').show();
          $('#cargo_label').show();
          $('#seccionCargo').show();
        } else {
          $('#id_cargo').hide();
          $('#cargo_label').hide();
          $('#seccionCargo').hide();
        }

        $('#rol').change(function() {
          var selectedRol = $(this).val();
          if (selectedRol === 'empleado') {
            $('#id_cargo').show();
            $('#cargo_label').show();
            $('#seccionCargo').show();
          } else {
            $('#id_cargo').hide();
            $('#cargo_label').hide();
            $('#seccionCargo').hide();
          }
        });
      });
    </script>
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
          <button type="button" class="btn btn-block btn-success" onclick='window.location.href="crearPersona"'><i class="fas fa-user-plus"></i> Crear Persona</button>
        </div>
        <div class="col2 ml-3">
          <a href="<?= base_url('exportar/' .  ($estado !== null && $estado !== '' ? ($estado != 0 ? $estado : 0) : 'none') . '/' . ($rol ? $rol : 'none') . '/' . ($nombre ? $nombre : 'none') . '/' . ($cargo ? $cargo : 'none') . '/' . ($extranjero !== null && $extranjero !== '' ? ($extranjero != 0 ? $extranjero : 0) : 'none') ) ?>" class="btn btn-block btn-primary">
              <i class="fas fa-file-excel"></i> Exportar Excel
          </a>
        </div>
      </div>
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
          <div id="collapseTwo" class="collapse show" data-parent="#accordion">
            <div class="card-body">
              <form id="formulario" method="get" action="<?= base_url('personas') ?>">
                <div class="row">
                  <div class="col-5">
                    <div class="form-group">
                      <label for="nombre" class="mr-2">Nombre:</label>
                      <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" class="form-control mr-2">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="estado" class="mr-2">Estado:</label>
                      <select name="estado" id="estado" class="form-control mr-2">
                        <option value="" <?php echo ($estado === '') ? 'selected' : ''; ?>>Todos</option>
                        <option value="1" <?php echo ($estado == '1') ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($estado == '0') ? 'selected' : ''; ?>>Inactivo</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="extranjero" class="mr-2">Extranjero:</label>
                      <select name="extranjero" id="extranjero" class="form-control mr-2">
                        <option value="" <?php echo ($extranjero === '') ? 'selected' : ''; ?>>Todos</option>
                        <option value="0" <?php echo ($extranjero == '0') ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo ($extranjero == '1') ? 'selected' : ''; ?>>Si</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="rol" class="mr-2">Rol:</label>
                      <select name="rol" id="rol" class="form-control mr-2">
                        <option value="" <?php echo ($rol === '') ? 'selected' : ''; ?>>Todos</option>
                        <option value="empleado" <?php echo ($rol === 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                        <option value="proveedor" <?php echo ($rol === 'proveedor') ? 'selected' : ''; ?>>Proveedor</option>
                        <option value="cliente" <?php echo ($rol === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                      </select>
                    </div>
                  </div>
                  <div class="col" id="seccionCargo">
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
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!--
            <div class="card-header">
              <h3 class="card-title">x Resultados Encontrados</h3>
            </div>
                -->
            <!-- /.card-header -->
            <div class="card-body">
            <?php if(session('mensaje')){?>
              <div class="alert alert-danger" role="alert">
                <?php echo session('mensaje') ?>
              </div>
            <?php }  ?> 
              <table id="example2" class="table table-bordered table-hover table-sm table-responsive-sm">
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