<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cotizaciones</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
              <li class="breadcrumb-item active">Cotizaciones</li>
            </ol>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col2">
            <a type="button" class="btn btn-block btn-success" href="cotizacionCrear"><i class="fas fa-plus"></i> Generar Cotización</a>
          </div>
          <div class="col2 ml-3">
            <button type="button" class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Generar PDF</button>
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
                <h3 class="card-title">Administrar cotizaciones </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <?php if(session('mensaje')){?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo session('mensaje') ?>
                  </div>
              <?php }  ?> 
              <?php if(session('exito')){?>
                  <div class="alert alert-success" role="alert">
                      <?php echo session('exito') ?>
                  </div>
              <?php }  ?> 
                <table id="example2" class="table table-bordered table-hover table-sm table-responsive-sm">
                  <thead>
                    <tr>
                      <th>Num. Cot.</th>
                      <th>Cliente</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <div>
                  <?php #echo $paginador->links() ?>
                </div>
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
<?= $this->endsection() ?>