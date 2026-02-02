<?php

namespace AiNews\Models;

use App\Core\Database;

class Source {
    public static function all() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM ai_news_sources ORDER BY created_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_news_sources WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_news_sources (name, url, type, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['url'],
            $data['type'] ?? 'rss',
            $data['status'] ?? 'active'
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE ai_news_sources SET name = ?, url = ?, type = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['url'],
            $data['type'],
            $data['status'],
            $id
        ]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM ai_news_sources WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getActive() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM ai_news_sources WHERE status = 'active'")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function updateLastCrawled($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE ai_news_sources SET last_crawled_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
