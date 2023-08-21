<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Editar Producto</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="">Productos</a></li>
                <li class="breadcrumb-item active">Editar Producto</li>
            </ol>
          </div>
        </div>
        <div class="" style="justify-content: center; align-items: center;">
            <div class="">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de creacion de Producto</h3>
                    </div>
                    <form action="<?= base_url('actualizarProducto') ?>" method="POST">
                        <input type="hidden" class="form-control" name="id" value="<?= $producto['id'] ?>">
                        <?php if(session('mensaje')){?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo session('mensaje') ?>
                            </div>
                        <?php }  ?> 
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="codigo">Codigo *</label>
                                        <input type="text" class="form-control" id="codigo" name="codigo" value="<?= $producto['codigo'] ?>" placeholder="Codigo del producto" pattern="[A-Za-z0-9]+" minlength="4" maxlength="10" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>" placeholder="Nombre del producto" pattern="[A-Za-z0-9 ]+" minlength="5" maxlength="30" required>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="precio_venta">Precio Venta *</label>
                                                <input type="text" class="form-control align-right" id="precio_venta" name="precio_venta" value="<?= $producto['precio_venta'] ?>" placeholder="Precio Venta" pattern="^\d*\.?\d+$">

                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="precio_compra">Precio Compra</label>
                                                <input type="text" class="form-control align-right" id="precio_compra" name="precio_compra" value="<?= $producto['precio_compra'] ?>" placeholder="Precio Compra" pattern="^\d*\.?\d+$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="es_inventariable" id="es_inventariable" value="1" <?= $producto['es_inventariable'] ? 'checked' : '' ?> value="1" class="form-control-input">
                                            <span class="custom-control-description">Es Inventariable</span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" value="1"  <?= $producto['estado'] == "1" ? 'checked' : '' ?> name="estado" id="estado">
                                            <label class="custom-control-label" for="estado">Activo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group" style="max-width: 200px;">
                                        <label for="id_categoria">Categoria *</label>
                                        <!--
                                        <select id="id_categoria" class="custom-select" name="id_categoria">
                                        </select> -->
                                        <select id="id_categoria" name="id_categoria" class="selectpicker form-control" data-live-search="true">
                                            <option value="">Seleccionar Categoria</option>
                                            <?php if($categorias):?>
                                                <?php foreach($categorias as $categoria):?>
                                                    <option value="<?=$categoria['id']?>" <?php if($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?>>
                                                        <?= $categoria['descripcion'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="max-width: 200px;">
                                        <label for="porcentaje_iva">Iva *</label>
                                        <select id="porcentaje_iva" name="porcentaje_iva" class="selectpicker form-control" style="width: 100px;">
                                            <option value="">Seleccionar Porcentaje</option>
                                            <option value="12" <?php if($producto['porcentaje_iva'] == '12') echo 'selected'; ?>>
                                                12%
                                            </option>
                                            <option value="0" <?php if($producto['porcentaje_iva'] == '0') echo 'selected'; ?>>
                                                0%
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group"  style="max-width: 200px;">
                                        <label for="descuento">Porcentaje descuento</label>
                                        <input type="text" class="form-control align-right" id="descuento" name="descuento" value="<?= $producto['descuento'] ?>" placeholder="Porcentaje descuento"   pattern="[0-9]+" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Editar producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .align-right {
        text-align: right;
    }
</style>

<script>
    $(document).ready(function() {
        $('form').submit(function() {
            // Bloquear el botón y cambiar el texto a "Guardando"
            var submitButton = $('button[type="submit"]');
            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

            // Continuar con el envío del formulario
            return true;
        });
    });
</script>
<?= $this->endsection() ?>