<?php

namespace App\Models;

use App\Core\Database;

class OtpCode
{
    protected static $table = 'otp_codes';

    /**
     * Create a new OTP record.
     *
     * @param string $mobile
     * @param string $otp_hash
     * @param string $expires_at
     * @return int
     */
    public static function create($mobile, $otp_hash, $expires_at)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO " . self::$table . " (mobile, otp_hash, expires_at) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$mobile, $otp_hash, $expires_at]);
        return $db->lastInsertId();
    }

    /**
     * Find the latest valid OTP for a mobile number.
     *
     * @param string $mobile
     * @return object|false
     */
    public static function findLatest($mobile)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM " . self::$table . "
                WHERE mobile = ? AND is_used = FALSE AND expires_at > NOW()
                ORDER BY created_at DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$mobile]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Mark an OTP as used.
     *
     * @param int $id
     * @return bool
     */
    public static function markAsUsed($id)
    {
        $db = Database::getConnection();
        $sql = "UPDATE " . self::$table . " SET is_used = TRUE WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Count recent OTP requests for rate limiting.
     *
     * @param string $mobile
     * @param int $minutes
     * @return int
     */
    public static function countRecent($mobile, $minutes = 30)
    {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM " . self::$table . "
                WHERE mobile = ? AND created_at > NOW() - INTERVAL ? MINUTE";
        $stmt = $db->prepare($sql);
        $stmt->execute([$mobile, $minutes]);
        return (int) $stmt->fetchColumn();
    }
}
