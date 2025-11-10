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
}
