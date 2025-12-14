<?php

namespace Store\Controllers;

use App\Models\FaqItem;
use App\Models\Page;
use Store\Models\Category;
use Store\Models\Order;
use Store\Models\Product;
use Store\Models\Review;
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
    public function index()
    {
        $categories = Category::findAllBy('status', 'active', 'position ASC');
        $products = Product::findAllPublished('position ASC');
        $latestReviews = Review::findLatestHighRated(6);
        $latestPosts = class_exists('App\Models\BlogPost') ? BlogPost::findAllPublished(4) : [];

        // Mock Brands Data
        $brands = [
            ['name' => 'Apple', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/1667px-Apple_logo_black.svg.png'],
            ['name' => 'Samsung', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Samsung_Logo.svg/2560px-Samsung_Logo.svg.png'],
        ];

        // Prepare reviews for Alpine
        $reviewsForJs = array_map(function($r) {
            // Check if object or array (defensive)
            $r = (object)$r;
            return [
                'id' => $r->id,
                'userName' => $r->name,
                'userAvatar' => 'https://ui-avatars.com/api/?name=' . urlencode($r->name) . '&background=random',
                'rating' => $r->rating,
                'comment' => $r->comment,
                'date' => \jdate('d F Y', strtotime($r->created_at))
            ];
        }, $latestReviews);

        // Prepare posts for Alpine
        $postsForJs = array_map(function($p) {
            $p = (object)$p;
            // Find category slug for URL construction
            $catSlug = 'news'; // Default fallback
            if (!empty($p->category_id)) {
                $cat = \App\Models\BlogCategory::find($p->category_id);
                if ($cat) {
                    $cat = (object)$cat; // Ensure object
                    $catSlug = $cat->slug;
                }
            }
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'category_slug' => $catSlug,
                'excerpt' => $p->excerpt,
                'imageUrl' => $p->image_url ?? 'https://placehold.co/600x400',
                'date' => \jdate('d F Y', strtotime($p->published_at ?? $p->created_at))
            ];
        }, $latestPosts);


        $store_data = json_encode([
            'categories' => array_map(function($c) {
                $c = (object)$c;
                return ['id' => $c->id, 'name' => $c->name_fa];
            }, $categories),
            'products' => array_map(function($p) {
                $p = (object)$p;
                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'old_price' => $p->old_price ? (float)$p->old_price : null,
                    'status' => $p->status,
                    'category' => $p->category_id,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                ];
            }, $products),
            'reviews' => $reviewsForJs,
            'brands' => $brands,
            'blogPosts' => $postsForJs,
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template();
        echo $template->render('index', [
            'pageTitle' => $this->settings['site_title'] ?? 'فروشگاه مدرن',
            'store_data' => $store_data,
            'settings' => $this->settings
        ]);
    }

    public function product($id)
    {
        $product = Product::find($id);
        if (!$product) {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Product not found.";
            return;
        }

        // Render simple view or json for test
        echo json_encode($product);
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $products = Product::paginated(20, 0, $query);
        echo json_encode($products);
    }

    public function faq()
    {
        $faq_items_grouped = FaqItem::findAllGroupedByType();
        $faq_types = \get_faq_types(); // Helper function

        $template = new Template();
        echo $template->render('faq', [
            'pageTitle' => 'سوالات متداول',
            'faq_items_grouped' => $faq_items_grouped,
            'faq_types' => $faq_types,
            'settings' => $this->settings
        ]);
    }

    public function page($slug)
    {
        $slug = urldecode($slug);
        $page = Page::findBySlug($slug);

        if (!$page || $page->status !== 'published') {
            header("HTTP/1.0 404 Not Found");
            $template = new Template();
            echo $template->render('error', ['code' => 404, 'message' => 'صفحه مورد نظر یافت نشد.']);
            return;
        }

        $template = new Template();
        echo $template->render('page', [
            'pageTitle' => $page->title,
            'page_title' => $page->title, // Template uses snake_case
            'page_content' => $page->content,
            'settings' => $this->settings
        ]);
    }

    // --- Cart API Methods ---

    public function getCart()
    {
        $cart = $_SESSION['cart'] ?? [];
        header('Content-Type: application/json');
        echo json_encode(['cart' => $cart]);
    }

    public function addToCart()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;
        $quantity = $input['quantity'] ?? 1;

        if (!$productId) {
            http_response_code(400);
            echo json_encode(['error' => 'Product ID required']);
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Simple cart logic
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $product = Product::find($productId);
            if ($product) {
                $_SESSION['cart'][$productId] = [
                    'id' => $product->id,
                    'name' => $product->name_fa,
                    'price' => $product->price,
                    'quantity' => $quantity
                ];
            }
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
    }

    public function removeFromCart()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;

        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart'] ?? []]);
    }

    public function updateCart()
    {
        // Implementation similar to add/remove
        echo json_encode(['success' => true]);
    }

    // --- Legacy Methods from original file ---

    public function category($slug)
    {
        $slug = urldecode($slug);
        $category = Category::findBy('slug', $slug);

        if (!$category || $category->status !== 'active') {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Category not found.";
            exit();
        }

        $products = Product::findAllBy('category_id', $category->id, 'position ASC');

        // Pass review form data
        $lastPurchasedProduct = null;
        if (isset($_SESSION['user_id'])) {
             $lastPurchasedProduct = Order::findLastPurchaseInCategory($_SESSION['user_id'], $category->id);
        }

        // Get reviews for all products in this category
        $reviews = [];
        foreach ($products as $p) {
            $p = (object)$p; // Ensure object access
            $reviews[$p->id] = Review::findApprovedByProductId($p->id);
        }

        $store_data = json_encode([
            'products' => array_map(function($p) {
                $p = (object)$p;
                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'old_price' => $p->old_price ? (float)$p->old_price : null,
                    'status' => $p->status,
                    'category' => $p->category_id,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                ];
            }, $products),
            'reviews' => [],
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template();
        echo $template->render('category', [
            'pageTitle' => $category->name_fa,
            'category' => $category,
            'products' => $products,
            'reviews' => $reviews,
            'lastPurchasedProduct' => $lastPurchasedProduct,
            'store_data' => $store_data,
            'settings' => $this->settings
        ]);
    }
}
