<?php
namespace App\Controllers;

use App\Services\BookService;

class BookController extends BaseController {
    protected $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }


    public function index($request = null) {
        $books = $this->bookService->readAllBooks();
        $this->render('books/list', ['books' => $books]);
    }


    public function show($request, $id) {
        $book = $this->bookService->read($id);
        
        if (!$book) {
            $this->setFlash('error', 'Book not found');
            $this->redirect('/books');
            return;
        }
        
        $this->render('books/show', ['book' => $book]);
    }


    public function create($request = null) {
        $this->render('books/create');
    }


    public function store($request = null) {
        if (!$this->isPost()) {
            $this->redirect('/books/create');
            return;
        }

        $data = $this->sanitize($this->getPostData());
        
        // Validate required fields
        $errors = $this->validateRequired($data, ['title', 'author', 'isbn', 'pages']);
        
        // Validate pages is numeric and positive
        if (empty($errors) && (!is_numeric($data['pages']) || $data['pages'] <= 0)) {
            $errors[] = 'Pages must be a valid positive number';
        }
        
        if (!empty($errors)) {
            $this->render('books/create', ['errors' => $errors, 'data' => $data]);
            return;
        }

        // Convert pages to integer and available to boolean
        $data['pages'] = intval($data['pages']);
        $data['available'] = isset($data['available']) ? (bool)$data['available'] : true;

        // Create book
        try {
            $book = $this->bookService->create($data);
            $this->setFlash('success', 'Book added successfully');
            $this->redirect('/books');
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            $this->render('books/create', ['errors' => $errors, 'data' => $data]);
        }
    }


    public function edit($request, $id) {
        
        $book = $this->bookService->read($id);
        
        if (!$book) {
            $this->setFlash('error', 'Book not found');
            $this->redirect('/books');
            return;
        }
        
        $this->render('books/create', ['data' => $book, 'isEdit' => true]);
    }


    public function update($request, $id) {
        if (!$this->isPost()) {
            $this->redirect('/books/' . $id . '/edit');
            return;
        }

        $book = $this->bookService->read($id);
        if (!$book) {
            $this->setFlash('error', 'Book not found');
            $this->redirect('/books');
            return;
        }

        $data = $this->sanitize($this->getPostData());
        
        // Validate required fields
        $errors = $this->validateRequired($data, ['title', 'author', 'isbn', 'pages']);
        
        // Validate pages is numeric and positive
        if (empty($errors) && (!is_numeric($data['pages']) || $data['pages'] <= 0)) {
            $errors[] = 'Pages must be a valid positive number';
        }
        
        if (!empty($errors)) {
            $this->render('books/edit', ['errors' => $errors, 'book' => $data]);
            return;
        }

        // Convert pages to integer and available to boolean
        $data['pages'] = intval($data['pages']);
        $data['available'] = isset($data['available']) ? (bool)$data['available'] : true;

        // Update book
        try {
            $this->bookService->update($id, $data);
            $this->setFlash('success', 'Book updated successfully');
            $this->redirect('/books');
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            $this->render('books/create', ['errors' => $errors, 'data' => $data, 'isEdit' => true]);
        }
    }


    public function delete($request, $id) {
        
        $book = $this->bookService->read($id);
        if (!$book) {
            $this->setFlash('error', 'Book not found');
            $this->redirect('/books');
            return;
        }

        $this->bookService->delete($id);
        $this->setFlash('success', 'Book deleted successfully');
        $this->redirect('/books');
    }


    public function search($request = null) {
        $query = isset($_GET['q']) ? $this->sanitize($_GET['q']) : '';
        
        if (empty($query)) {
            $this->redirect('/books');
            return;
        }
        
        $books = $this->bookService->search($query);
        $this->render('books/list', ['books' => $books, 'searchQuery' => $query]);
    }


    public function api() {
        $books = $this->bookService->readAllBooks();
        $this->json(['success' => true, 'data' => $books]);
    }


    public function apiShow($request, $id) {
        $book = $this->bookService->read($id);
        
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found'], 404);
            return;
        }
        
        $this->json(['success' => true, 'data' => $book]);
    }
}
