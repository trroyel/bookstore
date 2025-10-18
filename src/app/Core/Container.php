<?php

namespace App\Core;

class Container
{
    private $services = [];

    public function get($id)
    {
        if (!isset($this->services[$id])) {
            $this->services[$id] = $this->create($id);
        }
        return $this->services[$id];
    }

    private function create($id)
    {
        if ($id === 'database') {
            return Database::getInstance();
        }
        
        if ($id === 'bookRepository') {
            return new \App\Repositories\BookRepository($this->get('database'));
        }
        
        if ($id === 'userRepository') {
            return new \App\Repositories\UserRepository($this->get('database'));
        }
        
        if ($id === 'roleRepository') {
            return new \App\Repositories\RoleRepository($this->get('database'));
        }
        
        if ($id === 'bookService') {
            return new \App\Services\BookService($this->get('bookRepository'));
        }
        
        if ($id === 'userService') {
            return new \App\Services\UserService($this->get('userRepository'));
        }
        
        if ($id === 'jwtService') {
            return new \App\Services\JwtService();
        }
        
        if ($id === 'authService') {
            return new \App\Services\AuthorizationService($this->get('roleRepository'));
        }
        
        return new $id();
    }

    public function resolve($controllerClass)
    {
        if ($controllerClass === 'App\\Controllers\\BookController') {
            return new $controllerClass($this->get('bookService'));
        }
        
        if ($controllerClass === 'App\\Controllers\\UserController') {
            return new $controllerClass($this->get('userService'), $this->get('roleRepository'));
        }
        
        if ($controllerClass === 'App\\Controllers\\DashboardController') {
            return new $controllerClass($this->get('bookService'), $this->get('userService'));
        }
        
        if ($controllerClass === 'App\\Controllers\\HomeController') {
            return new $controllerClass($this->get('bookService'), $this->get('userService'), $this->get('roleRepository'));
        }
        
        if ($controllerClass === 'App\\Controllers\\ApiController') {
            return new $controllerClass($this->get('userService'), $this->get('jwtService'));
        }
        
        if ($controllerClass === 'App\\Middleware\\ApiAuthMiddleware') {
            return new $controllerClass($this->get('jwtService'), $this->get('userRepository'));
        }
        
        return new $controllerClass();
    }
}
