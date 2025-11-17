<?php

namespace App\Controllers\Admin;

use App\Models\Category;
use App\Models\CustomOrderField;
use App\Core\ImageUploader;

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
        $allCategories = Category::all(); // Renamed to avoid confusion in view
        $customFields = CustomOrderField::all();
        return view('main', 'categories/create', [
            'title' => 'افزودن دسته‌بندی جدید',
            'allCategories' => $allCategories,
            'customFields' => $customFields,
            'attachedFieldIds' => []
        ]);
    }

    /**
     * Store a new category in the database.
     */
    public function store()
    {
        if (!verify_csrf_token()) {
            redirect_back_with_error('Invalid CSRF token.');
        }

        // Server-side validation (can be enhanced)
        if (empty($_POST['name_fa']) || empty($_POST['slug'])) {
            // Handle error
            redirect_back_with_error('نام فارسی و اسلاگ الزامی است.');
        }

        $data = [
            'parent_id' => (int)$_POST['parent_id'] ?: null,
            'name_fa' => htmlspecialchars($_POST['name_fa']),
            'name_en' => htmlspecialchars($_POST['name_en'] ?? ''),
            'slug' => htmlspecialchars($_POST['slug']),
            'status' => $_POST['status'] ?? 'active',
            'position' => (int)($_POST['position'] ?? 0),
            'short_description' => htmlspecialchars($_POST['short_description'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'meta_title' => htmlspecialchars($_POST['meta_title'] ?? ''),
            'meta_description' => htmlspecialchars($_POST['meta_description'] ?? ''),
            'meta_keywords' => htmlspecialchars($_POST['meta_keywords'] ?? '')
        ];

        $uploader = new ImageUploader();

        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $data['image_url'] = $uploader->upload($_FILES['image_url'], 'categories/featured');
        }
        if (isset($_FILES['thumbnail_url']) && $_FILES['thumbnail_url']['error'] === UPLOAD_ERR_OK) {
            $data['thumbnail_url'] = $uploader->upload($_FILES['thumbnail_url'], 'categories/thumbnails');
        }

        $id = Category::create($data);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

        header('Location: ' . url('categories'));
        exit();
    }

    /**
     * Show the form for editing a specific category.
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
     */
    public function update($id)
    {
        if (!verify_csrf_token()) {
            redirect_back_with_error('Invalid CSRF token.');
        }

        $category = Category::find($id);
        if (!$category) {
            redirect_back_with_error('دسته بندی پیدا نشد.');
        }

        $data = [
            'parent_id' => (int)$_POST['parent_id'] ?: null,
            'name_fa' => htmlspecialchars($_POST['name_fa']),
            'name_en' => htmlspecialchars($_POST['name_en'] ?? ''),
            'slug' => htmlspecialchars($_POST['slug']),
            'status' => $_POST['status'] ?? 'active',
            'position' => (int)($_POST['position'] ?? 0),
            'short_description' => htmlspecialchars($_POST['short_description'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'meta_title' => htmlspecialchars($_POST['meta_title'] ?? ''),
            'meta_description' => htmlspecialchars($_POST['meta_description'] ?? ''),
            'meta_keywords' => htmlspecialchars($_POST['meta_keywords'] ?? '')
        ];

        $uploader = new ImageUploader();

        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            // Optionally delete old image
            if (!empty($category->image_url)) {
                @unlink(PROJECT_ROOT . '/public' . $category->image_url);
            }
            $data['image_url'] = $uploader->upload($_FILES['image_url'], 'categories/featured');
        }
        if (isset($_FILES['thumbnail_url']) && $_FILES['thumbnail_url']['error'] === UPLOAD_ERR_OK) {
             // Optionally delete old image
            if (!empty($category->thumbnail_url)) {
                @unlink(PROJECT_ROOT . '/public' . $category->thumbnail_url);
            }
            $data['thumbnail_url'] = $uploader->upload($_FILES['thumbnail_url'], 'categories/thumbnails');
        }

        Category::update($id, $data);

        // Sync custom fields
        $customFieldIds = $_POST['custom_fields'] ?? [];
        Category::syncCustomFields($id, $customFieldIds);

        header('Location: ' . url('categories'));
        exit();
    }

    /**
     * Delete an image for a category.
     */
    public function deleteImage($id, $type)
    {
        header('Content-Type: application/json');

        $category = Category::find($id);
        if (!$category) {
            echo json_encode(['success' => false, 'message' => 'دسته بندی یافت نشد.']);
            return;
        }

        $field = ($type === 'thumbnail') ? 'thumbnail_url' : 'image_url';

        if (!empty($category->$field)) {
            // Delete the physical file
            @unlink(PROJECT_ROOT . '/public' . $category->$field);

            // Update the database record
            Category::update($id, [$field => null]);

            echo json_encode(['success' => true, 'message' => 'تصویر با موفقیت حذف شد.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'تصویری برای حذف وجود ندارد.']);
        }
    }

    /**
     * Delete a category.
     */
    public function delete($id)
    {
        $category = Category::find($id);
        if($category){
            if (!empty($category->image_url)) {
                @unlink(PROJECT_ROOT . '/public' . $category->image_url);
            }
            if (!empty($category->thumbnail_url)) {
                @unlink(PROJECT_ROOT . '/public' . $category->thumbnail_url);
            }
            Category::delete($id);
        }
        header('Location: ' . url('categories'));
        exit();
    }

    /**
     * Reorder categories based on a nested structure.
     * Expects a JSON payload from a Nestable.js-like interface.
     */
    public function reorder()
    {
        header('Content-Type: application/json');

        $input = file_get_contents('php://input');
        $orderData = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($orderData)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
            return;
        }

        try {
            Category::updateOrder($orderData);
            echo json_encode(['success' => true, 'message' => 'ترتیب دسته‌بندی‌ها با موفقیت به‌روزرسانی شد.']);
        } catch (\Exception $e) {
            // In a real app, you would log this error.
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا در به‌روزرسانی ترتیب: ' . $e->getMessage()]);
        }
    }
}
