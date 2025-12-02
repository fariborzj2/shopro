<?php

$db = \App\Core\Database::getConnection();

// Check if table already exists to avoid errors on repeated installs (though uninstall should clean it)
$db->query("CREATE TABLE IF NOT EXISTS hello_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
