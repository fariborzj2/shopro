<?php

namespace AiNews\Controllers;

use AiNews\Models\AiNewsHistory;
use AiNews\Models\AiNewsLog;
use AiNews\Models\Source;
use AiNews\Models\Setting;

class DashboardController extends BaseController {
    public function index() {
        $stats = AiNewsHistory::getStats();
        $logs = AiNewsLog::getLatest(10);
        $sourcesCount = count(Source::getActive());
        $settings = Setting::getAll();

        $this->view('dashboard', [
            'stats' => $stats,
            'logs' => $logs,
            'sourcesCount' => $sourcesCount,
            'settings' => $settings
        ], 'داشبورد اتوماسیون محتوا');
    }
}
