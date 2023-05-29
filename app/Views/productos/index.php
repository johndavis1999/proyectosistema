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
                <h3 class="card-title">Gestionar información de Productos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <?php if(session('mensaje')){?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo session('mensaje') ?>
                  </div>
              <?php }  ?> 
                <table id="example2" class="table table-bordered table-hover table-responsive-sm" align-items: center>
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
    </script>
<?= $this->endsection() ?>