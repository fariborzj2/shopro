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
        $stmt = Database::query("SELECT id, name FROM admins ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
