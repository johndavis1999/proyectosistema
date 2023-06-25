<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pagos</h1>
                </div>
            
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                        <li class="breadcrumb-item active">Pagos</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col2 ml-3">
                    <a href="<?= base_url('exportarProductos/') ?>" class="btn btn-block btn-primary">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
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
                                        <th># Pago</th>
                                        <th>Proveedor</th>
                                        <th>Fecha de registro</th>
                                        <th>Forma de Pago</th>
                                        <th>Valor</th>
                                        <th class="col-1">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($pagos):?>
                                        <?php foreach($pagos as $Pago):?>
                                            <tr> 
                                            <td><?= $Pago['id'];?></td>
                                            <td><?= $Pago['persona'];?></td>
                                            <td><?= $Pago['fecha_registro'];?></td>
                                            <td><?= $Pago['forma_pago'];?></td>
                                            <td><?= $Pago['valor_pagado'];?></td>
                                            <td>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-warning">Acciones</button>
                                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <li><a class="dropdown-item" href="<?=base_url('consultarPago/'.$Pago['id']);?>"><i class="fas fa-eye"></i> Consultar</a></li>
                                                    <li><a class="dropdown-item" href="<?=base_url('editarPago/'.$Pago['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                                    <li><a class="dropdown-item"  data-toggle="modal" data-target="#modalDelete<?=$Pago['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                                </div>
                                                </div>
                                                <div class="modal fade" id="modalDelete<?=$Pago['id'];?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Eliminar Pago <?=$Pago['id'];?></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Desea Eliminar el Comprobante de Pago? <?=$Pago['id'];?>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                        <a type="button" class="btn btn-danger" href="<?=base_url('eliminarPago/'.$Pago['id']);?>">Eliminar Pago</a>
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
                    </div>
                </div>
            </div>
        </div>
    </section>

<?= $this->endsection() ?>