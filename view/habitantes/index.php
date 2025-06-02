<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Lista de Habitantes</h1>
                    <a href="habitantes/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Habitante
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaHabitantes">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los habitantes se cargarán aquí con JavaScript -->
                                    <tr>
                                        <td colspan="6" class="text-center">Cargando habitantes...</td>
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

<script>
// Script para cargar los habitantes mediante AJAX
document.addEventListener('DOMContentLoaded', function() {
    // Esta función se implementará cuando se conecte con el backend
    console.log('Cargando lista de habitantes...');
});
</script>
