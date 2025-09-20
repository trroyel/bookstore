<?php

namespace App\Repositories;

use PDO;
use App\Repositories\IBookRepository;

class BookRepository implements IBookRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM books WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO books (title, author, isbn, pages, available) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['isbn'],
            $data['pages'],
            isset($data['available']) ? (int)(bool)$data['available'] : 1
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE books SET title = ?, author = ?, isbn = ?, pages = ?, available = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['isbn'],
            $data['pages'],
            isset($data['available']) ? (int)(bool)$data['available'] : 0,
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function search($query)
    {
        $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isbnExists($isbn, $excludeId = null)
    {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM books WHERE isbn = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$isbn, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM books WHERE isbn = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$isbn]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
