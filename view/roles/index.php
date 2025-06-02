<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Lista de Roles</h1>
                    <a href="roles/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Rol
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los roles se cargarán aquí con JavaScript -->
                                    <tr>
                                        <td colspan="4" class="text-center">Cargando roles...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>
