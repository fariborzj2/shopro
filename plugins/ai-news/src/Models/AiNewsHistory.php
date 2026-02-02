<?php

namespace AiNews\Models;

use App\Core\Database;

class AiNewsHistory {
    public static function exists($url) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM ai_news_history WHERE original_url = ?");
        $stmt->execute([$url]);
        return (bool) $stmt->fetch();
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_news_history (source_id, original_url, content_hash, status, reason, post_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['source_id'],
            $data['original_url'],
            $data['content_hash'] ?? null,
            $data['status'] ?? 'pending',
            $data['reason'] ?? null,
            $data['post_id'] ?? null
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
        $fields = [];
        $values = [];
        foreach ($data as $key => $val) {
            $fields[] = "`$key` = ?";
            $values[] = $val;
        }
        $values[] = $id;
        $sql = "UPDATE ai_news_history SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function getStats() {
        $db = Database::getConnection();
        return $db->query("SELECT status, COUNT(*) as count FROM ai_news_history GROUP BY status")->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
