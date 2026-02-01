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

    public static function getPaginated($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_cp_jobs ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countAll() {
        $db = Database::getConnection();
        return $db->query("SELECT COUNT(*) FROM ai_cp_jobs")->fetchColumn();
    }

    public static function getStats() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM ai_cp_jobs GROUP BY status");
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
