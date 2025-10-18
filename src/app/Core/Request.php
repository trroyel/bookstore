<?php
namespace App\Core;

// Request: Handles HTTP requests
class Request {
    private $method;
    private $uri;
    private $params;
    private $query;
    private $body;
    private $headers;
    private $user;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->params = [];
        $this->query = $_GET ?? [];
        $this->body = $this->parseBody();
        $this->headers = $this->parseHeaders();
    }

    /**
     * Get HTTP method (GET, POST, PUT, DELETE, etc.)
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Get request URI
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Get route parameters
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Get specific route parameter
     */
    public function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    /**
     * Set route parameters (used by router)
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * Get query string parameters ($_GET)
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Get specific query parameter
     */
    public function getQueryParam($key, $default = null) {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get request body (POST data, JSON, etc.)
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Get specific body parameter
     */
    public function getBodyParam($key, $default = null) {
        return $this->body[$key] ?? $default;
    }

    /**
     * Get all request headers
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Get specific header
     */
    public function getHeader($key, $default = null) {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }

    /**
     * Check if request is POST
     */
    public function isPost() {
        return $this->method === 'POST';
    }

    /**
     * Check if request is GET
     */
    public function isGet() {
        return $this->method === 'GET';
    }

    /**
     * Check if request is PUT
     */
    public function isPut() {
        return $this->method === 'PUT';
    }

    /**
     * Check if request is DELETE
     */
    public function isDelete() {
        return $this->method === 'DELETE';
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax() {
        return $this->getHeader('x-requested-with') === 'XMLHttpRequest';
    }

    /**
     * Check if request expects JSON response
     */
    public function wantsJson() {
        $acceptHeader = $this->getHeader('accept', '');
        return strpos($acceptHeader, 'application/json') !== false || $this->isAjax();
    }

    /**
     * Get client IP address
     */
    public function getClientIp() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get user agent
     */
    public function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Parse URI from request
     */
    private function parseUri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove leading and trailing slashes
        $uri = trim($uri, '/');
        
        // Return root if empty
        return $uri === '' ? '/' : '/' . $uri;
    }

    /**
     * Parse request body
     */
    private function parseBody() {
        $contentType = $this->getHeader('content-type', '');
        
        if (strpos($contentType, 'application/json') !== false) {
            // Parse JSON body
            $json = file_get_contents('php://input');
            return json_decode($json, true) ?? [];
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false || 
                  strpos($contentType, 'multipart/form-data') !== false) {
            // Parse form data
            return $_POST ?? [];
        } else {
            // Raw body
            return file_get_contents('php://input');
        }
    }

    /**
     * Parse request headers
     */
    private function parseHeaders() {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $header = substr($key, 5);
                $header = str_replace('_', '-', $header);
                $header = strtolower($header);
                $headers[$header] = $value;
            }
        }
        
        // Add content type and content length if available
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['content-length'] = $_SERVER['CONTENT_LENGTH'];
        }
        
        return $headers;
    }

    /**
     * Validate CSRF token (if implemented)
     */
    public function validateCsrf($token) {
        session_start();
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return hash_equals($sessionToken, $token);
    }

    /**
     * Get all input data (query + body)
     */
    public function all() {
        return array_merge($this->query, (array)$this->body);
    }

    /**
     * Get specific input value
     */
    public function input($key, $default = null) {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }
}
