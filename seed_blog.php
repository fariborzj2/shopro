<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Models/BlogCategory.php';
require_once PROJECT_ROOT . '/app/Models/BlogPost.php';
require_once PROJECT_ROOT . '/app/Core/SitemapGenerator.php';
require_once PROJECT_ROOT . '/app/Models/Admin.php';

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Admin;

// Ensure Admin exists
$admin = Admin::find(1);
if (!$admin) {
    $stmt = \App\Core\Database::getConnection()->query("SELECT id FROM admins LIMIT 1");
    $adminId = $stmt->fetchColumn();
    if (!$adminId) {
        // Create admin if missing (should have been created by earlier step, but just in case)
        die("Admin not found. Please run create_admin.php first.");
    }
} else {
    $adminId = $admin['id'];
}

// Ensure Category exists
$categories = BlogCategory::all();
if (empty($categories)) {
    $catId = BlogCategory::create([
        'name_fa' => 'تکنولوژی',
        'slug' => 'technology',
        'status' => 'active'
    ]);
} else {
    $catId = $categories[0]['id'];
}

// Ensure Post exists
$posts = BlogPost::getAllPublished();
if (empty($posts)) {
    BlogPost::create([
        'category_id' => $catId,
        'author_id' => $adminId,
        'title' => 'سلام دنیا',
        'slug' => 'hello-world',
        'content' => '<p>این اولین پست وبلاگ است.</p>',
        'excerpt' => 'خلاصه اولین پست',
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s')
    ]);
    echo "Blog post created.\n";
} else {
    echo "Blog post already exists.\n";
}
