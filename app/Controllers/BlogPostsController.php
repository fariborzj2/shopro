<?php

namespace App\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Admin;
use App\Models\BlogTag;
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
        $total_posts = BlogPost::count();

        $paginator = new Paginator($total_posts, self::ITEMS_PER_PAGE, $current_page, '/blog/posts');

        $posts = BlogPost::paginated(self::ITEMS_PER_PAGE, $paginator->getOffset());

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
        // Basic validation
        if (empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['category_id']) || empty($_POST['author_id'])) {
            redirect_back_with_error('Title, slug, category, and author are required.');
        }

        $post_id = BlogPost::create([
            'category_id' => $_POST['category_id'],
            'author_id' => $_POST['author_id'],
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'],
            'excerpt' => $_POST['excerpt'],
            'status' => $_POST['status']
        ]);

        // Sync tags
        $tags = $_POST['tags'] ?? [];
        BlogPost::syncTags($post_id, $tags);

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
            'post_tags' => $post_tags
        ]);
    }

    /**
     * Update an existing blog post in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        // Basic validation
        $post = BlogPost::find($id);
        if (!$post) {
            redirect_back_with_error('Blog post not found.');
        }

        if (empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['category_id']) || empty($_POST['author_id'])) {
            redirect_back_with_error('Title, slug, category, and author are required.');
        }

        BlogPost::update($id, [
            'category_id' => $_POST['category_id'],
            'author_id' => $_POST['author_id'],
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'],
            'excerpt' => $_POST['excerpt'],
            'status' => $_POST['status']
        ]);

        // Sync tags
        $tags = $_POST['tags'] ?? [];
        BlogPost::syncTags($id, $tags);

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
