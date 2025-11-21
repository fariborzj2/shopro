<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Models/Admin.php';
require_once PROJECT_ROOT . '/config.php';

$db = \App\Core\Database::getConnection();
// Create admin user if not exists
$stmt = $db->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->execute(['admin']);
if (!$stmt->fetch()) {
    $stmt = $db->prepare("INSERT INTO admins (username, email, password_hash, role, permissions) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@example.com', password_hash('password', PASSWORD_DEFAULT), 'super_admin', json_encode(['all'])]);
    echo "Admin user created.\n";
} else {
    echo "Admin user already exists.\n";
}
