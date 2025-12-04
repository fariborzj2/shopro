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
            // Manual render with layout
            ob_start();
            extract(['settings' => $settings]);
            include $viewPath;
            $content = ob_get_clean();

            // This assumes the core view() helper isn't used, OR we wrap it in the main layout.
            // But we can't easily access main layout file directly from here without copy-paste or assuming path.
            // Best approach: Use the core Template engine if possible, or include the layout files.

            // Hack: We can use the global view() helper if we temporarily copy the view to views/tmp? No.
            // Better: Load the main layout and inject content.

            // Assuming views/layouts/main.php exists (from Memory)
            $layoutPath = PROJECT_ROOT . '/views/layouts/main.php';
            if (file_exists($layoutPath)) {
                // Determine user (admin) for the layout
                // $admin = ... (already in session)
                $page_title = 'تنظیمات SeoPilot';
                // view() helper expects $view to be a filename relative to views/.
                // We can't use it for plugin views.

                // So we manually include layout.
                // We need to match variables expected by layout.
                $admin = \App\Models\Admin::find($_SESSION['admin_id']);
                // Layout expects $content variable? Or yields?
                // Standard PHP layout usually echoes $content.

                include $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo "View not found: $viewPath";
        }
    }

    public function saveSettings()
    {
        if (!verify_csrf_token()) {
            die("CSRF Error");
        }

        $data = Request::all();
        $settings = [
            'separator' => $data['separator'] ?? '|',
            'site_type' => $data['site_type'] ?? 'organization',
            'ai_auto_meta' => isset($data['ai_auto_meta']),
            'sitemap_enabled' => isset($data['sitemap_enabled']),
            'analysis_strictness' => $data['analysis_strictness'] ?? 'normal'
        ];

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO seopilot_options (option_name, option_value) VALUES ('settings', :val) ON DUPLICATE KEY UPDATE option_value = :val");
        $stmt->execute([':val' => json_encode($settings)]);

        redirect_with_success('/admin/seopilot/settings', 'تنظیمات با موفقیت ذخیره شد.');
    }
}
