<?php
define('PROJECT_ROOT', __DIR__);
require_once 'app/Core/Database.php';
$db = \App\Core\Database::getConnection();
$stmt = $db->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $tables);
