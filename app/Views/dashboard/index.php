<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>

<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Inicio</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="dashboard">Dashboard</a></li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="row">
        <div class="col-12" id="accordion">
            <div class="card card-primary card-outline">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            Resumen
                        </h4>
                    </div>
                </a>
                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                        <!-- /.content-header -->
                        <section class="content">
                            <div class="container-fluid">
                                <!-- Small boxes (Stat box) -->
                                <div class="row">
                                    <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                            <h3>X</h3>

                                            <p>Ventas realizadas</p>
                                            </div>
                                            <div class="icon">
                                            <i class="ion ion-bag"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                            <h3>X$</h3>

                                            <p>Ingresos por ventas</p>
                                            </div>
                                            <div class="icon">
                                            <i class="ion ion-pie-graph"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                            <h3>X<sup style="font-size: 20px">%</sup></h3>

                                            <p>Ratio de ventas finalizadas</p>
                                            </div>
                                            <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                            <h3><?= $totalClientes ?></h3>

                                            <p>Clientes Activos</p>
                                            </div>
                                            <div class="icon">
                                            <i class="ion ion-person-add"></i>
                                            </div>
                                            <a href="personas" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-orange">
                                            <div class="inner">
                                            <h3>X</h3>

                                            <p>Compras realizadas</p>
                                            </div>
                                            <div class="icon">
                                            <i class="fas fa-cart-plus"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-gray">
                                            <div class="inner">
                                            <h3>X$</h3>

                                            <p>Egresos por compras</p>
                                            </div>
                                            <div class="icon">
                                            <i class="fas fa-money-check-alt"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-olive">
                                            <div class="inner">
                                            <h3><?= $totalProductos ?></h3>

                                            <p>Productos disponibles</p>
                                            </div>
                                            <div class="icon">
                                            <i class="fas fa-warehouse"></i>
                                            </div>
                                            <a href="productos" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-lightblue">
                                            <div class="inner">
                                            <h3>X$</h3>

                                            <p>Ganancias</p>
                                            </div>
                                            <div class="icon">
                                            <i class="fas fa-chart-line"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="card card-primary card-outline">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            Seccion 2
                        </h4>
                    </div>
                </a>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        Contenido
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endsection() ?>