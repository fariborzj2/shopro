<?php

namespace App\Controllers;

use App\Models\Category;

class CategoriesController
{
    /**
     * Show a list of all categories.
     */
    public function index()
    {
        $categories = Category::all();
        return view('main', 'categories/index', [
            'title' => 'مدیریت دسته‌بندی‌ها',
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::all();
        return view('main', 'categories/create', [
            'title' => 'افزودن دسته‌بندی جدید',
            'categories' => $categories
        ]);
    }

    /**
     * Store a new category in the database.
     */
    public function store()
    {
        // Basic validation
        if (empty($_POST['name_fa'])) {
            die('Persian name is required.');
        }

        Category::create([
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /categories');
        exit();
    }

    /**
     * Show the form for editing a specific category.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            die('Category not found.');
        }

        $allCategories = Category::all();

        return view('main', 'categories/edit', [
            'title' => 'ویرایش دسته‌بندی',
            'category' => $category,
            'allCategories' => $allCategories
        ]);
    }

    /**
     * Update an existing category in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        // Basic validation
        if (empty($_POST['name_fa'])) {
            die('Persian name is required.');
        }

        Category::update($id, [
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /categories');
        exit();
    }
}
