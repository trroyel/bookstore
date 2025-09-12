<?php
namespace App\Core;

class Container {
    private $services = [];
    
    public function get($className) {
        if (!isset($this->services[$className])) {
            $this->services[$className] = new $className();
        }
        return $this->services[$className];
    }
    
    public function resolve($controllerClass) {
        $bookService = $this->get('\\App\\Services\\BookService');
        $userService = $this->get('\\App\\Services\\UserService');
        
        if ($controllerClass === 'App\\Controllers\\BookController') {
            return new $controllerClass($bookService);
        }
        
        if ($controllerClass === 'App\\Controllers\\UserController') {
            return new $controllerClass($userService);
        }
        
        if ($controllerClass === 'App\\Controllers\\DashboardController') {
            return new $controllerClass($bookService, $userService);
        }
        
        if ($controllerClass === 'App\\Controllers\\HomeController') {
            return new $controllerClass($bookService);
        }
        
        return new $controllerClass();
    }
}