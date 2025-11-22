<?php

namespace App\Controllers\Admin;

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
            return redirect_back_with_error('نام فارسی و اسلاگ الزامی است.');
        }

        BlogCategory::create([
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'slug' => $_POST['slug'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /admin/blog/categories');
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
            return redirect_back_with_error('دسته‌بندی وبلاگ پیدا نشد.');
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
            return redirect_back_with_error('نام فارسی و اسلاگ الزامی است.');
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

    /**
     * Delete a blog category.
     *
     * @param int $id
     */
    public function delete($id)
    {
        BlogCategory::delete($id);
        header('Location: /admin/blog/categories');
        exit();
    }
}
