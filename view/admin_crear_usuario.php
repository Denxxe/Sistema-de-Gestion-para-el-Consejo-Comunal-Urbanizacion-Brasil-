<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'admin_principal') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Crear Nuevo Usuario</h2>
        <p><a href="admin_usuarios.php" class="btn btn-secondary mb-3">Volver a la Gestión de Usuarios</a></p>

        <div class="card p-4 shadow">
            <form id="crearUsuarioForm">
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="usuario">Usuario Común</option>
                        <option value="sub_admin">Sub-Administrador</option>
                        <option value="admin_principal">Administrador Principal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cedula">Cédula:</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="form-text text-muted">La contraseña será encriptada.</small>
                </div>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('crearUsuarioForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('procesar_crear_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'admin_usuarios.php'; // Redirigir después del éxito
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al procesar la solicitud.');
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>