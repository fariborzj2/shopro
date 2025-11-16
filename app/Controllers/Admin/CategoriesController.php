<?php

namespace App\Controllers\Admin;

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
        $customFields = CustomOrderField::all();
        return view('main', 'categories/create', [
            'title' => 'افزودن دسته‌بندی جدید',
            'categories' => $categories,
            'customFields' => $customFields,
            'attachedFieldIds' => [] // Empty array for create form
        ]);
    }

    /**
     * Store a new category in the database.
     */
    public function store()
    {
        // Server-side validation
        $errors = [];
        if (empty($_POST['name_fa'])) {
            $errors[] = 'نام فارسی دسته بندی الزامی است.';
        }
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ دسته بندی الزامی است.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST['slug'])) {
            $errors[] = 'اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.';
        }
        if (!empty($_POST['parent_id']) && !Category::find($_POST['parent_id'])) {
            $errors[] = 'دسته بندی والد انتخاب شده معتبر نیست.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        $id = Category::create([
            'parent_id' => (int)$_POST['parent_id'] ?: null,
            'name_fa' => htmlspecialchars($_POST['name_fa']),
            'name_en' => htmlspecialchars($_POST['name_en'] ?? ''),
            'slug' => htmlspecialchars($_POST['slug']),
            'status' => $_POST['status'] ?? 'draft',
            'position' => (int)($_POST['position'] ?? 0)
        ]);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

        header('Location: ' . url('categories'));
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
        $category = Category::find($id);
        if (!$category) {
            return redirect_back_with_error('دسته بندی پیدا نشد.');
        }

        // Server-side validation
        $errors = [];
        if (empty($_POST['name_fa'])) {
            $errors[] = 'نام فارسی دسته بندی الزامی است.';
        }
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ دسته بندی الزامی است.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST['slug'])) {
            $errors[] = 'اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.';
        }
        if (!empty($_POST['parent_id']) && !Category::find($_POST['parent_id'])) {
            $errors[] = 'دسته بندی والد انتخاب شده معتبر نیست.';
        }
        // Prevent setting a category as its own parent
        if ((int)$_POST['parent_id'] === (int)$id) {
            $errors[] = 'یک دسته بندی نمی‌تواند والد خودش باشد.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        Category::update($id, [
            'parent_id' => (int)$_POST['parent_id'] ?: null,
            'name_fa' => htmlspecialchars($_POST['name_fa']),
            'name_en' => htmlspecialchars($_POST['name_en'] ?? ''),
            'slug' => htmlspecialchars($_POST['slug']),
            'status' => $_POST['status'] ?? 'draft',
            'position' => (int)($_POST['position'] ?? 0)
        ]);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

        header('Location: ' . url('categories'));
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
        header('Location: ' . url('categories'));
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
