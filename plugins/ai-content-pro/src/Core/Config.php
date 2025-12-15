<?php

namespace AiContentPro\Core;

use App\Core\Database;

class Config
{
    private static $cache = null;

    public static function get($key, $default = null)
    {
        if (self::$cache === null) {
            self::load();
        }
        return self::$cache[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $db = Database::getConnection();

        // Determine module based on key prefix
        $parts = explode('_', $key);
        $module = $parts[0] ?? 'global';

        $stmt = $db->prepare("INSERT INTO ai_cp_settings (`key`, `value`, `module`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = ?");
        $stmt->execute([$key, $value, $module, $value]);

        self::$cache[$key] = $value;
    }

    public static function all()
    {
        if (self::$cache === null) {
            self::load();
        }
        return self::$cache;
    }

    private static function load()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT `key`, `value` FROM ai_cp_settings");
            self::$cache = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\PDOException $e) {
            // Fallback if table doesn't exist yet (e.g. during install)
            self::$cache = [];
        }
    }
}
