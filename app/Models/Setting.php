<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Setting
{
    /**
     * Get all settings from the database as an associative array.
     *
     * @return array
     */
    public static function getAll()
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
                ON DUPLICATE KEY UPDATE setting_value = :value";

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->execute(['key' => $key, 'value' => $value]);
            }

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            // In a real app, you would log the error
            error_log("Failed to update settings: " . $e->getMessage());
            return false;
        }
    }
}
