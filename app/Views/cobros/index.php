<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cobros</h1>
                </div>
            
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                        <li class="breadcrumb-item active">Cobros</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col2 ml-3">
                    <a href="<?= base_url('exportarCobros/') ?>" class="btn btn-block btn-primary">
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
                                <form id="formulario" method="get" action="<?= base_url('Cobros') ?>">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="num_pago" class="mr-2">Numero de cobro:</label>
                                                <input type="text" name="num_pago" id="num_pago" value="" class="form-control mr-2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="proveedorFiltro" class="mr-2">Proveedor:</label>
                                                <select id="proveedorFiltro" name="proveedorFiltro" class="selectpicker form-control" data-live-search="true">
                                                    <option value="">Todos</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="fecha_inicio">Fecha de Inicio</label>
                                                <input type="date" class="form-control" id="fecha_inicio" value="" name="fecha_inicio"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="fecha_fin">Fecha de Fin</label>
                                                <input type="date" class="form-control" id="fecha_fin" value="" name="fecha_fin" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="forma_pago" class="mr-2">Forma de Pago:</label>
                                                <select name="forma_pago" id="forma_pago" class="form-control mr-2">
                                                    <option value="">Todos</option>
                                                    <option value="Efectivo">Efectivo</option>
                                                    <option value="Transferencia">Transferencia</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bancoFiltro" class="mr-2">Banco:</label>
                                                <select id="bancoFiltro" name="bancoFiltro" class="selectpicker form-control" data-live-search="true">
                                                    <option value="">Todos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="num_compra" class="mr-2">Compra:</label>
                                                <select id="num_compra" name="num_compra" class="selectpicker form-control" data-live-search="true">
                                                    <option value="">Todos</option>
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
                                        <th>Num Cobro</th>
                                        <th>Cliente</th>
                                        <th>Documento</th>
                                        <th>Fecha de registro</th>
                                        <th>Forma de Pago</th>
                                        <th>Valor</th>
                                        <th class="col-1">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($cobros):?>
                                        <?php foreach($cobros as $cotizacion):?>
                                            <tr> 
                                            <td><a href="<?=base_url('consultarCobro/'.$cotizacion['id']);?>">Cobro #<?= $cotizacion['id'];?></a></td>
                                            <td><?= $cotizacion['persona'];?></td>
                                            <td><a href="<?=base_url('consultarCotizacion/'.$cotizacion['id_cotizacion']);?>" target="_blank">Cotización #<?= $cotizacion['cotizacion'];?></a></td>
                                            <td><?= $cotizacion['fecha_registro'];?></td>
                                            <td><?= $cotizacion['forma_pago'];?></td>
                                            <td><?= $cotizacion['valor_pagado'];?></td>
                                            <td>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-warning">Acciones</button>
                                                <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <li><a class="dropdown-item" href="<?=base_url('consultarCobro/'.$cotizacion['id']);?>"><i class="fas fa-eye"></i> Consultar</a></li>
                                                    <li><a class="dropdown-item" href="<?=base_url('editarCobro/'.$cotizacion['id']);?>"><i class="fas fa-edit"></i> Editar</a></li>
                                                    <li><a class="dropdown-item"  data-toggle="modal" data-target="#modalDelete<?=$cotizacion['id'];?>"><i class="fas fa-trash-alt"></i> Borrar</a></li>
                                                </div>
                                                </div>
                                                <div class="modal fade" id="modalDelete<?=$cotizacion['id'];?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Eliminar Cobro <?=$cotizacion['id'];?></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Desea Eliminar el Comprobante de Cobro de la <a href="<?=base_url('consultarCotizacion/'.$cotizacion['id_cotizacion']);?>" target="_blank">Cotización #<?= $cotizacion['cotizacion'];?></a>?
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                        <a type="button" class="btn btn-danger" href="<?=base_url('eliminarCobro/'.$cotizacion['id']);?>">Eliminar Cobro</a>
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

    
<script>
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            if($(this).val() == "") {
                $('#fecha_fin').prop('disabled', true);
            } else {
                $('#fecha_fin').prop('disabled', false);
            }
        });
    });
    
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            var fechaInicio = $(this).val();

            if(fechaInicio == "") {
                $('#fecha_fin').prop('disabled', true);
            } else {
                $('#fecha_fin').prop('disabled', false);
                $('#fecha_fin').prop('min', fechaInicio);
            }
        });
    });
    
    $(document).ready(function() {
        $('#fecha_inicio').change(function() {
            var fechaInicio = $(this).val();

            if(fechaInicio == "") {
                $('#fecha_fin').prop('disabled', true);
                $('#fecha_fin').removeAttr('required');
            } else {
                $('#fecha_fin').prop('disabled', false);
                $('#fecha_fin').prop('min', fechaInicio);
                $('#fecha_fin').prop('required', true);
            }
        });
    });
</script>
<?= $this->endsection() ?>