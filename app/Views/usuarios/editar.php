<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Editar Datos de Usuario</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Administrar usuarios</a></li>
                <li class="breadcrumb-item active">Crear Usuario</li>
            </ol>
          </div>
        </div>
        <div class="row" style="justify-content: center; align-items: center;">
            <div class="col-5">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de edición de usuario</h3>
                    </div>
                    <form action="<?= base_url('actualizarUsuario') ?>" method="POST" enctype="multipart/form-data">
                        <?php if(session('mensaje')){?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo session('mensaje') ?>
                            </div>
                        <?php }  ?> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="id_persona">Seleccionar Persona *</label>
                                <select id="id_persona" name="id_persona" class="selectpicker form-control" data-live-search="true">
                                    <option value="">Escoja una persona</option>
                                    <?php if($personas):?>
                                        <?php foreach($personas as $persona):?>
                                            <option value="<?=$persona['id']?>" <?php if($persona['id'] == $usuario['id_persona']) echo 'selected'; ?>>
                                                <?= $persona['nombres'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="usuario">Nombre de Usuario *</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombre de Usuario" value="<?= $usuario['usuario'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="id_rol">Rol de Usuario *</label>
                                <select id="id_rol" class="custom-select" name="id_rol" placeholder="rol">
                                    <option value="">Seleccionar Rol</option>
                                    <?php if($roles):?>
                                        <?php foreach($roles as $rol):?>
                                            <option value="<?=$rol['id']?>" <?php if($rol['id'] == $usuario['id_rol']) echo 'selected'; ?>>
                                                <?= $rol['descripcion'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Foto</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="imagen" value="<?=$usuario['imagen']?>">
                                        <label class="custom-file-label" for="exampleInputFile">Seleccionar archivo</label>
                                    </div>
                                </div>
                                <br>
                                
                                <?php if (!empty($usuario['imagen']) &&  file_exists('users/img/'.$usuario['imagen'])) {?>
                                    <img class="img-thumbnail" src="<?= base_url('users/img/'.$usuario['imagen'])?>" alt="imagen_usuario" width="100px">
                                <?php }else{?>
                                    <img class="img-thumbnail" src="<?= base_url('assets/no_image.png')?>" alt="imagen_usuario" width="300px">
                                <?php }?>
                                <br>
                            </div>
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Editar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endsection() ?>