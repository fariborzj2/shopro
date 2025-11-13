<?php

namespace App\Controllers;

use App\Core\Template;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\FaqItem;
use App\Core\Paginator;

class BlogController
{
    private $template;
    const POSTS_PER_PAGE = 10;

    public function __construct()
    {
        $this->template = new Template(__DIR__ . '/../../storefront/templates');
    }

    // ... index() and category() methods remain the same ...
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $category_id = $_GET['category'] ?? null;

        $total_posts = BlogPost::countAllPublished($search, $category_id);
        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog');
        $posts = BlogPost::findAllPublished(self::POSTS_PER_PAGE, $paginator->getOffset(), $search, $category_id);
        $categories = BlogCategory::findAllBy('status', 'active');

        echo $this->template->render('blog_index', [
            'pageTitle' => 'بلاگ',
            'posts' => $posts,
            'categories' => $categories,
            'paginator' => $paginator,
            'search' => $search,
            'selected_category' => $category_id
        ]);
    }

    public function category($slug)
    {
        $category = BlogCategory::findBy('slug', $slug);
        if (!$category) {
            http_response_code(404);
            echo "دسته‌بندی یافت نشد.";
            return;
        }

        $page = $_GET['page'] ?? 1;
        $total_posts = BlogPost::countAllPublished(null, $category->id);
        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog/category/' . $slug);
        $posts = BlogPost::findAllPublished(self::POSTS_PER_PAGE, $paginator->getOffset(), null, $category->id);

        echo $this->template->render('blog_category', [
            'pageTitle' => 'دسته‌بندی: ' . $category->name_fa,
            'category' => $category,
            'posts' => $posts,
            'paginator' => $paginator
        ]);
    }

    public function show($slug)
    {
        $post = BlogPost::findBySlug($slug);
        if (!$post) {
            http_response_code(404);
            echo "پست یافت نشد.";
            return;
        }

        $faq_ids = BlogPost::getFaqItemsByPostId($post->id);
        $faq_items = !empty($faq_ids) ? FaqItem::findByIds($faq_ids) : [];

        // Prepare data for SEO and Schema
        $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $canonical_url = $base_url . '/blog/' . $post->slug;

        $schema_data = [
            'article' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'image' => $post->featured_image ? $base_url . $post->featured_image : null,
                'author' => ['@type' => 'Person', 'name' => $post->author_name],
                'publisher' => ['@type' => 'Organization', 'name' => 'نام سایت شما', 'logo' => ['@type' => 'ImageObject', 'url' => $base_url . '/logo.png']],
                'datePublished' => date('c', strtotime($post->published_at)),
                'dateModified' => date('c', strtotime($post->updated_at)),
                'mainEntityOfPage' => $canonical_url,
            ],
            'faq' => !empty($faq_items) ? [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(function($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq->question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq->answer,
                        ],
                    ];
                }, $faq_items),
            ] : null,
        ];

        echo $this->template->render('blog_show', [
            'pageTitle' => $post->title,
            'metaDescription' => $post->excerpt,
            'canonicalUrl' => $canonical_url,
            'post' => $post,
            'faq_items' => $faq_items,
            'schema_data' => $schema_data,
        ]);
    }
}
