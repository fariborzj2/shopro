<?php

use App\Core\Database;

try {
    $db = Database::getConnection();

    // Drop Tables
    $db->exec("DROP TABLE IF EXISTS ai_cp_calendar");
    $db->exec("DROP TABLE IF EXISTS ai_cp_logs");
    $db->exec("DROP TABLE IF EXISTS ai_cp_queue");
    $db->exec("DROP TABLE IF EXISTS ai_cp_settings");

} catch (\PDOException $e) {
    error_log("AI Content Pro Uninstall Error: " . $e->getMessage());
    // Continue even if error to ensure plugin is removed from system
}
