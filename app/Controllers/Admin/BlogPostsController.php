<?php

namespace App\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Admin;
use App\Models\BlogTag;
use App\Models\FaqItem;
use App\Core\Paginator;
use App\Core\ImageUploader;

class BlogPostsController
{
    const ITEMS_PER_PAGE = 15;

    /**
     * Show a paginated list of all blog posts.
     */
    public function index()
    {
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * self::ITEMS_PER_PAGE;

        $result = BlogPost::paginatedWithCount(self::ITEMS_PER_PAGE, $offset);
        $posts = $result['posts'];
        $total_posts = $result['total_count'];

        $paginator = new Paginator($total_posts, self::ITEMS_PER_PAGE, $current_page, '/admin/blog/posts');

        return view('main', 'blog/posts/index', [
            'title' => 'مدیریت نوشته‌ها',
            'posts' => $posts,
            'paginator' => $paginator
        ]);
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        $authors = Admin::all();
        $tags = BlogTag::findAll();

        return view('main', 'blog/posts/create', [
            'title' => 'افزودن نوشته جدید',
            'categories' => $categories,
            'authors' => $authors,
            'tags' => $tags
        ]);
    }

    /**
     * Store a new blog post in the database.
     */
    public function store()
    {
        // Server-side validation
        $errors = [];
        if (empty($_POST['title'])) {
            $errors[] = 'عنوان نوشته الزامی است.';
        }
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ نوشته الزامی است.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST['slug'])) {
            $errors[] = 'اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.';
        }
        if (empty($_POST['category_id']) || !BlogCategory::find($_POST['category_id'])) {
            $errors[] = 'دسته بندی انتخاب شده معتبر نیست.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        // Use the logged-in admin's ID as the author
        $author_id = $_SESSION['admin_id'];

        // Handle Meta Keywords (array to string)
        $meta_keywords = '';
        if (isset($_POST['meta_keywords']) && is_array($_POST['meta_keywords'])) {
            $meta_keywords = implode(',', $_POST['meta_keywords']);
        } elseif (isset($_POST['meta_keywords'])) {
            $meta_keywords = $_POST['meta_keywords'];
        }

        $data = [
            'category_id' => (int)$_POST['category_id'],
            'author_id' => $author_id,
            'title' => htmlspecialchars($_POST['title']),
            'slug' => htmlspecialchars($_POST['slug']),
            'content' => $_POST['content'] ?? '', // Note: Content should be purified, but for now we leave it
            'excerpt' => $_POST['excerpt'] ?? '', // Content should be purified
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?? null,
            'is_editors_pick' => isset($_POST['is_editors_pick']) ? 1 : 0,
            'meta_title' => htmlspecialchars($_POST['meta_title'] ?? ''),
            'meta_description' => htmlspecialchars($_POST['meta_description'] ?? ''),
            'meta_keywords' => htmlspecialchars($meta_keywords),
        ];

        // Image Upload
        $uploader = new ImageUploader();
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image_url'] = $uploader->upload($_FILES['image'], 'blog/featured');
        }

        $post_id = BlogPost::create($data);

        // Sync tags (handle IDs and new Strings)
        $rawTags = $_POST['tags'] ?? [];
        $tagIds = [];
        foreach ($rawTags as $tag) {
            if (strpos($tag, 'new:') === 0) {
                // Create new tag
                $tagName = substr($tag, 4);
                $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($tagName));
                $existing = BlogTag::findBy('name', $tagName);
                if ($existing) {
                    $tagIds[] = $existing->id;
                } else {
                    // Create logic in controller or model. Model Create returns bool, need to adjust if we want ID.
                    // But Model Create doesn't return ID in current implementation.
                    // Let's use direct creation to get ID or update Model.
                    // Assuming BlogTag::create just does insert.
                    // I'll implement a helper here or assume standard behavior.
                    // Since BlogTag::create returns true, I need to fetch it back or use PDO lastInsertId if inside the model.
                    // Let's check BlogTag::create implementation again.
                    // It uses Database::query.
                    BlogTag::create(['name' => $tagName, 'slug' => $slug, 'status' => 'active']);
                    $newTag = BlogTag::findBy('name', $tagName);
                    if ($newTag) {
                        $tagIds[] = $newTag->id;
                    }
                }
            } else {
                $tagIds[] = (int)$tag;
            }
        }
        BlogPost::syncTags($post_id, $tagIds);

        // Sync FAQ items
        $faq_items = $_POST['faq_items'] ?? [];
        BlogPost::syncFaqItems($post_id, $faq_items);

        header('Location: /blog/posts');
        exit();
    }

    /**
     * Show the form for editing a specific blog post.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            redirect_back_with_error('Blog post not found.');
        }

        $categories = BlogCategory::all();
        $authors = Admin::all();
        $tags = BlogTag::findAll();
        $post_tags = BlogPost::getTagsByPostId($id);

        $all_faq_items = FaqItem::all();
        $post_faq_items = BlogPost::getFaqItemsByPostId($id);

        // Fetch full objects for the FAQ tab
        $post_faq_objects = [];
        if (!empty($post_faq_items)) {
            $post_faq_objects = FaqItem::findByIds($post_faq_items);
        }

        // Convert gregorian published_at to jalali for the view
        if (!empty($post['published_at'])) {
            $ts = strtotime($post['published_at']);
            list($jy, $jm, $jd) = gregorian_to_jalali(date('Y', $ts), date('m', $ts), date('d', $ts));
            $post['published_at_jalali'] = "$jy/$jm/$jd";
        }

        return view('main', 'blog/posts/edit', [
            'title' => 'ویرایش نوشته',
            'post' => $post,
            'categories' => $categories,
            'authors' => $authors,
            'tags' => $tags,
            'post_tags' => $post_tags,
            'all_faq_items' => $all_faq_items,
            'post_faq_items' => $post_faq_items,
            'post_faq_objects' => $post_faq_objects
        ]);
    }

    /**
     * Update an existing blog post in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            return redirect_back_with_error('نوشته پیدا نشد.');
        }

        // Authorization check (simple version: only author can edit)
        // In a real app, you might have roles like 'editor' or 'admin'
        // who can edit any post.
        // if ($post['author_id'] !== $_SESSION['admin_id']) {
        //     return redirect_back_with_error('شما اجازه ویرایش این نوشته را ندارید.');
        // }

        // Server-side validation
        $errors = [];
        if (empty($_POST['title'])) {
            $errors[] = 'عنوان نوشته الزامی است.';
        }
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ نوشته الزامی است.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST['slug'])) {
            $errors[] = 'اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.';
        }
        if (empty($_POST['category_id']) || !BlogCategory::find($_POST['category_id'])) {
            $errors[] = 'دسته بندی انتخاب شده معتبر نیست.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        // Handle Meta Keywords
        $meta_keywords = '';
        if (isset($_POST['meta_keywords']) && is_array($_POST['meta_keywords'])) {
            $meta_keywords = implode(',', $_POST['meta_keywords']);
        } elseif (isset($_POST['meta_keywords'])) {
            $meta_keywords = $_POST['meta_keywords'];
        }

        $data = [
            'category_id' => (int)$_POST['category_id'],
            'author_id' => (int)$_POST['author_id'], // Keep author, but it shouldn't be changeable from the form
            'title' => htmlspecialchars($_POST['title']),
            'slug' => htmlspecialchars($_POST['slug']),
            'content' => $_POST['content'] ?? '', // Note: Content should be purified, but for now we leave it
            'excerpt' => $_POST['excerpt'] ?? '', // Content should be purified
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?? null,
            'is_editors_pick' => isset($_POST['is_editors_pick']) ? 1 : 0,
            'meta_title' => htmlspecialchars($_POST['meta_title'] ?? ''),
            'meta_description' => htmlspecialchars($_POST['meta_description'] ?? ''),
            'meta_keywords' => htmlspecialchars($meta_keywords),
        ];

        // Image Upload
        $uploader = new ImageUploader();
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image if exists
            if (!empty($post['image_url'])) {
                @unlink(PROJECT_ROOT . '/public' . $post['image_url']);
            }
            $data['image_url'] = $uploader->upload($_FILES['image'], 'blog/featured');
        }

        BlogPost::update($id, $data);

        // Sync tags
        $rawTags = $_POST['tags'] ?? [];
        $tagIds = [];
        foreach ($rawTags as $tag) {
            if (strpos($tag, 'new:') === 0) {
                $tagName = substr($tag, 4);
                $slug = $tagName; // Use name as slug per requirements
                $existing = BlogTag::findBy('name', $tagName);
                if ($existing) {
                    $tagIds[] = $existing->id;
                } else {
                    BlogTag::create(['name' => $tagName, 'slug' => $slug, 'status' => 'active']);
                    $newTag = BlogTag::findBy('name', $tagName);
                    if ($newTag) {
                        $tagIds[] = $newTag->id;
                    }
                }
            } else {
                $tagIds[] = (int)$tag;
            }
        }
        BlogPost::syncTags($id, $tagIds);

        // Sync FAQ items
        $post_faqs = $_POST['post_faqs'] ?? [];
        $faq_ids = [];

        foreach ($post_faqs as $faq_data) {
            $faq_id = null;
            if (!empty($faq_data['id'])) {
                // Update existing
                $faq_id = $faq_data['id'];
                FaqItem::update($faq_id, [
                    'question' => htmlspecialchars($faq_data['question']),
                    'answer' => htmlspecialchars($faq_data['answer']),
                    'status' => 'active',
                    'position' => 0
                ]);
            } else {
                // Create new
                FaqItem::create([
                    'question' => htmlspecialchars($faq_data['question']),
                    'answer' => htmlspecialchars($faq_data['answer']),
                    'status' => 'active',
                    'position' => 0
                ]);
                $pdo = \App\Core\Database::getConnection();
                $faq_id = $pdo->lastInsertId();
            }

            if ($faq_id) {
                $faq_ids[] = $faq_id;
            }
        }

        $manual_faq_ids = $_POST['faq_items'] ?? [];
        $all_faq_ids = array_merge($faq_ids, $manual_faq_ids);

        BlogPost::syncFaqItems($id, $all_faq_ids);

        header('Location: /blog/posts');
        exit();
    }

    /**
     * Delete a blog post.
     *
     * @param int $id
     */
    public function delete($id)
    {
        BlogPost::delete($id);
        header('Location: /blog/posts');
        exit();
    }

    /**
     * Delete the featured image of a blog post.
     */
    public function deleteImage($id)
    {
        header('Content-Type: application/json');

        $post = BlogPost::find($id);
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'نوشته یافت نشد.']);
            return;
        }

        if (!empty($post['image_url'])) {
            // Delete physical file
            @unlink(PROJECT_ROOT . '/public' . $post['image_url']);

            // Update DB
            BlogPost::update($id, ['image_url' => null]);

            echo json_encode(['success' => true, 'message' => 'تصویر شاخص حذف شد.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'تصویری برای حذف وجود ندارد.']);
        }
        exit;
    }
}
