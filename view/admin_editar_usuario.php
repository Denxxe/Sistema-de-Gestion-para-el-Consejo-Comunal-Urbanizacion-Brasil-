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

    // Obtener la información del usuario a editar
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $user_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT id, rol, cedula, nombre, apellido, email FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Si no se encuentra el usuario, redirigir a la lista de usuarios
            header("Location: admin_usuarios.php?mensaje=Usuario no encontrado.&tipo=warning");
            exit();
        }
    } else {
        // Si no se proporciona un ID válido, redirigir
        header("Location: admin_usuarios.php?mensaje=ID de usuario inválido.&tipo=danger");
        exit();
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
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <p><a href="admin_usuarios.php" class="btn btn-secondary mb-3">Volver a la Gestión de Usuarios</a></p>

        <div class="card p-4 shadow">
            <form action="procesar_editar_usuario.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="usuario" <?php if ($usuario['rol'] === 'usuario') echo 'selected'; ?>>Usuario Común</option>
                        <option value="sub_admin" <?php if ($usuario['rol'] === 'sub_admin') echo 'selected'; ?>>Sub-Administrador</option>
                        <option value="admin_principal" <?php if ($usuario['rol'] === 'admin_principal') echo 'selected'; ?>>Administrador Principal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cedula">Cédula:</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" value="<?php echo $usuario['cedula']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Nueva Contraseña (opcional):</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Si dejas este campo vacío, la contraseña actual no se modificará.</small>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>