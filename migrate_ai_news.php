<?php
define('PROJECT_ROOT', __DIR__);
require_once 'app/Core/Database.php';

// Mock some things if needed
try {
    require 'plugins/ai-news/install.php';
    echo "AI News tables created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
