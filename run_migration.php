<?php

define('PROJECT_ROOT', __DIR__);

require_once PROJECT_ROOT . '/app/Core/Database.php';

try {
    $pdo = \App\Core\Database::getConnection();
    $sql = file_get_contents(PROJECT_ROOT . '/app/Plugins/AiNews/schema.sql');

    if (!$sql) {
        die("Error: Could not read schema file.\n");
    }

    $pdo->exec($sql);
    echo "Migration executed successfully.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
