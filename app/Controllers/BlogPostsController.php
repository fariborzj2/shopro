<?php

namespace App\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Admin;
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

        return view('main', 'blog/posts/create', [
            'title' => 'افزودن نوشته جدید',
            'categories' => $categories,
            'authors' => $authors
        ]);
    }

    /**
     * Store a new blog post in the database.
     */
    public function store()
    {
        // Basic validation
        if (empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['category_id']) || empty($_POST['author_id'])) {
            die('Title, slug, category, and author are required.');
        }

        BlogPost::create([
            'category_id' => $_POST['category_id'],
            'author_id' => $_POST['author_id'],
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'],
            'excerpt' => $_POST['excerpt'],
            'status' => $_POST['status']
        ]);

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
            die('Blog post not found.');
        }

        $categories = BlogCategory::all();
        $authors = Admin::all();

        return view('main', 'blog/posts/edit', [
            'title' => 'ویرایش نوشته',
            'post' => $post,
            'categories' => $categories,
            'authors' => $authors
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
        if (empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['category_id']) || empty($_POST['author_id'])) {
            die('Title, slug, category, and author are required.');
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

        header('Location: /blog/posts');
        exit();
    }
}
