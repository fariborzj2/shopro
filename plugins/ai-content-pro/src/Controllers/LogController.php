<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiLog;
use App\Core\Request;

class LogController extends BaseController {

    public function index() {
        $page = (int) (Request::all()['page'] ?? 1);
        $limit = 20;

        $logs = AiLog::getPaginated($page, $limit);
        $total = AiLog::countAll();

        $paginator = [
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_items' => $total
        ];

        $this->view('logs', [
            'logs' => $logs,
            'paginator' => (object) $paginator
        ], 'گزارشات فنی هوش مصنوعی');
    }

    public function clear() {
        AiLog::clear();
        redirect_with_success('/admin/ai-content-pro/logs', 'تمامی گزارشات با موفقیت پاکسازی شدند.');
    }
}
