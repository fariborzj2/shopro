<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BlogCategory
{
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM blog_categories ORDER BY name_fa ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findBy($column, $value)
    {
        // Whitelist columns to prevent SQL injection
        $allowed_columns = ['id', 'slug'];
        if (!in_array($column, $allowed_columns)) {
            throw new \Exception("Invalid column name: $column");
        }
        $stmt = Database::query("SELECT * FROM blog_categories WHERE `$column` = :value", ['value' => $value]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function findAllBy($column, $value)
    {
        // Whitelist columns to prevent SQL injection
        $allowed_columns = ['status'];
        if (!in_array($column, $allowed_columns)) {
            throw new \Exception("Invalid column name: $column");
        }
        $stmt = Database::query("SELECT * FROM blog_categories WHERE `$column` = :value", ['value' => $value]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
