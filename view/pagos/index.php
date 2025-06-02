<?php require_once __DIR__ . '/../plantilla/header.php'; ?>
<?php require_once __DIR__ . '/../plantilla/sidebar.php'; ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Registro de Pagos</h1>
                    <a href="pagos/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Pago
                    </a>
                </div>

                <div class="card">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar por cédula o nombre...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="month" class="form-control" id="filtroMes">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="completado">Completado</option>
                                    <option value="atrasado">Atrasado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" id="btnFiltrar">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPagos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habitante</th>
                                        <th>Cédula</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Fecha Pago</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los pagos se cargarán aquí con JavaScript -->
                                    <tr>
                                        <td colspan="8" class="text-center">Cargando registros de pagos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Mostrando <span id="registrosMostrados">0</span> de <span id="totalRegistros">0</span> registros
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal para ver detalles del pago -->
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Los detalles del pago se cargarán aquí dinámicamente -->
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnImprimirRecibo">
                    <i class="fas fa-print me-1"></i> Imprimir Recibo
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de DataTables o lógica similar
    console.log('Página de pagos cargada');
    
    // Aquí iría el código para cargar los pagos mediante AJAX
    cargarPagos();
});

function cargarPagos() {
    // Implementar la carga de pagos
    console.log('Cargando lista de pagos...');
}
</script>
