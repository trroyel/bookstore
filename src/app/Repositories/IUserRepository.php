<?php

namespace App\Repositories;

interface IUserRepository
{
    public function findAll();
    public function findById($id);
    public function findByEmail($email);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($query);
}