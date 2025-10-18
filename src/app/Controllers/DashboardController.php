<?php
namespace App\Controllers;

class DashboardController extends BaseController {
    private $bookService;
    private $userService;
    
    public function __construct($bookService, $userService) {
        $this->bookService = $bookService;
        $this->userService = $userService;
    }
    
    /**
     * Display dashboard with statistics
     */
    public function index($request = null) {
        $user = $this->getCurrentUser();
        $container = new \App\Core\Container();
        $authService = $container->get('authService');
        
        // Check if user can view admin dashboard
        $canViewUsers = $authService->can($user, 'user:read');
        
        if ($canViewUsers) {
            // Admin/Manager dashboard
            $books = $this->bookService->readAllBooks();
            $totalBooks = $this->bookService->getCount();
            $totalUsers = $this->userService->getCount();
            
            $stats = [
                'total_books' => $totalBooks,
                'total_users' => $totalUsers,
                'recent_books' => array_slice($books, -5),
            ];
            
            $this->render('dashboard/index', ['books' => $books, 'stats' => $stats]);
        } else {
            // Regular user dashboard
            $books = $this->bookService->readAllBooks();
            $this->render('dashboard/user', ['books' => $books, 'user' => $user]);
        }
    }
}