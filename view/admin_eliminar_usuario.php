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

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $user_id = $_GET['id'];

        // Evitar que un administrador principal se elimine a sí mismo (opcional, pero buena práctica)
        if ($user_id == $_SESSION["user_id"]) {
            header("Location: admin_usuarios.php?mensaje=No puedes eliminar tu propia cuenta.&tipo=warning");
            exit();
        }

        // Eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            header("Location: admin_usuarios.php?mensaje=Usuario eliminado exitosamente.&tipo=success");
            exit();
        } else {
            header("Location: admin_usuarios.php?mensaje=Error al eliminar el usuario.&tipo=danger");
            exit();
        }
    } else {
        // Si no se proporciona un ID válido
        header("Location: admin_usuarios.php?mensaje=ID de usuario inválido para eliminar.&tipo=danger");
        exit();
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$conn = null;
?>