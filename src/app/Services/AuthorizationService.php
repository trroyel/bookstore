<?php

namespace App\Services;

class AuthorizationService
{
    private $roleRepository;

    public function __construct($roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function can($user, $permission)
    {
        if (!$user || !isset($user['role_id'])) {
            return false;
        }

        $role = $this->roleRepository->findById($user['role_id']);
        if (!$role) {
            return false;
        }

        $permissions = json_decode($role['permissions'], true) ?? [];
        return in_array($permission, $permissions);
    }

    public function canAny($user, array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->can($user, $permission)) {
                return true;
            }
        }
        return false;
    }

    public function isOwner($user, $resourceUserId)
    {
        return $user && isset($user['id']) && $user['id'] === $resourceUserId;
    }
}
