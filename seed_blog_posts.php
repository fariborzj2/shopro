<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Core/SitemapGenerator.php';
require_once PROJECT_ROOT . '/app/Models/BlogCategory.php';
require_once PROJECT_ROOT . '/app/Models/BlogPost.php';
require_once PROJECT_ROOT . '/app/Models/Admin.php';

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Admin;

// 1. Get or Create Admin Author
$admin = Admin::find(1);
if (!$admin) {
    // Fallback if ID 1 doesn't exist, try to find any admin or create one
    $stmt = \App\Core\Database::getConnection()->query("SELECT id FROM admins LIMIT 1");
    $adminId = $stmt->fetchColumn();
    if (!$adminId) {
        $adminId = Admin::create([
            'username' => 'admin_seeder',
            'password_hash' => password_hash('password', PASSWORD_DEFAULT),
            'email' => 'seeder@example.com',
            'role' => 'super_admin',
            'is_super_admin' => 1,
            'status' => 'active'
        ]);
        echo "Created Admin ID: $adminId\n";
    }
} else {
    $adminId = $admin['id'];
}

// 2. Create Categories
$categories = [
    ['name_fa' => 'اخبار ارز دیجیتال', 'slug' => 'crypto-news'],
    ['name_fa' => 'تحلیل تکنیکال', 'slug' => 'technical-analysis'],
    ['name_fa' => 'آموزش بلاکچین', 'slug' => 'blockchain-education'],
    ['name_fa' => 'راهنمای ترید', 'slug' => 'trading-guide'],
    ['name_fa' => 'اخبار بیت‌بانک', 'slug' => 'bitbank-news'],
];

$categoryIds = [];
foreach ($categories as $catData) {
    // Check if exists
    $existing = BlogCategory::findBy('slug', $catData['slug']);
    if ($existing) {
        $categoryIds[] = $existing->id;
    } else {
        $id = BlogCategory::create([
            'name_fa' => $catData['name_fa'],
            'slug' => $catData['slug'],
            'status' => 'active',
            'parent_id' => null
        ]);
        $categoryIds[] = $id;
        echo "Created Category: {$catData['name_fa']}\n";
    }
}

// 3. Create 25 Blog Posts
$titles = [
    "آینده بیت کوین در سال ۲۰۲۵",
    "چگونه اتریوم بخریم؟",
    "تحلیل قیمت دوج کوین امروز",
    "بهترین کیف پول های سخت افزاری",
    "NFT چیست و چگونه کار می کند؟",
    "متاورس و آینده اینترنت",
    "صرافی غیرمتمرکز یا متمرکز؟",
    "استیکینگ ارز دیجیتال چیست؟",
    "ماینینگ بیت کوین با گوشی",
    "اخبار جدید ریپل و دادگاه SEC",
    "تاثیر تورم بر بازار کریپتو",
    "هوش مصنوعی در ترید ارز دیجیتال",
    "امنیت در تراکنش های بلاکچین",
    "توکن های هواداری چیست؟",
    "بازی های Play to Earn",
    "وب ۳.۰ چیست؟",
    "قراردادهای هوشمند اتریوم",
    "سولانا قاتل اتریوم؟",
    "کاردانو و آپدیت جدید",
    "شیبا اینو بخریم یا نه؟",
    "آموزش ثبت نام در بایننس",
    "احراز هویت در صرافی های ایرانی",
    "تتر چیست؟",
    "استیبل کوین های الگوریتمی",
    "سقوط لونا و درس های آن"
];

foreach ($titles as $index => $title) {
    $slug = 'post-' . ($index + 1) . '-' . time();
    $categoryId = $categoryIds[$index % count($categoryIds)];

    // Check if title exists (unlikely with time in slug but good practice)
    // Using slug to check uniqueness is easier

    BlogPost::create([
        'category_id' => $categoryId,
        'author_id' => $adminId,
        'title' => $title,
        'slug' => $slug,
        'content' => "<p>این یک متن تستی برای پست <strong>$title</strong> است. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p><h3>تیتر داخلی</h3><p>توضیحات تکمیلی در مورد این موضوع مهم در دنیای ارزهای دیجیتال.</p>",
        'excerpt' => "خلاصه ای کوتاه درباره $title برای نمایش در کارت های بلاگ.",
        'image_url' => 'https://placehold.co/800x400/2563eb/ffffff?text=' . urlencode($title),
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s', strtotime("-" . ($index * 2) . " hours")), // Staggered dates
        'is_editors_pick' => ($index % 5 === 0) ? 1 : 0, // Every 5th post is editor's pick
        'meta_title' => $title,
        'meta_description' => "توضیحات متا برای $title",
        'meta_keywords' => ['crypto', 'bitcoin', 'news']
    ]);

    echo "Created Post: $title\n";
}

echo "Seeding complete.\n";
