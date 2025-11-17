<?php
// create_temp_admin.php

// This script is for temporary use to create an admin user for testing.
// It should be deleted after use.

// Define PROJECT_ROOT as it's used in the Database class
define('PROJECT_ROOT', __DIR__);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Models/Admin.php';

// Load the configuration that defines DB constants
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    die("Error: config.php not found. Please create it from config.example.php.\n");
}


use App\Core\Database;
use App\Models\Admin;

// --- Configuration ---
$username = 'testadmin';
$password = 'password123';
$email = 'test@example.com';
$name = 'Test Admin';
$role = 'super_admin'; // Or any other role you have

try {
    // Check if the admin already exists
    $existingAdmin = Admin::findByUsername($username);
    if ($existingAdmin) {
        echo "Admin user '{$username}' already exists.\n";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create the admin user
    $adminData = [
        'username' => $username,
        'password' => $hashedPassword,
        'email' => $email,
        'name' => $name,
        'role' => $role,
        'status' => 'active'
    ];

    $id = Admin::create($adminData);

    if ($id) {
        echo "Successfully created admin user:\n";
        echo "Username: {$username}\n";
        echo "Password: {$password}\n";
    } else {
        echo "Failed to create admin user.\n";
    }

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}

?>
