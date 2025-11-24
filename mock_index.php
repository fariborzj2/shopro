<?php
// Mock helper functions
function csrf_token() { return 'mock_token_123'; }
function csrf_field() { echo '<input type="hidden" name="csrf_token" value="mock_token_123">'; }
function partial($path, $data = []) {
    // Basic partial simulation if needed
    // Storefront templates use include, so this might not be called, but header.tpl uses it?
    // No, I checked header.tpl and it doesn't use partial().
    // category.tpl uses partial()?
    // Yes, the original category.tpl did. But I overwrote it.
    // My new category.tpl uses `include 'footer.tpl'`.
    // Wait, let's double check.
}

// Mock global functions from helpers
if (!function_exists('jdate')) {
    function jdate($format, $timestamp = null) { return '1403/01/01'; }
}

// Mock Session
$_SESSION['user_id'] = null; // Unauthenticated view

// Mock Data
$pageTitle = 'صفحه اصلی';
$metaDescription = 'توضیحات تستی';

$store_data = json_encode([
    'categories' => [
        ['id' => 1, 'name' => 'لپ‌تاپ'],
        ['id' => 2, 'name' => 'موبایل'],
        ['id' => 3, 'name' => 'لوازم جانبی']
    ],
    'products' => [
        [
            'id' => 101,
            'name' => 'مک‌بوک پرو ۲۰۲۳',
            'price' => 120000000,
            'imageUrl' => 'https://placehold.co/400x400/png?text=MacBook',
            'category' => 1,
            'description' => 'لپ‌تاپ قدرتمند اپل با تراشه M3 Pro'
        ],
        [
            'id' => 102,
            'name' => 'آیفون ۱۵ پرو',
            'price' => 75000000,
            'imageUrl' => 'https://placehold.co/400x400/png?text=iPhone+15',
            'category' => 2,
            'description' => 'بدنه تیتانیومی و دوربین ۴۸ مگاپیکسلی'
        ],
        [
            'id' => 103,
            'name' => 'ایرپاد پرو ۲',
            'price' => 12000000,
            'imageUrl' => 'https://placehold.co/400x400/png?text=AirPods',
            'category' => 3,
            'description' => 'کیفیت صدای بی‌نظیر با حذف نویز'
        ],
        [
            'id' => 104,
            'name' => 'سامسونگ S24 Ultra',
            'price' => 68000000,
            'imageUrl' => 'https://placehold.co/400x400/png?text=S24+Ultra',
            'category' => 2,
            'description' => 'هوش مصنوعی Galaxy AI'
        ]
    ],
    'isUserLoggedIn' => false
]);

// Include the template
// We need to make sure include paths work.
// Since we are running php -S localhost:8000 mock_index.php, the CWD is root.
// index.tpl includes 'header.tpl'. If it's `include 'header.tpl'`, it looks in current dir.
// But files are in `storefront/templates/`.
// So we should copy `mock_index.php` INTO `storefront/templates/` and run server from there?
// Or fix paths.
// The real app sets include_path.
// Let's set include_path here.

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/storefront/templates');

// Actually, my rewritten templates use `include 'header.tpl'`.
// So if I include `storefront/templates/index.tpl` from root, and `index.tpl` says `include 'header.tpl'`,
// PHP will look in `include_path`.
// So setting include_path is correct.

include 'storefront/templates/index.tpl';
