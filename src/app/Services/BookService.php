<?php
namespace App\Services;

class BookService {
    private $bookRepository;

    public function __construct($bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    public function readAllBooks() {
        return $this->bookRepository->findAll();
    }

    public function create($book) {
        // Check if ISBN already exists
        if (isset($book['isbn']) && $this->bookRepository->isbnExists($book['isbn'])) {
            throw new \Exception('ISBN already exists');
        }
        
        $id = $this->bookRepository->create($book);
        $book['id'] = $id;
        return $book;
    }

    public function read($id) {
        return $this->bookRepository->findById($id);
    }

    public function update($id, $updatedBook) {
        // Check if ISBN already exists (excluding current book)
        if (isset($updatedBook['isbn']) && $this->bookRepository->isbnExists($updatedBook['isbn'], $id)) {
            throw new \Exception('ISBN already exists');
        }
        
        $rowCount = $this->bookRepository->update($id, $updatedBook);
        return $rowCount > 0 ? $this->bookRepository->findById($id) : null;
    }

    public function delete($id) {
        return $this->bookRepository->delete($id) > 0;
    }

    public function search($query) {
        return $this->bookRepository->search($query);
    }

    public function getCount() {
        return count($this->readAllBooks());
    }
}
