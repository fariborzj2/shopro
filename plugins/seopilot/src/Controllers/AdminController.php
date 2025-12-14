<?php

namespace SeoPilot\Enterprise\Controllers;

use App\Core\Database;
use App\Core\Request;

class AdminController
{
    public function index()
    {
        // Fetch Settings
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT option_value FROM seopilot_options WHERE option_name = 'settings'");
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $settings = $row ? json_decode($row['option_value'], true) : [];

        // Check view path.
        // Since we are in a plugin, we can't use view() helper easily unless we register the path
        // or load the file manually and inject into layout.

        $viewPath = dirname(__DIR__, 2) . '/views/admin/settings.php';

        if (file_exists($viewPath)) {
            $data = [
                'settings' => $settings,
                'page_title' => 'تنظیمات SeoPilot'
            ];

            // 1. Render the plugin's internal view to capture content
            extract($data);
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            // 2. Render the main layout with the captured content
            $data['content'] = $content;
            extract($data);

            $layoutPath = PROJECT_ROOT . '/views/layouts/main.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo "View not found: $viewPath";
        }
    }

    public function saveSettings()
    {
        $data = Request::all();
        $settings = [
            'separator' => $data['separator'] ?? '|',
            'site_type' => $data['site_type'] ?? 'organization',
            'ai_auto_meta' => isset($data['ai_auto_meta']),
            'sitemap_enabled' => isset($data['sitemap_enabled']),
            'analysis_strictness' => $data['analysis_strictness'] ?? 'normal'
        ];

        $json = json_encode($settings);
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO seopilot_options (option_name, option_value) VALUES ('settings', :val_insert) ON DUPLICATE KEY UPDATE option_value = :val_update");
        $stmt->execute([
            ':val_insert' => $json,
            ':val_update' => $json
        ]);

        redirect_with_success('/admin/seopilot/settings', 'تنظیمات با موفقیت ذخیره شد.');
    }
}
