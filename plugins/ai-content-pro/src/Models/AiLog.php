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

    public static function getPaginated($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_cp_logs ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countAll() {
        $db = Database::getConnection();
        return $db->query("SELECT COUNT(*) FROM ai_cp_logs")->fetchColumn();
    }

    public static function clear() {
        $db = Database::getConnection();
        return $db->exec("TRUNCATE TABLE ai_cp_logs");
    }
}
