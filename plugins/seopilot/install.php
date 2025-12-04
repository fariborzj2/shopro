<?php

use App\Core\Database;

/**
 * SeoPilot Enterprise Installer
 *
 * This script is executed by the PluginManager during installation
 * or can be run manually to set up the database.
 */

try {
    $db = Database::getConnection();

    // 1. Meta Table (Hybrid SQL/JSON)
    // Stores all SEO metadata for posts, products, categories, etc.
    $sqlMeta = "CREATE TABLE IF NOT EXISTS seopilot_meta (
        entity_id BIGINT UNSIGNED NOT NULL,
        entity_type VARCHAR(32) NOT NULL,
        focus_keyword VARCHAR(191),
        seo_score TINYINT UNSIGNED DEFAULT 0,
        data_raw JSON NULL,
        compiled_head MEDIUMTEXT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (entity_type, entity_id),
        INDEX idx_score (seo_score)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $db->exec($sqlMeta);

    // 2. Redirects Table (High Performance)
    // Handles 301/302/410 redirects efficiently
    $sqlRedirects = "CREATE TABLE IF NOT EXISTS seopilot_redirects (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        source_uri VARCHAR(191) NOT NULL,
        target_uri VARCHAR(2048) NOT NULL,
        status_code SMALLINT DEFAULT 301,
        hit_count BIGINT UNSIGNED DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_source(source_uri)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $db->exec($sqlRedirects);

    // 3. Options Table (Global Settings)
    // Stores configuration like separator, homepage title, social links
    $sqlOptions = "CREATE TABLE IF NOT EXISTS seopilot_options (
        option_name VARCHAR(64) PRIMARY KEY,
        option_value JSON NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $db->exec($sqlOptions);

    // Insert Default Options
    $defaultOptions = [
        'separator' => '|',
        'site_type' => 'organization', // organization or person
        'ai_auto_meta' => true,
        'sitemap_enabled' => true,
        'analysis_strictness' => 'normal'
    ];

    $stmt = $db->prepare("INSERT IGNORE INTO seopilot_options (option_name, option_value) VALUES ('settings', ?)");
    $stmt->execute([json_encode($defaultOptions)]);

} catch (PDOException $e) {
    // Log error but allow the installer to handle it
    error_log("SeoPilot Install Error: " . $e->getMessage());
    throw $e;
}
