<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\CustomOrderField;

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
            redirect_back_with_error('Persian name is required.');
        }

        $id = Category::create([
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

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
            redirect_back_with_error('Category not found.');
        }

        $allCategories = Category::all();
        $customFields = CustomOrderField::all();
        $attachedFieldIds = Category::getAttachedCustomFieldIds($id);

        return view('main', 'categories/edit', [
            'title' => 'ویرایش دسته‌بندی',
            'category' => $category,
            'allCategories' => $allCategories,
            'customFields' => $customFields,
            'attachedFieldIds' => $attachedFieldIds
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
        $category = Category::find($id);
        if (!$category) {
            redirect_back_with_error('Category not found.');
        }
        if (empty($_POST['name_fa'])) {
            redirect_back_with_error('Persian name is required.');
        }

        Category::update($id, [
            'parent_id' => $_POST['parent_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

        header('Location: /categories');
        exit();
    }

    /**
     * Delete a category.
     *
     * @param int $id
     */
    public function delete($id)
    {
        // Add logic here to handle products in this category before deleting.
        // For now, we'll just delete the category.
        Category::delete($id);
        header('Location: /categories');
        exit();
    }

    /**
     * Handle the reordering of categories.
     */
    public function reorder()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            http_response_code(400);
            return;
        }

        if (Category::updateOrder($ids)) {
            echo json_encode(['success' => true, 'message' => 'Order updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order.']);
            http_response_code(500);
        }
    }
}
