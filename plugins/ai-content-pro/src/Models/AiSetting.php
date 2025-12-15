<?php

namespace AiContentPro\Models;

use App\Core\Database;

class AiSetting {
    private static $cache = null;

    public static function getAll() {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ai_cp_settings");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        self::$cache = $settings;
        return $settings;
    }

    public static function get($key, $default = null) {
        $all = self::getAll();
        return $all[$key] ?? $default;
    }

    public static function set($key, $value) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_cp_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        $stmt->execute([$key, $value]);

        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }

    public static function updateBatch($data) {
        foreach ($data as $key => $value) {
            self::set($key, $value);
        }
    }
}
