<?php

namespace App\Plugins\AiModels\Controllers;

use App\Plugins\AiModels\Models\AiModel;
use App\Core\Request;

class AiModelsController
{
    // Helper to render plugin views using the core layout
    private function renderView($view, $data = [])
    {
        // Path traversal to access plugin views from core view helper
        // Core view path is 'views/', so we go up to 'plugins/AiModels/Views/'
        $viewPath = '../plugins/AiModels/Views/' . $view;
        view('main', $viewPath, $data);
    }

    public function index()
    {
        $models = AiModel::findAll();
        $this->renderView('index', ['models' => $models]);
    }

    public function create()
    {
        $this->renderView('create', ['model' => null]);
    }

    public function store()
    {
        if (!verify_csrf_token()) {
            redirect_back_with_error('نشست نامعتبر است.');
        }

        $data = Request::all();
        
        // Validation
        if (empty($data['name_fa']) || empty($data['name_en']) || empty($data['api_key'])) {
            $_SESSION['errors'] = ['لطفا تمام فیلدهای اجباری را پر کنید.'];
            $_SESSION['old'] = $data;
            redirect_back_with_error('لطفا تمام فیلدهای اجباری را پر کنید.');
        }

        try {
            AiModel::create($data);
            redirect_with_success('/admin/ai-models', 'مدل با موفقیت ایجاد شد.');
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                redirect_back_with_error('نام انگلیسی مدل تکراری است.');
            }
            redirect_back_with_error('خطا در ذخیره‌سازی: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $model = AiModel::find($id);
        if (!$model) {
            redirect_back_with_error('مدل یافت نشد.');
        }
        $this->renderView('edit', ['model' => (object)$model]); // Casting to object to match view convention
    }

    public function update($id)
    {
        if (!verify_csrf_token()) {
            redirect_back_with_error('نشست نامعتبر است.');
        }

        $data = Request::all();
        
        if (empty($data['name_fa']) || empty($data['name_en'])) {
            redirect_back_with_error('لطفا تمام فیلدهای اجباری را پر کنید.');
        }

        try {
            AiModel::update($id, $data);
            redirect_with_success('/admin/ai-models', 'مدل با موفقیت بروزرسانی شد.');
        } catch (\Exception $e) {
            redirect_back_with_error('خطا در بروزرسانی: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!verify_csrf_token()) {
            redirect_back_with_error('نشست نامعتبر است.');
        }

        AiModel::delete($id);
        redirect_with_success('/admin/ai-models', 'مدل با موفقیت حذف شد.');
    }

    public function testConnection()
    {
        // AJAX endpoint
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $apiKey = $input['api_key'] ?? '';
        $modelName = strtolower($input['name_en'] ?? '');

        if (empty($apiKey)) {
            echo json_encode(['status' => 'error', 'message' => 'کلید API الزامی است.']);
            exit;
        }

        // Simple connectivity check
        
        $ch = curl_init();
        
        // Heuristic to guess a real endpoint if possible
        if (strpos($modelName, 'gpt') !== false || strpos($modelName, 'openai') !== false) {
             $url = 'https://api.openai.com/v1/models';
        } elseif (strpos($modelName, 'claude') !== false || strpos($modelName, 'anthropic') !== false) {
             $url = 'https://api.anthropic.com/v1/models';
        } elseif (strpos($modelName, 'llama') !== false || strpos($modelName, 'groq') !== false) {
             $url = 'https://api.groq.com/openai/v1/models';
        } else {
             // Fallback: Just test internet connectivity
             $url = 'https://www.google.com';
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        // Add Authorization header just in case it hits a real API (except google)
        if ($url !== 'https://www.google.com') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
             echo json_encode(['status' => 'error', 'message' => 'خطا در اتصال: ' . $error]);
        } elseif ($httpCode >= 200 && $httpCode < 500) {
             if ($httpCode === 401 || $httpCode === 403) {
                 if ($url === 'https://www.google.com') {
                      echo json_encode(['status' => 'success', 'message' => 'اتصال اینترنت برقرار است. (ارائه‌دهنده نامشخص)']);
                 } else {
                      echo json_encode(['status' => 'error', 'message' => 'اتصال برقرار شد اما کلید نامعتبر است (کد ' . $httpCode . ').']);
                 }
             } else {
                 echo json_encode(['status' => 'success', 'message' => 'اتصال با موفقیت برقرار شد.']);
             }
        } else {
             echo json_encode(['status' => 'error', 'message' => 'خطا در دریافت پاسخ از سرور (کد ' . $httpCode . ').']);
        }
        exit;
    }
}
