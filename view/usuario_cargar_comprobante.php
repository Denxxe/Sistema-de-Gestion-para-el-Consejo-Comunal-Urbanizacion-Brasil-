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

    if (!isset($_GET['pago_id']) || !is_numeric($_GET['pago_id'])) {
        header("Location: usuario_dashboard.php?mensaje=ID de pago inválido.&tipo=danger");
        exit();
    }

    $pago_id = $_GET['pago_id'];

    // Verificar que el pago pertenezca al usuario actual
    $stmt = $conn->prepare("SELECT pb.beneficio, pb.monto
                           FROM pagos_beneficios pb
                           WHERE pb.id = :pago_id AND pb.usuario_id = :user_id AND pb.fecha_pago IS NULL");
    $stmt->bindParam(':pago_id', $pago_id);
    $stmt->bindParam(':user_id', $_SESSION["user_id"]);
    $stmt->execute();
    $pago = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pago) {
        header("Location: usuario_dashboard.php?mensaje=Pago no encontrado o ya registrado.&tipo=warning");
        exit();
    }

    $mensaje = "";
    if (isset($_GET['mensaje'])) {
        $mensaje = '<div class="alert alert-' . ($_GET['tipo'] ?? 'info') . ' mt-3" role="alert">' . $_GET['mensaje'] . '</div>';
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Comprobante de Pago</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cargar Comprobante de Pago</h2>
        <p><a href="usuario_dashboard.php" class="btn btn-secondary mb-3">Volver a mi Panel</a></p>

        <?php echo $mensaje; ?>

        <div class="card p-4 shadow">
            <p>Estás cargando el comprobante para el beneficio: <strong><?php echo $pago['beneficio']; ?></strong> por un monto de: <strong><?php echo number_format($pago['monto'], 2); ?></strong>.</p>
            <form action="procesar_cargar_comprobante.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="pago_id" value="<?php echo $pago_id; ?>">
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <input type="text" class="form-control" id="metodo_pago" name="metodo_pago" placeholder="Transferencia, Efectivo, etc." required>
                </div>
                <div class="form-group">
                    <label for="referencia">Número de Referencia:</label>
                    <input type="text" class="form-control" id="referencia" name="referencia">
                </div>
                <div class="form-group">
                    <label for="comprobante">Comprobante de Pago (Imagen):</label>
                    <input type="file" class="form-control-file" id="comprobante" name="comprobante" accept="image/*" required>
                    <small class="form-text text-muted">Por favor, sube una imagen clara del comprobante.</small>
                </div>
                <button type="submit" class="btn btn-primary">Subir Comprobante</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>