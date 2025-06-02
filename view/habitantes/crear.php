<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Registrar Nuevo Habitante</h1>
                    <a href="/habitantes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="formCrearHabitante" action="/habitantes" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Datos Personales</h5>
                                    <div class="form-group mb-3">
                                        <label for="cedula">Cédula <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sexo">Sexo</label>
                                                <select class="form-control" id="sexo" name="sexo">
                                                    <option value="">Seleccione...</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                    <option value="O">Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">Información de Contacto</h5>
                                    <div class="form-group mb-3">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="direccion">Dirección</label>
                                        <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Habitante
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de componentes o validaciones adicionales
    console.log('Formulario de creación de habitante listo');
});
</script>
