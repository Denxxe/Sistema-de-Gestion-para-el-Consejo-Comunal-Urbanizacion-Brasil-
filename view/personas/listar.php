<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\controllers\PersonaController;


// Obtener el controlador
$personaController = new PersonaController();

// Obtener todas las personas
$respuesta = $personaController->listar();
$personas = $respuesta['data'] ?? [];

require_once __DIR__ . '/../plantilla/header.php';
?>

<h2>Listado de Personas</h2>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="./personas/crear" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Persona
    </a>
</div>

<?php if (empty($personas)): ?>
    <div class="alert alert-info">
        No se encontraron personas registradas.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Fecha Nacimiento</th>
                    <th>Sexo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personas as $persona): ?>
                    <tr>
                        <td><?= htmlspecialchars($persona['id_persona'] ?? '') ?></td>
                        <td><?= htmlspecialchars($persona['cedula'] ?? '') ?></td>
                        <td><?= htmlspecialchars($persona['nombres'] ?? '') ?></td>
                        <td><?= htmlspecialchars($persona['apellidos'] ?? '') ?></td>
                        <td><?= htmlspecialchars($persona['fecha_nacimiento'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($persona['sexo'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($persona['estado'] ?? '-') ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="./personas/editar/<?= htmlspecialchars($persona['id_persona'] ?? '') ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="./personas/eliminar/<?= htmlspecialchars($persona['id_persona'] ?? '') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta persona?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>
