<?php

use App\Core\Database;

try {
    $db = Database::getConnection();

    // 1. Sources Table
    $db->exec("CREATE TABLE IF NOT EXISTS `ai_news_sources` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `url` VARCHAR(255) NOT NULL,
        `type` ENUM('rss', 'sitemap', 'html') DEFAULT 'rss',
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `last_crawled_at` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Ensure columns exist for older versions
    try { $db->exec("ALTER TABLE `ai_news_sources` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP"); } catch(\Exception $e) {}

    // 2. History Table
    $db->exec("CREATE TABLE IF NOT EXISTS `ai_news_history` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `source_id` INT NOT NULL,
        `original_url` VARCHAR(512) NOT NULL,
        `content_hash` VARCHAR(64),
        `status` ENUM('pending', 'processed', 'failed', 'skipped') DEFAULT 'pending',
        `post_id` INT DEFAULT NULL,
        `reason` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_url` (`original_url`(191)),
        FOREIGN KEY (`source_id`) REFERENCES `ai_news_sources`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    try { $db->exec("ALTER TABLE `ai_news_history` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP"); } catch(\Exception $e) {}

    // 3. Settings Table
    $db->exec("CREATE TABLE IF NOT EXISTS `ai_news_settings` (
        `setting_key` VARCHAR(255) PRIMARY KEY,
        `setting_value` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 4. Logs Table
    $db->exec("CREATE TABLE IF NOT EXISTS `ai_news_logs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `level` ENUM('info', 'warning', 'error') DEFAULT 'info',
        `message` TEXT,
        `context` JSON,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    try { $db->exec("ALTER TABLE `ai_news_logs` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP"); } catch(\Exception $e) {}

} catch (Exception $e) {
    error_log("AI News Installation/Fix Error: " . $e->getMessage());
}
