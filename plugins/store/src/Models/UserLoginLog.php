<?php

namespace Store\Models;

use App\Core\Database;
use PDO;

class UserLoginLog
{
    public static function log($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO user_login_logs (user_id, ip_address, user_agent) VALUES (:user_id, :ip, :ua)");
        return $stmt->execute([
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'ua' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
    }

    public static function findAllByUserId($userId, $limit = 50)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user_login_logs WHERE user_id = :user_id ORDER BY login_time DESC LIMIT :limit");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
