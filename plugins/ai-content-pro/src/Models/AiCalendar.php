<?php

namespace AiContentPro\Models;

use App\Core\Database;

class AiCalendar {
    public static function create($title, $dueDate, $type = 'blog') {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_cp_calendar (`title`, `due_date`, `content_type`, `status`, `created_at`) VALUES (?, ?, ?, 'planned', NOW())");
        $stmt->execute([$title, $dueDate, $type]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ai_cp_calendar ORDER BY due_date ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
