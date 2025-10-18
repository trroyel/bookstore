<?php

namespace App\Repositories;

interface IRoleRepository
{
    public function findAll();
    public function findByName($name);
}
