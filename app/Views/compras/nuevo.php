<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Registrar Compra</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item"><a href="#">Facturacion</a></li>
              <li class="breadcrumb-item active">Crear</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <form id="bookingForm" action="#" method="" class="needs-validation" novalidate autocomplete="off">
                <div class="form-group">
                    <label for="id_per_prov">Seleccionar Proveedor *</label>
                    <select id="id_per_prov" name="id_per_prov" class="selectpicker form-control" data-live-search="true">
                        <option value="">Escoja una persona</option>
                        <?php if($personas):?>
                            <?php foreach($personas as $persona):?>
                                <option value="<?=$persona['id']?>"><?= $persona['nombres'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="numero-factura">Factura:</label>
                    <input  class="form-control" type="text" id="num_fact" name="num_fact" placeholder="xxx-xxx-xxxxxxxxx" oninput="formatearNumero(this)" required>
                </div>
                
                <div class="form-group">
                    <label for="autorizacion-factura">Autorizacion:</label>
                    <input class="form-control" type="text" id="autorizacion_fact" name="autorizacion_fact" oninput="formatoAutorizacion(this)" required>
                </div>

                <div class="form-group">
                    <label for="fecha_doc">Fecha emision</label>
                    <input type="date" class="form-control" id="fecha_doc" name="fecha_doc" required/>
                </div>
                                
                <div class="form-group">
                    <label for="textAreaRemark">Descripcion</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="4" placeholder="Añadir detalle adicional del documento"></textarea>
                </div>

                <div class="form-group">
                    <label for="doc_adjunto">Documento adjunto</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="doc_adjunto" name="doc_adjunto">
                            <label class="custom-file-label" for="doc_adjunto">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="subttl_iva12">Subtotal IVA 12%*</label>
                            <input type="text" class="form-control align-right" id="subttl_iva12" name="subttl_iva12" placeholder="Subtotal IVA 12%">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="subttl_iva0">Subtotal IVA 0%*</label>
                            <input type="text" class="form-control align-right" id="subttl_iva0" name="subttl_iva0" placeholder="Subtotal IVA 0%">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="val_descuento">$ Descuento*</label>
                            <input type="text" class="form-control align-right" id="val_descuento" name="val_descuento" placeholder="Porcentaje Descuento">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="val_iva">Valor IVA</label>
                            <input type="text" class="form-control align-right" id="val_iva" name="val_iva" placeholder="Valor IVA">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="total">Valor Total</label>
                            <input type="text" class="form-control align-right" id="total" name="total" placeholder="Valor Total">
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-block col-lg-2" type="submit">Guardar Documento</button>
                <!-- End Submit Button -->
            </form><!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    
<script>

    /* para usar solo permitir numeros se be usar esto en el input oninput="this.value = permitirNumeros(this)" */
function formatoAutorizacion(input) {
    var numero = permitirNumeros(input);
    var maxLength = (numero.length >= 11) ? 50 : 11;
    input.value = numero.substring(0, maxLength);
    input.maxLength = maxLength;
}

function permitirNumeros(input) {
    // Eliminar todos los caracteres que no sean números
    var numero = input.value.replace(/\D/g, "");
    return numero;
}

function formatearNumero(input) {
    var numero = permitirNumeros(input);
    // Aplicar el formato xxx-xxx-xxxxxxxxx
    var formateado = "";
    if (numero.length > 0) {
        formateado += numero.substring(0, 3);
        if (numero.length >= 4) {
            formateado += "-" + numero.substring(3, 6);
        }
        if (numero.length >= 7) {
            formateado += "-" + numero.substring(6, 15);
        }
    }
    // Establecer el valor formateado en el campo de entrada
    input.value = formateado;
}

</script>

<?= $this->endsection() ?>