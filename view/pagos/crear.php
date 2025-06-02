<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Registrar Nuevo Pago</h1>
                    <a href="/pagos" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="formCrearPago" action="/pagos" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Información del Habitante</h5>
                                    <div class="form-group mb-3">
                                        <label for="buscarHabitante">Buscar Habitante <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="buscarHabitante" placeholder="Cédula o nombre del habitante" required>
                                            <button class="btn btn-outline-secondary" type="button" id="btnBuscarHabitante">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" id="id_habitante" name="id_habitante" required>
                                        <div id="infoHabitante" class="mt-2 p-2 border rounded bg-light d-none">
                                            <p class="mb-1"><strong>Nombre:</strong> <span id="nombreHabitante"></span></p>
                                            <p class="mb-1"><strong>Cédula:</strong> <span id="cedulaHabitante"></span></p>
                                            <p class="mb-0"><strong>Dirección:</strong> <span id="direccionHabitante"></span></p>
                                        </div>
                                    </div>

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
                                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="<?= date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
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
                                        <small class="form-text text-muted">Opcional para pagos electrónicos</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="observaciones">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Registrar Pago
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
    // Inicialización de componentes
    console.log('Formulario de registro de pago listo');
    
    // Aquí iría el código para la búsqueda de habitantes
    document.getElementById('btnBuscarHabitante').addEventListener('click', function() {
        // Implementar búsqueda de habitantes
        console.log('Buscando habitante...');
    });
});
</script>
