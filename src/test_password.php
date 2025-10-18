<?php

// Test password verification
$password = 'password';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "Testing password: '$password'\n";
echo "Against hash: $hash\n\n";

if (password_verify($password, $hash)) {
    echo "✓ Password verification SUCCESS\n";
} else {
    echo "✗ Password verification FAILED\n";
}

// Generate new hash
$newHash = password_hash($password, PASSWORD_DEFAULT);
echo "\nNew hash generated: $newHash\n";

if (password_verify($password, $newHash)) {
    echo "✓ New hash verification SUCCESS\n";
} else {
    echo "✗ New hash verification FAILED\n";
}

// Test database connection
require_once __DIR__ . '/bootstrap.php';

use App\Core\Database;

try {
    $db = Database::getInstance();
    echo "\n✓ Database connection SUCCESS\n";
    
    // Check if user exists
    $stmt = $db->prepare("SELECT id, name, email, password, role_id FROM users WHERE email = ?");
    $stmt->execute(['alice@example.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "\n✓ User found in database\n";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role ID: " . ($user['role_id'] ?? 'NULL') . "\n";
        echo "Password hash: " . $user['password'] . "\n";
        
        if (password_verify('password', $user['password'])) {
            echo "\n✓ Password matches database hash\n";
        } else {
            echo "\n✗ Password does NOT match database hash\n";
        }
    } else {
        echo "\n✗ User NOT found in database\n";
    }
    
} catch (Exception $e) {
    echo "\n✗ Database error: " . $e->getMessage() . "\n";
}
