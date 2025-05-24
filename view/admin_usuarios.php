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

    // Obtener todos los usuarios
    $stmt = $conn->prepare("SELECT id, rol, cedula, nombre, apellido, email FROM usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    $usuarios = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Usuarios</h2>
        <p><a href="admin_dashboard.php" class="btn btn-secondary mb-3">Volver al Dashboard</a> <a href="admin_crear_usuario.php" class="btn btn-success mb-3">Crear Nuevo Usuario</a></p>

        <?php if (!empty($usuarios)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rol</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo $usuario['rol']; ?></td>
                            <td><?php echo $usuario['cedula']; ?></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['apellido']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td>
                                <a href="admin_editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="admin_eliminar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay usuarios registrados.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>