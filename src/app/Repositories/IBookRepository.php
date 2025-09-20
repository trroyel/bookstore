<?php

namespace App\Repositories;

interface IBookRepository
{
    public function findAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($query);
}