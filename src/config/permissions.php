<?php

return [
    // User permissions
    'read_user' => 'user:read',
    'create_user' => 'user:create',
    'update_user' => 'user:update',
    'delete_user' => 'user:delete',
    'assign_role' => 'role:assign',
    
    // Book permissions
    'read_book' => 'book:read',
    'create_book' => 'book:create',
    'update_book' => 'book:update',
    'delete_book' => 'book:delete',
    
    // Role permissions
    'read_role' => 'role:read',
    'create_role' => 'role:create',
    'update_role' => 'role:update',
    'delete_role' => 'role:delete',

    
    // Self permissions (for regular users)
    'read_self' => 'self:read',
    'update_self' => 'self:update',
    'delete_self' => 'self:delete',
];
