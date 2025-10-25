<?php

namespace App\Repositories;

interface IRoleRepository
{
    public function findAll();
    public function findByName($name);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
