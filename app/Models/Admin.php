<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Admin
{
    /**
     * Get all admins from the database.
     *
     * @return array
     */
    public static function all()
    {
        $stmt = Database::query("SELECT id, username, name, email, role, status, created_at, last_login FROM admins ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find an admin by their username.
     *
     * @param string $username
     * @return mixed
     */
    public static function findByUsername($username)
    {
        $stmt = Database::query("SELECT * FROM admins WHERE username = :username", ['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new admin user.
     *
     * @param array $data
     * @return string|false The ID of the newly created admin, or false on failure.
     */
    public static function create(array $data)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO admins (username, password_hash, email, name, role, status)
                VALUES (:username, :password_hash, :email, :name, :role, :status)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':password_hash' => $data['password'],
            ':email' => $data['email'],
            ':name' => $data['name'] ?? $data['username'],
            ':role' => $data['role'] ?? 'admin',
            ':status' => $data['status'] ?? 'active'
        ]);
        return $db->lastInsertId();
    }
}
