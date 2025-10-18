<?php
namespace App\Controllers;

class HomeController extends BaseController {
    private $bookService;
    private $userService;
    private $roleRepository;
    
    public function __construct($bookService, $userService, $roleRepository = null) {
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->roleRepository = $roleRepository;
    }
    
    /**
     * Display public homepage
     */
    public function index($request = null) {
        $books = array_slice($this->bookService->readAllBooks(), 0, 3);
        $this->render('home/index', ['books' => $books]);
    }
    
    /**
     * Display signup form
     */
    public function signup($request = null) {
        $this->render('home/signup', ['csrf_token' => $this->generateCsrfToken()]);
    }
    
    /**
     * Process signup
     */
    public function register($request = null) {
        if (!$this->isPost()) {
            $this->redirect('/signup');
            return;
        }

        $data = $this->sanitize($this->getPostData());
        
        // Validate CSRF token
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $this->render('home/signup', ['errors' => ['Invalid security token'], 'csrf_token' => $this->generateCsrfToken()]);
            return;
        }
        
        $errors = $this->validateRequired($data, ['name', 'email', 'password', 'password_confirm']);
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Passwords do not match';
        }
        
        if (strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }
        
        if (!empty($errors)) {
            $this->render('home/signup', ['errors' => $errors, 'data' => $data, 'csrf_token' => $this->generateCsrfToken()]);
            return;
        }

        if ($this->userService->findByEmail($data['email'])) {
            $this->render('home/signup', ['errors' => ['Email already exists'], 'data' => $data, 'csrf_token' => $this->generateCsrfToken()]);
            return;
        }

        unset($data['password_confirm']);
        
        // Set default role_id to 'user'
        if ($this->roleRepository) {
            $userRole = $this->roleRepository->findByName('user');
            if ($userRole) {
                $data['role_id'] = $userRole['id'];
            }
        }
        
        $user = $this->userService->create($data);
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        
        $this->setFlash('success', 'Welcome to BookStore!');
        $this->redirect('/dashboard');
    }
}