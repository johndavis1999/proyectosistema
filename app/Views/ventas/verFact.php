<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Factura #007612</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item"><a href="#">Facturacion</a></li>
              <li class="breadcrumb-item active">Consultar</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> Empresa S.A.
                    <small class="float-right">Fecha de creació: 2/10/2023</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Datos de la empresa
                  <address>
                    <strong>Empresa S.A.</strong><br>
                    Pedro Carbo y Victor Manuel Rendón #123<br>
                    Guayaquil - Ecuador<br>
                    Telefono: (04) 123 456<br>
                    Correo: empresacorreo@gmail.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Cliente
                  <address>
                    <strong>John Davis</strong><br>
                    10 de agosto y Portete<br>
                    Guayaquil - Ecuador<br>
                    Telefono: (593) 912 3456 789<br>
                    Correo: john.davis@example.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Factura #007612</b><br>
                  <br>
                  <b>Monto:</b> $150<br>
                  <b>Fecha de pago:</b> 2/22/2023<br>
                  <b>#Comprobante:</b> 968-34567
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th width="1">-</th>
                      <th width="100">Cant.</th>
                      <th>Producto</th>
                      <th>IVA</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="checkbox"></td>
                      <td>
                        <div class="row">
                          <div class="form-group">
                            <input type="number" class="form-control form-control-md" name="" id="" placeholder="">
                          </div>
                        </div>
                      </td>
                      <td>Pintura RLM 02</td>
                      <td>12</td>
                      <td>$64.50</td>
                    </tr>
                    <tr>
                      <td><input type="checkbox"></td>
                      <td>
                        <div class="row">
                          <div class="form-group">
                            <input type="number" class="form-control form-control-md" name="" id="" placeholder="">
                          </div>
                        </div>
                      </td>
                      <td>Pintura RLM 22 </td>
                      <td>12</td>
                      <td>$50.00</td>
                    </tr>
                    <tr>
                      <td><input type="checkbox"></td>
                      <td>
                        <div class="row">
                          <div class="form-group">
                            <input type="number" class="form-control form-control-md" name="" id="" placeholder="">
                          </div>
                        </div>
                      </td>
                      <td>Pintura RLM 28</td>
                      <td>12</td>
                      <td>$10.70</td>
                    </tr>
                    <tr>
                      <td><input type="checkbox"></td>
                      <td>
                        <div class="row">
                          <div class="form-group">
                            <input type="number" class="form-control form-control-md" name="" id="" placeholder="">
                          </div>
                        </div>
                      </td>
                      <td>Pintura RLM 80</td>
                      <td>12</td>
                      <td>$25.99</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Descripcion/Detalles:</p>
                  <div class="form-group text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    <label for=""></label>
                    <textarea class="form-control" name="" id="" rows="3"></textarea>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Monto a pagar</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>$250.30</td>
                      </tr>
                      <tr>
                        <th>IVA (12%)</th>
                        <td>$10.34</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>$265.24</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> RGenerar Factura
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
<?= $this->endsection() ?>