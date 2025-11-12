<?php

namespace App\Controllers;

use App\Models\FaqItem;
use App\Models\Page;
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
}
