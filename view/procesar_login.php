<?php
session_start();

// Datos de conexión a la base de datos
$servername = "localhosty";
$username = "root"; // Reemplaza con tu nombre de usuario de la base de datos
$password_db = ""; // Reemplaza con tu contraseña de la base de datos
$dbname = "consejo_comunal";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos no estén vacíos
    if (empty($_POST["cedula"]) || empty($_POST["password"])) {
        header("Location: login.php?error=2"); // Error 2: Campos vacíos
        exit();
    }

    $cedula = $_POST["cedula"];
    $password = $_POST["password"];

    // Buscar el usuario en la base de datos por cédula
    $stmt = $conn->prepare("SELECT id, rol, password FROM usuarios WHERE cedula = :cedula");
    $stmt->bindParam(':cedula', $cedula);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_rol"] = $user["rol"];

            switch ($_SESSION["user_rol"]) {
                case 'admin_principal':
                    header("Location: admin_dashboard.php");
                    break;
                case 'sub_admin':
                    header("Location: subadmin_dashboard.php");
                    break;
                case 'usuario':
                    header("Location: usuario_dashboard.php");
                    break;
                default:
                    header("Location: index.php");
                    break;
            }
            exit();
        } else {
            header("Location: login.php?error=1"); // Error 1: Credenciales incorrectas
            exit();
        }
    } else {
        header("Location: login.php?error=1"); // Error 1: Credenciales incorrectas
        exit();
    }
    $conn = null;
} else {
    header("Location: login.php");
    exit();
}
?>