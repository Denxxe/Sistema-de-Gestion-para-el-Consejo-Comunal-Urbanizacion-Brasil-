<?php
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once '../core/Router.php';
require_once '../controllers/personaController.php';
require_once '../controllers/rolController.php';
require_once '../controllers/permisoController.php';
require_once '../controllers/habitanteController.php';
require_once '../controllers/pagoController.php';
require_once '../controllers/authController.php';

$router = new App\Core\Router();

// Middleware (opcional)
$router->addMiddleware('auth', function () {
    return isset($_SESSION['user_id']);
});

// Configuración de CORS para desarrollo
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

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
$router->get('/personas', function () {
    require_once '../view/personas/listar.php';
});
$router->get('/personas/actualizar/:id', function ($id) {
    require_once '../view/personas/actualizar.php';
});
$router->post('/personas', ['PersonaController', 'crear']);
$router->put('/personas/:id', ['PersonaController', 'actualizar']);
$router->delete('/personas/:id', ['PersonaController', 'eliminar']);

// Rutas protegidas (si decides usar auth)
$router->get('/dashboard', ['DashboardController', 'index'], ['auth']);
$router->post('/usuarios', ['UserController', 'store'], ['auth']);

$router->handleRequest();

$router->get('/personas/editar/:id', [PersonaController::class, 'edit'], ['auth']);
$router->post('/personas/actualizar/:id', [PersonaController::class, 'update'], ['auth']);
$router->post('/personas/eliminar/:id', [PersonaController::class, 'delete'], ['auth']);

// Rutas de Roles
$router->get('/roles', [RolController::class, 'index'], ['auth']);
$router->get('/roles/crear', [RolController::class, 'create'], ['auth']);
$router->post('/roles', [RolController::class, 'store'], ['auth']);
$router->get('/roles/editar/:id', [RolController::class, 'edit'], ['auth']);
$router->post('/roles/actualizar/:id', [RolController::class, 'update'], ['auth']);
$router->post('/roles/eliminar/:id', [RolController::class, 'delete'], ['auth']);

// Rutas de Permisos
$router->get('/permisos', [PermisoController::class, 'index'], ['auth']);
$router->get('/permisos/crear', [PermisoController::class, 'create'], ['auth']);
$router->post('/permisos', [PermisoController::class, 'store'], ['auth']);
$router->get('/permisos/editar/:id', [PermisoController::class, 'edit'], ['auth']);
$router->post('/permisos/actualizar/:id', [PermisoController::class, 'update'], ['auth']);
$router->post('/permisos/eliminar/:id', [PermisoController::class, 'delete'], ['auth']);

// Rutas protegidas (si decides usar auth)
$router->get('/dashboard', ['DashboardController', 'index'], ['auth']);
$router->post('/usuarios', ['UserController', 'store'], ['auth']);

$router->handleRequest();
