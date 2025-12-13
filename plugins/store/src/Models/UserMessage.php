<?php

namespace Store\Models;

use App\Core\Database;
use PDO;

class UserMessage
{
    public static function findAllByUserId($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user_messages WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countUnread($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_messages WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user_messages WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function markAsRead($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE user_messages SET is_read = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
