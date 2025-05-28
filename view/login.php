<?php
if(isset($_POST['error'])) {
if($_POST['error'] == 1) {
echo '<div class="alert alert-danger mt-3" role="alert">Credenciales incorrectas.</div>';
} elseif ($_POST['error'] == 2) {
 echo '<div class="alert alert-warning mt-3" role="alert">Por favor, complete todos los campos.</div>';
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css"> </head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow">
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>
                    <form action="procesar_login.php" method="POST">
                        <div class="form-group">
                            <label for="cedula">Cédula:</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Acceder</button>
                        <?php
                            if(isset($_GET['error'])) {
                                echo '<div class="alert alert-danger mt-3" role="alert">Credenciales incorrectas.</div>';
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>