<?php

if (PHP_SAPI === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $staticFile = __DIR__ . DIRECTORY_SEPARATOR . ltrim($requestedPath, '/');
    if ($requestedPath !== '/' && is_file($staticFile)) {
        return false;
    }
}

session_start();
header('Content-Type: text/html; charset=utf-8');

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = APP_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\AuthController;
use App\Controllers\EmployeeController;
use App\Core\Router;

$router = new Router();

$router->get('/', [EmployeeController::class, 'index']);
$router->post('/employees/store', [EmployeeController::class, 'store']);
$router->post('/employees/update', [EmployeeController::class, 'update']);
$router->post('/employees/delete', [EmployeeController::class, 'delete']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
