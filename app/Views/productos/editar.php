<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Editar Persona</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard')?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('personas')?>">Personas</a></li>
                <li class="breadcrumb-item active">Editar Persona</li>
            </ol>
          </div>
        </div>
        <div class="row" style="justify-content: center; align-items: center;">
            <div class="col-5">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de edición de Persona</h3>
                    </div>
                    <form action="<?= base_url('actualizarPersona')?>" method="POST">
                        <?php if(session('mensaje')){?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo session('mensaje') ?>
                            </div>
                        <?php }  ?> 
                        <input type="hidden" name="id" value="<?= $persona['id'] ?>" >
                        <div class="card-body">
                            <div class="form-group">
                                <label for="identificacion">Identificación *</label>
                                <input type="text" class="form-control" id="identificacion" name="identificacion" value="<?= $persona['identificacion'] ?>" placeholder="Identificacion">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" value="1"  <?= $persona['es_extranjero'] == "1" ? 'checked' : '' ?> name="es_extranjero" id="es_extranjero">
                                    <label class="custom-control-label" for="es_extranjero">Es Extranjero</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombres">Nombres*</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" value="<?= $persona['nombres'] ?>" placeholder="Nombre de Persona">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo *</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?= $persona['correo'] ?>" placeholder="Correo">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono*</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $persona['telefono'] ?>" placeholder="Telefono">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" value="1"  <?= $persona['es_empleado'] == "1" ? 'checked' : '' ?> name="es_empleado" id="es_empleado" onclick="mostrarSelect()">
                                    <label class="custom-control-label" for="es_empleado">Es Empleado</label>
                                </div>
                            </div>
                            <div class="form-group" id="div_cargo" style="display:none;">
                                <label for="id_cargo">Cargo Persona *</label>
                                <select id="id_cargo" class="custom-select" name="id_cargo">
                                    <option value="">Seleccionar Cargo</option>
                                    <?php if($cargos):?>
                                        <?php foreach($cargos as $cargo):?>
                                            <option value="<?=$cargo['id']?>" <?php if($cargo['id'] == $persona['id_cargo']) echo 'selected'; ?>>
                                                <?= $cargo['descripcion'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" value="1"  <?= $persona['es_cliente'] == "1" ? 'checked' : '' ?> name="es_cliente" id="es_cliente">
                                    <label class="custom-control-label" for="es_cliente">Es Cliente</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" value="1"  <?= $persona['es_proveedor'] == "1" ? 'checked' : '' ?> name="es_proveedor" id="es_proveedor">
                                    <label class="custom-control-label" for="es_proveedor">Es proveedor</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" value="1"  <?= $persona['estado'] == "1" ? 'checked' : '' ?> name="estado" id="estado">
                                    <label class="custom-control-label" for="estado">Activo</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endsection() ?>