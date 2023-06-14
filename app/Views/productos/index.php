<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Administrar Productos</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Administrar Productos</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <button type="button" class="btn btn-block btn-success" onclick='window.location.href="crearProducto"'><i class="fas fa-plus"></i> Crear Producto</button>
          </div>
          <div class="col2 ml-3">
            <a href="<?= base_url('exportarProductos/'. ($nombre ? $nombre : 'none') . '/' . ($codigo ? $codigo : 'none') . '/' .  ($estado !== null && $estado !== '' ? ($estado != 0 ? $estado : 0) : 'none') . '/' .  ($inventariable !== null && $inventariable !== '' ? ($inventariable != 0 ? $inventariable : 0) : 'none') . '/' .  ($iva !== null && $iva !== '' ? ($iva != 0 ? $iva : 0) : 'none') . '/' .  ($descuento !== null && $descuento !== '' ? ($descuento != 0 ? $descuento : 0) : 'none') . '/' . ($categoriaFiltro ? $categoriaFiltro : 'none')) ?>" class="btn btn-block btn-primary">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
          </div>
        </div>
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
                <form id="formulario" method="get" action="<?= base_url('productos') ?>">
                  <div class="row g-3">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="nombre" class="mr-2">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" class="form-control mr-2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="codigo" class="mr-2">Codigo:</label>
                        <input type="text" name="codigo" id="codigo" value="<?= $codigo ?>" class="form-control mr-2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="categoriaFiltro" class="mr-2">Categoria:</label>
                        <select id="categoriaFiltro" name="categoriaFiltro" class="selectpicker form-control" data-live-search="true">
                            <option value="">Todos</option>
                            <?php if($categorias):?>
                                <?php foreach($categorias as $categoria):?>
                                    <option value="<?=$categoria['id']?>" <?php if($categoria['id'] == $categoriaFiltro) echo 'selected'; ?>>
                                        <?= $categoria['descripcion'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="estado" class="mr-2">Estado:</label>
                        <select name="estado" id="estado" class="form-control mr-2">
                          <option value="" <?php echo ($estado === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="1" <?php echo ($estado == '1') ? 'selected' : ''; ?>>Activo</option>
                          <option value="0" <?php echo ($estado == '0') ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="inventariable" class="mr-2">Inventariable:</label>
                        <select name="inventariable" id="inventariable" class="form-control mr-2">
                          <option value="" <?php echo ($inventariable === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="1" <?php echo ($inventariable == '1') ? 'selected' : ''; ?>>Si</option>
                          <option value="0" <?php echo ($inventariable == '0') ? 'selected' : ''; ?>>No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="iva" class="mr-2">% IVA:</label>
                        <select name="iva" id="iva" class="form-control mr-2">
                          <option value="" <?php echo ($iva === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="12" <?php echo ($iva == '12') ? 'selected' : ''; ?>>12%</option>
                          <option value="0" <?php echo ($iva == '0') ? 'selected' : ''; ?>>0%</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="descuento" class="mr-2">Descuento:</label>
                        <select name="descuento" id="descuento" class="form-control mr-2">
                          <option value="" <?php echo ($descuento === '') ? 'selected' : ''; ?>>Todos</option>
                          <option value="1" <?php echo ($descuento == '1') ? 'selected' : ''; ?>>Si</option>
                          <option value="0" <?php echo ($descuento == '0') ? 'selected' : ''; ?>>No</option>
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
              <!-- /.card-header -->
              <div class="card-body">
              <?php if(session('mensaje')){?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo session('mensaje') ?>
                  </div>
              <?php }  ?> 
                <table id="example2" class="table table-bordered table-hover  table-sm table-responsive-sm" align-items: center>
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Categoria</th>
                      <th>Stock</th>
                      <th>% IVA</th>
                      <th>Estado</th>
                      <th class="col-1">Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($productos):?>
                      <?php foreach($productos as $producto):?>
                          <tr> 
                            <td><?= $producto['codigo']. ' - ' . $producto['nombre'];?></td>
                            <td><?= $producto['categoria'];?></td>
                            <?php
                              if ($producto['es_inventariable'] == '1') {
                                if ($producto['stock'] >= 10) {
                                  ?>
                                  <td class="align-middle" style="text-align: center;"><?php echo $producto['stock']; ?></td>
                                  <?php
                                } else {
                                  ?>
                                  <td class="align-middle" style="text-align: center;"><div id="alertaInventario"><?php echo $producto['stock'] . ' '; ?> <i class="fas fa-exclamation" style="color: #eb0000;"></i></div></td>
                                  <?php
                                }
                              } else {
                                ?>
                                <td class="align-middle" style="text-align: center;"><div id="noInventariable"><?php echo 'N/A'; ?></div></td>
                                <?php
                              }
                            ?>
                            <td class="align-middle" style="text-align: center;"><?= $producto['porcentaje_iva'];?>%</td>
                            <td><?php echo $producto['estado']==1 ? 'Activo' : 'Inactivo'  ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" class="btn btn-warning">Acciones</button>
                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <li><a class="dropdown-item" href="<?=base_url('editarProducto/'.$producto['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                  <li><a class="dropdown-item"  data-toggle="modal" data-target="#modalDelete<?=$producto['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                </div>
                              </div>
                            </td>
                            
                            <div class="modal fade" id="modalDelete<?=$producto['id'];?>">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h4 class="modal-title">Eliminar Producto <?=$producto['codigo'];?></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          Desea eliminar el producto <?=$producto['nombre'];?>
                                      </div>
                                      <div class="modal-footer justify-content-between">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                          <a type="button" class="btn btn-danger" href="<?=base_url('eliminarProducto/'.$producto['id']);?>">Eliminar Producto</a>
                                      </div>
                                  </div>
                              </div>
                            </div>

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
    <style>
      .tooltip {
  background-color: #28a745; /* Color de fondo verde */
  color: #ffffff; /* Color de texto blanco */
}

    </style>
    
    <script>
      // With the above scripts loaded, you can call `tippy()` with a CSS
      // selector and a `content` prop:
      tippy('#noInventariable', {
        content: 'El producto no utiliza inventario',
      });
      
      tippy('#alertaInventario', {
        content: 'Hay poco stock del producto',
      });

      
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