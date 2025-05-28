<?php

require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
require_once '../core/App.php';

// Crear instancia del router
$router = new Core\Router();

// Middleware de ejemplo para autenticación
$router->addMiddleware('auth', function() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        return false;
    }
    return true;
});

// Definir rutas
$router->get('/', ['HomeController', 'index']);
$router->get('/login', ['AuthController', 'loginForm']);
$router->post('/login', ['AuthController', 'login']);

// Rutas protegidas con middleware
$router->get('/dashboard', ['DashboardController', 'index'], ['auth']);
$router->get('/usuarios/{id}', ['UserController', 'show'], ['auth']);
$router->post('/usuarios', ['UserController', 'store'], ['auth']);

// Manejar la solicitud
$router->handleRequest();
