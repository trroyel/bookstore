<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $middlewares = [];
    private $currentMiddleware = [];
    private $container;

    public function __construct() {
        $this->container = new Container();
    }

    public function get($path, $handler) {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler) {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler) {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function any($path, $handler) {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $this->addRoute($method, $path, $handler);
        }
        return $this;
    }

    public function group($middleware, $callback) {
        $previousMiddleware = $this->currentMiddleware;
        $this->currentMiddleware = array_merge($this->currentMiddleware, (array)$middleware);
        
        $callback($this);
        
        $this->currentMiddleware = $previousMiddleware;
        return $this;
    }

    public function middleware($name, $callback) {
        $this->middlewares[$name] = $callback;
        return $this;
    }

    /**
     * Resolve and dispatch route
     */
    public function dispatch(Request $request) {
        $method = $request->getMethod();
        $uri = $request->getUri();

        // Find matching route
        $route = $this->findRoute($method, $uri);
        
        if (!$route) {
            $this->handleNotFound();
            return;
        }

        // Extract parameters from URI
        $params = $this->extractParams($route['pattern'], $uri);
        $request->setParams($params);

        // Run middlewares
        if (!empty($route['middlewares'])) {
            foreach ($route['middlewares'] as $middlewareName) {
                if (isset($this->middlewares[$middlewareName])) {
                    $middleware = $this->middlewares[$middlewareName];
                    if (!$middleware($request)) {
                        return; // Middleware blocked the request
                    }
                }
            }
        }

        // Execute handler
        $this->executeHandler($route['handler'], $request, $params);
    }

    /**
     * Add route to routes array
     */
    private function addRoute($method, $path, $handler) {
        $pattern = $this->convertToPattern($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $this->currentMiddleware
        ];

        return $this;
    }

    /**
     * Convert route path to regex pattern
     */
    private function convertToPattern($path) {
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $path);
        
        // Convert parameters like {id} to regex groups
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^\/]+)', $pattern);
        
        // Convert optional parameters like {id?} to optional regex groups
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\?\}/', '([^\/]*)', $pattern);
        
        return '/^' . $pattern . '$/';
    }

    /**
     * Find matching route
     */
    private function findRoute($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Extract parameters from URI
     */
    private function extractParams($pattern, $uri) {
        $matches = [];
        preg_match($pattern, $uri, $matches);
        
        // Remove full match, keep only captured groups
        array_shift($matches);
        
        return $matches;
    }

    /**
     * Execute route handler
     */
    private function executeHandler($handler, Request $request, $params = []) {
        if (is_string($handler)) {
            // Handle "Controller@method" format
            if (strpos($handler, '@') !== false) {
                list($controllerClass, $method) = explode('@', $handler);
                
                // Add namespace if not present
                if (strpos($controllerClass, '\\') === false) {
                    $controllerClass = 'App\\Controllers\\' . $controllerClass;
                }
                
                if (class_exists($controllerClass)) {
                    $controller = $this->instantiateController($controllerClass);
                    if (method_exists($controller, $method)) {
                        call_user_func_array([$controller, $method], array_merge([$request], $params));
                        return;
                    }
                }
                
                $this->handleError("Method $method not found in $controllerClass");
                return;
            }
            
            // Handle function name
            if (function_exists($handler)) {
                call_user_func_array($handler, array_merge([$request], $params));
                return;
            }
            
            $this->handleError("Handler $handler not found");
            return;
        }
        
        if (is_callable($handler)) {
            // Handle closure/callable
            call_user_func_array($handler, array_merge([$request], $params));
            return;
        }
        
        $this->handleError("Invalid handler type");
    }

    private function instantiateController($controllerClass) {
        return $this->container->resolve($controllerClass);
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound() {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested page could not be found.</p>";
    }

    /**
     * Handle errors
     */
    private function handleError($message) {
        http_response_code(500);
        echo "<h1>500 - Internal Server Error</h1>";
        echo "<p>$message</p>";
    }

    /**
     * Get all registered routes
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Generate URL for named route (basic implementation)
     */
    public function url($path, $params = []) {
        $url = $path;
        
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        
        return $url;
    }

    /**
     * Redirect helper
     */
    public function redirect($url, $statusCode = 302) {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }
}
