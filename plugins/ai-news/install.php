<?php

$db = \App\Core\Database::getConnection();

// Core Tables
$db->query("CREATE TABLE IF NOT EXISTS `ai_news_settings` (
  `setting_key` VARCHAR(255) PRIMARY KEY,
  `setting_value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$db->query("CREATE TABLE IF NOT EXISTS `ai_news_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `run_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('success', 'failed', 'partial') NOT NULL,
  `fetched_count` INT DEFAULT 0,
  `created_count` INT DEFAULT 0,
  `details` TEXT,
  `error_message` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$db->query("CREATE TABLE IF NOT EXISTS `ai_news_history` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `source_url` VARCHAR(255) NOT NULL UNIQUE,
  `status` ENUM('success', 'failed', 'skipped') DEFAULT 'success',
  `reason` VARCHAR(255) NULL,
  `content_hash` VARCHAR(64) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
