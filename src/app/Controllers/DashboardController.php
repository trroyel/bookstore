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
        
        $books = $this->bookService->readAllBooks();
        $totalBooks = $this->bookService->getCount();
        $totalUsers = $this->userService->getCount();
        
        $stats = [
            'total_books' => $totalBooks,
            'total_users' => $totalUsers,
            'recent_books' => array_slice($books, -5),
        ];
        
        $this->render('dashboard/index', ['books' => $books, 'stats' => $stats]);
    }
}