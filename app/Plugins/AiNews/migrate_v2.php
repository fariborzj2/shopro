<?php

// app/Plugins/AiNews/migrate_v2.php

define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/app/Core/Database.php';

try {
    $pdo = \App\Core\Database::getConnection();

    // Add columns if they don't exist
    $columns = [
        'status' => "ENUM('success', 'failed', 'skipped') DEFAULT 'success'",
        'reason' => "VARCHAR(255) NULL",
        'content_hash' => "VARCHAR(64) NULL"
    ];

    foreach ($columns as $col => $def) {
        try {
            $pdo->exec("ALTER TABLE ai_news_history ADD COLUMN $col $def");
            echo "Added column $col\n";
        } catch (PDOException $e) {
            // Column likely exists
        }
    }

    echo "Migration V2 completed.\n";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
