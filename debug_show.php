<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Models/BlogPost.php';
require_once PROJECT_ROOT . '/app/Models/BlogCategory.php';
require_once PROJECT_ROOT . '/app/Models/BlogTag.php';
require_once PROJECT_ROOT . '/app/Models/FaqItem.php';
require_once PROJECT_ROOT . '/app/Models/Setting.php';
require_once PROJECT_ROOT . '/app/Models/Comment.php';
require_once PROJECT_ROOT . '/app/Core/Template.php';
require_once PROJECT_ROOT . '/app/Core/Paginator.php';
require_once PROJECT_ROOT . '/app/Core/Captcha.php';
require_once PROJECT_ROOT . '/app/Controllers/BlogController.php';
require_once PROJECT_ROOT . '/app/Core/helpers.php';

// Mock session
session_start();

// Find a valid slug
$post = \App\Models\BlogPost::find(1);
if (!$post) die("Post 1 not found");
$slug = $post['slug'];
echo "Testing slug: $slug\n";

$controller = new \App\Controllers\BlogController();

try {
    $controller->show($slug);
} catch (\Throwable $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
