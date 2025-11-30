<?php

namespace App\Plugins\AiNews\Models;

use App\Core\Database;
use PDO;

class AiSetting
{
    public static function get($key, $default = null)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT setting_value FROM ai_news_settings WHERE setting_key = :key");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['setting_value'] : $default;
    }

    public static function set($key, $value)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO ai_news_settings (setting_key, setting_value)
            VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE setting_value = :value_update
        ");
        $stmt->execute([
            'key' => $key,
            'value' => $value,
            'value_update' => $value
        ]);
    }

    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM ai_news_settings");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
