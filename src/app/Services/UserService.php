<?php
namespace App\Services;


class UserService
{
    private $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function readAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function create($user)
    {
        // Hash password if provided
        if (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
        
        $id = $this->userRepository->create($user);
        $user['id'] = $id;
        return $user;
    }

    public function read($id)
    {
        return $this->userRepository->findById($id);
    }

    public function update($id, $updatedUser)
    {
        // Hash password if being updated
        if (isset($updatedUser['password']) && !empty($updatedUser['password'])) {
            $updatedUser['password'] = password_hash($updatedUser['password'], PASSWORD_DEFAULT);
        }
        
        $rowCount = $this->userRepository->update($id, $updatedUser);
        return $rowCount > 0 ? $this->userRepository->findById($id) : null;
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id) > 0;
    }

    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Remove password from returned user data
            unset($user['password']);
            return $user;
        }
        return null;
    }

    public function emailExists($email)
    {
        return $this->findByEmail($email) !== null;
    }

    public function getCount()
    {
        return count($this->readAllUsers());
    }

    public function search($query)
    {
        $users = $this->userRepository->search($query);
        // Remove password from search results
        foreach ($users as &$user) {
            unset($user['password']);
        }
        return $users;
    }
}
