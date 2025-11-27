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

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $category_id = $_GET['category'] ?? null;

        $offset = ($page - 1) * self::POSTS_PER_PAGE;
        $result = BlogPost::findAllPublishedWithCount(self::POSTS_PER_PAGE, $offset, $search, $category_id);
        $raw_posts = $result['posts'];
        $total_posts = $result['total_count'];

        // Sanitize and normalize posts
        $posts = [];
        foreach ($raw_posts as $post) {
            $posts[] = $this->sanitizePost($post);
        }

        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog');
        $categories = BlogCategory::findAllBy('status', 'active');

        $sidebar_data = $this->_getSidebarData();

        // Slider and featured categories
        $settings = \App\Models\Setting::getAll();
        $slider_posts = BlogPost::findMostViewed($settings['slider_posts_limit'] ?? 5, $settings['slider_time_range'] ?? 40);
        // Sanitize slider posts
        foreach ($slider_posts as &$sp) {
            $sp = $this->sanitizePost($sp);
        }

        $featured_category_ids = json_decode($settings['featured_categories'] ?? '[]', true);
        $featured_categories = [];
        if (!empty($featured_category_ids)) {
            $posts_limit = $settings['featured_category_posts_limit'] ?? 3;
            $all_categories = \App\Models\BlogCategory::all();
            foreach ($all_categories as $category) {
                if (in_array($category['id'], $featured_category_ids)) {
                    $cat_posts = BlogPost::findAllPublished($posts_limit, 0, null, $category['id']);
                    $category['posts'] = [];
                    foreach ($cat_posts as $cp) {
                        $category['posts'][] = $this->sanitizePost($cp);
                    }
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
        $offset = ($page - 1) * self::POSTS_PER_PAGE;

        $result = BlogPost::findAllPublishedWithCount(self::POSTS_PER_PAGE, $offset, null, $category->id);
        $raw_posts = $result['posts'];
        $total_posts = $result['total_count'];

        // Sanitize and normalize posts
        $posts = [];
        foreach ($raw_posts as $post) {
            $posts[] = $this->sanitizePost($post);
        }

        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog/category/' . $slug);
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
        try {
            $post = BlogPost::findBySlug($slug);
            if (!$post) {
                http_response_code(404);
                echo "پست یافت نشد.";
                return;
            }

            // Increment view count
            BlogPost::incrementViews($post->id);

            $faq_ids = BlogPost::getFaqItemsByPostId($post->id);
            $faq_items = !empty($faq_ids) ? FaqItem::findByIds($faq_ids) : [];

            $tags = BlogPost::getTagsByPostId($post->id);

            $settings = \App\Models\Setting::getAll();
            $related_posts_limit = $settings['related_posts_limit'] ?? 5;
            $related_posts = BlogPost::findRelatedPosts($post->id, $related_posts_limit);

            $comments = \App\Models\Comment::findByPostId($post->id);
            $captcha_image = \App\Core\Captcha::generate();

            // Prepare data for SEO and Schema
            $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $canonical_url = $base_url . '/blog/' . $post->slug;

            $schema_data = [
                'article' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $post->title,
                    'image' => $post->image_url ? $base_url . $post->image_url : null,
                    'author' => ['@type' => 'Person', 'name' => $post->author_name],
                    'publisher' => ['@type' => 'Organization', 'name' => 'نام سایت شما', 'logo' => ['@type' => 'ImageObject', 'url' => $base_url . '/logo.png']],
                    'datePublished' => date('c', strtotime($post->published_at)),
                    'dateModified' => date('c', strtotime($post->updated_at ?? $post->published_at)),
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
                'schema_data' => $schema_data,
                'comments' => $comments,
                'captcha_image' => $captcha_image
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
        }
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
        $offset = ($page - 1) * self::POSTS_PER_PAGE;

        $result = BlogPost::findAllPublishedWithCount(self::POSTS_PER_PAGE, $offset, null, null, $tag->id);
        $posts = $result['posts'];
        $total_posts = $result['total_count'];

        $paginator = new Paginator($total_posts, self::POSTS_PER_PAGE, $page, '/blog/tags/' . $slug);

        echo $this->template->render('blog/tag_posts', [
            'pageTitle' => 'Posts tagged with: ' . $tag->name,
            'tag' => $tag,
            'posts' => $posts,
            'paginator' => $paginator
        ]);
    }

    private function _getSidebarData()
    {
        // Most Viewed Posts (e.g., top 5)
        $mostViewedRaw = BlogPost::findMostViewed(5);
        $mostViewed = [];
        foreach ($mostViewedRaw as $p) {
            $mostViewed[] = $this->sanitizePost($p);
        }

        // Editor's Picks (e.g., top 5)
        $editorsPicksRaw = BlogPost::findEditorsPicks(5);
        $editorsPicks = [];
        foreach ($editorsPicksRaw as $p) {
            $editorsPicks[] = $this->sanitizePost($p);
        }

        // Most Discussed (e.g., top 5 by comment count - if comments exist)
        $mostDiscussedRaw = []; // Placeholder
        $mostDiscussed = [];
        foreach ($mostDiscussedRaw as $p) {
            $mostDiscussed[] = $this->sanitizePost($p);
        }

        return [
            'most_viewed' => $mostViewed,
            'editors_picks' => $editorsPicks,
            'most_commented' => $mostDiscussed,
        ];
    }

    /**
     * Sanitize and normalize a blog post object/array.
     *
     * @param mixed $post
     * @return object
     */
    private function sanitizePost($post)
    {
        // Convert to object if array
        if (is_array($post)) {
            $postObj = (object) $post;
        } else {
            $postObj = $post;
        }

        // Sanitize Title
        $postObj->title = strip_tags($postObj->title ?? '');

        // Sanitize Excerpt
        // Use excerpt if available, otherwise strip content
        $rawExcerpt = !empty($postObj->excerpt) ? $postObj->excerpt : ($postObj->content ?? '');
        $cleanExcerpt = strip_tags($rawExcerpt);

        // Trim to 200 chars safely
        if (mb_strlen($cleanExcerpt) > 200) {
            $postObj->excerpt = mb_substr($cleanExcerpt, 0, 200) . '...';
        } else {
            $postObj->excerpt = $cleanExcerpt;
        }

        return $postObj;
    }
}
