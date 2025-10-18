<?php

// Start session
session_start();

// Load .env variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && $line[0] !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
});

use App\Core\Request;
use App\Core\Router;

// Create application instances
$request = new Request();
$router = new Router();

// Authentication middleware
$router->middleware('auth', function ($request) {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    return true;
});

// API authentication middleware
$router->middleware('api', function ($request) use ($router) {
    $container = new \App\Core\Container();
    $apiAuth = $container->resolve('App\\Middleware\\ApiAuthMiddleware');
    return $apiAuth->handle($request);
});
