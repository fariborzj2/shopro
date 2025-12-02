<?php

namespace App\Controllers\Admin;

use App\Core\Plugin\PluginManager;
use App\Core\Database;

class PluginsController
{
    public function index()
    {
        // Simple list view
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM plugins ORDER BY name ASC");
        $plugins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        view('admin/layouts/main', 'admin/plugins/index', ['plugins' => $plugins]);
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plugin_zip'])) {
            try {
                if ($_FILES['plugin_zip']['error'] === UPLOAD_ERR_OK) {
                    PluginManager::install($_FILES['plugin_zip']['tmp_name']);
                    redirect_with_success('/admin/plugins', 'پلاگین با موفقیت نصب شد.');
                } else {
                    throw new \Exception('خطا در آپلود فایل.');
                }
            } catch (\Exception $e) {
                redirect_back_with_error($e->getMessage());
            }
        }
    }

    public function activate($slug)
    {
        try {
            PluginManager::activate($slug);
            redirect_with_success('/admin/plugins', 'پلاگین فعال شد.');
        } catch (\Exception $e) {
            redirect_back_with_error($e->getMessage());
        }
    }

    public function deactivate($slug)
    {
        try {
            PluginManager::deactivate($slug);
            redirect_with_success('/admin/plugins', 'پلاگین غیرفعال شد.');
        } catch (\Exception $e) {
            redirect_back_with_error($e->getMessage());
        }
    }

    public function delete($slug)
    {
        try {
            PluginManager::uninstall($slug);
            redirect_with_success('/admin/plugins', 'پلاگین حذف شد.');
        } catch (\Exception $e) {
            redirect_back_with_error($e->getMessage());
        }
    }
}
