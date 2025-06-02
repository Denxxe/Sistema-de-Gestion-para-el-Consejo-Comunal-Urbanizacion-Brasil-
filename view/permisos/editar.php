<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Editar Permiso</h1>
                    <a href="/permisos" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="formEditarPermiso" action="/permisos/actualizar/<?= $id ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nombre">Nombre del Permiso <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="llave">Llave del Permiso <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="llave" name="llave" required>
                                        <small class="form-text text-muted">Usar formato: modulo.accion (ej: usuarios.crear)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Permiso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>
