<?php

namespace AiNews\Controllers;

use AiNews\Models\Setting;
use App\Core\Request;

class SettingsController extends BaseController {
    public function index() {
        $settings = Setting::getAll();
        $categories = \App\Models\BlogCategory::all();
        $this->view('settings/index', [
            'settings' => $settings,
            'categories' => $categories
        ], 'تنظیمات اتوماسیون');
    }

    public function update() {
        $data = Request::all();
        Setting::updateBatch($data);
        header('Location: /admin/ai-news/settings?success=1');
    }
}
