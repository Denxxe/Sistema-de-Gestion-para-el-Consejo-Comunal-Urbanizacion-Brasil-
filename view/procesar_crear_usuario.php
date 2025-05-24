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
        $rol = $_POST["rol"];
        $cedula = trim($_POST["cedula"]);
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        // Validaciones básicas
        if (empty($rol) || empty($cedula) || empty($nombre) || empty($apellido) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos con (*) son obligatorios.']);
            exit();
        }

        // Validar formato de cédula
        if (!preg_match('/^[0-9]+$/', $cedula)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de cédula inválido.']);
            exit();
        }

        // Validar formato de email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de email inválido.']);
            exit();
        }

        // Verificar si la cédula ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE cedula = :cedula");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'La cédula ya está registrada en el sistema.']);
            exit();
        }

        // Hashear la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (rol, cedula, nombre, apellido, email, password) VALUES (:rol, :cedula, :nombre, :apellido, :email, :password)");
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente.']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el usuario.']);
            exit();
        }

    } else {
        // Si alguien intenta acceder directamente a este script
        echo json_encode(['status' => 'error', 'message' => 'Acceso no permitido.']);
        exit();
    }

} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit();
}

$conn = null;
?>