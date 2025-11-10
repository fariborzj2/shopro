<?php

namespace App\Controllers;

use App\Models\BlogCategory;

class BlogCategoriesController
{
    /**
     * Show a list of all blog categories.
     */
    public function index()
    {
        $categories = BlogCategory::all();
        return view('main', 'blog/categories/index', [
            'title' => 'مدیریت دسته‌بندی‌های وبلاگ',
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new blog category.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('main', 'blog/categories/create', [
            'title' => 'افزودن دسته‌بندی وبلاگ',
            'categories' => $categories
        ]);
    }

    /**
     * Store a new blog category in the database.
     */
    public function store()
    {
        // Basic validation
        if (empty($_POST['name_fa']) || empty($_POST['slug'])) {
            die('Persian name and slug are required.');
        }

        BlogCategory::create([
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'slug' => $_POST['slug'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /blog/categories');
        exit();
    }

    /**
     * Show the form for editing a specific blog category.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            die('Blog category not found.');
        }

        $allCategories = BlogCategory::all();

        return view('main', 'blog/categories/edit', [
            'title' => 'ویرایش دسته‌بندی وبلاگ',
            'category' => $category,
            'allCategories' => $allCategories
        ]);
    }

    /**
     * Update an existing blog category in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        // Basic validation
        if (empty($_POST['name_fa']) || empty($_POST['slug'])) {
            die('Persian name and slug are required.');
        }

        BlogCategory::update($id, [
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'slug' => $_POST['slug'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /blog/categories');
        exit();
    }
}
