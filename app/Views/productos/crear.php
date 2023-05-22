<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <h1>Crear Producto</h1>
          </div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
                <li class="breadcrumb-item"><a href="productos">Productos</a></li>
                <li class="breadcrumb-item active">Crear Producto</li>
            </ol>
          </div>
        </div>
        <div class="" style="justify-content: center; align-items: center;">
            <div class="">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de creacion de Producto</h3>
                    </div>
                    <form action="guardarProducto" method="POST">
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
                                        <input type="text" class="form-control" id="codigo" name="codigo" value="<?= old('codigo') ?>" placeholder="Codigo del producto">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>" placeholder="Nombre del producto">
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="precio_venta">Precio Venta *</label>
                                                <input type="text" class="form-control align-right" id="precio_venta" name="precio_venta" value="<?= old('precio_venta') ?>" placeholder="Precio Venta" pattern="^\d*\.?\d+$">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="precio_compra">Precio Compra</label>
                                                <input type="number" class="form-control align-right" id="precio_compra" name="precio_compra" value="<?= old('precio_compra') ?>" placeholder="Precio Compra" pattern="^\d*\.?\d+$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="es_inventariable" id="es_inventariable" value="1" <?= old('es_inventariable') ? 'checked' : '' ?> value="1" class="form-control-input">
                                            <span class="custom-control-description">Es Inventariable</span>
                                        </label>
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
                                                    <option value="<?=$categoria['id']?>"><?= $categoria['descripcion'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="max-width: 200px;">
                                        <label for="porcentaje_iva">Iva *</label>
                                        <select id="porcentaje_iva" name="porcentaje_iva" class="selectpicker form-control" style="width: 100px;">
                                            <option value="">Seleccionar Porcentaje</option>
                                            <option value="12">12%</option>
                                            <option value="0">0%</option>
                                        </select>
                                    </div>
                                    <div class="form-group"  style="max-width: 200px;">
                                        <label for="descuento">Porcentaje descuento</label>
                                        <input type="text" class="form-control align-right" id="descuento" name="descuento" value="0" placeholder="Porcentaje descuento"  pattern="^(100(\.0{1,2})?|[1-9]?\d(\.\d{1,2})?)%?$">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Crear Producto</button>
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


<?= $this->endsection() ?>