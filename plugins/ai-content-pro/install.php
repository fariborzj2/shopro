<?php

require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

// Create tables
try {
    $db = Database::getConnection();

    // 1. Settings Table
    $db->exec("CREATE TABLE IF NOT EXISTS ai_cp_settings (
        `key` VARCHAR(255) PRIMARY KEY,
        `value` TEXT,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Jobs Queue Table
    $db->exec("CREATE TABLE IF NOT EXISTS ai_cp_jobs (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `type` VARCHAR(50) NOT NULL,
        `payload` JSON NOT NULL,
        `status` ENUM('pending', 'processing', 'completed', 'failed', 'retrying') DEFAULT 'pending',
        `result` JSON,
        `attempts` INT DEFAULT 0,
        `error_message` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 3. Logs Table
    $db->exec("CREATE TABLE IF NOT EXISTS ai_cp_logs (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `level` VARCHAR(20) NOT NULL,
        `message` TEXT NOT NULL,
        `context` JSON,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 4. Content Calendar Table
    $db->exec("CREATE TABLE IF NOT EXISTS ai_cp_calendar (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content_type` VARCHAR(50) DEFAULT 'blog',
        `due_date` DATE NOT NULL,
        `status` ENUM('planned', 'drafted', 'published') DEFAULT 'planned',
        `job_id` INT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

} catch (PDOException $e) {
    error_log("AiContentPro Install Error: " . $e->getMessage());
    throw $e;
}
