<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../public/assets/css/logincss.css">
</head>
<body class="animated-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-header">
                        Bienvenido
                    </div>
                    <div class="card-body">
                        <form action=". /login" method="POST">
                            <div class="mb-4">
                                <label for="cedula" class="form-label">
                                    <i class="fas fa-user-circle"></i> Cédula:
                                </label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required autocomplete="off" placeholder="Ingresa tu cédula">
                            </div>
                            <div class="mb-5">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key"></i> Contraseña:
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Ingresa tu contraseña">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-right-to-bracket me-2"></i> Acceder
                                </button>
                            </div>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger text-center" role="alert">
                                    <?php 
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyN9GkcIdslK1eJ7Q+CwofK" crossorigin="anonymous"></script>
</body>
</html>