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

        $sidebar_data = $this->_getSidebarData();

        // Slider and featured categories
        $settings = \App\Models\Setting::getAll();
        $slider_posts = BlogPost::findMostViewed($settings['slider_posts_limit'] ?? 5, $settings['slider_time_range'] ?? 40);
        $featured_category_ids = json_decode($settings['featured_categories'] ?? '[]', true);
        $featured_categories = [];
        if (!empty($featured_category_ids)) {
            $posts_limit = $settings['featured_category_posts_limit'] ?? 3;
            $all_categories = \App\Models\BlogCategory::all();
            foreach ($all_categories as $category) {
                if (in_array($category['id'], $featured_category_ids)) {
                    $category['posts'] = BlogPost::findAllPublished($posts_limit, 0, null, $category['id']);
                    $featured_categories[] = $category;
                }
            }
        }

        echo $this->template->render('blog/index', [
            'pageTitle' => 'بلاگ',
            'posts' => $posts,
            'categories' => $categories,
            'paginator' => $paginator,
            'search' => $search,
            'selected_category' => $category_id,
            'sidebar' => $sidebar_data,
            'slider_posts' => $slider_posts,
            'featured_categories' => $featured_categories
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

        $sidebar_data = $this->_getSidebarData();

        echo $this->template->render('blog/category', [
            'pageTitle' => 'دسته‌بندی: ' . $category->name_fa,
            'category' => $category,
            'posts' => $posts,
            'paginator' => $paginator,
            'sidebar' => $sidebar_data
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

        $tags = BlogPost::getTagsByPostId($post->id);

        $settings = \App\Models\Setting::getAll();
        $related_posts_limit = $settings['related_posts_limit'] ?? 5;
        $related_posts = BlogPost::findRelatedPosts($post->id, $related_posts_limit);

        $comments = \App\Models\Comment::findByPostId($post->id);

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

        echo $this->template->render('blog/show', [
            'pageTitle' => $post->title,
            'metaDescription' => $post->excerpt,
            'canonicalUrl' => $canonical_url,
            'post' => $post,
            'faq_items' => $faq_items,
            'tags' => $tags,
            'related_posts' => $related_posts,
            'comments' => $comments,
            'sidebar' => $this->_getSidebarData(),
            'schema_data' => $schema_data,
        ]);
    }

    public function tags()
    {
        $tags = \App\Models\BlogTag::findAll();
        echo $this->template->render('blog/tags', [
            'pageTitle' => 'Tags',
            'tags' => $tags,
        ]);
    }

    public function showTag($slug)
    {
        $tag = \App\Models\BlogTag::findBy('slug', $slug);
        if (!$tag) {
            http_response_code(404);
            echo "Tag not found.";
            return;
        }

        $page = $_GET['page'] ?? 1;
        $total_posts = BlogPost::countAllPublished(null, null, $tag->id);
        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog/tags/' . $slug);
        $posts = BlogPost::findAllPublished(self::POSTS_PER_PAGE, $paginator->getOffset(), null, null, $tag->id);

        $sidebar_data = $this->_getSidebarData();

        echo $this->template->render('blog/tag_posts', [
            'pageTitle' => 'Posts tagged with: ' . $tag->name,
            'tag' => $tag,
            'posts' => $posts,
            'paginator' => $paginator,
            'sidebar' => $sidebar_data
        ]);
    }

    private function _getSidebarData()
    {
        return [
            'most_commented' => BlogPost::findMostCommented(5),
            'most_viewed' => BlogPost::findMostViewed(5, 365), // For all time
            'editors_picks' => BlogPost::findEditorsPicks(5)
        ];
    }
}
