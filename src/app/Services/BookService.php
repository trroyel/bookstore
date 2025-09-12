<?php
namespace App\Services;

class BookService {
    private $file = __DIR__ . '/../../storage/books.json';

    /**
     * Get all books
     */
    public function readAllBooks() {
        if (!file_exists($this->file)) {
            return [];
        }
        $json = file_get_contents($this->file);
        $books = json_decode($json, true);
        return is_array($books) ? $books : [];
    }

    /**
     * Create a new book
     */
    public function create($book) {
        $books = $this->readAllBooks();
        
        // Generate new ID
        $book['id'] = $this->generateId($books);
        
        // Add book to array
        $books[] = $book;
        
        // Save to file
        file_put_contents($this->file, json_encode($books, JSON_PRETTY_PRINT));
        return $book;
    }

    /**
     * Get a single book by ID
     */
    public function read($id) {
        $books = $this->readAllBooks();
        foreach ($books as $book) {
            if ($book['id'] == $id) {
                return $book;
            }
        }
        return null;
    }

    /**
     * Update an existing book
     */
    public function update($id, $updatedBook) {
        $books = $this->readAllBooks();
        foreach ($books as &$book) {
            if ($book['id'] == $id) {
                $book = array_merge($book, $updatedBook);
                $book['id'] = $id; // Ensure ID doesn't change
                file_put_contents($this->file, json_encode($books, JSON_PRETTY_PRINT));
                return $book;
            }
        }
        return null;
    }

    /**
     * Delete a book by ID
     */
    public function delete($id) {
        $books = $this->readAllBooks();
        foreach ($books as $key => $book) {
            if ($book['id'] == $id) {
                unset($books[$key]);
                // Re-index array to maintain proper JSON structure
                $books = array_values($books);
                file_put_contents($this->file, json_encode($books, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }

    /**
     * Search books by title, author, or ISBN
     */
    public function search($query) {
        $books = $this->readAllBooks();
        $results = [];
        
        foreach ($books as $book) {
            if (
                stripos($book['title'], $query) !== false ||
                stripos($book['author'], $query) !== false ||
                stripos($book['isbn'], $query) !== false
            ) {
                $results[] = $book;
            }
        }
        
        return $results;
    }

    /**
     * Get books count
     */
    public function getCount() {
        return count($this->readAllBooks());
    }
    
    /**
     * Generate new ID
     */
    private function generateId($items) {
        return empty($items) ? 1 : max(array_column($items, 'id')) + 1;
    }
}
