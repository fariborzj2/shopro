<?php

namespace AiContentPro\Models;

use App\Core\Database;

class AiJob {
    public static function create($type, $payload) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_cp_jobs (`type`, `payload`, `status`, `created_at`) VALUES (?, ?, 'pending', NOW())");
        $stmt->execute([$type, json_encode($payload, JSON_UNESCAPED_UNICODE)]);
        return $db->lastInsertId();
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_cp_jobs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getPending($limit = 5) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_cp_jobs WHERE status = 'pending' ORDER BY created_at ASC LIMIT ?");
        // LIMIT in prepared statement can be tricky in some PDO drivers, casting to int or binding
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function updateStatus($id, $status, $result = null, $errorMessage = null) {
        $db = Database::getConnection();
        $sql = "UPDATE ai_cp_jobs SET status = ?, updated_at = NOW()";
        $params = [$status];

        if ($result !== null) {
            $sql .= ", result = ?";
            $params[] = json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        if ($errorMessage !== null) {
            $sql .= ", error_message = ?";
            $params[] = $errorMessage;
        }
        if ($status === 'failed') {
            $sql .= ", attempts = attempts + 1";
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }
}
