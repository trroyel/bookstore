<?php

function hasPermission($permission) {
    return in_array($permission, $_SESSION['permissions'] ?? []);
}


function hasAnyPermission(array $permissions) {
    $userPermissions = $_SESSION['permissions'] ?? [];
    foreach ($permissions as $permission) {
        if (in_array($permission, $userPermissions)) {
            return true;
        }
    }
    return false;
}

function hasAllPermissions(array $permissions) {
    $userPermissions = $_SESSION['permissions'] ?? [];
    foreach ($permissions as $permission) {
        if (!in_array($permission, $userPermissions)) {
            return false;
        }
    }
    return true;
}
