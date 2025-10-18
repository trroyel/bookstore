<?php

// Bootstrap application
require_once __DIR__ . '/../bootstrap.php';

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/signup', 'HomeController@signup');
$router->post('/signup', 'HomeController@register');
$router->get('/login', 'UserController@login');
$router->post('/login', 'UserController@authenticate');
$router->get('/logout', 'UserController@logout');

// Protected routes (require authentication)
$router->group('auth', function ($router) {
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
});

// Public API routes
$router->post('/api/token', 'ApiController@generateToken');

// Protected API routes (require API token)
$router->group('api', function ($router) {
    $router->get('/api/books', 'BookController@api');
    $router->get('/api/books/{id}', 'BookController@apiShow');
});

// Dispatch request
$router->dispatch($request);
