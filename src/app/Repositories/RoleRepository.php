<?php

namespace App\Repositories;

use PDO;
use App\Repositories\IRoleRepository;

class RoleRepository implements IRoleRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM roles ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByName($name)
    {
        $sql = "SELECT * FROM roles WHERE name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM roles WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO roles (name, description, permissions) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['permissions']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE roles SET name = ?, description = ?, permissions = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['permissions'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM roles WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
