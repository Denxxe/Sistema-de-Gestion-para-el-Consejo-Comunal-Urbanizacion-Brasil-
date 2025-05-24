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

    if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['verificado']) && is_numeric($_GET['verificado'])) {
        $pago_id = $_GET['id'];
        $verificado = $_GET['verificado']; // 1 para verificado, 0 para rechazado

        $estado = $verificado ? 'verificado' : 'rechazado';

        $stmt = $conn->prepare("UPDATE pagos_beneficios SET verificado = :verificado, estado_pago = :estado_pago, fecha_pago = :fecha_pago WHERE id = :id");
        $stmt->bindParam(':verificado', $verificado, PDO::PARAM_BOOL);
        $stmt->bindParam(':estado_pago', $estado);
        $stmt->bindParam(':id', $pago_id, PDO::PARAM_INT);

        $fecha_pago_update = $verificado ? date('Y-m-d H:i:s') : null;
        $stmt->bindParam(':fecha_pago', $fecha_pago_update);

        if ($stmt->execute()) {
            header("Location: admin_verificar_pagos.php?mensaje=Pago " . ($verificado ? 'verificado' : 'rechazado') . ".&tipo=success");
            exit();
        } else {
            header("Location: admin_verificar_pagos.php?mensaje=Error al actualizar el pago.&tipo=danger");
            exit();
        }

    } else {
        // Si los parámetros no son correctos
        header("Location: admin_verificar_pagos.php?mensaje=Solicitud inválida.&tipo=warning");
        exit();
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$conn = null;
?>