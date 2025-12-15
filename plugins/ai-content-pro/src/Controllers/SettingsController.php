<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiSetting;
use App\Core\Request;

class SettingsController {

    public function index() {
        // Load data
        $settings = AiSetting::getAll();

        // Render View to String
        ob_start();
        extract(['settings' => $settings]);
        $viewPath = __DIR__ . '/../../views/settings.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View not found: " . $viewPath;
        }
        $content = ob_get_clean();

        $title = 'تنظیمات AI Content Pro';
        if (file_exists(__DIR__ . '/../../../../views/layouts/main.php')) {
            require __DIR__ . '/../../../../views/layouts/main.php';
        } else {
            echo $content;
        }
    }

    public function update() {
        if (Request::method() !== 'POST') {
            return;
        }

        if (!verify_csrf_token()) {
             redirect_back_with_error('نشست نامعتبر است.');
             exit;
        }

        $data = Request::all();

        $knownKeys = [
            'gemini_api_key',
            'enable_content_gen',
            'enable_faq_gen',
            'enable_image_gen',
            'enable_internal_links',
            'enable_seo',
            'enable_comments',
            'enable_calendar',
            'model_content',
            'max_tokens_content',
            'seo_title_length',
            'seo_desc_length',
            'queue_retry_limit'
        ];

        $updateData = [];
        foreach ($knownKeys as $key) {
            if (isset($data[$key])) {
                $updateData[$key] = $data[$key];
            } else {
                if (strpos($key, 'enable_') === 0) {
                     $updateData[$key] = '0';
                }
            }
        }

        AiSetting::updateBatch($updateData);

        redirect_with_success('/admin/ai-content-pro/settings', 'تنظیمات با موفقیت ذخیره شد.');
        exit;
    }
}
