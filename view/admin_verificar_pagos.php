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

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los pagos pendientes de verificación incluyendo la cédula del usuario
    $stmt = $conn->prepare("SELECT pb.id, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, u.cedula AS cedula_usuario, pb.beneficio, pb.mes, pb.anio, pb.monto, pb.comprobante_path
FROM pagos_beneficios pb
JOIN usuarios u ON pb.usuario_id = u.id
WHERE pb.comprobante_path IS NOT NULL AND pb.estado_pago = 'pendiente'");
    $stmt->execute();
    $pagos_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    $pagos_pendientes = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Pagos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .modal-dialog {
            max-width: 80%; /* Ajusta el ancho del modal */
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Verificar Pagos Pendientes</h2>
        <p><a href="<?php echo ($_SESSION['user_rol'] === 'admin_principal' ? 'admin_dashboard.php' : 'subadmin_dashboard.php'); ?>" class="btn btn-secondary mb-3">Volver al Dashboard</a></p>

        <?php if (!empty($pagos_pendientes)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Cédula</th>
                        <th>Beneficio</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Monto</th>
                        <th>Comprobante</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos_pendientes as $pago): ?>
                        <tr>
                            <td><?php echo $pago['nombre_usuario'] . ' ' . $pago['apellido_usuario']; ?></td>
                            <td><?php echo $pago['cedula_usuario']; ?></td>
                            <td><?php echo $pago['beneficio']; ?></td>
                            <td><?php echo $pago['mes']; ?></td>
                            <td><?php echo $pago['anio']; ?></td>
                            <td><?php echo number_format($pago['monto'], 2); ?></td>
                            <td><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#comprobanteModal<?php echo $pago['id']; ?>">Ver Comprobante</button></td>
                            <td>
                                <a href="procesar_verificar_pago.php?id=<?php echo $pago['id']; ?>&verificado=1" class="btn btn-sm btn-success">Verificar</a>
                                <a href="procesar_verificar_pago.php?id=<?php echo $pago['id']; ?>&verificado=0" class="btn btn-sm btn-danger">Rechazar</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="comprobanteModal<?php echo $pago['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="comprobanteModalLabel<?php echo $pago['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="comprobanteModalLabel<?php echo $pago['id']; ?>">Comprobante de Pago</h5>
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay pagos pendientes de verificación en este momento.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>