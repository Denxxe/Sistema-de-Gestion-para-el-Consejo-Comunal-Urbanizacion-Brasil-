<?php
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once '../core/Router.php';

$router = new Core\Router();

// Middleware (opcional)
$router->addMiddleware('auth', function () {
    return isset($_SESSION['user_id']);
});

// Rutas públicas
$router->get('/', function () {
    require_once '../view/auth/login.php';
});
$router->get('/login', function () {
    require_once '../view/auth/login.php';
});
$router->get('/personas/crear', function () {
    require_once '../view/personas/crear.php';
});
$router->post('/personas', ['PersonaController', 'crear']);

// Rutas protegidas (si decides usar auth)
$router->get('/dashboard', ['DashboardController', 'index'], ['auth']);
$router->post('/usuarios', ['UserController', 'store'], ['auth']);

$router->handleRequest();
