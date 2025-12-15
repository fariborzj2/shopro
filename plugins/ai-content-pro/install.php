<?php

use App\Core\Database;

// 1. Settings Table
$sqlSettings = "CREATE TABLE IF NOT EXISTS ai_cp_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(191) NOT NULL UNIQUE,
    `value` TEXT,
    `module` VARCHAR(50) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 2. Queue Table
$sqlQueue = "CREATE TABLE IF NOT EXISTS ai_cp_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    payload JSON NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    attempts INT DEFAULT 0,
    last_attempt_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 3. Logs Table
$sqlLogs = "CREATE TABLE IF NOT EXISTS ai_cp_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level ENUM('info', 'warning', 'error') DEFAULT 'info',
    message TEXT NOT NULL,
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 4. Calendar Table
$sqlCalendar = "CREATE TABLE IF NOT EXISTS ai_cp_calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    scheduled_at TIMESTAMP NOT NULL,
    status ENUM('planned', 'created', 'cancelled') DEFAULT 'planned',
    meta JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

try {
    $db = Database::getConnection();

    $db->exec($sqlSettings);
    $db->exec($sqlQueue);
    $db->exec($sqlLogs);
    $db->exec($sqlCalendar);

    // Insert Default Settings
    $defaults = [
        // Global
        'api_key' => '',
        'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',

        // Content Generator
        'content_enabled' => '0',
        'content_model' => 'gemini-1.5-flash',
        'content_status' => 'draft', // draft, scheduled
        'content_max_urls' => '5',
        'content_lang' => 'fa', // Locked

        // SEO
        'seo_enabled' => '0',
        'seo_meta_title_len' => '60',
        'seo_meta_desc_len' => '160',
        'seo_schema_enabled' => '1',

        // Comments
        'comments_enabled' => '0',
        'comments_max_replies' => '1',
        'comments_tone' => 'professional',

        // Queue
        'queue_enabled' => '0',
        'queue_max_concurrent' => '1',
        'queue_retry_limit' => '3'
    ];

    $stmt = $db->prepare("INSERT IGNORE INTO ai_cp_settings (`key`, `value`, `module`) VALUES (?, ?, ?)");

    foreach ($defaults as $key => $val) {
        $module = explode('_', $key)[0];
        $stmt->execute([$key, $val, $module]);
    }

} catch (\PDOException $e) {
    error_log("AI Content Pro Install Error: " . $e->getMessage());
    throw $e;
}
