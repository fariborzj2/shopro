<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiCalendar;
use App\Core\Request;

class CalendarController extends BaseController {

    public function index() {
        $items = AiCalendar::getAll();

        $this->view('calendar', [
            'items' => $items
        ], 'تقویم محتوایی هوشمند');
    }

    public function delete($id) {
        AiCalendar::delete($id);
        redirect_with_success('/admin/ai-content-pro/calendar', 'آیتم تقویم با موفقیت حذف شد.');
    }
}
