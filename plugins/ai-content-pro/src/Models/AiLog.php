<?php

namespace AiContentPro\Models;

use App\Core\Database;

class AiLog {
    public static function log($level, $message, $context = []) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO ai_cp_logs (`level`, `message`, `context`, `created_at`) VALUES (?, ?, ?, NOW())");
            $stmt->execute([
                $level,
                $message,
                json_encode($context, JSON_UNESCAPED_UNICODE)
            ]);
        } catch (\Exception $e) {
            // Last resort logging
            error_log("AiLog Failed: " . $e->getMessage());
        }
    }

    public static function error($message, $context = []) {
        self::log('error', $message, $context);
    }

    public static function info($message, $context = []) {
        self::log('info', $message, $context);
    }
}
