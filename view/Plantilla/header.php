<?php 
session_start();
// Obtener la ruta base del sitio
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión - Urbanización Brasil</title>
    <!-- Bootstrap CSS -->
    <link href="../public/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        /* Estilos generales */
        body {
            padding-top: 60px;
            transition: margin-left 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Contenido principal */
        .main-content {
            padding: 20px;
            margin-left: 0;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
        }

        @media (min-width: 769px) {
            .main-content {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Incluir el sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- El contenido de la página irá aquí -->
        </button>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
