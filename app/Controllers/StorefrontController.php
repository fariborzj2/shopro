<?php

namespace App\Controllers;

use App\Models\FaqItem;
use App\Models\Page;
use App\Models\Category;
use App\Models\Product;
use App\Core\Template;

class StorefrontController
{
    /**
     * Display the home page.
     */
    public function home()
    {
        // For now, it just renders a simple template.
        // This can be expanded to include dynamic data.
        $template = new Template(__DIR__ . '/../../storefront/views');
        echo $template->render('index', [
            'pageTitle' => 'صفحه اصلی'
        ]);
    }

    /**
     * Display a static page by its slug.
     *
     * @param string $slug
     */
    public function page($slug)
    {
        if ($slug === 'faq') {
            $faqItems = FaqItem::findAll('display_order ASC');
            $template = new Template(__DIR__ . '/../../storefront/views');
            echo $template->render('faq', [
                'pageTitle' => 'سوالات متداول',
                'faqItems' => $faqItems
            ]);
            return;
        }

        $page = Page::findBy('slug', $slug);

        if (!$page || !$page->is_published) {
            // Simple 404 handler
            header("HTTP/1.0 404 Not Found");
            echo "404 - Page not found.";
            exit();
        }

        $template = new Template(__DIR__ . '/../../storefront/views');
        echo $template->render('page', [
            'pageTitle' => $page->title,
            'content' => $page->content
        ]);
    }

    /**
     * Display a category page with its products.
     *
     * @param string $slug
     */
    public function category($slug)
    {
        // Note: We need a findBy method in the Category model that can handle slugs.
        // Assuming the model has a `findBySlug` static method for this.
        $category = Category::findBy('slug', $slug);

        if (!$category || $category->status !== 'active') {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Category not found.";
            exit();
        }

        $products = Product::findAllBy('category_id', $category->id, 'position ASC');

        // We need to pass the data in a JSON format for Alpine.js, similar to the home page.
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
                    'imageUrl' => $p->image_url ?? '/path/to/default/image.jpg', // Add a default image
                ];
            }, $products)
        ]);

        $template = new Template(__DIR__ . '/../../storefront/templates');
        echo $template->render('category', [
            'pageTitle' => $category->name_fa,
            'category' => $category,
            'store_data' => $store_data
        ]);
    }
}
