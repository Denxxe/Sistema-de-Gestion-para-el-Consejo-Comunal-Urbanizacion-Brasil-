<?php
// Obtener la ruta base del sitio
$base_url = ''; // Usar rutas relativas
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$is_active = function($path) use ($current_uri) {
    return strpos($current_uri, $path) !== false ? 'active' : '';
};
?>
<!-- Sidebar Component -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-home"></i>
        <span>Urbanización Brasil</span>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link <?= $is_active('/dashboard') ?>" href="dashboard">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <a class="nav-link <?= $is_active('/personas') ?>" href="personas">
            <i class="fas fa-users"></i> Personas
        </a>
        
        <a class="nav-link <?= $is_active('/roles') ?>" href="#" data-toggle="submenu">
            <i class="fas fa-user-shield"></i> Roles
            <i class="fas fa-chevron-down"></i>
        </a>
        <div class="submenu">
            <a class="nav-link <?= $is_active('/roles/listar') ?>" href="roles">
                <i class="fas fa-list"></i> Listar Roles
            </a>
            <a class="nav-link <?= $is_active('/roles/crear') ?>" href="roles/crear">
                <i class="fas fa-plus"></i> Crear Rol
            </a>
        </div>

        <a class="nav-link <?= $is_active('/permisos') ?>" href="permisos">
            <i class="fas fa-key"></i> Permisos
        </a>
        
        <a class="nav-link <?= $is_active('/habitantes') ?>" href="habitantes">
            <i class="fas fa-home"></i> Habitantes
        </a>
        
        <a class="nav-link <?= $is_active('/pagos') ?>" href="pagos">
            <i class="fas fa-money-bill"></i> Pagos
        </a>
        
        <a class="nav-link" href="logout">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </nav>
</aside>

<!-- Botón de toggle para móvil -->
<button class="toggle-btn" id="toggleBtn">
    <i class="fas fa-bars"></i>
    <span>Menú</span>
</button>

<!-- Incluir estilos y scripts del sidebar -->
<link rel="stylesheet" href="assets/css/sidebar.css">
<script src="assets/js/sidebar.js" defer></script>
