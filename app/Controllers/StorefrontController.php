<?php

namespace App\Controllers;

use App\Models\FaqItem;
use App\Models\Page;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use App\Models\BlogPost;
use App\Core\Template;

class StorefrontController
{
    protected $settings;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->settings = Setting::getAll();
    }

    /**
     * Display the home page.
     */
    public function home()
    {
        $categories = Category::findAllBy('status', 'active', 'position ASC');
        $products = Product::findAllPublished('position ASC');
        $latestReviews = Review::findLatestHighRated(6);
        $latestPosts = BlogPost::findAllPublished(4); // Get 4 latest posts

        // Mock Brands Data (Since we don't have a Brand model)
        $brands = [
            ['name' => 'Apple', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/1667px-Apple_logo_black.svg.png'],
            ['name' => 'Samsung', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Samsung_Logo.svg/2560px-Samsung_Logo.svg.png'],
            ['name' => 'Xiaomi', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Xiaomi_logo_%282021-%29.svg/2048px-Xiaomi_logo_%282021-%29.svg.png'],
            ['name' => 'Sony', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Sony_logo.svg/2560px-Sony_logo.svg.png'],
            ['name' => 'LG', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/LG_symbol.svg/2048px-LG_symbol.svg.png'],
            ['name' => 'Asus', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/ASUS_Logo.svg/2560px-ASUS_Logo.svg.png'],
        ];

        $store_data = json_encode([
            'categories' => array_map(function($c) {
                return ['id' => $c->id, 'name' => $c->name_fa];
            }, $categories),
            'products' => array_map(function($p) {
                // Determine mock badges based on price or arbitrary logic for demo
                $badges = [];
                if ($p->price > 50000000) $badges[] = 'پرفروش';
                if ($p->old_price && $p->old_price > $p->price) {
                    $badges[] = 'تخفیف‌دار';
                    $discountPercent = round((($p->old_price - $p->price) / $p->old_price) * 100);
                } else {
                    $discountPercent = 0;
                }
                if ($p->id % 3 == 0) $badges[] = 'پیشنهاد ویژه';

                // Mock sales progress (random 50-95%)
                $salesProgress = rand(50, 95);

                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'old_price' => $p->old_price ? (float)$p->old_price : null,
                    'status' => $p->status,
                    'category' => $p->category_id,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                    'description' => isset($p->description) ? $p->description : '',
                    'badges' => $badges,
                    'discountPercent' => $discountPercent,
                    'salesProgress' => $salesProgress
                ];
            }, $products),
            'reviews' => array_map(function($r) {
                return [
                    'id' => $r['id'],
                    'userName' => $r['user_name'] ?? 'کاربر ناشناس',
                    'userAvatar' => 'https://ui-avatars.com/api/?name=' . urlencode($r['user_name'] ?? 'U') . '&background=random',
                    'rating' => (int)$r['rating'],
                    'comment' => $r['comment'],
                    'date' => \jdate('j F Y', strtotime($r['created_at']))
                ];
            }, $latestReviews),
            'blogPosts' => array_map(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => mb_substr(strip_tags($post->excerpt), 0, 100) . '...',
                    'imageUrl' => $post->image_url ?? 'https://placehold.co/600x400/EEE/31343C?text=Blog',
                    'date' => !empty($post->published_at) ? \jdate('j F Y', strtotime($post->published_at)) : ''
                ];
            }, $latestPosts),
            'brands' => $brands,
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template(__DIR__ . '/../../storefront/templates');
        echo $template->render('index', [
            'pageTitle' => $this->settings['site_title'] ?? 'فروشگاه مدرن',
            'store_data' => $store_data,
            'settings' => $this->settings
        ]);
    }

    /**
     * Display a static page by its slug.
     */
    public function page($slug)
    {
        $template = new Template(__DIR__ . '/../../storefront/templates');

        if ($slug === 'faq') {
            $faq_items_grouped = FaqItem::findAllGroupedByType();
            echo $template->render('faq', [
                'pageTitle' => 'سوالات متداول',
                'faq_items_grouped' => $faq_items_grouped,
                'faq_types' => get_faq_types(),
                'settings' => $this->settings
            ]);
            return;
        }

        $page = Page::findBySlug($slug);

        if (!$page || $page->status !== 'published') {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Page not found.";
            exit();
        }

        echo $template->render('page', [
            'page_title' => $page->title,
            'page_content' => $page->content,
            'settings' => $this->settings
        ]);
    }

    /**
     * Display a category page with its products.
     */
    public function category($slug)
    {
        $category = Category::findBy('slug', $slug);

        if (!$category || $category->status !== 'active') {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Category not found.";
            exit();
        }

        $products = Product::findAllBy('category_id', $category->id, 'position ASC');
        $reviews = [];
        foreach ($products as $product) {
            $reviews[$product->id] = \App\Models\Review::findByProductId($product->id);
        }

        $lastPurchasedProduct = null;
        if (isset($_SESSION['user_id'])) {
            $lastPurchasedProduct = Order::findLastPurchaseInCategory($_SESSION['user_id'], $category->id);
        }

        $store_data = json_encode([
            'category' => [
                'id' => $category->id,
                'name' => $category->name_fa,
                'description' => $category->description,
            ],
            'products' => array_map(function($p) use ($reviews) {
                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'old_price' => $p->old_price ? (float)$p->old_price : null,
                    'status' => $p->status,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                    'reviews' => $reviews[$p->id] ?? []
                ];
            }, $products),
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template(__DIR__ . '/../../storefront/templates');
        echo $template->render('category', [
            'pageTitle' => $category->name_fa,
            'category' => $category,
            'store_data' => $store_data,
            'reviews' => $reviews,
            'lastPurchasedProduct' => $lastPurchasedProduct,
            'settings' => $this->settings
        ]);
    }
}
