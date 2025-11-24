<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Models/BlogPost.php';
require_once PROJECT_ROOT . '/app/Core/Template.php';
require_once PROJECT_ROOT . '/app/Core/Paginator.php';
require_once PROJECT_ROOT . '/app/Models/BlogCategory.php';
require_once PROJECT_ROOT . '/app/Models/Setting.php';
require_once PROJECT_ROOT . '/app/Controllers/BlogController.php';

// Mock $_GET
$_GET['page'] = 1;

$controller = new \App\Controllers\BlogController();
ob_start();
try {
    $controller->index();
    echo "Index rendered successfully.\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Memory Usage: " . memory_get_peak_usage(true) . "\n";
}
echo ob_get_clean();
