<?php

use App\Core\Database;

// Drop ai_models table
$db = Database::getConnection();

$sql = "DROP TABLE IF EXISTS `ai_models`;";

$db->exec($sql);
