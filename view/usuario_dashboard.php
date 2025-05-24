<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'usuario') {
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

    $user_id = $_SESSION["user_id"];

    // Obtener los pagos pendientes para el usuario actual
    $stmt_pendientes = $conn->prepare("SELECT pb.id, pb.beneficio, pb.mes, pb.anio, pb.monto, pp.detalles_pago, pp.fecha_limite, pb.estado_pago
                                     FROM pagos_beneficios pb
                                     INNER JOIN periodos_pago pp ON pb.beneficio = pp.beneficio AND pb.mes = pp.mes AND pb.anio = pp.anio
                                     WHERE pb.usuario_id = :user_id AND pb.fecha_pago IS NULL");
    $stmt_pendientes->bindParam(':user_id', $user_id);
    $stmt_pendientes->execute();
    $pagos_pendientes = $stmt_pendientes->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el historial de pagos del usuario actual
    $stmt_historial = $conn->prepare("SELECT pb.beneficio, pb.mes, pb.anio, pb.monto, pb.fecha_pago, pb.estado_pago, pb.comprobante_path, pb.metodo_pago, pb.referencia, pb.id AS pago_id
                                    FROM pagos_beneficios pb
                                    WHERE pb.usuario_id = :user_id AND pb.fecha_pago IS NOT NULL
                                    ORDER BY pb.fecha_pago DESC");
    $stmt_historial->bindParam(':user_id', $user_id);
    $stmt_historial->execute();
    $historial_pagos = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    $pagos_pendientes = [];
    $historial_pagos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        #comprobante-viewer {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }
        #comprobante-viewer img {
            max-width: 90%;
            max-height: 90%;
        }
        #close-comprobante {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #f1f1f1;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Bienvenido, Usuario</h1>
            <p class="lead">Este es tu panel personal.</p>
            <hr class="my-4">
            <p>Aquí podrás verificar tu información y el estado de tus pagos.</p>
            <a class="btn btn-danger btn-lg" href="logout.php" role="button">Cerrar Sesión</a>
        </div>

        <h2>Pagos Pendientes</h2>
        <?php if (!empty($pagos_pendientes)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Beneficio</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Monto</th>
                        <th>Detalles de Pago</th>
                        <th>Fecha Límite</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos_pendientes as $pago): ?>
                        <tr>
                            <td><?php echo $pago['beneficio']; ?></td>
                            <td><?php echo $pago['mes']; ?></td>
                            <td><?php echo $pago['anio']; ?></td>
                            <td><?php echo number_format($pago['monto'], 2); ?></td>
                            <td><?php echo $pago['detalles_pago']; ?></td>
                            <td><?php echo $pago['fecha_limite'] ? $pago['fecha_limite'] : 'Sin fecha límite'; ?></td>
                            <td><?php echo ucfirst($pago['estado_pago']); ?></td>
                            <td>
                                <?php if ($pago['estado_pago'] === 'pendiente'): ?>
                                    <a href="usuario_cargar_comprobante.php?pago_id=<?php echo $pago['id']; ?>" class="btn btn-sm btn-info">Cargar Comprobante</a>
                                <?php elseif ($pago['estado_pago'] === 'rechazado'): ?>
                                    <span class="text-danger">Pago Rechazado. Por favor, intente nuevamente.</span>
                                    <a href="usuario_cargar_comprobante.php?pago_id=<?php echo $pago['id']; ?>" class="btn btn-sm btn-warning mt-2">Reintentar Carga</a>
                                <?php else: ?>
                                    <span class="text-muted">Pago en proceso...</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes pagos pendientes en este momento.</p>
        <?php endif; ?>

        <h2 class="mt-4">Historial de Pagos</h2>
        <?php if (!empty($historial_pagos)): ?>
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
                    <?php foreach ($historial_pagos as $pago): ?>
                        <tr>
                            <td><?php echo $pago['beneficio']; ?></td>
                            <td><?php echo $pago['mes']; ?></td>
                            <td><?php echo $pago['anio']; ?></td>
                            <td><?php echo number_format($pago['monto'], 2); ?></td>
                            <td><?php echo $pago['fecha_pago']; ?></td>
                            <td><?php echo ucfirst($pago['estado_pago']); ?></td>
                            <td>
                                <?php if ($pago['comprobante_path']): ?>
                                    <button type="button" class="btn btn-sm btn-info" onclick="mostrarComprobante('<?php echo $pago['comprobante_path']; ?>')">Ver</button>
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
        <?php else: ?>
            <p>No tienes historial de pagos.</p>
        <?php endif; ?>

        <div id="comprobante-viewer">
            <span id="close-comprobante" onclick="cerrarComprobante()">&times;</span>
            <img src="" alt="Comprobante" id="comprobante-imagen">
        </div>
    </div>

    <script>
        function mostrarComprobante(ruta) {
            document.getElementById('comprobante-imagen').src = ruta;
            document.getElementById('comprobante-viewer').style.display = 'flex';
        }

        function cerrarComprobante() {
            document.getElementById('comprobante-viewer').style.display = 'none';
            document.getElementById('comprobante-imagen').src = ''; // Limpiar la imagen al cerrar
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>