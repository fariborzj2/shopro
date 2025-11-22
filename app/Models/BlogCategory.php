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

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM blog_categories WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $sql = "INSERT INTO blog_categories (parent_id, name_fa, name_en, slug, status, position)
                VALUES (:parent_id, :name_fa, :name_en, :slug, :status, :position)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'parent_id' => !empty($data['parent_id']) ? $data['parent_id'] : null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'] ?? null,
            'slug' => $data['slug'],
            'status' => $data['status'] ?? 'active',
            'position' => $data['position'] ?? 0
        ]);
        return Database::getConnection()->lastInsertId();
    }

    public static function update($id, $data)
    {
        $sql = "UPDATE blog_categories SET
                parent_id = :parent_id,
                name_fa = :name_fa,
                name_en = :name_en,
                slug = :slug,
                status = :status,
                position = :position
                WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'parent_id' => !empty($data['parent_id']) ? $data['parent_id'] : null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'] ?? null,
            'slug' => $data['slug'],
            'status' => $data['status'] ?? 'active',
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM blog_categories WHERE id = :id", ['id' => $id]);
        return true;
    }
}
