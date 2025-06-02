<?php session_start(); ?>
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
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #3498db;
            --sidebar-text: white;
            --transition-speed: 0.3s;
        }

        /* Estilos base del sidebar */
        .sidebar {
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            padding: 1rem;
            z-index: 1000;
            transition: transform var(--transition-speed) ease;
        }

        /* Header del sidebar */
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }

        .sidebar-header:hover {
            background-color: var(--sidebar-hover);
        }

        .sidebar-header i {
            font-size: 1.5rem;
        }

        /* Navegación */
        .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
        }

        .nav-link.active {
            background-color: var(--sidebar-hover);
            font-weight: 500;
        }

        /* Contenido principal */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left var(--transition-speed) ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .toggle-btn {
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background-color: var(--sidebar-bg);
                color: var(--sidebar-text);
                border: none;
                padding: 0.5rem;
                border-radius: 0.5rem;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .toggle-btn:hover {
                background-color: var(--sidebar-hover);
            }
        }

        /* Submenus */
        .submenu {
            display: none;
            padding-left: 1rem;
        }

        .nav-link.active + .submenu {
            display: block;
        }

        /* Animaciones */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-home"></i>
            <span>Urbanización Brasil</span>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link <?php echo $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>" href="/dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/personas') !== false ? 'active' : '' ?>" href="/personas">
                <i class="fas fa-users"></i> Personas
            </a>
            
            <a class="nav-link" href="#" data-toggle="submenu">
                <i class="fas fa-user-shield"></i> Roles
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="submenu">
                <a class="nav-link" href="/roles/listar">
                    <i class="fas fa-list"></i> Listar Roles
                </a>
                <a class="nav-link" href="/roles/crear">
                    <i class="fas fa-plus"></i> Crear Rol
                </a>
            </div>

            <a class="nav-link" href="/permisos">
                <i class="fas fa-key"></i> Permisos
            </a>
            
            <a class="nav-link" href="/habitantes">
                <i class="fas fa-home"></i> Habitantes
            </a>
            
            <a class="nav-link" href="/pagos">
                <i class="fas fa-money-bill"></i> Pagos
            </a>
            
            <a class="nav-link" href="/logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Botón de toggle para móvil -->
        <button class="toggle-btn" id="toggleBtn">
            <i class="fas fa-bars"></i>
            <span>Menú</span>
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
