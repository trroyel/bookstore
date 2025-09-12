<?php
namespace App\Services;


class UserService
{
    private $file = __DIR__ . '/../../storage/users.json';

    /**
     * Get all users
     */
    public function readAllUsers()
    {
        if (!file_exists($this->file)) {
            return [];
        }
        $json = file_get_contents($this->file);
        $users = json_decode($json, true);
        return is_array($users) ? $users : [];
    }

    /**
     * Create a new user
     */
    public function create($user)
    {
        $users = $this->readAllUsers();
        
        // Generate new ID
        $user['id'] = $this->generateId($users);
        
        // Add timestamp
        $user['created_at'] = date('c'); // ISO 8601 format
        
        // Set default role if not provided
        if (!isset($user['role']) || empty($user['role'])) {
            $user['role'] = 'user';
        }
        
        // Hash password if provided
        if (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
        
        // Add user to array
        $users[] = $user;
        
        // Save to file
        file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
        return $user;
    }

    /**
     * Get a single user by ID
     */
    public function read($id)
    {
        $users = $this->readAllUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Update an existing user
     */
    public function update($id, $updatedUser)
    {
        $users = $this->readAllUsers();
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                // Hash password if being updated
                if (isset($updatedUser['password']) && !empty($updatedUser['password'])) {
                    $updatedUser['password'] = password_hash($updatedUser['password'], PASSWORD_DEFAULT);
                }
                
                $user = array_merge($user, $updatedUser);
                $user['id'] = $id; // Ensure ID doesn't change
                file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
                return $user;
            }
        }
        return null;
    }

    /**
     * Delete a user by ID
     */
    public function delete($id)
    {
        $users = $this->readAllUsers();
        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                unset($users[$key]);
                // Re-index array to maintain proper JSON structure
                $users = array_values($users);
                file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $users = $this->readAllUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Authenticate user login
     */
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

    /**
     * Check if email already exists
     */
    public function emailExists($email)
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Get users count
     */
    public function getCount()
    {
        return count($this->readAllUsers());
    }

    /**
     * Search users by name or email
     */
    public function search($query)
    {
        $users = $this->readAllUsers();
        $results = [];
        
        foreach ($users as $user) {
            if (
                stripos($user['name'], $query) !== false ||
                stripos($user['email'], $query) !== false
            ) {
                // Remove password from search results
                unset($user['password']);
                $results[] = $user;
            }
        }
        
        return $results;
    }
    
    /**
     * Generate new ID
     */
    private function generateId($items)
    {
        return empty($items) ? 1 : max(array_column($items, 'id')) + 1;
    }
}
