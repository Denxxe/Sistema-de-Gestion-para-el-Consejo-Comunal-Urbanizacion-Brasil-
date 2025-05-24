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
        $id = $_POST["id"];
        $rol = $_POST["rol"];
        $cedula = trim($_POST["cedula"]);
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"]; // Contraseña nueva (puede estar vacía)

        // Validaciones básicas
        if (empty($rol) || empty($cedula) || empty($nombre) || empty($apellido) || empty($id)) {
            header("Location: admin_editar_usuario.php?id=$id&mensaje=Los campos con (*) son obligatorios.&tipo=warning");
            exit();
        }

        // Validar formato de cédula
        if (!preg_match('/^[0-9]+$/', $cedula)) {
            header("Location: admin_editar_usuario.php?id=$id&mensaje=Formato de cédula inválido.&tipo=warning");
            exit();
        }

        // Validar formato de email si se proporciona
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: admin_editar_usuario.php?id=$id&mensaje=Formato de email inválido.&tipo=warning");
            exit();
        }

        // Verificar si la nueva cédula ya existe para otro usuario
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE cedula = :cedula AND id != :id");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            header("Location: admin_editar_usuario.php?id=$id&mensaje=Esta cédula ya está registrada para otro usuario.&tipo=danger");
            exit();
        }

        // Actualizar el usuario
        $sql = "UPDATE usuarios SET rol = :rol, cedula = :cedula, nombre = :nombre, apellido = :apellido, email = :email";
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        if (!empty($password)) {
            $stmt->bindParam(':password', $password_hash);
        }

        if ($stmt->execute()) {
            header("Location: admin_usuarios.php?mensaje=Usuario actualizado exitosamente.&tipo=success");
            exit();
        } else {
            header("Location: admin_editar_usuario.php?id=$id&mensaje=Error al actualizar el usuario.&tipo=danger");
            exit();
        }

    } else {
        // Si alguien intenta acceder directamente a este script
        header("Location: admin_usuarios.php");
        exit();
    }

} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

$conn = null;
?>