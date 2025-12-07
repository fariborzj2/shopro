<?php

use App\Core\Database;

// Create ai_models table
$db = Database::getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `ai_models` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name_fa` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL UNIQUE,
    `api_key` TEXT NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$db->exec($sql);
