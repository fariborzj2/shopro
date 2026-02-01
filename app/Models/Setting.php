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
     * Get the active TinyMCE API key, rotating it if the interval has passed.
     *
     * @return string
     */
    public static function getTinyMceApiKey()
    {
        $settings = self::getAll();
        $keys_str = $settings['tinymce_api_keys'] ?? '';

        if (empty($keys_str) || trim($keys_str) === 'no-api-key') {
            return 'no-api-key';
        }

        $keys = array_values(array_filter(array_map('trim', explode("\n", $keys_str))));
        if (empty($keys)) {
            return 'no-api-key';
        }

        $count = count($keys);
        $interval_hours = (int)($settings['tinymce_rotation_interval'] ?? 24);
        if ($interval_hours <= 0) $interval_hours = 24;

        $current_index = (int)($settings['tinymce_current_key_index'] ?? 0);
        $last_rotation = (int)($settings['tinymce_last_rotation'] ?? 0);

        // Check if it's time to rotate
        if (time() - $last_rotation >= $interval_hours * 3600) {
            $new_index = ($current_index + 1) % $count;
            $new_rotation_time = time();

            // Save new state (this will also invalidate the cache)
            self::updateBatch([
                'tinymce_current_key_index' => $new_index,
                'tinymce_last_rotation' => $new_rotation_time
            ]);

            return $keys[$new_index];
        }

        // Return current key (ensure index is valid)
        $actual_index = $current_index % $count;
        return $keys[$actual_index];
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

            // Invalidate cache
            try {
                Cache::getInstance()->invalidateTag('config');
            } catch (\Exception $e) {
                // Ignore cache errors
            }

            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Failed to update settings: " . $e->getMessage());
            return false;
        }
    }
}
