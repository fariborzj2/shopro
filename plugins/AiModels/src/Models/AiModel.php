<?php

namespace App\Plugins\AiModels\Models;

use App\Core\Database;
use PDO;

class AiModel
{
    // Use a hardcoded key if APP_KEY is not available.
    // In production, this should be in config.
    private static $encryptionKey = 'AiModelsPluginSecretKey_ChangeMeInProd!';

    public static function findAll()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ai_models ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ai_models WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::getConnection();
        
        $encryptedKey = self::encrypt($data['api_key']);

        $stmt = $db->prepare("INSERT INTO ai_models (name_fa, name_en, api_key, description, is_active) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name_fa'],
            $data['name_en'],
            $encryptedKey,
            $data['description'] ?? null,
            isset($data['is_active']) ? 1 : 0
        ]);
    }

    public static function update($id, $data)
    {
        $db = Database::getConnection();
        
        $fields = [
            'name_fa = ?',
            'name_en = ?',
            'description = ?',
            'is_active = ?'
        ];
        $params = [
            $data['name_fa'],
            $data['name_en'],
            $data['description'] ?? null,
            isset($data['is_active']) ? 1 : 0
        ];

        if (!empty($data['api_key'])) {
            $fields[] = 'api_key = ?';
            $params[] = self::encrypt($data['api_key']);
        }

        $params[] = $id;
        
        $sql = "UPDATE ai_models SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM ai_models WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- Encryption Helpers ---

    private static function getKey()
    {
        // Try to use a secure key from config if available, otherwise fallback
        return defined('APP_KEY') ? APP_KEY : self::$encryptionKey;
    }

    public static function encrypt($value)
    {
        $key = self::getKey();
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($value)
    {
        $key = self::getKey();
        $data = base64_decode($value);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
