<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
</head>
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
                            session_start();
                            if (isset($_SESSION['error'])) {
                                echo "<p style='color: red;'>{$_SESSION['error']}</p>";
                                unset($_SESSION['error']);
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="/public/assets/js/login.js" defer></script>
<script src="../../public/assets/js/bootstrap.bundle.min.js"></script>
<script src="../../public/assets/js/chart.min.js"></script>
</html>
