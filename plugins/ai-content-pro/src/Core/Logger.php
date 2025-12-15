<?php

namespace AiContentPro\Core;

use App\Core\Database;

class Logger
{
    public static function info($message, $context = [])
    {
        self::log('info', $message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::log('warning', $message, $context);
    }

    public static function error($message, $context = [])
    {
        self::log('error', $message, $context);
    }

    private static function log($level, $message, $context = [])
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO ai_cp_logs (level, message, context) VALUES (?, ?, ?)");
            $stmt->execute([
                $level,
                $message,
                json_encode($context, JSON_UNESCAPED_UNICODE)
            ]);
        } catch (\Exception $e) {
            // Fallback to file log if DB fails
            error_log("[AI-CP] {$level}: {$message} " . json_encode($context));
        }
    }
}
