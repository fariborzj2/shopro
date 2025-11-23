<?php

namespace App\Controllers\Admin;

use App\Models\BlogTag;
use App\Core\Database;
use PDO;

class BlogTagsController
{
    public function index()
    {
        $tags = BlogTag::findAll();
        // If it's an AJAX request, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($tags);
            exit;
        }
        // Otherwise load the view
        // Note: Using 'main' layout as per other controllers seen previously
        return view('main', 'blog/tags/index', [
            'title' => 'مدیریت برچسب‌ها',
            'tags' => $tags
        ]);
    }

    public function store()
    {
        // Read JSON input if available
        $input = file_get_contents('php://input');
        $jsonData = json_decode($input, true);

        if ($jsonData) {
            $_POST = $jsonData;
        }

        // Validation
        if (empty($_POST['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نام برچسب الزامی است.']);
            exit;
        }

        $data = [
            'name' => htmlspecialchars($_POST['name']),
            'slug' => htmlspecialchars($_POST['name']), // Use Name as Slug per user request
            'status' => $_POST['status'] ?? 'active',
        ];

        try {
            BlogTag::create($data);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'برچسب با موفقیت ایجاد شد.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا در ایجاد برچسب: ' . $e->getMessage()]);
        }
        exit;
    }

    public function update($id)
    {
        $input = file_get_contents('php://input');
        $jsonData = json_decode($input, true);

        if ($jsonData) {
            $_POST = $jsonData;
        }

        if (empty($_POST['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نام برچسب الزامی است.']);
            exit;
        }

        $data = [
            'name' => htmlspecialchars($_POST['name']),
            'slug' => htmlspecialchars($_POST['name']), // Use Name as Slug per user request
            'status' => $_POST['status'] ?? 'active',
        ];

        try {
            BlogTag::update($id, $data);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'برچسب با موفقیت به‌روزرسانی شد.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا در به‌روزرسانی برچسب: ' . $e->getMessage()]);
        }
        exit;
    }

    public function destroy($id)
    {
        try {
            BlogTag::delete($id);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'برچسب حذف شد.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا در حذف برچسب: ' . $e->getMessage()]);
        }
        exit;
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        if (strlen($query) < 1) {
            echo json_encode([]);
            exit;
        }

        // Add a search method to BlogTag model if not exists, or use Database::query here
        $stmt = Database::query("SELECT * FROM blog_tags WHERE name LIKE :query LIMIT 10", ['query' => '%' . $query . '%']);
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($tags);
        exit;
    }
}
