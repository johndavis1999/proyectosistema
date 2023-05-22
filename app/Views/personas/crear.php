<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Crear Persona</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item"><a href="personas">Personas</a></li>
                <li class="breadcrumb-item active">Crear Persona</li>
            </ol>
          </div>
        </div>
        <div class="row" style="justify-content: center; align-items: center;">
            <div class="col-5">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de creacion de Persona</h3>
                    </div>
                    <form action="guardarPersona" method="POST">
                        <?php if(session('mensaje')){?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo session('mensaje') ?>
                            </div>
                        <?php }  ?> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="identificacion">Identificación *</label>
                                <input type="text" class="form-control" id="identificacion" name="identificacion" value="<?= old('identificacion') ?>" placeholder="Identificacion">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="es_extranjero" id="es_extranjero" value="1" <?= old('es_extranjero') ? 'checked' : '' ?> class="form-control-input">
                                    <span class="custom-control-description">Es Extranjero</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="nombres">Nombres*</label>
                                <input type="text" value="<?= old('nombres') ?>" class="form-control" id="nombres" name="nombres" placeholder="Nombre de Persona">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo *</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?= old('correo') ?>" placeholder="Correo">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono*</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= old('telefono') ?>" placeholder="Telefono">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="es_empleado" id="es_empleado" value="1" <?= old('es_empleado') ? 'checked' : '' ?> class="form-control-input" onclick="mostrarSelect()">
                                    <span class="custom-control-description">Es Empleado</span>
                                </label>
                            </div>
                            <div class="form-group" id="div_cargo" style="display:none;">
                                <label for="id_cargo">Cargo Persona *</label>
                                <select id="id_cargo" class="custom-select" name="id_cargo">
                                    <option value="">Seleccionar Cargo</option>
                                    <?php if($cargos):?>
                                        <?php foreach($cargos as $cargo):?>
                                            <option value="<?=$cargo['id']?>"><?= $cargo['descripcion'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="es_cliente" id="es_cliente" value="1" <?= old('es_cliente') ? 'checked' : '' ?> value="1" class="form-control-input">
                                    <span class="custom-control-description">Es Cliente</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="es_proveedor" id="es_proveedor" value="1" <?= old('es_proveedor') ? 'checked' : '' ?> class="form-control-input">
                                    <span class="custom-control-description">Es Proveedor</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Crear Persona</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endsection() ?>