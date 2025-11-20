<?php

define('PROJECT_ROOT', __DIR__);
require PROJECT_ROOT . '/public/index.php';

use App\Models\Admin;
use App\Core\Database;

// Check if admin exists
$admin = Admin::findByUsername('admin');

if (!$admin) {
    echo "Creating default admin user...\n";
    $data = [
        'username' => 'admin',
        'password_hash' => password_hash('password', PASSWORD_DEFAULT),
        'email' => 'admin@example.com',
        'name' => 'Super Admin',
        'role' => 'Super Admin',
        'is_super_admin' => 1,
        'permissions' => [],
        'status' => 'active'
    ];

    Admin::create($data);
    echo "Admin user created.\n";
} else {
    echo "Admin user already exists.\n";
    // Ensure it has the right password if needed, but 'password' is the convention here.
    // Also update to be super admin just in case.
    Admin::update($admin['id'], ['is_super_admin' => 1, 'status' => 'active']);
    echo "Admin updated to Super Admin.\n";
}
