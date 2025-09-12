<?php
// Start session at the very beginning
session_start();

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Include core classes
require_once __DIR__ . '/../app/Core/Request.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Container.php';

use App\Core\Request;
use App\Core\Router;

// Create request and router instances
$request = new Request();
$router = new Router();

// Add authentication middleware
$router->middleware('auth', function($request) {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    return true;
});

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/signup', 'HomeController@signup');
$router->post('/signup', 'HomeController@register');
$router->get('/login', 'UserController@login');
$router->post('/login', 'UserController@authenticate');
$router->get('/logout', 'UserController@logout');

// Protected routes (require authentication)
$router->group('auth', function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    $router->get('/books', 'BookController@index');
    $router->get('/books/create', 'BookController@create');
    $router->get('/books/search', 'BookController@search');
    $router->post('/books', 'BookController@store');
    $router->get('/books/{id}', 'BookController@show');
    $router->get('/books/{id}/edit', 'BookController@edit');
    $router->post('/books/{id}/update', 'BookController@update');
    $router->post('/books/{id}/delete', 'BookController@delete');
    
    $router->get('/users', 'UserController@index');
    $router->get('/users/create', 'UserController@create');
    $router->post('/users', 'UserController@store');
    $router->get('/users/search', 'UserController@search');
    $router->get('/users/{id}', 'UserController@show');
    $router->get('/users/{id}/edit', 'UserController@edit');
    $router->post('/users/{id}/update', 'UserController@update');
    $router->post('/users/{id}/delete', 'UserController@delete');
    
    $router->get('/api/books', 'BookController@api');
    $router->get('/api/books/{id}', 'BookController@apiShow');
});

// Dispatch the request
$router->dispatch($request);
