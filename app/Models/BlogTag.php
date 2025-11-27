<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BlogTag
{
    public static function findAll()
    {
        $stmt = Database::query("SELECT * FROM blog_tags ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAllWithCount()
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as posts_count
                FROM blog_tags t
                LEFT JOIN blog_post_tags pt ON t.id = pt.tag_id
                GROUP BY t.id
                ORDER BY t.name ASC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM blog_tags WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findBy($column, $value)
    {
        $stmt = Database::query("SELECT * FROM blog_tags WHERE $column = :value", ['value' => $value]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function create($data)
    {
        $sql = "INSERT INTO blog_tags (name, slug, status) VALUES (:name, :slug, :status)";
        Database::query($sql, $data);
        return true;
    }

    public static function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE blog_tags SET name = :name, slug = :slug, status = :status WHERE id = :id";
        Database::query($sql, $data);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM blog_tags WHERE id = :id", ['id' => $id]);
        return true;
    }
}
