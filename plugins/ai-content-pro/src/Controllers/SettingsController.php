<?php

namespace AiContentPro\Controllers;

use AiContentPro\Core\Config;
use App\Core\Database;

class SettingsController
{
    public function index()
    {
        // Check permissions
        $this->checkPermission();

        $settings = Config::all();

        // Load view
        // Using manual require as per plugin architecture
        extract(['settings' => $settings]);
        require_once PROJECT_ROOT . '/plugins/ai-content-pro/views/settings.php';
    }

    public function save()
    {
        $this->checkPermission();

        // Verify CSRF
        if (!verify_csrf_token()) {
             redirect_back_with_error('نشست نامعتبر است.');
             return;
        }

        $data = $_POST['settings'] ?? [];

        foreach ($data as $key => $value) {
            Config::set($key, $value);
        }

        // Handle checkboxes (boolean flags)
        $checkboxes = ['content_enabled', 'seo_enabled', 'comments_enabled', 'queue_enabled', 'seo_schema_enabled'];
        foreach ($checkboxes as $chk) {
            if (!isset($data[$chk])) {
                 Config::set($chk, '0');
            }
        }

        redirect_with_success('/admin/ai-content/settings', 'تنظیمات با موفقیت ذخیره شد.');
    }

    public function logs()
    {
        $this->checkPermission();

        $db = Database::getConnection();
        $logs = $db->query("SELECT * FROM ai_cp_logs ORDER BY created_at DESC LIMIT 100")->fetchAll(\PDO::FETCH_ASSOC);

        extract(['logs' => $logs]);
        require_once PROJECT_ROOT . '/plugins/ai-content-pro/views/logs.php';
    }

    private function checkPermission()
    {
        // Simple check, real app uses Auth Middleware which we hooked into via index.php logic or global middleware
        // But for double safety:
        if (!isset($_SESSION['admin_id'])) {
             header('Location: /admin/login');
             exit;
        }
    }
}
