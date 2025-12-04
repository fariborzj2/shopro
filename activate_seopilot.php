<?php

// activate_seopilot.php
// A temporary script to register the plugin in the database since we lack a UI installer.

define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getConnection();

    // Ensure 'plugins' table exists (normally handled by PluginManager, but doing it here just in case)
    $db->query("CREATE TABLE IF NOT EXISTS plugins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        version VARCHAR(50),
        status ENUM('active', 'inactive') DEFAULT 'inactive',
        load_order INT DEFAULT 10,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Insert or Update SeoPilot
    $stmt = $db->prepare("INSERT INTO plugins (name, slug, version, status) VALUES (?, ?, ?, 'active')
        ON DUPLICATE KEY UPDATE status = 'active', version = ?");

    $stmt->execute(['SeoPilot Enterprise', 'seopilot', '2.0.0', '2.0.0']);

    echo "SeoPilot registered and activated in DB.\n";

    // Run the installer logic manually to ensure tables are created
    require_once PROJECT_ROOT . '/plugins/seopilot/install.php';

    echo "SeoPilot tables installed successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
