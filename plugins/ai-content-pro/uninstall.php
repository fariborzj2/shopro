<?php

require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

// Drop tables
try {
    $db = Database::getConnection();

    $db->exec("DROP TABLE IF EXISTS ai_cp_settings");
    $db->exec("DROP TABLE IF EXISTS ai_cp_jobs");
    $db->exec("DROP TABLE IF EXISTS ai_cp_logs");

} catch (PDOException $e) {
    error_log("AiContentPro Uninstall Error: " . $e->getMessage());
}
