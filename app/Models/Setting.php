<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Cache;
use PDO;

class Setting
{
    /**
     * Get all settings from the database as an associative array.
     *
     * @param bool $useCache Whether to attempt to fetch from cache.
     * @return array
     */
    public static function getAll($useCache = true)
    {
        // Avoid cache if explicitly requested or if Cache is not ready (to prevent circular dependency during boot)
        if ($useCache) {
            try {
                // We use a closure here so the Cache class can lazily call it
                return Cache::getInstance()->remember('settings_all', 3600, function () {
                    return self::fetchFromDb();
                }, ['config']);
            } catch (\Exception $e) {
                // If Cache fails (e.g. during Cache::__construct), fallback to DB
                return self::fetchFromDb();
            }
        }

        return self::fetchFromDb();
    }

    /**
     * Internal method to fetch from DB.
     */
    private static function fetchFromDb()
    {
        $stmt = Database::query("SELECT * FROM settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $settings ?: [];
    }

    /**
     * Update a batch of settings in the database.
     *
     * @param array $data
     * @return bool
     */
    public static function updateBatch(array $data)
    {
        $pdo = Database::getConnection();

        // Using INSERT ... ON DUPLICATE KEY UPDATE for efficiency
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :update_value";

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);

            foreach ($data as $key => $value) {
                // Unique parameter names to avoid conflicts in some PDO drivers
                $stmt->execute(['key' => $key, 'value' => $value, 'update_value' => $value]);
            }

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Failed to update settings: " . $e->getMessage());
            return false;
        }
    }
}
