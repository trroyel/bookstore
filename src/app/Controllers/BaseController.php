<?php
namespace App\Controllers;

// BaseController: Common controller logic
abstract class BaseController {
    
    /**
     * Render a view with data
     */
    protected function render($view, $data = []) {
        // Sanitize view path to prevent directory traversal
        $view = str_replace(['../', '..\\', '//', '\\\\'], '', $view);
        $view = trim($view, '/\\');
        
        // Extract data to variables
        extract($data);
        
        // Include the view file
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        $realPath = realpath($viewPath);
        $baseDir = realpath(__DIR__ . '/../Views/');
        
        if (!$realPath || !$baseDir || strpos($realPath, $baseDir) !== 0) {
            throw new \Exception("View not found: " . $view);
        }
        
        include $viewPath;
    }
    

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        // Validate URL to prevent open redirects
        if (!$this->isValidRedirectUrl($url)) {
            $url = '/';
        }
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Validate redirect URL
     */
    private function isValidRedirectUrl($url) {
        // Allow relative URLs starting with /
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }
        return false;
    }
    
    /**
     * Get request method
     */
    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Get POST data
     */
    protected function getPostData() {
        return $_POST;
    }
    
    /**
     * Get GET data
     */
    protected function getGetData() {
        return $_GET;
    }
    
    /**
     * Validate required fields
     */
    protected function validateRequired($data, $requiredFields) {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }
        return $errors;
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $this->getMethod() === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet() {
        return $this->getMethod() === 'GET';
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    protected function getFlash($type) {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Check if user has permission
     */
    protected function authorize($permission) {
        $container = new \App\Core\Container();
        $authService = $container->get('authService');
        $user = $this->getCurrentUser();
        
        if (!$authService->can($user, $permission)) {
            $this->setFlash('error', 'You do not have permission to perform this action');
            $this->redirect('/dashboard');
            exit;
        }
    }
    
    /**
     * Check if user is owner or has permission
     */
    protected function authorizeOwnerOr($resourceUserId, $permission) {
        $container = new \App\Core\Container();
        $authService = $container->get('authService');
        $user = $this->getCurrentUser();
        
        if (!$authService->isOwner($user, $resourceUserId) && !$authService->can($user, $permission)) {
            $this->setFlash('error', 'You do not have permission to perform this action');
            $this->redirect('/dashboard');
            exit;
        }
    }
}
