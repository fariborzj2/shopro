<?php

namespace App\Plugins\AiNews\Models;

use App\Core\Database;
use PDO;

class AiLog
{
    public static function log($status, $fetched = 0, $created = 0, $details = '', $error = null)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO ai_news_logs (status, fetched_count, created_count, details, error_message)
            VALUES (:status, :fetched, :created, :details, :error)
        ");
        $stmt->execute([
            'status' => $status,
            'fetched' => $fetched,
            'created' => $created,
            'details' => $details,
            'error' => $error
        ]);
    }

    public static function getRecent($limit = 50)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM ai_news_logs ORDER BY run_time DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
