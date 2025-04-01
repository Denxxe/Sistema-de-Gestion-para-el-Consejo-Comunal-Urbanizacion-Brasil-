<?php
include 'config/conexion.php';

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $cedula = trim($_POST["cedula"]);
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
    $correo_electronico = trim($_POST["correo_electronico"]);
    $sexo = $_POST["sexo"];
    $tipo_usuario = 2;

    if (empty($username) || empty($password) || empty($cedula) || empty($nombres) || empty($apellidos) || empty($fecha_nacimiento) || empty($correo_electronico) || empty($sexo)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_mensaje = "danger";
    } elseif (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo electrónico no válido.";
        $tipo_mensaje = "danger";
    } elseif (!preg_match('/^[0-9]{7,8}$/', $cedula)) {
        $mensaje = "Cédula no válida.";
        $tipo_mensaje = "danger";
    } else {
    
        $sql_verificar_username = "SELECT * FROM usuarios WHERE username = ?";
        $stmt_verificar_username = $conn->prepare($sql_verificar_username);
        $stmt_verificar_username->bind_param("s", $username);
        $stmt_verificar_username->execute();
        $stmt_verificar_username->store_result();

        $sql_verificar_cedula = "SELECT * FROM usuarios WHERE cedula = ?";
        $stmt_verificar_cedula = $conn->prepare($sql_verificar_cedula);
        $stmt_verificar_cedula->bind_param("s", $cedula);
        $stmt_verificar_cedula->execute();
        $stmt_verificar_cedula->store_result();

        if ($stmt_verificar_username->num_rows > 0) {
            $mensaje = "El nombre de usuario ya existe.";
            $tipo_mensaje = "danger";
        } elseif ($stmt_verificar_cedula->num_rows > 0) {
            $mensaje = "La cédula ya está registrada.";
            $tipo_mensaje = "danger";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql_insertar = "INSERT INTO usuarios (username, password, id_tipo_usuario, cedula, nombres, apellidos, fecha_nacimiento, correo_electronico, sexo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insertar = $conn->prepare($sql_insertar);
            $stmt_insertar->bind_param("ssissssss", $username, $hashed_password, $tipo_usuario, $cedula, $nombres, $apellidos, $fecha_nacimiento, $correo_electronico, $sexo);

            if ($stmt_insertar->execute()) {
                $mensaje = "Usuario registrado con éxito.";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al registrar el usuario: " . $stmt_insertar->error;
                $tipo_mensaje = "danger";
            }

            $stmt_insertar->close();
        }

        $stmt_verificar_username->close();
        $stmt_verificar_cedula->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de miembro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .register-container {
            width: 400px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4">Registro de miembro</h2>
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select class="form-control" id="sexo" name="sexo" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="correo_electronico" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
            <p class="mt-3 text-center">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>