<?php

namespace App\Controllers;

use App\Core\Template;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Core\Paginator;

class BlogController
{
    private $template;
    const POSTS_PER_PAGE = 10;

    public function __construct()
    {
        $this->template = new Template();
    }

    public function index()
    {
        $settings = \App\Models\Setting::getAll();
        $posts_per_page = $settings['blog_index_limit'] ?? self::POSTS_PER_PAGE;

        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $category_id = $_GET['category'] ?? null;

        $offset = ($page - 1) * $posts_per_page;
        $result = BlogPost::findAllPublishedWithCount($posts_per_page, $offset, $search, $category_id);
        $raw_posts = $result['posts'];
        $total_posts = $result['total_count'];

        // Sanitize and normalize posts
        $posts = [];
        foreach ($raw_posts as $post) {
            $posts[] = $this->sanitizePost($post);
        }

        $paginator = new Paginator($total_posts, $posts_per_page, $page, '/blog');
        $categories = BlogCategory::findAllBy('status', 'active');

        $sidebar_data = $this->_getSidebarData();

        // Slider and featured categories
        // The new logic (Smart Featured Posts) handles limits and fallback internally.
        $slider_limit = $settings['blog_slider_limit'] ?? 5;
        $slider_posts = BlogPost::getSmartFeaturedPosts($slider_limit);

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
        $settings = \App\Models\Setting::getAll();
        $posts_per_page = $settings['blog_category_limit'] ?? self::POSTS_PER_PAGE;

        $slug = urldecode($slug);
        $category = BlogCategory::findBy('slug', $slug);
        if (!$category) {
            http_response_code(404);
            echo "دسته‌بندی یافت نشد.";
            return;
        }

        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $posts_per_page;

        $result = BlogPost::findAllPublishedWithCount($posts_per_page, $offset, null, $category->id);
        $raw_posts = $result['posts'];
        $total_posts = $result['total_count'];

        // Sanitize and normalize posts
        $posts = [];
        foreach ($raw_posts as $post) {
            $posts[] = $this->sanitizePost($post);
        }

        $paginator = new Paginator($total_posts, $posts_per_page, $page, '/blog/category/' . $slug);
        $sidebar_data = $this->_getSidebarData();

        echo $this->template->render('blog/category', [
            'pageTitle' => 'دسته‌بندی: ' . $category->name_fa,
            'category' => $category,
            'posts' => $posts,
            'paginator' => $paginator,
            'sidebar' => $sidebar_data
        ]);
    }

    public function show($category = null, $slug = null)
    {
        // Handle route ambiguity: /blog/{slug} vs /blog/{category}/{slug}
        // If $slug is null, it means we came from /blog/{slug}, so $category actually holds the slug.
        if ($slug === null) {
            $slug = $category;
            $category = null;
        }

        $slug = urldecode($slug);
        // Extract ID from slug (format: {id}-{slug})
        if (preg_match('/^(\d+)-/', $slug, $matches)) {
            $id = (int)$matches[1];
            $post = BlogPost::findByIdWithCategory($id);
        } else {
            // Fallback for old URLs or direct slug access (optional, but good for stability)
            $post = BlogPost::findBySlug($slug);
        }

        try {
            if (!$post) {
                // Check if it matches a category slug (fallback for /blog/news URLs)
                $categoryObj = BlogCategory::findBy('slug', $slug);
                if ($categoryObj) {
                    return $this->category($slug);
                }

                http_response_code(404);
                echo "پست یافت نشد.";
                return;
            }

            // Canonical check/Verification (Optional: strict check on category and slug structure)
            // If strictly enforcing structure, we could redirect here if URL doesn't match constructed URL.
            // For now, we just proceed.

            // Increment view count
            BlogPost::incrementViews($post->id);

            // FAQ Items from JSON
            $faq_items = [];
            if (!empty($post->faq)) {
                $faq_items = json_decode($post->faq, true);
                if (!is_array($faq_items)) {
                    $faq_items = [];
                }
            }

            $tags = BlogPost::getTagsByPostId($post->id);

            $settings = \App\Models\Setting::getAll();
            $related_posts_limit = $settings['blog_related_limit'] ?? 5;
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
                    'datePublished' => date('c', strtotime($post->published_at ?? $post->created_at ?? 'now')),
                    'dateModified' => date('c', strtotime($post->updated_at ?? $post->published_at ?? $post->created_at ?? 'now')),
                    'mainEntityOfPage' => $canonical_url,
                ],
                'faq' => !empty($faq_items) ? [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => array_map(function($faq) {
                        return [
                            '@type' => 'Question',
                            'name' => $faq['question'],
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => $faq['answer'],
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
        $settings = \App\Models\Setting::getAll();
        $posts_per_page = $settings['blog_tag_limit'] ?? self::POSTS_PER_PAGE;

        $slug = urldecode($slug);
        $tag = \App\Models\BlogTag::findBy('slug', $slug);
        if (!$tag) {
            http_response_code(404);
            echo "Tag not found.";
            return;
        }

        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $posts_per_page;

        $result = BlogPost::findAllPublishedWithCount($posts_per_page, $offset, null, null, $tag->id);
        $posts = $result['posts'];
        $total_posts = $result['total_count'];

        $paginator = new Paginator($total_posts, $posts_per_page, $page, '/blog/tags/' . $slug);

        echo $this->template->render('blog/tag_posts', [
            'pageTitle' => 'Posts tagged with: ' . $tag->name,
            'tag' => $tag,
            'posts' => $posts,
            'paginator' => $paginator
        ]);
    }

    public function storeComment()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             http_response_code(405);
             echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
             return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        $captcha = trim($_POST['captcha'] ?? '');
        $post_id = $_POST['post_id'] ?? null;
        $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

        // Basic validation
        if (empty($name) || empty($comment) || empty($post_id) || empty($captcha)) {
             echo json_encode([
                 'success' => false,
                 'message' => 'لطفا تمام فیلد‌های ضروری را پر کنید.',
                 'new_csrf_token' => $_SESSION['csrf_token'] ?? ''
             ]);
             return;
        }

        // Captcha validation
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $sessionCaptcha = $_SESSION['captcha'] ?? '';
        $inputCaptcha = convert_persian_numbers($captcha);

        if (empty($sessionCaptcha) || $inputCaptcha != $sessionCaptcha) {
             echo json_encode([
                 'success' => false,
                 'message' => 'کد امنیتی اشتباه است.',
                 'new_csrf_token' => $_SESSION['csrf_token'] ?? ''
             ]);
             return;
        }

        // Clear captcha after use
        unset($_SESSION['captcha']);

        // Create comment
        try {
            $data = [
                'post_id' => $post_id,
                'parent_id' => $parent_id,
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'status' => 'pending' // Pending approval
            ];

            $commentId = \App\Models\Comment::create($data);

            // Return success with the comment data
            $newComment = [
                'id' => $commentId,
                'post_id' => $post_id,
                'parent_id' => $parent_id,
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'comment' => htmlspecialchars($comment),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'children' => []
            ];

            echo json_encode([
                'success' => true,
                'comment' => $newComment,
                'message' => 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.',
                'new_csrf_token' => $_SESSION['csrf_token'] ?? ''
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'خطا در ثبت نظر.',
                'new_csrf_token' => $_SESSION['csrf_token'] ?? ''
            ]);
        }
    }

    private function _getSidebarData()
    {
        $settings = \App\Models\Setting::getAll();

        // Most Viewed Posts
        $viewedLimit = $settings['blog_viewed_limit'] ?? 5;
        $mostViewedRaw = BlogPost::findMostViewed($viewedLimit);
        $mostViewed = [];
        foreach ($mostViewedRaw as $p) {
            $mostViewed[] = $this->sanitizePost($p);
        }

        // Editor's Picks
        $recommendedLimit = $settings['blog_recommended_limit'] ?? 5;
        $editorsPicksRaw = BlogPost::findEditorsPicks($recommendedLimit);
        $editorsPicks = [];
        foreach ($editorsPicksRaw as $p) {
            $editorsPicks[] = $this->sanitizePost($p);
        }

        // Most Discussed
        $discussedLimit = $settings['blog_discussed_limit'] ?? 5;
        $mostDiscussedRaw = BlogPost::findMostDiscussed($discussedLimit);
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

        // Ensure author_name is set
        $postObj->author_name = $postObj->author_name ?? 'نویسنده ناشناس';

        // Ensure published_at is valid
        if (empty($postObj->published_at)) {
            $postObj->published_at = $postObj->created_at ?? date('Y-m-d H:i:s');
        }

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
