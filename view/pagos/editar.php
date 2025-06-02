<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Editar Pago</h1>
                    <a href="/pagos" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="formEditarPago" action="/pagos/actualizar/<?= $id ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Información del Habitante</h5>
                                    <div class="alert alert-info">
                                        <p class="mb-1"><strong>Habitante:</strong> <span id="nombreHabitante">Cargando...</span></p>
                                        <p class="mb-0"><strong>Cédula:</strong> <span id="cedulaHabitante">Cargando...</span></p>
                                    </div>
                                    <input type="hidden" id="id_habitante" name="id_habitante" required>

                                    <div class="form-group mb-3">
                                        <label for="tipo_pago">Tipo de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                            <option value="">Seleccione un tipo de pago</option>
                                            <option value="mensualidad">Mensualidad</option>
                                            <option value="cuota_especial">Cuota Especial</option>
                                            <option value="donacion">Donación</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">Detalles del Pago</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="monto">Monto <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" id="monto" name="monto" step="0.01" min="0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="fecha_pago">Fecha de Pago <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="estado">Estado <span class="text-danger">*</span></label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="completado">Completado</option>
                                            <option value="anulado">Anulado</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="metodo_pago">Método de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="transferencia">Transferencia Bancaria</option>
                                            <option value="pago_movil">Pago Móvil</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="referencia">Número de Referencia</label>
                                        <input type="text" class="form-control" id="referencia" name="referencia">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="observaciones">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-danger me-2" id="btnEliminarPago">
                                    <i class="fas fa-trash-alt"></i> Eliminar Pago
                                </button>
                                <div>
                                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Pago
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar este pago? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pagoId = <?= $id ?>;
    
    // Cargar los datos del pago
    cargarDatosPago(pagoId);
    
    // Configurar el botón de eliminar
    document.getElementById('btnEliminarPago').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
        modal.show();
    });
    
    // Confirmar eliminación
    document.getElementById('confirmarEliminar').addEventListener('click', function() {
        eliminarPago(pagoId);
    });
});

function cargarDatosPago(id) {
    // Implementar la carga de datos del pago
    console.log('Cargando datos del pago con ID:', id);
    // Aquí iría la llamada AJAX para obtener los datos del pago
}

function eliminarPago(id) {
    // Implementar la lógica para eliminar el pago
    console.log('Eliminando pago con ID:', id);
    // Aquí iría la llamada AJAX para eliminar el pago
    // Luego de eliminar, redirigir a la lista de pagos
    window.location.href = '/pagos';
}
</script>
