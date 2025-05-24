<?php
session_start();
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_rol"] !== 'admin_principal' && $_SESSION["user_rol"] !== 'sub_admin')) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza
$password_db = ""; // Reemplaza
$dbname = "consejo_comunal";

$historial_usuario = [];
$mensaje = '';
$nombre_usuario_buscado = '';
$apellido_usuario_buscado = '';
$cedula_buscada = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['cedula_buscar'])) {
        $cedula_buscada = $_GET['cedula_buscar'];

        $stmt_usuario = $conn->prepare("SELECT id, nombre, apellido FROM usuarios WHERE cedula = :cedula");
        $stmt_usuario->bindParam(':cedula', $cedula_buscada);
        $stmt_usuario->execute();
        $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $user_id = $usuario['id'];
            $nombre_usuario_buscado = $usuario['nombre'];
            $apellido_usuario_buscado = $usuario['apellido'];
            $stmt_historial = $conn->prepare("SELECT pb.beneficio, pb.mes, pb.anio, pb.monto, pb.fecha_pago, pb.estado_pago, pb.comprobante_path, pb.metodo_pago, pb.referencia
                                            FROM pagos_beneficios pb
                                            WHERE pb.usuario_id = :user_id
                                            ORDER BY pb.fecha_pago DESC");
            $stmt_historial->bindParam(':user_id', $user_id);
            $stmt_historial->execute();
            $historial_usuario = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $mensaje = "No se encontró ningún usuario con esa cédula.";
        }
    }
} catch(PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Historial de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .modal-dialog {
            max-width: 80%;
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Ver Historial de Pagos de Usuario</h2>
        <p><a href="<?php echo ($_SESSION['user_rol'] === 'admin_principal' ? 'admin_dashboard.php' : 'subadmin_dashboard.php'); ?>" class="btn btn-secondary mb-3">Volver al Dashboard</a></p>

        <form class="mb-3">
            <div class="form-group">
                <label for="cedula_buscar">Ingrese el Número de Cédula del Usuario:</label>
                <input type="text" class="form-control" id="cedula_buscar" name="cedula_buscar" pattern="^\d{7,8}$" title="Ingrese solo el número de cédula (7 u 8 dígitos)" required>
                <small class="form-text text-muted">Ingrese solo el número de cédula (ej: 12345678).</small>
                <div id="cedula-error" class="text-danger"></div>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Historial</button>
        </form>

        <?php if ($mensaje): ?>
            <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <?php if (!empty($historial_usuario)): ?>
            <h3>Historial de Pagos de: <strong><?php echo $nombre_usuario_buscado . ' ' . $apellido_usuario_buscado; ?></strong> (Cédula: <?php echo $cedula_buscada; ?>)</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Beneficio</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                        <th>Estado</th>
                        <th>Comprobante</th>
                        <th>Método</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_usuario as $pago): ?>
                        <tr>
                            <td><?php echo $pago['beneficio']; ?></td>
                            <td><?php echo $pago['mes']; ?></td>
                            <td><?php echo $pago['anio']; ?></td>
                            <td><?php echo number_format($pago['monto'], 2); ?></td>
                            <td><?php echo $pago['fecha_pago']; ?></td>
                            <td><?php echo ucfirst($pago['estado_pago']); ?></td>
                            <td>
                                <?php if ($pago['comprobante_path']): ?>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#historialAdminComprobanteModal<?php echo md5($pago['comprobante_path']); ?>">Ver</button>
                                    <div class="modal fade" id="historialAdminComprobanteModal<?php echo md5($pago['comprobante_path']); ?>" tabindex="-1" role="dialog" aria-labelledby="historialAdminComprobanteModalLabel<?php echo md5($pago['comprobante_path']); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="historialAdminComprobanteModalLabel<?php echo md5($pago['comprobante_path']); ?>">Comprobante de Pago</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo $pago['comprobante_path']; ?>" alt="Comprobante de Pago" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo $pago['metodo_pago'] ?: 'N/A'; ?></td>
                            <td><?php echo $pago['referencia'] ?: 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['cedula_buscar'])): ?>
            <p>No se encontraron pagos en el historial para la cédula proporcionada.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formulario = document.querySelector('form');
            const cedulaInput = document.getElementById('cedula_buscar');
            const cedulaError = document.getElementById('cedula-error');
            const formatoNumeroCedula = /^\d{7,8}$/;

            formulario.addEventListener('submit', function(event) {
                const cedulaValue = cedulaInput.value.trim();
                if (!formatoNumeroCedula.test(cedulaValue)) {
                    cedulaError.textContent = 'Por favor, ingrese solo el número de cédula (7 u 8 dígitos).';
                    event.preventDefault(); // Evita que se envíe el formulario
                } else {
                    cedulaError.textContent = ''; // Limpia el mensaje de error
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>