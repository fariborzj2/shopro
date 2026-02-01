<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiJob;
use AiContentPro\Models\AiLog;
use App\Core\Request;

class DashboardController extends BaseController {

    public function index() {
        $page = (int) (Request::all()['page'] ?? 1);
        $limit = 10;

        $jobs = AiJob::getPaginated($page, $limit);
        $totalJobs = AiJob::countAll();
        $stats = AiJob::getStats();

        // Paginator usually expects a core class, but we can mock it or just pass values
        $paginator = [
            'current_page' => $page,
            'total_pages' => ceil($totalJobs / $limit),
            'total_items' => $totalJobs
        ];

        $this->view('dashboard', [
            'jobs' => $jobs,
            'stats' => $stats,
            'paginator' => (object) $paginator
        ], 'داشبورد هوش مصنوعی');
    }
}
