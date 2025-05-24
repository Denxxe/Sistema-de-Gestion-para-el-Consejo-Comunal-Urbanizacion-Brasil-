<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'admin_principal') {
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $beneficio = trim($_POST["beneficio"]);
        $mes = $_POST["mes"];
        $anio = $_POST["anio"];
        $monto = $_POST["monto"];
        $detalles_pago = trim($_POST["detalles_pago"]);
        $fecha_limite = $_POST["fecha_limite"];

        // Validaciones (las mismas de antes)
        if (empty($beneficio) || empty($mes) || empty($anio) || empty($monto)) {
            header("Location: admin_activar_pago.php?mensaje=Los campos con (*) son obligatorios.&tipo=warning");
            exit();
        }
        if (!is_numeric($mes) || $mes < 1 || $mes > 12 || !is_numeric($anio) || !is_numeric($monto) || $monto <= 0) {
            header("Location: admin_activar_pago.php?mensaje=Formato de mes, año o monto inválido.&tipo=warning");
            exit();
        }
        $stmt = $conn->prepare("SELECT COUNT(*) FROM periodos_pago WHERE beneficio = :beneficio AND mes = :mes AND anio = :anio AND activo = TRUE");
        $stmt->bindParam(':beneficio', $beneficio);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':anio', $anio);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            header("Location: admin_activar_pago.php?mensaje=Ya existe un período de pago activo para este beneficio en este mes y año.&tipo=danger");
            exit();
        }

        // Insertar el nuevo período de pago
        $stmt = $conn->prepare("INSERT INTO periodos_pago (beneficio, mes, anio, monto, detalles_pago, fecha_limite) VALUES (:beneficio, :mes, :anio, :monto, :detalles_pago, :fecha_limite)");
        $stmt->bindParam(':beneficio', $beneficio);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':detalles_pago', $detalles_pago);
        $stmt->bindParam(':fecha_limite', $fecha_limite);

        if ($stmt->execute()) {
            $periodo_pago_id = $conn->lastInsertId(); // Obtener el ID del período de pago recién insertado

            // Crear entradas pendientes en pagos_beneficios para todos los usuarios comunes
            $stmt_usuarios = $conn->prepare("SELECT id FROM usuarios WHERE rol = 'usuario'");
            $stmt_usuarios->execute();
            $usuarios_comunes = $stmt_usuarios->fetchAll(PDO::FETCH_COLUMN);

            $stmt_insert_pago = $conn->prepare("INSERT INTO pagos_beneficios (usuario_id, beneficio, mes, anio, monto) VALUES (:usuario_id, :beneficio, :mes, :anio, :monto)");

            foreach ($usuarios_comunes as $usuario_id) {
                $stmt_insert_pago->bindParam(':usuario_id', $usuario_id);
                $stmt_insert_pago->bindParam(':beneficio', $_POST["beneficio"]);
                $stmt_insert_pago->bindParam(':mes', $_POST["mes"]);
                $stmt_insert_pago->bindParam(':anio', $_POST["anio"]);
                $stmt_insert_pago->bindParam(':monto', $_POST["monto"]);
                $stmt_insert_pago->execute();
            }

            header("Location: admin_activar_pago.php?mensaje=Período de pago activado y notificaciones creadas.&tipo=success");
            exit();
        } else {
            header("Location: admin_activar_pago.php?mensaje=Error al activar el período de pago.&tipo=danger");
            exit();
        }

    } else {
        header("Location: admin_activar_pago.php");
        exit();
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$conn = null;
?>