<?php
// create_temp_admin.php

// A simple autoloader matching the one in public/index.php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Database;

try {
    if (!file_exists('config.php')) {
        // Create a dummy config for playwright tests if it doesn't exist
        copy('config.example.php', 'config.php');
    }
    $config = require 'config.php';
    Database::connect($config['database']);

    $username = 'testadmin';
    $password = 'password';
    $name = 'Test Admin';
    $email = 'testadmin@example.com';

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (name, username, email, password_hash, status)
            VALUES (:name, :username, :email, :password_hash, 'active')
            ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), name = VALUES(name), email = VALUES(email)";

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password_hash' => $password_hash
    ]);

    echo "Temporary admin 'testadmin' with password 'password' created/updated successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
