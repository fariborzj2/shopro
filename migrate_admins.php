<?php
require_once __DIR__ . '/public/index.php';

use App\Core\Database;

echo "Starting migration...\n";

$db = Database::getConnection();

// Add is_super_admin column
try {
    $db->exec("ALTER TABLE admins ADD COLUMN is_super_admin BOOLEAN NOT NULL DEFAULT FALSE AFTER role");
    echo "Added is_super_admin column.\n";
} catch (PDOException $e) {
    echo "is_super_admin column might already exist or error: " . $e->getMessage() . "\n";
}

// Add permissions column
try {
    $db->exec("ALTER TABLE admins ADD COLUMN permissions JSON DEFAULT NULL AFTER is_super_admin");
    echo "Added permissions column.\n";
} catch (PDOException $e) {
    echo "permissions column might already exist or error: " . $e->getMessage() . "\n";
}

// Set Admin ID 1 as Super Admin
try {
    $db->exec("UPDATE admins SET is_super_admin = 1 WHERE id = 1");
    echo "Updated Admin ID 1 to Super Admin.\n";
} catch (PDOException $e) {
    echo "Error updating Admin ID 1: " . $e->getMessage() . "\n";
}

echo "Migration complete.\n";
