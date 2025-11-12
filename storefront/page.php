<?php

require_once __DIR__ . '/../app/Core/Template.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Page.php';

use App\Core\Template;
use App\Models\Page;

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    // Or redirect to a 404 page
    die('Page not specified.');
}

$page = Page::findBySlug($slug);

if (!$page) {
    // Handle page not found
    $page = [
        'title' => 'صفحه یافت نشد',
        'content' => '<p>متاسفانه صفحه‌ای با این آدرس یافت نشد.</p>'
    ];
}

$template = new Template();

$template->assign('title', $page['title'] . ' - فروشگاه مدرن');
$template->assign('page_title', $page['title']);
$template->assign('page_content', $page['content']);

echo $template->render('page.tpl');
