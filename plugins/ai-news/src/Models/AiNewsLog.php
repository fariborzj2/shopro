<?php

namespace AiNews\Models;

use App\Core\Database;

class AiNewsLog {
    public static function log($level, $message, $context = []) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO ai_news_logs (level, message, context) VALUES (?, ?, ?)");
            $stmt->execute([$level, $message, json_encode($context)]);
        } catch (\Exception $e) {
            error_log("AiNewsLog failed: " . $e->getMessage());
        }
    }

    public static function info($message, $context = []) { self::log('info', $message, $context); }
    public static function warning($message, $context = []) { self::log('warning', $message, $context); }
    public static function error($message, $context = []) { self::log('error', $message, $context); }

    public static function getLatest($limit = 50) {
        try {
            $db = Database::getConnection();
            return $db->query("SELECT * FROM ai_news_logs ORDER BY created_at DESC LIMIT $limit")->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Fallback if created_at is missing
            try {
                return $db->query("SELECT * FROM ai_news_logs ORDER BY id DESC LIMIT $limit")->fetchAll(\PDO::FETCH_ASSOC);
            } catch (\Exception $e2) {
                return [];
            }
        }
    }
}
