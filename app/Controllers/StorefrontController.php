<?php

namespace App\Controllers;

use App\Models\FaqItem;
use App\Models\Page;
use App\Models\Category;
use App\Models\Product;
use App\Core\Template;

class StorefrontController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Display the home page.
     */
    public function home()
    {
        $categories = Category::findAllBy('status', 'active', 'position ASC');
        $products = Product::findAllBy('status', 'available', 'position ASC');

        $store_data = json_encode([
            'categories' => array_map(function($c) {
                return ['id' => $c->id, 'name' => $c->name_fa];
            }, $categories),
            'products' => array_map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'category' => $p->category_id,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                ];
            }, $products),
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template(__DIR__ . '/../../storefront/templates');
        echo $template->render('index', [
            'pageTitle' => 'صفحه اصلی',
            'store_data' => $store_data
        ]);
    }

    /**
     * Display a static page by its slug.
     */
    public function page($slug)
    {
        $template = new Template(__DIR__ . '/../../storefront/templates');

        if ($slug === 'faq') {
            $faqItems = FaqItem::findAll('position ASC');
            echo $template->render('faq', [
                'pageTitle' => 'سوالات متداول',
                'faqItems' => $faqItems
            ]);
            return;
        }

        $page = Page::findBy('slug', $slug);

        if (!$page || $page->status !== 'published') {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Page not found.";
            exit();
        }

        echo $template->render('page', [
            'pageTitle' => $page->title,
            'content' => $page->content
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

        $store_data = json_encode([
            'category' => [
                'id' => $category->id,
                'name' => $category->name_fa,
                'description' => $category->description,
            ],
            'products' => array_map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name_fa,
                    'price' => (float)$p->price,
                    'imageUrl' => $p->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                ];
            }, $products),
            'isUserLoggedIn' => isset($_SESSION['user_id'])
        ]);

        $template = new Template(__DIR__ . '/../../storefront/templates');
        echo $template->render('category', [
            'pageTitle' => $category->name_fa,
            'category' => $category,
            'store_data' => $store_data
        ]);
    }
}
