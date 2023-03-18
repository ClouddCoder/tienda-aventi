<?php

use Tienda\App\Controllers\DocumentController;
use Tienda\App\Controllers\UserController;
use Tienda\App\Controllers\ProductController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {

    $router->get('/', [DocumentController::class, 'index']);
    $router->get('/admin-panel', [DocumentController::class, 'adminPanel']);
    $router->get('/register', [DocumentController::class, 'register']);
    $router->post('/register-data', [UserController::class, 'register']);
    $router->get('/login', [DocumentController::class, 'login']);
    $router->post('/login-data', [UserController::class, 'login']);
    $router->get('/forgot-password', [DocumentController::class, 'forgotPassword']);
    $router->post('/forgot-password-data', [UserController::class, 'forgotPassword']);
    $router->get('/user-profile', [DocumentController::class, 'userProfile']);
    $router->get('/edit-email', [DocumentController::class, 'editEmail']);
    $router->post('/edit-email-data', [UserController::class, 'editEmail']);
    $router->get('/edit-password', [DocumentController::class, 'editPassword']);
    $router->post('/edit-password-data', [UserController::class, 'editPassword']);
    $router->get('/edit-username', [DocumentController::class, 'editUsername']);
    $router->post('/edit-username-data', [UserController::class, 'editUsername']);
    $router->get('/edit-phone', [DocumentController::class, 'editPhone']);
    $router->post('/edit-phone-data', [UserController::class, 'editPhone']);
    $router->get('/all-users', [DocumentController::class, 'allUsers']);
    $router->post('/delete-user', [UserController::class, 'deleteUser']);
    $router->get('/all-supervisors', [DocumentController::class, 'allSupervisors']);
    $router->get('/all-products', [DocumentController::class, 'allProducts']);
    $router->post('/create-product', [ProductController::class, 'createProduct']);
    $router->post('/delete-product', [ProductController::class, 'deleteProduct']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];

        list($class, $method) = array($handler[0], $handler[1]);
        call_user_func_array(array(new $class, $method), [array_merge($_POST, $_GET, $_FILES, $routeInfo[2])]);
        // ... call $handler with $vars
        break;
}
