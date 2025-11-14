<?php

namespace App\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Admin;
use App\Models\BlogTag;
use App\Models\FaqItem;
use App\Core\Paginator;

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
        $tags = BlogTag::all();

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

        $post_id = BlogPost::create([
            'category_id' => (int)$_POST['category_id'],
            'author_id' => $author_id,
            'title' => htmlspecialchars($_POST['title']),
            'slug' => htmlspecialchars($_POST['slug']),
            'content' => $_POST['content'] ?? '', // Note: Content should be purified, but for now we leave it
            'excerpt' => htmlspecialchars($_POST['excerpt'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?? null,
            'is_editors_pick' => isset($_POST['is_editors_pick']) ? 1 : 0,
        ]);

        // Sync tags
        $tags = $_POST['tags'] ?? [];
        BlogPost::syncTags($post_id, $tags);

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
        $tags = BlogTag::all();
        $post_tags = BlogPost::getTagsByPostId($id);

        $all_faq_items = FaqItem::all();
        $post_faq_items = BlogPost::getFaqItemsByPostId($id);

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
            'post_faq_items' => $post_faq_items
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

        BlogPost::update($id, [
            'category_id' => (int)$_POST['category_id'],
            'author_id' => (int)$_POST['author_id'], // Keep author, but it shouldn't be changeable from the form
            'title' => htmlspecialchars($_POST['title']),
            'slug' => htmlspecialchars($_POST['slug']),
            'content' => $_POST['content'] ?? '', // Note: Content should be purified, but for now we leave it
            'excerpt' => htmlspecialchars($_POST['excerpt'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?? null,
            'is_editors_pick' => isset($_POST['is_editors_pick']) ? 1 : 0,
        ]);

        // Sync tags
        $tags = $_POST['tags'] ?? [];
        BlogPost::syncTags($id, $tags);

        // Sync FAQ items
        $faq_items = $_POST['faq_items'] ?? [];
        BlogPost::syncFaqItems($id, $faq_items);

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
}
