<?php

require_once __DIR__ . '/../app/Core/Template.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Page.php';
require_once __DIR__ . '/../app/Models/FaqItem.php';

use App\Core\Template;
use App\Models\Page;
use App\Models\FaqItem;

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    die('Page not specified.');
}

$template = new Template();

// Special handling for the FAQ page
if ($slug === 'faq') {
    $faqItems = FaqItem::getActive();
    $template->assign('title', 'سوالات متداول - فروشگاه مدرن');
    $template->assign('faq_items', $faqItems);
    echo $template->render('faq.tpl');
    exit;
}

// Default page handling
$page = Page::findBySlug($slug);

if (!$page) {
    $page = [
        'title' => 'صفحه یافت نشد',
        'content' => '<p>متاسفانه صفحه‌ای با این آدرس یافت نشد.</p>'
    ];
}

$template->assign('title', $page['title'] . ' - فروشگاه مدرن');
$template->assign('page_title', $page['title']);
$template->assign('page_content', $page['content']);

echo $template->render('page.tpl');
