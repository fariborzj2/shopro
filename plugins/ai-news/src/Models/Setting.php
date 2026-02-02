<?php

namespace AiNews\Models;

use App\Core\Database;

class Setting {
    public static function getAll() {
        $db = Database::getConnection();
        $results = $db->query("SELECT * FROM ai_news_settings")->fetchAll(\PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public static function get($key, $default = null) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM ai_news_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        return $value !== false ? $value : $default;
    }

    public static function set($key, $value) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_news_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    }

    public static function updateBatch($data) {
        foreach ($data as $key => $value) {
            self::set($key, $value);
        }
    }
}
