<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content',$titulo) ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Error 404</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url("dashboard") ?>">Inicio</a></li>
                    <li class="breadcrumb-item active">Error 404</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content"  style="margin-top: 15%">
        <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>
            <div class="error-content">
                <h3>
                    <i class="fas fa-exclamation-triangle text-warning"></i> ¡Ups! Página no encontrada.
                </h3>
                <p>
                    No pudimos encontrar la página que estabas buscando.
                    Mientras tanto, puedes <a href="<?= base_url("dashboard") ?>">regresar al panel de control</a> o intentar usar el formulario de búsqueda.
                </p>
            </div>
        </div>
    </section>
<?= $this->endsection() ?>
