<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_rol"] !== 'sub_admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Sub-Administrador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Bienvenido, Sub-Administrador</h1>
            <p class="lead">Este es tu panel de control.</p>
            <hr class="my-4">
            <p>Aquí podrás gestionar las áreas que te han sido asignadas.</p>
            <a class="btn btn-danger btn-lg" href="logout.php" role="button">Cerrar Sesión</a>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Pagos</h5>
                        <p class="card-text">Opciones para la gestión de pagos de los usuarios.</p>
                        <a href="admin_verificar_pagos.php" class="btn btn-info btn-block mb-2">Verificación de Pagos</a>
                        <a href="admin_ver_historial_usuario.php" class="btn btn-primary btn-block">Ver Historial de Pagos por Usuario</a>
                    </div>
                </div>
            </div>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>