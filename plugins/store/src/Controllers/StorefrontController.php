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

        $store_data = json_encode([
            'categories' => array_map(function($c) {
                return ['id' => $c->id, 'name' => $c->name_fa];
            }, $categories),
            'products' => array_map(function($p) {
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

        $template = new Template();
        echo $template->render('category', [
            'pageTitle' => $category->name_fa,
            'category' => $category,
            'products' => $products,
            'settings' => $this->settings
        ]);
    }
}
